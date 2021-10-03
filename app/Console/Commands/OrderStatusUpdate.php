<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Order;

class OrderStatusUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:cancel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically cancels order if invalid_at is reached';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $order = Order::all();

        foreach ($order as $o) {
            $order_invalid = Carbon::create($o->invalid_at);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            if($order_invalid->isBefore($now) && $o->status != 6) {
                $o->update(['status' => 6]);
            }
        }
    }
}
