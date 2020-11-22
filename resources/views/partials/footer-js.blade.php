<script>
    var siteUrl = "{{ url('') }}";
    var csrf_token = "{{ csrf_token() }}";
    var isSuperAdmin    = {{ (!Auth::guest() && Auth::user()->hasRole('super admin')) ? 'true' : 'false' }};
</script>
<script src="{{ mix("js/app.js") }}"></script>
<script src="{{ mix("js/all.js") }}"></script>
<script>
    $.widget.bridge('uibutton', $.ui.button)
</script>
