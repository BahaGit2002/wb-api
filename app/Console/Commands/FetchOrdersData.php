<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ApiService;
use App\Models\Order;

class FetchOrdersData extends Command
{
    protected $signature = 'app:fetch-orders-data {--dateFrom=} {--dateTo=}';

    protected $description = 'Получение данных orders с API и сохранение в базе данных';

    public function handle()
    {
        $apiService = new ApiService();
        $params = [
            'dateFrom' => $this->option('dateFrom') ?? now()->subMonth()->format('Y-m-d'),
            'dateTo' => $this->option('dateTo') ?? now()->format('Y-m-d'),
        ];

        $this->info('Начало загрузки данных orders с API...');

        $orders = $apiService->fetchAllData('orders', $params);

        if (empty($orders)) {
            $this->info('Нет данных orders для загрузки.');
            return;
        }

        $orderData = [];
        foreach ($orders as $item) {
            $orderData[] = [
                'g_number' => $item['g_number'],
                'date' => isset($item['date']) ? date('Y-m-d', strtotime($item['date'])) : null,
                'last_change_date' => isset($item['last_change_date']) ? date('Y-m-d', strtotime($item['last_change_date'])) : null,
                'supplier_article' => $item['supplier_article'] ?? '',
                'tech_size' => $item['tech_size'] ?? '',
                'barcode' => isset($item['barcode']) ? (int)$item['barcode'] : null,
                'total_price' => $item['total_price'] ?? 0.00,
                'discount_percent' => $item['discount_percent'] ?? 0,
                'warehouse_name' => $item['warehouse_name'] ?? '',
                'oblast' => $item['oblast'] ?? '',
                'income_id' => isset($item['income_id']) ? (int)$item['income_id'] : null,
                'odid' => isset($item['odid']) ? (int)$item['odid'] : null,
                'nm_id' => isset($item['nm_id']) ? (int)$item['nm_id'] : null,
                'subject' => $item['subject'] ?? '',
                'category' => $item['category'] ?? '',
                'brand' => $item['brand'] ?? '',
                'is_cancel' => $item['is_cancel'] ?? false,
                'cancel_dt' => isset($item['cancel_dt']) ? date('Y-m-d', strtotime($item['cancel_dt'])) : null,
                'updated_at' => now(),
            ];
        }

        Order::upsert(
            $orderData,
            ['g_number'],
            [
                'date',
                'last_change_date',
                'supplier_article',
                'tech_size',
                'barcode',
                'total_price',
                'discount_percent',
                'warehouse_name',
                'oblast',
                'income_id',
                'odid',
                'nm_id',
                'subject',
                'category',
                'brand',
                'is_cancel',
                'cancel_dt',
                'updated_at',
            ]
        );

        $this->info('Orders загружены: ' . count($orders) . ' записей.');
    }
}
