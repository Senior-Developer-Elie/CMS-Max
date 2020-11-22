<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>{{ $mockup['title'] }}</title>
    <style type="text/css">
        html{
        }
        #content{
            position: relative;
            background-repeat: no-repeat;
            background-color: {{ $mockup['color'] }};
            margin: {{ $mockup['align'] == 'center' ? 'auto' : '0' }};
        }
    </style>
    <link href="{{ asset('assets/css/mockups/show.css') }}?v=1" rel="stylesheet" type="text/css">
</head>

<body>
    <div id="content">
    </div>
    <ul class="leps-directives">
        <li class="leps-next">
            <a>Next</a>
        </li>
        <li class="leps-prev">
            <a>Prev</a>
        </li>
    </ul>

    <!-- jQuery 3.1.1 -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js?v=1"></script>
    <script src="{{ asset('assets/js/mockups/show.js') }}"></script>
    <script>
        var mockupDetails = {!! json_encode($mockup) !!};
    </script>
</body>

</html>
