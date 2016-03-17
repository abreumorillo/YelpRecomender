(function () {
    'use strict';

    angular.module('app').filter('offset', function () {
        return function (input, start) {
            if (!input || !input.length) { return; }
            start = parseInt(start, 10);
            return input.slice(start);
        };
    });
})();