<?php

namespace Flower\FinancesBundle\Controller;

use Flower\FinancesBundle\Entity\DocumentItem;
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
 * @Route("/finance/document")
 */
class DocumentController extends Controller
{
    /**
     * Lists all Document entities.
     *
     * @Route("/", name="finance_document")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('FlowerFinancesBundle:Document')->createQueryBuilder('c');
        $this->addQueryBuilderSort($qb, 'document');
        $paginator = $this->get('knp_paginator')->paginate($qb, $request->query->get('page', 1), 20);

        return array(
            'paginator' => $paginator,
        );
    }

    /**
     * Finds and displays a Document entity.
     *
     * @Route("/{id}/show", name="finance_document_show", requirements={"id"="\d+"})
     * @Method("GET")
     * @Template()
     */
    public function showAction(Document $document)
    {
        $editForm = $this->createForm(new DocumentType(), $document, array(
            'action' => $this->generateUrl('finance_document_update', array('id' => $document->getid())),
            'method' => 'PUT',
        ));
        $deleteForm = $this->createDeleteForm($document->getId(), 'finance_document_delete');

        return array(

            'document' => $document,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),

        );
    }

    /**
     * Displays a form to create a new Document entity.
     *
     * @Route("/new", name="finance_document_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $document = new Document();
        $form = $this->createForm(new DocumentType(), $document);

        return array(
            'document' => $document,
            'form' => $form->createView(),
        );
    }


    /**
     * Creates a new Document entity.
     *
     * @Route("/create/sale/{id}", name="finance_document_create_from_sale")
     * @Method("GET")
     * @Template("FlowerFinancesBundle:Document:new.html.twig")
     */
    public function createFromSaleAction(Request $request, Sale $sale)
    {
        $document = new Document();

        $document->setAccount($sale->getAccount());
        $document->setDiscount($sale->getDiscount());
        $document->setTax($sale->getTax());
        $document->setTotal($sale->getTotal());
        $document->setTotalDiscount($sale->getTotalDiscount());
        $document->setTotalWithTax($sale->getTotalWithTax());
        $document->setType(Document::TYPE_CUSTOMER_INVOICE);
        $document->setSale($sale);

        foreach ($sale->getSaleItems() as $saleItem) {
            $invoiceItem = new DocumentItem();

            $invoiceItem->setProduct($saleItem->getProduct());
            $invoiceItem->setService($saleItem->getService());
            $invoiceItem->setTotal($saleItem->getTotal());
            $invoiceItem->setDocument($document);
            $invoiceItem->setUnitPrice($saleItem->getUnitPrice());
            $invoiceItem->setUnits($saleItem->getUnits());

            $document->addItem($invoiceItem);

        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($document);
        $em->flush();

        $sale->setDocument($document);
        $em->flush();

        return $this->redirect($this->generateUrl('finance_document_show', array('id' => $document->getId())));

    }

    /**
     * Creates a new Document entity.
     *
     * @Route("/create", name="finance_document_create")
     * @Method("POST")
     * @Template("FlowerFinancesBundle:Document:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $document = new Document();
        
        $form = $this->createForm(new DocumentType(), $document);
        
        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();

            foreach ($document->getItems() as $item) {
                $item->setDocument($document);
            }

            $em->persist($document);
            $em->flush();

            return $this->redirect($this->generateUrl('finance_document_show', array('id' => $document->getId())));
        }

        return array(
            'document' => $document,
            'form' => $form->createView(),
        );
    }


    /**
     * Displays a form to edit an existing Document entity.
     *
     * @Route("/{id}/edit", name="finance_document_edit", requirements={"id"="\d+"})
     * @Method("GET")
     * @Template()
     */
    public function editAction(Document $document)
    {
        $editForm = $this->createForm(new DocumentType(), $document, array(
            'action' => $this->generateUrl('finance_document_update', array('id' => $document->getid())),
            'method' => 'PUT',
        ));
        $deleteForm = $this->createDeleteForm($document->getId(), 'finance_document_delete');

        return array(
            'document' => $document,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Document entity.
     *
     * @Route("/{id}/update", name="finance_document_update", requirements={"id"="\d+"})
     * @Method("PUT")
     * @Template("FlowerFinancesBundle:Document:edit.html.twig")
     */
    public function updateAction(Document $document, Request $request)
    {
        $editForm = $this->createForm(new DocumentType(), $document, array(
            'action' => $this->generateUrl('finance_document_update', array('id' => $document->getid())),
            'method' => 'PUT',
        ));

        if ($editForm->handleRequest($request)->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirect($this->generateUrl('finance_document_show', array('id' => $document->getId())));
        }
        $deleteForm = $this->createDeleteForm($document->getId(), 'finance_document_delete');

        return array(
            'document' => $document,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }


    /**
     * Save order.
     *
     * @Route("/order/{field}/{type}", name="finance_document_sort")
     */
    public function sortAction($field, $type)
    {
        $this->setOrder('document', $field, $type);

        return $this->redirect($this->generateUrl('finance_document'));
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
     * @Route("/{id}/delete", name="finance_document_delete", requirements={"id"="\d+"})
     * @Method("DELETE")
     */
    public function deleteAction(Document $document, Request $request)
    {
        $form = $this->createDeleteForm($document->getId(), 'finance_document_delete');
        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($document);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('finance_document'));
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
