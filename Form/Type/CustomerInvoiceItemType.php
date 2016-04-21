<?php

namespace Flower\FinancesBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CustomerInvoiceItemType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        
            ->add('units')
            ->add('unitPrice')
            ->add('total')
            ->add('customerInvoice')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Flower\FinancesBundle\Entity\CustomerInvoiceItem',
            'translation_domain' => 'CustomerInvoiceItem',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'customerinvoiceitem';
    }
}
