<?php

namespace Flower\FinancesBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToOne;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation\Groups;

/**
 * Document
 *
 * @ORM\Table(name="finance_document")
 * @ORM\Entity(repositoryClass="Flower\FinancesBundle\Repository\DocumentRepository")
 */
class Document
{

    const SUBTYPE_INVOICE_A = 'a';
    const SUBTYPE_INVOICE_B = 'b';
    const SUBTYPE_INVOICE_C = 'c';

    const STATUS_DRAFT = 'status_draft';
    const STATUS_PENDING = 'status_pending';
    const STATUS_PAID = 'status_paid';

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
     * @ORM\Column(name="code", type="string", length=255, nullable=true)
     */
    private $code;

    /**
     * @ORM\ManyToOne(targetEntity="\Flower\FinancesBundle\Entity\DocumentType")
     * @ORM\JoinColumn(name="document_type_id", referencedColumnName="id")
     * @Groups({"public_api"})
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="subtype", type="string", length=255, nullable=true)
     */
    private $subtype;

    /**
     * @ManyToMany(targetEntity="\Flower\FinancesBundle\Entity\Payment", mappedBy="documents")
     */
    private $payments;

    /**
     * @ORM\ManyToOne(targetEntity="\Flower\ModelBundle\Entity\Clients\Account")
     * @ORM\JoinColumn(name="account", referencedColumnName="id")
     * @Groups({"public_api"})
     */
    private $account;

    /**
     * @ORM\ManyToOne(targetEntity="\Flower\ModelBundle\Entity\Clients\Account")
     * @ORM\JoinColumn(name="supplier_id", referencedColumnName="id")
     * @Groups({"public_api"})
     */
    private $supplier;

    /**
     * @ManyToOne(targetEntity="\Flower\FinancesBundle\Entity\Account")
     * @JoinColumn(name="finance_account_id", referencedColumnName="id")
     */
    private $financeAccount;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="due_date", type="datetime", nullable=true)
     */
    private $dueDate;

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
     * @OneToOne(targetEntity="\Flower\ModelBundle\Entity\Sales\Sale", mappedBy="document")
     */
    private $sale;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255)
     */
    private $status;

    /**
     * @OneToMany(targetEntity="\Flower\FinancesBundle\Entity\DocumentItem", mappedBy="document", cascade={"persist"})
     *
     */
    protected $items;

    public function __construct()
    {
        $this->status = self::STATUS_DRAFT;
        $this->items = new ArrayCollection();
        $this->payments = new ArrayCollection();
        $this->date = new \DateTime();
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
     * @return Document
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
     * @return Document
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
     * @return Document
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
     * @return Document
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
     * @return Document
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
     * @return Document
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
     * @return Document
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
     * @param string $status
     * @return Document
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Add items
     *
     * @param \Flower\FinancesBundle\Entity\DocumentItem $items
     * @return Document
     */
    public function addItem(\Flower\FinancesBundle\Entity\DocumentItem $items)
    {
        $this->items[] = $items;

        return $this;
    }

    /**
     * Remove items
     *
     * @param \Flower\FinancesBundle\Entity\DocumentItem $items
     */
    public function removeItem(\Flower\FinancesBundle\Entity\DocumentItem $items)
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

    /**
     * Set sale
     *
     * @param \Flower\ModelBundle\Entity\Sales\Sale $sale
     * @return Document
     */
    public function setSale(\Flower\ModelBundle\Entity\Sales\Sale $sale = null)
    {
        $this->sale = $sale;

        return $this;
    }

    /**
     * Get sale
     *
     * @return \Flower\ModelBundle\Entity\Sales\Sale
     */
    public function getSale()
    {
        return $this->sale;
    }

    /**
     * Set account
     *
     * @param \Flower\ModelBundle\Entity\Clients\Account $account
     * @return Document
     */
    public function setAccount(\Flower\ModelBundle\Entity\Clients\Account $account = null)
    {
        $this->account = $account;

        return $this;
    }

    /**
     * Get account
     *
     * @return \Flower\ModelBundle\Entity\Clients\Account
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Set type
     *
     * @param \Flower\FinancesBundle\Entity\DocumentType $type
     * @return Document
     */
    public function setType(\Flower\FinancesBundle\Entity\DocumentType $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \Flower\FinancesBundle\Entity\DocumentType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Add payments
     *
     * @param \Flower\FinancesBundle\Entity\Payment $payment
     * @return Document
     */
    public function addPayment(\Flower\FinancesBundle\Entity\Payment $payment)
    {
        $this->payments[] = $payment;

        return $this;
    }

    /**
     * Remove payments
     *
     * @param \Flower\FinancesBundle\Entity\Payment $payment
     */
    public function removePayment(\Flower\FinancesBundle\Entity\Payment $payment)
    {
        $this->payments->removeElement($payment);
    }

    /**
     * @return mixed
     */
    public function getSubtype()
    {
        return $this->subtype;
    }

    /**
     * @param mixed $subtype
     */
    public function setSubtype($subtype)
    {
        $this->subtype = $subtype;
    }

    /**
     * @return mixed
     */
    public function getSupplier()
    {
        return $this->supplier;
    }

    /**
     * @param mixed $supplier
     */
    public function setSupplier($supplier)
    {
        $this->supplier = $supplier;
    }


    /**
     * Get payments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPayments()
    {
        return $this->payments;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return \DateTime
     */
    public function getDueDate()
    {
        return $this->dueDate;
    }

    /**
     * @param \DateTime $dueDate
     */
    public function setDueDate($dueDate)
    {
        $this->dueDate = $dueDate;
    }

    /**
     * Get Payment Balance.
     *
     * @return float
     */
    function getBalance()
    {
        $paymentAmount = 0;
        foreach ($this->getPayments() as $payment) {
            $paymentAmount += $payment->getAmount();
        }

        $balance = $this->getTotal() - $paymentAmount;
        return $balance;
    }


    function __toString()
    {
        $name = $this->getType()->getCode();
        $name .= " #" . $this->getId();
        if ($this->getAccount()) {
            $name .= " " . $this->getAccount()->getName();
        }
        if ($this->getSupplier()) {
            $name .= " " . $this->getSupplier()->getName();
        }
        return $name;
    }


    /**
     * Set financeAccount
     *
     * @param \Flower\FinancesBundle\Entity\Account $financeAccount
     * @return Document
     */
    public function setFinanceAccount(\Flower\FinancesBundle\Entity\Account $financeAccount = null)
    {
        $this->financeAccount = $financeAccount;

        return $this;
    }

    /**
     * Get financeAccount
     *
     * @return \Flower\FinancesBundle\Entity\Account
     */
    public function getFinanceAccount()
    {
        return $this->financeAccount;
    }
}
