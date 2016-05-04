<?php

namespace Flower\FinancesBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Flower\FinancesBundle\Entity\Payment;
use Flower\FinancesBundle\Form\Type\PaymentType;
use Doctrine\ORM\QueryBuilder;

/**
 * Payment controller.
 *
 * @Route("/finance/payment")
 */
class PaymentController extends Controller
{
    /**
     * Lists all Payment entities.
     *
     * @Route("/", name="finance_payment")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('FlowerFinancesBundle:Payment')->createQueryBuilder('p');
        $this->addQueryBuilderSort($qb, 'payment');
        $paginator = $this->get('knp_paginator')->paginate($qb, $request->query->get('page', 1), 20);
        
        return array(
            'paginator' => $paginator,
        );
    }

    /**
     * Finds and displays a Payment entity.
     *
     * @Route("/{id}/show", name="finance_payment_show", requirements={"id"="\d+"})
     * @Method("GET")
     * @Template()
     */
    public function showAction(Payment $payment)
    {
        $editForm = $this->createForm(new PaymentType(), $payment, array(
            'action' => $this->generateUrl('finance_payment_update', array('id' => $payment->getid())),
            'method' => 'PUT',
        ));
        $deleteForm = $this->createDeleteForm($payment->getId(), 'finance_payment_delete');

        return array(

        'payment' => $payment,
        'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),

        );
    }

    /**
     * Displays a form to create a new Payment entity.
     *
     * @Route("/new", name="finance_payment_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $payment = new Payment();
        $form = $this->createForm(new PaymentType(), $payment);

        return array(
            'payment' => $payment,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Payment entity.
     *
     * @Route("/create", name="finance_payment_create")
     * @Method("POST")
     * @Template("FlowerFinancesBundle:Payment:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $payment = new Payment();
        $form = $this->createForm(new PaymentType(), $payment);
        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($payment);
            $em->flush();

            return $this->redirect($this->generateUrl('finance_payment_show', array('id' => $payment->getId())));
        }

        return array(
            'payment' => $payment,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Payment entity.
     *
     * @Route("/{id}/edit", name="finance_payment_edit", requirements={"id"="\d+"})
     * @Method("GET")
     * @Template()
     */
    public function editAction(Payment $payment)
    {
        $editForm = $this->createForm(new PaymentType(), $payment, array(
            'action' => $this->generateUrl('finance_payment_update', array('id' => $payment->getid())),
            'method' => 'PUT',
        ));
        $deleteForm = $this->createDeleteForm($payment->getId(), 'finance_payment_delete');

        return array(
            'payment' => $payment,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Payment entity.
     *
     * @Route("/{id}/update", name="finance_payment_update", requirements={"id"="\d+"})
     * @Method("PUT")
     * @Template("FlowerFinancesBundle:Payment:edit.html.twig")
     */
    public function updateAction(Payment $payment, Request $request)
    {
        $editForm = $this->createForm(new PaymentType(), $payment, array(
            'action' => $this->generateUrl('finance_payment_update', array('id' => $payment->getid())),
            'method' => 'PUT',
        ));
        if ($editForm->handleRequest($request)->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirect($this->generateUrl('finance_payment_show', array('id' => $payment->getId())));
        }
        $deleteForm = $this->createDeleteForm($payment->getId(), 'finance_payment_delete');

        return array(
            'payment' => $payment,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }


    /**
     * Save order.
     *
     * @Route("/order/{field}/{type}", name="finance_payment_sort")
     */
    public function sortAction($field, $type)
    {
        $this->setOrder('payment', $field, $type);

        return $this->redirect($this->generateUrl('finance_payment'));
    }

    /**
     * @param string $name  session name
     * @param string $field field name
     * @param string $type  sort type ("ASC"/"DESC")
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
     * @param string       $name
     */
    protected function addQueryBuilderSort(QueryBuilder $qb, $name)
    {
        $alias = current($qb->getDQLPart('from'))->getAlias();
        if (is_array($order = $this->getOrder($name))) {
            $qb->orderBy($alias . '.' . $order['field'], $order['type']);
        }
    }

    /**
     * Deletes a Payment entity.
     *
     * @Route("/{id}/delete", name="finance_payment_delete", requirements={"id"="\d+"})
     * @Method("DELETE")
     */
    public function deleteAction(Payment $payment, Request $request)
    {
        $form = $this->createDeleteForm($payment->getId(), 'finance_payment_delete');
        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($payment);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('finance_payment'));
    }

    /**
     * Create Delete form
     *
     * @param integer                       $id
     * @param string                        $route
     * @return \Symfony\Component\Form\Form
     */
    protected function createDeleteForm($id, $route)
    {
        return $this->createFormBuilder(null, array('attr' => array('id' => 'delete')))
            ->setAction($this->generateUrl($route, array('id' => $id)))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

}
