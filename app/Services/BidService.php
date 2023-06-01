<?php


namespace App\Services;
use App\Models\Bid;
use App\Models\User;

class BidService {
    public function bid(int $user_id, float $price): float {
        //insert into bids
        $bid = new Bid;
        $bid->user_id = $user_id;
        $bid->price = $price;
        $bid->save();
        return $price;
    }
}