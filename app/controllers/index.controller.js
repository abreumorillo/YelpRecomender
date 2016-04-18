(function() {
    'use strict';

    angular
        .module(appInfo.module)
        .controller('IndexController', IndexController);

    IndexController.$inject = ['$scope', 'CommonService', 'toastr', 'IndexService', 'focus', '$timeout'];

    /* @ngInject */
    function IndexController($scope, CommonService, toastr, IndexService, focus, $timeout) {
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
        vm.closeSearch = onCloseSearch;

        //FUNCTIONS
        vm.closeDetails = closeDetails;
        vm.onKeyPressed = onKeyPressed;
        vm.replaceWord = replaceWord;

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
                    if(CommonService.isValidResponse(response)){
                        vm.terms = response.data;
                        //remove suggestion after 5 seconds
                        $timeout(function () {
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

    }
})();
