@extends('layouts.master')

@section('content')

    <div class="container-fluid">
        <div class="row well well-sm">
            <div class="col-xs-2" style="float:left;width:18%;height:100%;">
                <div class="list-group">
                    @foreach($categories as $category)
                        <a href="/category/{{$category->id}}" class="list-group-item">{{$category->name}}</a>
                    @endforeach
                </div>
                {{--<ul class="category_ul list-group">--}}
                    {{--@foreach($categories as $categoryRS)--}}
                        {{--<li style="border: solid 1px transparent; margin: 1px;" class="category_li list-group-item"--}}
                            {{--value={{$categoryRS['id']}} id="category_li_{{$categoryRS['id']}}">--}}
                            {{--&nbsp&nbsp{{$categoryRS['name']}}--}}
                            {{--<div style='width: 100%;' class="category_div" id="category_div_{{$categoryRS['id']}}">--}}
                            {{--</div>--}}
                        {{--</li>--}}
                    {{--@endforeach--}}
                {{--</ul>--}}
            </div>

            <div class="col-xs-8">
                <ul class="rslides" id="discout-products-slide" style='background-color:white;'>
                    @foreach($products as $discount_product)
                        <li>
                            <div class="row">
                                <div class="col-xs-7">
                                    <a href="/pages/product/{{$discount_product->id}}">
                                        <img
                                                class='slide-prod-img'
                                                src="http://lorempixel.com/350/250/technics/?{{$discount_product->id}}" alt="">

                                        <p class="caption">{{$discount_product->name}}</p></a>
                                </div>

                            </div>
                        </li>
                    @endforeach
                </ul>
                <hr>
                <div class="row">
                    <div class="col-xs-12">
                        <h2>Most popular products</h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <ul class="nav nav-tabs">
                            <li><a href="#week" data-toggle="tab">This week</a></li>
                            <li><a href="#month" data-toggle="tab">Past month</a></li>
                            <li class="active"><a href="#all" data-toggle="tab" aria-expanded="true">All time</a></li>
                        </ul>
                    </div>
                </div>
                <hr>

                {{--'products','top_product_info','top_seller_info','popular_products_week','popular_products_month','
            popular_products_all', 'discount_products', 'categories', 'sub_categories',
            'user', 'selected_user_info', 'shopping_cart_orders', 'rates_array'--}}
                <div class="row">
                    <div class="col-xs-6">
                        <div class="panel panel-success well well-sm">
                            <div class="panel-heading">
                                <h3 class="panel-title">Top sellers</h3>
                            </div>
                        </div>

                    </div>

                    <div class="col-xs-6">
                        <div class="panel panel-warning well well-sm">
                            <div class="panel-heading">
                                <h3 class="panel-title">Top products</h3>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h3 class="panel-title">Recently added products</h3>
                        </div>
                    </div>
                </div>
            </div>

            {{--/main row--}}
        </div>
        {{--/main container fluid--}}
    </div>
@endsection

@section("js-end")
    <script src="{{ URL::to('src/js/index.js') }}" type="text/javascript"></script>
@endsection