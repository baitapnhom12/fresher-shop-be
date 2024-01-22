<?php

namespace App\Http\Controllers;

use App\Http\Requests\brand\BrandRequest;
use App\Http\Resources\SuccessResource;
use App\Services\BrandService;
use Exception;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    private $brandService;

    public function __construct(BrandService $brandService)
    {
        $this->brandService = $brandService;
    }

    /**
     * Display a listing of the resource.
     */
    public function list()
    {
        $brand = $this->brandService->list();

        return view('brand.list', compact('brand'));
    }

    public function create()
    {
        return view('brand.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BrandRequest $request)
    {
        try {
            $this->brandService->store($request);

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
        $brand = $this->brandService->findId($id);

        return view('brand.edit', compact('brand'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $id = $request->id;
        $request->validate([
            'name' => 'required|max:50|unique:brands,name,' . $id,
        ]);
        try {
            $result = $this->brandService->update($request, $id);

            if ($result) {
                return back()->with('success', 'Edited successfully');
            } else {
                return back()->with('error', 'Edited unsuccessfully');
            }
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
            $result = $this->brandService->destroy($id);

            if ($result) {
                return redirect()->route('brand.list')->with('success', 'Deleted successfully');
            } else {
                return back()->with('error', 'Deleted unsuccessfully');
            }
        } catch (Exception $exception) {
            return back()->with('error', 'Error');
        }
    }
}
