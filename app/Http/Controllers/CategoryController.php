<?php

namespace App\Http\Controllers;

use App\Http\Requests\Categories\CreateCategotyRequest;
use App\Http\Requests\Categories\EditCategotyRequest;
use App\Services\CategoryService;
use Exception;

class CategoryController extends Controller
{
    private $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function list()
    {
        try {
            $categories = $this->categoryService->listCategoryies();
        } catch (Exception $exception) {
            return back()->with('error', 'Error');
        }

        return view('categories.list', compact('categories'));
    }

    public function create()
    {
        $categories = $this->categoryService->listCategoryies();

        return view('categories.create', compact('categories'));
    }

    public function store(CreateCategotyRequest $request)
    {
        try {
            $result = $this->categoryService->storeCategory($request);

            if ($result) {
                return redirect()->route('category.list')->with('success', 'Created successfully');
            } else {
                return back()->with('error', 'Created unsuccessfully');
            }
        } catch (Exception $exception) {
            return back()->with('error', 'Error');
        }
    }

    public function edit($id)
    {
        $category = $this->categoryService->findId($id);
        $categories = $this->categoryService->listCategoryies();

        return view('categories.edit', compact('category', 'categories'));
    }

    public function update(EditCategotyRequest $request, $id)
    {
        try {
            $result = $this->categoryService->updateCategory($request, $id);

            if ($result) {
                return redirect()->route('category.list')->with('success', 'Edited successfully');
            } else {
                return back()->with('error', 'Edited unsuccessfully');
            }
        } catch (Exception $exception) {
            return back()->with('error', 'Error');
        }
    }

    public function delete($id)
    {
        try {
            $result = $this->categoryService->deleteCategory($id);

            if ($result) {
                return redirect()->route('category.list')->with('success', 'Deleted successfully');
            } else {
                return back()->with('error', 'Deleted unsuccessfully');
            }
        } catch (Exception $exception) {
            return back()->with('error', 'Error');
        }
    }
}
