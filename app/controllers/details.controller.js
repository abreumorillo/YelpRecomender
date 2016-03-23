/*
 * This angular controller handle the operation related to the detail views. It handles the add review operation
 */
(function () {
    'use strict';

    angular
        .module(appInfo.module)
        .controller('DetailsController', DetailsController);

    DetailsController.$inject = ['YelpService', '$stateParams',  '$state',  'toastr'];

    function DetailsController(YelpService, $stateParams, $state, toastr) {
        /* jshint validthis:true */
        var vm = this;
        //Rating
        vm.max = 10;
        vm.isReadonly = true;
        vm.movieInfo = {};
        vm.getGenre = getGenre;
        vm.getGenreLabel = getGenreLabel;


        activate();

        /*
         * This functions is executed when this controller gets intatantiated 
         */
        function activate() {            
            // getMovieDetails();
            // console.log('activated', $stateParams);
            // console.log(YelpService);
            var restaurant = $stateParams.restaurant;
            var location = $stateParams.city + ', '+ $stateParams.state;
            YelpService.getRestaurants(restaurant, location);
        }

        /**
         * Get details for a given movies from the omdb api
         * @returns {mix}
         */
        function getMovieDetails() {
            OmdbService.getData($stateParams.searchTerm, $stateParams.year).then(function (successResponse) {
                if (successResponse.status === 200) { //OK
                    var data = successResponse.data;
                    toastr.info("Info about the movies successfully retrieved", "", { positionClass: "toast-bottom-right" });
                    vm.movieInfo = data;
                    vm.movieInfo.rating = calcRating(data);
                    vm.movieInfo.Poster = getMoviePoster(vm.movieInfo.Poster);
                }
            }, function (errorResponse) { 
                //Handle error
                toastr.error("An error has occurred.", "Code: "+errorResponse.status);
            });
        }

        function calcRating(movieInfo) {
            return Math.round(movieInfo.imdbRating);
        }

        /**
         * Return an array of string
         * @param {string} string with comma separated values
         * @returns {mix}
         */
        function getGenre(genre) {
            if (!genre) return;
            return genre.split(', ');
        }
        
        function getMoviePoster(url) {
            if(url !== "N/A") {
                return url;
            }
            return "img/no_image_available.jpeg";
        }
        

        /**
         * Get CSS label class for movie/series genre   
         * @param {string} genre
         * @returns {CSS class}
         */
        function getGenreLabel(genre) {

            switch (genre) {
                case 'Adventure':
                case 'Music':
                case 'Animation':
                case 'Family':
                    return "label label-info";
                
                case 'Action':
                case 'Romance':
                    return "label label-primary";
                    
                case 'Sci-Fi':
                case 'Fantasy':
                case 'Drama':
                    return "label label-warning";
                    
                case 'Mystery':
                case 'Thriller':
                case 'Crime':
                case 'Horror':
                    return "label label-danger";
                    
                case 'Comedy':
                case 'Fantasy':
                case 'Musical':
                    return "label label-success";
                    
                default:
                    return "label label-default";
            }

        }

    }
})();
