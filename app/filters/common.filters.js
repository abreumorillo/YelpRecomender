/**
 * Common filters used in the application
 * @return {mix}
 * common.filters.js
 */
(function() {

    var app = angular.module(appInfo.module);
    //String truncate http://jsfiddle.net/tuyyx/
    app.filter('truncate', function() {
        return function(text, length, end) {
            if (isNaN(length))
                length = 10;

            if (end === undefined)
                end = "...";

            if (text.length <= length || text.length - end.length <= length) {
                return text;
            } else {
                return String(text).substring(0, length - end.length) + end;
            }

        };
    });

    //offset
    app.filter('offset', function() {
        return function(input, start) {
            if (!input || !input.length) {
                return; }
            start = parseInt(start, 10);
            return input.slice(start);
        };
    });

    // Came from the comments here:  https://gist.github.com/maruf-nc/5625869
    app.filter('titlecase', function() {
        return function(input) {
            var smallWords = /^(a|an|and|as|at|but|by|en|for|if|in|nor|of|on|or|per|the|to|vs?\.?|via)$/i;

            input = input.toLowerCase();
            return input.replace(/[A-Za-z0-9\u00C0-\u00FF]+[^\s-]*/g, function(match, index, title) {
                if (index > 0 && index + match.length !== title.length &&
                    match.search(smallWords) > -1 && title.charAt(index - 2) !== ":" &&
                    (title.charAt(index + match.length) !== '-' || title.charAt(index - 1) === '-') &&
                    title.charAt(index - 1).search(/[^\s-]/) < 0) {
                    return match.toLowerCase();
                }

                if (match.substr(1).search(/[A-Z]|\../) > -1) {
                    return match;
                }

                return match.charAt(0).toUpperCase() + match.substr(1);
            });
        };
    });
})();