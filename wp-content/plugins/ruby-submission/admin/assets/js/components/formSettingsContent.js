var rbAjax;
const formSettingsContent = Vue.defineComponent( {
    name: 'formSettingsContent',
    components: {
        generalSettingsContent,
        userSettingsContent,
        formFieldsContent,
        securityFieldsContent,
        emailsContent,
        previewContent
    },
    props: {
        formItemReceived: {
            type: Object,
            default: () => ( {} )
        },
        isTabVisible: {
            type: Boolean,
            default: false,
        }
    },
    data()
    {
        return {
            snackbarMessage: Vue.ref( '' ),
            snackbar: Vue.ref( false ),
            dataToSave: {},
            currentFormIndex: 0,
            titleItems: Vue.ref( [] ),
            emailData: Vue.ref( null ),
            dialogMessage: Vue.ref( '' ),
            formsData: Vue.ref( [ {} ] ),
            disableButton: Vue.ref( true ),
            userLoginData: Vue.ref( null ),
            isHandlingAjaxUpdateData: false,
            formFieldsData: Vue.ref( null ),
            shouldSaveData: Vue.ref( false ),
            userProfileData: Vue.ref( null ),
            formTitleSelected: Vue.ref( '' ),
            isDisplayDialog: Vue.ref( false ),
            securityFieldsData: Vue.ref( null ),
            generalSettingsData: Vue.ref( null ),
            translate: Vue.ref( rbAjax.translate ),
            hasSavingSettings: Vue.ref( false ),
        }
    },
    watch: {
        async formItemReceived()
        {
            await this.getAllForms();
            this.currentFormIndex = this.formsData.findIndex( form => form.id === this.formItemReceived.id );
            this.renderFormSettingWithProp();
        },
        async isTabVisible()
        {
            if( this.isTabVisible )
            {
                await this.getAllForms();

                if( this.formsData.length > 0 )
                    this.renderFormSettings();
            }
        },
        panel()
        {
            localStorage.setItem( 'rbsm_admin_security_panel', this.panel );
        }
    },
    async mounted()
    {
        await this.getAllForms();
        this.disableButton = false;
        if( this.formItemReceived )
        {
            this.currentFormIndex = this.formsData.findIndex( form => form.id === this.formItemReceived.id );
            this.renderFormSettingWithProp();
        }
        else
        {
            if( this.formsData.length > 0 )
                this.renderFormSettings();
        }
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
                            this.formsData = data.data;
                            this.titleItems = Vue.ref( [] );
                            this.formsData.forEach( element =>
                            {
                                this.titleItems.push( element.title );
                            } );

                            resolve();
                        }
                        else
                        {
                            this.formsData = [];
                            this.titleItems = [];
                            resolve();
                        }
                    } )
                    .catch( error =>
                    {
                        console.log( error );
                    } );
            } );
        },
        updateCurrentFormData()
        {
            const formData = JSON.parse( this.formsData[ this.currentFormIndex ].data );
            this.generalSettingsData = formData[ 'general_setting' ];
            this.userLoginData = formData[ 'user_login' ];
            this.formFieldsData = formData[ 'form_fields' ];
            this.securityFieldsData = formData[ 'security_fields' ];
            this.emailData = formData[ 'email' ];
        },
        async changeForm( value )
        {
            this.currentFormIndex = this.titleItems.findIndex( element => value === element );
            this.renderFormSettings();
        },
        updateFormSettings()
        {
            if( this.hasSavingSettings ) return;

            this.hasSavingSettings = true;
            this.shouldSaveData = true;
        },
        displayUpdatedDialog( formTitle )
        {
            this.dialogMessage = `${formTitle} ${this.translate.wasUpdatedSuccessfully}`;
            this.isDisplayDialog = true;
        },
        renderFormSettings()
        {
            this.formTitleSelected = this.titleItems.length > 0 ? this.formsData[ this.currentFormIndex ].title : '';
            this.updateCurrentFormData();
        },
        renderFormSettingWithProp()
        {
            this.formTitleSelected = this.formItemReceived.title;
            this.updateCurrentFormData();
        },
        addNewForm()
        {
            this.$emit( 'open-form' );
        },
        handleSavingData( data )
        {
            if( Object.keys( data ).length === 0 )
            {
                this.shouldSaveData = false;
                return;
            }

            const validFields = [ 'general_setting', 'user_login', 'form_fields', 'security_fields', 'email' ];

            this.dataToSave[ `${Object.keys( data )[ 0 ]}` ] = Object.values( data )[ 0 ];
            const dataToSaveFields = Object.keys( this.dataToSave );

            if( this.arraysEqualUnordered( validFields, dataToSaveFields ) )
            {
                if( !this.isHandlingAjaxUpdateData )
                {
                    this.isHandlingAjaxUpdateData = true;
                    this.savingDataToDatabase();
                }
            }
        },
        arraysEqualUnordered( arr1, arr2 )
        {
            if( arr1.length !== arr2.length )
            {
                return false;
            }

            let sortedArr1 = arr1.slice().sort();
            let sortedArr2 = arr2.slice().sort();

            for( let i = 0; i < sortedArr1.length; i++ )
            {
                if( sortedArr1[ i ] !== sortedArr2[ i ] )
                {
                    return false;
                }
            }

            return true;
        },
        savingDataToDatabase()
        {
            const jsonData = {
                id: this.formsData[ this.currentFormIndex ].id,
                data: this.dataToSave
            };

            const formTitle = this.formsData[ this.currentFormIndex ].title;
            const formData = new FormData();
            formData.append( 'action', 'rbsm_update_form' );
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
                        this.displayUpdatedDialog( formTitle );
                        this.getAllForms();
                        this.hasSavingSettings = false;
                    }
                    else
                    {
                        console.log( data.data );
                        this.showSaveFailureMessage( data.data );
                        this.hasSavingSettings = false;
                    }

                    this.shouldSaveData = false;
                    this.isHandlingAjaxUpdateData = false;
                    this.dataToSave = {};
                } )
                .catch( error =>
                {
                    console.log( error );
                    this.showSaveFailureMessage( error );
                    this.hasSavingSettings = false;
                } );
        },
        showSaveFailureMessage( message )
        {
            this.snackbar = true;
            this.snackbarMessage = message;
        },
        displayFormPreview()
        {
            const previewComponent = this.$refs.previewComponent;

            if( previewComponent )
            {
                previewComponent.displayFormPreview( this.formsData[ this.currentFormIndex ] );
            }
        }
    },
    template: `
        <div class="rbsm-snackbar-container">
            <v-snackbar v-model="snackbar" class="rbsm-failed-snackbar" :timeout="3000">
                <v-icon class="pr-2">mdi-alert-outline</v-icon>{{ snackbarMessage }}
            </v-snackbar>
            <v-dialog class="rbsm-popup-box" v-model="isDisplayDialog" >
                <v-card>
                    <v-card-title><v-icon class="rbsm-green">mdi-content-save-check-outline</v-icon>{{ translate.updateSuccessful }}</v-card-title>
                    <v-card-text>{{ translate.updateSuccessfulMessage }}</v-card-text>
                    <template v-slot:actions>
                    <v-btn class="ms-auto" :text="translate.ok" @click="isDisplayDialog = false"></v-btn>
                    </template>
                </v-card>
            </v-dialog>
            <v-row class="ma-0 pa-0" v-show="formsData.length === 0">
                <v-col cols="12" class="ma-0 pa-0">
                   <v-card class="rbsm-card rbsm-card-center" elevation="0">
                        <h2 class="rbsm-card-title-center">
                            <v-icon>mdi-information-outline</v-icon>{{ translate.formNotFoundTitle }} </h2>
                        <p class="rbsm-tagline">{{ translate.formNotFoundDescription }}</p>
                        <v-col cols="12" sm="4" class="d-flex align-center justify-center">
                            <button class="rbsm-black-btn rbsm-transition rbsm-access-btn rbsm-add-new-form-btn" @click="addNewForm" >
                                <v-icon>mdi-plus</v-icon>{{ translate.goToAddNew }}
                            </button>
                        </v-col>
                    </v-card>
                </v-col>
            </v-row>
            <v-row class="ma-0 pa-0" v-show="formsData.length > 0">
                <v-col cols="12" class="ma-0 pa-0">
                    <v-card class="rbsm-current-form rbsm-card" elevation="0">
                        <div class="rb-current-form-label">
                            <span><v-icon>mdi-checkbox-blank-badge</v-icon>{{translate.currentSettingsFor}}</span>
                            <h3>{{formTitleSelected}}</h3>
                        </div>
                        <div class="rbsm-current-form-selection">
                        <v-select
                                    class="rbsm-select"
                                    v-model="formTitleSelected"
                                    density="compact"
                                    :items="titleItems"
                                    variant="outlined"
                                    :label="translate.changeFormLabel"
                                    @update:modelValue="changeForm"
                                    hide-details
                                ></v-select>
                            </div>
                    </v-card>
                </v-col>
                <generalSettingsContent :data="generalSettingsData" :saveData="shouldSaveData" :sendDataToSave="handleSavingData"/>
                <userSettingsContent :data="userLoginData" :saveData="shouldSaveData" :sendDataToSave="handleSavingData"/>
                <formFieldsContent :data="formFieldsData" :saveData="shouldSaveData" :sendDataToSave="handleSavingData"/>
                <securityFieldsContent :data="securityFieldsData" :saveData="shouldSaveData" :sendDataToSave="handleSavingData"/>
                <emailsContent :data="emailData" :saveData="shouldSaveData" :sendDataToSave="handleSavingData"/>
                <previewContent ref="previewComponent"/>
                <div class="rbsm-footer">
                    <div id="rbsm-footer-btn">
                         <button :disabled="disableButton" class="rbsm-white-btn rbsm-transition rbsm-normal-btn rbsm-footer-btn" @click="displayFormPreview">
                            <v-icon>mdi-eye</v-icon>{{translate.preview}}
                        </button>
                        <button :disabled="disableButton" class="rbsm-black-btn rbsm-transition rbsm-access-btn rbsm-footer-btn" @click="updateFormSettings">
                            <v-icon v-show="hasSavingSettings" class="rbsm-loading-icon">mdi-loading</v-icon>
                            <v-icon v-show="!hasSavingSettings">mdi-content-save</v-icon>{{translate.saveSettings}}
                        </button>
                    </div>
                </div>
            </v-row>
        </div>
    `
} );