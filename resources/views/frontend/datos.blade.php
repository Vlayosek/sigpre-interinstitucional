@extends('frontend.layouts.appc')
@section('javascript')
<script type="text/javascript">
function soloNumeros(e){
	var key = window.Event ? e.which : e.keyCode
	return (key >= 48 && key <= 57)
}
		@if(session('message'))
			alert('{{session("message")}}');
		@else
			      window.location = '{{route("frontend.home")}}';

		@endif
	
		@if ($errors->any())
		     		@foreach ($errors->all() as $error)
						alert('{{ $error }}');
					@endforeach
				
		@endif

    </script>
@endsection
