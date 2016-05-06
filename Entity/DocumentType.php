<?php

namespace Flower\FinancesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DocumentType
 *
 * @ORM\Table(name="finance_document_type")
 * @ORM\Entity(repositoryClass="Flower\FinancesBundle\Repository\DocumentTypeRepository")
 */
class DocumentType
{

    const TYPE_CUSTOMER_INVOICE = 'customer_invoice';
    const TYPE_CUSTOMER_CREDIT_NOTE = 'customer_credit_note';
    const TYPE_CUSTOMER_DEBIT_NOTE = 'customer_debit_note';
    const TYPE_CUSTOMER_RECEIPT = 'customer_receipt';

    const TYPE_SUPPLIER_INVOICE = 'supplier_invoice';
    const TYPE_SUPPLIER_CREDIT_NOTE = 'supplier_credit_note';
    const TYPE_SUPPLIER_DEBIT_NOTE = 'supplier_debit_note';
    const TYPE_SUPPLIER_RECEIPT = 'supplier_receipt';

    const TYPE_RECEIPT = 'receipt';

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=100, nullable=true)
     */
    private $code;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return DocumentType
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set code
     *
     * @param string $code
     * @return DocumentType
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    function __toString()
    {
        return $this->getName();
    }


}
