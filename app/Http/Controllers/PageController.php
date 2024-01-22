<?php

namespace App\Http\Controllers;

use App\Http\Resources\SuccessResource;
use App\Services\PageService;
use Exception;
use Illuminate\Http\Request;

class PageController extends Controller
{
    private $pageService;

    public function __construct(PageService $pageService)
    {
        $this->pageService = $pageService;
    }

    /**
     * Display a listing of the resource.
     */
    public function list()
    {
        $pages = $this->pageService->list();

        return view('pages.list', compact('pages'));
    }

    public function create()
    {
        return view('pages.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:100|unique:pages,name,',
            'content' => 'required',
        ]);
        try {
            $this->pageService->store($request);

            return new SuccessResource;
        } catch (Exception $exception) {
            return back()->with('error', 'Error');
        }
    }

    /**
     * Display the specified resource.
     */
    public function edit(string $id)
    {
        $page = $this->pageService->edit($id);

        return view('pages.edit', compact('page'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|max:100|unique:pages,name,' . $id,
            'content' => 'required',
        ]);
        try {
            $this->pageService->update($request, $id);

            return back()->with('success', 'Edited successfully');
        } catch (Exception $exception) {
            return back()->with('error', 'Error');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(string $id)
    {
        try {
            $this->pageService->destroy($id);

            return back()->with('success', 'Deleted successfully');
        } catch (Exception $exception) {
            return back()->with('error', 'Error');
        }
    }
}
