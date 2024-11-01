const recaptchaContent = Vue.defineComponent( {
    name: 'recaptchaContent',
    props: {
        siteKey: {
            type: String,
            required: true
        },
        shouldLoadRecaptcha: {
            type: Boolean,
            required: false
        }
    },
    data()
    {
        return {
            recaptchaInstance: null,
            translate: Vue.ref( rbLocalizeData.translate )
        }
    },
    watch: {
        shouldLoadRecaptcha()
        {
            if( this.shouldLoadRecaptcha )
            {
                this.loadRecaptcha();
            }
        }
    },
    mounted()
    {
        if( this.shouldLoadRecaptcha )
        {
            this.loadRecaptcha();
        }
    },
    methods: {
        loadRecaptcha()
        {
            if( !document.getElementById( 'recaptcha-script' ) )
            {
                const script = document.createElement( 'script' );
                script.id = 'recaptcha-script';
                script.src = 'https://www.google.com/recaptcha/api.js?render=explicit';
                script.onload = _ => this.waitForGrecaptcha().then( _ => this.renderRecaptcha() );
                script.async = true;
                script.defer = true;
                document.head.appendChild( script );
            }
            else
            {
                this.waitForGrecaptcha().then( _ => this.renderRecaptcha() );
            }

        },
        waitForGrecaptcha()
        {
            return new Promise( ( resolve, reject ) =>
            {
                const interval = setInterval( () =>
                {
                    if( typeof grecaptcha.render !== 'undefined' )
                    {
                        clearInterval( interval );
                        resolve();
                    }
                }, 50 );

                setTimeout( () =>
                {
                    clearInterval( interval );
                    reject( this.translate.recaptchaLoadFailed );
                }, 5000 );
            } );
        },
        renderRecaptcha()
        {
            if( this.siteKey !== '' )
            {
                this.recaptchaInstance = grecaptcha.render( this.$el.querySelector( '.g-recaptcha' ), {
                    sitekey: this.siteKey,
                    callback: this.onVerify,
                    'expired-callback': this.onExpired
                } );
            }
        },
        onVerify( response )
        {
            this.$emit( 'verified', response );
        },
        onExpired()
        {
            this.$emit( 'data-expired-callback' );
        },
        resetRecaptcha()
        {
            if( this.recaptchaInstance !== null )
                window.grecaptcha.reset( this.recaptchaInstance );
        }
    },
    template: `
        <v-container>
            <v-row>
                <div>
                    <div
                    class="g-recaptcha"
                    :data-sitekey="siteKey"
                    :data-callback="onVerify"
                    ></div>
                </div>
            </v-row>
        </v-container>
    `
} );