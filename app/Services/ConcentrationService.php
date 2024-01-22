<?php

namespace App\Services;

use App\Models\Concentration;

class ConcentrationService
{
    protected $concentrationModel;

    public function __construct(Concentration $concentrationModel)
    {
        $this->concentrationModel = $concentrationModel;
    }

    public function list()
    {
        return $this->concentrationModel->latest('id')->get(['id', 'name', 'slug']);
    }

    public function paginate()
    {
        return $this->concentrationModel->latest('id')->paginate(10)->toArray();
    }

    public function store(array $data)
    {
        $this->concentrationModel->create($data);
    }

    public function show(string $id)
    {
        return $this->concentrationModel->find($id);
    }

    public function update(array $data, string $id)
    {
        $concentration = $this->concentrationModel->find($id);
        $concentration->update($data);
    }

    public function destroy(string $id)
    {
        $concentration = $this->concentrationModel->find($id);
        $concentration->delete();
    }
}
