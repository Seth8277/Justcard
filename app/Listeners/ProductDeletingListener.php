<?php

namespace App\Listeners;

use App\Events\ProductDeleting;
use Illuminate\Database\Eloquent\Builder;

class ProductDeletingListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(ProductDeleting $event)
    {

        /** @var Builder $orders */
        $orders = $event->product->orders;
        $orders->update(['product_id' => null]);
    }
}
