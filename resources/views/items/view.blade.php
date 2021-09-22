@extends('layouts.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">View Items</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
                    <li class="breadcrumb-item active"><a href="{{ url('/item/index') }}">Items</a></li>
                    <li class="breadcrumb-item active">View Items</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <!--  ITEM INFO  -->
    <div class="row" >
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>Item Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Item Code</label>
                                <input type="text" class="form-control" @if (!empty($item)) value="{{$item->item_code}}" @endif readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Current Stock</label>
                                <input type="text" class="form-control" @if (!empty($item->stock_level())) value="{{$item->stock_level()->quantity }}" @endif readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Stock Level</label>
                                <input type="text" class="form-control" @if (!empty($item)) value="{{$item->stock_level}}" @endif readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Brand Name</label>
                                <input type="text" class="form-control" @if (!empty($item)) value="{{$item->brand_name}}" @endif readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Generic Name</label>
                                <input type="text" class="form-control" @if (!empty($item)) value="{{$item->generic_name}}" @endif readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Indication</label>
                                <input type="text" class="form-control" @if (!empty($item)) value="{{$item->indication}}" @endif readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Indikasi</label>
                                <input type="text" class="form-control" @if (!empty($item)) value="{{$item->indikasi}}" @endif readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Instruction</label>
                                <input type="text" class="form-control" @if (!empty($item)) value="{{$item->instruction}}" @endif readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Frequency</label>
                                <input type="text" class="form-control" @if (!empty($item->frequency)) value="{{$item->frequency->name }}" @endif readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Formula</label>
                                <input type="text" class="form-control" @if (!empty($item->formula)) value="{{$item->formula->detail}}" @endif readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Selling Price (RM)</label>
                                <input type="text" class="form-control" @if (!empty($item)) value="{{ number_format($item->selling_price, 2) }}" @endif readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Selling UOM</label>
                                <input type="text" class="form-control" @if (!empty($item)) value="{{$item->selling_uom}}" @endif readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Purchase Price (RM)</label>
                                <input type="text" class="form-control" @if (!empty($item)) value="{{ number_format($item->purchase_price, 2) }}" @endif readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Purchase UOM</label>
                                <input type="text" class="form-control" @if (!empty($item)) value="{{$item->purchase_uom }}" @endif readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Purchase Quantity</label>
                                <input type="text" class="form-control" @if (!empty($item)) value="{{$item->purchase_quantity}}" @endif readonly>
                            </div>
                        </div>
                    </div>
                    <a href="{{ url('/item/'.$item->id.'/update') }}" style="float:right; " class="btn btn-primary">Update Item</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection