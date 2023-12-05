@extends('layouts.app')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Item</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Item</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <form method="get" action="{{ route('item.search') }}">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text" for="stock">Stock</label>
                                        </div>
                                        <select class="form-control" name="stock" id="stock">
                                            <option value="">All</option>
                                            <option {{ isset($stock) && $stock === 'low' ? 'selected' : ''  }} value="low">Low Stock</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" @if (!empty($keyword)) value="{{ $keyword }}" @endif name="keyword" class="form-control" placeholder="Item Code / Item Name">
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-secondary w-100">Search</button>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-primary w-100" onclick="location.href='{{ route('item.create') }}'">Add New Item</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- tariff searching -->
            <!-- <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="col-md-12">
                            <div class="input-group pb-2">
                                <div class="input-group-prepend">
                                    <label class="input-group-text" for=" ">Tariff</label>
                                </div>
                                <select class="custom-select" name="tarif" onchange="this.form.submit()">
                                    <option value="" @if ($tarif == null) selected @endif>All</option>
                                    <option value=1 @if ($tarif != null && $tarif == 1) selected @endif>JHEV</option>
                                    <option value=2 @if ($tarif != null && $tarif == 2) selected @endif>JPA</option>
                                    <option value=3 @if ($tarif != null && $tarif == 3) selected @endif>MINDEFF</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body" style="overflow-x:auto;">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width:8px">No</th>
                                    <th>Item</td>
                                    <th>Purchase Price (RM)</th>
                                    <th>Selling Price (RM)</th>
                                    <th>UOM</th>
                                    <th>Stock</th>
                                    <th>Stock Level</th>
                                    <!-- <td>Tariff</td> -->
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $i)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <a href=" {{ url('/item/' . $i->id . '/view') }}" data-toggle="tooltip" title="View Detail" >
                                                {{ $i->item_code }} 
                                            </a>
                                            <br><br>
                                            {{ $i->brand_name }}
                                        </td>
                                        @foreach ($purchase_prices as $price)
                                        @if ($i->id == $price['item_id'])    
                                        <td style="text-align: right;">{{ number_format($price['purchase_price'], 2) }} </td>
                                        @else
                                        @endif
                                        @endforeach
                                        <td style="text-align: right;">{{ number_format($i->selling_price, 2) }} </td>
                                        <td>{{ $i->selling_uom }}</td>
                                        <td>
                                            @if (!empty($i->stock_level())) 
                                                @if ($i->stock_level()->quantity <= $i->stock_level)
                                                    <span class="badge bg-danger" style="font-size: 15px;"><b>(!)</b> {{$i->stock_level()->quantity}}</span> 
                                                @else
                                                    {{$i->stock_level()->quantity}} 
                                                @endif
                                            @else 
                                                0
                                            @endif
                                        </td>
                                        <td>{{ $i->stock_level }}</td>
                                        {{-- <td>{{ $i->tariff->name }}</td> --}}
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="card-footer">
                            {{ $items->withQueryString()->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
    $(document).ready(function(){
      $('[data-toggle="tooltip"]').tooltip();   
    });
</script>
@endsection
