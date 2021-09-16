<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="referrer" content="always">
    <link rel="canonical" href="/login">
    <link rel="shortcut icon" type="image/jpg" href="https://i.imgur.com/UyXqJLi.png">
    <title>{{$title}}</title>
    <!--  CSS  -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://font.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&display=swap">
    <!-- JS -->
    <script src="{{ asset('js/app.js') }}"></script>
</head>
<body>
    <!-- CONTENT --> 
    @yield('content')
</body>
</html>