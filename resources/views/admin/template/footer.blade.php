<script src="{{asset('/assets/plugins/jquery/jquery.min.js')}}"></script>
<script src="{{asset('/assets/plugins/slimscrollbar/jquery.slimscroll.min.js')}}"></script>
<script src="{{asset('/assets/js/sleek.bundle.js')}}"></script>

<script src="{{asset('/assets/plugins/data-tables/jquery.datatables.min.js')}}"></script>
<script src="{{asset('/assets/plugins/data-tables/datatables.bootstrap4.min.js')}}"></script>
<script src="{{asset('/assets/plugins/select2/js/select2.min.js')}}"></script>
<script src="{{asset('/assets/js/custom.js')}}"></script>

<script>
    jQuery(document).ready(function() {
        jQuery('#basic-data-table').DataTable({
            "dom": '<"row justify-content-between top-information"lf>rt<"row justify-content-between bottom-information"ip><"clear">'
        });
    });
    $('.select2').select2();
</script>
@livewireScripts
