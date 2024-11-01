const postManagerContent = Vue.defineComponent( {
    name: 'postManagerContent',
    data()
    {
        return {
            snackbar: Vue.ref( false ),
            snackbarClass: Vue.ref( 'rbsm-failed-snackbar' ),
            snackbarMessage: Vue.ref( '' ),
            yesStorage: false,
            userPostsPanel: Vue.ref( [ 0 ] ),
            editPostFormPanel: Vue.ref( [] ),
            userProfilePanel: Vue.ref( [] ),
            login: Vue.ref( [] ),
            translate: Vue.ref( rbAjax.translate ),
            editPostFormUrl: Vue.ref( '' ),
            allowEditPost: Vue.ref( false ),
            allowDeletePost: Vue.ref( false ),
            formSubmissionDefaultId: Vue.ref( 1 ),
            disableButton: Vue.ref( false ),
            hasSavingSettings: Vue.ref( false ),
            isDisplayDialog: Vue.ref( false ),
            loginTypeItems: Vue.ref( [ 'Show Login Message', 'Redirect to Login Page' ] ),
            loginTypeSelected: Vue.ref( 'Show Login Message' ),
            loginTypePanel: Vue.ref( [ 0 ] ),
            loginUserPostsChoice: Vue.ref( 'Show Login Message' ),
            loginEditPostChoice: Vue.ref( 'Show Login Message' ),
            loginUserPostsChoicePanel: Vue.ref( [] ),
            loginEditPostChoicePanel: Vue.ref( [] ),
            userPostsLoginTitle: Vue.ref( '' ),
            userPostsLoginMessage: Vue.ref( '' ),
            editPostLoginTitle: Vue.ref( '' ),
            editPostLoginMessage: Vue.ref( '' ),
            customLoginButtonText: Vue.ref( '' ),
            customLoginLink: Vue.ref( '' ),
            customRegisterButtonText: Vue.ref( '' ),
            customRegisterLink: Vue.ref( '' )
        }
    },
    props: {
        isTabVisible: {
            type: Boolean,
            default: false,
        }
    },
    created()
    {
        this.yesStorage = this.isStorageAvailable();
        this.userPostsPanel = this.getStorage( 'rbsm_post_manager', '' ).user_profile ?? [ 0 ];
        this.editPostFormPanel = this.getStorage( 'rbsm_post_manager', '' ).edit_post_form ?? [];
    },
    mounted()
    {
        this.getPostManagerSettings();
    },
    watch: {
        async isTabVisible()
        {
            if( this.isTabVisible )
            {
                this.getPostManagerSettings();
            }
        },
        userPostsPanel()
        {
            const value = ( {
                user_profile: this.userPostsPanel,
                edit_post_form: this.editPostFormPanel
            } );
            this.setStorage( 'rbsm_post_manager', value );
        },
        editPostFormPanel()
        {
            const value = ( {
                user_profile: this.userPostsPanel,
                edit_post_form: this.editPostFormPanel
            } );
            this.setStorage( 'rbsm_post_manager', value );
        }
    },
    methods: {
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
        updatePostManagerSettings()
        {
            if( this.hasSavingSettings ) return;

            this.hasSavingSettings = true;

            const jsonData = {
                user_profile: {
                    allow_edit_post: this.allowEditPost,
                    allow_delete_post: this.allowDeletePost,
                    form_submission_default_id: this.formSubmissionDefaultId,
                    login_action_choice: this.loginUserPostsChoice,
                    user_posts_required_login_title: this.userPostsLoginTitle,
                    user_posts_required_login_message: this.userPostsLoginMessage
                },
                edit_post_form: {
                    edit_post_url: this.editPostFormUrl,
                    login_action_choice: this.loginEditPostChoice,
                    edit_post_required_login_title: this.editPostLoginTitle,
                    edit_post_required_login_message: this.editPostLoginMessage
                },
                custom_login_and_registration: {
                    custom_login_button_label: this.customLoginButtonText,
                    custom_login_link: this.customLoginLink,
                    custom_registration_button_label: this.customRegisterButtonText,
                    custom_registration_link: this.customRegisterLink
                }
            }

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
                        this.hasSavingSettings = false;
                        this.isDisplayDialog = true;
                    }
                    else
                    {
                        this.displayErrorDialog( data.data );
                        console.log( data.data );
                        this.hasSavingSettings = false;
                    }
                } )
                .catch( error =>
                {
                    this.displayErrorDialog( error );
                    console.log( error );
                    this.hasSavingSettings = false;
                } );
        },
        getPostManagerSettings()
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
                        this.renderPostManagerSettings( data.data );
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
        renderPostManagerSettings( postManagerSettingData )
        {
            this.allowEditPost = postManagerSettingData?.user_profile?.allow_edit_post ?? false;
            this.allowDeletePost = postManagerSettingData?.user_profile?.allow_delete_post ?? false;
            this.formSubmissionDefaultId = postManagerSettingData?.user_profile?.form_submission_default_id ?? 1;
            this.loginUserPostsChoice = postManagerSettingData?.user_profile?.login_action_choice ?? 'Show Login Message';

            this.userPostsLoginTitle = postManagerSettingData?.user_profile?.user_posts_required_login_title ?? '';
            this.userPostsLoginTitle = this.userPostsLoginTitle === '' ? this.translate.userPostsRequiredLoginTitlePattern : this.userPostsLoginTitle;

            this.userPostsLoginMessage = postManagerSettingData?.user_profile?.user_posts_required_login_message ?? '';
            this.userPostsLoginMessage = this.userPostsLoginMessage === '' ? this.translate.userPostsRequiredLoginMessagePattern : this.userPostsLoginMessage;

            this.editPostFormUrl = postManagerSettingData?.edit_post_form?.edit_post_url ?? '';
            this.loginEditPostChoice = postManagerSettingData?.edit_post_form?.login_action_choice ?? 'Show Login Message';

            this.editPostLoginTitle = postManagerSettingData?.edit_post_form?.edit_post_required_login_title ?? '';
            this.editPostLoginTitle = this.editPostLoginTitle === '' ? this.translate.editPostRequiredLoginTitlePattern : this.editPostLoginTitle;

            this.editPostLoginMessage = postManagerSettingData?.edit_post_form?.edit_post_required_login_message ?? '';
            this.editPostLoginMessage = this.editPostLoginMessage === '' ? this.translate.editPostRequiredLoginMessagePattern : this.editPostLoginMessage;

            this.customLoginButtonText = postManagerSettingData?.custom_login_and_registration?.custom_login_button_label ?? '';
            this.customLoginButtonText = this.customLoginButtonText === '' ? this.translate.loginLinkLabelPattern : this.customLoginButtonText;

            this.customLoginLink = postManagerSettingData?.custom_login_and_registration?.custom_login_link ?? '';

            this.customRegisterButtonText = postManagerSettingData?.custom_login_and_registration?.custom_registration_button_label ?? '';
            this.customRegisterButtonText = this.customRegisterButtonText === '' ? this.translate.register : this.customRegisterButtonText;

            this.customRegisterLink = postManagerSettingData?.custom_login_and_registration?.custom_registration_link ?? '';

            this.changeUserPostsChoice( this.loginUserPostsChoice );
            this.changeEditPostChoice( this.loginEditPostChoice );
        },
        changeUserPostsChoice( value )
        {
            this.loginUserPostsChoicePanel = value === 'Show Login Message' ? [ 0 ] : [];
        },
        changeEditPostChoice( value )
        {
            this.loginEditPostChoicePanel = value === 'Show Login Message' ? [ 0 ] : [];
        },
        displayErrorDialog( errorMessage )
        {
            this.snackbarClass = 'rbsm-failed-snackbar';
            this.snackbarMessage = errorMessage;
            this.snackbar = true;
        }
    },
    template: `
    <div class="rbsm-fullwidth rbsm-snackbar-container">
        <v-snackbar v-model="snackbar" :class="[snackbarClass]" :timeout="3000">
            <v-icon class="pr-2">mdi-alert-outline</v-icon>{{ snackbarMessage }}
        </v-snackbar>
        <v-dialog class="rbsm-popup-box" v-model="isDisplayDialog" >
            <v-card>
                <v-card-title><v-icon class="rbsm-green">mdi-content-save-check-outline</v-icon>{{ translate.updateSuccessful }}</v-card-title>
                <v-card-text>{{ translate.updatePostManagerSuccessfulMessage }}</v-card-text>
                <template v-slot:actions>
                <v-btn class="ms-auto" :text="translate.ok" @click="isDisplayDialog = false"></v-btn>
                </template>
            </v-card>
        </v-dialog>
        <v-expansion-panels v-model="userPostsPanel" multiple class="rbsm-expansion-panel" elevation="0">
            <v-expansion-panel>
                <v-expansion-panel-title>
                    <div>
                        <p class="rbsm-settings-title">
                            <v-icon class="mr-2">mdi-clipboard-list-outline</v-icon>{{translate.userProfile}}
                        </p>
                    </div>
                </v-expansion-panel-title>
                <v-expansion-panel-text>
                    <div class="rbsm-settings-list rbsm-shortcode-helper">
                       <v-icon>mdi-code-block-tags</v-icon>
                       <p>{{translate.rubySubmissionManager}}</p>
                       <div><span class="rbsm-form-code">[ruby_submission_manager]</span></div>
                    </div>
                    <div class="rbsm-settings-list">
                        <v-row class="d-flex rbsm-row-settings align-center">
                            <v-col class="pa-0" cols="6">
                                <p class="rbsm-setting-properties-title">{{translate.allowEditPost}}</p>
                                <div class="rbsm-setting-properties-content">{{translate.allowEditPostDesc}}</div>
                            </v-col>
                            <v-col offset="4" offset-md="0" cols="2" class="pa-0 rbsm-checkbox-layout">
                                <label class="rbsm-import-checkbox rbsm-checkbox">
                                    <input v-model="allowEditPost" type="checkbox" checked="checked">
                                    <span class="rbsm-checkbox-style"><i></i></span>
                                </label>
                            </v-col>
                        </v-row>
                        <v-row class="d-flex rbsm-row-settings align-center">
                            <v-col class="pa-0" cols="6">
                                <p class="rbsm-setting-properties-title">{{translate.allowDeletePost}}</p>
                                <div class="rbsm-setting-properties-content">{{translate.allowDeletePostDesc}}</div>
                            </v-col>
                            <v-col offset="4" offset-md="0" cols="2" class="pa-0 rbsm-checkbox-layout">
                                <label class="rbsm-import-checkbox rbsm-checkbox">
                                    <input v-model="allowDeletePost" type="checkbox" checked="checked">
                                    <span class="rbsm-checkbox-style"><i></i></span>
                                </label>
                            </v-col>
                        </v-row>
                        <v-row class="d-flex rbsm-row-settings">
                            <v-col class="pa-0" cols="12" md="6">
                                <p class="rbsm-setting-properties-title">{{translate.formSubmissionDefault}}</p>
                                <div class="rbsm-setting-properties-content">{{translate.formSubmissionDefaultDesc}}</div>
                            </v-col>
                            <v-col class="rbsm-settings-input d-flex align-center" cols="12" md="6">
                                <input class="rbsm-text-input" v-model="formSubmissionDefaultId" type="number">
                            </v-col>
                        </v-row>
                        <v-row class="d-flex rbsm-row-settings rbsm-row-select">
                            <v-col cols="12" md="6" class="pa-0">
                                <p class="rbsm-setting-properties-title-2">{{ translate.loginActionChoice }}</p>
                                <div class="rbsm-setting-properties-content-2">{{ translate.loginActionChoiceDesc }}</div>
                            </v-col>
                            <v-col cols="12" md="6" class="rbsm-settings-input">
                                <v-select
                                    class="rbsm-select"
                                    density="compact"
                                    v-model="loginUserPostsChoice"
                                    :items="loginTypeItems"
                                    variant="outlined"
                                    @update:modelValue="changeUserPostsChoice"
                                    hide-details
                                ></v-select>
                            </v-col>
                        </v-row>
                        <v-expansion-panels v-model="loginUserPostsChoicePanel" multiple class="rbsm-mini-expansion-panel" elevation="0">
                            <v-expansion-panel>
                                <v-expansion-panel-text>
                                    <v-row class="d-flex rbsm-row-settings">
                                        <v-col class="pa-0" cols="12" md="6">
                                            <p class="rbsm-setting-properties-title">{{translate.userPostsRequiredLoginTitle}}</p>
                                            <div class="rbsm-setting-properties-content">{{translate.userPostsRequiredLoginTitleDesc}}</div>
                                        </v-col>
                                        <v-col class="rbsm-settings-input d-flex align-center" cols="12" md="6">
                                            <input class="rbsm-text-input" v-model="userPostsLoginTitle" type="text">
                                        </v-col>
                                    </v-row>
                                    <v-row class="d-flex rbsm-row-settings">
                                        <v-col class="pa-0" cols="12" md="6">
                                            <p class="rbsm-setting-properties-title">{{translate.userPostsRequiredLoginMessage}}</p>
                                            <div class="rbsm-setting-properties-content">{{translate.userPostsRequiredLoginMessageDesc}}</div>
                                        </v-col>
                                        <v-col class="rbsm-settings-input d-flex align-center" cols="12" md="6">
                                            <input class="rbsm-text-input" v-model="userPostsLoginMessage" type="text">
                                        </v-col>
                                    </v-row>
                                </v-expansion-panel-text>
                            </v-expansion-panel>
                        </v-expansion-panels>
                    </div>
                </v-expansion-panel-text>
            </v-expansion-panel>
        </v-expansion-panels>
        <v-expansion-panels v-model="editPostFormPanel" multiple class="rbsm-expansion-panel" elevation="0">
            <v-expansion-panel>
                <v-expansion-panel-title>
                    <div>
                        <p class="rbsm-settings-title">
                            <v-icon class="mr-2">mdi-text-box-edit-outline</v-icon>{{translate.editPostForm}}
                        </p>
                    </div>
                </v-expansion-panel-title>
                <v-expansion-panel-text>
                    <div class="rbsm-settings-list rbsm-shortcode-helper">
                        <v-icon>mdi-code-block-tags</v-icon>
                        <p>{{translate.rubySubmissionEdit}}</p>
                        <div><span class="rbsm-form-code">[ruby_submission_edit]</span></div>
                    </div>
                    <div class="rbsm-settings-list">
                        <v-row class="d-flex rbsm-row-settings">
                            <v-col class="pa-0" cols="12" md="6">
                                <p class="rbsm-setting-properties-title">{{translate.editPostUrl}}</p>
                                <div class="rbsm-setting-properties-content">{{translate.editPostUrlDesc}}</div>
                            </v-col>
                            <v-col class="rbsm-settings-input d-flex align-center" cols="12" md="6">
                                <input class="rbsm-text-input" v-model="editPostFormUrl" type="text">
                            </v-col>
                        </v-row>
                        <v-row class="d-flex rbsm-row-settings rbsm-row-select">
                            <v-col cols="12" md="6" class="pa-0">
                                <p class="rbsm-setting-properties-title-2">{{ translate.loginActionChoice }}</p>
                                <div class="rbsm-setting-properties-content-2">{{ translate.loginActionChoiceDesc }}</div>
                            </v-col>
                            <v-col cols="12" md="6" class="rbsm-settings-input">
                                <v-select
                                    class="rbsm-select"
                                    density="compact"
                                    v-model="loginEditPostChoice"
                                    :items="loginTypeItems"
                                    variant="outlined"
                                    @update:modelValue="changeEditPostChoice"
                                    hide-details
                                ></v-select>
                            </v-col>
                        </v-row>
                        <v-expansion-panels v-model="loginEditPostChoicePanel" multiple class="rbsm-mini-expansion-panel" elevation="0">
                            <v-expansion-panel>
                                <v-expansion-panel-text>
                                    <v-row class="d-flex rbsm-row-settings">
                                        <v-col class="pa-0" cols="12" md="6">
                                            <p class="rbsm-setting-properties-title">{{translate.editPostRequiredLoginTitle}}</p>
                                            <div class="rbsm-setting-properties-content">{{translate.editPostRequiredLoginTitleDesc}}</div>
                                        </v-col>
                                        <v-col class="rbsm-settings-input d-flex align-center" cols="12" md="6">
                                            <input class="rbsm-text-input" v-model="editPostLoginTitle" type="text">
                                        </v-col>
                                    </v-row>
                                    <v-row class="d-flex rbsm-row-settings">
                                        <v-col class="pa-0" cols="12" md="6">
                                            <p class="rbsm-setting-properties-title">{{translate.editPostRequiredLoginMessage}}</p>
                                            <div class="rbsm-setting-properties-content">{{translate.editPostRequiredLoginMessageDesc}}</div>
                                        </v-col>
                                        <v-col class="rbsm-settings-input d-flex align-center" cols="12" md="6">
                                            <input class="rbsm-text-input" v-model="editPostLoginMessage" type="text">
                                        </v-col>
                                    </v-row>
                                </v-expansion-panel-text>
                            </v-expansion-panel>
                        </v-expansion-panels>
                    </div>
                </v-expansion-panel-text>
            </v-expansion-panel>
        </v-expansion-panels>
        <v-expansion-panels v-model="userProfilePanel" multiple class="rbsm-expansion-panel rbsm-final-setting-row" elevation="0">
            <v-expansion-panel>
                <v-expansion-panel-title>
                    <div>
                        <p class="rbsm-settings-title">
                            <v-icon class="mr-2">mdi-account-cog-outline</v-icon>{{translate.customLoginAndRegister}}
                        </p>
                    </div>
                </v-expansion-panel-title>
                <v-expansion-panel-text>
                    <div class="rbsm-settings-list">
                        <v-row class="d-flex rbsm-row-settings">
                            <v-col class="pa-0" cols="12" md="6">
                                <p class="rbsm-setting-properties-title">{{translate.customLoginButtonLabel}}</p>
                                <div class="rbsm-setting-properties-content">{{translate.customLoginButtonLabelDesc}}</div>
                            </v-col>
                            <v-col class="rbsm-settings-input d-flex align-center" cols="12" md="6">
                                <input class="rbsm-text-input" v-model="customLoginButtonText" type="text">
                            </v-col>
                        </v-row>
                        <v-row class="d-flex rbsm-row-settings">
                            <v-col class="pa-0" cols="12" md="6">
                                <p class="rbsm-setting-properties-title">{{translate.customLoginLink}}</p>
                                <div class="rbsm-setting-properties-content">{{translate.customLoginLinkDesc}}</div>
                            </v-col>
                            <v-col class="rbsm-settings-input d-flex align-center" cols="12" md="6">
                                <input class="rbsm-text-input" v-model="customLoginLink" type="text" placeholder="https://youwebsite.com/login/">
                            </v-col>
                        </v-row>
                        <v-row class="d-flex rbsm-row-settings">
                            <v-col class="pa-0" cols="12" md="6">
                                <p class="rbsm-setting-properties-title">{{translate.customRegisterButtonLabel}}</p>
                                <div class="rbsm-setting-properties-content">{{translate.customRegisterButtonLabelDesc}}</div>
                            </v-col>
                            <v-col class="rbsm-settings-input d-flex align-center" cols="12" md="6">
                                <input class="rbsm-text-input" v-model="customRegisterButtonText" type="text">
                            </v-col>
                        </v-row>
                        <v-row class="d-flex rbsm-row-settings">
                            <v-col class="pa-0" cols="12" md="6">
                                <p class="rbsm-setting-properties-title">{{translate.customRegisterLink}}</p>
                                <div class="rbsm-setting-properties-content">{{translate.customRegisterLinkDesc}}</div>
                            </v-col>
                            <v-col class="rbsm-settings-input d-flex align-center" cols="12" md="6">
                                <input class="rbsm-text-input" v-model="customRegisterLink" type="text" placeholder="https://youwebsite.com/register/">
                            </v-col>
                        </v-row>
                    </div>
                </v-expansion-panel-text>
            </v-expansion-panel>
        </v-expansion-panels>
        <div class="rbsm-footer">
            <div id="rbsm-footer-btn">
                <button :disabled="disableButton" class="rbsm-black-btn rbsm-transition rbsm-access-btn rbsm-footer-btn" @click="updatePostManagerSettings">
                    <v-icon v-show="hasSavingSettings" class="rbsm-loading-icon">mdi-loading</v-icon>
                    <v-icon v-show="!hasSavingSettings">mdi-content-save</v-icon>{{translate.saveSettings}}
                </button>
            </div>
        </div>
    </div>
    `
} );