<?php

namespace App\Services;

use App\Models\Contact;
use App\Models\FeedBack;
use App\Models\Question;
use Illuminate\Http\Request;

class ContactService
{
    protected $contactModel;

    public function __construct(Contact $contactModel)
    {
        $this->contactModel = $contactModel;
    }

    public function list()
    {
        return $this->contactModel->latest('id')->get();
    }

    public function store(Request $request)
    {
        $this->contactModel->create($request->all());
    }

    public function show(string $id)
    {
        return $this->contactModel->find($id);
    }

    public function update(Request $request, string $id)
    {
        $page = $this->contactModel->find($id);
        $page->update($request->all());
    }

    public function destroy(string $id)
    {
        $page = $this->contactModel->find($id);
        $page->delete();
    }
}
