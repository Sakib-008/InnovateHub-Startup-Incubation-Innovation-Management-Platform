<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CurrencyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function __construct(private CurrencyService $currencyService) {}

    /**
     * GET /api/currency/rates
     * Returns current exchange rates from USD.
     */
    public function rates(): JsonResponse
    {
        $rates = $this->currencyService->getLatestRates();

        return response()->json([
            'success' => true,
            'data'    => $rates,
        ]);
    }

    /**
     * GET /api/currency/convert?amount=50000&to=BDT
     * Converts a USD amount to the given currency.
     */
    public function convert(Request $request): JsonResponse
    {
        $request->validate([
            'amount' => ['required', 'numeric', 'min:0'],
            'to'     => ['required', 'string', 'size:3'],
        ]);

        $converted = $this->currencyService->convert(
            (float) $request->amount,
            strtoupper($request->to)
        );

        if ($converted === null) {
            return response()->json([
                'success' => false,
                'message' => 'Currency not supported.',
            ], 422);
        }

        return response()->json([
            'success'  => true,
            'amount'   => $request->amount,
            'currency' => strtoupper($request->to),
            'result'   => $converted,
        ]);
    }
}