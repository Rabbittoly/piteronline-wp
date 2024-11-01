var rbAjax;
const formContent = Vue.defineComponent( {
    name: 'formContent',
    data()
    {
        return {
            snackbarClass: Vue.ref( 'rbsm-snackbar' ),
            formShouldRemoveId: -1,
            formTitleInputValue: '',
            formsData: Vue.ref( [] ),
            snackbar: Vue.ref( false ),
            dialogTitle: Vue.ref( '' ),
            dialogMessage: Vue.ref( '' ),
            snackbarMessage: Vue.ref( '' ),
            snackbarIcon: Vue.ref( 'mdi-delete-empty-outline' ),
            isCreatingForm: Vue.ref( false ),
            isDisplayDialog: Vue.ref( false ),
            hasFormJustCreate: Vue.ref( false ),
            translate: Vue.ref( rbAjax.translate ),
            isShowFormTitleInput: Vue.ref( false ),
            shouldDisplayTitleWarning: Vue.ref( false ),
            isDisplayDeleteConfirmationDialog: Vue.ref( false ),
            defaultAuthorAssign: Vue.ref( {
                name: '',
                id: ''
            } ),
            rules: {
                required: value => !!value || rbAjax.translate.createFormInputRequired,
            },
        }
    },
    props: {
        shouldUpdateData: {
            type: Boolean,
            default: false
        }
    },
    watch: {
        shouldUpdateData()
        {
            this.getAllForms();
            this.$emit( 'update-data-completed' );
        }
    },
    async mounted()
    {
        this.getAllForms();
        this.defaultAuthorAssign = await this.getDefaultAuthorPostAssigned();
    },
    methods: {
        getAllForms()
        {
            const formData = new FormData();
            formData.append( 'action', 'rbsm_get_forms' );
            formData.append( '_nonce', rbAjax.nonce );

            fetch( rbAjax.ajaxUrl, {
                method: 'POST',
                body: formData
            } )
                .then( response => response.json() )
                .then( data =>
                {
                    if( data.success )
                    {
                        this.formsData = data.data;
                    } else
                    {
                        this.formsData = [];
                    }
                } )
                .catch( error =>
                {
                    this.displayErrorDialog( error );
                    console.log( error );
                } );
        },
        async createDefaultFormSettings()
        {

            return {
                "general_setting": {
                    "post_status": "Draft", // ["Draft", "Pending Review", ""]
                    "url_direction": "",
                    "success_message": this.translate.successMessagePattern,
                    "error_message": this.translate.errorMessagePattern,
                    "unique_title": true,
                    "form_layout_type": '2 Cols', // ["1 Col", "2 Cols"]
                },
                "user_login": {
                    "author_access": "Allow Guess", // ["Allow Guess", "Only Logged User"]
                    "assign_author": this.defaultAuthorAssign.name,
                    "assign_author_id": this.defaultAuthorAssign.id,
                    "login_link_url": "",
                    "login_type": {
                        "type": "Show Login Message", // ["Show Login Message", "Redirect To Login Page"]
                        "login_message": this.translate.loginMessagePattern,
                        "login_link_label": this.translate.loginLinkLabelPattern,
                        "required_login_title": this.translate.requiredLoginTitlePattern,
                        "required_login_title_desc": this.translate.requiredLoginTitleDescPattern,
                        "register_link": "",
                        "register_button_label": this.translate.register,
                    }
                },
                "form_fields": {
                    "user_name": "Require", // ["Require", "Optional", "Disable"]
                    "user_email": "Require", // ["Require", "Optional", "Disable"]
                    "post_title": "Require", // ["Require", "Optional", "Disable"]
                    "tagline": "Require", // ["Require", "Optional", "Disable"]
                    "editor_type": "Rich Editor", //["Rich Editor", "RawHTML", "HTML Editor"]
                    "featured_image": {
                        "status": "Require", // ["Require", "Optional", "Disable"]
                        "upload_file_size_limit": 0,
                        "default_featured_image": ""
                    },
                    "categories": {
                        "multiple_categories": true,
                        "exclude_categories": [],
                        "exclude_category_ids": [],
                        "auto_assign_categories": [],
                        "auto_assign_category_ids": []
                    },
                    "tags": {
                        "multiple_tags": true,
                        "allow_add_new_tag": true,
                        "exclude_tags": [],
                        "exclude_tag_ids": [],
                        "auto_assign_tags": [],
                        "auto_assign_tag_ids": []
                    },
                    "custom_field": [
                        // {
                        //     "custom_field_name": "",
                        //     "custom_field_label": "",
                        //     "field_type": "Text" //["Text", "TextArea", "Upload"]
                        // }
                    ]
                },
                "security_fields": {
                    "challenge": {
                        "status": false,
                        "question": "",
                        "response": ""
                    },
                    "recaptcha": {
                        "status": false,
                        "recaptcha_site_key": "",
                        "recaptcha_secret_key": ""
                    }
                },
                "email": {
                    "admin_mail": {
                        "status": false,
                        "email": "",
                        "subject": this.translate.emailAdminSubjectPattern,
                        "title": this.translate.emailAdminTitlePattern,
                        "message": this.translate.emailAdminMessagePattern
                    },
                    "post_submit_notification": {
                        "status": false,
                        "subject": this.translate.emailPostSubmitSubjectPattern,
                        "title": this.translate.emailPostSubmitTitlePattern,
                        "message": this.translate.emailPostSubmitMessagePattern
                    },
                    "post_publish_notification": {
                        "status": false,
                        "subject": this.translate.emailPostPublishSubjectPattern,
                        "title": this.translate.emailPostPublishTitlePattern,
                        "message": this.translate.emailPostPublishMessagePattern
                    },
                    "post_trash_notification": {
                        "status": false,
                        "subject": this.translate.emailPostTrashSubjectPattern,
                        "title": this.translate.emailPostTrashTitlePattern,
                        "message": this.translate.emailPostTrashMessagepattern
                    }
                }
            };
        },
        getDefaultAuthorPostAssigned()
        {
            return new Promise( ( resolve, reject ) =>
            {
                const formData = new FormData();
                formData.append( 'action', 'rbsm_get_authors' );
                formData.append( '_nonce', rbAjax.nonce );

                fetch( rbAjax.ajaxUrl, {
                    method: 'POST',
                    body: formData
                } )
                    .then( response => response.json() )
                    .then( data =>
                    {
                        let author = {
                            name: '',
                            id: -1
                        };

                        if( data.success )
                        {
                            this.authors = data.data;
                            if( this.authors.length > 0 )
                                author = {
                                    name: this.authors[ 0 ].display_name,
                                    id: this.authors[ 0 ].ID
                                };

                            resolve( author );
                        }
                        else
                        {
                            resolve( author );
                        }
                    } )
                    .catch( error =>
                    {
                        this.displayErrorDialog( error );
                        reject();
                    } );
            } );
        },
        async addNewForm()
        {
            if( !this.isShowFormTitleInput )
            {
                this.isShowFormTitleInput = true;
                return;
            }

            if( this.formTitleInputValue === '' ) return;

            if( this.isCreatingForm ) return;

            this.isCreatingForm = true;

            const jsonData = {
                title: this.formTitleInputValue,
                data: await this.createDefaultFormSettings()
            };


            const formData = new FormData();
            formData.append( 'action', 'rbsm_submit_form' );
            formData.append( '_nonce', rbAjax.nonce );
            formData.append( 'data', JSON.stringify( jsonData ) );

            fetch( rbAjax.ajaxUrl, {
                method: 'POST',
                body: formData
            } )
                .then( response => response.json() )
                .then( data =>
                {
                    if( data.success )
                    {
                        this.displayNewFormSuccessDialog( jsonData.title );
                        this.getAllForms();
                        this.isCreatingForm = false;
                        this.hasFormJustCreate = true;
                    } else
                    {
                        console.log( `%c ${data.data}`, 'color: red; font-weight: bold' );
                        this.displayErrorDialog( data.data );
                        this.isCreatingForm = false;
                    }
                } )
                .catch( error =>
                {
                    console.log( error );
                    this.displayErrorDialog( error );
                    this.isCreatingForm = false;
                } );
        },
        displayNewFormSuccessDialog( newFormTitle )
        {
            this.dialogTitle = this.translate.formCreateSuccess;
            this.dialogMessage = this.translate.formCreateSuccessMessage.replace( '%s', newFormTitle );
            this.isDisplayDialog = true;
        },
        displayFormRemovedDialog( formRemovedTitle )
        {
            this.snackbarClass = 'rbsm-snackbar';
            this.snackbarMessage = this.translate.formRemovedMessage.replace( '%s', formRemovedTitle );
            this.snackbarIcon = 'mdi-delete-empty-outline';
            this.snackbar = true;
        },
        displayErrorDialog( errorMessage )
        {
            this.snackbarClass = 'rbsm-failed-snackbar';
            this.snackbarIcon = 'mdi-alert-outline';
            this.snackbarMessage = errorMessage;
            this.snackbar = true;
        },
        cancelSubmitForm()
        {
            this.isShowFormTitleInput = false;
        },
        removeForm()
        {
            if( this.formShouldRemoveId === -1 ) return;

            const jsonData = {
                id: this.formShouldRemoveId
            };

            const formTitle = ( this.formsData.find( element => ( element.id === this.formShouldRemoveId ) ) ).title;

            const formData = new FormData();
            formData.append( 'action', 'rbsm_delete_form' );
            formData.append( '_nonce', rbAjax.nonce );
            formData.append( 'data', JSON.stringify( jsonData ) );

            fetch( rbAjax.ajaxUrl, {
                method: 'POST',
                body: formData
            } )
                .then( response => response.json() )
                .then( data =>
                {
                    if( data.success )
                    {
                        this.displayFormRemovedDialog( formTitle );
                        this.getAllForms();
                    }
                    else
                    {
                        this.displayErrorDialog( data.data );
                    }
                } )
                .catch( error =>
                {
                    this.displayErrorDialog( error );
                    console.log( error );
                } );
        },
        openFormSettings( formItem )
        {
            this.$emit( 'open-form-settings', formItem );
        },
        displayDeleteConfirmationDialog( formId )
        {
            this.formShouldRemoveId = formId;
            this.isDisplayDeleteConfirmationDialog = true;
        },
        cancelDeleteDialog()
        {
            this.formShouldRemoveId = -1;
            this.isDisplayDeleteConfirmationDialog = false;
        },
        confirmDeleteDialog()
        {
            this.isDisplayDeleteConfirmationDialog = false;
            this.removeForm();
        },
        validateFormTitleInput()
        {
            this.shouldDisplayTitleWarning = this.formTitleInputValue === '';
        },
        onDialogClose()
        {
            this.isDisplayDialog = false;

            if( this.hasFormJustCreate )
            {
                this.hasFormJustCreate = false;
                this.formTitleInputValue = '';
                this.isShowFormTitleInput = false;
            }
        }
    },
    template: `
        <div >
            <v-dialog class="rbsm-popup-box" v-model="isDisplayDeleteConfirmationDialog" persistent>
                <v-card>
                    <v-card-title><v-icon>mdi-delete-empty-outline</v-icon>{{ translate.deleteFormTitle }}</v-card-title>
                    <v-card-text>{{ translate.deleteFormText }}</v-card-text>
                    <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn @click="confirmDeleteDialog">{{ translate.delete }}</v-btn>
                    <v-btn @click="cancelDeleteDialog">{{ translate.cancel }}</v-btn>
                    </v-card-actions>
              </v-card>
            </v-dialog>
            <v-dialog class="rbsm-popup-box" v-model="isDisplayDialog" @click:outside="onDialogClose">
                <v-card>
                    <v-card-title><v-icon class="rbsm-green">mdi-bell-check</v-icon>{{dialogTitle}}</v-card-title>
                    <v-card-text>{{dialogMessage}}</v-card-text>
                    <template v-slot:actions>
                        <v-btn class="ms-auto" text="Ok" @click="onDialogClose"></v-btn>
                    </template>
                </v-card>
            </v-dialog>
            <v-row class="ma-0 pa-0">
                <v-col cols="12" class="ma-0 pa-0" xs="12" sm="12" md="12">
                    <v-card class="rbsm-card rbsm-card-center" elevation="0">
                        <h2 class="rbsm-card-title-center">
                            <v-icon>mdi-invoice-plus-outline</v-icon>{{translate.createFormTitle}}
                        </h2>
                        <p class="rbsm-tagline"> {{translate.createFormDesc}}</p>
                        <div class="rbsm-create-form-wrap">
                              <div class="rbsm-big-input-wrap" v-show="isShowFormTitleInput">
                                <input @input="validateFormTitleInput" class="rbsm-big-input" type="text" :placeholder="translate.createFormInputPlaceholder" v-model="formTitleInputValue">
                                <p class="pl-1" v-show="shouldDisplayTitleWarning" style="color: red">{{translate.createFormInputRequired}}</p>
                              </div>
                            <button :disabled="isCreatingForm" class="rbsm-creation-btn rbsm-btn rbsm-transition rbsm-access-btn" @click="addNewForm" >
                                  <v-icon v-show="isCreatingForm" class="rbsm-loading-icon">mdi-loading</v-icon>
                                  <v-icon v-show="!isCreatingForm" >{{isShowFormTitleInput ? 'mdi-content-save' : 'mdi-plus'}}</v-icon>{{isShowFormTitleInput ? translate.submitForm : translate.addNewForm}}
                            </button>
                            <button v-show="isShowFormTitleInput" class="rbsm-btn rbsm-creation-btn is-cancel" @click="cancelSubmitForm">
                                <v-icon>mdi-cancel</v-icon>{{translate.cancel}}
                            </button>
                        </div>
                    </v-card>
                </v-col>
            </v-row>
            <v-row class="ma-0 pa-0" v-show="formsData.length > 0">
                <v-col cols="12" class="ma-0 pa-0">
                    <v-card class="rbsm-card rbsm-snackbar-container" elevation="0">
                        <v-snackbar v-model="snackbar" :class="[snackbarClass]" :timeout="3000">
                            <v-icon class="pr-2">{{snackbarIcon}}</v-icon>{{ snackbarMessage }}
                        </v-snackbar>
                        <div class="rbsm-card-heading">
                            <h3 class="rbsm-card-title"><v-icon>mdi-format-list-bulleted</v-icon>{{translate.formListTitle}}</h3>
                        </div>
                        <div v-for="(item, index) in formsData">
                            <v-row class="ma-0 pa-0">
                                <v-col cols="12" md="8" sm="12" class="ma-0 pa-0" >
                                    <h3 class="rbsm-form-name"><v-icon>mdi-list-box-outline</v-icon>{{ item.title }}</h3>
                                    <p class="rbsm-form-code"> [ruby_submission_form id={{item.id}}] </p>
                                </v-col>
                                <v-col cols="12" md="4" sm="12" class="ma-0 pa-0 rbsm-form-actions">
                                    <button class="rbsm-btn rbsm-black-btn rbsm-transition rbsm-access-btn" @click="openFormSettings(item)" ><v-icon>mdi-cog-play-outline</v-icon>{{translate.formSettings}}</button>
                                    <button class="rbsm-btn rbsm-white-btn rbsm-transition rbsm-btn-red" @click="displayDeleteConfirmationDialog(item.id)"><v-icon>mdi-delete-outline</v-icon>{{translate.delete}}</button>
                                </v-col>
                            </v-row>
                            <v-divider v-show="index < formsData.length - 1" class="rbsm-divider mb-5"></v-divider>
                        </div>
                    </v-card>
                </v-col>
            </v-row>
        </div>
    `
} );