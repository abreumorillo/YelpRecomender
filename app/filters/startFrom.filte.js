/**
 * @purpose         : This filter is used for pagination.
 * @course          : Knowledge Process Technologies (ISTE-612)
 */
(function () {
    'use strict';

    angular.module(appInfo.module).filter('offset', function () {
        return function (input, start) {
            if (!input || !input.length) { return; }
            start = parseInt(start, 10);
            return input.slice(start);
        };
    });
    
})();