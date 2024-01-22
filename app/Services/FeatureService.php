<?php

namespace App\Services;

use App\Models\Feature;
use Illuminate\Http\Request;

class FeatureService
{
    protected $featureModel;

    public function __construct(Feature $featureModel)
    {
        $this->featureModel = $featureModel;
    }

    public function list()
    {
        return $this->featureModel->latest('id')->get();
    }

    public function store(Request $request)
    {
        $this->featureModel->create($request->validated());
    }

    public function show(string $id)
    {
        return $this->featureModel->find($id);
    }

    public function update(Request $request, string $id)
    {
        $size = $this->featureModel->find($id);
        $size->update($request->validated());
    }

    public function destroy(string $id)
    {
        $size = $this->featureModel->find($id);
        $size->delete();
    }
}
