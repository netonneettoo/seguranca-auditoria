<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Laravel</title>

	<!-- Fonts -->
	<link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>

	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="/plugins/bootstrap/css/bootstrap.min.css">
	<!-- Optional theme -->
	<link rel="stylesheet" href="/plugins/bootstrap/css/bootstrap-theme.min.css">

	<link href="/plugins/pnotify/pnotify.custom.min.css" media="all" rel="stylesheet" type="text/css" />

	<link href="/css/app.css" rel="stylesheet">

</head>
<body>
	<nav class="navbar navbar-default">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle Navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="#">Laravel</a>
			</div>

			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					<li><a href="/">Home</a></li>
					<li><a href="/packages">Packages</a></li>
					<li><a href="/rules">Rules</a></li>
				</ul>

				<ul class="nav navbar-nav navbar-right">
					@if (Auth::guest())
						<li><a href="/auth/login">Login</a></li>
						<li><a href="/auth/register">Register</a></li>
					@else
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ Auth::user()->name }} <span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
								<li><a href="/auth/logout">Logout</a></li>
							</ul>
						</li>
					@endif
				</ul>
			</div>
		</div>
	</nav>

	@yield('content')

	<!-- Scripts -->
	<script src="/plugins/jquery/jquery.min.js"></script>
	<!-- Latest compiled and minified JavaScript -->
	<script src="/plugins/bootstrap/js/bootstrap.min.js"></script>

	<script src="http://igorescobar.github.io/jQuery-Mask-Plugin/js/jquery.mask.min.js"></script>

	<script src="/plugins/jquery-validate/jquery.validate.min.js"></script>
	<script src="/plugins/jquery-validate/localization/messages_pt_BR.min.js"></script>
	<script src="/plugins/jquery-validate/additional-methods.min.js"></script>

	<script type="text/javascript" src="/plugins/pnotify/pnotify.custom.min.js"></script>

	<script>
		$(document).ready(function() {
			$('[data-toggle="tooltip"]').tooltip();

            /*new PNotify({
			 title: 'Successfully deleted!',
			 text: '',
			 type: 'success',
			 opacity: .8,
			 styling: "bootstrap3",
			 icon: true,
			 animation: "slide",
			 shadow: true,
			 delay: 8000
			 });*/

            $.validator.addMethod(
                    "regex",
                    function(value, element, regexp) {
                        var re = new RegExp(regexp);
                        return this.optional(element) || re.test(value);
                    },
                    "Please check your input."
            );
		});
	</script>

	@yield('scripts')

</body>
</html>
