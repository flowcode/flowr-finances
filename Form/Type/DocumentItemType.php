<?php

namespace Flower\FinancesBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DocumentItemType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('product')
            ->add('service')
            ->add('units')
            ->add('unitPrice')
            ->add('total');
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Flower\FinancesBundle\Entity\DocumentItem',
            'translation_domain' => 'DocumentItem',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'documentitem';
    }
}
