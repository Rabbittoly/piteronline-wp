var rbLocalizeData;
document.addEventListener( 'DOMContentLoaded', function ()
{
    const { createApp } = Vue;
    const { createVuetify } = Vuetify;
    const vuetify = createVuetify();

    const app = createApp( {
        data()
        {
            return {
                isRenderUI: Vue.ref( false ),
                translate: Vue.ref( rbLocalizeData.translate ),
                requiredLoginTitle: Vue.ref( '' ),
                requiredLoginDesc: Vue.ref( '' ),
                loginLinkLabel: Vue.ref( '' ),
                registerLinkLabel: Vue.ref( '' ),
                registerURL: Vue.ref( '' ),
                loginURL: Vue.ref( '' )
            };
        },
        components: {
            userPostsContent
        },
        created()
        {
            this.checkUserLogin();
        },
        methods: {
            checkUserLogin()
            {
                this.isRenderUI = rbsmUserPostsData?.isUserLogged ?? 0;
                const userPostLoginAction = rbsmUserPostsData?.postManagerSettings?.user_profile?.login_action_choice ?? 'Show Login Message';

                if( !rbsmUserPostsData?.isUserLogged )
                {
                    if( userPostLoginAction === 'Show Login Message' )
                    {
                        this.requiredLoginTitle = rbsmUserPostsData?.postManagerSettings?.user_profile?.user_posts_required_login_title ?? this.translate.userPostsRequiredLoginTitlePattern;
                        this.requiredLoginDesc = rbsmUserPostsData?.postManagerSettings?.user_profile?.user_posts_required_login_message ?? this.translate.userPostsRequiredLoginMessagePattern;
                        this.loginLinkLabel = rbsmUserPostsData?.postManagerSettings?.custom_login_and_registration?.custom_login_button_label ?? this.translate.login;
                        this.registerLinkLabel = rbsmUserPostsData?.postManagerSettings?.custom_login_and_registration?.custom_registration_button_label ?? this.translate.register;
                        this.registerURL = rbLocalizeData.registerURL;
                        this.loginURL = rbLocalizeData.loginUrl;
                    }
                    else
                    {
                        window.location.href = rbLocalizeData.loginUrl + `?redirect_to=${window.location.href}`;
                    }
                }
            },
            redirectToLogin()
            {
                window.location.href = this.loginURL + `?redirect_to=${window.location.href}`;
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
            <userPostsContent v-if="isRenderUI"/>
        `
    } );

    app.use( vuetify );
    app.mount( '#rbsm-user-posts' );
} );
