(function() {
    'use strict';

    angular
        .module(appInfo.module)
        .controller('IndexController', IndexController);

    IndexController.$inject = ['$scope', 'CommonService', 'toastr', 'IndexService'];

    /* @ngInject */
    function IndexController($scope, CommonService, toastr, IndexService) {
        var vm = this;
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
        vm.closeSearch = closeSearch;

        //FUNCTIONS
        vm.closeDetails = closeDetails;

        activate();

        ////////////////

        function activate() {
            toastr.error('Yelp Restaurant Recommender');
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
                if(response.status === CommonService.statusCode.HTTP_NO_CONTENT) {
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
         * Close a search
         * @return {[type]} [description]
         */

        function closeDetails() {
            vm.isShowingDetails = false;
        }

        function closeSearch(){
           vm.isSearchResult = false;
        }

    }
})();
