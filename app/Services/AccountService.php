<?php

namespace App\Services;

use App\Jobs\ProcessBigTransfer;
use App\Repositories\AccountRepository;
use Illuminate\Support\Facades\Auth;

class AccountService
{
    public function __construct(protected AccountRepository $accountRepository) {}

    public function balance()
    {
        return $this->accountRepository->balance();
    }

    public function transfer(string $recipientEmail, float $amount)
    {
        $sender = Auth::user();

        if ($amount >= env('BIG_TRANSFER_THRESHOLD')) {
            ProcessBigTransfer::dispatch($recipientEmail, $amount, $sender);
            return;
        }

        $this->accountRepository->tranfer($recipientEmail, $amount, $sender);
    }
}
