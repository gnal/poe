<?php

namespace Poe\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use Poe\CoreBundle\Form\Type\SearchFormType;
use Poe\CoreBundle\Entity\Item;

class ItemController extends Controller
{
    public function searchAction()
    {
        $form = $this->get('form.factory')->create(new SearchFormType());
        $valid = false;

        if ($search = $this->getRequest()->query->get('poe_search')) {
            $form->bind($this->getRequest());
            if ($form->isValid()) {
                $valid = true;
            }
        }

        $qb = $this->get('poe_core.item_manager')->getFindByQueryBuilder(
            [
                'a.league' => $search['league'] !== null ? $search['league'] : Item::LEAGUE_DEFAULT,
            ],
            [
                'a.type' => 't',
                't.parent' => 'tp',
            ],
            // ['tp.name' => 'ASC', 'a.lvlReq' => 'ASC']
            ['a.dps' => 'DESC']
        );

        if ($valid) {
            for ($i=1; $i < 4; $i++) {
                $prop = 'prop'.$i;
                $propVal = 'prop'.$i.'val';
                if (isset($search[$prop]) && '' !== $search[$prop]) {
                    $qb->andWhere('a.'.$search[$prop].' >= :'.$search[$prop].'');
                    $qb->setParameter($search[$prop], $search[$propVal] ?: 1);
                }
            }

            for ($i=1; $i < 7; $i++) {
                $mod = 'mod'.$i;
                $modVal = 'mod'.$i.'val';
                if (isset($search[$mod]) && '' !== $search[$mod]) {
                    $qb->andWhere('a.'.$search[$mod].' >= :'.$search[$mod].'');
                    $qb->setParameter($search[$mod], $search[$modVal] ?: 1);
                }
            }

            if (isset($search['name']) && '' !== $search['name']) {
                $orX = $qb->expr()->orX();

                $orX->add($qb->expr()->like('a.name', ':likeName'));
                $qb->setParameter('likeName', '%'.$search['name'].'%');

                $orX->add($qb->expr()->like('t.name', ':likeType'));
                $qb->setParameter('likeType', '%'.$search['name'].'%');

                $qb->andWhere($orX);
            }

            if (isset($search['frameType']) && '' !== $search['frameType']) {
                $qb->andWhere('a.frameType = :frameType');
                $qb->setParameter('frameType', $search['frameType']);
            }

            if (isset($search['type']) && '' !== $search['type']) {
                $type = $this->get('poe_core.item_type_manager')->getOneBy(['a.id' => $search['type']]);
                $qb->andWhere('t.root = :typeRoot')->setParameter('typeRoot', $type->getRoot());
            }
        }

        $qb->setMaxResults(100);
        $qb->select(
            // 'a.id',
            'a.frameType',
            'a.name',
            'a.threadId',
            'a.dps',
            'a.averagePhysicalDamage',
            'a.averageFireDamage',
            'a.averageColdDamage',
            'a.averageLightningDamage',
            'a.armour',
            'a.evasionRating',
            'a.energyShield',
            'a.minColdDamage',
            'a.minLightningDamage'
        );
        $items = $qb->getQuery()->execute();

        // $pager = $this->get('msi_cmf.pager.factory')->create($query);
        // $pager->paginate($this->getRequest()->query->get('page', 1), 15);
        // $items = $pager->getIterator()->getArrayCopy();

        if ($this->getRequest()->isXmlHttpRequest()) {
            $response = $this->render('PoeCoreBundle:Item:search_content.html.twig', ['form' => $form->createView(), 'items' => $items]);

            return $response;
        } else {
            $response = $this->render('PoeCoreBundle:Item:search.html.twig', ['form' => $form->createView(), 'items' => $items]);

            return $response;
        }
    }

    public function viewJsonAction()
    {
        $item = $this->get('poe_core.item_manager')->getOneBy(['a.id' => $this->getRequest()->attributes->get('id')]);

        die(print_r(json_decode($item->getJson())));

        return new Response();
    }
}
