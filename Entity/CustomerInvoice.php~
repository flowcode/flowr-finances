<?php

namespace Flower\FinancesBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation\Groups;

/**
 * CustomerInvoice
 *
 * @ORM\Table(name="finance_customer_invoice")
 * @ORM\Entity(repositoryClass="Flower\FinancesBundle\Repository\CustomerInvoiceRepository")
 */
class CustomerInvoice
{

    const STATUS_DRAFT = 0;
    const STATUS_PENDING = 1;
    const STATUS_PAID = 2;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    private $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated", type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    private $updated;

    /**
     * @var float
     *
     * @ORM\Column(name="total", type="float", nullable=true)
     */
    private $total;

    /**
     * @var float
     *
     * @ORM\Column(name="totalWithTax", type="float", nullable=true)
     */
    private $totalWithTax;

    /**
     * @var float
     *
     * @ORM\Column(name="discount", type="float", nullable=true)
     */
    private $discount;

    /**
     * @var float
     *
     * @ORM\Column(name="totalDiscount", type="float", nullable=true)
     */
    private $totalDiscount;

    /**
     * @var float
     *
     * @ORM\Column(name="tax", type="float", nullable=true)
     */
    private $tax;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status;

    /**
     * @OneToMany(targetEntity="\Flower\FinancesBundle\Entity\CustomerInvoiceItem", mappedBy="customerInvoice")
     *
     */
    protected $items;

    public function __construct()
    {
        $this->status = self::STATUS_DRAFT;
        $this->items = new ArrayCollection();
    }


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
     * Set created
     *
     * @param \DateTime $created
     * @return CustomerInvoice
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     * @return CustomerInvoice
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set total
     *
     * @param float $total
     * @return CustomerInvoice
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
     * Set totalWithTax
     *
     * @param float $totalWithTax
     * @return CustomerInvoice
     */
    public function setTotalWithTax($totalWithTax)
    {
        $this->totalWithTax = $totalWithTax;

        return $this;
    }

    /**
     * Get totalWithTax
     *
     * @return float
     */
    public function getTotalWithTax()
    {
        return $this->totalWithTax;
    }

    /**
     * Set discount
     *
     * @param float $discount
     * @return CustomerInvoice
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;

        return $this;
    }

    /**
     * Get discount
     *
     * @return float
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * Set totalDiscount
     *
     * @param float $totalDiscount
     * @return CustomerInvoice
     */
    public function setTotalDiscount($totalDiscount)
    {
        $this->totalDiscount = $totalDiscount;

        return $this;
    }

    /**
     * Get totalDiscount
     *
     * @return float
     */
    public function getTotalDiscount()
    {
        return $this->totalDiscount;
    }

    /**
     * Set tax
     *
     * @param float $tax
     * @return CustomerInvoice
     */
    public function setTax($tax)
    {
        $this->tax = $tax;

        return $this;
    }

    /**
     * Get tax
     *
     * @return float
     */
    public function getTax()
    {
        return $this->tax;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return CustomerInvoice
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Add items
     *
     * @param \Flower\FinancesBundle\Entity\CustomerInvoiceItem $items
     * @return CustomerInvoice
     */
    public function addItem(\Flower\FinancesBundle\Entity\CustomerInvoiceItem $items)
    {
        $this->items[] = $items;

        return $this;
    }

    /**
     * Remove items
     *
     * @param \Flower\FinancesBundle\Entity\CustomerInvoiceItem $items
     */
    public function removeItem(\Flower\FinancesBundle\Entity\CustomerInvoiceItem $items)
    {
        $this->items->removeElement($items);
    }

    /**
     * Get items
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getItems()
    {
        return $this->items;
    }
}
