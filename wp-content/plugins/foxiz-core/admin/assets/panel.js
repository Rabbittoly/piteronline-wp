var RB_ADMIN_CORE = (function (Module, $) {
    'use strict';

    Module.isAjaxProgressing = false;
    Module.globalConfigs = foxizAdminCore || {};

    Module.init = function () {

        this.registerForm = $('#rb-register-theme-form');
        this.deregisterForm = $('#rb-deregister-theme-form');

        this.registerPurchase();
        this.deregisterPurchase();
        this.recommendedPlugin();

        /** search */
        this.dashboardSearch();

        /** importer */
        this.fetchImporter();
        this.cleanupImportData();
        this.initImport();

        /** translation */
        this.fetchTranslation();
        this.updateTranslation();

        /** fonts */
        this.updateFontProject();
        this.deleteFontProject();

        /** GTM tag */
        this.deleteGTMTag();
        this.addGTMTag();
    };

    /** register */
    Module.registerPurchase = function () {

        const self = this;
        if (self.registerForm.length) {
            const submitBtn = self.registerForm.find('#rb-register-theme-btn');
            const loading = self.registerForm.find('.rb-loading');
            const messenger = self.registerForm.find('.rb-response-info');
            submitBtn.on('click', function (e) {

                e.preventDefault();
                e.stopPropagation();

                const data = self.getFormData($(self.registerForm));
                if (!data.nonce || !data.purchaseCode || !data.emailInfo || self.isAjaxProgressing) {
                    return;
                }

                self.isAjaxProgressing = true;
                self.setButtonWidth(submitBtn);

                $.ajax({
                    type: 'POST',
                    async: true,
                    dataType: 'json',
                    url: self.globalConfigs.ajaxUrl,
                    data: {
                        action: 'rb_register_theme',
                        purchase_code: data.purchaseCode,
                        email: data.emailInfo,
                        _nonce: data.nonce
                    },
                    beforeSend: function (xhr) {
                        self.isAjaxProgressing = true;
                        loading.fadeIn(300).removeClass('is-hidden');
                        submitBtn.attr('disabled', 'disabled');
                    },
                    success: function (response) {
                        if ('undefined' != typeof response.data) {
                            loading.fadeOut(300).addClass('is-hidden');
                            messenger.html('<p class="info-success">' + response.data + '</p>').removeClass('is-hidden');
                        }
                        setTimeout(function () {
                            location.reload();
                        }, 1500);
                    },
                    error: function (xhr, status, error) {
                        if (xhr.responseJSON && xhr.responseJSON.data) {
                            loading.fadeOut(300).addClass('is-hidden');
                            messenger.html(xhr.responseJSON.data).removeClass('is-hidden');
                        }
                    }
                });
                return false;
            })
        }
    };

    Module.setButtonWidth = function (target) {
        target.css('min-width', target.outerWidth() + 'px');
    }

    /** deregisterPurchase */
    Module.deregisterPurchase = function () {

        const self = this;
        if (self.deregisterForm.length) {
            const submitBtn = self.deregisterForm.find('#rb-deregister-theme-btn');
            const loading = self.deregisterForm.find('.rb-loading');
            const messenger = self.deregisterForm.find('.rb-response-info');

            submitBtn.on('click', function (e) {

                e.preventDefault();
                e.stopPropagation();
                const userConfirmed = window.confirm(self.globalConfigs.confirmDeactivate);
                if (userConfirmed === false) {
                    return;
                }

                if (self.isAjaxProgressing) return;
                self.isAjaxProgressing = true;

                self.setButtonWidth(submitBtn);
                const data = self.getFormData($(self.deregisterForm));

                if (!data.nonce) {
                    return;
                }

                $.ajax({
                    type: 'POST',
                    async: true,
                    dataType: 'json',
                    url: self.globalConfigs.ajaxUrl,
                    data: {
                        action: 'rb_deregister_theme',
                        _nonce: data.nonce
                    },
                    beforeSend: function (xhr) {
                        self.isAjaxProgressing = true;
                        loading.fadeIn(300).removeClass('is-hidden');
                        submitBtn.attr('disabled', 'disabled');
                    },
                    success: function (response) {
                        response = JSON.parse(JSON.stringify(response));
                        if (response.data) {
                            submitBtn.val(self.globalConfigs.reload);
                            messenger.html('<p class="info-success">' + response.data + '</p>').removeClass('is-hidden');
                        }
                        setTimeout(function () {
                            location.reload();
                        }, 1500);
                    },
                    error: function (xhr, status, error) {
                        if (xhr.responseJSON && xhr.responseJSON.data) {
                            loading.fadeOut(300).addClass('is-hidden');
                            messenger.html(xhr.responseJSON.data).removeClass('is-hidden');
                        }

                    }
                });
                return false;
            })
        }
    };

    /** recommendedPlugin */
    Module.recommendedPlugin = function () {
        const self = this;
        const submitBtn = $('.ruby-install-plugin');

        submitBtn.on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            const target = $(this);
            const wrapper = target.parents('.rb-plugin');
            const name = target.data('plugin');
            const loading = target.find('.rb-loading');
            const btnLabel = target.find('.button-label');
            const error = wrapper.find('.rb-plugin-error');
            const status = wrapper.find('.rb-plugin-status');

            if (self.isAjaxProgressing) return;

            self.isAjaxProgressing = true;
            self.setButtonWidth(target);

            $.ajax({
                type: 'POST',
                async: true,
                dataType: 'json',
                url: self.globalConfigs.ajaxUrl,
                data: {
                    action: 'rb_recommended_plugin',
                    plugin: name,
                    _nonce: self.globalConfigs._rbNonce
                },
                beforeSend: function (xhr) {
                    self.isAjaxProgressing = true;
                    loading.css('display', 'flex').removeClass('is-hidden');
                    submitBtn.attr('disabled', 'disabled');
                },

                success: function (response) {
                    self.isAjaxProgressing = false;
                    response = JSON.parse(JSON.stringify(response));
                    loading.fadeOut(300, function () {
                        loading.addClass('is-hidden');
                        if (response && response.data) {
                            btnLabel.html(response.data.btnLabel);
                            status.html(response.data.statusLabel);
                            wrapper.attr('data-status', response.data.status);
                            submitBtn.removeAttr('disabled');
                        }
                    });
                },
                error: function (response) {
                    response = JSON.parse(JSON.stringify(response.responseJSON));
                    self.isAjaxProgressing = false;

                    loading.fadeOut(300, function () {
                        loading.addClass('is-hidden');
                        if (response && response.data) {
                            error.html(response.data);
                        }
                    });
                }
            });
        })
    };

    /** dashboard search */
    Module.dashboardSearch = function () {

        const pluginWrap = $('.rb-search-area');
        if (pluginWrap.length) {
            pluginWrap.searcher({
                itemSelector: '.rb-search-item',
                textSelector: 'h3, p, label',
                inputSelector: '#rb-search-form',
                toggle: function (item, containsText) {
                    containsText ? $(item).fadeIn(200) : $(item).fadeOut(0);
                }
            });
        }
    }

    /** get form data */
    Module.getFormData = function (form) {

        const data = {};

        const purchaseCodeInput = form.find('[name="purchase_code"]');
        const emailInfoInput = form.find('[name="email"]');

        data.nonce = this.globalConfigs._rbNonce;
        data.purchaseCode = purchaseCodeInput.length ? purchaseCodeInput.val() : '';
        data.emailInfo = emailInfoInput.length ? emailInfoInput.val() : '';

        /** validate */
        if ('' !== data.purchaseCode) {
            purchaseCodeInput.removeClass('rb-validate-error');
            purchaseCodeInput.parent().find('.rb-error-info').addClass('is-hidden');
        } else {
            purchaseCodeInput.addClass('rb-validate-error');
            purchaseCodeInput.parent().find('.rb-error-info').removeClass('is-hidden');
        }

        if ('' !== data.emailInfo) {
            emailInfoInput.removeClass('rb-validate-error');
            emailInfoInput.parent().find('.rb-error-info').addClass('is-hidden');
        } else {
            emailInfoInput.addClass('rb-validate-error');
            emailInfoInput.parent().find('.rb-error-info').removeClass('is-hidden');
        }

        return data;
    };

    /** Quick Translation */
    Module.fetchTranslation = function () {

        const self = this;
        const fetchBtn = $('#rb-fetch-translation');

        fetchBtn.on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            const target = $(this);
            if (self.isAjaxProgressing) {
                return;
            }
            self.setButtonWidth(target);

            $.ajax({
                type: 'POST',
                async: true,
                dataType: 'json',
                url: self.globalConfigs.ajaxUrl,
                data: {
                    action: 'rb_fetch_translation',
                    _nonce: self.globalConfigs._rbNonce
                },
                beforeSend: function () {
                    self.isAjaxProgressing = true;
                    target.html('<span class="rb-loading"><i class="rbi-dash rbi-dash-load"></i></span><span>${self.globalConfigs.updating}</span>');
                    fetchBtn.attr('disabled', 'disabled');
                },
                success: function () {
                    target.html('<span class="rb-loading"><i class="rbi-dash rbi-dash-load"></i></span><span>${self.globalConfigs.reload}</span>');
                    setTimeout(function () {
                        location.reload();
                    }, 500)
                },
                error: function () {
                    target.html(self.globalConfigs.error);
                }
            });

        });
    }

    /** update translation */
    Module.updateTranslation = function () {
        const self = this;
        const updateBtn = $('#rb-update-translation');

        updateBtn.on('click', function (e) {

            e.preventDefault();
            e.stopPropagation();

            if (self.isAjaxProgressing) {
                return false;
            }
            self.setButtonWidth(updateBtn);

            const form = $(this).parents('#rb-translation-form');
            const nonce = self.globalConfigs._rbNonce;
            const loading = form.find('.rb-loading');
            const info = form.find('.rb-info');
            let errorText = self.globalConfigs.error;

            let data = 'action=rb_update_translation';
            data += '&_nonce=' + nonce + '';
            data += '&' + form.find('input[type="text"]').serialize();

            $.ajax({
                type: 'POST',
                async: true,
                dataType: 'json',
                url: self.globalConfigs.ajaxUrl,
                data: data,
                beforeSend: function () {
                    self.isAjaxProgressing = true;
                    loading.removeClass('is-hidden');
                    updateBtn.attr('disabled', 'disabled');
                },
                success: function (response) {
                    loading.addClass('is-hidden');
                    updateBtn.removeAttr('disabled');
                    self.isAjaxProgressing = false;
                    info.text('Settings Saved!').slideDown(300);
                    setTimeout(function () {
                        info.slideUp(300);
                    }, 2000)
                },
                error: function (xhr, status, error) {
                    loading.addClass('is-hidden');
                    if (xhr.responseJSON && xhr.responseJSON.data) {
                        errorText = xhr.responseJSON.data;
                    }
                    info.addClass('is-error').text(errorText).slideDown(300);
                }
            })
        });
    }

    /** get importer data */
    Module.fetchImporter = function () {

        const self = this;
        $('#rb-update-importer').on('click', function (e) {

            e.preventDefault();
            e.stopPropagation();

            const target = $(this);

            const confirm = window.confirm(self.globalConfigs.confirmUpdateDemos);
            if (confirm === false) {
                return;
            }

            if (self.isAjaxProgressing) return;
            self.setButtonWidth(target);
            self.isAjaxProgressing = true;

            target.html('<span class="rb-loading"><i class="rbi-dash rbi-dash-load"></i></span><span>${self.globalConfigs.updating}</span>');
            jQuery.post(self.globalConfigs.ajaxUrl, {
                action: 'rb_importer_update',
                _nonce: self.globalConfigs._rbNonce
            }, function (response) {
                if (response.length > 0 && (response.match(/done/gi))) {
                    target.html('<span class="rb-loading"><i class="rbi-dash rbi-dash-load"></i></span><span>${self.globalConfigs.reload}</span>');
                    setTimeout(function () {
                        location.reload();
                    }, 1000);
                } else {
                    target.html(`<i class="rbi-dash rbi-dash-info is-error"></i><span class="is-error">${self.globalConfigs.error}</span>`);
                    alert('There was an error: \n\n' + response.replace(/(<([^>]+)>)/gi, ""));
                }
            });
            return false;
        });
    };

    /** cleaup importer */
    Module.cleanupImportData = function () {
        const self = this;
        const template = wp.template('rb-cleanup');

        $('#rb-cleanup-import').on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            if (self.isAjaxProgressing) return;

            $('body').addClass('rb-importer-activated');
            const renderedHTML = template();
            $('#rb-importer').html(renderedHTML);

            $(document).off('click', '.rb-cleanup-import-data').on('click', '.rb-cleanup-import-data', function (e) {

                e.preventDefault();
                e.stopPropagation();

                const userConfirmed = window.confirm(self.globalConfigs.confirmDeleteImporter);
                if (userConfirmed === false) {
                    return;
                }
                const currentStep = $('#rb-importer .activated-step');
                const completeStep = $('#rb-importer .rb-step-complete');
                const errorStep = $('#rb-importer .rb-step-error');
                const loading = $('#rb-importer .rb-cleanup-info');
                const wrapper = $('#rb-importer .rb-cleanup-buttons');
                const closeBtn = $('#rb-importer .rb-close-import');

                self.isAjaxProgressing = true;
                wrapper.hide();
                loading.removeClass('is-hidden');
                closeBtn.addClass('disabled');

                $.ajax({
                    type: 'POST',
                    url: self.globalConfigs.ajaxUrl,
                    dataType: 'json',
                    data: {
                        action: 'rb_cleanup_content',
                        cleanup: $(this).data('cleanup'),
                        _nonce: self.globalConfigs._rbNonce
                    },
                    success: function () {
                        currentStep.removeClass('activated-step');
                        completeStep.addClass('activated-step');
                    },
                    error: function () {
                        currentStep.removeClass('activated-step');
                        errorStep.addClass('activated-step');
                    },
                    complete: function () {
                        self.isAjaxProgressing = false;
                        closeBtn.removeClass('disabled');
                    }
                });
            });
        });
    };

    /** import */
    Module.initImport = function () {
        const self = this;
        $(document).on('click', '.rb-do-import', self.showImportPanel.bind(self));
        $(document).on('click', '.rb-close-import', self.closeImport.bind(self));
        $(document).on('click', '#rb-import-next', self.importNextStep.bind(self));
        $(document).on('click', '#rb-import-action', self.importAction.bind(self));
    };

    /** do importer */
    Module.closeImport = function (e) {
        const self = this;
        if (self.isAjaxProgressing) {
            return;
        }
        $('body').removeClass('rb-importer-activated');
    }

    Module.showImportPanel = function (e) {
        e.preventDefault();
        e.stopPropagation();

        const self = this;
        const target = $(e.currentTarget);
        const id = target.data('id');

        if (self.isAjaxProgressing || !id || !self.globalConfigs.importData || !self.globalConfigs.importData[id]) {
            return;
        }

        $('body').addClass('rb-importer-activated');

        const demo = self.globalConfigs.importData[id];
        const template = wp.template('rb-import');
        const renderedHTML = template(demo);
        $('#rb-importer').html(renderedHTML);

        const importAllCheckbox = $('#import_all');
        const otherCheckboxes = $('.rb-importer-checkbox:not(#import_all)');
        const nextButton = $('#rb-import-next');

        function toggleNextBtn() {
            const anyChecked = importAllCheckbox.is(':checked') || otherCheckboxes.is(':checked');
            nextButton.prop('disabled', !anyChecked);
        }

        importAllCheckbox.on('change', function () {
            const isChecked = $(this).is(':checked');
            otherCheckboxes.prop('checked', isChecked);
            toggleNextBtn();
        });

        otherCheckboxes.on('change', function () {
            if (!$(this).is(':checked')) {
                importAllCheckbox.prop('checked', false);
            } else {
                const allChecked = otherCheckboxes.length === otherCheckboxes.filter(':checked').length;
                if (allChecked) {
                    importAllCheckbox.prop('checked', true);
                }
            }
            toggleNextBtn();
        });
    }

    /** action */
    Module.importNextStep = function (e) {

        e.preventDefault();
        e.stopPropagation();

        const target = $(e.currentTarget);
        if (target.is(':disabled')) {
            return;
        }

        const currentStep = $('#rb-importer .rb-step-select');
        const nextStep = $('#rb-importer .rb-step-plugin');

        currentStep.removeClass('activated-step');
        nextStep.addClass('activated-step');
    }

    /** import action */
    Module.importAction = function (e) {

        e.preventDefault();
        e.stopPropagation();

        const self = this;
        const plugins = $('#rb-importer .rb-install-plugin');
        const currentStep = $('#rb-importer .activated-step');
        const nextStep = $('#rb-importer .rb-step-importing');
        const closeBtn = $('#rb-importer .rb-close-import');

        currentStep.removeClass('activated-step');
        nextStep.addClass('activated-step');
        closeBtn.addClass('disabled');

        const settings = {
            directory: $('[data-directory]').data('directory'),
            import_all: $('[data-action="import_all"]').is(':checked'),
            import_content: $('[data-action="import_content"]').is(':checked'),
            import_pages: $('[data-action="import_pages"]').is(':checked'),
            import_opts: $('[data-action="import_opts"]').is(':checked'),
            import_widgets: $('[data-action="import_widgets"]').is(':checked'),
            clean_up: $('[data-action="clean_up"]').is(':checked'),
            plugins: []
        }

        plugins.each(function () {
            const pluginCheckbox = $(this).find('input[type="checkbox"]');
            const pluginSlug = pluginCheckbox.val();
            if (pluginCheckbox.is(':checked')) {
                settings.plugins.push(pluginSlug);
            }
        });

        self.handleAjaxImport(settings);

    };

    /** import */
    Module.runImportPluginContent = function (settings, currentStep, completeStep, errorStep, closeBtn) {
        const self = this;

        $.ajax({
            type: 'POST',
            async: true,
            dataType: 'json',
            url: self.globalConfigs.ajaxUrl,
            data: {
                action: 'rb_importer_plugin',
                settings: settings,
                _nonce: self.globalConfigs._rbNonce
            },
            success: function () {
                self.runImportContent(settings, currentStep, completeStep, errorStep, closeBtn);
            },
            error: function () {
                currentStep.removeClass('activated-step');
                errorStep.addClass('activated-step');
                if (self.progressInterval) {
                    clearInterval(self.progressInterval);
                    self.progressInterval = null;
                }
            }
        });
    }

    Module.runImportContent = function (settings, currentStep, completeStep, errorStep, closeBtn) {
        const self = this;
        $.ajax({
            type: 'POST',
            async: true,
            dataType: 'json',
            url: self.globalConfigs.ajaxUrl,
            data: {
                action: 'rb_importer',
                settings: settings,
                _nonce: self.globalConfigs._rbNonce
            },
            success: function (response) {
                currentStep.removeClass('activated-step');
                if (response && response.match(/Have fun!/gi)) {
                    completeStep.addClass('activated-step');
                } else {
                    errorStep.addClass('activated-step');
                }
            },
            error: function (xhr, status, error) {
                currentStep.removeClass('activated-step');
                if (xhr.responseText && xhr.responseText.match(/Have fun!/gi)) {
                    completeStep.addClass('activated-step');
                } else {
                    errorStep.addClass('activated-step');
                }
            },
            complete: function () {
                self.isAjaxProgressing = false;
                closeBtn.removeClass('disabled');
                if (self.progressInterval) {
                    clearInterval(self.progressInterval);
                    self.progressInterval = null;
                }
            }
        });
    }

    Module.handleAjaxImport = function (settings) {
        const self = this;
        const currentStep = $('#rb-importer .activated-step');
        const completeStep = $('#rb-importer .rb-step-complete');
        const errorStep = $('#rb-importer .rb-step-error');
        const closeBtn = $('#rb-importer .rb-close-import');

        if (self.isAjaxProgressing) return;

        self.getImporterProgress();
        self.isAjaxProgressing = true;
        if (settings.plugins && Array.isArray(settings.plugins) && settings.plugins.length > 0) {
            self.runImportPluginContent(settings, currentStep, completeStep, errorStep, closeBtn);
        } else {
            self.runImportContent(settings, currentStep, completeStep, errorStep, closeBtn);
        }
    }

    Module.getImporterProgress = function () {
        const self = this;

        const progressLabel = $('#rb-importer .rb-import-progress-label');
        const progressPercent = $('#rb-importer .rb-import-progress-percent');
        const indicator = $('#rb-importer .rb-import-progress-indicator');

        self.progressInterval = setInterval(function () {
            $.ajax({
                type: 'POST',
                async: true,
                dataType: 'json',
                url: self.globalConfigs.ajaxUrl,
                data: {
                    action: 'rb_import_progress',
                    _nonce: self.globalConfigs._rbNonce
                },
                success: function (response) {
                    if (response && response.success && response.data) {
                        progressLabel.html(response.data.label);
                        progressPercent.html(response.data.percent + '%');
                        indicator.css('width', response.data.percent + '%');
                    }
                }
            });
        }, 3000);
    }

    /** delete font project */
    Module.deleteFontProject = function () {
        const self = this;
        const deleteButton = $('#delete-project-id');
        const messenger = $('.rb-response-info');

        deleteButton.on('click', function (e) {

            e.preventDefault();
            e.stopPropagation();

            if (self.isAjaxProgressing) return;
            const confirm = window.confirm(self.globalConfigs.confirmDeleteAdobeFont);
            if (confirm === false) {
                return;
            }

            deleteButton.addClass('disabled');
            self.isAjaxProgressing = true;

            $.ajax({
                type: 'POST',
                async: true,
                dataType: 'json',
                url: self.globalConfigs.ajaxUrl,
                data: {
                    action: 'rb_adobef_delete',
                    _nonce: self.globalConfigs._rbNonce
                },
                success: function (response) {
                    response = JSON.parse(JSON.stringify(response));
                    if ('undefined' != typeof response.data) {
                        messenger.html('<p class="info-success">' + response.data + '</p>').removeClass('is-hidden');
                    }
                    setTimeout(function () {
                        location.reload();
                    }, 1000)
                },
                error: function (xhr, status, error) {
                    deleteButton.text(self.globalConfigs.error);
                    if (xhr.responseJSON && xhr.responseJSON.data) {
                        messenger.html(xhr.responseJSON.data).removeClass('is-hidden');
                    }
                },
                complete: function () {
                    self.isAjaxProgressing = false;
                }
            });
        })
    }

    /** update font project */
    Module.updateFontProject = function () {
        const self = this;
        const editBtn = $('#rb-edit-project-id');
        const submitBtn = $('#submit-project-id');
        const projectID = $('#rb-project-id');
        const messenger = $('.rb-response-info');
        const loading = $('.rb-loading');

        editBtn.on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).remove();
            projectID.prop('readonly', false);
            submitBtn.removeClass('is-hidden');
        });

        submitBtn.on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            if (self.isAjaxProgressing) return;

            self.setButtonWidth(submitBtn);
            submitBtn.addClass('disabled');
            self.isAjaxProgressing = true;
            loading.fadeIn(300).removeClass('is-hidden');

            $.ajax({
                type: 'POST',
                async: true,
                dataType: 'json',
                url: self.globalConfigs.ajaxUrl,
                data: {
                    action: 'rb_adobef_update',
                    _nonce: self.globalConfigs._rbNonce,
                    projectID: projectID.val()
                },
                success: function (response) {
                    if (response.data) {
                        messenger.html('<p class="info-success">' + response.data + '</p>').removeClass('is-hidden');
                        submitBtn.text(self.globalConfigs.reload);
                        setTimeout(function () {
                            location.reload();
                        }, 1000)
                    }
                },
                error: function (xhr, status, error) {
                    submitBtn.text(self.globalConfigs.error);
                    if (xhr.responseJSON && xhr.responseJSON.data) {
                        messenger.html(xhr.responseJSON.data).removeClass('is-hidden');
                    }
                },
                complete: function () {
                    self.isAjaxProgressing = false;
                }
            });
        })
    };

    /** delete GTM tag */
    Module.deleteGTMTag = function () {
        const self = this;
        const button = $('#rb-gtm-delete');
        const messenger = $('.rb-response-info');
        const loading = button.find('.rb-loading');

        button.on('click', function (e) {

            e.preventDefault();
            e.stopPropagation();

            const confirm = window.confirm(self.globalConfigs.confirmDeleteGA);
            if (confirm === false) {
                return;
            }

            if (self.isAjaxProgressing) return;
            self.setButtonWidth(button);
            button.addClass('disabled');
            loading.fadeIn(300).removeClass('is-hidden');

            self.isAjaxProgressing = true;

            $.ajax({
                type: 'POST',
                async: true,
                dataType: 'json',
                url: self.globalConfigs.ajaxUrl,
                data: {
                    action: 'rb_gtm_delete',
                    _nonce: self.globalConfigs._rbNonce
                },
                success: function (response) {
                    response = JSON.parse(JSON.stringify(response));
                    if ('undefined' != typeof response.data) {
                        messenger.html('<p class="info-success">' + response.data + '</p>').removeClass('is-hidden');
                    }
                    button.text(self.globalConfigs.reload);
                    setTimeout(function () {
                        location.reload();
                    }, 1000)
                },
                error: function (xhr, status, error) {
                    button.text(self.globalConfigs.error);
                    if (xhr.responseJSON && xhr.responseJSON.data) {
                        messenger.html(xhr.responseJSON.data).removeClass('is-hidden');
                    }
                },
                complete: function () {
                    self.isAjaxProgressing = false;
                }
            });
        })
    }

    /** add tags */
    Module.addGTMTag = function () {

        const self = this;
        const button = $('#rb-gtm-submit');
        const messenger = $('.rb-response-info');
        const loading = button.find('.rb-loading');

        button.on('click', function (e) {

            e.preventDefault();
            e.stopPropagation();

            if (self.isAjaxProgressing) {
                return
            }

            self.setButtonWidth(button);
            button.addClass('disabled');
            self.isAjaxProgressing = true;
            loading.fadeIn(300).removeClass('is-hidden');

            $.ajax({
                type: 'POST',
                async: true,
                dataType: 'json',
                url: self.globalConfigs.ajaxUrl,
                data: {
                    action: 'rb_gtm_add',
                    gtmID: $('#rb-gtm-input').val(),
                    gtagID: $('#rb-gtag-input').val(),
                    _nonce: self.globalConfigs._rbNonce
                },
                success: function (response) {
                    response = JSON.parse(JSON.stringify(response));
                    if ('undefined' != typeof response.data) {
                        messenger.html('<p class="info-success">' + response.data + '</p>').removeClass('is-hidden');
                    }
                    button.text(self.globalConfigs.reload);
                    setTimeout(function () {
                        location.reload();
                    }, 1000)
                },
                error: function (xhr, status, error) {
                    button.removeClass('disabled');
                    if (xhr.responseJSON && xhr.responseJSON.data) {
                        messenger.html(xhr.responseJSON.data).removeClass('is-hidden');
                    }
                },
                complete: function () {
                    loading.addClass('is-hidden');
                    self.isAjaxProgressing = false;
                }
            });
        })
    }

    return Module;
}(RB_ADMIN_CORE || {}, jQuery));

jQuery(document).ready(function () {
    RB_ADMIN_CORE.init();
});