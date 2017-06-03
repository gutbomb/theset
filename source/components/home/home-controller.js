export default function ($http, appConfig) {
    let hc = this;
    $http.get(appConfig.apiUrl+'/year/previous').
    then(function(response) {
        hc.previousYear = response.data[0];
        $http.get(appConfig.apiUrl+'/album?year='+hc.previousYear.year).
        then(function(response) {
            hc.previousAlbums = response.data;
        });
    });

    $http.get(appConfig.apiUrl+'/year/active').
    then(function(response) {
        hc.currentYear = response.data[0];
        $http.get(appConfig.apiUrl+'/album?year='+hc.currentYear.year).
        then(function(response) {
            hc.currentAlbums = response.data;
        });
    });
}