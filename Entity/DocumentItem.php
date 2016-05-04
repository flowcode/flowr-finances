<?php

namespace Flower\FinancesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation\Groups;

/**
 * DocumentItem
 *
 * @ORM\Table(name="finance_document_item")
 * @ORM\Entity(repositoryClass="Flower\FinancesBundle\Repository\DocumentItemRepository")
 */
class DocumentItem
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="\Flower\ModelBundle\Entity\Stock\Product", cascade={"persist"})
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id", nullable=true)
     * @Groups({"public_api"})
     */
    protected $product;

    /**
     * @ORM\ManyToOne(targetEntity="\Flower\ModelBundle\Entity\Stock\Service", cascade={"persist"})
     * @ORM\JoinColumn(name="service_id", referencedColumnName="id", nullable=true)
     * @Groups({"public_api"})
     */
    protected $service;

    /**
     * @var integer
     *
     * @ORM\Column(name="units", type="integer", nullable=true)
     */
    private $units;

    /**
     * @var float
     *
     * @ORM\Column(name="unitPrice", type="float", nullable=true)
     */
    private $unitPrice;

    /**
     * @var float
     *
     * @ORM\Column(name="total", type="float", nullable=true)
     */
    private $total;

    /**
     * @ORM\ManyToOne(targetEntity="\Flower\FinancesBundle\Entity\Document", inversedBy="items")
     * @ORM\JoinColumn(name="customer_invoice_id", referencedColumnName="id")
     */
    protected $document;


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
     * Set units
     *
     * @param integer $units
     * @return DocumentItem
     */
    public function setUnits($units)
    {
        $this->units = $units;

        return $this;
    }

    /**
     * Get units
     *
     * @return integer
     */
    public function getUnits()
    {
        return $this->units;
    }

    /**
     * Set unitPrice
     *
     * @param float $unitPrice
     * @return DocumentItem
     */
    public function setUnitPrice($unitPrice)
    {
        $this->unitPrice = $unitPrice;

        return $this;
    }

    /**
     * Get unitPrice
     *
     * @return float
     */
    public function getUnitPrice()
    {
        return $this->unitPrice;
    }

    /**
     * Set total
     *
     * @param float $total
     * @return DocumentItem
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total
     *
     * @return float
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set document
     *
     * @param \Flower\FinancesBundle\Entity\Document $document
     * @return DocumentItem
     */
    public function setDocument(\Flower\FinancesBundle\Entity\Document $document = null)
    {
        $this->document = $document;

        return $this;
    }

    /**
     * Get document
     *
     * @return \Flower\FinancesBundle\Entity\Document
     */
    public function getDocument()
    {
        return $this->document;
    }

    /**
     * Set product
     *
     * @param \Flower\ModelBundle\Entity\Stock\Product $product
     * @return DocumentItem
     */
    public function setProduct(\Flower\ModelBundle\Entity\Stock\Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \Flower\ModelBundle\Entity\Stock\Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set service
     *
     * @param \Flower\ModelBundle\Entity\Stock\Service $service
     * @return DocumentItem
     */
    public function setService(\Flower\ModelBundle\Entity\Stock\Service $service = null)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * Get service
     *
     * @return \Flower\ModelBundle\Entity\Stock\Service
     */
    public function getService()
    {
        return $this->service;
    }
}
