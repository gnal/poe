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
    ];

    protected function configure()
    {
        $this
            ->setName('poe:core:crawl')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getContainer()->get('msi_cmf.translatable_listener')->setSkipPostLoad(true);
        $this->itemManager = $this->getContainer()->get('poe_core.item_manager');
        $this->itemTypeManager = $this->getContainer()->get('poe_core.item_type_manager');

        $this->process($output);

        $output->writeln("<comment>Done!</comment>");
    }

    protected function process($output)
    {
        // $ch = curl_init();
        // curl_setopt($ch, CURLOPT_URL, 'http://www.pathofexile.com/forum/view-thread/100118');
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // $html = curl_exec($ch);
        // curl_close($ch);

        // $crawler = new Crawler($html);
        // $i=1;
        // foreach ($crawler->filter('body')->children() as $element) {
        //     if ($i == count($crawler->filter('body')->children())) {
        //         $foo = substr(substr(trim($element->nodeValue), 122), 0, -34);
        //     }
        //     $i++;
        // }

        // $data = json_decode(utf8_encode($foo), true);

        // // $this->findFields($data[0][1]);

        // die(print_r($data[0][1]));

        // ----------------- //

        $l = 1;
        for ($j=100122; $j < 100133; $j++) {
            $url = 'http://www.pathofexile.com/forum/view-thread/'.$j;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $html = curl_exec($ch);
            curl_close($ch);

            $output->writeln($l.' <comment>CRAWL</comment> http://www.pathofexile.com/forum/view-thread/'.$j);

            $crawler = new Crawler($html);

            $i=1;
            foreach ($crawler->filter('body')->children() as $element) {
                if ($i == count($crawler->filter('body')->children())) {
                    $foo = substr(substr(trim($element->nodeValue), 122), 0, -34);
                }
                $i++;
            }

            $data = json_decode(utf8_encode($foo), true);

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
                    ->setAccountName($crawler->filter('a.profile-link.post_by_account')->text())
                    ->setThread($url)
                ;

                // type
                $type = $this->itemTypeManager->getFindByQueryBuilder(['a.name' => $row['typeLine']])->getQuery()->getOneOrNullResult();

                if (!$type) {
                    $type = $this->itemTypeManager->create();

                    $type->setName($row['typeLine']);
                }
                if (!$type->getParent()) {
                    if (preg_match('#\sRing$#', $row['typeLine'])) {
                        $parentType = $this->itemTypeManager->getFindByQueryBuilder(['a.name' => 'Ring'])->getQuery()->getOneOrNullResult();

                        if (!$parentType) {
                            $parentType = $this->itemTypeManager->create();

                            $parentType->setName('Ring');

                            $this->itemTypeManager->update($parentType);
                        }

                        $type->setParent($parentType);
                    }
                    if (preg_match('#\sAmulet$#', $row['typeLine'])) {
                        $parentType = $this->itemTypeManager->getFindByQueryBuilder(['a.name' => 'Amulet'])->getQuery()->getOneOrNullResult();

                        if (!$parentType) {
                            $parentType = $this->itemTypeManager->create();

                            $parentType->setName('Amulet');

                            $this->itemTypeManager->update($parentType);
                        }

                        $type->setParent($parentType);
                    }
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
                        if ($property['name'] === 'Quality') {
                            $item->setQuality(str_replace('%', '', $property['values'][0][0]));
                        }
                        if (!$type->getParent()) {
                            if (in_array($property['name'], $this->parentTypes)) {
                                $parentType = $this->itemTypeManager->getFindByQueryBuilder(['a.name' => array_search($property['name'], $this->parentTypes)])->getQuery()->getOneOrNullResult();

                                if (!$parentType) {
                                    $parentType = $this->itemTypeManager->create();

                                    $parentType->setName(array_search($property['name'], $this->parentTypes));

                                    $this->itemTypeManager->update($parentType);
                                }

                                $type->setParent($parentType);
                            }
                        }
                    }
                }

                if (!$type->getId()) {
                    $this->itemTypeManager->update($type);
                }

                $item->setType($type);
                $this->itemManager->updateBatch($item, $i);

                $label = $row['name'] ?: $row['typeLine'];
                $output->writeln($l." <info>ADD</info> ".$label);
                $i++;
            }
            $l++;
            $this->itemManager->getEntityManager()->flush();
        }
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
