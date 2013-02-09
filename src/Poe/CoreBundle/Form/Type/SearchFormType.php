<?php

namespace Poe\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class SearchFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', 'entity', [
                'class' => 'PoeCoreBundle:ItemType',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('a')
                        ->andWhere('a.lvl = 0')
                        ->addOrderBy('a.name', 'ASC')
                    ;
                },
            ])
        ;
    }

    public function getName()
    {
        return 'poe_core_search';
    }
}
