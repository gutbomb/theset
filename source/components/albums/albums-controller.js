export default function ($http) {
    'ngInject';
    var ac = this;
    $http.get('/api/album.php').
    then(function(response) {
        ac.albums = response.data;
    });
}