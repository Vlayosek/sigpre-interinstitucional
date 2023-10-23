<script src="{{ url('js/jquery/') }}/jquery2.js"></script>
<script src="{{ url('js/jquery/') }}/jqueryui.js"></script>
<script src="{{ url('js/jquery/') }}/jqueryuitouch.js"></script>
<script src="{{ url('adminlte/plugins/respond/') }}/html5shiv.min.js"></script>
<script src="{{ url('adminlte/plugins/respond/') }}/respond.min.js"></script>

<script src="{{ url('adminlte//plugins/datatables/') }}/jquery.dataTables2.min.js"></script>
<script src="{{ url('adminlte/js') }}/dataTables.buttons.min.js"></script>
<script src="{{ url('adminlte/js') }}/buttons.flash.min.js"></script>
<script src="{{ url('adminlte/js') }}/jszip.min.js"></script>
<script src="{{ url('adminlte/js') }}/pdfmake.min.js"></script>
<script src="{{ url('adminlte/js') }}/vfs_fonts.js"></script>
<script src="{{ url('adminlte/js') }}/buttons.html5.min.js"></script>
<script src="{{ url('adminlte/js') }}/buttons.print.min.js"></script>
<script src="{{ url('adminlte/js') }}/buttons.colVis.min.js"></script>
<script src="{{ url('adminlte/js') }}/dataTables.select.min.js"></script>
<script src="{{ url('adminlte/js') }}/bootstrap.min.js"></script>
<script src="{{ url('adminlte/js') }}/dataTables.colReorder.min.js"></script>
<script src="{{ url('adminlte/plugins/fileinput/fileinput.min.js') }}"></script>

<script src="{{ url('adminlte/js') }}/main.js"></script>

<script src="{{ url('adminlte/plugins/notifications/pnotify.min.js') }}"></script>
<script src="{{ url('adminlte/plugins/notifications/sweet_alert.min.js') }}"></script>

<script src="{{ url('adminlte/plugins/slimScroll/jquery.slimscroll.min.js') }}"></script>
<script src="{{ url('adminlte/plugins/fastclick/fastclick.js') }}"></script>
<script src="{{ url('adminlte/js/app.min.js') }}"></script>

<script  type="text/javascript" src="{{ url('adminlte/plugins/daterange/moment.min.js') }}">
</script>

<script  type="text/javascript" src="{{ url('adminlte/plugins/daterange/daterangepicker.min.js') }}">
</script>

<script src="{{ asset('/vendors/ckeditor/ckeditor.js') }}"></script>

<script src="{{ url('adminlte/plugins/dropzone/dropzone.js') }}"></script>

<script>
    window._token = '{{ csrf_token() }}';
   // inicioBucle();
    
</script>
<script src="{{ asset('js/modules') }}/utils.js?v=2"></script>
<script src="{{ url('js/modules/Core.js') }}"></script>


<script src="{{ url('adminlte/plugins/select2/') }}/select21.full.min.js"></script> 
<script>
var dropdowMenu=0;
var cargaTicketModalDerecho=0;
//$(".dropdown-menu").hide();
// Get the modal
var modal = document.getElementById("myModal");
var modalPrint = document.getElementById("myModalPrint");


$(".numero").on({
"focus": function (event) {
    $(event.target).select();
},
"keyup": function (event) {

    $(event.target).val(function (index, value ) {
         var vari=value.replace(/\D/g, "")
                    return vari;
    });
}
});
$(".dt-dropdown").on("click",function(){
    var dropdownmenuClass=dropdowMenu!=0?$(".dt-dropdown-menu").hide():$(".dt-dropdown-menu").show();
    dropdowMenu=dropdowMenu!=0?0:1;
    if(dropdowMenu==1)
    $("#mayuda").addClass("hidden");

   // $(".control-sidebar").removeClass("control-sidebar-open");
});
/*
$("#mesaAyuda").on("click",function(){
    if(cargaTicketModalDerecho==0){
        cargaTicketModalDerecho=1;
        $("#mayuda").removeClass("hidden");

    }else{
        cargaTicketModalDerecho=0;
        $("#mayuda").addClass("hidden");
    }
    $(".dropdown-menu").hide();
    dropdowMenu=0;
});

$("#mayuda").addClass("hidden");*/
</script>
@yield('javascript')

<script src="{{ url('adminlte/plugins/select2/') }}/select21.full.min.js"></script> 