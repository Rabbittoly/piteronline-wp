/** core */
var RUBY_BBP_SUPPORTED = (function (Module, $) {
    "use strict";

    Module.init = function () {
        this.topicToggle();
    }

    Module.topicToggle = function () {
        $('#bbp-new-topic-toggle-btn').off('click').on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            $('#bbp-new-topic-toggle').slideToggle('400');
        })
    }

    return Module;
}(RUBY_BBP_SUPPORTED || {}, jQuery));


jQuery(document).ready(function () {
    RUBY_BBP_SUPPORTED.init();
});