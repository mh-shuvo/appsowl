<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Appsowl || 500 Error</title>

    <link rel="shortcut icon" href="{{ asset('favicon.png') }}">

    <link href="{{asset('dashboard/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('dashboard/font-awesome/css/font-awesome.css')}}" rel="stylesheet">
    <link href="{{asset('dashboard/css/animate.css')}}" rel="stylesheet">
    <link href="{{asset('dashboard/css/style.css')}}" rel="stylesheet">

</head>

<body class="gray-bg">


    <div class="middle-box text-center animated fadeInDown">
        <h1><strong>500</strong></h1>
        <h3 class="font-bold">Internal Server Error</h3>

        <div class="error-desc">
            The server encountered something unexpected that didn't allow it to complete the request. We apologize.<br/>
            You can go back to main page: <br/><a href="{{url('/')}}" class="btn btn-primary m-t">Dashboard</a>
        </div>
    </div>

    <!-- Mainly scripts -->
    <script src="{{asset('dashboard/js/jquery-3.1.1.min.js')}}"></script>
    <script src="{{asset('dashboard/js/bootstrap.min.js') }}"></script>

</body>

</html>
