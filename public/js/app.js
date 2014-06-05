var app = angular.module('yttApp', ['ngRoute']);

app.config(function($routeProvider) {
	$routeProvider.
		when('/', {
			templateUrl: 'js/partials/mainlist.html',
			controller: 'MainListController'
		}).
		when('/settings', {
			templateUrl: 'js/partials/settings.html',
			controller: 'SettingsController'
		}).
		otherwise({
			redirectTo: '/'
		});
});