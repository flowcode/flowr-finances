<?php

namespace Flower\FinancesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation\Groups;

/**
 * Transaction
 *
 * @ORM\Table(name="finance_transaction")
 * @ORM\Entity(repositoryClass="Flower\FinancesBundle\Repository\TransactionRepository")
 */
class Transaction
{

    const CIRCUIT_ONE = 1;
    const CIRCUIT_TWO = 2;

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
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @OneToMany(targetEntity="\Flower\FinancesBundle\Entity\JournalEntry", mappedBy="transaction", cascade={"persist"})
     */
    protected $journalEntries;

    /**
     * @var integer
     *
     * @ORM\Column(name="circuit", type="integer")
     * @Groups({"public_api"})
     */
    protected $circuit;

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
        $this->circuit = self::CIRCUIT_ONE;
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
     * Set description
     *
     * @param string $description
     * @return Transaction
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Transaction
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Transaction
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
     * @return Transaction
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
     * @param \Flower\FinancesBundle\Entity\JournalEntry $journalEntry
     * @return Transaction
     */
    public function addJournalEntry(\Flower\FinancesBundle\Entity\JournalEntry $journalEntry)
    {
        $this->journalEntries->add($journalEntry);

        return $this;
    }

    /**
     * Remove journalEntries
     *
     * @param \Flower\FinancesBundle\Entity\JournalEntry $journalEntry
     */
    public function removeJournalEntry(\Flower\FinancesBundle\Entity\JournalEntry $journalEntry)
    {
        $this->journalEntries->removeElement($journalEntry);
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
        return $this->getDescription();
    }

    /**
     * @return int
     */
    public function getCircuit()
    {
        return $this->circuit;
    }

    /**
     * @param int $circuit
     */
    public function setCircuit($circuit)
    {
        $this->circuit = $circuit;
    }

    
}
