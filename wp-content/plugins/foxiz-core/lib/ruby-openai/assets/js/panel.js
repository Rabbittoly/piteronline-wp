var RB_OPENAI_PANEL = (function (Module, $) {
    'use strict';
    Module.isAjaxProgressing = false;
    Module.init = function () {

        const self = this;
        const infoWrap = $('#rb-form-response');
        const rbAiAssistantForm = $('#rb-openai');
        const saveButton = $('#rb-submit-api');
        const spinner = saveButton.find('.rb-loading');

        rbAiAssistantForm.on('submit', function (e) {

            e.preventDefault();
            e.stopPropagation();

            if (self.isAjaxProgressing) return;
            self.isAjaxProgressing = true;

            saveButton.prop('disabled', true);
            const target = $(this);
            const formData = target.serialize() + '&nonce=' + $('#rb-openai-nonce').val() + '&action=rb_openai_save';
            spinner.removeClass('is-hidden');

            $.ajax({
                type: 'POST',
                url: ajaxurl || '',
                data: formData,
                dataType: 'json',
                success: function (response) {
                    const infoClass = response.success ? 'info-success' : 'info-error';
                    infoWrap.html('<span class="' + infoClass + '">' + response.data + '</span>').show().delay(5000).fadeOut();
                    spinner.addClass('is-hidden');
                    saveButton.prop('disabled', false);
                    self.isAjaxProgressing = false;
                },
                error: function (xhr, status, error) {
                    if (xhr.responseJSON && xhr.responseJSON.data) {
                        infoWrap.text(xhr.responseJSON.data).show();
                    }
                    saveButton.text('Error!');
                }
            });
        });
    }

    return Module;

}(RB_OPENAI_PANEL || {}, jQuery));

/** init */
jQuery(document).ready(function ($) {
    RB_OPENAI_PANEL.init();
});
