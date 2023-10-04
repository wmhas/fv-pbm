<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="container">
        <div class="container-fluid">
            <div class="row">
                <div class="col-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <table class="table table-striped">
                                        <tr>
                                            <th>DO Number</th>
                                            <td>{{ $order->do_number }}</td>
                                        </tr><tr>
                                            <th>Patient Name</th>
                                            <td>{{ $order->patient->full_name }}</td>
                                        </tr><tr>
                                            <th>Dispensing Method</th>
                                            <td>{{ $order->dispensing_method }}</td>
                                        </tr><tr>
                                            <th>Date of Issue</th>
                                            <td>{{ date_format(date_create($order->created_at), 'd/m/Y') }}</td>
                                        </tr><tr>
                                            <th>Dispensing Date</th>
                                            <td>{{ date_format(date_create($order->dispense_date), 'd/m/Y') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('order.date.update', [$order->do_number]) }}" method="post">
                                @csrf
                                @method('patch')
        
                                <div class="row mb-2">
                                    <div class="col-12">
                                        <label for="date_issue">Issue Date</label>
                                        <input type="date" name="date_issue" id="date_issue" class="form-control" required>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-12">
                                        <label for="date_dispense">Dispensing Date</label>
                                        <input type="date" name="date_dispense" id="date_dispense" class="form-control" required>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-12">
                                        <button class="btn btn-primary btn-block">Update</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</body>
</html>