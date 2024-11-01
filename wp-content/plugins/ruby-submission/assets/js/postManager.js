var rbLocalizeData;
var rbSubmissionForm;

document.addEventListener( 'DOMContentLoaded', function ()
{
    const { createApp } = Vue;
    const { createVuetify } = Vuetify;
    const vuetify = createVuetify();

    const app = createApp( {
        data()
        {
            return {
                tab: Vue.ref( 0 ),
                yesStorage: false,
                dialog: Vue.ref( false ),
                loginMessage: Vue.ref( '' ),
                loginLinkUrl: Vue.ref( '' ),
                isRenderUI: Vue.ref( false ),
                loginLinkLabel: Vue.ref( '' ),
                authorAccess: 'Only Logged User',
                loginType: Vue.ref( 'Show Login Message' ),
                formSettings: Vue.ref( null ),
                translate: Vue.ref( rbLocalizeData.translate ),
                tab2TextLabel: Vue.ref( rbLocalizeData.translate.submitPost ),
            };
        },
        components: {
            submissionFormContent,
            userPostsContent,
        },
        watch: {
            tab()
            {
                this.setStorage( 'rbsm_client_tab', this.tab );
            }
        },
        async created()
        {
            this.yesStorage = this.isStorageAvailable();

            if( rbSubmissionForm.hasError )
            {
                console.log( rbSubmissionForm.errorMessage );
                return;
            }

            this.getFormSettings();
            this.checkUserLogin();

            this.tab = +rbLocalizeData?.tab ?? 0;

            if( this.tab === 0 )
            {
                this.tab = this.getStorage( 'rbsm_client_tab', 0 );
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
            getFormSettings()
            {
                const formSettingsRaw = rbSubmissionForm?.formSettings ?? undefined;
                if( formSettingsRaw === undefined )
                {
                    console.log( 'Cannot find submission form setting.' );
                    return;
                }

                this.formSettings = JSON.parse( formSettingsRaw.data );
                this.authorAccess = this.formSettings?.user_login?.author_access ?? 'Only Logged User';
                this.loginLinkLabel = this.formSettings?.user_login?.login_type?.login_link_label ?? '';
                this.loginLinkUrl = this.formSettings?.user_login?.login_type?.login_link_url ?? '';
                this.loginMessage = this.formSettings?.user_login?.login_type?.login_message ?? '';
                this.loginType = this.formSettings?.user_login?.login_type?.type ?? '';
            },
            checkUserLogin()
            {
                const isUserLogged = rbSubmissionForm?.isUserLogged ?? 0;

                if( isUserLogged )
                {
                    this.isRenderUI = true;
                }
                else
                {
                    if( this.authorAccess === 'Only Logged User' )
                    {
                        if( this.loginType === 'Show Login Message' )
                            this.dialog = true;
                        else
                            window.location.href = rbLocalizeData.loginUrl + `?redirect_to=${window.location.href}`;
                    } else
                        this.isRenderUI = true;
                }
            },
            changeTabNameToEdit()
            {
                this.tab2TextLabel = this.translate.editPost;
            },
            redirectToLogin()
            {
                window.location.href = this.loginLinkUrl;
            }
        },
        template: `
                <v-container v-if="isRenderUI">
                     <v-dialog class="rbsm-popup-box" v-model="dialog" persistent>
                        <v-card>
                            <v-card-title class="headline"><v-icon>mdi-lock-outline</v-icon>{{ translate.needLogin }}</v-card-title>
                            <v-card-text>{{loginMessage}}</v-card-text>
                            <v-card-actions>
                            <v-btn @click="redirectToLogin">{{loginLinkLabel}}</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                    <v-tabs v-model="tab">
                        <v-tab :value="0"><div class="rbsm-tab-label h4"><v-icon>mdi-folder-file-outline</v-icon>{{ translate.postListLabel }}</div></v-tab>
                        <v-tab :value="1"><div class="rbsm-tab-label h4"><v-icon>mdi-note-edit-outline</v-icon>{{ tab2TextLabel }}</div></v-tab>
                    </v-tabs>
                    <v-tabs-window class="rbsm-tabs-container" v-model="tab">
                        <v-tabs-window-item :value="0">
                              <div class="rbsm-tab-wrapper rbsm-tab-posts-listing"><userPostsContent /></div>
                        </v-tabs-window-item>
                        <v-tabs-window-item :value="1">
                              <submissionFormContent @change-tab-label-to-edit="changeTabNameToEdit"/>
                        </v-tabs-window-item>
                    </v-tabs-window>
                </v-container>
        `
    } );

    app.use( vuetify );
    app.mount( '#rbsm-post-manager' );
} );
