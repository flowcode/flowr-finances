<?php

namespace Flower\FinancesBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CustomerInvoiceType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('total')
            ->add('totalWithTax')
            ->add('discount')
            ->add('totalDiscount')
            ->add('tax')
            ->add('status');
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Flower\FinancesBundle\Entity\CustomerInvoice',
            'translation_domain' => 'CustomerInvoice',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'customerinvoice';
    }
}
