<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Page\PageResources;
use App\Services\PageService;

class PageController extends Controller
{
    private $pageService;

    public function __construct(PageService $pageService)
    {
        $this->pageService = $pageService;
    }

    public function index()
    {
        return PageResources::collection($this->pageService->list());
    }

    public function show(string $slug)
    {
        return new PageResources($this->pageService->showPage($slug));
    }
}
