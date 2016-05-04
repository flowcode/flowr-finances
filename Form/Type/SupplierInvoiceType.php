<?php

namespace Flower\FinancesBundle\Form\Type;

use Flower\FinancesBundle\Entity\Document;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SupplierInvoiceType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('supplier', 'genemu_jqueryselect2_entity',
                array('class' => 'Flower\StockBundle\Entity\Supplier',
                    'property' => 'name',
                    'multiple' => false,
                    'required' => false))
            ->add('total')
            ->add('totalWithTax')
            ->add('discount')
            ->add('totalDiscount')
            ->add('tax')
            ->add('status', 'choice', array(
                'choices' => array(
                    Document::STATUS_DRAFT => Document::STATUS_DRAFT,
                    Document::STATUS_PENDING => Document::STATUS_PENDING,
                    Document::STATUS_PAID => Document::STATUS_PAID,
                )
            ))
            ->add('items', 'collection', array(
                'type' => new DocumentItemType(),
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
            'data_class' => 'Flower\FinancesBundle\Entity\Document',
            'translation_domain' => 'Finance',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'document';
    }
}
