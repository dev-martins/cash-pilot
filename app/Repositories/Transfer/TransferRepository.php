<?php

namespace App\Repositories\Transfer;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TransferRepository
{
    public function transferByIds(int $senderId, int $receiverId, float $amount): Transaction
    {
        $sender = User::findOrFail($senderId);
        $receiver = User::findOrFail($receiverId);

        return DB::transaction(function () use ($sender, $receiver, $amount) {
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
    }
}
