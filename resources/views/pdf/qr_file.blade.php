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
        @foreach($data as $val)
        <img class="img-qr" src="{{url('/')}}/qrcode_trx_file/{{$val['code']}}" alt="">
        <span>{{$val['code']}}</span>
        @endforeach
    </table>
</body>
</html>