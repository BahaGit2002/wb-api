<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ApiService;
use App\Models\Income;

class FetchIncomesData extends Command
{
    protected $signature = 'app:fetch-incomes-data {--dateFrom=} {--dateTo=}';

    protected $description = 'Получение данных incomes с API и сохранение в базе данных';

    public function handle()
    {
        $apiService = new ApiService();
        $params = [
            'dateFrom' => $this->option('dateFrom') ?? now()->subMonth()->format('Y-m-d'),
            'dateTo' => $this->option('dateTo') ?? now()->format('Y-m-d'),
        ];

        $this->info('Начало загрузки данных incomes с API...');

        $incomes = $apiService->fetchAllData('incomes', $params);

        if (empty($incomes)) {
            $this->info('Нет данных incomes для загрузки.');
            return;
        }

        $incomeData = [];
        foreach ($incomes as $item) {
            $incomeData[] = [
                'income_id' => $item['income_id'],
                'number' => $item['number'] ?? null,
                'date' => $item['date'] ?? null,
                'last_change_date' => $item['last_change_date'] ?? null,
                'supplier_article' => $item['supplier_article'] ?? null,
                'tech_size' => $item['tech_size'] ?? null,
                'barcode' => $item['barcode'] ?? null,
                'quantity' => $item['quantity'] ?? 0,
                'total_price' => $item['total_price'] ?? 0,
                'date_close' => $item['date_close'] ?? null,
                'warehouse_name' => $item['warehouse_name'] ?? null,
                'nm_id' => $item['nm_id'] ?? null,
            ];
        }

        Income::upsert(
            $incomeData,
            ['income_id'],
            [
                'number',
                'date',
                'last_change_date',
                'supplier_article',
                'tech_size',
                'barcode',
                'quantity',
                'total_price',
                'date_close',
                'warehouse_name',
                'nm_id',
            ]
        );

        $this->info('Incomes загружены: ' . count($incomes) . ' записей.');
    }
}
