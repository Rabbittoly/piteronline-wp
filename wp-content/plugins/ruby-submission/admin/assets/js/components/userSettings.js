var rbAjax;
const userSettingsContent = Vue.defineComponent( {
    name: 'userSettingsContent',
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
            authors: [],
            panel: Vue.ref( [] ),
            authorSelectedId: -1,
            authorPanel: Vue.ref( [] ),
            authorItems: Vue.ref( [] ),
            loginMessage: Vue.ref( '' ),
            loginTypePanel: Vue.ref( [] ),
            authorSelected: Vue.ref( '' ),
            translate: Vue.ref( rbAjax.translate ),
            loginRequired: Vue.ref( 'Allow Guess' ),
            loginTypeSelected: Vue.ref( 'Show Login Message' ),
            loginItems: Vue.ref( [ 'Allow Guess', 'Only Logged User' ] ),
            loginTypeItems: Vue.ref( [ 'Show Login Message', 'Redirect to Login Page' ] ),
            requiredLoginTitle: Vue.ref( '' ),
            requiredLoginTitleDesc: Vue.ref( '' ),
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
                    "user_login": {
                        "author_access": this.loginRequired,
                        "assign_author": this.authorSelected,
                        "assign_author_id": this.authorSelectedId,
                        "login_type": {
                            "type": this.loginTypeSelected,
                            "required_login_title": this.requiredLoginTitle,
                            "required_login_title_desc": this.requiredLoginTitleDesc,
                            "login_message": this.loginMessage,
                        }
                    }
                };
                this.sendDataToSave( data );
            }
        },
        panel()
        {
            localStorage.setItem( 'rbsm_admin_user_setting_panel', this.panel );
        }

    },
    mounted()
    {
        this.getLocalStorageValue();
        this.getAllAuthors();
        this.loginRequiredChange( this.loginRequired );
    },
    methods: {
        getLocalStorageValue()
        {
            const panelValue = localStorage.getItem( 'rbsm_admin_user_setting_panel' ) || [];
            this.panel = panelValue;
        },
        loginRequiredChange( value )
        {
            if( value === 'Allow Guess' )
            {
                this.authorPanel = [ 0 ];
            } else
            {
                this.authorPanel = [ 1 ];
                this.changeLoginType( this.loginTypeSelected );
            }
        },
        changeLoginType( value )
        {
            if( value === 'Show Login Message' )
                this.loginTypePanel = [ 0 ];
            else this.loginTypePanel = [];
        },
        updateUIWithData()
        {
            this.loginRequired = this.data[ 'author_access' ] ?? '';
            this.authorSelected = this.data[ 'assign_author' ] ?? '';
            this.authorSelectedId = this.data[ 'assign_author_id' ] ?? -1;
            this.loginTypeSelected = this.data[ 'login_type' ]?.[ 'type' ] ?? '';
            this.loginMessage = this.data[ 'login_type' ]?.[ 'login_message' ] ?? '';
            this.loginLinkLabel = this.data[ 'login_type' ]?.[ 'login_link_label' ] ?? '';
            this.requiredLoginTitle = this.data[ 'login_type' ]?.[ 'required_login_title' ] ?? '';
            this.requiredLoginTitleDesc = this.data[ 'login_type' ]?.[ 'required_login_title_desc' ] ?? '';

            this.loginRequiredChange( this.loginRequired );
        },
        getAllAuthors()
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
                        if( data.success )
                        {
                            this.authors = data.data;
                            this.authorItems = this.authors.map( author => author.display_name );

                            resolve();
                        } else
                        {
                            resolve();
                        }
                    } )
                    .catch( error =>
                    {
                        console.log( error );
                    } );
            } );
        },
        changeAuthorSelect( value )
        {
            this.authorSelectedId = this.authors.find( author => ( author.display_name === value ) )?.ID;
        }
    },
    template: `
        <div class="rbsm-fullwidth">
            <v-expansion-panels v-model="panel" multiple class="rbsm-expansion-panel" elevation="0">
                <v-expansion-panel>
                    <v-expansion-panel-title>
                        <p class="rbsm-settings-title"><v-icon class="mr-2">mdi-login-variant</v-icon>{{translate.userLogin}}</p>
                    </v-expansion-panel-title>
                    <v-expansion-panel-text>
                        <div class="rbsm-settings-list">
                            <v-row class="d-flex align-center rbsm-row-settings">
                                <v-col cols="12" md="6" class="pa-0">
                                    <p class="rbsm-setting-properties-title">{{translate.authorAccess}}</p>
                                    <div class="rbsm-setting-properties-content">{{translate.authorAccessDesc}}</div>
                                </v-col>
                                <v-col cols="12" md="6" class="d-flex flex-column justify-end rbsm-settings-input">
                                    <v-select
                                        class="rbsm-select"
                                        density="compact"
                                        v-model="loginRequired"
                                        :items="loginItems"
                                        variant="outlined"
                                        @update:modelValue="loginRequiredChange"
                                        hide-details
                                    ></v-select>
                                </v-col>
                            </v-row>
                            <v-row class="d-flex align-center rbsm-row-settings">
                                <v-expansion-panels v-model="authorPanel" multiple class="rbsm-mini-expansion-panel" elevation="0">
                                    <v-expansion-panel>
                                        <v-expansion-panel-text>
                                            <v-row class="d-flex align-center rbsm-row-settings-2">
                                                <v-col cols="12" md="6" class="pa-0">
                                                    <p class="rbsm-setting-properties-title-2">{{translate.assignAuthor}}</p>
                                                    <div class="rbsm-setting-properties-content">{{translate.assignAuthorDesc}}</div>
                                                </v-col>
                                                <v-col cols="12" md="6" class="rbsm-settings-input">
                                                    <v-select
                                                        class="rbsm-select"
                                                        density="compact"
                                                        v-model="authorSelected"
                                                        :items="authorItems"
                                                        variant="outlined"
                                                        @update:modelValue="changeAuthorSelect"
                                                        hide-details
                                                        >
                                                    </v-select>
                                                </v-col>
                                            </v-row>
                                        </v-expansion-panel-text>
                                    </v-expansion-panel>
                                    <v-expansion-panel>
                                        <v-expansion-panel-text>
                                            <v-row class="d-flex align-center rbsm-row-settings-2">
                                                <v-col cols="12" md="6" class="pa-0">
                                                    <p class="rbsm-setting-properties-title-2">{{ translate.loginType }}</p>
                                                    <div class="rbsm-setting-properties-content-2">{{ translate.loginTypeDesc }}</div>
                                                </v-col>
                                                <v-col cols="12" md="6" class="rbsm-settings-input">
                                                    <v-select
                                                        class="rbsm-select"
                                                        density="compact"
                                                        v-model="loginTypeSelected"
                                                        :items="loginTypeItems"
                                                        variant="outlined"
                                                        @update:modelValue="changeLoginType"
                                                        hide-details
                                                    ></v-select>
                                                </v-col>
                                            </v-row>
                                            <v-row class="d-flex align-center rbsm-row-settings-2">
                                                <v-expansion-panels v-model="loginTypePanel" multiple class="rbsm-mini-expansion-panel" elevation="0">
                                                    <v-expansion-panel>
                                                        <v-expansion-panel-text>
                                                            <v-row class="d-flex align-center rbsm-row-settings-2">
                                                                <v-col cols="12" md="6" class="pa-0">
                                                                    <p class="rbsm-setting-properties-title-3">{{ translate.customRequiredLoginTitle }}</p>
                                                                    <div class="rbsm-setting-properties-content-3">{{ translate.customRequiredLoginTitleDesc }}</div>
                                                                </v-col>
                                                                <v-col cols="12" md="6" class="rbsm-settings-input">
                                                                    <input class="rbsm-text-input" type="text" v-model="requiredLoginTitle">
                                                                </v-col>
                                                            </v-row>
                                                            <v-row class="d-flex align-center rbsm-row-settings-2">
                                                                <v-col cols="12" md="6" class="pa-0">
                                                                    <p class="rbsm-setting-properties-title-3">{{ translate.customRequiredLoginDescLabel }}</p>
                                                                    <div class="rbsm-setting-properties-content-3">{{ translate.customRequiredLoginDescLabelDesc }}</div>
                                                                </v-col>
                                                                <v-col cols="12" md="6" class="rbsm-settings-input">
                                                                    <input class="rbsm-text-input" type="text" v-model="requiredLoginTitleDesc">
                                                                </v-col>
                                                            </v-row>
                                                        </v-expansion-panel-text>
                                                    </v-expansion-panel>
                                                </v-expansion-panels>
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