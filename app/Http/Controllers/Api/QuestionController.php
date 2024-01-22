<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Question\QuestionResources;
use App\Services\QuestionService;

class QuestionController extends Controller
{
    private $questionService;

    public function __construct(QuestionService $questionService)
    {
        $this->questionService = $questionService;
    }

    public function index()
    {
       return QuestionResources::collection($this->questionService->list()->take(5));
    }
}
