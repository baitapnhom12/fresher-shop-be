<?php

namespace App\Http\Controllers;

use App\Http\Requests\Products\CreateProductRequest;
use App\Http\Requests\Products\UpdateProductRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Concentration;
use App\Models\Discount;
use App\Models\Feature;
use App\Models\Size;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function list(Request $request)
    {
        $categories = Category::all('id', 'name');
        $concentrations = Concentration::all('id', 'name');
        $brands = Brand::all('id', 'name');
        $sizes = Size::all('id', 'name');
        $products = $this->productService->list($request)->getData();

        return view('products.list', compact('products', 'categories', 'concentrations', 'brands', 'sizes'));
    }

    public function create()
    {
        $categories = Category::all('id', 'name');
        $concentrations = Concentration::all('id', 'name');
        $brands = Brand::all('id', 'name');
        $sizes = Size::all('id', 'name');
        $discounts = Discount::where('active', 1)->get(['id', 'name', 'percent']);
        $features = Feature::all(['id', 'feature']);

        return view('products.create', compact('categories', 'concentrations', 'brands', 'sizes', 'discounts', 'features'));
    }

    public function store(CreateProductRequest $request)
    {
        try {
            $result = $this->productService->storeProduct($request);

            if ($result) {
                return redirect()->route('admin.product.list')->with('success', 'Created successfully');
            } else {
                return back()->with('error', 'Created unsuccessfully');
            }
        } catch (\Exception $exception) {
            return back()->with('error', 'Error');
        }
    }

    public function detail($id)
    {
        $product = $this->productService->detail($id)->getData();
        $categories = Category::whereNotIn('id', array_column($product->categories, 'id'))->get(['id', 'name']);
        $concentrations = Concentration::whereNot('id', $product->concentration->id)->get(['id', 'name']);
        $brands = Brand::whereNot('id', $product->brand->id)->get(['id', 'name']);
        $sizes = Size::select('id', 'name')->whereNotIn('id', array_column($product->quantities, 'sizeId'))->get();
        $discounts = Discount::where('active', 1)->whereNotIn('id', array_column($product->discounts, 'discountId'))->get(['id', 'name', 'percent']);
        $features = Feature::whereNotIn('id', array_column($product->features, 'id'))->get(['id', 'feature']);

        return view('products.detail', compact('product', 'categories', 'concentrations', 'brands', 'sizes', 'discounts', 'features'));
    }

    public function update(UpdateProductRequest $request, $id)
    {
        try {
            $result = $this->productService->updateProduct($request, $id);

            if ($result) {
                return redirect()->route('admin.product.list')->with('success', 'Edited successfully');
            } else {
                return back()->with('error', 'Edited unsuccessfully');
            }
        } catch (\Exception $exception) {
            return back()->with('error', 'Error');
        }
    }

    public function delete($id)
    {
        try {
            $result = $this->productService->delete($id);

            if ($result) {
                return redirect()->route('admin.product.list')->with('success', 'Deleted successfully');
            } else {
                return back()->with('error', 'Deleted unsuccessfully');
            }
        } catch (\Exception $exception) {
            return back()->with('error', 'Error');
        }
    }
}
