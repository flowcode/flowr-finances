<?php

namespace Flower\FinancesBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Flower\FinancesBundle\Entity\JournalEntry;
use Flower\FinancesBundle\Form\Type\JournalEntryType;
use Doctrine\ORM\QueryBuilder;

/**
 * JournalEntry controller.
 *
 * @Route("/finance/journal_entry")
 */
class JournalEntryController extends Controller
{
    /**
     * Lists all JournalEntry entities.
     *
     * @Route("/", name="finance_journal_entry")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('FlowerFinancesBundle:JournalEntry')->createQueryBuilder('j');
        $this->addQueryBuilderSort($qb, 'journalentry');
        $paginator = $this->get('knp_paginator')->paginate($qb, $request->query->get('page', 1), 20);
        
        return array(
            'paginator' => $paginator,
        );
    }

    /**
     * Finds and displays a JournalEntry entity.
     *
     * @Route("/{id}/show", name="finance_journal_entry_show", requirements={"id"="\d+"})
     * @Method("GET")
     * @Template()
     */
    public function showAction(JournalEntry $journalentry)
    {
        $editForm = $this->createForm(new JournalEntryType(), $journalentry, array(
            'action' => $this->generateUrl('finance_journal_entry_update', array('id' => $journalentry->getid())),
            'method' => 'PUT',
        ));
        $deleteForm = $this->createDeleteForm($journalentry->getId(), 'finance_journal_entry_delete');

        return array(

        'journalentry' => $journalentry,
        'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),

        );
    }

    /**
     * Displays a form to create a new JournalEntry entity.
     *
     * @Route("/new", name="finance_journal_entry_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $journalentry = new JournalEntry();
        $form = $this->createForm(new JournalEntryType(), $journalentry);

        return array(
            'journalentry' => $journalentry,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new JournalEntry entity.
     *
     * @Route("/create", name="finance_journal_entry_create")
     * @Method("POST")
     * @Template("FlowerFinancesBundle:JournalEntry:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $journalentry = new JournalEntry();
        $form = $this->createForm(new JournalEntryType(), $journalentry);
        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($journalentry);
            $em->flush();

            return $this->redirect($this->generateUrl('finance_journal_entry_show', array('id' => $journalentry->getId())));
        }

        return array(
            'journalentry' => $journalentry,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing JournalEntry entity.
     *
     * @Route("/{id}/edit", name="finance_journal_entry_edit", requirements={"id"="\d+"})
     * @Method("GET")
     * @Template()
     */
    public function editAction(JournalEntry $journalentry)
    {
        $editForm = $this->createForm(new JournalEntryType(), $journalentry, array(
            'action' => $this->generateUrl('finance_journal_entry_update', array('id' => $journalentry->getid())),
            'method' => 'PUT',
        ));
        $deleteForm = $this->createDeleteForm($journalentry->getId(), 'finance_journal_entry_delete');

        return array(
            'journalentry' => $journalentry,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing JournalEntry entity.
     *
     * @Route("/{id}/update", name="finance_journal_entry_update", requirements={"id"="\d+"})
     * @Method("PUT")
     * @Template("FlowerFinancesBundle:JournalEntry:edit.html.twig")
     */
    public function updateAction(JournalEntry $journalentry, Request $request)
    {
        $editForm = $this->createForm(new JournalEntryType(), $journalentry, array(
            'action' => $this->generateUrl('finance_journal_entry_update', array('id' => $journalentry->getid())),
            'method' => 'PUT',
        ));
        if ($editForm->handleRequest($request)->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirect($this->generateUrl('finance_journal_entry_show', array('id' => $journalentry->getId())));
        }
        $deleteForm = $this->createDeleteForm($journalentry->getId(), 'finance_journal_entry_delete');

        return array(
            'journalentry' => $journalentry,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }


    /**
     * Save order.
     *
     * @Route("/order/{field}/{type}", name="finance_journal_entry_sort")
     */
    public function sortAction($field, $type)
    {
        $this->setOrder('journalentry', $field, $type);

        return $this->redirect($this->generateUrl('finance_journal_entry'));
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
     * Deletes a JournalEntry entity.
     *
     * @Route("/{id}/delete", name="finance_journal_entry_delete", requirements={"id"="\d+"})
     * @Method("DELETE")
     */
    public function deleteAction(JournalEntry $journalentry, Request $request)
    {
        $form = $this->createDeleteForm($journalentry->getId(), 'finance_journal_entry_delete');
        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($journalentry);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('finance_journal_entry'));
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
