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

        if ($search = $this->getRequest()->query->get('poe_search')) {
            $form->bind($this->getRequest());
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

        for ($i=1; $i < 4; $i++) {
            $prop = 'prop'.$i;
            $propVal = 'prop'.$i.'val';
            if (isset($search[$prop]) && '' !== $search[$prop] && isset($search[$propVal]) && '' !== $search[$propVal]) {
                $qb->andWhere('a.'.$search[$prop].' >= :'.$search[$prop].'');
                $qb->setParameter($search[$prop], $search[$propVal]);
            }
        }

        for ($i=1; $i < 7; $i++) {
            $mod = 'mod'.$i;
            $modVal = 'mod'.$i.'val';
            if (isset($search[$mod]) && '' !== $search[$mod] && isset($search[$modVal]) && '' !== $search[$modVal]) {
                $qb->andWhere('a.'.$search[$mod].' >= :'.$search[$mod].'');
                $qb->setParameter($search[$mod], $search[$modVal]);
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

        $pager = $this->get('msi_cmf.pager.factory')->create($qb);
        $pager->paginate($this->getRequest()->query->get('page', 1), 10);
        $items = $pager->getIterator()->getArrayCopy();

        return $this->render('PoeCoreBundle:Item:search.html.twig', ['pager' => $pager, 'form' => $form->createView(), 'items' => $items]);
    }

    public function viewJsonAction()
    {
        $item = $this->get('poe_core.item_manager')->getOneBy(['a.id' => $this->getRequest()->attributes->get('id')]);

        die(print_r(json_decode($item->getJson())));

        return new Response();
    }
}
