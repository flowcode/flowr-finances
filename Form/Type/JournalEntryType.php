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
            ->add('account')
            ->add('debit', null, array(
                'required' => false,
            ))
            ->add('credit', null, array(
                'required' => false,
            ))
            ->add('date')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Flower\FinancesBundle\Entity\JournalEntry',
            'translation_domain' => 'JournalEntry',
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
