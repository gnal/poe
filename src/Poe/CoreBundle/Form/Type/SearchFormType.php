<?php

namespace Poe\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

use Poe\CoreBundle\Entity\Item;

class SearchFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $prop = [
            'averagePhysicalDamage' => 'Avg Physical Dmg',
            'criticalStrikeChance' => 'Crit Strike Chance',
            'attacksPerSecond' => 'APS',
            'armour' => 'Armour',
            'evasionRating' => 'ER',
            'energyShield' => 'ES',
        ];

        $mod = [
            'increasedPhysicalDamage' => '+% Physical Dmg',
            'increasedStunDuration' => '+% Stun Duration',
        ];

        $builder
            ->  add('league', 'choice', [
                'choices' => [
                    Item::LEAGUE_DEFAULT => 'Default',
                    Item::LEAGUE_HARDCORE => 'Hardcore',
                ],
            ])
            ->add('type', 'entity', [
                'empty_value' => 'All',
                'class' => 'PoeCoreBundle:ItemType',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('a')
                        ->andWhere('a.lvl = 0')
                        ->addOrderBy('a.name', 'ASC')
                    ;
                },
            ])
            ->add('name', 'text', [
                'attr' => [
                    'placeholder' => 'Name',
                ],
            ])
            ->add('frameType', 'choice', [
                'label' => ' ',
                'empty_value' => 'All',
                'choices' => [
                    Item::FRAME_TYPE_NORMAL => 'Normal',
                    Item::FRAME_TYPE_MAGIC => 'Magic',
                    Item::FRAME_TYPE_RARE => 'Rare',
                    Item::FRAME_TYPE_UNIQUE => 'Unique',
                ],
            ])
            ->add('prop1', 'choice', [
                'label' => ' ',
                'empty_value' => 'None',
                'choices' => $prop,
            ])
            ->add('prop1val', 'text', [
                'attr' => [
                    'style' => 'width: 36px;',
                ],
            ])
            ->add('prop2', 'choice', [
                'label' => ' ',
                'empty_value' => 'None',
                'choices' => $prop,
            ])
            ->add('prop2val', 'text', [
                'attr' => [
                    'style' => 'width: 36px;',
                ],
            ])
            ->add('prop3', 'choice', [
                'label' => ' ',
                'empty_value' => 'None',
                'choices' => $prop,
            ])
            ->add('prop3val', 'text', [
                'attr' => [
                    'style' => 'width: 36px;',
                ],
            ])
            ->add('mod1', 'choice', [
                'label' => ' ',
                'empty_value' => 'None',
                'choices' => $mod,
            ])
            ->add('mod1val', 'text', [
                'attr' => [
                    'style' => 'width: 36px;',
                ],
            ])
            ->add('mod2', 'choice', [
                'label' => ' ',
                'empty_value' => 'None',
                'choices' => $mod,
            ])
            ->add('mod2val', 'text', [
                'attr' => [
                    'style' => 'width: 36px;',
                ],
            ])
            ->add('mod3', 'choice', [
                'label' => ' ',
                'empty_value' => 'None',
                'choices' => $mod,
            ])
            ->add('mod3val', 'text', [
                'attr' => [
                    'style' => 'width: 36px;',
                ],
            ])
            ->add('mod4', 'choice', [
                'label' => ' ',
                'empty_value' => 'None',
                'choices' => $mod,
            ])
            ->add('mod4val', 'text', [
                'attr' => [
                    'style' => 'width: 36px;',
                ],
            ])
            ->add('mod5', 'choice', [
                'label' => ' ',
                'empty_value' => 'None',
                'choices' => $mod,
            ])
            ->add('mod5val', 'text', [
                'attr' => [
                    'style' => 'width: 36px;',
                ],
            ])
            ->add('mod6', 'choice', [
                'label' => ' ',
                'empty_value' => 'None',
                'choices' => $mod,
            ])
            ->add('mod6val', 'text', [
                'attr' => [
                    'style' => 'width: 36px;',
                ],
            ])
        ;
    }

    public function getName()
    {
        return 'poe_search';
    }
}
