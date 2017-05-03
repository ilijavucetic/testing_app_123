<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Product;
use App\Price;
use App\Category;
use App\Tax;
use App\ProductImage;

class ProductController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {

        $products =  \DB::table('product')
            ->leftJoin('category', 'category.id', '=', 'product.category_id')
            ->leftJoin('price', 'price.product_id', '=', 'product.id')
            ->leftJoin('tax', 'tax.product_id', '=', 'product.id')
            ->select(
                'product.id',
                'product.name',
                'product.description',
                'product.category_id',
                'category.name as category_name',
                'price.price',
                'tax.tax',
                'product.created_at',
                'product.updated_at'
            )->orderBy('product.id', 'asc')->orderBy('price.id', 'asc')->get();


        //$products = json_decode($products, true);
        $array = json_decode(json_encode($products), true);
        $data = [];
        foreach($array as $pr){
            $data[$pr["id"]] = $pr;
        }
        $products = json_decode(json_encode($data));

        //$products = Product::orderBy('created_at', 'desc')->get();
        $categories = Category::orderBy('created_at', 'desc')->get();
        //$prices = $products->prices();

        return view('admin.add_product', ["products" => $products, "categories" => $categories]);
    }

    public function saveProduct(Request $request)
    {
        $saved_price = true;
        $saved_tax = true;

        $this->validate($request,[
            'files' => 'required',
            'product_name' => 'required',
            'price' => 'required',
            'tax' => 'required',
            'category_id' => 'required|not_in:-1',
        ]);

        $product_id = $request["product_id"];
        $product_name = $request["product_name"];
        $product_price = $request["price"];
        $product_tax = $request["tax"];
        $description = $request["description"];
        $category_id = $request["category_id"];

        //var_dump($_FILES);

        $images = $request->file('files');
        $images_array = [];
        if($images[0] != null){
            foreach($images as $im){
                $fileName = $im->getClientOriginalName();
                $im->move("images/products/", $fileName);
                $images_array[] = $fileName;
            }
        }


        if($product_id == "-1"){

            $product = new Product();
            $product->name = $product_name;
            $product->category_id = $category_id;
            $product->description = $description;
            $saved = $product->save();
            $inserted_id = $product->id;

            if($saved){

                $price = new Price();
                $price->price = $product_price;
                $price->product_id = $inserted_id;
                $saved_price = $price->save();

                $tax = new Tax();
                $tax->tax = $product_tax;
                $tax->product_id = $inserted_id;
                $saved_tax = $tax->save();

                foreach($images_array as $im){
                    $image = new ProductImage();
                    $image->product_id = $inserted_id;
                    $image->image = $im;
                    $image->main = 0;
                    $saved_image = $image->save();
                    if(!$saved_image)
                        break;
                }
            }
        }
        else{

            $product = Product::find($product_id);
            $product->name = $product_name;
            $product->description = $description;
            $product->category_id = $category_id;
            $saved = $product->update();

            if($saved){

                $old_price = $request["old_price"];
                $old_tax = $request["old_tax"];

                if($product_price != $old_price){

                    $price = new Price();
                    $price->price = $product_price;
                    $price->product_id = $product_id;
                    $saved_price = $price->save();
                }
                if($product_tax != $old_tax){

                    $tax = new Tax();
                    $tax->tax = $product_tax;
                    $tax->product_id = $product_id;
                    $saved_tax = $tax->save();
                }

                foreach($images_array as $im){
                    $image = new ProductImage();
                    $image->product_id = $product_id;
                    $image->image = $im;
                    $image->main = 0;
                    $saved_image = $image->save();
                    if(!$saved_image)
                        break;
                }
            }
        }

        if($saved && $saved_price && $saved_tax)
            $message = "Dodato uspješno";
        else
            $message = "Greška prilikom dodavanja";

        return redirect()->route('add_product')->with(['message' => $message]);

    }

    public function showHistory($product_id){

        $product = Product::find($product_id);
        $prices = $product->prices()->orderBy('created_at', 'desc')->get();
        $taxes = $product->taxes()->orderBy('created_at', 'desc')->get();

        return response()->json(['prices' => $prices, "taxes" => $taxes, "product" => $product], 200);
    }

}
