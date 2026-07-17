<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CurrencyService
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://api.frankfurter.dev',
            'timeout'  => 5.0,
        ]);
    }

    public function getLatestRates(string $base = 'USD'): array
    {
        $cacheKey = "exchange_rates_{$base}";

        return Cache::remember($cacheKey, now()->addHours(6), function () use ($base) {
            try {
                $response = $this->client->get('/v1/latest', [
                    'query' => [
                        'base'    => $base,
                        'symbols' => 'EUR,GBP,SGD,JPY,INR,AUD,CAD,CHF',
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

                return [
                    'base'     => $base,
                    'date'     => now()->toDateString(),
                    'rates'    => [
                        'EUR' => 0.92,
                        'GBP' => 0.79,
                        'SGD' => 1.34,
                        'JPY' => 149.50,
                        'INR' => 83.12,
                        'AUD' => 1.53,
                        'CAD' => 1.36,
                        'CHF' => 0.89,
                    ],
                    'fallback' => true,
                ];
            }
        });
    }

    public function convert(float $amount, string $to): ?float
    {
        // Can't convert USD to USD via this API
        if (strtoupper($to) === 'USD') {
            return $amount;
        }

        $rates = $this->getLatestRates('USD');

        if (! isset($rates['rates'][strtoupper($to)])) {
            return null;
        }

        return round($amount * $rates['rates'][strtoupper($to)], 2);
    }

    public function getSupportedCurrencies(): array
    {
        return [
            'EUR' => 'Euro',
            'GBP' => 'British Pound',
            'SGD' => 'Singapore Dollar',
            'JPY' => 'Japanese Yen',
            'INR' => 'Indian Rupee',
            'AUD' => 'Australian Dollar',
            'CAD' => 'Canadian Dollar',
            'CHF' => 'Swiss Franc',
        ];
    }
}