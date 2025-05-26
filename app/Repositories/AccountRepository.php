<?php

namespace App\Repositories;

use App\Events\TransferCompleted;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AccountRepository
{
    public function balance()
    {
        $user = Auth::user();

        return [
            "balance" => $user->balance
        ];
    }

    public function tranfer(string $recipientEmail, float $amount, object $sender)
    {
        $receiver = User::where('email', $recipientEmail)->firstOrFail();

        if ($receiver->id === $sender->id) {
            abort(422, 'Você não pode transferir para si mesmo');
        }

        if ($sender->balance < $amount) {
            abort(422, 'Saldo insuficiente');
        }

        $transaction = DB::transaction(function () use ($sender, $receiver, $amount) {

            $sender->decrement('balance', $amount);
            $receiver->increment('balance', $amount);

            return Transaction::create([
                'from_user_id' => $sender->id,
                'to_user_id'   => $receiver->id,
                'amount'       => $amount,
                'type'         => 'transfer',
                'status'       => 'completed',
            ]);
        });

        TransferCompleted::dispatch($transaction);
    }
}
