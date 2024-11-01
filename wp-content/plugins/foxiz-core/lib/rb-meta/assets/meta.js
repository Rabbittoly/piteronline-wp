/** RUBY META BOXES */
var RB_META_BOXES = (function (Module, $) {
    "use strict";

    Module.init = function () {
        var self = this;
        self.$Document = $(document);
        self.body = $('body');
        self.switchPanel();
        self.imageSelect();
        self.uploadImages();
        self.uploadGallery();
        self.removeImages();
        self.removeGallery();
        self.removeFile();
        self.fileUpload();
        self.datePicker();
        self.repeatGroup();
        self.taxSelect();

        setTimeout(function () {
            self.removeHideJs();
            self.exceptIncludeTemplate();
        }, 10);
    };

    /* switch panel */
    Module.switchPanel = function () {
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

    /** remove image upload */
    Module.removeImages = function () {
        this.$Document.on('click', '.rb-clear-images', function (event) {
            event.preventDefault();
            event.stopPropagation();

            var currentWrap = $(this).parents('.rb-images');
            currentWrap.find('.rb-value-images').val('').trigger('change');
            currentWrap.find('.meta-preview').html('');
        });
    };

    /** remove gallery upload */
    Module.removeGallery = function () {
        this.$Document.on('click', '.rb-clear-gallery', function (event) {
            event.preventDefault();
            event.stopPropagation();

            var currentWrap = $(this).parents('.rb-gallery');
            currentWrap.find('.rb-value-gallery').val('').trigger('change');
            currentWrap.find('.meta-preview').html('');
        });
    };

    /** image upload */
    Module.uploadImages = function () {
        this.$Document.on('click', '.rb-edit-images', function (event) {
            if (typeof wp === 'undefined' || !wp.media) {
                return;
            }

            event.preventDefault();
            event.stopPropagation();

            var currentWrap = $(this).parents('.rb-images');
            var preview = currentWrap.find('.meta-preview');
            var targetValWrap = currentWrap.find('.rb-value-images');
            var currentVal = targetValWrap.val();
            var attachment, attachments, element, previewImg, previewHTML;

            if (frame) {
                frame.open();
                return;
            }

            var frame = wp.media({
                title: 'Select Images',
                multiple: true
            });

            frame.on('open', function () {
                var selection = frame.state().get('selection');
                if (currentVal.length > 0) {
                    var ids = currentVal.split(',');
                    ids.forEach(function (id) {
                        attachment = wp.media.attachment(id);
                        selection.add(attachment ? [attachment] : []);
                    });
                }
            });

            frame.on('select', function () {
                attachments = frame.state().get('selection').toJSON();
                preview.html('');

                var selectionIDs = attachments.map(function (element) {
                    previewImg = typeof element.sizes.thumbnail !== 'undefined' ? element.sizes.thumbnail.url : element.url;
                    previewHTML = '<span class="thumbnail"><img src="' + previewImg + '"/></span>';
                    preview.append(previewHTML);

                    return element.id;
                });

                targetValWrap.val(selectionIDs.join(',')).trigger('change');
            });

            frame.open();
        });
    };

    /** gallery upload */
    Module.uploadGallery = function () {

        var self = this;
        self.$Document.on('click', '.rb-edit-gallery', function (event) {
            if (typeof wp === 'undefined' || !wp.media) {
                return;
            }

            event.preventDefault();
            event.stopPropagation();

            var currentWrap = $(this).parents('.rb-gallery');
            var preview = currentWrap.find('.meta-preview');
            var targetValWrap = currentWrap.find('.rb-value-gallery');
            var currentVal = targetValWrap.val();
            var library, attachments;

            if (frame) {
                frame.open();
                return;
            }

            var frame = wp.media({
                title: wp.media.view.l10n.editGalleryTitle,
                frame: 'post',
                state: 'gallery-edit',
                editing: true,
                multiple: true,
                selection: self.getSelectGallery(currentVal),
                library: {
                    order: 'ASC',
                    type: 'image',
                    search: null
                }
            });

            frame.on('update', function () {
                library = frame.states.get('gallery-edit').get('library');
                attachments = library.pluck('id');
                preview.html('');

                $.ajax({
                    type: 'POST',
                    url: rbMetaParams.ajaxurl,
                    data: {
                        action: 'rb_meta_gallery',
                        attachments: attachments
                    },
                    success: function (data) {
                        data = JSON.parse(JSON.stringify(data));
                        preview.append(data);
                    }
                });
                targetValWrap.val(attachments.join(',')).trigger('change');
            });

            frame.open();
        });
    };

    /** get selection */
    Module.getSelectGallery = function (value) {

        if (!value) {
            return;
        }

        var selection, attachments;
        var shortcode = wp.shortcode.next('gallery', '[gallery ids=\'' + value + '\']');
        var defaultPostId = wp.media.gallery.defaults.id;

        if (!shortcode) {
            return;
        }

        shortcode = shortcode.shortcode;
        if (_.isUndefined(shortcode.get('id')) && !_.isUndefined(defaultPostId)) {
            shortcode.set('id', defaultPostId);
        }

        if (_.isUndefined(shortcode.get('ids'))) {
            shortcode.set('ids', '0');
        }

        attachments = wp.media.gallery.attachments(shortcode);
        selection = new wp.media.model.Selection(attachments.models, {
            props: attachments.props.toJSON(),
            multiple: true
        });

        selection.gallery = attachments.gallery;

        selection.more().done(function () {
            selection.props.set({query: false});
            selection.unmirror();
            selection.props.unset('orderby');
        });

        return selection;
    };

    /** image select */
    Module.imageSelect = function () {
        this.$Document.on('click', 'input.rb-meta-image', function () {
            var target = $(this);
            target.parent('.rb-checkbox').addClass('is-active').siblings().removeClass('is-active');
        })
    };

    /** remove file upload */
    Module.removeFile = function () {
        this.$Document.on('click', '.rb-clear-file', function (event) {
            event.preventDefault();
            event.stopPropagation();

            var currentWrap = $(this).parents('.rb-file');
            currentWrap.find('.rb-value-file').val('').trigger('change');
            currentWrap.find('.meta-preview').html('');
            return false;
        });
    };

    /* file upload */
    Module.fileUpload = function () {
        $('.rb-edit-file').on('click', function (event) {
            if (typeof wp === 'undefined' || !wp.media) {
                return;
            }

            event.preventDefault();
            event.stopPropagation();

            var currentWrap = $(this).parents('.rb-file');
            var preview = currentWrap.find('.meta-preview');
            var targetValWrap = currentWrap.find('.rb-value-file');
            var currentVal = targetValWrap.val();
            var attachment, previewImg, previewName, previewHTML;

            if (frame) {
                frame.open();
                return;
            }

            var frame = wp.media({
                title: 'Select Media',
                multiple: false
            });

            frame.on('open', function () {
                var selection = frame.state().get('selection');
                if (currentVal.length > 0) {
                    attachment = wp.media.attachment(currentVal);
                    selection.add(attachment ? [attachment] : []);

                }
            });

            frame.on('select', function () {
                attachment = frame.state().get('selection').first().toJSON();
                preview.html('');

                var selectionID = attachment.id;
                previewImg = typeof attachment.thumb !== 'undefined' ? attachment.thumb.src : attachment.url;
                previewName = typeof attachment.filename !== 'undefined' ? attachment.filename : attachment.url;
                previewHTML = '<span class="thumbnail file"><img src="' + previewImg + '"/><span class="file-name">' + previewName + '</span></span>';
                preview.append(previewHTML);

                targetValWrap.val(selectionID).trigger('change');
            });

            frame.open();
        });
    };

    /** date picker */
    Module.datePicker = function () {
        var $rbMetaDate = $('.rb-meta-date');
        if ($rbMetaDate.length > 0) {
            $rbMetaDate.datepicker({
                dateFormat: 'mm/dd/yy'
            });
        }
    };

    /* exceptIncludeTemplate */
    Module.exceptIncludeTemplate = function () {
        var tempTarget = '.editor-page-attributes__template select, #page_template';
        this.$Document.on('change RB:metaTempSelect', tempTarget, function () {
            var tempVal = $(this).val();
            var metaWrap = $('.rb-meta-wrapper');
            if (metaWrap.length > 0) {
                metaWrap.each(function () {
                    var target = $(this);
                    var except = target.data('except_template');
                    var include = target.data('include_template');
                    var metaBox = $('#' + target.data('section_id'));
                    if (except) {
                        if (tempVal == except) {
                            metaBox.addClass('is-hidden');
                        } else {
                            metaBox.removeClass('is-hidden');
                        }
                    }
                    if (include) {
                        metaBox.addClass('is-hidden');
                        if (tempVal == include) {
                            metaBox.removeClass('is-hidden');
                        }
                    }
                });
            }
        });

        $(tempTarget).trigger('RB:metaTempSelect');
    };

    /**
     * repeat group
     */
    Module.repeatGroup = function () {

        let self = this;
        let tempTarget = '.rb-group-trigger';
        let deleteGroupTarget = '.rb-group-delete';
        let sortableTarget = '.rb-group-content';

        self.$Document.on('click', tempTarget, function (e) {
            e.preventDefault();
            e.stopPropagation();
            let target = $(this);
            let wrapper = target.parents('.rb-group');
            let defaultItem = wrapper.find('.default-group-item');
            let html = defaultItem.clone();
            let index = defaultItem.data('index');

            html.removeClass('is-hidden default-group-item');
            html.find('input').each(function () {
                let item = $(this);
                let name = 'rb_meta[' + item.data('group-id') + '][' + index + '][' + item.data('id') + ']';
                item.attr('value', item.data('value'));
                item.attr('name', name);
            });
            defaultItem.data('index', index + 1);
            wrapper.find('.rb-group-content').append(html)
        });

        self.$Document.on('click', deleteGroupTarget, function (e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).parents('.group-item').remove();
        });

        if (self.body.find(sortableTarget).length > 0) {
            self.body.find(sortableTarget).sortable({
                axis: 'y'
            });
        }

        self.body.on('touchstart touchmove touchend', `${sortableTarget} input`, function (e) {
            if (e.type === 'touchstart') {
                $(this).data('detectTap', true);
            } else if (e.type === 'touchmove') {
                $(this).data('detectTap', false);
            } else if (e.type === 'touchend') {
                if ($(this).data('detectTap')) {
                    $(this).focus();
                }
            }
        });
    }

    Module.taxSelect = function () {
        setTimeout(function () {

            var tagsSuggest = $('.rb-tags-suggest');
            var rbTaxSelect = $('.rb-tax-select');

            if (tagsSuggest.length > 0) {
                tagsSuggest.each(function () {
                    var target = $(this);
                    target.wpTagsSuggest({
                        select: function (event, ui) {
                            $(this).val(ui.item.name.trim());
                            return false;
                        }
                    });
                })
            }

            if (rbTaxSelect.length > 0) {
                rbTaxSelect.each(function () {
                    var currentSelect = $(this);
                    var parent = currentSelect.parent('.rb-tax-select-parent');
                    try {
                        currentSelect.select2({
                            dropdownParent: parent,
                            ...(currentSelect.select2.hasOwnProperty('isOpen') && typeof currentSelect.select2.isOpen === 'function' ? {
                                multiple: false,
                            } : {})
                        });
                    } catch (error) {
                    }
                });
            }
        }, 150);
    }

    /** remove JS hide */
    Module.removeHideJs = function () {
        var metaWrap = $('.rb-meta-wrapper');
        if (metaWrap.length > 0) {
            metaWrap.each(function () {
                var target = $(this);
                var metaBox = $('#' + target.data('section_id'));
                metaBox.removeClass('hide-if-js');
            })
        }
    };

    return Module;

}(RB_META_BOXES || {}, jQuery));

/** init RUBY META BOXES */
jQuery(document).ready(function () {
    RB_META_BOXES.init();
});