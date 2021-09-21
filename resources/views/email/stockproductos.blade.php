<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PRODUCTOS POR AGOTARSE</title>

    <style>
        table {
            margin: 0px;
            font-family: Arial, Helvetica, sans-serif;
            width: 100%;
            max-width: 100%;
            background-color: transparent;
            border-collapse: collapse;
        }

        tr {
            display: table-row;
            vertical-align: inherit;
            border-color: inherit;
        }

        .thead-dark-1 tr th,
        .thead-dark-1 tr td {
            /* background: #1D2B36;
            color: #fff;
            border: solid 1px #585858;
            */
            background: #DFDFDF;
            color: #333;
            text-align: center;
        }

        .tfoot td {
            background: #DFDFDF;
            color: #333;
        }

        .table thead tr th {
            padding: 5px;
            font-size: 11px;
        }

        .table tbody tr td{
            font-size: 10px;
            text-transform: uppercase;
        }

        .table-bordered tr th,
        .table-bordered tr td {
            /* border: 1px solid #e3ebf3; */
            border: 1px solid #525252;
        }

        .text-left {
            text-align: left;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>
    <div style="margin-bottom: 20px;">PRODUCTOS POR AGOTARSE</div>

    <table class="table">
        <thead class="thead-dark-1">
            <tr>
                <th class="text-left">Producto</th>
                <th class="text-left">Área</th>
                <th class="text-center">F.exp</th>
                <th class="text-center">Lote</th>
                <th class="text-center">Stock</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($productos as $item)
            <tr>
                <td class="text-left">{{$item->producto_nombre}} <br> <small>{{$item->producto_descripcion}}</small></td>
                <td class="text-left">{{$item->area_nombre}} - {{$item->area_tipo}}</td>
                <td class="text-center">{{date('d/m/Y' , strtotime($item->fecha_caduc))}}</td>
                <td class="text-center">{{$item->lote}}</td>
                <td class="text-center">{{$item->stock <= 0 ? 0 : $item->stock}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
