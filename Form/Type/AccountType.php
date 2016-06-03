<?php

namespace Flower\FinancesBundle\Form\Type;

use Flower\FinancesBundle\Entity\Account;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AccountType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code')
            ->add('name')
            ->add('parent', 'y_tree', array(
                'class' => 'Flower\FinancesBundle\Entity\Account',
                'orderFields' => array('root' => 'asc','lft' => 'asc'),
                'prefixAttributeName' => 'data-level-prefix',
                'treeLevelField' => 'lvl',
                'required' => false,
                'multiple' => false,
                'attr' => array("class" => "tall")))
            ->add('type', 'choice', array(
                'choices' => array(
                    1 => 'account_type_asset',
                    2 => 'account_type_liability',
                    3 => 'account_type_income',
                    4 => 'account_type_expense',
                    5 => 'account_type_equity',
                )
            ))
            ->add('subtype', 'choice', array(
                'required' => false,
                'placeholder' => 'Choose an option',
                'choices' => array(
                    1 => 'SUBTYPE_ASSET_RECEIVABLE',
                    2 => 'SUBTYPE_LIABILITY_PAYABLE',
                    Account::SUBTYPE_INCOME_SALES => 'SUBTYPE_INCOME_SALES',
                )
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Flower\FinancesBundle\Entity\Account',
            'translation_domain' => 'Finance',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'account';
    }
}
