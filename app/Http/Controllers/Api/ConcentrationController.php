<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ConcentrationService;

class ConcentrationController extends Controller
{
    private $concentrationService;

    public function __construct(ConcentrationService $concentrationService)
    {
        $this->concentrationService = $concentrationService;
    }

    public function list()
    {
        try {
            return response()->json($this->concentrationService->list());
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getCode(),
                'message' => $th->getMessage(),
            ], 500);
        }
    }
}
