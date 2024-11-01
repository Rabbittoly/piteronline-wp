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
                loginLinkUrl: Vue.ref( '' ),
                loginLinkLabel: Vue.ref( '' ),
                authorAccess: 'Only Logged User',
                loginType: Vue.ref( 'Show Login Message' ),
                formSettings: Vue.ref( null ),
                isRenderUI: Vue.ref( false ),
                translate: Vue.ref( rbLocalizeData.translate ),
                requiredLoginTitle: Vue.ref( '' ),
                registerLinkLabel: Vue.ref( '' )
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
                const formSettingsRaw = rbSubmissionForm.formSettings ?? undefined;
                if( formSettingsRaw === undefined )
                {
                    console.log( 'Cannot find submission form setting.' );
                    return;
                }

                this.formSettings = JSON.parse( formSettingsRaw.data );
                this.authorAccess = this.formSettings?.user_login?.author_access ?? 'Only Logged User';

                this.loginLinkLabel = rbLocalizeData?.postManagerSettings?.custom_login_and_registration?.custom_login_button_label ?? '';
                this.loginLinkLabel = this.loginLinkLabel === '' ? this.translate.login : this.loginLinkLabel;

                this.requiredLoginTitle = this.formSettings?.user_login?.login_type?.required_login_title ?? '';
                this.requiredLoginTitle = this.requiredLoginTitle === '' ? this.translate.requiredLoginTitlePattern : this.requiredLoginTitle;

                this.requiredLoginDesc = this.formSettings?.user_login?.login_type?.required_login_title_desc ?? '';
                this.requiredLoginDesc = this.requiredLoginDesc === '' ? this.translate.requiredLoginDescPattern : this.requiredLoginDesc;

                this.registerLinkLabel = rbLocalizeData?.postManagerSettings?.custom_login_and_registration?.custom_registration_button_label ?? '';
                this.registerLinkLabel = this.registerLinkLabel === '' ? this.translate.register : this.registerLinkLabel;

                this.loginType = this.formSettings?.user_login?.login_type.type ?? '';

                this.loginLinkUrl = rbLocalizeData.loginUrl
                this.registerURL = rbLocalizeData.registerURL;
            },
            checkUserLogin()
            {
                const isUserLogged = rbSubmissionForm?.isUserLogged ?? 0;

                if( !isUserLogged )
                {
                    if( this.authorAccess === 'Only Logged User' )
                    {
                        if( this.loginType === 'Show Login Message' )
                            this.isRenderUI = false;
                        else
                            window.location.href = this.loginLinkUrl + `?redirect_to=${window.location.href}`;
                    } else
                        this.isRenderUI = true;
                } else
                {
                    this.isRenderUI = true;
                }
            },
            redirectToLogin()
            {
                window.location.href = this.loginLinkUrl + `?redirect_to=${window.location.href}`;
            },
            redirectToRegister()
            {
                window.location.href = this.registerURL;
            }
        },
        template: `
            <div class="rbsm-login-wrap" v-if="!isRenderUI">
                <span class="rbsm-login-icon"><v-icon>mdi-lock-outline</v-icon></span>
                <h2 class="headline">{{requiredLoginTitle}}</h2>
                <p class="rbsm-login-desc">{{requiredLoginDesc}}</p>
                <button class="rbsm-btn-primary is-btn" @click="redirectToLogin">{{loginLinkLabel}}</button>
                <button v-if="registerURL !== ''" class="rbsm-btn-primary is-btn is-outlined" @click="redirectToRegister">{{registerLinkLabel}}</button>
            </div>
            <submissionFormContent v-if="isRenderUI"/>
        `
    } );

    app.use( vuetify );
    app.mount( '#rbsm-form-shortcode' );
} );
