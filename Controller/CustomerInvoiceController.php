<?php

namespace Flower\FinancesBundle\Controller;

use Flower\FinancesBundle\Entity\CustomerInvoiceItem;
use Flower\ModelBundle\Entity\Sales\Sale;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Flower\FinancesBundle\Entity\CustomerInvoice;
use Flower\FinancesBundle\Form\Type\CustomerInvoiceType;
use Doctrine\ORM\QueryBuilder;

/**
 * CustomerInvoice controller.
 *
 * @Route("/finance/customer_invoice")
 */
class CustomerInvoiceController extends Controller
{
    /**
     * Lists all CustomerInvoice entities.
     *
     * @Route("/", name="finance_customer_invoice")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('FlowerFinancesBundle:CustomerInvoice')->createQueryBuilder('c');
        $this->addQueryBuilderSort($qb, 'customerinvoice');
        $paginator = $this->get('knp_paginator')->paginate($qb, $request->query->get('page', 1), 20);

        return array(
            'paginator' => $paginator,
        );
    }

    /**
     * Finds and displays a CustomerInvoice entity.
     *
     * @Route("/{id}/show", name="finance_customer_invoice_show", requirements={"id"="\d+"})
     * @Method("GET")
     * @Template()
     */
    public function showAction(CustomerInvoice $customerinvoice)
    {
        $editForm = $this->createForm(new CustomerInvoiceType(), $customerinvoice, array(
            'action' => $this->generateUrl('finance_customer_invoice_update', array('id' => $customerinvoice->getid())),
            'method' => 'PUT',
        ));
        $deleteForm = $this->createDeleteForm($customerinvoice->getId(), 'finance_customer_invoice_delete');

        return array(

            'customerinvoice' => $customerinvoice,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),

        );
    }

    /**
     * Displays a form to create a new CustomerInvoice entity.
     *
     * @Route("/new", name="finance_customer_invoice_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $customerinvoice = new CustomerInvoice();
        $form = $this->createForm(new CustomerInvoiceType(), $customerinvoice);

        return array(
            'customerinvoice' => $customerinvoice,
            'form' => $form->createView(),
        );
    }


    /**
     * Creates a new CustomerInvoice entity.
     *
     * @Route("/create/sale/{id}", name="finance_customer_invoice_create_from_sale")
     * @Method("GET")
     * @Template("FlowerFinancesBundle:CustomerInvoice:new.html.twig")
     */
    public function createFromSaleAction(Request $request, Sale $sale)
    {
        $customerinvoice = new CustomerInvoice();

        $customerinvoice->setAccount($sale->getAccount());
        $customerinvoice->setDiscount($sale->getDiscount());
        $customerinvoice->setTax($sale->getTax());
        $customerinvoice->setTotal($sale->getTotal());
        $customerinvoice->setTotalDiscount($sale->getTotalDiscount());
        $customerinvoice->setTotalWithTax($sale->getTotalWithTax());
        $customerinvoice->setSale($sale);

        foreach ($sale->getSaleItems() as $saleItem) {
            $invoiceItem = new CustomerInvoiceItem();

            $invoiceItem->setProduct($saleItem->getProduct());
            $invoiceItem->setService($saleItem->getService());
            $invoiceItem->setTotal($saleItem->getTotal());
            $invoiceItem->setCustomerInvoice($customerinvoice);
            $invoiceItem->setUnitPrice($saleItem->getUnitPrice());
            $invoiceItem->setUnits($saleItem->getUnits());

            $customerinvoice->addItem($invoiceItem);

        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($customerinvoice);
        $em->flush();

        $sale->setCustomerInvoice($customerinvoice);
        $em->flush();

        return $this->redirect($this->generateUrl('finance_customer_invoice_show', array('id' => $customerinvoice->getId())));

    }

    /**
     * Creates a new CustomerInvoice entity.
     *
     * @Route("/create", name="finance_customer_invoice_create")
     * @Method("POST")
     * @Template("FlowerFinancesBundle:CustomerInvoice:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $customerinvoice = new CustomerInvoice();
        $form = $this->createForm(new CustomerInvoiceType(), $customerinvoice);
        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($customerinvoice);
            $em->flush();

            return $this->redirect($this->generateUrl('finance_customer_invoice_show', array('id' => $customerinvoice->getId())));
        }

        return array(
            'customerinvoice' => $customerinvoice,
            'form' => $form->createView(),
        );
    }


    /**
     * Displays a form to edit an existing CustomerInvoice entity.
     *
     * @Route("/{id}/edit", name="finance_customer_invoice_edit", requirements={"id"="\d+"})
     * @Method("GET")
     * @Template()
     */
    public function editAction(CustomerInvoice $customerinvoice)
    {
        $editForm = $this->createForm(new CustomerInvoiceType(), $customerinvoice, array(
            'action' => $this->generateUrl('finance_customer_invoice_update', array('id' => $customerinvoice->getid())),
            'method' => 'PUT',
        ));
        $deleteForm = $this->createDeleteForm($customerinvoice->getId(), 'finance_customer_invoice_delete');

        return array(
            'customerinvoice' => $customerinvoice,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing CustomerInvoice entity.
     *
     * @Route("/{id}/update", name="finance_customer_invoice_update", requirements={"id"="\d+"})
     * @Method("PUT")
     * @Template("FlowerFinancesBundle:CustomerInvoice:edit.html.twig")
     */
    public function updateAction(CustomerInvoice $customerinvoice, Request $request)
    {
        $editForm = $this->createForm(new CustomerInvoiceType(), $customerinvoice, array(
            'action' => $this->generateUrl('finance_customer_invoice_update', array('id' => $customerinvoice->getid())),
            'method' => 'PUT',
        ));
        if ($editForm->handleRequest($request)->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirect($this->generateUrl('finance_customer_invoice_show', array('id' => $customerinvoice->getId())));
        }
        $deleteForm = $this->createDeleteForm($customerinvoice->getId(), 'finance_customer_invoice_delete');

        return array(
            'customerinvoice' => $customerinvoice,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }


    /**
     * Save order.
     *
     * @Route("/order/{field}/{type}", name="finance_customer_invoice_sort")
     */
    public function sortAction($field, $type)
    {
        $this->setOrder('customerinvoice', $field, $type);

        return $this->redirect($this->generateUrl('finance_customer_invoice'));
    }

    /**
     * @param string $name session name
     * @param string $field field name
     * @param string $type sort type ("ASC"/"DESC")
     */
    protected function setOrder($name, $field, $type = 'ASC')
    {
        $this->getRequest()->getSession()->set('sort.' . $name, array('field' => $field, 'type' => $type));
    }

    /**
     * @param  string $name
     * @return array
     */
    protected function getOrder($name)
    {
        $session = $this->getRequest()->getSession();

        return $session->has('sort.' . $name) ? $session->get('sort.' . $name) : null;
    }

    /**
     * @param QueryBuilder $qb
     * @param string $name
     */
    protected function addQueryBuilderSort(QueryBuilder $qb, $name)
    {
        $alias = current($qb->getDQLPart('from'))->getAlias();
        if (is_array($order = $this->getOrder($name))) {
            $qb->orderBy($alias . '.' . $order['field'], $order['type']);
        }
    }

    /**
     * Deletes a CustomerInvoice entity.
     *
     * @Route("/{id}/delete", name="finance_customer_invoice_delete", requirements={"id"="\d+"})
     * @Method("DELETE")
     */
    public function deleteAction(CustomerInvoice $customerinvoice, Request $request)
    {
        $form = $this->createDeleteForm($customerinvoice->getId(), 'finance_customer_invoice_delete');
        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($customerinvoice);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('finance_customer_invoice'));
    }

    /**
     * Create Delete form
     *
     * @param integer $id
     * @param string $route
     * @return \Symfony\Component\Form\Form
     */
    protected function createDeleteForm($id, $route)
    {
        return $this->createFormBuilder(null, array('attr' => array('id' => 'delete')))
            ->setAction($this->generateUrl($route, array('id' => $id)))
            ->setMethod('DELETE')
            ->getForm();
    }

}
