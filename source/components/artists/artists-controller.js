export default function ($http, appConfig) {
    let arc = this;
    $http.get(appConfig.apiUrl+'/artist').
    then(function(response) {
        arc.artists = response.data;
    });
}