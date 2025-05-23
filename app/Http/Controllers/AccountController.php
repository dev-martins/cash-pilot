<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransferRequest;
use App\Services\AccountService;

class AccountController extends Controller
{
    public function __construct(protected AccountService $accountService) {}

    public function balance()
    {
        return $this->accountService->balance();
    }

    public function transfer(TransferRequest $request)
    {
        $this->accountService->transfer(
            recipientEmail: $request->email,
            amount: $request->amount
        );

        return response()->json(['message' => 'Transferência realizada com sucesso']);
    }
}
