<?php

namespace App\Http\Controllers;

use App\Http\Requests\banner\BannerRequest;
use App\Http\Resources\SuccessResource;
use App\Services\BannerService;
use Exception;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    private $bannerService;

    public function __construct(BannerService $bannerService)
    {
        $this->bannerService = $bannerService;
    }

    /**
     * Display a listing of the resource.
     */
    public function list()
    {
        $banner = $this->bannerService->list();

        return view('banner.list', compact('banner'));
    }

    public function create()
    {
        return view('banner.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BannerRequest $request)
    {
        try {
            $this->bannerService->store($request);

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
        $banner = $this->bannerService->findId($id);

        return view('banner.edit', compact('banner'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $id = $request->id;
        $request->validate([
            'name' => 'required|max:50|unique:banners,name,' . $id,
        ]);
        try {
            $result = $this->bannerService->update($request, $id);

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
            $result = $this->bannerService->destroy($id);

            if ($result) {
                return redirect()->route('banner.list')->with('success', 'Deleted successfully');
            } else {
                return back()->with('error', 'Deleted unsuccessfully');
            }
        } catch (Exception $exception) {
            return back()->with('error', 'Error');
        }
    }
}
