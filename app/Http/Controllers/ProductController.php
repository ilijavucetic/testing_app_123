<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Product;
use App\Price;
use App\Category;
use App\Tax;
use App\ProductImage;
use App\Comment;
use App\OrderProduct;

class ProductController extends Controller
{

    public function __construct()
    {
        //$this->middleware('auth');
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

    public function list_all(){

        $products =  \DB::table('product')
            ->leftJoin('category', 'category.id', '=', 'product.category_id')
            ->leftJoin('price', 'price.product_id', '=', 'product.id')
            ->leftJoin('tax', 'tax.product_id', '=', 'product.id')
            ->leftJoin('product_image', 'product_image.product_id', '=', 'product.id')
            ->select(
                'product.id',
                'product.name',
                'product.description',
                'product.category_id',
                'category.name as category_name',
                'price.price',
                'tax.tax',
                'image',
                'product.created_at',
                'product.updated_at'
            )->groupBy('product.id')->orderBy('product.id', 'asc')
            ->orderBy('price.id', 'asc')->orderBy('product_image.id', 'asc')->limit(5)->get();


        $array = json_decode(json_encode($products), true);
        $data = [];
        foreach($array as $pr){
            $data[$pr["id"]] = $pr;
        }
        $products = json_decode(json_encode($data));

        $categories = Category::orderBy('created_at', 'desc')->get();
        $orders = Category::orderBy('created_at', 'desc')->get();

        return view('home', ["products" => $products, "categories" => $categories, "shopping_cart_orders" => $orders]);

    }

    public function show_category(){

        $products =  \DB::table('product')
            ->leftJoin('category', 'category.id', '=', 'product.category_id')
            ->leftJoin('price', 'price.product_id', '=', 'product.id')
            ->leftJoin('tax', 'tax.product_id', '=', 'product.id')
            ->leftJoin('product_image', 'product_image.product_id', '=', 'product.id')
            ->select(
                'product.id',
                'product.name',
                'product.description',
                'product.category_id',
                'category.name as category_name',
                'price.price',
                'tax.tax',
                'image',
                'product.created_at',
                'product.updated_at'
            )->groupBy('product.id')->orderBy('product.id', 'asc')
            ->orderBy('price.id', 'asc')->orderBy('product_image.id', 'asc')->limit(5)->get();


        $array = json_decode(json_encode($products), true);
        $data = [];
        foreach($array as $pr){
            $data[$pr["id"]] = $pr;
        }
        $products = json_decode(json_encode($data));

        $categories = Category::orderBy('created_at', 'desc')->get();
        $orders = Category::orderBy('created_at', 'desc')->get();

        return view('category', ["products" => $products, "categories" => $categories, "shopping_cart_orders" => $orders,]);



    }

    public function show_product($product_id){

        $product = Product::find($product_id);
        $prices = $product->prices()->orderBy('created_at', 'desc')->limit(1)->get();
        $taxes = $product->taxes()->orderBy('created_at', 'desc')->limit(1)->get();
        $images = $product->product_images()->orderBy('created_at', 'asc')->get();
        $comments = $product->comment()->orderBy('created_at', 'asc')->get();
        $categories = Category::orderBy('created_at', 'desc')->get();

        $orders = Category::orderBy('created_at', 'desc')->get();


        return view('product', ["product" => $product,
            "categories" => $categories, "comments" => $comments, "shopping_cart_orders" => $orders,
        "images" => $images, "price" => $prices[0], "tax" => $taxes, "main_image" => $images[0]->image]);

    }

    public function postCreatePost(Request $request){

        $this->validate($request,[
            'body' => 'required|max:1000',
            'product_id' => 'required'
        ]);

        $product_id = $request['product_id'];

        $comment = new Comment();
        $comment -> description = $request['body'];
        $comment -> product_id = $product_id;
        $message = 'There was an error';
        if($request->user()->comment()->save($comment)){
            $message = 'Post succesfully created';

        }
        #return redirect()->route('product')->with(['message' => $message]);

        $product = Product::find($product_id);
        $prices = $product->prices()->orderBy('created_at', 'desc')->limit(1)->get();
        $taxes = $product->taxes()->orderBy('created_at', 'desc')->limit(1)->get();
        $images = $product->product_images()->orderBy('created_at', 'asc')->get();
        $comments = $product->comment()->where("product_id", $product_id)->orderBy('created_at', 'asc')->get();
        $categories = Category::orderBy('created_at', 'desc')->get();

        return redirect()->route('product', $product_id)->with(["product" => $product, "categories" => $categories,
            "comments" => $comments,
            "images" => $images, "price" => $prices[0], "tax" => $taxes,
            "main_image" => $images[0]->image,
            'message' => $message]);


    }

    public function getDeletePost($post_id){

        $comment = Comment::where('id', $post_id)->first();
        $product_id = $comment->product_id;

        if(Auth::user() != $comment->user){
            return redirect()->back();
        }
        $comment->delete();

        return redirect()->route('product', $product_id)->with(
            ["message" => "Successfully deleted."]);


    }

    public function postEditPost(Request $request){

        $this->validate($request, ['body' => 'required']);

        $comment = Comment::find($request["postId"]);
        $comment->description = $request["body"];
        $comment->update();
        return response()->json(['new_body' => $comment->description], 200);


    }

    public function add_to_cart(Request $request){


    }

}
