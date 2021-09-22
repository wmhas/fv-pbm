<!DOCTYPE html>
<html>

<style>
    .bottom {
        width: 100%;
        border-top: 1px solid black;
        border-collapse: collapse;
        padding: 5px;
        border-right: 1px solid black;
        border-left: 1px solid black;
    }
    .bottom .nak-tepi{
        border-right: 1px solid black;
        border-left: 1px solid black;
        border-collapse: collapse;
        padding: 10px;
        border-top: 1px solid black;
        border-bottom: 1px solid black;
    }
</style>

<body>
    <table class="bottom">
        <tr >
            <th >No</th>
            <th>Customer Name</th>
            <th>Quantity</th>
            <th>Amount</th>
        </tr>
        @foreach($patient_lists as $list)
        <tr>
            <td class="nak-tepi">{{$loop->iteration}}</td>
            <td class="nak-tepi">{{$list->full_name}}</td>
            <td class="nak-tepi">{{$list->quantity}}</td>
            <td class="nak-tepi">{{number_format($list->amount,2)}}</td>
        </tr>
        @endforeach
    </table>
</body>

</html>