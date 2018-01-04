<!DOCTYPE html>
<html>
<head>
    <meta name="csrf-token" content="{!! Session::token() !!}" />
	<meta charset="utf-8" />

    @yield('title')

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <meta content="" name="description" />
    <meta content="" name="author" />


    <link rel="stylesheet" type="text/css" href="{{ URL::asset( 'assets/global/plugins/font-awesome/css/font-awesome.min.css' ) }}" />
    <link rel="stylesheet" type="text/css" href="{{ URL::asset( 'assets/global/plugins/bootstrap/css/bootstrap.min.css' ) }}" />
    <link rel="stylesheet" type="text/css" href="{{ URL::asset( 'assets/global/css/components.min.css' ) }}" />
    <!-- <link rel="stylesheet" type="text/css" href="{{ URL::asset( 'assets/global/css/plugins.min.css' ) }}" /> -->
    <link rel="stylesheet" type="text/css" href="{{ URL::asset( 'assets/global/plugins/bootstrap-toastr/toastr.min.css') }}"/>

	@yield('css')

</head>

<body class="page-header-fixed page-sidebar-closed page-content-white page-sidebar-closed">

	<div class="clearfix"> </div>

	<div class="page-container">

		<div class="page-content-wrapper">
			<div class="page-content">
                
				@yield('content')

			</div>
		</div>
	</div>

	<script type="text/javascript" src="{{ URL::asset( 'assets/global/plugins/jquery.min.js' ) }}" ></script>
    <script type="text/javascript" src="{{ URL::asset( 'assets/global/plugins/bootstrap/js/bootstrap.min.js' ) }}" ></script>
    
    <script type="text/javascript" src="{{ URL::asset( 'assets/global/scripts/app.min.js' ) }}" ></script>
    <script type="text/javascript" src="{{ URL::asset( 'assets/global/plugins/jquery-validation/js/jquery.validate.min.js' ) }}" ></script>
    <script type="text/javascript" src="{{ URL::asset( 'assets/global/plugins/jquery-validation/js/additional-methods.min.js' ) }}" ></script>
    <!-- toastr plugins -->
    <script type="text/javascript" src="{{ URL::asset( 'assets/global/plugins/bootstrap-toastr/toastr.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset( 'assets/pages/scripts/ui-toastr.js') }}"></script>
    <!-- end toastr plugins -->
    <script type="text/javascript" src="{{ URL::asset( 'js/system/system.js' ) }}" ></script>

	@yield('js')

</body>
</html>