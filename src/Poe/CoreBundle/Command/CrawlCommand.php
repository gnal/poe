<?php

namespace Poe\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\CssSelector\CssSelector;

class CrawlCommand extends ContainerAwareCommand
{
    private $parentTypes = [
        'Shield' => 'Chance to Block',
        '1h Sword' => 'One Handed Sword',
        '2h Sword' => 'Two Handed Sword',
        '1h Axe' => 'One Handed Axe',
        '2h Axe' => 'Two Handed Axe',
        '1h Mace' => 'One Handed Mace',
        '2h Mace' => 'Two Handed Mace',
        'Bow' => 'Bow',
        'Wand' => 'Wand',
        'Dagger' => 'Dagger',
        'Staff' => 'Staff',
        'Claw' => 'Claw',
    ];

    private $bootsKeywords = [
        'Boots',
        'Greaves',
        'Slippers',
        'Shoes',
    ];

    private $gloveKeywords = [
        'Gloves',
        'Gauntlets',
        'Mitts',
    ];

    private $helmetKeywords = [
        'Casque',
        'Hood',
        'Coif',
        'Helmet',
        'Mask',
        'Circlet',
        'Cap',
    ];

    private $beltKeywords = [
        'Belt',
    ];

    protected function configure()
    {
        $this
            ->setName('poe:core:crawl')
            ->setDefinition(array(
                new InputArgument('id', InputArgument::OPTIONAL, 'The thread ID'),
            ))
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getContainer()->get('msi_cmf.translatable_listener')->setSkipPostLoad(true);
        $this->itemManager = $this->getContainer()->get('poe_core.item_manager');
        $this->itemTypeManager = $this->getContainer()->get('poe_core.item_type_manager');
        $id = $input->getArgument('id');
        $this->output = $output;

        if ($id) {
            $this->processID($id);
        } else {
            $this->process();
        }

        $this->output->writeln("<comment>Done!</comment>");
    }

    protected function processID($id)
    {
        $data = $this->getJson($id);

        die(print_r($data[2]));
    }

    protected function process()
    {
        $l = 1;
        for ($id=110579; $id < 110589; $id++) {
            $data = $this->getJson($id);

            if (!$data) {
                $l++;
                continue;
            }

            $i = 1;
            foreach ($data as $v) {
                $row = $v[1];

                if (!$row['verified']) {
                    continue;
                }

                $item = $this->itemManager->create();

                $item
                    ->setName($row['name'])
                    ->setVerified($row['verified'])
                    ->setIcon($row['icon'])
                    ->setLeague($row['league'])
                    ->setSockets('dada')
                    ->setIdentified($row['identified'])
                    ->setAccountName($this->crawler->filter('a.profile-link.post_by_account')->text())
                    ->setThreadId($id)
                ;

                // type
                $type = $this->findType($row);
                if (!$type->getId()) {
                    $this->itemTypeManager->update($type);
                }

                // requirements
                if (isset($row['requirements'])) {
                    foreach ($row['requirements'] as $requirement) {
                        if ($requirement['name'] === 'Level') {
                            $item->setLvlReq($requirement['values'][0][0]);
                        }
                        if ($requirement['name'] === 'Dex') {
                            $item->setDexReq($requirement['values'][0][0]);
                        }
                        if ($requirement['name'] === 'Str') {
                            $item->setStrReq($requirement['values'][0][0]);
                        }
                        if ($requirement['name'] === 'Int') {
                            $item->setIntReq($requirement['values'][0][0]);
                        }
                    }
                }

                // properties
                if (isset($row['properties'])) {
                    foreach ($row['properties'] as $property) {
                        if ($property['name'] === 'Physical Damage') {
                            $pieces = explode('-', $property['values'][0][0]);
                            $min = $pieces[0];
                            $max = $pieces[1];
                            $item
                                ->setMinPhysicalDamage($min)
                                ->setMaxPhysicalDamage($max)
                            ;
                        }
                        if ($property['name'] === 'Elemental Damage') {
                            foreach ($property['values'] as $value) {
                                if ($value[1] == 4) {
                                    $pieces = explode('-', $value[0]);
                                    $min = $pieces[0];
                                    $max = $pieces[1];
                                    $item
                                        ->setMinFireDamage($min)
                                        ->setMaxFireDamage($max)
                                    ;
                                }
                                if ($value[1] == 5) {
                                    $pieces = explode('-', $value[0]);
                                    $min = $pieces[0];
                                    $max = $pieces[1];
                                    $item
                                        ->setMinColdDamage($min)
                                        ->setMaxColdDamage($max)
                                    ;
                                }
                                if ($value[1] == 6) {
                                    $pieces = explode('-', $value[0]);
                                    $min = $pieces[0];
                                    $max = $pieces[1];
                                    $item
                                        ->setMinLightningDamage($min)
                                        ->setMaxLightningDamage($max)
                                    ;
                                }
                            }
                        }
                        if ($property['name'] === 'Quality') {
                            $item->setQuality(str_replace('%', '', $property['values'][0][0]));
                        }
                        if ($property['name'] === 'Armour') {
                            $item->setArmour($property['values'][0][0]);
                        }
                        if ($property['name'] === 'Evasion Rating') {
                            $item->setEvasionRating($property['values'][0][0]);
                        }
                        if ($property['name'] === 'Energy Shield') {
                            $item->setEnergyShield($property['values'][0][0]);
                        }
                        if ($property['name'] === 'Attacks per Second') {
                            $item->setAttacksPerSecond($property['values'][0][0]);
                        }
                        if ($property['name'] === 'Critical Strike Chance') {
                            $item->setCriticalStrikeChance($property['values'][0][0]);
                        }
                        if ($property['name'] === 'Map Level') {
                            $item->setMapLvl($property['values'][0][0]);
                        }
                    }
                }

                $item->setType($type);
                $this->itemManager->updateBatch($item, $i);

                $label = $row['name'] ?: $row['typeLine'];
                $this->output->writeln("<info>ADD</info> ".$label);
                $i++;
            }
            $l++;
            $this->itemManager->getEntityManager()->flush();
        }
    }

