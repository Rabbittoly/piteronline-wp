var RUBY_THEME_ACTIVATION = (function (Module, $) {
    'use strict';

    Module.init = function () {
        $(document).on('click', '#ruby-install-core', Module.installCore);
        $(document).on('click', '#ruby-upgrade-core', Module.upgradeCore);
        $(document).on('click', '#ruby-activation-notice .notice-dismiss', Module.ajaxDismiss);
    };

    Module.installCore = function (e) {

        e.preventDefault();
        e.stopPropagation();

        const target = $(this);
        const redirect = target.data('redirect') || '';
        const actionLabel = target.data('action') || '';
        const status = target.data('status') || '';
        const message = target.next('.rb-error');

        target.prop('disabled', true).html(actionLabel + ' <span class="loading-indicator"></span>');

        if (status === 'active') {
            if (redirect) {
                target.text('Redirecting...');
                setTimeout(() => {
                    window.location.href = redirect;
                }, 1000);
                return false;
            }
            return false;
        }

        $.ajax({
            url: typeof ajaxurl !== 'undefined' ? ajaxurl : '',
            type: 'POST',
            data: {
                action: 'ruby-install-core',
                nonce: typeof foxizActivation !== 'undefined' ? foxizActivation._nonce : '',
            },
            success: function (response) {
                if (response.success) {
                    if (redirect) {
                        target.text('Redirecting...');
                        setTimeout(function () {
                            window.location.href = redirect;
                        }, 1000);
                    } else {
                        target.text('Completed');
                    }
                } else {
                    message.text(response.data).removeClass('is-hidden');
                }
            },
            error: function (xhr, status, error) {
                target.text('Error');
            }
        });
    };


    Module.upgradeCore = function (e) {

        e.preventDefault();
        e.stopPropagation();

        const target = $(this);
        const redirect = target.data('redirect') || '';
        const actionLabel = target.data('action') || '';
        const message = target.next('.rb-error');

        target.prop('disabled', true).html(actionLabel + ' <span class="loading-indicator"></span>');

        $.ajax({
            url: typeof ajaxurl !== 'undefined' ? ajaxurl : '',
            type: 'POST',
            data: {
                action: 'ruby-upgrade-core',
                nonce: typeof foxizActivation !== 'undefined' ? foxizActivation._nonce : '',
            },
            success: function (response) {
                if (response.success) {
                    if (redirect) {
                        target.text('Redirecting...');
                        setTimeout(function () {
                            window.location.href = redirect;
                        }, 1000);
                    } else {
                        target.text('Completed');
                    }
                } else {
                    message.text(response.data).removeClass('is-hidden');
                }
            },
            error: function (xhr, status, error) {
                target.text('Error');
            }
        });
    };


    Module.ajaxDismiss = function () {
        $.ajax({
            url: typeof ajaxurl !== 'undefined' ? ajaxurl : '',
            type: 'POST',
            data: {
                action: 'ruby-activation-dismiss',
                nonce: typeof foxizActivation !== 'undefined' ? foxizActivation._nonce : '',
            },
        });
    };

    return Module;

}(RUBY_THEME_ACTIVATION || {}, jQuery));

/** init */
jQuery(document).ready(function ($) {
    RUBY_THEME_ACTIVATION.init();
});
