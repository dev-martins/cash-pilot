<?php

namespace App\Services;

use App\Repositories\AccountRepository;

class AccountService
{
    public function __construct(protected AccountRepository $accountRepository) {}

    public function balance()
    {
        return $this->accountRepository->balance();
    }

    public function transfer(string $recipientEmail, float $amount)
    {
        return $this->accountRepository->tranfer($recipientEmail, $amount);
    }
}
