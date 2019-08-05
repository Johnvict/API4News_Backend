<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <title>News | AdeUnique</title>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">

</head>

<style>
    .footerContainer{
        padding: 5px 0 0 0;
        width: 100%;
        text-align: center;
        font-family: sans-serif;
        color: #000;
        background: #fff;
    }
    .container {
        width: 100%;
        color: white;
    }

    @media (max-width: 768px) {
        .container {
            max-width: 768px;
        }
        body{
            /* background: url('/images/unique.png') no-repeat fixed center / cover 30px; */
            background-color: #000;
        }
    }

    .containerback {
        max-width: 100%;
        /* border: solid 1px black; */
        padding: 5px;
        background: rgba(0, 0, 0, .5);
        box-shadow: 2px 2px 2px rgba(4, 0, 0, 0.5);
        margin: auto auto;
        margin-top: 50px;
        border-radius: 4px;
    }
    .white{
        color: white;
        font-size: 2em;
        text-align: center;
        justify-content: center;
    }

    body{
        -webkit-background-size: 100%;
        -moz-background-size: 100%;
        -o-background-size: 100%;
        background-size: 100%;
        margin: auto auto;
        background-color: #fff;
    }
</style>

<body id="body">
        <div class="container">

            <div class="containerback">
                <h1 class="white"><b>@yield('titleHere')</b></h1>
                    <hr>
                    <div class="container text-center">
                        <div class="row">
                                @yield('content')
                        </div>
                    </div>
                <hr>

                <footer class="footerContainer text-center">
                    <p>&copy {{date('Y')}} AdeUnique Enterprises<br>
                </footer>
            </div>
        </div>
</body>

</html>
