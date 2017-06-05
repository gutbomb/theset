export default function ($http, $routeParams) {
    let varc = this;

    $http.get('/api/artist/'+$routeParams.id).
    then(function(response) {
        varc.artist = response.data[0];
    });



    $http.get('/api/album?artist_id='+$routeParams.id).
    then(function(response) {
        varc.albums = response.data;
    });
}