    private function findType($row)
    {
        $type = $this->itemTypeManager->getFindByQueryBuilder(['a.name' => $row['typeLine']])->getQuery()->getOneOrNullResult();
        if (!$type) {
            $type = $this->itemTypeManager->create();
            $type->setName($row['typeLine']);
        }

        if ($type->getParent()) {
            return $type;
        }

        if (preg_match('#Ring#', $row['typeLine'])) {
            $parentType = $this->findOrCreateParentType('Ring');
            $type->setParent($parentType);

            return $type;
        }

        if (preg_match('#Amulet#', $row['typeLine'])) {
            $parentType = $this->findOrCreateParentType('Amulet');
            $type->setParent($parentType);

            return $type;
        }

        foreach ($this->bootsKeywords as $keyword) {
            if (preg_match('#'.$keyword.'#', $row['typeLine'])) {
                $parentType = $this->findOrCreateParentType('Boots');
                $type->setParent($parentType);

                return $type;
            }
        }

        foreach ($this->helmetKeywords as $keyword) {
            if (preg_match('#'.$keyword.'#', $row['typeLine'])) {
                $parentType = $this->findOrCreateParentType('Helmet');
                $type->setParent($parentType);

                return $type;
            }
        }

        foreach ($this->gloveKeywords as $keyword) {
            if (preg_match('#'.$keyword.'#', $row['typeLine'])) {
                $parentType = $this->findOrCreateParentType('Glove');
                $type->setParent($parentType);

                return $type;
            }
        }

        foreach ($this->beltKeywords as $keyword) {
            if (preg_match('#'.$keyword.'#', $row['typeLine'])) {
                $parentType = $this->findOrCreateParentType('Belt');
                $type->setParent($parentType);

                return $type;
            }
        }

        if (preg_match('#Flask#', $row['typeLine'])) {
            $parentType = $this->findOrCreateParentType('Flask');
            $type->setParent($parentType);

            return $type;
        }

        if (preg_match('#Quiver#', $row['typeLine'])) {
            $parentType = $this->findOrCreateParentType('Quiver');
            $type->setParent($parentType);

            return $type;
        }

        if (isset($row['properties'])) {
            foreach ($row['properties'] as $property) {
                if (in_array($property['name'], $this->parentTypes)) {
                    $parentType = $this->findOrCreateParentType(array_search($property['name'], $this->parentTypes));
                    $type->setParent($parentType);

                    return $type;
                }
                if (in_array($property['name'], ['Armour', 'Evasion Rating', 'Energy Shield'])) {
                    if ($row['w'] == 2 && $row['h'] == 3) {
                        $parentType = $this->findOrCreateParentType('Chest Armor');
                        $type->setParent($parentType);

                        return $type;
                    }
                }
                if ($property['name'] === 'Level') {
                    $parentType = $this->findOrCreateParentType('Gem');
                    $type->setParent($parentType);

                    return $type;
                }
                if ($property['name'] === 'Map Level') {
                    $parentType = $this->findOrCreateParentType('Map');
                    $type->setParent($parentType);

                    return $type;
                }
            }
        }

        return $type;
    }

    private function findOrCreateParentType($name)
    {
        $parentType = $this->itemTypeManager->getFindByQueryBuilder(['a.name' => $name])->getQuery()->getOneOrNullResult();
        if (!$parentType) {
            $parentType = $this->itemTypeManager->create();
            $parentType->setName($name);
            $this->itemTypeManager->update($parentType);
        }

        return $parentType;
    }

    private function getJson($id)
    {
        $url = 'http://www.pathofexile.com/forum/view-thread/'.$id;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $html = curl_exec($ch);
        curl_close($ch);

        $this->output->writeln('<comment>CRAWL</comment> http://www.pathofexile.com/forum/view-thread/'.$id);

        $this->crawler = new Crawler($html);
        $i=1;
        foreach ($this->crawler->filter('body')->children() as $element) {
            if ($i == count($this->crawler->filter('body')->children())) {
                $foo = substr(substr(trim($element->nodeValue), 122), 0, -34);
            }
            $i++;
        }

        $data = json_decode(utf8_encode($foo), true);

        return $data;
    }

    // protected $fields = [];

    // private function findFields($data, $prefix = '')
    // {
    //     foreach ($data as $k => $v) {
    //         if (is_array($v)) {
    //             $this->findFields($v, $prefix.$k);
    //         } else {
    //             $this->fields[] = $prefix.$k.' : '.$v;
    //         }
    //     }
    // }
}
