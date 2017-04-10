export default function ($http, $routeParams) {
    'ngInject';
    var vac = this;
    vac.editMode = 0;
    $http.get('/api/years.php?year_status=active').
    then(function(response) {
        vac.currentYear = response.data[0];
        $http.get('/api/album.php?album_id='+$routeParams.id).
        then(function(response) {
            vac.album = response.data[0];
            //console.log(vac.currentYear);
            //console.log(vac.album.year);
            if (vac.album.year === vac.currentYear.year) {
                vac.editMode = 1;
            }
        });
    });
    $http.get('/api/track.php?album_id='+$routeParams.id).
    then(function(response) {
        vac.tracks = response.data;
    });
    $http.get('/api/comments.php?album_id='+$routeParams.id).
    then(function(response) {
        vac.comments = response.data;
    });
}