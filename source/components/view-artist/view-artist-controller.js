export default function ($http, $routeParams) {
    'ngInject';
    var varc = this;

    $http.get('/api/artist.php?artist_id='+$routeParams.id).
    then(function(response) {
        varc.artist = response.data;
    });

	$http.get('/api/album.php?artist_id='+$routeParams.id).
	then(function(response) {
		varc.albums = response.data;
	});
}