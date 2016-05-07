<?php

namespace Flower\FinancesBundle\Form\Type;

use Flower\FinancesBundle\Entity\Document;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SupplierInvoiceType extends AbstractType
{

    private $accountService;

    public function __construct($accountService)
    {
        $this->accountService = $accountService;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('supplier', 'genemu_jqueryselect2_entity',
                array('class' => 'Flower\ModelBundle\Entity\Clients\Account',
                    'property' => 'name',
                    'choices' => $this->accountService->findSuppliers(),
                    'multiple' => false,
                    'required' => false))
            ->add('code')
            ->add('date')
            ->add('dueDate')
            ->add('total')
            ->add('discount')
            ->add('totalDiscount')
            ->add('tax')
            ->add('totalWithTax')
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
