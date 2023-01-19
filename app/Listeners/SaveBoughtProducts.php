<?php

namespace App\Listeners;

use App\Models\BoughtProduct;

class SaveBoughtProducts
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $log_id = $event->log_id;
        $request = $event->request;
    
        foreach ($request->products as $product) {
            $bought_product = new BoughtProduct([
                'log_id' => $log_id,
                'product_id' => $product['product_id'],
                'price' => $product['price'],
                'quantity' => $product['quantity'],
                'ef' => $product['ef'],
                'rmf' => $product['rmf'],
                'capacity' => $request->capacity,
            ]);

            $bought_product->save();
        }
    }
}
