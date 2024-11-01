var rbAjax;
const generalSettingsContent = Vue.defineComponent( {
    name: 'generalSettingsContent',
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
            urlDirection: Vue.ref( '' ),
            errorMessage: Vue.ref( '' ),
            successMessage: Vue.ref( '' ),
            allowUniqueTitle: Vue.ref( true ),
            translate: Vue.ref( rbAjax.translate ),
            postStatusSelected: Vue.ref( 'Pending Review' ),
            postStatusItems: Vue.ref( [ 'Draft', 'Pending Review', 'Publish' ] ),
            formLayoutTypeItems: Vue.ref( [ '1 Col', '2 Cols' ] ),
            formLayoutTypeSelected: Vue.ref( '2 Cols' )
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
                    'general_setting': {
                        "post_status": this.postStatusSelected,
                        "url_direction": this.urlDirection,
                        "success_message": this.successMessage,
                        "error_message": this.errorMessage,
                        "unique_title": this.allowUniqueTitle,
                        'form_layout_type': this.formLayoutTypeSelected
                    }
                };

                if( this.validateURLDirection() )
                    this.sendDataToSave( data );
                else
                {
                    alert( this.translate.urlDirectionError );
                    this.sendDataToSave( {} );
                }
            }
        },
        panel()
        {
            localStorage.setItem( 'rbsm_admin_general_setting_panel', this.panel );
        }
    },
    mounted()
    {
        this.panel = localStorage.getItem( 'rbsm_admin_general_setting_panel' ) || [ 0 ];
    },
    methods: {
        changeAllowUniqueTitle( value )
        {
            this.allowUniqueTitle = value
        },
        updateUIWithData()
        {
            this.postStatusSelected = this.data[ 'post_status' ] ?? '';
            this.urlDirection = this.data[ 'url_direction' ] ?? '';
            this.successMessage = this.data[ 'success_message' ] ?? '';
            this.errorMessage = this.data[ 'error_message' ] ?? '';
            this.allowUniqueTitle = this.data[ 'unique_title' ] ?? true;
            this.formLayoutTypeSelected = this.data[ 'form_layout_type' ] ?? '';
        },
        validateURLDirection()
        {
            if( this.urlDirection === '' ) return true;

            const urlRegex = /^(https?:\/\/)?([\da-z\.-]+\.[a-z\.]{2,6}|localhost)(:\d+)?([\/\w\.\-\=\&]*)*\/?$/i;

            return urlRegex.test( this.urlDirection );
        }
    },
    template: `
        <div class="rbsm-fullwidth">
            <v-expansion-panels v-model="panel" multiple class="rbsm-expansion-panel" elevation="0">
                <v-expansion-panel>
                    <v-expansion-panel-title>
                        <div>
                            <p class="rbsm-settings-title">
                                <v-icon class="mr-2">mdi-web</v-icon>{{translate.generalSettings}}
                            </p>
                        </div>
                    </v-expansion-panel-title>
                    <v-expansion-panel-text>
                        <div class="rbsm-settings-list">
                            <v-row class="d-flex rbsm-row-settings">
                                <v-col class="pa-0 ma-0" cols="12" md="6">
                                    <p class="rbsm-setting-properties-title">{{translate.postStatus}}</p>
                                    <div class="rbsm-setting-properties-content">{{translate.postStatusDesc}}</div>
                                </v-col>
                                <v-col class="d-flex rbsm-settings-input" cols="12" md="6">
                                    <v-select
                                        class="rbsm-select"
                                        density="compact"
                                        v-model="postStatusSelected"
                                        :items="postStatusItems"
                                        variant="outlined"
                                        hide-details
                                    ></v-select>
                                </v-col>
                            </v-row>
                            <v-row class="d-flex rbsm-row-settings">
                                <v-col class="pa-0" cols="12" md="6">
                                    <p class="rbsm-setting-properties-title">{{translate.urlDirection}}</p>
                                    <div class="rbsm-setting-properties-content">{{translate.urlDirectionDesc}}</div>
                                </v-col>
                                <v-col class="rbsm-settings-input" cols="12" md="6">
                                    <input class="rbsm-text-input" v-model="urlDirection" type="text">
                                </v-col>
                            </v-row>
                            <v-row class="d-flex rbsm-row-settings">
                                <v-col class="pa-0" cols="12" md="6">
                                    <p class="rbsm-setting-properties-title">{{translate.successMessage}}</p>
                                    <div class="rbsm-setting-properties-content">{{translate.successMessageDesc}}</div>
                                </v-col>
                                <v-col class="rbsm-settings-input" cols="12" md="6">
                                    <input class="rbsm-text-input" v-model="successMessage" type="text">
                                </v-col>
                            </v-row>
                            <v-row class="d-flex rbsm-row-settings">
                                <v-col class="pa-0" cols="12" md="6">
                                    <p class="rbsm-setting-properties-title">{{translate.errorMessage}}</p>
                                    <div class="rbsm-setting-properties-content">{{translate.errorMessageDesc}}</div>
                                </v-col class="pa-0">
                                <v-col class="rbsm-settings-input" cols="12" md="6">
                                    <input class="rbsm-text-input" v-model="errorMessage" type="text">
                                </v-col>
                            </v-row>
                            <v-row class="d-flex rbsm-row-settings">
                                <v-col class="pa-0" cols="6">
                                    <p class="rbsm-setting-properties-title">{{translate.uniqueTitle}}</p>
                                    <div class="rbsm-setting-properties-content">{{translate.uniqueTitleDesc}}</div>
                                </v-col>
                                <v-col offset="4" offset-md="0" cols="2" class="pa-0 rbsm-checkbox-layout">
                                    <label class="rbsm-import-checkbox rbsm-checkbox">
                                        <input v-model="allowUniqueTitle" type="checkbox" checked="checked">
                                        <span class="rbsm-checkbox-style"><i></i></span>
                                    </label>
                                </v-col>
                            </v-row>
                            <v-row class="d-flex rbsm-row-settings">
                                <v-col class="pa-0 ma-0" cols="12" md="6">
                                    <p class="rbsm-setting-properties-title">{{translate.submissionFormLayoutType}}</p>
                                    <div class="rbsm-setting-properties-content">{{translate.submissionFormLayoutTypeDesc}}</div>
                                </v-col>
                                <v-col class="d-flex rbsm-settings-input" cols="12" md="6">
                                    <v-select
                                        class="rbsm-select"
                                        density="compact"
                                        v-model="formLayoutTypeSelected"
                                        :items="formLayoutTypeItems"
                                        variant="outlined"
                                        hide-details
                                    ></v-select>
                                </v-col>
                            </v-row>
                        </div>
                    </v-expansion-panel-text>
                </v-expansion-panel>
            </v-expansion-panels>
        </div>
    `
} );