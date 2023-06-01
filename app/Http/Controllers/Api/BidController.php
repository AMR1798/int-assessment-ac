<?php

namespace App\Http\Controllers\Api;

use App\Events\BidEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserBidRequest;
use App\Services\BidService;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class BidController extends Controller
{
    public function create(UserBidRequest $request, BidService $bid_service, UserService $user_service)
    {
        #write your code for bid creation here...
        #model name = Bid
        #table name = bids
        #table fields = id,price,user_id
        #price only can be 2 decimal and must higher than the latest bid price
        $valid_data = $request->validated();
        $price = $bid_service->bid($valid_data['user_id'], $valid_data['price']);
        $user = $user_service->getUser($valid_data['user_id']);
        event(new BidEvent($price));
        # return status code 201, with message 'Success' and data = ['full_name' => user.first_name + user.last_name]
        return response()->json([
            'message' => 'Success',
            'data' => [
                'full_name' => $user->full_name,
                'price' => number_format($price, 2, '.', '')
            ]
        ], JsonResponse::HTTP_CREATED);
    }
}
