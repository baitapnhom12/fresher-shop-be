<?php

namespace App\Http\Controllers;

use App\Http\Requests\feature\FeatureRequest;
use App\Http\Resources\SuccessResource;
use App\Models\FeedBack;
use App\Services\FeedBackService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;

class FeedBackController extends Controller
{
    private $feedbackService;

    public function __construct(FeedBackService $feedbackService)
    {
        $this->feedbackService = $feedbackService;
    }

    /**
     * Display a listing of the resource.
     */
    public function viewList()
    {
        return view('feedback.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function list()
    {
        try {
            return Response::json([
                'data' => $this->feedbackService->list(),
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getCode(),
                'message' => $th->getMessage(),
            ]);
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $result = $this->feedbackService->show($request->id);
        return Response::json([
            'data' => $result,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'reply' => 'required'
        ]);
        $id = $request->id;
        $email=$request->email;
        $reply=$request->reply;
        try {
            $this->feedbackService->update($request, $id);
            $data = [
                'email' => $email,
                'reply' => $reply
            ];
   
                Mail::send('mails.contact', $data, function ($mesage) use ($data) {
                    $mesage->to($data['email']);
                    $mesage->subject('Reply Contact');
                });
            
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
            $this->feedbackService->destroy($request->id);

            return new SuccessResource();
        } catch (\Throwable $e) {
            return Response::json([
                'error' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);
        }
    }
}
