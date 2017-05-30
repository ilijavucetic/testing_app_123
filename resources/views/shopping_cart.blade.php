@extends('layouts.master')

@section('content')

    <div class="container-fluid">
        <div class="row">
            User
            information: {{$user_info->name.' '.$user_info->last_name.", ".$user_info->address.' from '.$user_info->city
            .'/'.$user_info->country}}
        </div>

        @foreach($cart_orders as $order)
            <br>
            <div class="row">
                <div class="col-xs-12 order-row-{{$order->id}}">
                    {{"Order id: ".$order->id}}
                </div>
            </div>
            <br>
            <div class="row order-row-{{$order->id}}">
                <div class="col-xs-1">
                    <img class='img-thumbnail' style='height:100px;width:150px;'
                         src="{{$order->product->product_images()->first()["image"]}}">
                </div>
                <div class="col-xs-2">
                    <div class="row">{{$order->product->name}}</div>
                    <div class="row">Color: <span
                                style="width:16px;display: inline-block;background-color:{{$order['color']}};border:solid 2px;">&nbsp;</span>
                    </div>
                    {{--<div class="row">Type: <span>{{$order['type']}}</span></div>--}}
                </div>
                <div class="col-xs-1">
                    <div class="row" style="text-align:center;">Quantity:</div>
                    <div class="row">
                        <hr>
                    </div>
                    <div class="row"><input value='{{$order->quantity}}' class='form-control'
                                            style="text-align: center;vertical-align: middle;border: solid 1px;"
                                            id="quantity">
                    </div>
                </div>
                <div class="col-xs-1">
                    <div class="row">
                        <div class="col-xs-12">Price:</div>
                    </div>
                    <div class="row">
                        <hr>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">{{$order->price}}</div>
                    </div>
                </div>
                <div class="col-xs-2">
                    <div class="row">
                        <div class="col-xs-12">Selected Shipping</div>
                    </div>
                    <div class="row">
                        <hr>
                    </div>
                </div>
                <div class="col-xs-1">
                    <div class="row">
                        <div class="col-xs-12">
                            Total:
                        </div>
                    </div>
                    <div class="row">
                        <hr>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            {{$order->price*$order->quantity}}
                        </div>
                    </div>
                </div>
                <div class="col-xs-1">
                    <div class="row">
                        &nbsp;
                    </div>
                    <div class="row">
                        <hr>
                    </div>
                    <div class="row"><a href="#" value={{$order->id}} id="remove-order-{{$order->id}}"
                                        class="btn btn-default btn-sm">Remove</a></div>
                </div>
            </div>
            <hr>
        @endforeach
        {{--@if(count($shopping_cart_orders)!=0)--}}
            {{--<div class="row">--}}
                {{--<div class="col-xs-4 col-xs-offset-2">--}}
                    {{--<div class="row">--}}
                        {{--<div class="col-xs-12"><h3>Total payment: 1234</h3></div>--}}
                    {{--</div>--}}
                    {{--<div class="row">--}}
                        {{--<div class="col-xs-12"><button class="btn btn-default">Proceed to Checkout</button></div>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--@endif--}}
    </div>
@endsection

@section("js-end")
    {{--<script src="{{ URL::to('src/js/index.js') }}" type="text/javascript"></script>--}}
@endsection