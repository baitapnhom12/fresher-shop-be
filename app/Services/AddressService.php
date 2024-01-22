<?php

namespace App\Services;

use App\Models\Address;

class AddressService
{
    protected $addressModel;

    public function __construct(Address $addressModel)
    {
        $this->addressModel = $addressModel;
    }

    public function list()
    {
        return $this->addressModel->where('user_id', auth()->user()->id)->latest('id')->paginate(10);
    }

    public function store(array $data)
    {
        $this->addressModel->create($data);
    }

    public function show(string $id)
    {
        return $this->addressModel->find($id);
    }

    public function update(array $data, string $id)
    {
        $address = $this->addressModel->find($id);
        $address->update($data);
    }

    public function destroy(string $id)
    {
        $address = $this->addressModel->find($id);
        $address->delete();
    }
}
