
<!DOCTYPE html>
<html lang="es">

<head>
@include('frontend.partials.head')
</head>

<body>
	<!-- banner -->
	<div class="banner" id="home">
		<!-- agileinfo-dot -->
		<div class="agileinfo-dot">
          @include('frontend.partials.menu')
          @include('frontend.partials.slider')
		</div>
		<!-- //agileinfo-dot -->
	</div>
	<!-- //banner -->
	<!-- modal -->
    @include('frontend.partials.modal')
	<!-- //modal -->
	<!-- about -->
    @include('frontend.partials.direct')

	<!-- //about -->
	<!-- about -->
    @include('frontend.partials.feature')

	<!-- //about -->
	<!-- services 
    @include('frontend.partials.services')-->

	<!-- //services -->
	<!-- news -->
    @include('frontend.partials.galery')

	<!-- //news -->
	<!-- team 
    @include('frontend.partials.team')-->

	<!-- //team -->
	<!-- contact
    @include('frontend.partials.practice') -->

	<!-- //contact -->
	<!-- offer 
    @include('frontend.partials.ofter')-->

	<!-- //offer -->
	<!-- contact -->
    @include('frontend.partials.contact')

	<!-- //contact -->
	<!-- map 
	@include('frontend.partials.map')-->

	<!-- //map -->
	<!-- footer -->
	@include('frontend.partials.footer')

	<!-- //footer -->
	<!-- //footer -->
@include('frontend.partials.javascripts')

</body>

</html>
