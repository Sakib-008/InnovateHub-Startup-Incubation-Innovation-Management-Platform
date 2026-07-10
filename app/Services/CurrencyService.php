<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CurrencyService
{
    private Client $client;
    private string $baseUrl = 'https://api.frankfurter.app';

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'timeout'  => 5.0,
        ]);
    }

    /**
     * Get latest exchange rates relative to USD.
     * Results cached for 6 hours to avoid hammering the free API.
     */
    public function getLatestRates(string $base = 'USD'): array
    {
        $cacheKey = "exchange_rates_{$base}";

        return Cache::remember($cacheKey, now()->addHours(6), function () use ($base) {
            try {
                $response = $this->client->get('/latest', [
                    'query' => [
                        'from'   => $base,
                        'to'     => 'EUR,GBP,BDT,SGD,AED',
                    ],
                ]);

                $data = json_decode($response->getBody()->getContents(), true);

                return [
                    'base'  => $data['base'],
                    'date'  => $data['date'],
                    'rates' => $data['rates'],
                ];
            } catch (RequestException $e) {
                Log::warning('CurrencyService: API call failed — ' . $e->getMessage());

                // Fallback rates if API is unreachable
                return [
                    'base'  => $base,
                    'date'  => now()->toDateString(),
                    'rates' => [
                        'EUR' => 0.92,
                        'GBP' => 0.79,
                        'BDT' => 110.50,
                        'SGD' => 1.34,
                        'AED' => 3.67,
                    ],
                    'fallback' => true,
                ];
            }
        });
    }

    /**
     * Convert an amount from USD to a target currency.
     */
    public function convert(float $amount, string $to = 'BDT'): ?float
    {
        $rates = $this->getLatestRates();

        if (! isset($rates['rates'][$to])) {
            return null;
        }

        return round($amount * $rates['rates'][$to], 2);
    }
}