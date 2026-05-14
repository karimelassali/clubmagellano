<?php

namespace App\Services;

use RuntimeException;

class ProcessingEngine
{
    public function process(array $payload): array
    {
        $items = $payload['items'] ?? null;

        if (! is_array($items) || count($items) === 0) {
            throw new RuntimeException('Items array is required.');
        }

        $total = 0;

        foreach ($items as $item) {
            $qty = (int) ($item['qty'] ?? 0);
            $price = (float) ($item['price'] ?? 0);

            if ($qty <= 0) {
                throw new RuntimeException('Invalid qty.');
            }

            if ($price < 0) {
                throw new RuntimeException('Invalid price.');
            }

            $total += $qty * $price;
        }

        $vat = round($total * 0.22, 2);

        return [
            'customer' => $payload['customer'] ?? null,
            'items_count' => count($items),
            'total_amount' => round($total, 2),
            'vat' => $vat,
            'grand_total' => round($total + $vat, 2),
        ];
    }
}
