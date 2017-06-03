export default function ($http, appConfig) {
    let gc = this;
    $http.get(appConfig.apiUrl+'/album').
    then(function(response) {
        gc.albums = response.data;
    });
}