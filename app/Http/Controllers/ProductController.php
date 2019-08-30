<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $productFile = file_get_contents(public_path('products.json'));
        $products_data = json_decode($productFile, true);
        $products = array_reverse($products_data["products"]);
        return view('welcome', compact('products'));
    }


    public function store(Request $request)
    {
        $data = $this->getData($request);

        //get the products.json file
        $products = file_get_contents(public_path('products.json'));
        $products_data = json_decode($products, true);

        $products_data["products"] = array_values($products_data["products"]);
        array_push($products_data["products"], $data);
        file_put_contents(public_path('products.json'), json_encode($products_data));
        return $data;
    }

    public function update($id, Request $request){
        $data = $this->getEditData($request);
        $getFile = file_get_contents(public_path('products.json'));
        $allProducts = json_decode($getFile, true);
        $products = array_reverse($allProducts["products"]);
        $product = $products[$id];
        if ($product) {
            unset($product);
            $products = array_reverse($allProducts["products"]);
            $products[$id] = $data;
            $allProducts["products"] = array_reverse($products);
            file_put_contents(public_path('products.json'), json_encode($allProducts));
        }
        return $data;
    }

    public function destroy($id)
    {
        $all = file_get_contents(public_path('products.json'));
        $all = json_decode($all, true);
        $products = array_reverse($all["products"]);
        $product = $products[$id];

        if ($product) {
            unset($product);
            $all["products"] = array_values($all["products"]);
            file_put_contents(public_path('products.json'), json_encode($all));
        }
        return $id;
    }

    protected function getData(Request $request)
    {
        $rules = [
            'product_name' => 'required|string|min:1|max:255',
            'quantity' => 'required|integer|min:1|max:255',
            'price' => 'required|integer|min:1|max:255',
            'date_time' => 'nullable',

        ];
        $data = $request->validate($rules);
        $data['date_time'] = date("Y-m-d H:i:s", time());
        return $data;
    }

    protected function getEditData(Request $request)
    {
        $rules = [
            'product_name' => 'required|string|min:1|max:255',
            'quantity' => 'required|integer|min:1|max:255',
            'price' => 'required|integer|min:1|max:255',
            'date_time' => 'nullable',
        ];
        $data = $request->validate($rules);
        return $data;
    }

}
