<?php

namespace Flower\FinancesBundle\Controller;

use Flower\FinancesBundle\Entity\Account;
use Flower\FinancesBundle\Entity\Payment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

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
        $paymentRepo = $em->getRepository('FlowerFinancesBundle:Payment');

        $assets = $accountRepo->getQuickStats(Account::TYPE_ASSET);
        $income = $accountRepo->getQuickStats(Account::TYPE_INCOME);
        $expense = $accountRepo->getQuickStats(Account::TYPE_EXPENSE);
        $liability = $accountRepo->getQuickStats(Account::TYPE_LIABILITY);

        $incomePayments = $paymentRepo->getQuickStats(Payment::TYPE_INCOME);
        $expensePayments = $paymentRepo->getQuickStats(Payment::TYPE_EXPENSE);

        return array(
            "assets" => count($assets) > 0 ? $assets[0] : $assets,
            "income" => count($income) > 0 ? $income[0] : $income,
            "expense" => count($expense) > 0 ? $expense[0] : $expense,
            "liability" => count($liability) > 0 ? $liability[0] : $liability,
            "incomePayments" => $incomePayments,
            "expensePayments" => $expensePayments,
        );
    }

    /**
     * @Route("/finance/reports/general_ledger", name="finance_report_general_ledger")
     * @Template()
     */
    public function generalLedgerAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $accountRepo = $em->getRepository('FlowerFinancesBundle:Account');
        $from = new \DateTime($request->get('from'));

        $to = new \DateTime();
        if ($request->get('to')) {
            $to = new \DateTime($request->get('to'));
        }
        $accounts = $accountRepo->getGeneralLedger($from, $to);

        return array(
            "from" => $from,
            "to" => $to,
            "accounts" => $accounts,
        );
    }
}
