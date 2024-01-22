<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SuccessResource;
use App\Services\FeedBackService;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    private $featbackService;

    public function __construct(FeedBackService $featbackService)
    {
        $this->featbackService = $featbackService;
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:50',
            'email' => 'required|email|max:150',
            'message' => 'required|max:255',
        ]);
        try {
         $this->featbackService->store($request);
         return new SuccessResource();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
