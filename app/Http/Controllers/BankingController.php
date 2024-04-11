<?php

namespace App\Http\Controllers;

use App\Services\BankingService;

class BankingController extends Controller
{
    private BankingService $bankingService;

    public function __construct(BankingService $bankingService)
    {
        $this->bankingService = $bankingService;
    }

    public function reset()
    {
        return response(null);
    }

    public function getBalance()
    {
        return response(null);
    }

    public function handleEvent()
    {
        return response(null);
    }
}
