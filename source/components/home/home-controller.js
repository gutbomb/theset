export default function ($http) {
    'ngInject';
    var hc = this;
    $http.get('/api/years.php?year_status=previous').
    then(function(response) {
        hc.previousYear = response.data[0];
        $http.get('/api/album.php?year='+hc.previousYear.year).
        then(function(response) {
            hc.previousAlbums = response.data;
        });
    });

    $http.get('/api/years.php?year_status=active').
    then(function(response) {
        hc.currentYear = response.data[0];
        $http.get('/api/album.php?year='+hc.currentYear.year).
        then(function(response) {
            hc.currentAlbums = response.data;
        });
    });
}