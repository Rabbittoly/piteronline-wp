/** backend */
var FOXIZ_DASHBOARD_SCRIPT = (function (Module, $) {
        'use strict';

        Module.init = function () {
            this.megaMenuInfo();
        };

        /**
         * mega menu information
         */
        Module.megaMenuInfo = function () {
            let self = this;
            $('.rb-menu-setting').each(function () {
                let target = $(this);
                let titleWrap = target.parents('.menu-item').find('.item-title');
                if (target.val().length && target.val() != 0) {
                    self.addMenuInfo(target, titleWrap);
                }
                target.on('change', function () {
                    if (target.val().length && target.val() != 0) {
                        self.addMenuInfo(target, titleWrap, true);
                    } else {
                        self.removeMenuInfo(titleWrap);
                    }
                });
            })
        };

        /**
         *
         * @param target
         * @param titleWrap
         * @param animation
         */
        Module.addMenuInfo = function (target, titleWrap, animation = false) {

            if (titleWrap.children('.rb-mega-info').length != 0) {
                return;
            }

            let content = '<span class="rb-mega-info label-columns">Mega Columns</span>';
            if (target.hasClass('mega-category-setting')) {
                content = '<span class="rb-mega-info label-category">Mega Category</span>';
            }

            if (animation) {
                $(content).hide().appendTo(titleWrap).fadeIn(250);
            } else {
                titleWrap.append(content);
            }
        }

        /**
         *
         * @param titleWrap
         */
        Module.removeMenuInfo = function (titleWrap) {
            titleWrap.find('.rb-mega-info').fadeOut(250).remove();
        }

        return Module;

    }(FOXIZ_DASHBOARD_SCRIPT || {}, jQuery)
);

jQuery(document).ready(function () {
    FOXIZ_DASHBOARD_SCRIPT.init();
});
