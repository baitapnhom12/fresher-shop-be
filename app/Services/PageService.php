<?php

namespace App\Services;

use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageService
{
    protected $pageModel;

    public function __construct(Page $pageModel)
    {
        $this->pageModel = $pageModel;
    }

    public function list()
    {
        return $this->pageModel->latest('id')->get();
    }

    public function showPage(string $slug)
    {
        return $this->pageModel->where('slug', $slug)->first();
    }

    public function store(Request $request)
    {
        $this->pageModel->create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'content' => $request->content,
        ]);
    }

    public function edit(string $id)
    {
        return $this->pageModel->find($id);
    }

    public function update(Request $request, string $id)
    {
        $page = $this->pageModel->find($id);
        $page->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'content' => $request->content,
        ]);
    }

    public function destroy(string $id)
    {
        $page = $this->pageModel->find($id);
        $page->delete();
    }
}
