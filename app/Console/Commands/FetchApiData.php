<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ApiService;
use App\Models\Stock;
use App\Models\Sale;
use App\Models\Income;
use App\Models\Order;

class FetchApiData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-api-data {--dateFrom=} {--dateTo=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Получение данных с API (stocks, incomes, sales, orders) и сохранение в базе данных';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $apiService = new ApiService();
        $params = [
            'dateFrom' => $this->option('dateFrom') ?? now()->subMonth()->format('Y-m-d'),
            'dateTo' => $this->option('dateTo') ?? now()->format('Y-m-d'),
        ];

        $this->info('Начало загрузки данных с API...');

        // Stocks
        $this->info('Загрузка данных stocks...');
        $stocks = $apiService->fetchAllData('stocks', $params);
        \Log::info('Stocks response:', ['count' => count($stocks)]);
        foreach ($stocks as $item) {
            Stock::updateOrCreate(
                ['barcode' => $item['barcode'], 'date' => $item['date']],
                [
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
                ]
            );
        }
        $this->info('Stocks загружены: ' . count($stocks) . ' записей.');

        // Sales
        $this->info('Загрузка данных sales...');
        $sales = $apiService->fetchAllData('sales', $params);
        \Log::info('Sales response:', ['count' => count($sales)]);
        foreach ($sales as $item) {
            Sale::updateOrCreate(
                ['sale_id' => $item['sale_id'], 'date' => $item['date']],
                [
                    'g_number' => $item['g_number'] ?? null,
                    'last_change_date' => $item['last_change_date'] ?? null,
                    'supplier_article' => $item['supplier_article'] ?? null,
                    'tech_size' => $item['tech_size'] ?? null,
                    'barcode' => $item['barcode'] ?? null,
                    'total_price' => $item['total_price'] ?? 0,
                    'discount_percent' => $item['discount_percent'] ?? null,
                    'is_supply' => $item['is_supply'] ?? null,
                    'is_realization' => $item['is_realization'] ?? null,
                    'promo_code_discount' => $item['promo_code_discount'] ?? null,
                    'warehouse_name' => $item['warehouse_name'] ?? null,
                    'country_name' => $item['country_name'] ?? null,
                    'oblast_okrug_name' => $item['oblast_okrug_name'] ?? null,
                    'region_name' => $item['region_name'] ?? null,
                    'income_id' => $item['income_id'] ?? null,
                    'odid' => $item['odid'] ?? null,
                    'spp' => $item['spp'] ?? null,
                    'for_pay' => $item['for_pay'] ?? null,
                    'finished_price' => $item['finished_price'] ?? null,
                    'price_with_disc' => $item['price_with_disc'] ?? null,
                    'nm_id' => $item['nm_id'] ?? null,
                    'subject' => $item['subject'] ?? null,
                    'category' => $item['category'] ?? null,
                    'brand' => $item['brand'] ?? null,
                    'is_storno' => $item['is_storno'] ?? null,
                ]
            );
        }
        $this->info('Sales загружены: ' . count($sales) . ' записей.');

        // Incomes
        $this->info('Загрузка данных incomes...');
        $incomes = $apiService->fetchAllData('incomes', $params);
        \Log::info('Incomes response:', ['count' => count($incomes), 'sample' => $incomes]);
        foreach ($incomes as $item) {
            Income::updateOrCreate(
                ['income_id' => $item['income_id']],
                [
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
                ]
            );
        }
        $this->info('Incomes загружены: ' . count($incomes) . ' записей.');

        // Orders
        $this->info('Загрузка данных orders...');
        $orders = $apiService->fetchAllData('orders', $params);
        \Log::info('Orders response:', ['count' => count($orders), 'sample' => $orders]);
        foreach ($orders as $item) {
            Order::updateOrCreate(
                ['g_number' => $item['g_number']],
                [
                    'date' => $item['date'] ?? null,
                    'last_change_date' => $item['last_change_date'] ?? null,
                    'supplier_article' => $item['supplier_article'] ?? null,
                    'tech_size' => $item['tech_size'] ?? null,
                    'barcode' => $item['barcode'] ?? null,
                    'total_price' => $item['total_price'] ?? null,
                    'discount_percent' => $item['discount_percent'] ?? null,
                    'warehouse_name' => $item['warehouse_name'] ?? null,
                    'oblast' => $item['oblast'] ?? null,
                    'income_id' => $item['income_id'] ?? null,
                    'odid' => $item['odid'] ?? null,
                    'nm_id' => $item['nm_id'] ?? null,
                    'subject' => $item['subject'] ?? null,
                    'category' => $item['category'] ?? null,
                    'brand' => $item['brand'] ?? null,
                    'is_cancel' => $item['is_cancel'] ?? null,
                    'cancel_dt' => $item['cancel_dt'] ?? null,
                ]
            );
        }
        $this->info('Orders загружены: ' . count($orders) . ' записей.');

        $this->info('Загрузка данных завершена.');
    }
}
