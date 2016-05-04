<?php

namespace Flower\FinancesBundle\Controller;

use Flower\FinancesBundle\Entity\Document;
use Flower\FinancesBundle\Entity\JournalEntry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Flower\FinancesBundle\Entity\Transaction;
use Flower\FinancesBundle\Form\Type\TransactionType;
use Doctrine\ORM\QueryBuilder;

/**
 * Transaction controller.
 *
 * @Route("/finance/transaction")
 */
class TransactionController extends Controller
{
    /**
     * Lists all Transaction entities.
     *
     * @Route("/", name="finance_transaction")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('FlowerFinancesBundle:Transaction')->createQueryBuilder('t');
        $this->addQueryBuilderSort($qb, 'transaction');
        $paginator = $this->get('knp_paginator')->paginate($qb, $request->query->get('page', 1), 20);

        return array(
            'paginator' => $paginator,
        );
    }

    /**
     * Finds and displays a Transaction entity.
     *
     * @Route("/{id}/show", name="finance_transaction_show", requirements={"id"="\d+"})
     * @Method("GET")
     * @Template()
     */
    public function showAction(Transaction $transaction)
    {
        $editForm = $this->createForm(new TransactionType(), $transaction, array(
            'action' => $this->generateUrl('finance_transaction_update', array('id' => $transaction->getid())),
            'method' => 'PUT',
        ));
        $deleteForm = $this->createDeleteForm($transaction->getId(), 'finance_transaction_delete');

        return array(
            'transaction' => $transaction,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Transaction entity.
     *
     * @Route("/new/Document/{id}", name="finance_transaction_new_from_invoice")
     * @Method("GET")
     * @Template("FlowerFinancesBundle:Transaction:new.html.twig")
     */
    public function newFromDocumentAction(Document $Document)
    {
        $transaction = new Transaction();

        $financeAccount = $Document->getAccount()->getFinanceAccount();
        if ($financeAccount) {
            $desc = "Pago " . $Document->getAccount()->getName();
            $desc .= " por factura " . $Document->getId();
            $transaction->setDescription($desc);

            $journalEntry = new JournalEntry();
            $journalEntry->setAccount($financeAccount);
            $journalEntry->getDate(new \DateTime());

            $transaction->addJournalEntry($journalEntry);
        }

        $form = $this->createForm(new TransactionType(), $transaction);

        return array(
            'transaction' => $transaction,
            'form' => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new Transaction entity.
     *
     * @Route("/new", name="finance_transaction_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $transaction = new Transaction();
        $form = $this->createForm(new TransactionType(), $transaction);

        return array(
            'transaction' => $transaction,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a new Transaction entity.
     *
     * @Route("/create", name="finance_transaction_create")
     * @Method("POST")
     * @Template("FlowerFinancesBundle:Transaction:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $transaction = new Transaction();
        $form = $this->createForm(new TransactionType(), $transaction);
        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();

            foreach ($transaction->getJournalEntries() as $entry) {
                $entry->setTransaction($transaction);
            }

            $em->persist($transaction);
            $em->flush();

            return $this->redirect($this->generateUrl('finance_transaction_show', array('id' => $transaction->getId())));
        }

        return array(
            'transaction' => $transaction,
            'form' => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Transaction entity.
     *
     * @Route("/{id}/edit", name="finance_transaction_edit", requirements={"id"="\d+"})
     * @Method("GET")
     * @Template()
     */
    public function editAction(Transaction $transaction)
    {
        $editForm = $this->createForm(new TransactionType(), $transaction, array(
            'action' => $this->generateUrl('finance_transaction_update', array('id' => $transaction->getid())),
            'method' => 'PUT',
        ));
        $deleteForm = $this->createDeleteForm($transaction->getId(), 'finance_transaction_delete');

        return array(
            'transaction' => $transaction,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Transaction entity.
     *
     * @Route("/{id}/update", name="finance_transaction_update", requirements={"id"="\d+"})
     * @Method("PUT")
     * @Template("FlowerFinancesBundle:Transaction:edit.html.twig")
     */
    public function updateAction(Transaction $transaction, Request $request)
    {
        $editForm = $this->createForm(new TransactionType(), $transaction, array(
            'action' => $this->generateUrl('finance_transaction_update', array('id' => $transaction->getid())),
            'method' => 'PUT',
        ));
        if ($editForm->handleRequest($request)->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirect($this->generateUrl('finance_transaction_show', array('id' => $transaction->getId())));
        }
        $deleteForm = $this->createDeleteForm($transaction->getId(), 'finance_transaction_delete');

        return array(
            'transaction' => $transaction,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }


    /**
     * Save order.
     *
     * @Route("/order/{field}/{type}", name="finance_transaction_sort")
     */
    public function sortAction($field, $type)
    {
        $this->setOrder('transaction', $field, $type);

        return $this->redirect($this->generateUrl('finance_transaction'));
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
     * Deletes a Transaction entity.
     *
     * @Route("/{id}/delete", name="finance_transaction_delete", requirements={"id"="\d+"})
     * @Method("DELETE")
     */
    public function deleteAction(Transaction $transaction, Request $request)
    {
        $form = $this->createDeleteForm($transaction->getId(), 'finance_transaction_delete');
        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($transaction);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('finance_transaction'));
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
