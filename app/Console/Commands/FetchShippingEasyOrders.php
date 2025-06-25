<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FetchShippingEasyOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-shipping-easy-orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
{
    // Replace with real ShippingEasy API call
    $response = Http::withHeaders([
        'Authorization' => 'Bearer YOUR_API_KEY',
    ])->get('https://api.shippingeasy.com/orders');

    foreach ($response->json() as $order) {
        Order::updateOrCreate(
            ['external_id' => $order['id']],
            [
                'customer_name' => $order['customer']['name'],
                'total' => $order['total'],
                'ordered_at' => Carbon::parse($order['created_at']),
                // other fields...
            ]
        );
    }

    $this->info('Orders imported successfully!');
}

}
