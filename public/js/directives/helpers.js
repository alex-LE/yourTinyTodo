app.directive('ngEnter', function () {
	return function (scope, element, attrs) {
		element.bind("keydown keypress", function (event) {
			if(event.which === 13) {
				scope.$apply(function (){
					scope.$eval(attrs.ngEnter);
				});

				event.preventDefault();
			}
		});
	};
}).filter('formatPrio', function () {
	return function (n) {
		var num = parseInt(n, 10);
		if(num > 0) {
			return '+'+num;
		} else {
			return num;
		}
	};
});
