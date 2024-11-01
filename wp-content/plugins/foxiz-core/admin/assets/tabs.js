/** RUBY META BOXES */
var RB_META_TAB_SWITCHER = (function (Module, $) {
    "use strict";

    Module.init = function () {
        var self = this;
        self.$Document = $(document);
        self.body = $('body');

        $('.rb-tab-title').on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            var target = $(this);
            var tab = target.data('tab');
            var id = '#rb-tab-' + tab;
            var wrapper = target.parents('.rb-meta-wrapper');
            wrapper.css('height', wrapper.height());
            target.addClass('is-active').siblings().removeClass('is-active');
            wrapper.find('.rb-meta-tab').removeClass('is-active');
            wrapper.find(id).addClass('is-active');
            wrapper.css('height', 'auto');
            wrapper.find('.rb-meta-last-tab').val(tab);

            return false;
        })

    };

    return Module;

}(RB_META_TAB_SWITCHER || {}, jQuery));

/** init RUBY META BOXES */
jQuery(document).ready(function () {
    RB_META_TAB_SWITCHER.init();
});