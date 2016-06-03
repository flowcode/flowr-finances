<?php

namespace Flower\FinancesBundle\Controller\Api;

use Flower\FinancesBundle\Entity\Account;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View as FOSView;
use Symfony\Component\HttpFoundation\Request;

/**
 * SaleCategory controller.
 */
class AccountController extends FOSRestController
{
    public function getIncomesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $accounts = $em->getRepository('FlowerFinancesBundle:Account')->findBy(array(
            "type" => Account::TYPE_INCOME
        ));

        $view = FOSView::create($accounts, Codes::HTTP_OK)->setFormat('json');
        $view->getSerializationContext()->setGroups(array('public_api'));
        return $this->handleView($view);
    }

}
