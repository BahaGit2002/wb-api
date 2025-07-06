<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ApiService;
use App\Models\Sale;

class FetchSalesData extends Command
{
    protected $signature = 'app:fetch-sales-data {--dateFrom=} {--dateTo=}';

    protected $description = 'Получение данных sales с API и сохранение в базе данных';

    public function handle()
    {
        $apiService = new ApiService();
        $params = [
            'dateFrom' => $this->option('dateFrom') ?? now()->subMonth()->format('Y-m-d'),
            'dateTo' => $this->option('dateTo') ?? now()->format('Y-m-d'),
        ];

        $this->info('Начало загрузки данных sales с API...');

        $sales = $apiService->fetchAllData('sales', $params);

        if (empty($sales)) {
            $this->info('Нет данных sales для загрузки.');
            return;
        }

        $saleData = [];
        foreach ($sales as $item) {
            $saleData[] = [
                'g_number' => $item['g_number'],
                'sale_id' => $item['sale_id'],
                'date' => isset($item['date']) ? date('Y-m-d', strtotime($item['date'])) : now()->format('Y-m-d'),
                'last_change_date' => isset($item['last_change_date']) ? date('Y-m-d', strtotime($item['last_change_date'])) : null,
                'supplier_article' => $item['supplier_article'] ?? '',
                'tech_size' => $item['tech_size'] ?? '',
                'barcode' => isset($item['barcode']) ? (int)$item['barcode'] : null,
                'total_price' => $item['total_price'] ?? 0.00,
                'discount_percent' => $item['discount_percent'] ?? 0,
                'is_supply' => $item['is_supply'] ?? false,
                'is_realization' => $item['is_realization'] ?? false,
                'promo_code_discount' => $item['promo_code_discount'] ?? 0.00,
                'warehouse_name' => $item['warehouse_name'] ?? '',
                'country_name' => $item['country_name'] ?? '',
                'oblast_okrug_name' => $item['oblast_okrug_name'] ?? '',
                'region_name' => $item['region_name'] ?? '',
                'income_id' => isset($item['income_id']) ? (int)$item['income_id'] : null,
                'odid' => $item['odid'] ?? null,
                'spp' => $item['spp'] ?? null,
                'for_pay' => $item['for_pay'] ?? 0.00,
                'finished_price' => $item['finished_price'] ?? 0.00,
                'price_with_disc' => $item['price_with_disc'] ?? 0.00,
                'nm_id' => isset($item['nm_id']) ? (int)$item['nm_id'] : null,
                'subject' => $item['subject'] ?? '',
                'category' => $item['category'] ?? '',
                'brand' => $item['brand'] ?? '',
                'is_storno' => $item['is_storno'] ?? false,
                'updated_at' => now(),
            ];
        }

        Sale::upsert(
            $saleData,
            ['g_number', 'sale_id'],
            [
                'date',
                'last_change_date',
                'supplier_article',
                'tech_size',
                'barcode',
                'total_price',
                'discount_percent',
                'is_supply',
                'is_realization',
                'promo_code_discount',
                'warehouse_name',
                'country_name',
                'oblast_okrug_name',
                'region_name',
                'income_id',
                'odid',
                'spp',
                'for_pay',
                'finished_price',
                'price_with_disc',
                'nm_id',
                'subject',
                'category',
                'brand',
                'is_storno',
                'updated_at',
            ]
        );

        $this->info('Sales загружены: ' . count($sales) . ' записей.');
    }
}
