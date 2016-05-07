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

        $income = $accountRepo->getQuickStats(Account::TYPE_INCOME);
        $expense = $accountRepo->getQuickStats(Account::TYPE_EXPENSE);

        return array(
            "income" => count($income) > 0 ? $income[0] : $income,
            "expense" => count($expense) > 0 ? $expense[0] : $expense,
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
