<?php

namespace App\BusinessLogic;

use App\Entity\Bank;
use Doctrine\Persistence\ManagerRegistry;

class BankBusinessLogic
{
    private $registry;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function addAccount(string $playerName)
    {
        $entityManager = $this->registry->getManager();
        $account = new Bank();
        $account->setPlayerName($playerName);
        $account->setCredit(0);

        $entityManager->persist($account);
        $entityManager->flush();
    }

    public function updateDbWithNewCredit(int $credit, string $playerName)
    {
        if ($playerName === "anonymous"){
            return;
        }
        $account = $this->getAccount($playerName);
        $account->addCredit($credit);

        $entityManager = $this->registry->getManager();
        $entityManager->persist($account);
        $entityManager->flush();
    }

    public function getCreditTotal(string $playerName)
    {
       $account = $this->getAccount($playerName);
        if ($account) {
            $credit = $account->getCredit();
            return $credit;
        }
        if (!$account)
        {
            return null;
        }
    }

    public function getAccount(string $playerName): ?Bank
    {
        $repository = $this->registry->getRepository(Bank::class);
        $account = $repository->findOneBy(['player_name' => $playerName]);
        return $account;
    }
}
