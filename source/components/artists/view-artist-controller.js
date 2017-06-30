export default function ($http, $routeParams, appConfig) {
    let varc = this;

    $http.get(appConfig.apiUrl+'/artist/'+$routeParams.id).
    then(function(response) {
        varc.artist = response.data[0];
    });



    $http.get(appConfig.apiUrl+'/album?artist_id='+$routeParams.id).
    then(function(response) {
        varc.albums = response.data;
    });
}