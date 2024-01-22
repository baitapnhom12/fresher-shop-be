<?php

namespace App\Services;

use App\Models\FeedBack;
use Illuminate\Http\Request;

class FeedBackService
{
    protected $peedbackModel;

    public function __construct(FeedBack $peedbackModel)
    {
        $this->peedbackModel = $peedbackModel;
    }

    public function list()
    {
        return $this->peedbackModel->latest('id')->get();
    }

    public function store(Request $request)
    {
        $this->peedbackModel->create($request->all());
    }

    public function show(string $id)
    {
        return $this->peedbackModel->find($id);
    }

    public function update(Request $request, string $id)
    {
        $page = $this->peedbackModel->find($id);
        $page->update($request->all());
    }

    public function destroy(string $id)
    {
        $page = $this->peedbackModel->find($id);
        $page->delete();
    }
}
