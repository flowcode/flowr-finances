<?php

namespace Flower\FinancesBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Flower\FinancesBundle\Entity\Account;
use Flower\FinancesBundle\Form\Type\AccountType;
use Doctrine\ORM\QueryBuilder;

/**
 * Account controller.
 *
 * @Route("/finance/account")
 */
class AccountController extends Controller
{
    /**
     * Lists all Account entities.
     *
     * @Route("/", name="finance_account")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('FlowerFinancesBundle:Account')->createQueryBuilder('a');
        $this->addQueryBuilderSort($qb, 'account');
        $paginator = $this->get('knp_paginator')->paginate($qb, $request->query->get('page', 1), 20);
        
        return array(
            'paginator' => $paginator,
        );
    }

    /**
     * Finds and displays a Account entity.
     *
     * @Route("/{id}/show", name="finance_account_show", requirements={"id"="\d+"})
     * @Method("GET")
     * @Template()
     */
    public function showAction(Account $account)
    {
        $editForm = $this->createForm(new AccountType(), $account, array(
            'action' => $this->generateUrl('finance_account_update', array('id' => $account->getid())),
            'method' => 'PUT',
        ));
        $deleteForm = $this->createDeleteForm($account->getId(), 'finance_account_delete');

        return array(

        'account' => $account,
        'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),

        );
    }

    /**
     * Displays a form to create a new Account entity.
     *
     * @Route("/new", name="finance_account_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $account = new Account();
        $form = $this->createForm(new AccountType(), $account);

        return array(
            'account' => $account,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Account entity.
     *
     * @Route("/create", name="finance_account_create")
     * @Method("POST")
     * @Template("FlowerFinancesBundle:Account:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $account = new Account();
        $form = $this->createForm(new AccountType(), $account);
        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($account);
            $em->flush();

            return $this->redirect($this->generateUrl('finance_account_show', array('id' => $account->getId())));
        }

        return array(
            'account' => $account,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Account entity.
     *
     * @Route("/{id}/edit", name="finance_account_edit", requirements={"id"="\d+"})
     * @Method("GET")
     * @Template()
     */
    public function editAction(Account $account)
    {
        $editForm = $this->createForm(new AccountType(), $account, array(
            'action' => $this->generateUrl('finance_account_update', array('id' => $account->getid())),
            'method' => 'PUT',
        ));
        $deleteForm = $this->createDeleteForm($account->getId(), 'finance_account_delete');

        return array(
            'account' => $account,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Account entity.
     *
     * @Route("/{id}/update", name="finance_account_update", requirements={"id"="\d+"})
     * @Method("PUT")
     * @Template("FlowerFinancesBundle:Account:edit.html.twig")
     */
    public function updateAction(Account $account, Request $request)
    {
        $editForm = $this->createForm(new AccountType(), $account, array(
            'action' => $this->generateUrl('finance_account_update', array('id' => $account->getid())),
            'method' => 'PUT',
        ));
        if ($editForm->handleRequest($request)->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirect($this->generateUrl('finance_account_show', array('id' => $account->getId())));
        }
        $deleteForm = $this->createDeleteForm($account->getId(), 'finance_account_delete');

        return array(
            'account' => $account,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }


    /**
     * Save order.
     *
     * @Route("/order/{field}/{type}", name="finance_account_sort")
     */
    public function sortAction($field, $type)
    {
        $this->setOrder('account', $field, $type);

        return $this->redirect($this->generateUrl('finance_account'));
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
     * Deletes a Account entity.
     *
     * @Route("/{id}/delete", name="finance_account_delete", requirements={"id"="\d+"})
     * @Method("DELETE")
     */
    public function deleteAction(Account $account, Request $request)
    {
        $form = $this->createDeleteForm($account->getId(), 'finance_account_delete');
        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($account);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('finance_account'));
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
