<?php

namespace Flower\FinancesBundle\Controller;

use Flower\FinancesBundle\Entity\Account;
use Flower\FinancesBundle\Entity\DocumentItem;
use Flower\FinancesBundle\Entity\JournalEntry;
use Flower\FinancesBundle\Entity\Payment;
use Flower\FinancesBundle\Entity\Transaction;
use Flower\FinancesBundle\Form\Type\CustomerInvoiceType;
use Flower\FinancesBundle\Form\Type\SupplierInvoiceType;
use Flower\ModelBundle\Entity\Sales\Sale;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Flower\FinancesBundle\Entity\Document;
use Flower\FinancesBundle\Form\Type\DocumentType;
use Doctrine\ORM\QueryBuilder;

/**
 * Document controller.
 *
 * @Route("/finance/document/si")
 */
class SupplierInvoiceController extends Controller
{
    /**
     * Lists all Document entities.
     *
     * @Route("/", name="finance_document_si")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('FlowerFinancesBundle:Document')->createQueryBuilder('c');
        $qb->leftJoin('c.type', 't');
        $qb->where('t.name = :type')->setParameter('type', \Flower\FinancesBundle\Entity\DocumentType::TYPE_SUPPLIER_INVOICE);
        $this->addQueryBuilderSort($qb, 'document');
        $paginator = $this->get('knp_paginator')->paginate($qb, $request->query->get('page', 1), 20);

        return array(
            'paginator' => $paginator,
        );
    }

    /**
     * Finds and displays a Document entity.
     *
     * @Route("/{id}/show", name="finance_document_si_show", requirements={"id"="\d+"})
     * @Method("GET")
     * @Template()
     */
    public function showAction(Document $document)
    {
        $editForm = $this->createForm(new DocumentType(), $document, array(
            'action' => $this->generateUrl('finance_document_si_update', array('id' => $document->getid())),
            'method' => 'PUT',
        ));
        $deleteForm = $this->createDeleteForm($document->getId(), 'finance_document_si_delete');

        return array(

            'document' => $document,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),

        );
    }

    /**
     * Displays a form to create a new Document entity.
     *
     * @Route("/new", name="finance_document_si_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $document = new Document();
        $type = $this->getDoctrine()->getManager()->getRepository('FlowerFinancesBundle:DocumentType')->findOneBy(array(
            'name' => \Flower\FinancesBundle\Entity\DocumentType::TYPE_SUPPLIER_INVOICE
        ));
        $document->setType($type);

        $form = $this->createForm($this->get('finances.form.type.supplier_invoice'), $document);

        return array(
            'document' => $document,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a new Document entity.
     *
     * @Route("/create", name="finance_document_si_create")
     * @Method("POST")
     * @Template("FlowerFinancesBundle:SupplierInvoice:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $document = new Document();
        $type = $this->getDoctrine()->getManager()->getRepository('FlowerFinancesBundle:DocumentType')->findOneBy(array(
            'name' => \Flower\FinancesBundle\Entity\DocumentType::TYPE_SUPPLIER_INVOICE
        ));
        $document->setType($type);

        $form = $this->createForm($this->get('finances.form.type.supplier_invoice'), $document);

        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();

            foreach ($document->getItems() as $item) {
                $item->setDocument($document);
            }

            $em->persist($document);
            $em->flush();

            /* pending documents generates accounting transactions */
            if ($document->getStatus() == Document::STATUS_PENDING) {
                $this->get('finances.service.transaction')->createSupplierInvoiceTransaction($document);
            }

            return $this->redirect($this->generateUrl('finance_document_si_show', array('id' => $document->getId())));
        }

        return array(
            'document' => $document,
            'form' => $form->createView(),
        );
    }


    /**
     * Displays a form to edit an existing Document entity.
     *
     * @Route("/{id}/edit", name="finance_document_si_edit", requirements={"id"="\d+"})
     * @Method("GET")
     * @Template()
     */
    public function editAction(Document $document)
    {
        $editForm = $this->createForm(new DocumentType(), $document, array(
            'action' => $this->generateUrl('finance_document_si_update', array('id' => $document->getid())),
            'method' => 'PUT',
        ));
        $deleteForm = $this->createDeleteForm($document->getId(), 'finance_document_si_delete');

        return array(
            'document' => $document,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Document entity.
     *
     * @Route("/{id}/update", name="finance_document_si_update", requirements={"id"="\d+"})
     * @Method("PUT")
     * @Template("FlowerFinancesBundle:Document:edit.html.twig")
     */
    public function updateAction(Document $document, Request $request)
    {
        $editForm = $this->createForm(new DocumentType(), $document, array(
            'action' => $this->generateUrl('finance_document_si_update', array('id' => $document->getid())),
            'method' => 'PUT',
        ));

        if ($editForm->handleRequest($request)->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirect($this->generateUrl('finance_document_si_show', array('id' => $document->getId())));
        }
        $deleteForm = $this->createDeleteForm($document->getId(), 'finance_document_si_delete');

        return array(
            'document' => $document,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Document entity.
     *
     * @Route("/{id}/payments/new", name="finance_document_si_payments_new", requirements={"id"="\d+"})
     * @Method("GET")
     * @Template()
     */
    public function addPaymentAction(Document $document)
    {
        $em = $this->getDoctrine()->getManager();
        $assetAccounts = $em->getRepository('FlowerFinancesBundle:Account')->getAssetAndLiabilityAccounts();

        $expenseAccounts = $em->getRepository('FlowerFinancesBundle:Account')->findBy(array(
            'type' => Account::TYPE_EXPENSE,
        ));

        return array(
            'assetAccounts' => $assetAccounts,
            'expenseAccounts' => $expenseAccounts,
            'document' => $document,
        );
    }

    /**
     * Displays a form to edit an existing Document entity.
     *
     * @Route("/{id}/payments/create", name="finance_document_si_payments_create", requirements={"id"="\d+"})
     * @Method("POST")
     * @Template("FlowerFinancesBundle:SupplierInvoice:addPayment.html.twig")
     */
    public function paymentCreateAction(Request $request, Document $document)
    {

        $em = $this->getDoctrine()->getManager();

        if ($request->get('amount')) {
            $amount = $request->get('amount');
            $date = new \DateTime($request->get('date'));
            $assetAccountId = $request->get('asset_account_id');
            $expenseAccountId = $request->get('expense_account_id');

            /* financial transactions */
            $transaction = new Transaction();
            $transaction->setDate($date);
            $transaction->setDescription('Payment ' . $document->__toString());
            $journalEntryPayable = new JournalEntry();

            $payableAccount = $em->getRepository('FlowerFinancesBundle:Account')->findOneBy(array(
                'subtype' => Account::SUBTYPE_LIABILITY_PAYABLE,
            ));

            $journalEntryPayable->setAccount($payableAccount);
            $journalEntryPayable->setTransaction($transaction);
            $journalEntryPayable->setCredit($amount);
            $journalEntryPayable->setDate($date);
            $transaction->addJournalEntry($journalEntryPayable);

            $journalEntrySupplierAccount = new JournalEntry();
            $journalEntrySupplierAccount->setAccount($document->getSupplier()->getFinanceAccount());
            $journalEntrySupplierAccount->setTransaction($transaction);
            $journalEntrySupplierAccount->setDebit($amount);
            $journalEntrySupplierAccount->setDate($date);
            $transaction->addJournalEntry($journalEntrySupplierAccount);

            $expenseAccount = $em->getRepository('FlowerFinancesBundle:Account')->find($expenseAccountId);
            $expenseEntry = new JournalEntry();
            $expenseEntry->setAccount($expenseAccount);
            $expenseEntry->setTransaction($transaction);
            $expenseEntry->setDebit($amount);
            $expenseEntry->setDate($date);
            $transaction->addJournalEntry($expenseEntry);

            $assetAccount = $em->getRepository('FlowerFinancesBundle:Account')->find($assetAccountId);
            $assetEntry = new JournalEntry();
            $assetEntry->setAccount($assetAccount);
            $assetEntry->setCredit($amount);
            $assetEntry->setDate($date);
            $assetEntry->setTransaction($transaction);
            $transaction->addJournalEntry($assetEntry);

            $em->persist($transaction);
            $em->flush();

            $payment = new Payment();
            $payment->setAmount($amount);
            $payment->setType(Payment::TYPE_EXPENSE);
            $payment->setName('Payment ' . $document->__toString());
            $payment->setDescription('Payment ' . $document->__toString());
            $payment->setDate($date);
            $payment->addDocument($document);
            $document->addPayment($payment);

            $em->persist($payment);

            $paymentsAmount = 0;
            foreach ($document->getPayments() as $pay) {
                $paymentsAmount += (float)$pay->getAmount();
            }

            if ($paymentsAmount >= $document->getTotal()) {
                $document->setStatus(Document::STATUS_PAID);
            }

            $em->flush();

            return $this->redirect($this->generateUrl('finance_document_si_show', array(
                'id' => $document->getId(),
            )));

        }

        $assetAccounts = $em->getRepository('FlowerFinancesBundle:Account')->findBy(array(
            'type' => Account::TYPE_ASSET,
        ));

        return array(
            'assetAccounts' => $assetAccounts,
            'document' => $document,
        );
    }


    /**
     * Save order.
     *
     * @Route("/order/{field}/{type}", name="finance_document_si_sort")
     */
    public function sortAction($field, $type)
    {
        $this->setOrder('document', $field, $type);

        return $this->redirect($this->generateUrl('finance_document_si'));
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
     * Deletes a Document entity.
     *
     * @Route("/{id}/delete", name="finance_document_si_delete", requirements={"id"="\d+"})
     * @Method("DELETE")
     */
    public function deleteAction(Document $document, Request $request)
    {
        $form = $this->createDeleteForm($document->getId(), 'finance_document_si_delete');
        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($document);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('finance_document_si'));
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
