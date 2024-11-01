var rbSubmissionForm;
var rbLocalizeData;
const submissionFormContent = Vue.defineComponent( {
    name: 'submissionFormContent',
    components: {
        recaptchaContent
    },
    data()
    {
        return {
            snackbar: Vue.ref( false ),
            snackbarClass: Vue.ref( 'rbsm-failed-snackbar' ),
            snackbarMessage: Vue.ref( '' ),
            userPostFromLocalStorage: Vue.ref( undefined ),
            isFromPostManager: Vue.ref( false ),
            userPostLocalStorageKey: Vue.ref( '' ),
            formLayoutClass: Vue.ref( '' ),
            formLayoutDisplayMap: Vue.ref( [] ),
            yesStorage: false,
            postId: null,
            tags: Vue.ref( [] ),
            title: Vue.ref( '' ),
            content: Vue.ref( '' ),
            quill: Vue.ref( null ),
            excerpt: Vue.ref( '' ),
            editor: Vue.ref( null ),
            recaptchaSiteKey: '',
            categories: Vue.ref( [] ),
            isEditPostMode: false,
            recaptchaResponse: '',
            editorType: Vue.ref( '' ),
            prependIcon: Vue.ref( '' ),
            dialogTitle: Vue.ref( '' ),
            tagsSelected: Vue.ref( [] ),
            formSettings: Vue.ref( [] ),
            customFields: Vue.ref( [] ),
            userNameInput: Vue.ref( '' ),
            dialogMessage: Vue.ref( '' ),
            imagePreview: Vue.ref( null ),
            challengeInput: Vue.ref( '' ),
            userEmailInput: Vue.ref( '' ),
            isVerifiedRecaptcha: true,
            amountUploadMedia: Vue.ref( 0 ),
            isRemoveFeaturedImage: false,
            challengeResponse: Vue.ref( '' ),
            challengeQuestion: Vue.ref( '' ),
            categoriesSelected: Vue.ref( [] ),
            isDisplayDialog: Vue.ref( false ),
            allowAddNewTags: Vue.ref( false ),
            allowDisplayTags: Vue.ref( false ),
            featuredImageFile: Vue.ref( null ),
            isUsingRecaptcha: Vue.ref( false ),
            customFieldsContent: Vue.ref( [] ),
            isUsingChallenge: Vue.ref( false ),
            allowMultipleTags: Vue.ref( false ),
            allowDisplayTitle: Vue.ref( false ),
            allowDisplayExcerpt: Vue.ref( false ),
            allowDisplayUserName: Vue.ref( false ),
            allowDisplayUserEmail: Vue.ref( false ),
            isDisableSubmitButton: Vue.ref( false ),
            allowDisplayCategories: Vue.ref( false ),
            allowMultipleCategories: Vue.ref( false ),
            doDirectLinkAfterDissmisDialog: false,
            isInvalidAnswerChallenge: Vue.ref( true ),
            isDisplayChallengeDialog: Vue.ref( false ),
            isDisplayProgressCircular: Vue.ref( false ),
            allowDisplayFeaturedImage: Vue.ref( false ),
            isDisplayInvalidAnswerChallengeText: Vue.ref( false ),
            translate: Vue.ref( rbLocalizeData.translate ),
            formLayoutDisplayType: Vue.ref( '' ),
            featuredInputLabel: Vue.ref( rbLocalizeData.translate.chooseFeaturedImage ),
            submitButtonText: Vue.ref( rbLocalizeData.translate.submitPostLabel ),
            rules: {
                title: Vue.ref( true ),
                excerpt: Vue.ref( true ),
                userName: Vue.ref( true ),
                userEmail: Vue.ref( true ),
                challenge: value => !!value || rbLocalizeData.translate.challengeRequiredInput
            },
        };
    },
    created()
    {
        this.yesStorage = this.isStorageAvailable();
    },
    async mounted()
    {
        this.initFormLayoutDisplayMap();
        this.getFormSettings();

        if( this.allowDisplayCategories ) this.getCategories();
        if( this.allowDisplayTags ) this.getTags();

        this.initQuillEditor();
        this.tryRenderUserPostContent();

        this.updateRulesForTextFields();
        this.syncDataWithInterval();
    },
    methods: {
        initFormLayoutDisplayMap()
        {
            this.formLayoutDisplayMap[ '1 Col' ] = 'rbsm-form-col1';
            this.formLayoutDisplayMap[ '2 Cols' ] = 'rbsm-form-col2';
            this.formLayoutDisplayMap[ '' ] = '';
        },
        isStorageAvailable()
        {

            let storage;
            try
            {
                storage = window[ 'localStorage' ];
                storage.setItem( '__rbStorageSet', 'x' );
                storage.getItem( '__rbStorageSet' );
                storage.removeItem( '__rbStorageSet' );
                return true;
            } catch( e )
            {
                return false;
            }
        },
        setStorage( key, data )
        {
            this.yesStorage && localStorage.setItem( key, typeof data === 'string' ? data : JSON.stringify( data ) );

        },
        getStorage( key, defaultValue )
        {

            if( !this.yesStorage ) return null;

            const data = localStorage.getItem( key );
            if( data === null ) return defaultValue;

            try
            {
                return JSON.parse( data );
            } catch( e )
            {
                return data;
            }
        },
        deleteStorage( key )
        {
            this.yesStorage && localStorage.removeItem( key );
        },
        getFormSettings()
        {
            const formSettingsRaw = rbSubmissionForm?.formSettings ?? undefined;
            if( formSettingsRaw === undefined )
            {
                console.log( 'Error: Cannot find submission form setting.' );
                return;
            }

            this.formSettings = JSON.parse( formSettingsRaw.data );
            this.amountUploadMedia = this.formSettings?.amount_upload_media ?? 10;
            this.editorType = this.formSettings?.form_fields?.editor_type ?? 'Rich Editor';
            this.allowDisplayTitle = this.formSettings?.form_fields?.post_title !== 'Disable';
            this.allowDisplayFeaturedImage = this.formSettings?.form_fields?.featured_image?.status !== 'Disable';
            this.allowDisplayExcerpt = this.formSettings?.form_fields?.tagline !== 'Disable';
            this.allowDisplayUserName = this.formSettings?.form_fields?.user_name !== 'Disable';
            this.allowDisplayUserEmail = this.formSettings?.form_fields?.user_email !== 'Disable';
            this.allowDisplayCategories = this.formSettings?.allow_categories ?? true;
            this.allowDisplayTags = this.formSettings?.allow_tags ?? true;
            this.allowAddNewTags = this.formSettings?.form_fields?.tags?.allow_add_new_tag ?? false;
            this.allowMultipleCategories = this.formSettings?.form_fields?.categories?.multiple_categories ?? false;
            this.allowMultipleTags = this.formSettings?.form_fields?.tags?.multiple_tags ?? false;
            this.customFields = this.formSettings?.form_fields?.custom_field ?? [];
            this.featuredImageButtonLabel = this.translate.featuredButtonLabel;
            this.isUsingRecaptcha = this.formSettings?.security_fields?.recaptcha?.status ?? false;
            this.recaptchaSiteKey = this.formSettings?.security_fields?.recaptcha?.recaptcha_site_key ?? '';
            this.isUsingChallenge = this.formSettings?.security_fields?.challenge?.status ?? false;
            this.challengeQuestion = this.formSettings?.security_fields?.challenge?.question ?? '';
            this.challengeResponse = this.formSettings?.security_fields?.challenge?.response ?? '';
            this.formLayoutDisplayType = this.formSettings?.general_setting?.form_layout_type ?? '';
            this.formLayoutClass = this.formLayoutDisplayMap[ this.formLayoutDisplayType ] ?? '';

            this.isVerifiedRecaptcha = !this.isUsingRecaptcha;
            this.isInvalidAnswerChallenge = this.isUsingChallenge;

            const isUserLogged = rbSubmissionForm?.isUserLogged ?? false;
            if( isUserLogged )
            {
                this.allowDisplayUserName = false;
                this.allowDisplayUserEmail = false;
            }
        },
        syncDataWithInterval()
        {
            setInterval( () =>
            {
                this.syncDataToLocalStorage();
            }, 3000 );
        },
        initQuillEditor()
        {
            this.quill = new Quill( '#editor', {
                theme: 'snow',
                placeholder: this.translate.textAreaFormPlaceholder,
                modules: {
                    toolbar: this.editorType === 'Rich Editor'
                        ? [
                            [ { header: [ 2, 3, 4, 5, 6, false ] } ],
                            this.amountUploadMedia > 0 ?
                                [ 'image', 'bold', 'italic', 'underline', 'strike' ] :
                                [ 'bold', 'italic', 'underline', 'strike' ],
                            [ { list: 'ordered' }, { list: 'bullet' } ],
                            [ 'link', 'blockquote', 'code-block' ],
                            [ { align: [] } ],
                            [ { 'indent': '-1' }, { 'indent': '+1' } ],
                            [ { direction: 'rtl' } ]
                        ]
                        : []
                }
            } );

            this.quill.on( 'text-change', () =>
            {
                this.content = this.quill.root.innerHTML;
            } );
        },
        updateEditorHeight()
        {
            const editorContainer = document.querySelector( '#editorContainer' );
            const richEditorContainer = document.querySelector( '#richEditorContainer' );
            const rbTextAreaEditor = document.querySelector( '#rbTextAreaEditor' );

            editorContainer.style.height = `${this.getFitHeightForEditor() + 100}px`;
            richEditorContainer.style.height = `${this.getFitHeightForEditor()}px`;
            rbTextAreaEditor.style.height = `${this.getFitHeightForEditor() + 100}px`;
        },
        getFitHeightForEditor()
        {
            let propertiesColHeight = 354;
            propertiesColHeight += this.formSettings[ 'form_fields' ][ 'featured_image' ][ 'status' ] !== 'Disable' ? 367 : 0;
            propertiesColHeight += this.formSettings[ 'form_fields' ][ 'user_email' ] !== 'Disable' ? 156 : 0;
            propertiesColHeight += this.formSettings[ 'form_fields' ][ 'user_name' ] !== 'Disable' ? 156 : 0;
            return Math.max( propertiesColHeight - 156, 500 );
        },
        updateRulesForTextFields()
        {
            this.rules.title = this.formSettings?.form_fields?.post_title === 'Require' ? ( value => !!value || this.translate.titleRequiredRule ) : true;
            this.rules.excerpt = this.formSettings?.form_fields?.tagline === 'Require' ? ( value => !!value || this.translate.excerptRequiredRule ) : true;
            this.rules.userName = this.formSettings?.form_fields?.user_name === 'Require' ? ( value => !!value || this.translate.userNameRequiredRule ) : true;
            this.rules.userEmail = this.formSettings?.form_fields?.user_email === 'Require' ? ( value => !!value || this.translate.userEmailRequiredRule ) : true;
        },
        tryRenderUserPostContent()
        {
            this.initHandlingLocalStorage();

            const userPosts = rbLocalizeData.userPost;
            if( ( this.userPostFromLocalStorage === undefined ) || ( userPosts.length > 0 && this.isFromPostManager ) )
            {
                this.title = this.allowDisplayTitle ? userPosts[ 0 ].title : '';
                this.excerpt = this.allowDisplayExcerpt ? userPosts[ 0 ].excerpt : '';
                this.content = userPosts[ 0 ].content;
                this.content = this.formattedContent( this.content );
                this.categoriesSelected = userPosts[ 0 ]?.categories?.map( category => category ) ?? [];
                this.tagsSelected = userPosts[ 0 ]?.tags?.map( tag => tag ) ?? [];
                this.postId = userPosts[ 0 ].post_id || null;
                this.userNameInput = this.allowDisplayUserName ? userPosts[ 0 ].user_name : '';
                this.userEmailInput = this.allowDisplayUserEmail ? userPosts[ 0 ].user_email : '';

                this.handleRenderFeaturedImage( userPosts[ 0 ].featured_image, userPosts[ 0 ].featured_image_size );
                this.updateCustomFieldContents( userPosts[ 0 ].custom_fields );

                this.quill.root.innerHTML = this.content;
                this.submitButtonText = this.translate.updatePostLabel;
                this.isEditPostMode = true;
                this.changeTabName();
            } else
            {
                this.tryLoadDataFromLocalStorage();
            }

            this.updateFeaturedInputLabel();
        },
        initHandlingLocalStorage()
        {
            this.isFromPostManager = this.getStorage( 'rbsm_is_from_post_manager', false );
            if( this.isFromPostManager )
            {
                this.deleteStorage( 'rbsm_is_from_post_manager' );
            }

            const userPosts = rbLocalizeData.userPost;
            if( userPosts.length > 0 )
            {
                this.postId = userPosts[ 0 ]?.post_id ?? null;
            }

            this.userPostLocalStorageKey = this.postId === null ? 'rbsm_client_user_post' : `rbsm_client_user_post_${this.postId}`;
            this.userPostFromLocalStorage = this.getStorage( this.userPostLocalStorageKey, undefined );
        },
        handleRenderFeaturedImage( featuredImageData, featuredImageSize )
        {
            this.imagePreview = this.getImageSrc( featuredImageData )[ 0 ] ?? '';
            if( this.imagePreview !== '' )
            {
                this.featuredImageFile = {};
                const regex = /\/([^\/]+\.[a-zA-Z]{2,})$/;
                const match = this.imagePreview.match( regex );
                if( match )
                {
                    this.featuredImageFile.name = match[ 1 ];
                    this.featuredImageFile.size = featuredImageSize;
                }
            }
        },
        changeTabName()
        {
            this.$emit( 'change-tab-label-to-edit' );
        },
        getImageSrc( imageStrData )
        {
            const srcRegex = /<img[^>]+src="([^">]+)"/g;
            const matches = [ ...imageStrData.matchAll( srcRegex ) ];
            return matches.map( match => match[ 1 ] );
        },
        getCategories()
        {
            const categoriesRaw = rbSubmissionForm?.categories ?? [];
            categoriesRaw.forEach( category =>
            {
                this.categories.push( {
                    title: category.name,
                    value: category.term_id
                } );
            } );
        },
        getTags()
        {
            const tagsRaw = rbSubmissionForm?.tags ?? [];
            tagsRaw.forEach( tag =>
            {
                this.tags.push( tag.name );
            } );
        },
        getAmountImagesUploaded()
        {
            const images = this.quill.root.querySelectorAll( 'img' );

            return images.length;
        },
        verifyAmountImageUploaded()
        {
            return this.getAmountImagesUploaded() <= this.amountUploadMedia;
        },
        async submitPost()
        {
            const validateInputs = await this.validateInputs();
            if( !validateInputs.status )
            {
                this.displayFailedValidationSnackbar( validateInputs.message );
                return;
            }

            if( this.isUsingRecaptcha && !this.isVerifiedRecaptcha )
            {
                this.displayFailedDialog( this.translate.verifyFailedRecaptchaMessage );
                return;
            }

            this.tryDisplayChallengeDialog();

            if( this.isInvalidAnswerChallenge ) return;

            if( !this.isVerifiedRecaptcha )
            {
                this.displayFailedDialog( this.translate.verifyFailedRecaptchaMessage );
                return;
            }

            if( !this.verifyAmountImageUploaded() )
            {
                this.displayDialog( {
                    dialogTitle: this.translate.maxAmountImages,
                    dialogMessage: this.translate.maxAmountImagesMessage.replace( '%s', this.amountUploadMedia ),
                    prependIcon: 'mdi-alert'
                } );

                return;
            }

            this.displaySubmitPostAnimation();
            const customFieldsData = this.getDataCustomFields();

            const jsonData = {
                title: this.title,
                excerpt: this.excerpt,
                categories: ( typeof this.categoriesSelected === 'string' || Number.isInteger( this.categoriesSelected ) ) ? [ this.categoriesSelected ] : this.categoriesSelected,
                tags: this.tagsSelected,
                content: this.content,
                formId: rbSubmissionForm.formId,
                customFieldsData,
                postId: this.postId,
                isRemoveFeaturedImage: this.isRemoveFeaturedImage,
                userName: this.userNameInput,
                userEmail: this.userEmailInput,
                recaptchaResponse: this.recaptchaResponse
            };

            const ajaxAction = this.postId === null ? 'rbsm_submit_post' : 'rbsm_update_post';

            const formData = new FormData();
            formData.append( 'image', this.featuredImageFile );
            formData.append( 'action', ajaxAction );
            formData.append( 'data', JSON.stringify( jsonData ) );
            formData.append( '_nonce', rbLocalizeData.nonce );

            try
            {
                response = await fetch( rbLocalizeData.ajaxUrl, {
                    headers: {
                        'X-WP-Nonce': rbLocalizeData.nonce
                    },
                    method: 'POST',
                    body: formData,
                } );

                const data = await response.json();
                if( data.success )
                {
                    this.hideSubmitPostAnimation();
                    this.displaySuccessDialog();
                    this.tryDirectLink();
                    this.deleteStorage( this.userPostLocalStorageKey );
                    this.yesStorage = false;

                } else
                {
                    this.hideSubmitPostAnimation();
                    this.displayFailedDialog( data.data );
                    this.resetRecaptcha();

                    if( this.isUsingRecaptcha ) this.isVerifiedRecaptcha = false;
                }

            } catch( err )
            {
                this.hideSubmitPostAnimation();
                this.displayFailedDialog( err );
                this.resetRecaptcha();
                if( this.isUsingRecaptcha ) this.isVerifiedRecaptcha = false;
            }
        },
        async validateInputs()
        {
            let invalidFieldNames = [];

            if( this.$refs.titleTextRef !== undefined )
            {
                const titleValidate = await this.$refs.titleTextRef.validate();
                if( this.rules.title !== true && titleValidate.length !== 0 )
                {
                    invalidFieldNames.push( this.translate.title );
                }
            }

            if( this.$refs.excerptTextRef !== undefined )
            {
                const excerptValidate = await this.$refs.excerptTextRef.validate();
                if( this.rules.excerpt !== true && excerptValidate.length !== 0 )
                {
                    invalidFieldNames.push( this.translate.excerpt );
                }
            }

            if( this.$refs.userNameTextRef !== undefined )
            {
                const userNameValidate = await this.$refs.userNameTextRef.validate();
                if( this.rules.userName !== true && userNameValidate.length !== 0 )
                {
                    invalidFieldNames.push( this.translate.userName );
                }
            }

            if( this.$refs.userEmailTextRef !== undefined )
            {
                const userEmaiValidate = await this.$refs.userEmailTextRef.validate();
                if( this.rules.userEmail !== true && userEmaiValidate.length !== 0 )
                {
                    invalidFieldNames.push( this.translate.userEmail );
                }
            }

            if( invalidFieldNames.length > 0 )
            {
                message = invalidFieldNames.join( ', ' ) + this.translate.isMissing;

                return ( {
                    status: false,
                    message
                } );
            }

            return ( {
                status: true
            } );
        },
        displayFailedValidationSnackbar( message )
        {
            this.snackbarClass = 'rbsm-failed-snackbar';
            this.snackbarMessage = message;
            this.snackbar = true;
        },
        displaySubmitPostAnimation()
        {
            this.isDisplayProgressCircular = true;
            this.isDisableSubmitButton = true;
            this.submitButtonText = this.isEditPostMode ? this.translate.updating : this.translate.submitting;
        },
        hideSubmitPostAnimation()
        {
            this.isDisplayProgressCircular = false;
            this.isDisableSubmitButton = false;
            this.submitButtonText = this.isEditPostMode ? this.translate.updatePostLabel : this.translate.submitPostLabel;
        },
        handleFileChange()
        {
            const file = this.featuredImageFile ? this.featuredImageFile : null;
            if( file )
            {
                const reader = new FileReader();

                reader.onload = e =>
                {
                    this.imagePreview = e.target.result;
                    this.updateFeaturedInputLabel();
                };

                reader.onerror = e =>
                {
                    console.log( e );
                };

                reader.readAsDataURL( file );
            } else
            {
                this.imagePreview = null;
            }

            this.updateFeaturedInputLabel();
        },
        updateFeaturedInputLabel()
        {
            this.featuredInputLabel = ( this.imagePreview === '' || this.imagePreview === null )
                ? ( this.featuredImageButtonLabel ? this.featuredImageButtonLabel : this.translate.chooseFeaturedImage )
                : this.translate.editFeaturedImage;
        },
        updateCustomFieldContents( customFieldsData )
        {
            this.customFields.forEach( ( customField, index ) =>
            {
                const matchIndex = customFieldsData.findIndex( customFieldData => customFieldData.type === customField[ 'field_type' ] );

                if( matchIndex !== -1 )
                {
                    this.customFieldsContent[ index + '_' + customField[ 'field_type' ] ] = customFieldsData[ matchIndex ].content;
                    customFieldsData.splice( matchIndex, 1 );
                }
            } );
        },
        getDataCustomFields()
        {
            const customFieldsData = [];
            this.customFields.forEach( ( customField, index ) =>
            {
                customFieldsData.push( {
                    content: this.customFieldsContent[ index + '_' + customField[ 'field_type' ] ],
                    label: customField[ 'custom_field_label' ],
                    name: customField[ 'custom_field_name' ],
                    type: customField[ 'field_type' ]
                } );
            } );

            return customFieldsData;
        },
        displaySuccessDialog()
        {
            let dialogMessage;

            if( this.formSettings[ 'general_setting' ][ 'success_message' ] !== '' )
                dialogMessage = this.formSettings[ 'general_setting' ][ 'success_message' ];
            else
                dialogMessage = this.isEditPostMode
                    ? this.translate.updatePostSuccessMessage.replace( '%s', this.title )
                    : this.translate.submitPostSuccessMessage.replace( '%s', this.title );

            const dialogData = ( {
                dialogTitle: this.isEditPostMode ? this.translate.updatePostSuccessTitle : this.translate.submitPostSuccessTitle,
                dialogMessage,
                prependIcon: 'mdi-content-save-check-outline'
            } );

            this.displayDialog( dialogData );
        },
        displayFailedDialog( message )
        {
            let dialogMessage;

            if( this.formSettings[ 'general_setting' ][ 'error_message' ] !== '' )
                dialogMessage = `${this.formSettings[ 'general_setting' ][ 'error_message' ]}: ${message}`;
            else
                dialogMessage = message;

            const dialogData = ( {
                dialogTitle: this.isEditPostMode ? this.translate.updatePostFailedTitle : this.translate.submitPostFailedTitle,
                dialogMessage,
                prependIcon: 'mdi-alert-circle-check-outline'
            } );

            this.displayDialog( dialogData );
        },
        displayDialog( { dialogTitle, dialogMessage, prependIcon } )
        {
            this.dialogTitle = dialogTitle;
            this.dialogMessage = dialogMessage;
            this.isDisplayDialog = true;
            this.prependIcon = prependIcon;
        },
        tryDirectLink()
        {
            if( this.formSettings[ 'general_setting' ][ 'url_direction' ] === '' ) return;

            this.doDirectLinkAfterDissmisDialog = true;
        },
        onDialogClicked()
        {
            this.isDisplayDialog = false;
            if( this.doDirectLinkAfterDissmisDialog )
            {
                this.doDirectLinkAfterDissmisDialog = false;
                window.location.href = this.formSettings[ 'general_setting' ][ 'url_direction' ];
            }
        },
        handleClearFeaturedImage()
        {
            this.imagePreview = null;
            this.updateFeaturedInputLabel();
            this.isRemoveFeaturedImage = true;
        },
        handleVerification( response )
        {
            this.isVerifiedRecaptcha = true;
            this.recaptchaResponse = response;
        },
        onExpired()
        {
            this.isVerifiedRecaptcha = false;
        },
        tryDisplayChallengeDialog()
        {
            if( this.isUsingChallenge && this.isInvalidAnswerChallenge )
            {
                this.isDisplayChallengeDialog = true;
            }
        },
        onChallengeSubmit()
        {
            if( this.challengeInput === this.challengeResponse )
            {
                this.isDisplayChallengeDialog = false;
                this.isInvalidAnswerChallenge = false;
                this.isDisplayInvalidAnswerChallengeText = false;
                this.submitPost();
            } else
            {
                this.isInvalidAnswerChallenge = true;
                this.isDisplayInvalidAnswerChallengeText = true;
            }
        },
        resetRecaptcha()
        {
            const recaptchaComponent = this.$refs.recaptchaComponent;

            if( recaptchaComponent )
                recaptchaComponent.resetRecaptcha();
        },
        onChallengeClose()
        {
            this.isDisplayInvalidAnswerChallengeText = false;
            this.challengeInput = '';
        },
        tryLoadDataFromLocalStorage()
        {
            let userPostData;
            try
            {
                userPostData = this.getStorage( this.userPostLocalStorageKey, undefined );
                if( userPostData === null || userPostData === undefined ) return;
            } catch( err )
            {
                this.deleteStorage( this.userPostLocalStorageKey );
                return;
            }

            if( !Array.isArray( userPostData?.categories ?? '' ) )
            {
                userPostData.categories = [ userPostData.categories ];
            }

            this.title = userPostData?.title ?? '';
            this.excerpt = userPostData?.excerpt ?? '';
            this.content = userPostData?.content ?? '';
            this.categoriesSelected = userPostData?.categories?.map( category => category ) ?? [];
            this.tagsSelected = userPostData?.tags?.map( tag => tag ) ?? [];
            this.userNameInput = userPostData?.userName ?? '';
            this.userEmailInput = userPostData?.userEmail ?? '';
            this.updateCustomFieldContents( userPostData.custom_fields );
            this.quill.root.innerHTML = this.content;
        },
        syncDataToLocalStorage()
        {
            const userPostData = {
                title: this.title,
                excerpt: this.excerpt,
                content: this.content,
                categories: typeof this.categoriesSelected === 'string' ? [ this.categoriesSelected ] : this.categoriesSelected,
                tags: this.tagsSelected,
                userName: this.userNameInput,
                userEmail: this.userEmailInput,
                custom_fields: this.getDataCustomFields()
            };

            this.setStorage( this.userPostLocalStorageKey, JSON.stringify( userPostData ) );
        },
        formattedContent( content )
        {
            let cleanedContent = content.replace( /<p>\s*(<br\s*\/?>\s*)*<\/p>/g, '' );
            cleanedContent = cleanedContent.replace( /<figure[^>]*>(.*?)<\/figure>/g, '$1' );

            return cleanedContent.trim();
        },
        onCategoriesClear()
        {
            this.categoriesSelected = [];
        }
    },
    template: `
    <v-container class="rbsm-snackbar-container">
        <v-snackbar v-model="snackbar" :class="[snackbarClass]" :timeout="3000">
            <v-icon class="pr-2">mdi-alert-outline</v-icon>{{ snackbarMessage }}
        </v-snackbar>
        <v-dialog class="rbsm-popup-box" v-model="isDisplayDialog" persistent>
            <v-card>
                <v-card-title>  <v-icon>{{ prependIcon ? prependIcon : mdiAlertCircleCheckOutline }}</v-icon>{{ dialogTitle }}</v-card-title>
                <v-card-text>{{dialogMessage}}</v-card-text>
                <v-card-actions>
                    <v-btn :text="translate.ok" @click="onDialogClicked"></v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog class="rbsm-popup-box" v-model="isDisplayChallengeDialog" @update:model-value="onChallengeClose">
            <v-card>
                <v-card-title><v-icon>mdi-progress-question</v-icon>{{ translate.challengeTitle }}</v-card-title>
                <v-card-text>{{ translate.challengeLabel }} {{challengeQuestion}}</v-card-text>
                <v-text-field :rules="[rules.challenge]" v-model="challengeInput" variant="outlined" label="Answer"></v-text-field>
                <span v-if="isDisplayInvalidAnswerChallengeText" class="text-error"> {{ translate.invalidAnswer }}</span>
                <v-card-actions>
                    <v-btn class="is-btn" @click="onChallengeSubmit">{{ translate.submit }}</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <div :class="['rbsm-form-wrap', formLayoutClass]">
            <div class="rbsm-form-main rbsm-form-list">
                <div class="rbsm-input-wrap" v-if="allowDisplayUserName">
                    <h2 class="rbsm-input-title">{{ translate.yourName }}</h2>
                    <p class="rbsm-input-hind">{{ translate.hindYourName }}</p>
                    <v-text-field
                        :rules="[rules.userName]"
                        v-model="userNameInput"
                        variant="outlined"
                        ref="userNameTextRef"
                        :label="translate.yourName">
                    </v-text-field>
                </div>
                <div class="rbsm-input-wrap" v-if="allowDisplayUserEmail">
                    <h2 class="rbsm-input-title">{{ translate.yourEmail }}</h2>
                    <p class="rbsm-input-hind">{{ translate.hindYourEmail }}</p>
                    <v-text-field :rules="[rules.userEmail]"
                        v-model="userEmailInput"
                        variant="outlined"
                        ref="userEmailTextRef"
                        :label="translate.yourEmail">
                    </v-text-field>
                </div>
                <div class="rbsm-input-wrap" v-if="allowDisplayTitle">
                    <h2 class="rbsm-input-title">{{ translate.addPostTitle }}</h2>
                    <p class="rbsm-input-hind">{{ translate.hindPostTitle }}</p>
                    <v-text-field
                        :rules="[rules.title]"
                        v-model="title"
                        :validate-on-blur="true"
                        :label="translate.addPostTitle"
                        variant="outlined"
                        ref="titleTextRef"
                    ></v-text-field>
                </div>
                <div class="rbsm-input-wrap" v-if="allowDisplayExcerpt">
                    <h2 class="rbsm-input-title">{{ translate.addPostExcerpt }}</h2>
                    <p class="rbsm-input-hind">{{ translate.hindPostExcerpt }}</p>
                    <v-text-field
                        :rules="[rules.excerpt]"
                        v-model="excerpt"
                        :label="translate.addPostExcerpt"
                        ref="excerptTextRef"
                        variant="outlined">
                    </v-text-field>
                </div>
                <div class="rbsm-input-wrap">
                     <h2 class="rbsm-input-title"><v-icon>mdi-pencil-ruler-outline</v-icon>{{ translate.addPostContent }}</h2>
                      <p class="rbsm-input-hind">{{ translate.hindPostContent }}</p>
                    <div id="editorContainer">
                        <div id="richEditorContainer" v-show="editorType === 'Rich Editor'"><div id="editor"></div></div>
                        <v-textarea 
                            v-model="content"
                            id="rbTextAreaEditor"
                            :label="translate.textAreaFormPlaceholder" 
                            v-if="editorType === 'RawHTML'" 
                            variant="outlined"
                            rows="25"
                        >
                        </v-textarea>
                    </div>
                </div>
            </div>
            <div class="rbsm-form-sidebar ">
                <div class="rbsm-form-list rbsm-form-border-wrap">
                <div class="rbsm-input-wrap" v-if="allowDisplayCategories">
                    <h2 class="rbsm-input-title">{{ translate.titlePostCategories }}</h2>
                    <p class="rbsm-input-hind">{{ translate.hindPostCategories }}</p>
                    <v-autocomplete
                          clearable
                          chips
                          v-model="categoriesSelected"
                          variant="outlined"
                          :label="translate.choosePostCategories"
                          :items="categories"
                          :title="title"
                          :value="value"
                          :multiple="allowMultipleCategories"
                          @click:clear="onCategoriesClear"
                        />
                    </v-autocomplete>
                </div>
                <div class="rbsm-input-wrap" v-if="allowDisplayTags">
                    <h2 class="rbsm-input-title">{{ translate.titlePostTags }}</h2>
                    <p class="rbsm-input-hind">{{ translate.hindPostTags }}</p>
                    <v-combobox
                        v-if="allowAddNewTags"
                        clearable
                        chips
                        :multiple="allowMultipleTags"
                        v-model="tagsSelected"
                        variant="outlined"
                        :label="translate.addPostTags"
                        :items="tags"
                    ></v-combobox>
                    <v-autocomplete
                        v-if="!allowAddNewTags"
                        clearable
                        chips
                        v-model="tagsSelected"
                        variant="outlined"
                        :label="translate.choosePostTags"
                        :items="tags"
                        :multiple="allowMultipleTags"
                    ></v-autocomplete>
                </div>
                <div class="rbsm-input-wrap" v-if="allowDisplayFeaturedImage">
                    <h2 class="rbsm-input-title"><v-icon>mdi-image-outline</v-icon>{{ translate.titlePostFeatured }}</h2>
                    <p class="rbsm-input-hind">{{ translate.hindPostFeatured }}</p>
                      <v-form Vue.ref="form">
                        <!-- Image preview -->
                        <v-img
                            v-if="imagePreview"
                            :src="imagePreview"
                        ></v-img>
                        <v-file-input
                            density="compact"
                            v-model="featuredImageFile"
                            :label="featuredInputLabel"
                            accept="image/*"
                            @change="handleFileChange"
                            variant="outlined"
                            prepend-icon="mdi-image-plus-outline"
                            :show-size="1000"
                            @click:clear="handleClearFeaturedImage"
                        ></v-file-input>
                    </v-form>
                </div>
                 <div class="rbsm-input-wrap" v-for="(field, index) in customFields">
                     <h2 class="rbsm-input-title"><v-icon>mdi-tag-edit-outline</v-icon>{{ field.custom_field_label }}</h2>
                    <v-text-field
                        density="compact"
                        v-if="field.field_type === 'Text'"
                        v-model="customFieldsContent[index + '_Text']"
                        label="Type anything"
                        variant="outlined"
                    ></v-text-field>
                    <v-textarea
                        v-if="field.field_type === 'Textarea'"
                        v-model="customFieldsContent[index + '_Textarea']"
                        label="Message"
                        variant="outlined"
                    ></v-textarea>
                    <v-file-input
                        density="compact"
                        v-if="field.field_type === 'Upload'"
                        v-model="customFieldsContent[index + '_Upload']"
                        label="Upload file"
                        outlined
                        variant="outlined"
                    ></v-file-input>
                </div>
              </div>
            </div>
            <div class="rbsm-form-footer rbsm-form-list">
                <v-row v-if="isUsingRecaptcha">
                    <v-col>
                        <recaptchaContent
                            :shouldLoadRecaptcha="isUsingRecaptcha"
                            :siteKey="recaptchaSiteKey"
                            @verified="handleVerification"
                            @data-expired-callback="onExpired"
                            ref="recaptchaComponent"
                        />
                    </v-col>
                </v-row>
                <div class="rbsm-from-submit">
                    <button class="rbsm-submit-btn rbsm-btn-primary is-btn" @click="submitPost" :disabled="isDisableSubmitButton">{{ submitButtonText }}<v-icon>mdi-arrow-right-thin</v-icon></button>
                    <v-progress-circular indeterminate size="26" v-show="isDisplayProgressCircular"></v-progress-circular>
                </div>
            </div>
        </div>
    </v-container>
        `
} );