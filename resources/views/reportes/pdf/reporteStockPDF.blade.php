<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de stock de productos</title>

    <style>
        @page {
            margin: 0cm 0cm;
        }

        strong {
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
            font-size: 14px
        }

        body {
            margin-top: 3.4cm;
            margin-bottom: 3cm;
            color: #4f4f4f;
        }

        #watermark {
            position: fixed;
            bottom: 0px;
            left: 0px;
            width: 29.7cm;
            height: 21cm;
            z-index: -1000;
            /* opacity: 0.5; */
        }

        header {
            position: fixed;
            top: 0cm;
            left: 0cm;
            right: 0cm;
            height: 3cm;
        }

        footer {
            position: fixed;
            bottom: 0cm;
            left: 0cm;
            right: 0cm;
            height: 3cm;
        }

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

        .table tr th {
            padding: 5px;
            font-size: 13px;
        }

        .table tr td,
            {
            font-size: 12px;
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

        .oficio {
            color: black;
            margin-left: auto;
            margin-right: auto;
            padding: 0px 30px;
        }

        .text-strong {
            font-weight: bold;
        }

        .text-justify {
            text-align: justify;
        }

        .d-flex {
            display: flex !important;
        }

        .col-left {
            margin-right: 66.666666%;
        }

        .col-center {
            margin-left: 33.333333%;
            margin-right: 33.333333%
        }

        .col-right {
            margin-left: 66.666666%;
        }

        .col-uno {
            margin-right: 50%;
        }

        .col-dos {
            margin-left: 50%;
        }

        .line {
            width: 20%;
            border-top: 1px solid rgb(38, 38, 38);
            height: 10px;
        }
        .section-tabla{
            /* margin-bottom: 150px; */
        }

        .entregado {
            margin-left: 150px
        }

    </style>
</head>
<body>
    <header></header>
    <footer></footer>
    <div id="watermark">
        <img src="../public/img/reporte-pdf.png" height="100%" width="100%" />
    </div>
    <main>
        <!-- CUERPO -->
        <div class="oficio">
            <div class="text-center text-strong" style="margin-bottom: 20px">
                REPORTE DE STOCK
            </div>

            <table class="table table-bordered">
                <thead class="thead-dark-1">
                    <tr>
                        <th width="30%">Nombre producto</th>
                        <th width="10%">Lote</th>
                        <th width="20%">Área</th>
                        <th width="10%">Stock actual</th>
                        <th width="11%">F. Caducidad</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($productos as $item)
                    <tr>
                        <td>{{$item->producto_nombre}}</td>
                        <td class="text-center">{{$item->lote}}</td>
                        <td>{{$item->area_nombre}} - {{$item->area_tipo}}</td>
                        <td class="text-center">{{$item->cantidad}}</td>
                        <td class="text-center">{{date('d-m-Y' , strtotime($item->fecha_cadu))}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <script type="text/php">
            if ( isset($pdf) ) {

                $pdf->page_script('
                    $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
                    $color = array(0.3, 0.3, 0.3);
                    $pdf->text(29, 520, "Generado por: {{mb_strtoupper(Auth::user()->username)}} el {{date('d/m/y H:i:s' , strtotime(date('Y-m-d H:i:s'))) }}", $font, 11, $color);
                    $pdf->text(745, 520, "Página $PAGE_NUM de $PAGE_COUNT", $font, 12, $color);
                ');
            }
        </script>
    </main>
</body>
</html>
