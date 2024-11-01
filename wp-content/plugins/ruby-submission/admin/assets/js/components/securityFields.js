var rbAjax;
const securityFieldsContent = Vue.defineComponent( {
    name: 'securityFieldsContent',
    props: {
        data: {
            type: Object,
            default: () => ( {} )
        },
        saveData: {
            type: Boolean,
            default: false
        },
        sendDataToSave: {
            type: Function,
            require: true
        }
    },
    data()
    {
        return {
            panel: Vue.ref( [] ),
            reCaptchaPanel: Vue.ref( [] ),
            challengePanel: Vue.ref( [] ),
            allowChallenge: Vue.ref( false ),
            allowReCaptcha: Vue.ref( false ),
            recaptchaSiteKeyInput: Vue.ref( '' ),
            translate: Vue.ref( rbAjax.translate ),
            challengeQuestionInput: Vue.ref( '' ),
            challengeResponseInput: Vue.ref( '' ),
            recaptchaSecretKeyInput: Vue.ref( '' ),
        }
    },
    watch: {
        data()
        {
            this.updateUIWithData();
        },
        saveData()
        {
            if( this.saveData )
            {
                const data = {
                    "security_fields": {
                        "challenge": {
                            "status": this.allowChallenge,
                            "question": this.challengeQuestionInput,
                            "response": this.challengeResponseInput
                        },
                        "recaptcha": {
                            "status": this.allowReCaptcha,
                            "recaptcha_site_key": this.recaptchaSiteKeyInput,
                            "recaptcha_secret_key": this.recaptchaSecretKeyInput
                        }
                    }
                };
                this.sendDataToSave( data );
            }
        },
        panel()
        {
            localStorage.setItem( 'rbsm_admin_security_panel', this.panel );
        }
    },
    mounted()
    {
        const panelValue = localStorage.getItem( 'rbsm_admin_security_panel' ) || [];
        this.panel = panelValue;
    },
    methods: {
        allowReCaptchaChange()
        {
            this.reCaptchaPanel = this.allowReCaptcha ? [ 0 ] : [];
        },
        allowChallengeChange()
        {
            this.challengePanel = this.allowChallenge ? [ 0 ] : [];
        },
        updateUIWithData()
        {
            this.allowChallenge = this.data[ 'challenge' ]?.[ 'status' ] ?? false;
            this.challengeQuestionInput = this.data[ 'challenge' ]?.[ 'question' ] ?? '';
            this.challengeResponseInput = this.data[ 'challenge' ]?.[ 'response' ] ?? '';
            this.allowReCaptcha = this.data[ 'recaptcha' ]?.[ 'status' ] ?? false;
            this.recaptchaSiteKeyInput = this.data[ 'recaptcha' ]?.[ 'recaptcha_site_key' ] ?? '';
            this.recaptchaSecretKeyInput = this.data[ 'recaptcha' ]?.[ 'recaptcha_secret_key' ] ?? '';

            this.allowReCaptchaChange( this.allowReCaptcha );
            this.allowChallengeChange( this.allowChallenge );
        }
    },
    template: `
        <div class="rbsm-fullwidth">
            <v-expansion-panels v-model="panel" multiple class="rbsm-expansion-panel" elevation="0">
                <v-expansion-panel>
                    <v-expansion-panel-title>
                        <p class="rbsm-settings-title"><v-icon class="mr-2">mdi-security</v-icon>{{translate.securityFields}}</p>
                    </v-expansion-panel-title>
                    <v-expansion-panel-text>
                        <div class="rbsm-settings-list">
                            <v-row class="d-flex align-center rbsm-row-settings">
                            <v-col cols="6" class="pa-0">
                                <p class="rbsm-setting-properties-title">{{translate.challenge}}</p>
                                <div class="rbsm-setting-properties-content">{{translate.challengeDesc}}</div>
                            </v-col>
                            <v-col offset="4" offset-md="0" cols="2" class="pa-0 rbsm-checkbox-layout">
                                <label class="rbsm-import-checkbox rbsm-checkbox">
                                    <input v-model="allowChallenge" @change="allowChallengeChange" type="checkbox" checked="checked">
                                    <span class="rbsm-checkbox-style"><i></i></span>
                                </label>
                            </v-col>
                        </v-row>
                        <v-row class="d-flex align-center rbsm-row-settings">
                            <v-expansion-panels v-model="challengePanel" multiple class="rbsm-mini-expansion-panel" elevation="0">
                                <v-expansion-panel>
                                    <v-expansion-panel-text>
                                        <v-row class="d-flex align-center rbsm-row-settings-2">
                                            <v-col cols="12" md="6" class="pa-0">
                                                <p class="rbsm-setting-properties-title-2">{{translate.challengeQuestion}}</p>
                                                <div class="rbsm-setting-properties-content-2">{{translate.challengeQuestionDesc}}</div>
                                            </v-col>
                                            <v-col cols="12" md="6" class="rbsm-settings-input">
                                                <input class="rbsm-text-input" type="text" v-model="challengeQuestionInput">
                                            </v-col>
                                        </v-row>
                                        <v-row class="d-flex align-center rbsm-row-settings-2">
                                            <v-col cols="12" md="6" class="pa-0">
                                                <p class="rbsm-setting-properties-title-2">{{translate.challengeResponse}}</p>
                                                <div class="rbsm-setting-properties-content-2">{{translate.challengeResponseDesc}}</div>
                                            </v-col>
                                            <v-col cols="12" md="6" class="rbsm-settings-input">
                                                <input class="rbsm-text-input" type="text" v-model="challengeResponseInput">
                                            </v-col>
                                        </v-row>
                                    </v-expansion-panel-text>
                                </v-expansion-panel>
                            </v-expansion-panels>
                        </v-row>
                        <v-row class="d-flex align-center rbsm-row-settings">
                            <v-col cols="6" class="pa-0">
                                <p class="rbsm-setting-properties-title">{{translate.recaptcha}}</p>
                                <div class="rbsm-setting-properties-content">{{translate.recaptchaDesc}}</div>
                            </v-col>
                            <v-col offset="4" offset-md="0" cols="2" class="pa-0 rbsm-checkbox-layout">
                                <label class="rbsm-import-checkbox rbsm-checkbox">
                                    <input v-model="allowReCaptcha" @change="allowReCaptchaChange" type="checkbox" checked="checked">
                                    <span class="rbsm-checkbox-style"><i></i></span>
                                </label>
                            </v-col>
                        </v-row>
                        <v-row class="d-flex align-center rbsm-row-settings">
                            <v-expansion-panels v-model="reCaptchaPanel" multiple class="rbsm-mini-expansion-panel" elevation="0">
                                <v-expansion-panel>
                                    <v-expansion-panel-text>
                                        <v-row class="d-flex align-center rbsm-row-settings-2">
                                            <v-col cols="12" md="6" class="pa-0">
                                                <p class="rbsm-setting-properties-title-2">{{translate.recaptchaSiteKey}}</p>
                                                <div class="rbsm-setting-properties-content-2">{{translate.recaptchaSiteKeyDesc}}</div>
                                            </v-col>
                                            <v-col cols="12" md="6" class="rbsm-settings-input">
                                                <input class="rbsm-text-input" type="text" v-model="recaptchaSiteKeyInput">
                                            </v-col>
                                        </v-row>
                                        <v-row class="d-flex align-center rbsm-row-settings-2">
                                            <v-col cols="12" md="6" class="pa-0">
                                                <p class="rbsm-setting-properties-title-2">{{translate.recaptchaSecretKey}}</p>
                                                <div class="rbsm-setting-properties-content-2">{{translate.recaptchaSecretKeyDesc}}</div>
                                            </v-col>
                                            <v-col cols="12" md="6" class="rbsm-settings-input">
                                                <input class="rbsm-text-input" v-model="recaptchaSecretKeyInput" type="text">
                                            </v-col>
                                        </v-row>
                                    </v-expansion-panel-text>
                                </v-expansion-panel>
                            </v-expansion-panels>
                        </v-row>
                        </div>
                    </v-expansion-panel-text>
                </v-expansion-panel>
            </v-expansion-panels>
        </div>
    `
} );