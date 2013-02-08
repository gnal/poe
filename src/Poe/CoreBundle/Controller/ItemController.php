<?php

namespace Poe\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ItemController extends Controller
{
    public function searchAction()
    {
        $q = $this->getRequest()->query->get('q');

        $qb = $this->get('poe_core.item_manager')->getSearchQueryBuilder(
            $q,
            ['a.name'],
            [],
            ['a.type' => 't', 't.parent' => 'tp'],
            ['tp.name' => 'ASC', 'a.lvlReq' => 'ASC']
        );

        if ($league = $this->getRequest()->query->get('league')) {
            $qb->andWhere('a.league = :league')->setParameter('league', $league);
        }

        $items = $qb->getQuery()->execute();

        return $this->render('PoeCoreBundle:Item:search.html.twig', ['items' => $items]);
    }
}
