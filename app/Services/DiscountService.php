<?php

namespace App\Services;

use App\Models\Discount;

class DiscountService
{
    protected $discountModel;

    public function __construct(Discount $discountModel)
    {
        $this->discountModel = $discountModel;
    }

    public function list()
    {
        return $this->discountModel->latest('id')->get();
    }

    public function paginate()
    {
        return $this->discountModel->latest('id')->paginate(10)->toArray();
    }

    public function store(array $data)
    {
        $this->discountModel->create($data);
    }

    public function show(string $id)
    {
        return $this->discountModel->find($id);
    }

    public function update(array $data, string $id)
    {
        $discount = $this->discountModel->find($id);
        $discount->update($data);
    }

    public function destroy(string $id)
    {
        $discount = $this->discountModel->find($id);
        $discount->delete();
    }
}
