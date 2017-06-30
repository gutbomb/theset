export default function ($http, $routeParams, $rootScope, $location, appConfig, loginService) {
    let vac = this;

    vac.editMode = false;
    vac.hideRatings = false;
    vac.noComments = true;

    function getRatings () {
        $http.get(appConfig.apiUrl+'/rating?album_id='+$routeParams.id+'&rating_source='+$rootScope.userId).
        then(function(response) {
            vac.ratings = response.data;
        });
    }

    function getAlbum () {
        $http.get(appConfig.apiUrl+'/year').
        then(function(response) {
            vac.currentYear = response.data.activeYear;
            $http.get(appConfig.apiUrl+'/album/'+$routeParams.id).
            then(function(response) {
                vac.album = response.data[0];
                if (vac.album.year === vac.currentYear) {
                    if ($rootScope.isLoggedIn===true) {
                        vac.editMode = true;
                        getRatings();
                        vac.reviewCount=0;
                        if (typeof(vac.album.reviews)!=='undefined') {
                            for (let i = 0; i < vac.album.reviews.length; i++) {
                                if (vac.album.reviews[i].review_source_id===$rootScope.userId) {
                                    vac.reviewCount++;
                                }
                            }
                        }
                    } else {
                        vac.hideRatings=true;
                    }
                }
            });
        });
    }

    function getTracks () {
        $http.get(appConfig.apiUrl+'/track?album_id='+$routeParams.id).
        then(function(response) {
            vac.tracks = response.data;
        });
    }

    function saveRating(trackId) {
        vac.newRatings[trackId].failed=false;
        vac.newRatings[trackId].saving=true;
        $http({
            method: 'POST',
            url: appConfig.apiUrl+'/rating',
            data: {track_id: trackId, track_rating: vac.newRatings[trackId].rating_score, track_album: $routeParams.id, action: 'save'},
            headers : {'Content-Type': 'application/json'}
        })
        .then(function() {
            vac.newRatings[trackId].saving=false;
            vac.newRatings[trackId].saved=true;
            getTracks();
            getRatings();
        },
        function() {
            vac.newRatings[trackId].saving=false;
            vac.newRatings[trackId].failed=true;
        });
    }

    function saveReview() {
        $http({
            method: 'POST',
            url: appConfig.apiUrl+'/review',
            data: {review_album: $routeParams.id, review_text: vac.newReview, action: 'save'},
            headers : {'Content-Type': 'application/json'}
        })
        .then(function() {
            getAlbum();
        },
        function() {
            $location.path('/login');
        });
    }

    function updateRating(ratingId, trackId) {
        vac.ratings[trackId].failed=false;
        vac.ratings[trackId].updating=true;
        $http({
            method: 'PUT',
            url: appConfig.apiUrl+'/rating/'+ratingId,
            data: {track_rating: vac.ratings[trackId].rating_score},
            headers : {'Content-Type': 'application/json'}
        })
        .then(function() {
            vac.ratings[trackId].updating=false;
            vac.ratings[trackId].updated=true;
            getRatings();
        },
        function() {
            vac.ratings[trackId].updating=false;
            vac.ratings[trackId].false=true;
        });
    }

    function updateReview(reviewIndex) {
        $http({
            method: 'PUT',
            url: appConfig.apiUrl+'/review/'+vac.album.reviews[reviewIndex].review_id,
            data: {review_text: vac.album.reviews[reviewIndex].review_text},
            headers : {'Content-Type': 'application/json'}
        })
        .then(function() {
            getAlbum();
        },
        function() {
            $location.path('/login');
        });
    }

    function getComments() {
        $http.get(appConfig.apiUrl+'/comment?album_id='+$routeParams.id).
        then(function(response) {
            vac.noComments=false;
            vac.comments = response.data;
        },
        function() {
            vac.noComments=true;
        });
    }

    getAlbum();
    getTracks();

    if (vac.editMode===false) {
        getComments();
    } else {
        getRatings();
    }

    function postComment() {
        $http({
            method: 'POST',
            url: appConfig.apiUrl+'/comment',
            data: {album_id: $routeParams.id, comment_text: vac.newComment},
            headers : {'Content-Type': 'application/json'}
        })
        .then(function() {
            getComments();
            vac.newComment='';
        },
        function(response) {
            if (response.status===401) {
                loginService.logoutUser();
            }
        });
    }



    vac.getAlbum = getAlbum;
    vac.saveRating = saveRating;
    vac.getTracks = getTracks;
    vac.getRatings = getRatings;
    vac.updateRating = updateRating;
    vac.saveReview = saveReview;
    vac.updateReview = updateReview;
    vac.postComment = postComment;
    vac.getComments = getComments;
}