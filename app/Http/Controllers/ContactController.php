<?php

namespace App\Http\Controllers;

use App\Http\Resources\SuccessResource;
use App\Services\ContactService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ContactController extends Controller
{
    private $contactService;

    public function __construct(ContactService $contactService)
    {
        $this->contactService = $contactService;
    }

    /**
     * Display a listing of the resource.
     */
    public function viewList()
    {
        return view('contact.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function list()
    {
        try {
            return Response::json([
                'data' => $this->contactService->list(),
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getCode(),
                'message' => $th->getMessage(),
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255|unique:contacts,name',
            'content' => 'required|max:255',
        ]);
        try {
            $this->contactService->store($request);

            return new SuccessResource();
        } catch (\Throwable $e) {
            return Response::json([
                'error' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $result = $this->contactService->show($request->id);

        return Response::json([
            'data' => $result,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $id = $request->id;
        $request->validate([
            'name' => 'required|max:255|unique:contacts,name,' . $id,
            'content' => 'required|max:255',
        ]);
        try {
            $this->contactService->update($request, $id);

            return new SuccessResource;
        } catch (\Throwable $e) {
            return Response::json([
                'error' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Request $request)
    {
        try {
            $this->contactService->destroy($request->id);

            return new SuccessResource;
        } catch (\Throwable $e) {
            return Response::json([
                'error' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);
        }
    }
}

