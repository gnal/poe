<?php

namespace Poe\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Poe\CoreBundle\Form\Type\SearchFormType;

class ItemController extends Controller
{
    public function searchAction()
    {
        $form = $this->get('form.factory')->create(new SearchFormType());

        $q = $this->getRequest()->query->get('q');

        $qb = $this->get('poe_core.item_manager')->getSearchQueryBuilder(
            $q,
            ['a.name'],
            [],
            ['a.type' => 't', 't.parent' => 'tp'],
            // ['tp.name' => 'ASC', 'a.lvlReq' => 'ASC']
            ['a.dps' => 'DESC']
        );

        if (null !== $league = $this->getRequest()->query->get('league')) {
            $qb->andWhere('a.league = :league')->setParameter('league', $league);
        }

        $pager = $this->get('msi_cmf.pager.factory')->create($qb);
        $pager->paginate($this->getRequest()->query->get('page', 1), 10);

        $items = $pager->getIterator()->getArrayCopy();

        return $this->render('PoeCoreBundle:Item:search.html.twig', ['pager' => $pager, 'form' => $form->createView(), 'items' => $items]);
    }
}
