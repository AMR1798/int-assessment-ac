<?php

namespace App\Listeners;

use App\Events\BidEvent;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SendNotificationBidEventListener implements ShouldQueue
{
    use InteractsWithQueue;
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
     * @param  \App\Events\BidEvent  $event
     * @return void
     */
    public function handle(BidEvent $event)
    {
        // chunk process user
        User::with('latestBid')->chunk(100, function($users) use ($event){
            $data = [];
            foreach ($users as $user){
                $price = (object)[
                    'latest_bid_price' => number_format($event->getLatest(), 2, '.', ''),
                    'user_last_bid_price' => number_format(($user->latestBid->price ?? 0), 2, '.', '')
                ];
                $data[] = [
                    'id' => Str::uuid()->toString(),
                    'notifiable_id' => $user->id,
                    'data' => json_encode($price),
                    'type' => '',
                    'notifiable_type' => ''
                ];
            }

            DB::table('notifications')->insert($data);
        });
    }
}
