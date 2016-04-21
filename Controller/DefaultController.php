<?php

namespace Flower\FinancesBundle\Controller;

use Flower\FinancesBundle\Entity\Account;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/finance", name="finance_dashboard")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $accountRepo = $em->getRepository('FlowerFinancesBundle:Account');

        $assets = $accountRepo->getQuickStats(Account::TYPE_ASSET);
        $income = $accountRepo->getQuickStats(Account::TYPE_INCOME);
        $expense = $accountRepo->getQuickStats(Account::TYPE_EXPENSE);
        $liability = $accountRepo->getQuickStats(Account::TYPE_LIABILITY);

        return array(
            "assets" => count($assets) > 0 ? $assets[0] : $assets,
            "income" => count($income) > 0 ? $income[0] : $income,
            "expense" => count($expense) > 0 ? $expense[0] : $expense,
            "liability" => count($liability) > 0 ? $liability[0] : $liability,
        );
    }
}
