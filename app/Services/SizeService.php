<?php

namespace App\Services;

use App\Models\Size;

class SizeService
{
    protected $sizeModel;

    public function __construct(Size $sizeModel)
    {
        $this->sizeModel = $sizeModel;
    }

    public function list()
    {
        return $this->sizeModel->latest('id')->get(['id', 'name']);
    }

    public function paginate()
    {
        return $this->sizeModel->latest('id')->paginate(10)->toArray();
    }

    public function store(array $data)
    {
        $this->sizeModel->create($data);
    }

    public function show(string $id)
    {
        return $this->sizeModel->find($id);
    }

    public function update(array $data, string $id)
    {
        $size = $this->sizeModel->find($id);
        $size->update($data);
    }

    public function destroy(string $id)
    {
        $size = $this->sizeModel->find($id);
        $size->delete();
    }
}
