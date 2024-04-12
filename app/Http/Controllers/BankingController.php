<?php

namespace App\Http\Controllers;

use App\Services\BankingService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BankingController extends Controller
{
    private BankingService $bankingService;

    public function __construct(BankingService $bankingService)
    {
        $this->bankingService = $bankingService;
    }
    
    /**
     * Reset accounts storage.
     *
     * @return Response
     */
    public function reset(): Response
    {
        $this->bankingService->reset();
        return response('OK');
    }
    
    /**
     * Get account balance by account ID.
     *
     * @param  Request $request
     * @return Response
     */
    public function getBalance(Request $request): Response
    {
        $accountId = $request->get('account_id');
        $balance = $this->bankingService->getBalanceByAccountId($accountId);
        if ($balance === null) {
            return response('0', 404);
        }

        return response((string)$balance, 200);
    }
    
    /**
     * Handle events of type 'deposit', 'withdraw' and 'transfer'.
     *
     * @param  Request $request
     * @return Response
     */
    public function handleEvent(Request $request): Response
    {
        $type = $request->get('type');
        $origin = $request->get('origin');
        $destination = $request->get('destination');
        $amount = $request->get('amount');
        switch ($type) {
            case 'deposit':
                return $this->handleDeposit($destination, $amount);
            case 'withdraw':
                return $this->handleWithdraw($origin, $amount);
            case 'transfer':
                return $this->handleTransfer($origin, $destination, $amount);
            default:
                return response([
                    'error' => 'Invalid event type'
                ], 400);
        }
    }
    
    /**
     * Handle a deposit event.
     *
     * @param  string $destination
     * @param  float $amount
     * @return Response
     */
    public function handleDeposit(string $destination, float $amount): Response
    {
        $balance = $this->bankingService->deposit($destination, $amount);
        return response([
            'destination' => [
                'id' => $destination,
                'balance' => $balance
            ]
        ], 201);
    }
    
    /**
     * Handle a withdraw event.
     *
     * @param  string $origin
     * @param  float $amount
     * @return Response
     */
    public function handleWithdraw(string $origin, float $amount): Response
    {
        $balance = $this->bankingService->withdraw($origin, $amount);

        if ($balance !== null) {
            return response([
                'origin' => [
                    'id' => $origin,
                    'balance' => $balance
                ]
            ], 201);
        }
        return response('0', 404);
    }
    
    /**
     * Handle a transfer event.
     *
     * @param  string $origin
     * @param  string $destination
     * @param  float $amount
     * @return Response
     */
    private function handleTransfer(string $origin, string $destination, float $amount): Response
    {
        $result = $this->bankingService->transfer($origin, $destination, $amount);

        if ($result !== null) {
            return response([
                'origin' => ['id' => $origin, 'balance' => $result['origin']],
                'destination' => ['id' => $destination, 'balance' => $result['destination']]
            ], 201);
        }
        return response('0', 404);
    }
}
