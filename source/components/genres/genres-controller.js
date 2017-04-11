export default function ($http) {
    'ngInject';
    var gc = this;
    $http.get('/api/album.php').
    then(function(response) {
        gc.albums = response.data;
        console.log(gc.albums);
    });
}