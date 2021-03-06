<?php

namespace Flower\FinancesBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TransactionType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('description', null, array(
                'required' => true,
            ))
            ->add('date')
            ->add('journalEntries', 'collection', array(
                'type' => new JournalEntryType(),
                'allow_add' => true,
                'by_reference' => false,
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Flower\FinancesBundle\Entity\Transaction',
            'translation_domain' => 'Finance',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'transaction';
    }
}
