(function() {
    'use strict';

    angular
        .module(appInfo.module)
        .controller('IndexController', IndexController);

    IndexController.$inject = ['$scope', 'CommonService', 'toastr'];

    /* @ngInject */
    function IndexController($scope, CommonService, toastr) {
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
            console.log('search');
            // if (vm.searchTerm.length <= 0) {
            //     return;
            // }
            // vm.isSearching = true;
            // IndexService.searchPaper(vm.searchTerm, vm.currentPage, vm.itemPerPage).then(function(response) {
            //     vm.isSearching = false;
            //     if(response.status === CommonService.statusCode.HTTP_NO_CONTENT) {
            //         toastr.info('No paper in the database');
            //         vm.papers = [];
            //     }
            //     if (CommonService.isValidResponse(response)) {
            //         vm.papers = [];
            //         vm.papers = CommonService.getResponse(response);
            //         vm.isSearchResult = true;
            //     } else {
            //         toastr.warning("No paper found that maches " + vm.searchTerm);
            //     }

            // }, function(errorResponse) {
            //     toastr.warning("No paper found that maches " + vm.searchTerm);
            //     vm.isSearching = false;
            // });
        }

        /**
         * Close a search
         * @return {[type]} [description]
         */

        function closeDetails() {
            vm.isShowingDetails = false;
        }

    }
})();
