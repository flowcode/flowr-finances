<?php

namespace Flower\FinancesBundle\Service;

use Flower\FinancesBundle\Entity\Account;
use Flower\FinancesBundle\Entity\Document;
use Flower\FinancesBundle\Entity\JournalEntry;
use Flower\FinancesBundle\Entity\Transaction;
use Flower\FinancesBundle\Repository\AccountRepository;
use Flower\FinancesBundle\Repository\TransactionRepository;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Created by PhpStorm.
 * User: juanma
 * Date: 5/5/16
 * Time: 8:47 AM
 */
class TransactionService
{

    private $translator;
    private $transactionRepository;
    private $accountRepository;

    /**
     * TransactionService constructor.
     * @param $translator
     * @param $transactionRepository
     * @param $accountRepository
     */
    public function __construct(TranslatorInterface $translator, TransactionRepository $transactionRepository, AccountRepository $accountRepository)
    {
        $this->translator = $translator;
        $this->transactionRepository = $transactionRepository;
        $this->accountRepository = $accountRepository;
    }


    public function createCustomerInvoiceTransaction(Document $document)
    {
        $transaction = new Transaction();

        $description = $this->translator->trans("transaction.description.document", array(
            "%document_code%" => $document->getType()->getCode(),
            "%document_number%" => $document->getId(),
            "%document_to%" => $document->getAccount()->getName(),
        ), 'Finance');

        $transaction->setDescription($description);
        $transaction->setDate(new \DateTime());

        $journalEntryReceivable = new JournalEntry();

        $receivableAccount = $this->accountRepository->findOneBy(array(
            'subtype' => Account::SUBTYPE_ASSET_RECEIVABLE,
        ));

        $journalEntryReceivable->setAccount($receivableAccount);
        $journalEntryReceivable->setTransaction($transaction);
        $journalEntryReceivable->setCredit($document->getTotal());
        $journalEntryReceivable->setDate(new \DateTime());
        $transaction->addJournalEntry($journalEntryReceivable);

        $journalEntryCustomerAccount = new JournalEntry();
        $journalEntryCustomerAccount->setAccount($document->getAccount()->getFinanceAccount());
        $journalEntryCustomerAccount->setTransaction($transaction);
        $journalEntryCustomerAccount->setDebit($document->getTotal());
        $journalEntryCustomerAccount->setDate(new \DateTime());
        $transaction->addJournalEntry($journalEntryCustomerAccount);


        /* persist */
        $this->transactionRepository->save($transaction);

        return true;
    }
}