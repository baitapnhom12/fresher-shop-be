<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Subscribers\SubscriberRequest;
use App\Models\Subscriber;

class SubscriberController extends Controller
{
    public function subscriber(SubscriberRequest $request)
    {
        $result = Subscriber::create(['email' => $request->email]);
        if ($result) {
            return response()->json('Created successfully', 201);
        }

        return response()->json('Created unsuccessfully', 400);
    }
}
