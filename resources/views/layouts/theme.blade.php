<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Evolution Marketing - CRM Dashboard</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Ionicons -->
        <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

        <!-- Google Font: Source Sans Pro -->
        <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

        <!-- Font Awesome -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css" rel="stylesheet">

        <!-- All CSS -->
        <link href="{{ mix('/css/all.css') }}" rel="stylesheet">

        <!-- SCSS -->
        <link href="{{ mix('/css/app.css') }}" rel="stylesheet">

        @yield('css')
    </head>

    <?php
        if( !isset($currentSection) ) $currentSection = '';
        if( !isset($initialExpandOnHover) ) $initialExpandOnHover = false;
    ?>

    <body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed {{ $initialExpandOnHover ? 'sidebar-collapse' : ''}} text-sm">
        <div id="app" class="wrapper">

            <!-- Navbar -->
            @include('partials.top-nav-bar')

            <!-- Main Sidebar Container -->
            @include('partials.left-side-bar')

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <div class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                @yield('content-header')
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">
                        @include('partials.page-alert')

                        @yield('content')
                    </div>
                </section>
                <!-- /.content -->
            </div>

            <footer class="main-footer">
                Powered by <a href = "https://www.evolutionmarketing.com">Evolution Marketing</a>
            </footer>
        </div>

        @include('partials.footer-js')

        @yield('javascript')
    </body>
</html>
