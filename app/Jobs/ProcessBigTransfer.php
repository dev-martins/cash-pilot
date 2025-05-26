<?php

namespace App\Jobs;

use App\Repositories\AccountRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessBigTransfer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $recipientEmail,
        public float $amount,
        public object $sender
    ) {}

    /**
     * Execute the job.
     */
    public function handle(AccountRepository $accountRepository): void
    {
        $accountRepository->tranfer($this->recipientEmail, $this->amount, $this->sender);
    }
}
