<?php

namespace App\Services;

use App\Models\FeedBack;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionService
{
    protected $questionModel;

    public function __construct(Question $questionModel)
    {
        $this->questionModel = $questionModel;
    }

    public function list()
    {
        return $this->questionModel->latest('id')->get();
    }

    public function store(Request $request)
    {
        $this->questionModel->create($request->all());
    }

    public function show(string $id)
    {
        return $this->questionModel->find($id);
    }

    public function update(Request $request, string $id)
    {
        $page = $this->questionModel->find($id);
        $page->update($request->all());
    }

    public function destroy(string $id)
    {
        $page = $this->questionModel->find($id);
        $page->delete();
    }
}
