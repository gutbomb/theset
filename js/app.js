var app = angular.module("theSetApp", ["ngRoute"]);

app.directive("navMenu", function() {
	return {
		templateUrl : 'templates/nav-menu.html'
	};
});

app.directive("jumbotron", function() {
	return {
		templateUrl : 'templates/jumbotron.html'
	};
});

app.directive("pageFooter", function() {
	return {
		templateUrl : 'templates/page-footer.html'
	};
});


// app.directive('activeLink', ['$location', function ($location) {
//     return {
//         restrict: 'A', //use as attribute
//         replace: false,
//         link: function (scope, elem) {
//             //after the route has changed
//             scope.$on("$routeChangeSuccess", function () {
//                 var hrefs = ['/#' + $location.path(),
//                     '#' + $location.path(), //html5: false
//                     $location.path()]; //html5: true
//                 angular.forEach(elem.find('a'), function (a) {
//                     a = angular.element(a);
//                     if (-1 !== hrefs.indexOf(a.attr('href'))) {
//                         a.parent().addClass('active');
//                     } else {
//                         a.parent().removeClass('active');
//                     };
//                 });
//             });
//         }
//     }
// }]);

app.config(['$locationProvider', function($locationProvider) {
  $locationProvider.hashPrefix('');
}]);

app.config(function ($routeProvider) {
	$routeProvider
		.when("/home", {
			templateUrl: "templates/home.html",
			controller: "homeController",
			controllerAs: "hc",
			activeTab: "home"
		})
		.when("/albums", {
			templateUrl: "templates/albums.html",
			controller: "albumsController",
			controllerAs: "ac",
			activeTab: "albums"
		})
		.when("/view-album/:id", {
			templateUrl: "templates/view-album.html",
			controller: "viewAlbumController",
			controllerAs: "vac",
			activeTab: "albums"
		})
		.when("/artists", {
			templateUrl: "templates/artists.html",
			controller: "artistsController",
			controllerAs: "arc",
			activeTab: "artists"
		})
		.when("/view-artists/:id", {
			templateUrl: "templates/view-artist.html",
			controller: "viewArtistController",
			controllerAs: "varc",
			activeTab: "artists"
		})
		.when("/genres", {
			templateUrl: "templates/genres.html",
			controller: "genresController",
			controllerAs: "gc",
			activeTab: "genres"
		})
		.when("/status", {
			templateUrl: "templates/status.html",
			controller: "statusController",
			controllerAs: "sc",
			activeTab: "status"
		})
		.when("/account", {
			templateUrl: "templates/account.html",
			controller: "accountController",
			controllerAs: "acc",
			activeTab: "account"
		})
		.when("/login", {
			templateUrl: "templates/login.html",
			controller: "loginController",
			controllerAs: "lc",
			activeTab: "login"
		})
		.otherwise({
			redirectTo: "/home"
		})
});

app.controller('navController', navController);

function navController($scope, $route) {
	var nc = this;
	$scope.$route = $route;
}

app.controller('homeController', homeController);

function homeController($http) {
	var hc = this;
	$http.get("/api/years.php?year_status=previous").
		then(function(response) {
			hc.previousYear = response.data[0];
			$http.get("/api/album.php?year="+hc.previousYear.year).
			 	then(function(response) {
			 		hc.previousAlbums = response.data;
			 	});
		});
	
	$http.get("/api/years.php?year_status=active").
		then(function(response) {
			hc.currentYear = response.data[0];
				$http.get("/api/album.php?year="+hc.currentYear.year).
					then(function(response) {
						hc.currentAlbums = response.data;
					});
		});
}

app.controller('albumsController', albumsController);

function albumsController($http) {
	var ac = this;
	$http.get("/api/album.php").
	 	then(function(response) {
	 		ac.albums = response.data;
	 	});

}

app.controller('artistsController', artistsController);

function artistsController($http) {
	var arc = this;
	$http.get("/api/artist.php").
	 	then(function(response) {
	 		arc.artists = response.data;
	 	});
}

app.controller('viewAlbumController', viewAlbumController);

function viewAlbumController($http,$routeParams) {
	var vac = this;
	vac.editMode = 0;
	$http.get("/api/years.php?year_status=active").
		then(function(response) {
			vac.currentYear = response.data[0];
			$http.get("/api/album.php?album_id="+$routeParams.id).
		 	then(function(response) {
		 		vac.album = response.data[0];
		 		//console.log(vac.currentYear);
		 		//console.log(vac.album.year);
		 		if (vac.album.year == vac.currentYear.year) {
					vac.editMode = 1;
					console.log('editMode');
				}
		 	});
		});
	$http.get("/api/track.php?album_id="+$routeParams.id).
	 	then(function(response) {
	 		vac.tracks = response.data;
	 	});
	$http.get("/api/comments.php?album_id="+$routeParams.id).
	 	then(function(response) {
	 		vac.comments = response.data;
	 	});
}

app.controller('viewArtistController', viewArtistController);

function viewArtistController($http) {
	var varc = this;

}

app.controller('genresController', genresController);

function genresController($http) {
	var gc = this;

}

app.controller('statusController', statusController);

function statusController($http) {
	var sc = this;

}

app.controller('accountController', accountController);

function accountController($http) {
	var acc = this;

}

app.controller('loginController', loginController);

function loginController($http) {
	var lc = this;

}