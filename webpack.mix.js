const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */
if (mix.inProduction()) {
    mix.version();
}
//All JS
mix.js('resources/js/app.js', 'public/js');
mix.scripts([
    'resources/adminLTE3/plugins/jquery/jquery.min.js',
    'resources/adminLTE3/plugins/jquery-ui/jquery-ui.min.js',
    'resources/adminLTE3/plugins/moment/moment.min.js',
    'resources/adminLTE3/plugins/bootstrap/js/bootstrap.bundle.min.js',
    'resources/adminLTE3/plugins/sparklines/sparkline.js',
    'resources/adminLTE3/plugins/jquery-knob/jquery.knob.min.js',
    'resources/adminLTE3/adminLTE3/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js',
    'resources/adminLTE3/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js',
    'resources/adminLTE3/plugins/select2/js/select2.full.min.js',
    'resources/adminLTE3/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js',
    'resources/adminLTE3/dist/js/adminlte.js',
    'public/assets/js/lib/waitMe.min.js',
    'public/assets/js/notification.js',
    'public/assets/js/lib/bootstrap-notify.min.js',
    'resources/js/libs/jszip.min.js',
    'public/assets/js/core.js'
], 'public/js/all.js');
//All CSS
mix.styles([
    'resources/adminLTE3/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css',
    'resources/adminLTE3/plugins/icheck-bootstrap/icheck-bootstrap.min.css',
    'resources/adminLTE3/dist/css/adminlte.min.css',
    'resources/adminLTE3/plugins/overlayScrollbars/css/OverlayScrollbars.min.css',
    'resources/adminLTE3/plugins/select2/css/select2.min.css',
    'resources/adminLTE3/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css',
    'resources/adminLTE3/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css',
    'public/assets/css/lib/waitMe.min.css',
    'public/assets/css/base.css'
], 'public/css/all.css');

//Bootstrap Datatable JS
mix.scripts([
    'resources/adminLTE3/plugins/datatables/jquery.dataTables.js',
    'resources/adminLTE3/plugins/datatables-bs4/js/dataTables.bootstrap4.js',
    'resources/adminLTE3/plugins/datatables-fixedheader/js/dataTables.fixedHeader.min.js',
    'resources/adminLTE3/plugins/datatables-fixedheader/js/fixedHeader.bootstrap4.min.js',
    'resources/adminLTE3/plugins/datatables-buttons/js/dataTables.buttons.min.js',
    'resources/adminLTE3/plugins/datatables-buttons/js/buttons.bootstrap4.min.js',
    'resources/adminLTE3/plugins/datatables-buttons/js/buttons.colVis.min.js',
    'resources/adminLTE3/plugins/datatables-buttons/js/buttons.html5.min.js',
], 'public/js/datatable.js');

//Bootstrap Datatable CSS
mix.styles([
    'resources/adminLTE3/plugins/datatables-bs4/css/dataTables.bootstrap4.css',
    'resources/adminLTE3/plugins/datatables-fixedheader/css/fixedHeader.bootstrap4.min.css',
    'resources/adminLTE3/plugins/datatables-buttons/css/buttons.bootstrap4.min.css',
], 'public/css/datatable.css');

//Chart JS
mix.scripts([
    'resources/adminLTE3/plugins/chart.js/Chart.min.js',
], 'public/js/chart.js');

//Download Adaptor
mix.scripts([
    'resources/js/libs/FileSaver.min.js',
    'resources/js/libs/jszip.min.js',
    'resources/js/plugins/download-adaptor.js',
], 'public/js/download-adaptor.js');
