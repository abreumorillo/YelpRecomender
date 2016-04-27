(function() {
    'use strict';

    angular
        .module(appInfo.module)
        .controller('IndexController', IndexController);

    IndexController.$inject = ['CommonService', 'toastr', 'IndexService', 'focus', '$timeout', 'YelpService'];

    /* @ngInject */
    function IndexController(CommonService, toastr, IndexService, focus, $timeout, YelpService) {
        var vm = this,
            geocoder,
            map = null,
            marker,
            mapOptions = { //map option for google map
                zoom: 16
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

        activate();

        ////////////////

        function activate() {
            toastr.error('Yelp Search Engine');
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
                console.log(response);
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
            var business = restaurant.businessName;
            var location = restaurant.businessCity + ', ' + restaurant.businessState;
            YelpService.getRestaurants(business, location).then(function(successResponse) {
                console.log(successResponse);
                console.log(successResponse.data.region.center);
                vm.restaurantDetails.name = restaurant.businessName;
                var mapBusinessName = "<p style='font-weight:bold; font-size:16px;'>"+ business + "</p>";
                renderMap(successResponse.data.region.center, mapBusinessName);
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
            // else { //if the latitude and longitude are not provided we try using the google geocoder service
            //     var address = info.address1 + ', ' + info.city + ', ' + info.state;
            //     geocoder.geocode({
            //         'address': address
            //     }, function(results, status) {
            //         if (status == google.maps.GeocoderStatus.OK) {
            //             latLng = results[0].geometry.location;
            //             map.setCenter(latLng);
            //             setMapMarker(latLng);
            //         } else {
            //             toastr.warning("Geocode was not successful for the following reason: " + status);
            //         }
            //     });
            // }
            // Add a new marker at the new plotted point on the polyline.
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

    }
})();
