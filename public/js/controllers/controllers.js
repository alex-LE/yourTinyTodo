app.controller('MainListController', function ($scope) {
	$scope.lists = [];
	$scope.tasks = [];

	init();

	function init() {
		$scope.lists = [
			{id: 1, name: "List1 1", private: false, tasks: [
				{id: 1, title: "Task1 1", due: "10.1", prio: -1, desc: "lorem ipsum1"},
				{id: 2, title: "Task1 2", due: "10.2", prio: 0, desc: "lorem ipsum2"},
				{id: 3, title: "Task1 3", due: "10.3", prio: 1, desc: "lorem ipsum3"},
				{id: 4, title: "Task1 4", due: "10.4", prio: 2, desc: ""}
			]},
			{id: 2, name: "List2", private: true, tasks: [
				{id: 1, title: "Task2 1", due: "10.1", prio: -1, desc: "lorem ipsum1"},
				{id: 3, title: "Task2 3", due: "10.3", prio: 1, desc: "lorem ipsum3"},
				{id: 4, title: "Task2 4", due: "10.4", prio: 2, desc: ""}
			]},
			{id: 3, name: "List3", private: false, tasks: [
				{id: 1, title: "Task3 1", due: "10.1", prio: -1, desc: "lorem ipsum1"},
				{id: 4, title: "Task3 4", due: "10.4", prio: 2, desc: ""}
			]},
			{id: 4, name: "List4", private: false, tasks: []}
		];
	}

	$scope.addSimpleTask = function(list) {
		console.log($scope.activeListIndex, $scope.newSimpleTask);
		alert('foo');
		return;
		console.log(listid, $scope.newSimpleTask);
		if($scope.newSimpleTask.title.length > 0) {
			$scope.lists[listid].tasks.push(
				{id: 5, title: $scope.newSimpleTask.title, due:"", prio:0, desc:""}
			);
		}
	};

	angular.element(document).ready(function () {
		$('input[type="checkbox"]').checkbox({
			checkedClass: 'glyphicon glyphicon-check',
			uncheckedClass: 'glyphicon glyphicon-unchecked'
		});

		$('.tree').treegrid();
		//$(".tasklist ul").sortable({});
		$('.dropdown-toggle').dropdown();

		$('#listtabs li span.caret').click(function(event) {
			console.log($(this));
			event.stopImmediatePropagation();
			$(this).parent().parent().find('.dropdown-menu').dropdown('toggle');
		});
	});
});


app.controller('SettingsController', function ($scope) {

});