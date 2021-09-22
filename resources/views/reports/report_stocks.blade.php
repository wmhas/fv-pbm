<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Courier - Stocks</title>
    <style>
        * {
            font-family: arial, sans-serif;
        }

        table.main,
        th.main {
            border: 1px solid black;
            border-collapse: collapse;
            width: 100%;
        }

        th.main {
            text-align: center;
        }

        th.main,
        td.main {
            padding-bottom: 3px;
            padding-top: 3px;
            padding-right: 8px;
            padding-left: 8px;
        }

        td.main {
            border-bottom: 1px solid black;
        }

    </style>
</head>

<body>
    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <th style="text-align: center; width: 90%">COURIER ONLY</th>
            <th style="text-align: center;">{{$date}}</th>
        </tr>
    </table>
    <table class="main">
        <tr class="main">
            <th style="width: 10%" class="main">Item #</th>
            <th style="width: 45%" class="main">Item Name</th>
            <th style="width: 15%" class="main">On Hand</th>
            <th style="width: 15%" class="main">Committed</th>
            <th style="width: 15%" class="main">Available</th>
        </tr>
        @foreach ($items as $item)
            <tr>
                <td style="width: 10%" class="main">{{ $item->item_code }}</td>
                <td style="width: 45%" class="main">{{ $item->brand_name }}</td>
                <td style="width: 15%" class="main">
                    @if ($item->on_hand != null)
                        {{ $item->on_hand }}
                    @else
                        0
                    @endif
                </td>
                <td style="width: 15%" class="main">
                    @if (!empty($item->committed))
                        {{ $item->committed }}
                    @else
                        0
                    @endif
                </td>
                <td style="width: 15%" class="main">
                    @if (!empty($item->committed) && $item->on_hand != null)
                        {{ $item->on_hand - $item->committed }}
                    @elseif($item->on_hand == null)
                        0
                    @else
                        {{ $item->on_hand }}
                    @endif
                </td>
            </tr>
        @endforeach
    </table>
</body>

</html>
