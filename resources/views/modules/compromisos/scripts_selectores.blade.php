<script src="{{ url('adminlte/plugins/datepicker/') }}/bootstrap-datepicker.js"></script>

<script src="{{ asset('adminlte3/plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('adminlte3/plugins/fullcalendar/main.js') }}"></script>


<script src="{{ url('js/modules/compromisos/global.js?v=33') }}"></script>
<script src="{{ url('js/modules/compromisos/compromisos.js?v=27') }}"></script>

{{-- <script src="{{ url('js/modules/compromisos/calendario/calendar.js?v=4') }}"></script> --}}
<script src="{{ url('js/modules/compromisos/responsables.js?v=4') }}"></script>
<script src="{{ url('js/modules/compromisos/datatableCompromisos.js?v=25') }}"></script>
<script src="{{ url('js/modules/compromisos/busquedas.js?v=5') }}"></script>
<script>
    $(function() {
        let dropdownParent = retornaModalSelect2('modal-default');

        $("#responsable_id").select2({
            dropdownParent,
            placeholder: "SELECCIONE UNA OPCIÓN",
            ajax: {
                url: "{{ route('buscarResponsable') }}",
                type: "post",
                delay: 250,
                dataType: 'json',
                data: function(params) {
                    return {
                        query: params.term, // search term
                        "_token": "{{ csrf_token() }}",
                    };
                },
                processResults: function(response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        });
        $("#institucion_id").select2({
            dropdownParent,
            placeholder: "SELECCIONE UNA OPCION",
            ajax: {
                url: "{{ route('buscarInstitucion') }}",
                type: "post",
                delay: 250,
                dataType: 'json',
                data: function(params) {
                    return {
                        query: params.term, // search term
                        "_token": "{{ csrf_token() }}",
                    };
                },
                processResults: function(response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        });
        $("#instituciones_corresponsables").select2({
            dropdownParent,
            ajax: {
                url: "{{ route('buscarInstitucionCo') }}",
                type: "post",
                delay: 250,
                dataType: 'json',
                data: function(params) {
                    return {
                        query: params.term, // search term
                        "_token": "{{ csrf_token() }}",
                    };
                },
                processResults: function(response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        });

        //initCalendario();
        //initCalendarioFinalizacion();
        //$("#siguiente").trigger("click");
        // $("#siguiente_finalizacion").trigger("click");

        //$("#siguiente_finalizacion").trigger("click");
    });
</script>
