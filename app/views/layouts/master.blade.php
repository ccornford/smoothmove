<!DOCTYPE html>
<html lang="">
<head>
    <!--[if lt IE 7]><html class="no-js lt-ie10 lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
    <!--[if IE 7]><html class="no-js lt-ie10 lt-ie9 lt-ie8"> <![endif]-->
    <!--[if IE 8]><html class="no-js lt-ie10 lt-ie9"> <![endif]-->
    <!--[if IE 9]><html class="no-js lt-ie10"> <![endif]-->
    <!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Smooth Move</title>

    <!-- Fonts -->
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,800,700,600,300' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Roboto+Slab:400,300,100,700' rel='stylesheet' type='text/css'>

    <!-- Stylesheets -->
    {{ HTML::style('assets/css/main.css') }}
    {{ HTML::style('//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css') }}
    {{ HTML::script('assets/js/libs/jquery/jquery-2.1.1.min.js') }}


    <!-- Javascript Libraries -->
    {{ HTML::script('https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js') }}
    {{ HTML::script('https://code.jquery.com/ui/1.11.4/jquery-ui.min.js') }}
    <!--[if lt IE 9]>
            {{ HTML::script('//html5shiv.googlecode.com/svn/trunk/html5.js') }}
    <![endif]-->


</head>

<body class="@yield('page-class')">
    <!-- START header -->
    <header class="header-navigation">
        <div class="container">
            @include('layouts.header')
        </div>
    </header>
    @yield('content')
    <!-- START footer-->
    <footer class="footer">
        <div class="container">
            @include('layouts.footer')
        </div>
    </footer>
    <!-- Page Specific Scripts -->
    @yield('page-script')
    
    {{ HTML::script('assets/js/main.js') }}


</body>
</html>
