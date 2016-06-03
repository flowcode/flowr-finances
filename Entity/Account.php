<?php

namespace Flower\FinancesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation\Groups;

/**
 * Account
 *
 * @Gedmo\Tree(type="nested")
 * @ORM\Table(name="finance_account")
 * @ORM\Entity(repositoryClass="Flower\FinancesBundle\Repository\AccountRepository")
 */
class Account
{

    const TYPE_ASSET = 1;
    const TYPE_LIABILITY = 2;
    const TYPE_INCOME = 3;
    const TYPE_EXPENSE = 4;
    const TYPE_EQUITY = 5;

    const SUBTYPE_ASSET_RECEIVABLE = 1;
    const SUBTYPE_LIABILITY_PAYABLE = 2;
    const SUBTYPE_INCOME_SALES = 3;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"api","public_api"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=255, nullable=true)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Groups({"api","public_api"})
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="integer")
     */
    private $type;

    /**
     * @var integer
     *
     * @ORM\Column(name="subtype", type="integer", nullable=true)
     */
    private $subtype;

    /**
     * @var boolean
     *
     * @ORM\Column(name="editable", type="boolean")
     * @Groups({"api","public_api"})
     */
    protected $editable;

    /**
     * @var integer
     *
     * @Gedmo\TreeLeft
     * @ORM\Column(name="lft", type="integer")
     */
    protected $lft;

    /**
     * @var integer
     *
     * @Gedmo\TreeRight
     * @ORM\Column(name="rgt", type="integer")
     */
    protected $rgt;
    /**
     * @var integer
     *
     * @Gedmo\TreeLevel
     * @ORM\Column(name="lvl", type="integer")
     */
    protected $lvl;
    /**
     * @Gedmo\TreeRoot
     * @ORM\Column(name="root", type="integer", nullable=true)
     */
    protected $root;

    /**
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="\Flower\FinancesBundle\Entity\Account", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $parent;

    /**
     * @ORM\OneToMany(targetEntity="\Flower\FinancesBundle\Entity\Account", mappedBy="parent")
     * @ORM\OrderBy({"lft" = "ASC"})
     */
    protected $children;

    /**
     * @OneToMany(targetEntity="\Flower\FinancesBundle\Entity\JournalEntry", mappedBy="account")
     */
    protected $journalEntries;

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
     * Constructor
     */
    public function __construct()
    {
        $this->journalEntries = new \Doctrine\Common\Collections\ArrayCollection();
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
        $this->editable = true;
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
     * Get id
     *
     * @return string
     */
    public function getTypeTitle()
    {
        $typeTitle = "";
        switch ($this->type) {
            case 1:
                $typeTitle = "account_type_asset";
                break;
            case 2:
                $typeTitle = "account_type_liability";
                break;
            case 3:
                $typeTitle = "account_type_income";
                break;
            case 4:
                $typeTitle = "account_type_expense";
                break;
            case 5:
                $typeTitle = "account_type_equity";
                break;
        }
        return $typeTitle;
    }

    /**
     * Set code
     *
     * @param string $code
     * @return Account
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

    /**
     * Set name
     *
     * @param string $name
     * @return Account
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
     * Set type
     *
     * @param integer $type
     * @return Account
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }


    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Account
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
     * @return Account
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
     * Add journalEntries
     *
     * @param \Flower\FinancesBundle\Entity\JournalEntry $journalEntries
     * @return Account
     */
    public function addJournalEntry(\Flower\FinancesBundle\Entity\JournalEntry $journalEntries)
    {
        $this->journalEntries[] = $journalEntries;

        return $this;
    }

    /**
     * Remove journalEntries
     *
     * @param \Flower\FinancesBundle\Entity\JournalEntry $journalEntries
     */
    public function removeJournalEntry(\Flower\FinancesBundle\Entity\JournalEntry $journalEntries)
    {
        $this->journalEntries->removeElement($journalEntries);
    }

    /**
     * Get journalEntries
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getJournalEntries()
    {
        return $this->journalEntries;
    }

    /**
     * @return mixed
     */
    public function getEditable()
    {
        return $this->editable;
    }

    /**
     * @param mixed $editable
     */
    public function setEditable($editable)
    {
        $this->editable = $editable;
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
    

    function __toString()
    {
        return $this->getName();
    }



    /**
     * Set lft
     *
     * @param integer $lft
     * @return Account
     */
    public function setLft($lft)
    {
        $this->lft = $lft;

        return $this;
    }

    /**
     * Get lft
     *
     * @return integer 
     */
    public function getLft()
    {
        return $this->lft;
    }

    /**
     * Set rgt
     *
     * @param integer $rgt
     * @return Account
     */
    public function setRgt($rgt)
    {
        $this->rgt = $rgt;

        return $this;
    }

    /**
     * Get rgt
     *
     * @return integer 
     */
    public function getRgt()
    {
        return $this->rgt;
    }

    /**
     * Set lvl
     *
     * @param integer $lvl
     * @return Account
     */
    public function setLvl($lvl)
    {
        $this->lvl = $lvl;

        return $this;
    }

    /**
     * Get lvl
     *
     * @return integer 
     */
    public function getLvl()
    {
        return $this->lvl;
    }

    /**
     * Set root
     *
     * @param integer $root
     * @return Account
     */
    public function setRoot($root)
    {
        $this->root = $root;

        return $this;
    }

    /**
     * Get root
     *
     * @return integer 
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * Set parent
     *
     * @param \Flower\FinancesBundle\Entity\Account $parent
     * @return Account
     */
    public function setParent(\Flower\FinancesBundle\Entity\Account $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Flower\FinancesBundle\Entity\Account 
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add children
     *
     * @param \Flower\FinancesBundle\Entity\Account $children
     * @return Account
     */
    public function addChild(\Flower\FinancesBundle\Entity\Account $children)
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     *
     * @param \Flower\FinancesBundle\Entity\Account $children
     */
    public function removeChild(\Flower\FinancesBundle\Entity\Account $children)
    {
        $this->children->removeElement($children);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getChildren()
    {
        return $this->children;
    }
}
