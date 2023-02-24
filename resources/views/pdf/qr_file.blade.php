<!DOCTYPE html>
<html>

<head>
    <style>
        .box-card {
            width: 332px;
            height: 224px;
            border: 0px transparent;
        }

        .hero-image {
            background-image: url("Background-access-card.png");
            background-color: #cccccc;
            width: 332px;
            height: 224px;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }
        .table {
            width:100%;
            padding: 10px 15px 20px 10px;
        }
        .img-logo {
            width: 65px;
        }
        .img-qr {
            width: 56px;
        }
        .code-card {
            width: 100%;
            background-color: #ffff;
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;
            padding-left: 10px;
            font-size: 6pt;
            border: 0px transparent;
        }
        .qr-card {
            width: 100%;
            background-color: #ffff;
            border: 0px transparent;
        }

    </style>
</head>

<body>
    <table>
        <div class="grid-container">
            <table>
                @php $counter = 0; @endphp
                @for($j=0;$j < $rowData;$j++)
                <tr>
                    @for($i=0;$i < $colData; $i++)
                    @if(isset($data[$counter]))
                    <th style="border:solid 1px"><img class="img-qr" style="width:150px" src="{{url('/')}}/qrcode_trx_file/{{$data[$counter]['code']}}.png" alt=""><br><p style="font-size:11pt">{{$data[$counter]['code']}}</p></th>
                    @php $counter++ @endphp
                    @endif
                    @endfor
                </tr>
                @endfor
            </table>
        </div>
    </table>
</body>
</html>