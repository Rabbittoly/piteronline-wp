var RB_OPENAI_EDITOR = (function (Module, $) {
    'use strict';

    Module.isSubmitting = false;

    Module.init = function () {
        this.changePromptInfo();
        this.openAISubmit();
        this.copyText();
    };

    Module.generatePromptContent = function () {
        var infoWrap = $('#openai-prompt-info');
        var prompt = $('#prompt').val() || '';
        var contentType = $('#content-type').val() || 'title';
        var style = $('#writing-style').val() || '';
        var language = $('#writing-language').val() || '';

        var templates = {
            'title': $('#tmpl-prompt-title').text(),
            'excerpt': $('#tmpl-prompt-excerpt').text(),
            'content': $('#tmpl-prompt-content').text(),
            'description': $('#tmpl-prompt-meta-description').text(),
            'keys': $('#tmpl-prompt-meta-keywords').text(),
            'tags': $('#tmpl-prompt-tags').text(),
        };

        var promptContent = templates[contentType] || '';

        if (promptContent) {
            promptContent = promptContent.replace('{{prompt}}', prompt);
            promptContent = promptContent.replace('{{language}}', language);
            promptContent = promptContent.replace('{{style}}', style);
        }

        promptContent = promptContent.trim();
        infoWrap.html(promptContent);
        return promptContent;
    };

    Module.openAISubmit = function () {
        var self = this;
        var submitBtn = $('#openai-generate-content');
        var responseWrap = $('#openai-response');
        var noticeWrap = $('#rb-openai-notice');

        submitBtn.on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            var contentType = $('#content-type').val();
            var prompt = $('#prompt').val();

            if (!prompt) {
                noticeWrap.html('<p>Please input your keywords or the content...</p>').show(200);
                self.isSubmitting = false;
                return false;
            }

            var promptContent = self.generatePromptContent();
            if (!promptContent || self.isSubmitting) {
                return false;
            }

            self.isSubmitting = true;
            submitBtn.addClass('disabled');

            var spinner = $('<span class="rb-loading"><i class="dashicons dashicons-update"></i></span>');
            noticeWrap.html(spinner).show(200);

            $.ajax({
                type: 'POST',
                url: ajaxurl,
                data: {
                    'action': 'rb_openai_create_content',
                    'prompt': promptContent,
                    'type': contentType,
                },
                dataType: 'json',
                success: function (response) {
                    self.isSubmitting = false;
                    submitBtn.removeClass('disabled');
                    noticeWrap.hide();

                    response.content && responseWrap.html(response.content);
                    response.error && noticeWrap.html('<p>' + response.error + '</p>').show(200);
                },

                error: function (jqXHR, textStatus, errorThrown) {
                    self.isSubmitting = false;
                    submitBtn.removeClass('disabled');
                    noticeWrap.hide();
                }
            });
        });
    };

    Module.copyText = function () {
        var self = this;
        $('.rb-meta-copy-btn').on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var target = $(this);
            var content = target.prev().html();

            navigator.clipboard.writeText(content).then(
                function () {
                    self.showCopySuccessPopup();
                }).catch(function () {
                console.log('Copying is not supported by your browser.');
            });
        });
    }

    Module.changePromptInfo = function () {
        $('#prompt, #content-type, #writing-style, #writing-language').on('input change', this.generatePromptContent);
    };

    Module.showCopySuccessPopup = function () {
        var popup = document.createElement('div');
        popup.className = 'rb-copied-popup';
        popup.textContent = 'Text copied successfully!';
        document.body.appendChild(popup);
        setTimeout(function () {
            document.body.removeChild(popup);
        }, 1500);
    }

    return Module;

}(RB_OPENAI_EDITOR || {}, jQuery));

/** init */
jQuery(document).ready(function ($) {
    RB_OPENAI_EDITOR.init();
});
