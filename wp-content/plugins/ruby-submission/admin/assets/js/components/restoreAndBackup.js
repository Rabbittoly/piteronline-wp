var rbAjax;
const restoreAndBackupContent = Vue.defineComponent( {
    name: 'restoreAndBackupContent',
    data()
    {
        return {
            snackbar: Vue.ref( false ),
            snackbarClass: Vue.ref( 'rbsm-failed-snackbar' ),
            snackbarMessage: Vue.ref( '' ),
            ids: Vue.ref( [] ),
            formsData: Vue.ref( [] ),
            postManagerData: Vue.ref( [] ),
            backupRawContent: Vue.ref( {} ),
            dialogTitle: Vue.ref( '' ),
            backupContent: Vue.ref( '' ),
            dialogMessage: Vue.ref( '' ),
            restoreContent: Vue.ref( '' ),
            isCopiedData: Vue.ref( false ),
            isDisplayDialog: Vue.ref( false ),
            translate: Vue.ref( rbAjax.translate ),
            shouldDirectoFormsTab: Vue.ref( false ),
            copyBtnContent: Vue.ref( rbAjax.translate.copy ),
        }
    },
    props: {
        isTabVisible: {
            type: Boolean,
            default: false,
        }
    },
    watch: {
        async isTabVisible()
        {
            if( this.isTabVisible )
            {
                await this.getAllForms();
                await this.getPostManagerData();
                this.backupContent = JSON.stringify( this.backupRawContent );
            }
        }
    },
    async mounted()
    {
        await this.getAllForms();
        await this.getPostManagerData();
        this.backupContent = JSON.stringify( this.backupRawContent );
    },
    methods: {
        getAllForms()
        {
            return new Promise( ( resolve, reject ) =>
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
                            this.formsData = this.cleanJsonData( data.data );
                            this.backupRawContent[ 'formData' ] = this.formsData;
                            resolve();
                        } else
                        {
                            this.formsData = [];
                            this.backupRawContent[ 'formData' ] = this.formData;
                            resolve();
                        }
                    } )
                    .catch( error =>
                    {
                        this.displayErrorDialog( error );
                        console.log( error );
                        reject( err );
                    } );
            } );
        },
        getPostManagerData()
        {
            return new Promise( ( resolve, reject ) =>
            {
                const formData = new FormData();
                formData.append( 'action', 'rbsm_get_post_manager' );
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
                            this.postManagerData = data.data;
                            this.backupRawContent[ 'postManagerData' ] = this.postManagerData;
                            resolve( data.data );
                        }
                        else
                        {
                            this.postManagerData = [];
                            this.backupRawContent[ 'postManagerData' ] = this.postManagerData;
                            resolve( data.data );
                        }
                    } )
                    .catch( error =>
                    {
                        this.displayErrorDialog( error );
                        console.log( error );
                        reject( error );
                    } );
            } );
        },
        onRestoreData()
        {
            this.ids = [];
            if( this.restoreContent === '' ) return;

            try
            {
                const restoreDataParsed = JSON.parse( this.restoreContent );
                const formsDataParsed = restoreDataParsed?.formData ?? '';
                const postManagerDataParsed = restoreDataParsed?.postManagerData ?? '';

                if( formsDataParsed === '' || postManagerDataParsed === '' )
                {
                    this.displayFailedRestoreDialog( this.translate.parseRestoreDataFailed )
                    return;
                }

                const cleanRestoreData = this.getCleanRestoreFormsData( formsDataParsed );
                if( !cleanRestoreData.status )
                {
                    this.displayFailedRestoreDialog( cleanRestoreData.message );
                    return;
                }

                const cleanRestorePostManagerData = this.getClearnRestorePostManagerData( postManagerDataParsed )
                if( !cleanRestorePostManagerData.status )
                {
                    this.displayFailedRestoreDialog( cleanRestorePostManagerData.message );
                    return;
                }

                this.restoreData( cleanRestoreData.result );
                this.restorePostManagerData( cleanRestorePostManagerData.result );
            }
            catch( err )
            {
                this.displayFailedRestoreDialog( err );

            }
        },
        getClearnRestorePostManagerData( postManagerData )
        {
            const editPostForm = postManagerData?.edit_post_form ?? '';
            if( editPostForm === '' )
            {
                return {
                    status: false,
                    message: this.translate.validateFailedPostManager
                }
            }

            const editPostUrl = editPostForm?.edit_post_url ?? undefined;
            const edit_login_action_choice = editPostForm?.login_action_choice ?? undefined;
            const edit_post_required_login_title = editPostForm?.edit_post_required_login_title ?? undefined;
            const edit_post_required_login_message = editPostForm?.edit_post_required_login_message ?? undefined;

            if( editPostUrl === undefined || edit_login_action_choice === undefined
                || edit_post_required_login_title === undefined || edit_post_required_login_message === undefined )
            {
                return {
                    status: false,
                    message: this.translate.validateFailedPostManager
                }
            }

            const userProfile = postManagerData?.user_profile ?? '';
            if( userProfile === '' )
            {
                return {
                    status: false,
                    message: this.translate.validateFailedPostManager
                }
            }

            const allowDeletePost = userProfile?.allow_delete_post ?? undefined;
            const allowEditPost = userProfile?.allow_edit_post ?? undefined;
            const formSubmissionDefaultId = userProfile?.form_submission_default_id ?? undefined;
            const login_action_choice = userProfile?.login_action_choice ?? undefined;
            const user_posts_required_login_title = userProfile?.user_posts_required_login_title ?? undefined;
            const user_posts_required_login_message = userProfile?.user_posts_required_login_message ?? undefined;

            if( allowDeletePost === undefined || allowEditPost === undefined || formSubmissionDefaultId === undefined
                || login_action_choice === undefined || user_posts_required_login_title === undefined || user_posts_required_login_message === undefined
            )
            {
                return {
                    status: false,
                    message: this.translate.validateFailedPostManager
                }
            }

            const custom_login_and_registration = postManagerData?.custom_login_and_registration ?? '';
            if( custom_login_and_registration === '' )
            {
                return {
                    status: false,
                    message: this.translate.validateFailedPostManager
                }
            }

            const custom_login_button_label = custom_login_and_registration?.custom_login_button_label ?? undefined;
            const custom_login_link = custom_login_and_registration?.custom_login_link ?? undefined;
            const custom_registration_button_label = custom_login_and_registration?.custom_registration_button_label ?? undefined;
            const custom_registration_link = custom_login_and_registration?.custom_registration_link ?? undefined;
            if( custom_login_button_label === undefined || custom_login_link === undefined || custom_registration_button_label === undefined
                || custom_registration_link === undefined
            )
            {
                return {
                    status: false,
                    message: this.translate.validateFailedPostManager
                }
            }


            const result = {
                edit_post_form: editPostForm,
                user_profile: userProfile,
                custom_login_and_registration: custom_login_and_registration
            }

            return {
                status: true,
                message: this.translate.validateSuccessPostManager,
                result
            }
        },
        restorePostManagerData( postManagerData )
        {
            const jsonData = postManagerData;

            const formData = new FormData();
            formData.append( 'action', 'rbsm_update_post_manager' );
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
                        this.isDisplayDialog = true;
                    }
                    else
                    {
                        this.displayErrorDialog( data.data );
                        console.log( data.data );
                    }
                } )
                .catch( error =>
                {
                    this.displayErrorDialog( error );
                    console.log( error );
                } );
        },
        restoreData( data )
        {
            const formData = new FormData();
            formData.append( 'action', 'rbsm_restore_data' );
            formData.append( '_nonce', rbAjax.nonce );
            formData.append( 'data', JSON.stringify( data ) );

            fetch( rbAjax.ajaxUrl, {
                method: 'POST',
                body: formData
            } )
                .then( response => response.json() )
                .then( data =>
                {
                    if( data.success )
                    {
                        this.displaySuccessRestoreDialog();
                    }
                    else
                    {
                        this.displayFailedRestoreDialog( data.data );
                    }
                } )
                .catch( error =>
                {
                    this.displayFailedRestoreDialog( error );
                } );
        },
        getCleanRestoreFormsData( restoreDataParsed )
        {
            this.ids = [];
            const result = [];
            let status = true;

            for( const formData of restoreDataParsed )
            {
                const cleanFormData = {};
                const validateId = this.validateId( formData );

                if( !validateId.status )
                {
                    return {
                        status: false,
                        message: validateId.message
                    }
                }
                else
                {
                    cleanFormData.id = validateId.id;
                }

                const validateTitle = this.validateTitle( formData );
                if( !validateTitle.status )
                {
                    return {
                        status: false,
                        message: validateTitle.message
                    }
                }
                else
                {
                    cleanFormData.title = validateTitle.title;
                }

                const validateData = this.validateData( formData );
                if( !validateData.status )
                {
                    return {
                        status: false,
                        message: validateData.message
                    }
                }
                else
                {
                    cleanFormData.data = validateData.data;
                }

                result.push( cleanFormData );
            }

            return { status, result }
        },
        validateId( formData )
        {
            let status = true;
            let message = this.translate.validId;
            const id = Number( formData[ 'id' ] );

            if( isNaN( id ) || id === null || id <= 0 )
            {
                return {
                    status: false,
                    message: this.translate.invalidId,
                    id
                };
            }

            if( this.ids.includes( id ) )
            {
                return {
                    status: false,
                    message: `${this.translate.restoreDataDuplicateKeyErrorMessage} ${id}`,
                    id
                }
            }

            this.ids.push( id );

            return {
                status, message, id
            }
        },
        validateTitle( formData )
        {
            let status = true;
            let message = this.translate.validTitle;
            const title = formData[ 'title' ];

            if( title === undefined || title === null || typeof title !== 'string' )
            {
                return {
                    status: false,
                    message: this.translate.invalidTitle
                }
            }

            return {
                status,
                message,
                title
            };
        },
        validateData( formData )
        {
            let status = true;
            let message = this.translate.validData;
            const data = formData[ 'data' ];

            if( data === undefined || data === null )
            {
                status = false;
                message = this.translate.invalidData;
            }

            return {
                status,
                message,
                data
            };
        },
        cleanJsonData( formsData )
        {
            try
            {
                for( const formData of formsData )
                {
                    const jsonData = JSON.parse( formData[ 'data' ] );
                    formData[ 'data' ] = jsonData;
                }
            }
            catch( err )
            {
                throw err;
            }

            return formsData;
        },
        async onCopyData()
        {
            this.copyBtnContent = this.translate.copied;
            this.isCopiedData = true;
            await navigator.clipboard.writeText( this.backupContent );
        },
        async onPasteData()
        {
            this.restoreContent = await navigator.clipboard.readText();
        },
        displaySuccessRestoreDialog()
        {
            this.shouldDirectoFormsTab = true;
            this.isDisplayDialog = true;
            this.dialogMessage = this.translate.restoreDataSuccessMessage;
            this.dialogTitle = this.translate.restoreDataSuccessTitle;

        },
        displayFailedRestoreDialog( errorMessage )
        {
            this.shouldDirectoFormsTab = false;
            this.isDisplayDialog = true;
            this.dialogMessage = errorMessage;
            this.dialogTitle = this.translate.restoreDataFailedTitle;
        },
        onDialogBtnClick()
        {
            this.isDisplayDialog = false;

            if( this.shouldDirectoFormsTab )
                this.$emit( 'open-form' );
        },
        displayErrorDialog( errorMessage )
        {
            this.snackbarClass = 'rbsm-failed-snackbar';
            this.snackbarMessage = errorMessage;
            this.snackbar = true;
        }
    },
    template: `
        <div class="rbsm-snackbar-container">
            <v-snackbar v-model="snackbar" :class="[snackbarClass]" :timeout="3000">
                <v-icon class="pr-2">mdi-alert-outline</v-icon>{{ snackbarMessage }}
            </v-snackbar>
            <v-dialog class="rbsm-popup-box" v-model="isDisplayDialog">
                <v-card>
                    <v-card-title><v-icon class="rbsm-green">mdi-database-check-outline</v-icon>{{ dialogTitle }}</v-card-title>
                    <v-card-text>{{dialogMessage}}</v-card-text>
                    <template v-slot:actions>
                    <v-btn class="ms-auto" :text="translate.ok" @click="onDialogBtnClick"></v-btn>
                    </template>
                </v-card>
            </v-dialog>
            <v-row>
                <v-col>
                    <v-card class="rbsm-card" elevation="0">
                        <div class="rbsm-card-heading">
                            <div class="rbsm-card-title no-border">
                                <v-icon>mdi-restore</v-icon>{{translate.importSestoreData}}
                            </div>
                        </div>
                        <v-row>
                            <v-col cols="12" class="pt-0">
                                <textarea v-model="restoreContent" class="rbsm-text-area rbsm-text-area-sync-data"></textarea>
                            </v-col>
                            <v-col id="rbsm-sync-data-btn-group" col="12" sm="6" md="6">
                                <button @click="onRestoreData" class="rbsm-black-btn rbsm-transition rbsm-access-btn rbsm-remove-field-btn">
                                    <v-icon>mdi-import</v-icon>{{translate.import}}
                                </button>
                                <button @click="onPasteData" class="rbsm-white-btn rbsm-transition rbsm-normal-btn rbsm-remove-field-btn">
                                    <v-icon>mdi-content-paste</v-icon>{{translate.paste}}
                                </button>
                            </v-col>
                        </v-row>
                    </v-card>
                </v-col>
            </v-row>
            <v-row>
                <v-col>
                    <v-card class="rbsm-card" elevation="0">
                     <div class="rbsm-card-heading">
                            <div class="rbsm-card-title no-border">
                                <v-icon>mdi-export</v-icon>{{translate.backupData}}
                            </div>
                        </div>
                        <v-row>
                            <v-col cols="12" class="pt-0">
                                <textarea readonly v-model="backupContent" class="rbsm-text-area rbsm-text-area-sync-data"></textarea>
                            </v-col>
                            <v-col col="12" sm="2">
                                <button @click="onCopyData" class="rbsm-black-btn rbsm-transition rbsm-access-btn rbsm-remove-field-btn">
                                    <v-icon>mdi-content-copy</v-icon>{{copyBtnContent}}
                                </button>
                            </v-col>
                        </v-row>
                    </v-card>
                </v-col>
            </v-row>
        </div>
    `
} );