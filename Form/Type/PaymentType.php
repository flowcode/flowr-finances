<?php

namespace Flower\FinancesBundle\Form\Type;

use Flower\FinancesBundle\Entity\Payment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PaymentType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', 'choice', array(
                'choices' => array(
                    Payment::TYPE_INCOME => Payment::TYPE_INCOME,
                    Payment::TYPE_EXPENSE => Payment::TYPE_EXPENSE,
                )
            ))
            ->add('name')
            ->add('description')
            ->add('amount')
            ->add('created')
            ->add('documents');
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Flower\FinancesBundle\Entity\Payment',
            'translation_domain' => 'Payment',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'payment';
    }
}
