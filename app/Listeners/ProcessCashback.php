<?php

namespace App\Listeners;

use App\Events\TransferCompleted;
use App\Models\Transaction;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class ProcessCashback implements ShouldQueue
{
     public string $queue = 'cashbacks';

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TransferCompleted $event): void
    {
        $transaction = $event->transaction;
        $receiver = $transaction->receiver;

        // Simula se o usuário é parceiro
        $isPartner = str_contains($receiver->email, 'parceiro');

        if (!$isPartner) {
            Log::info("Usuário {$receiver->email} não é parceiro. Cashback ignorado.");
            return;
        }

        $cashbackValue = $transaction->amount * 0.10;

        // Atualiza saldo
        $receiver->increment('balance', $cashbackValue);

        // Registra nova transação de cashback
        Transaction::create([
            'from_user_id' => null,
            'to_user_id'   => $receiver->id,
            'amount'       => $cashbackValue,
            'type'         => 'cashback',
            'status'       => 'completed',
        ]);

        Log::info("Cashback de R$ {$cashbackValue} enviado para {$receiver->email}");
    }
}
