(function() {
    'use strict';

    angular
        .module(appInfo.module)
        .controller('IndexController', IndexController);

    IndexController.$inject = ['CommonService', 'toastr', 'IndexService', 'focus', '$timeout', 'YelpService', '$uibModal'];

    /* @ngInject */
    function IndexController(CommonService, toastr, IndexService, focus, $timeout, YelpService, $uibModal) {
        var vm = this,
            geocoder,
            map = null,
            marker,
            mapOptions = { //map option for google map
                zoom: 18
            };

        vm.title = 'IndexController';
        vm.searchTerm = "";
        vm.search = search;
        //Pagination options
        vm.totalItems = 0;
        vm.currentPage = 1;
        vm.itemPerPage = 2;
        vm.isSearchResult = false;
        vm.restaurants = [];
        vm.isSearching = false;
        vm.isShowingDetails = false;
        vm.restaurantDetails = {};

        //FUNCTIONS
        vm.closeDetails = closeDetails;
        vm.onKeyPressed = onKeyPressed;
        vm.replaceWord = replaceWord;
        vm.getRestaurantDetails = getRestaurantDetails;
        vm.closeSearch = onCloseSearch;
        vm.getBoxClass = getBoxClass;
        vm.getBoxTitleClass = getBoxTitleClass;
        vm.showAdditionalInfo = showAdditionalInfo;

        activate();

        ////////////////

        function activate() {
            toastr.error('Yelp Restaurant Finder!');
        }

        /**
         * Execute the search
         * @return {array}
         */
        function search() {
            if (vm.searchTerm.length <= 0) {
                return;
            }
            vm.isSearching = true;
            IndexService.getRestaurants(vm.searchTerm).then(function(response) {
                // console.log(response);
                vm.isSearching = false;
                if (response.status === CommonService.statusCode.HTTP_NO_CONTENT) {
                    toastr.info('No restaurant found for the given criteria ' + vm.searchTerm);
                    vm.restaurants = [];
                }
                if (CommonService.isValidResponse(response)) {
                    vm.restaurants = [];
                    vm.restaurants = CommonService.getResponse(response);
                    vm.isSearchResult = true;
                } else {
                    toastr.warning("No restaurant found for the given criteria " + vm.searchTerm);
                }

            }, function(errorResponse) {
                toastr.warning("No restaurant found for the given criteria " + vm.searchTerm);
                vm.isSearching = false;
            });
        }

        /**
         * Close the detail view of a given restaurant
         * @return {mix}
         */
        function closeDetails() {
            vm.isShowingDetails = false;
        }

        /**
         * Execute when the close button is pressed
         * @return {[type]} [description]
         */
        function onCloseSearch() {
            vm.isSearchResult = false;
            vm.searchTerm = "";
        }

        /**
         * Executed on keypress event
         * @param  {$event} evt angular event
         * @return {mix}
         */
        function onKeyPressed(evt) {
            if (evt.keyCode === 32) { //space bar keycode = 32
                var searchTerms = vm.searchTerm.split(' ');
                var termToSpellCheck = searchTerms[searchTerms.length - 1]; //We get the last word
                IndexService.spellcheck(termToSpellCheck).then(function(response) {
                    if (CommonService.isValidResponse(response)) {
                        vm.terms = response.data;
                        //remove suggestion after 5 seconds
                        $timeout(function() {
                            vm.terms = [];
                        }, 3000);
                    }
                }, function(errorResponse) {
                    console.log(errorResponse);
                });
            }
        }

        /**
         * Replace spellchecked word
         * @param  {string} wordToReplace
         * @return {mix}
         */
        function replaceWord(wordToReplace) {
            var currentSearchString = vm.searchTerm.split(' ');
            currentSearchString[currentSearchString.length - 1] = wordToReplace;
            vm.searchTerm = currentSearchString.join(' ').concat(' ');
            focus('searchTerm');
            vm.terms = [];
        }

        /**
         * Get the class for the box
         * @param  {string} preditedClass
         * @return {string}
         */
        function getBoxClass(preditedClass) {
            if (preditedClass === 'Excellent') {
                return 'box-primary';
            } else if (preditedClass === 'Good') {
                return 'box-warning';
            } else {
                return 'box-danger';
            }
        }

        /**
         * Gets the class for the title icon
         * @param  {string} preditedClass
         * @return {string}
         */
        function getBoxTitleClass(preditedClass) {
            if (preditedClass === 'Excellent') {
                return 'fa-thumbs-o-up text-primary';
            } else if (preditedClass === 'Good') {
                return 'fa-thumbs-o-up text-warning';
            } else {
                return 'fa-thumbs-o-down text-danger';
            }
        }

        function getRestaurantDetails(restaurant) {
            vm.isShowingDetails = true;
            // console.log(restaurant);
            var location = restaurant.businessCity + ', ' + restaurant.businessState;
            YelpService.getRestaurants(restaurant.businessName, location).then(function(successResponse) {
                console.log('response', successResponse);
                if (successResponse.status === "success") {
                    var business = successResponse.data.businesses[0];
                    var categories = [];
                    angular.forEach(business.categories, function(item) {
                        categories.push(item[0]);
                    });
                    // vm.restaurantDetails = business;
                    vm.restaurantDetails.phone = business.display_phone;
                    vm.restaurantDetails.address = business.location.display_address;
                    vm.restaurantDetails.name = restaurant.businessName;
                    vm.restaurantDetails.categories = categories;
                    // vm.restaurantDetails.moreInfo = business.snippet_text;
                    // console.log('data', vm.restaurantDetails);
                    var mapBusinessName = "<p style='font-weight:bold; font-size:16px;'>" + restaurant.businessName + "</p>";
                    renderMap(successResponse.data.region.center, mapBusinessName);
                }
            }, function(errorResponse) {
                console.log(errorResponse);
            });
        }

        /**
         * Google map
         */
        /**
         * Returns an array of object literal locations
         * @param data
         * @returns {Array}
         */
        function getLatLng(lat, lng) {
            return new google.maps.LatLng(lat, lng);
        }

        /**
         * Render the map
         * @param  {object} info
         * @return {mix}
         */
        function renderMap(info, businessName) {
            var latLng;
            map = new google.maps.Map(document.getElementById('map-canvas'),
                mapOptions);

            if (info.latitude && info.latitude !== "" && info.latitude !== 'null') {
                latLng = getLatLng(info.latitude, info.longitude);
                map.setCenter(latLng);
                setMapMarker(latLng, businessName);
            }
        }

        /**
         * Display marker on the map
         * @param {mix} latLng
         */
        function setMapMarker(latLng, businessName) {
            var marker = new google.maps.Marker({
                position: latLng,
                map: map,
                animation: google.maps.Animation.DROP
            });
            var infowindow = new google.maps.InfoWindow({
                content: businessName
            });
            infowindow.open(map, marker);
        }

        function showAdditionalInfo(restaurant) {
            var modalInstance = $uibModal.open({
                animation: false,
                templateUrl: 'myModalContent.html',
                controller: ['$scope', '$uibModalInstance', 'Restaurant', function($scope, $uibModalInstance, Restaurant) {
                    // console.log(Restaurant);
                    $scope.restaurant = Restaurant;
                    $scope.ok = function() {
                        $uibModalInstance.close();
                    };

                    $scope.getPercentage = function(value) {
                        var result = parseFloat(Math.round(value * 100));
                        if (result > 0) {
                            return result;
                        }
                        return value.toFixed(6);
                    };
                }],
                resolve: {
                    Restaurant: function() {
                        return restaurant;
                    }
                }
            });
        }


    }
})();
