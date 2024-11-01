var rbLocalizeData;

document.addEventListener( 'DOMContentLoaded', function ()
{
    const { createApp } = Vue;
    const { createVuetify } = Vuetify;
    const vuetify = createVuetify();

    const app = createApp( {
        components: {
            submissionFormContent
        },
        data()
        {
            return {
                dialog: Vue.ref( false ),
                loginMessage: Vue.ref( '' ),
                loginLinkUrl: Vue.ref( '' ),
                loginLinkLabel: Vue.ref( '' ),
                authorAccess: 'Only Logged User',
                loginType: Vue.ref( 'Show Login Message' ),
                formSettings: Vue.ref( null ),
                isRenderUI: Vue.ref( false ),
                translate: Vue.ref( rbLocalizeData.translate ),
            };
        },
        created()
        {
            if( rbSubmissionForm.hasError )
            {
                console.log( rbSubmissionForm.errorMessage );
                return;
            }

            this.getFormSettings();
            this.checkUserLogin();
        },
        methods: {
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

                if( !isUserLogged )
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
                else
                {
                    this.isRenderUI = true;
                }
            },
            redirectToLogin()
            {
                window.location.href = this.loginLinkUrl;
            }
        },
        template: `
            <div class="rbsm-login-wrap" v-if="!isRenderUI">
                <span class="rbsm-login-icon"><v-icon>mdi-lock-outline</v-icon></span>
                <h2 class="headline">{{requiredLoginTitle}}</h2>
                <p class="rbsm-login-desc">{{requiredLoginDesc}}</p>
                <button class="rbsm-btn-primary is-btn" @click="redirectToLogin">{{loginLinkLabel}}</button>
            </div>
            <submissionFormContent v-if="isRenderUI"/>
        `
    } );

    app.use( vuetify );
    app.mount( '#rbsm-post-editing' );
} );
