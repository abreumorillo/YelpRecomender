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

        activate();

        /*
         * This functions is executed when this controller gets intatantiated
         */
        function activate() {
            console.log('activated', $stateParams);
            var restaurant = $stateParams.restaurant;
            var location = $stateParams.city + ', '+ $stateParams.state;
            YelpService.getRestaurants(restaurant, location);
        }
    }
})();
