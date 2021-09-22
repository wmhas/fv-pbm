@extends('layouts.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Update Items</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
                    <li class="breadcrumb-item active"><a href="{{ url('/item/' . $item->id . '/view') }}">View Items</a></li>
                    <li class="breadcrumb-item active">Update Items</li>
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
                <div class="card-body">
                    <form action="{{ url('/item/'.$item->id.'/update/save') }}" method="post">
                        @csrf
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
                                    <input type="text" class="form-control" name="stock_level" @if (!empty($item)) value="{{$item->stock_level}}" @endif>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Brand Name</label>
                                    <input type="text" class="form-control" name="brand_name" @if (!empty($item)) value="{{$item->brand_name}}" @endif>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Generic Name</label>
                                    <input type="text" class="form-control" name="generic_name" @if (!empty($item)) value="{{$item->generic_name}}" @endif>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Indication</label>
                                    <input type="text" class="form-control" name="indication" @if (!empty($item)) value="{{$item->indication}}" @endif>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Indikasi</label>
                                    <input type="text" class="form-control" name="indikasi" @if (!empty($item)) value="{{$item->indikasi}}" @endif>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Instruction</label>
                                    <input type="text" class="form-control" name="instruction" @if (!empty($item)) value="{{$item->instruction}}" @endif>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Frequency</label>
                                    <select class="form-control" name="frequency">
                                        @if (!empty($item->frequency_id))
                                            <option value="{{ $item->frequency_id }}" selected>
                                                {{ $item->frequency->name }}</option>
                                        @endif
                                        @foreach ($frequencies as $frequency)
                                            <option value="{{ $frequency->id }}">{{ $frequency->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Formula</label>
                                    <select class="form-control" name="formula">
                                        @if (!empty($item->formula_id))
                                            <option value="{{ $item->formula_id }}" selected>
                                                {{ $item->formula->detail }}</option>
                                        @endif
                                        @foreach ($formulas as $formula)
                                            <option value="{{ $formula->id }}">{{ $formula->detail }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Selling Price (RM)</label>
                                    <input type="text" class="form-control" name="selling_price" @if (!empty($item)) value="{{ number_format($item->selling_price, 2) }}" @endif>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Selling UOM</label>
                                    <input type="text" class="form-control" name="selling_uom" @if (!empty($item)) value="{{$item->selling_uom}}" @endif>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Purchase Price (RM)</label>
                                    <input type="text" class="form-control" name="purchase_price" @if (!empty($item)) value="{{ number_format($item->purchase_price, 2) }}" @endif>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Purchase UOM</label>
                                    <input type="text" class="form-control" name="purchase_uom" @if (!empty($item)) value="{{$item->purchase_uom }}" @endif>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Purchase Quantity</label>
                                    <input type="text" class="form-control" name="purchase_quantity" @if (!empty($item)) value="{{$item->purchase_quantity}}" @endif >
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script type="text/javascript">

</script>
@endsection