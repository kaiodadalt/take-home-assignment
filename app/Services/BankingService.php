<?php

namespace App\Services;

class BankingService
{
    private array $accounts = [];

    /**
     * Reset accounts storage.
     *
     * @return void
     */
    public function reset(): void
    {
        $this->accounts = [];
    }

    /**
     * Add the given amount into the destination account.
     *
     * @param  string $destination
     * @param  float $amount
     * @return float
     */
    public function deposit(string $destination, float $amount): float
    {
        if (isset($this->accounts[$destination])) {
            $this->accounts[$destination] += $amount;
        } else {
            $this->accounts[$destination] = $amount;
        }
        return $this->accounts[$destination];
    }

    /**
     * Withdraw the given amount from the origin account.
     *
     * @param  string $origin
     * @param  float $amount
     * @return ?float
     */
    public function withdraw(string $origin, float $amount): ?float
    {
        if (
            isset($this->accounts[$origin]) &&
            $this->accounts[$origin] >= $amount
        ) {
            $this->accounts[$origin] -= $amount;
            return $this->accounts[$origin];
        }
        // not enough balance or account doesn't exist.
        return null;
    }

    /**
     * Transfer the given amount from the origin to the destination account.
     *
     * @param  string $origin
     * @param  string $destination
     * @param  float $amount
     * @return ?array
     */
    public function transfer(string $origin, string $destination, float $amount): ?array
    {
        $originBalance = $this->withdraw($origin, $amount);
        if ($originBalance !== null) {
            $destinationBalance = $this->deposit($destination, $amount);
            return [
                'origin' => $originBalance,
                'destination' => $destinationBalance
            ];
        }
        // withdrawal failed, transfer cannot proceed.
        return null;
    }

    /**
     * Get balance by account ID.
     *
     * @param  mixed $id
     * @return ?float
     */
    public function getBalanceByAccountId(string $id): ?float
    {
        return $this->accounts[$id] ?? null;
    }
}
