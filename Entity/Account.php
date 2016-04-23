<?php

namespace Flower\FinancesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation\Groups;

/**
 * Account
 *
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="integer")
     */
    private $type;

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

    function __toString()
    {
        return $this->getName();
    }


}