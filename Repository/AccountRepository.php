<?php

namespace Flower\FinancesBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Flower\FinancesBundle\Entity\Account;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

/**
 * AccountRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AccountRepository extends NestedTreeRepository
{
    public function getQuickStats($type)
    {
        $qb = $this->createQueryBuilder('a');
        $qb->select("SUM(je.debit) as debit, SUM(je.credit) as credit");
        $qb->join("a.journalEntries", "je");
        $qb->where("a.type = :type")->setParameter(":type", $type);

        return $qb->getQuery()->getResult();
    }

    public function getGeneralLedger($from = null, $to = null)
    {
        $qb = $this->createQueryBuilder('a');

        $qb->select("a.name, a.type, SUM(je.debit) as debit, SUM(je.credit) as credit");
        $qb->leftJoin("a.journalEntries", "je");
        $qb->groupBy("a.name");


        return $qb->getQuery()->getResult();
    }

    public function getLiabilityAndExpenseAccounts()
    {
        $qb = $this->createQueryBuilder('a');

        $qb->where("a.type IN (:types)")->setParameter('types', array(
            Account::TYPE_EXPENSE,
            Account::TYPE_LIABILITY,
        ));

        return $qb->getQuery()->getResult();
    }

    public function getAssetAndLiabilityAccounts()
    {
        $qb = $this->createQueryBuilder('a');

        $qb->where("a.type IN (:types)")->setParameter('types', array(
            Account::TYPE_ASSET,
            Account::TYPE_LIABILITY,
        ));

        return $qb->getQuery()->getResult();
    }

}
