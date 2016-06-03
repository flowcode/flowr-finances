<?php

namespace Flower\FinancesBundle\Form\Type;

use Flower\FinancesBundle\Entity\Document;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CustomerInvoiceType extends AbstractType
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
            ->add('account', 'genemu_jqueryselect2_entity',
                array('class' => 'Flower\ModelBundle\Entity\Clients\Account',
                    'property' => 'name',
                    'choices' => $this->accountService->findClients(),
                    'multiple' => false,
                    'required' => false))
            ->add('code')
            ->add('date')
            ->add('dueDate')
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
            ->add('financeAccount', 'y_tree', array(
                'class' => 'Flower\FinancesBundle\Entity\Account',
                'orderFields' => array('root' => 'asc','lft' => 'asc'),
                'prefixAttributeName' => 'data-level-prefix',
                'treeLevelField' => 'lvl',
                'required' => false,
                'multiple' => false,
                'attr' => array("class" => "tall")))
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
