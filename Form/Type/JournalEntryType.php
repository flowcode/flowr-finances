<?php

namespace Flower\FinancesBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class JournalEntryType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('account', 'y_tree', array(
                'class' => 'Flower\FinancesBundle\Entity\Account',
                'orderFields' => array('root' => 'asc', 'lft' => 'asc'),
                'prefixAttributeName' => 'data-level-prefix',
                'treeLevelField' => 'lvl',
                'required' => false,
                'multiple' => false,
                'attr' => array("class" => "tall")))
            ->add('debit', null, array(
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Debit',
                ),
            ))
            ->add('credit', null, array(
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Credit',
                ),
            ))
            ->add('date');
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Flower\FinancesBundle\Entity\JournalEntry',
            'translation_domain' => 'Finance',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'journalentry';
    }
}
