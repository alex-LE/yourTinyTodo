<!DOCTYPE html>
<html lang="en" data-ng-app="yttApp">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>YourTinyTodo 2</title>

	<!-- Bootstrap -->
	{{ HTML::style('css/bootstrap.min.css') }}

	<!-- custom css -->
	{{ HTML::style('css/bootstrap-checkbox.css') }}
	{{ HTML::style('css/jquery-ui-1.10.4.custom.min.css') }}
	{{ HTML::style('css/jquery.treegrid.css') }}
	{{ HTML::style('css/styles.css') }}

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body>

<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="#">YourTinyTodo 2</a>
		</div>
		<div class="collapse navbar-collapse">
			<ul class="nav navbar-nav navbar-right nav-pills">
				<li><a href="#/settings">Settings</a></li>
				<li><a data-toggle="modal" data-target="#loginModal">Login</a></li>
			</ul>
		</div><!--/.nav-collapse -->
	</div>
</div>

<div animated-view></div>

<!-- Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="loginModalLabel">Log in</h4>
			</div>
			<div class="modal-body">
				...
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-primary">Log in</button>
			</div>
		</div>
	</div>
</div>


<div id="footer">
	<div class="container">
		<p class="text-muted">Powered by yourTinyTodo 2</p>
	</div>
</div>


{{ HTML::script('js/angular.js') }}
{{ HTML::script('js/angular-route.min.js') }}

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->


{{ HTML::script('js/jquery.min.js') }}
{{ HTML::script('js/jquery-ui-1.10.4.custom.min.js') }}
<!-- Include all compiled plugins (below), or include individual files as needed -->
{{ HTML::script('js/bootstrap.min.js') }}
{{ HTML::script('js/bootstrap-checkbox.js') }}
{{ HTML::script('js/jquery.treegrid.js') }}
{{ HTML::script('js/jquery.treegrid.bootstrap3.js') }}
{{ HTML::script('js/jquery-sortable.js') }}

{{ HTML::script('js/app.js') }}
{{ HTML::script('js/controllers/controllers.js') }}
{{ HTML::script('js/directives/animatedView.js') }}
{{ HTML::script('js/directives/helpers.js') }}

</body>
</html>