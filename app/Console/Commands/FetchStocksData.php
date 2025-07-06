<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ApiService;
use App\Models\Stock;

class FetchStocksData extends Command
{
    protected $signature = 'app:fetch-stocks-data {--dateFrom=} {--dateTo=}';

    protected $description = 'Получение данных stocks с API и сохранение в базе данных';

    public function handle()
    {
        $apiService = new ApiService();
        $params = [
            'dateFrom' => $this->option('dateFrom') ?? now()->subMonth()->format('Y-m-d'),
            'dateTo' => $this->option('dateTo') ?? now()->format('Y-m-d'),
        ];

        $this->info('Начало загрузки данных stocks с API...');

        $stocks = $apiService->fetchAllData('stocks', $params);

        if (empty($stocks)) {
            $this->info('Нет данных stocks для загрузки.');
            return;
        }

        $stockData = [];
        foreach ($stocks as $item) {
            $stockData[] = [
                'barcode' => $item['barcode'],
                'date' => $item['date'],
                'last_change_date' => $item['last_change_date'] ?? null,
                'supplier_article' => $item['supplier_article'] ?? null,
                'tech_size' => $item['tech_size'] ?? null,
                'quantity' => $item['quantity'] ?? 0,
                'is_supply' => $item['is_supply'] ?? null,
                'is_realization' => $item['is_realization'] ?? null,
                'quantity_full' => $item['quantity_full'] ?? null,
                'warehouse_name' => $item['warehouse_name'] ?? null,
                'in_way_to_client' => $item['in_way_to_client'] ?? null,
                'in_way_from_client' => $item['in_way_from_client'] ?? null,
                'nm_id' => $item['nm_id'] ?? null,
                'subject' => $item['subject'] ?? null,
                'category' => $item['category'] ?? null,
                'brand' => $item['brand'] ?? null,
                'sc_code' => $item['sc_code'] ?? null,
                'price' => $item['price'] ?? null,
                'discount' => $item['discount'] ?? null,
            ];
        }

        Stock::upsert(
            $stockData,
            ['barcode', 'date'],
            [
                'last_change_date',
                'supplier_article',
                'tech_size',
                'quantity',
                'is_supply',
                'is_realization',
                'quantity_full',
                'warehouse_name',
                'in_way_to_client',
                'in_way_from_client',
                'nm_id',
                'subject',
                'category',
                'brand',
                'sc_code',
                'price',
                'discount',
            ]
        );

        $this->info('Stocks загружены: ' . count($stocks) . ' записей.');
    }
}
