<?php

namespace App\Http\Controllers;

use App\Services\HomeService;

class HomeController extends Controller
{
    private $homeService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(HomeService $homeService)
    {
        $this->middleware('auth');
        $this->homeService = $homeService;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $homeData = $this->homeService->getDashBoardData()->getData();

        return view('home', compact('homeData'));
    }

    public function dataChart()
    {
        return $this->homeService->getDashBoardData()->getData();
    }
}
