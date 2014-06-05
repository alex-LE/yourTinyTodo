<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>YourTinyTodo 2 - Preview</title>

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
				<a class="navbar-brand" href="#">YourTinyTodo 2 - Preview</a>
			</div>
			<div class="collapse navbar-collapse">
				<ul class="nav navbar-nav navbar-right nav-pills">
					<li><a href="#settings">Settings</a></li>
					<li><a href="#login">Login</a></li>
				</ul>
			</div><!--/.nav-collapse -->
		</div>
	</div>

	<div class="container">

		<div class="row">
			<ul class="nav nav-tabs" id="listtabs">
				<li class="active"><a href="#list1" data-toggle="tab"><span class="glyphicon glyphicon-lock"></span>List 1</a></li>
				<li><a href="#list2" data-toggle="tab">List 2</a></li>
				<li><a href="#list3" data-toggle="tab">List 3</a></li>
				<button type="button" class="btn btn-default btn-sm navbar-btn pull-right" id="addlist">
					<span class="glyphicon glyphicon-plus"></span>
				</button>
			</ul>
		</div>

		<div class="row" id="toolbar">
			<div class="col-md-7 col-xs-9">
				<div class="input-group">
					<input type="text" placeholder="New task" class="form-control" id="task" name="task">
					<span class="input-group-btn">
						<button title="Add" id="newtask_submit" type="button" class="btn btn-default">
							<span class="glyphicon glyphicon-play"></span>
						</button>
						<a href="#" id="newtask_adv" class="btn btn-default" title=">">
							<span class="glyphicon glyphicon-pencil"></span>
						</a>
					</span>
				</div>
			</div>
			<div class="col-md-3 col-md-offset-2 col-xs-3">
				<div class="input-group">
					<span class="input-group-addon glyphicon glyphicon-search"></span>
					<input type="text" placeholder="Search" class="form-control" id="search" name="search">
				</div>
			</div>
		</div>

		<div class="row" id="taskcontainer">

			<div class="tab-content">
				<div class="tab-pane active" id="list1">
					<div class="row" id="taskhead">
						<ul class="nav navbar-nav navbar-left">
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">List Actions <b class="caret"></b></a>
								<ul class="dropdown-menu">
									<li><a href="#">Rename list</a></li>
									<li><a href="#">Delete list</a></li>
									<li><a href="#">Clear completed tasks</a></li>
									<li class="dropdown-submenu">
										<a href="#">Export</a>
										<ul class="dropdown-menu">
											<li><a href="#">CSV</a></li>
										</ul>
									</li>
									<li class="divider"></li>
									<li><a href="#">action</a></li>
									<li><a href="#">action</a></li>
								</ul>
							</li>
						</ul>
						<ul class="nav navbar-nav navbar-left">
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">Tasks (5) <b class="caret"></b></a>
								<ul class="dropdown-menu">
									<li><a href="#">All tasks</a></li>
									<li><a href="#">Overdue</a></li>
									<li><a href="#">Today and tomorrow</a></li>
									<li><a href="#">Soon</a></li>
									<li class="divider"></li>
									<li><a href="#">Sort by date created</a></li>
									<li><a href="#">Sort by priority</a></li>
									<li><a href="#">Sort by due date</a></li>
									<li><a href="#">Sort by date modified</a></li>
									<li><a href="#">Sort by progress</a></li>
								</ul>
							</li>
						</ul>
						<ul class="nav navbar-nav navbar-right">
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">Tags <b class="caret"></b></a>
								<ul class="dropdown-menu">
									<li><a href="#"><span class="label label-default">tag 1</span></a></li>
									<li><a href="#"><span class="label label-default">tag 2</span></a></li>
									<li><a href="#"><span class="label label-default">tag 3</span></a></li>
									<li><a href="#"><span class="label label-default">tag 4</span></a></li>
								</ul>
							</li>
						</ul>
					</div>
					<div class="panel-group tasklist">
						<ul>
							<li class="taskitem">
								<div class="panel panel-default callout-primary">
									<div class="panel-heading">
										<h4 class="panel-title">
											<input type="checkbox" name="" value="" />
											<span class="label label-primary">-1</span>
											<span class="badge alert-success pull-right">24.7.</span>

											<div class="actions pull-right">
												<a title="" href="#" data-placement="top" class="btn btn-xs btn-link edit has-tooltip" data-original-title="Edit todo">
													<i class="glyphicon glyphicon-pencil"></i>
												</a>
												<a title="" href="#" data-placement="top" class="btn btn-xs btn-link remove has-tooltip" data-original-title="Remove todo">
													<i class="glyphicon glyphicon-trash"></i>
												</a>
											</div>

											<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
												Task 1 - Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt.
												<span class="caret"></span>
											</a>

											<div class="progress progress-striped">
												<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%">
													<span class="sr-only">40% Complete (success)</span>
												</div>
											</div>
										</h4>
									</div>
									<div id="collapseOne" class="panel-collapse collapse in">
										<div class="panel-body">
											<blockquote>
												Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.
											</blockquote>
											<ul class="taskcomments">
												<li>
													<div class="avatar pull-left">
														<span class="glyphicon glyphicon-user img-circle"></span>
													</div>
													<div class="comment">
														<div class="comment-head">
															<span class="comment-user">User</span>
													<span class="comment-details pull-right">
														2014-02-15 14:33
														<a href="#"><span class="glyphicon glyphicon-trash"></span></a>
													</span>
														</div>
														<div class="comment-content">
															Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.
														</div>
													</div>
												</li>
												<li>
													<div class="avatar pull-left">
														<span class="glyphicon glyphicon-user img-circle"></span>
													</div>
													<div class="comment">
														<div class="comment-head">
															<span class="comment-user">User</span>
													<span class="comment-details pull-right">
														2014-02-15 14:33
														<a href="#"><span class="glyphicon glyphicon-trash"></span></a>
													</span>
														</div>
														<div class="comment-content">
															Lorem ipsum dolor sit amet, consectetuer adipiscing elit.
														</div>
													</div>
												</li>
											</ul>
											<button type="button" class="btn btn-default btn-xs comment-add">Add comment</button>
										</div>
									</div>
								</div>
								<ul></ul>
							</li>
							<li class="taskitem">
								<div class="panel panel-default callout-default">
									<div class="panel-heading">
										<h4 class="panel-title">
											<input type="checkbox" name="" value="" />
											<span class="label label-default">&plusmn;0</span>
											<span class="badge alert-success pull-right">24.2.</span>

											<div class="actions pull-right">
												<a title="" href="#" data-placement="top" class="btn btn-xs btn-link edit has-tooltip" data-original-title="Edit todo">
													<i class="glyphicon glyphicon-pencil"></i>
												</a>
												<a title="" href="#" data-placement="top" class="btn btn-xs btn-link remove has-tooltip" data-original-title="Remove todo">
													<i class="glyphicon glyphicon-trash"></i>
												</a>
											</div>

											<a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
												Task 2
												<span class="caret"></span>
											</a>

											<div class="progress progress-striped">
												<div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: 70%">
													<span class="sr-only">70% Complete</span>
												</div>
											</div>
										</h4>
									</div>
									<div id="collapseTwo" class="panel-collapse collapse">
										<div class="panel-body">
											<blockquote>
												Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.
											</blockquote>
										</div>
									</div>
								</div>
								<ul></ul>
							</li>
							<li class="taskitem">
								<div class="panel panel-default callout-warning">
									<div class="panel-heading">
										<h4 class="panel-title">
											<input type="checkbox" name="" value="" />
											<span class="label label-warning">+1</span>
											<span class="badge alert-danger pull-right">today</span>

											<div class="actions pull-right">
												<a title="" href="#" data-placement="top" class="btn btn-xs btn-link edit has-tooltip" data-original-title="Edit todo">
													<i class="glyphicon glyphicon-pencil"></i>
												</a>
												<a title="" href="#" data-placement="top" class="btn btn-xs btn-link remove has-tooltip" data-original-title="Remove todo">
													<i class="glyphicon glyphicon-trash"></i>
												</a>
											</div>

											<a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
												Task 3
												<span class="caret"></span>
											</a>

											<div class="progress progress-striped">
												<div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100" style="width: 90%">
													<span class="sr-only">90% Complete</span>
												</div>
											</div>
										</h4>
									</div>
									<div id="collapseThree" class="panel-collapse collapse">
										<div class="panel-body">
											<blockquote>
												Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.
											</blockquote>
										</div>
									</div>
								</div>
								<ul>
									<li class="taskitem">
										<div class="panel panel-default callout-danger">
											<div class="panel-heading">
												<h4 class="panel-title">
													<input type="checkbox" name="" value="" />
													<span class="label label-danger">+2</span>
													<span class="badge alert-warning pull-right">24.5.</span>

													<div class="actions pull-right">
														<a title="" href="#" data-placement="top" class="btn btn-xs btn-link edit has-tooltip" data-original-title="Edit todo">
															<i class="glyphicon glyphicon-pencil"></i>
														</a>
														<a title="" href="#" data-placement="top" class="btn btn-xs btn-link remove has-tooltip" data-original-title="Remove todo">
															<i class="glyphicon glyphicon-trash"></i>
														</a>
													</div>

													<a>
														Subtask 3/1
													</a>
												</h4>
											</div>
										</div>
									</li>
								</ul>
							</li>
							<li class="taskitem">
								<div class="panel panel-default callout-danger">
									<div class="panel-heading">
										<h4 class="panel-title">
											<input type="checkbox" name="" value="" />
											<span class="label label-danger">+2</span>
											<span class="badge alert-warning pull-right">tomorrow</span>

											<div class="actions pull-right">
												<a title="" href="#" data-placement="top" class="btn btn-xs btn-link edit has-tooltip" data-original-title="Edit todo">
													<i class="glyphicon glyphicon-pencil"></i>
												</a>
												<a title="" href="#" data-placement="top" class="btn btn-xs btn-link remove has-tooltip" data-original-title="Remove todo">
													<i class="glyphicon glyphicon-trash"></i>
												</a>
											</div>

											<a data-toggle="collapse" data-parent="#accordion" href="#collapseFour">
												Task 4
												<span class="caret"></span>
											</a>
										</h4>
									</div>
									<div id="collapseFour" class="panel-collapse collapse">
										<div class="panel-body">
											<blockquote>
												Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.
											</blockquote>
										</div>
									</div>
								</div>
								<ul></ul>
							</li>
							<li class="taskitem">
								<div class="panel panel-default callout-danger">
									<div class="panel-heading">
										<h4 class="panel-title">
											<input type="checkbox" name="" value="" />
											<span class="label label-danger">+2</span>
											<span class="badge alert-warning pull-right">tomorrow</span>

											<div class="actions pull-right">
												<a title="" href="#" data-placement="top" class="btn btn-xs btn-link edit has-tooltip" data-original-title="Edit todo">
													<i class="glyphicon glyphicon-pencil"></i>
												</a>
												<a title="" href="#" data-placement="top" class="btn btn-xs btn-link remove has-tooltip" data-original-title="Remove todo">
													<i class="glyphicon glyphicon-trash"></i>
												</a>
											</div>

											<a>
												Task 5
											</a>
										</h4>
									</div>
								</div>
								<ul>
									<li class="taskitem">
										<div class="panel panel-default callout-danger">
											<div class="panel-heading">
												<h4 class="panel-title">
													<input type="checkbox" name="" value="" />
													<span class="label label-danger">+2</span>
													<span class="badge alert-warning pull-right">tomorrow</span>

													<div class="actions pull-right">
														<a title="" href="#" data-placement="top" class="btn btn-xs btn-link edit has-tooltip" data-original-title="Edit todo">
															<i class="glyphicon glyphicon-pencil"></i>
														</a>
														<a title="" href="#" data-placement="top" class="btn btn-xs btn-link remove has-tooltip" data-original-title="Remove todo">
															<i class="glyphicon glyphicon-trash"></i>
														</a>
													</div>

													<a>
														Subtask 5/1
													</a>
												</h4>
											</div>
										</div>
									</li>
									<li class="taskitem">
										<div class="panel panel-default callout-danger">
											<div class="panel-heading">
												<h4 class="panel-title">
													<input type="checkbox" name="" value="" />
													<span class="label label-danger">+2</span>
													<span class="badge alert-warning pull-right">tomorrow</span>

													<div class="actions pull-right">
														<a title="" href="#" data-placement="top" class="btn btn-xs btn-link edit has-tooltip" data-original-title="Edit todo">
															<i class="glyphicon glyphicon-pencil"></i>
														</a>
														<a title="" href="#" data-placement="top" class="btn btn-xs btn-link remove has-tooltip" data-original-title="Remove todo">
															<i class="glyphicon glyphicon-trash"></i>
														</a>
													</div>

													<a>
														Subtask 5/2
													</a>
												</h4>
											</div>
										</div>
									</li>
								</ul>
							</li>
						</ul>
					</div>
				</div>
				<div class="tab-pane" id="list2">
					<div class="row" id="taskhead">
						<ul class="nav navbar-nav navbar-left">
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">Tasks (0) <b class="caret"></b></a>
								<ul class="dropdown-menu">
									<li><a href="#">All tasks</a></li>
									<li><a href="#">Overdue</a></li>
									<li><a href="#">Today and tomorrow</a></li>
									<li><a href="#">Soon</a></li>
								</ul>
							</li>
						</ul>
						<ul class="nav navbar-nav navbar-right">
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">Tags <b class="caret"></b></a>
								<ul class="dropdown-menu">
									<li><a href="#">All tasks</a></li>
									<li><a href="#">Overdue</a></li>
									<li><a href="#">Today and tomorrow</a></li>
									<li><a href="#">Soon</a></li>
								</ul>
							</li>
						</ul>
					</div>
					<div class="panel-group tasklist">
						<p class="text-muted text-center">no tasks found</p>
					</div>
				</div>
				<div class="tab-pane" id="list3">
					<div class="row" id="taskhead">
						<ul class="nav navbar-nav navbar-left">
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">Tasks (0) <b class="caret"></b></a>
								<ul class="dropdown-menu">
									<li><a href="#">All tasks</a></li>
									<li><a href="#">Overdue</a></li>
									<li><a href="#">Today and tomorrow</a></li>
									<li><a href="#">Soon</a></li>
								</ul>
							</li>
						</ul>
						<ul class="nav navbar-nav navbar-right">
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">Tags <b class="caret"></b></a>
								<ul class="dropdown-menu">
									<li><a href="#">All tasks</a></li>
									<li><a href="#">Overdue</a></li>
									<li><a href="#">Today and tomorrow</a></li>
									<li><a href="#">Soon</a></li>
								</ul>
							</li>
						</ul>
					</div>
					<div class="panel-group tasklist">
						<p class="text-muted text-center">no tasks found</p>
					</div>
				</div>
			</div>
		</div>


	</div><!-- /.container -->

	<div id="footer">
		<div class="container">
			<p class="text-muted">Powered by yourTinyTodo 2</p>
		</div>
	</div>

	<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.0-beta.7/angular.min.js"></script>

	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	{{ HTML::script('js/jquery.min.js') }}
	{{ HTML::script('js/jquery-ui-1.10.4.custom.min.js') }}
	<!-- Include all compiled plugins (below), or include individual files as needed -->
	{{ HTML::script('js/bootstrap.min.js') }}
	{{ HTML::script('js/bootstrap-checkbox.js') }}
	{{ HTML::script('js/jquery.treegrid.js') }}
	{{ HTML::script('js/jquery.treegrid.bootstrap3.js') }}
	{{ HTML::script('js/jquery-sortable.js') }}

	<script type="text/javascript">
		$(".tasklist ul").sortable({});

		/*
		$(".tasklist ul").nestedSortable({
			stop: function( event, ui ) {
				if(ui.item.hasClass('subtask')) {
					ui.item.removeClass('subtask');
				}
				console.log(ui.originalPosition, ui.item.position(), ui.item);
			},
			handle: 'div',
			listType: 'ul',
			items: 'li',
			toleranceElement: '> div',
			maxLevels: 2,
			placeholder: 'sortable-placeholder',
			forcePlaceholderSize: true
		});
		*/

		$('.dropdown-toggle').dropdown();

		$('#listtabs li span.caret').click(function(event) {
			console.log($(this));
			event.stopImmediatePropagation();
			$(this).parent().parent().find('.dropdown-menu').dropdown('toggle');
		});

		$(document).ready(function() {
			$('input[type="checkbox"]').checkbox({
				checkedClass: 'glyphicon glyphicon-check',
				uncheckedClass: 'glyphicon glyphicon-unchecked'
			});

			$('.tree').treegrid();
		});
	</script>

</body>
</html>