document.addEventListener( 'DOMContentLoaded', function ()
{
    const { createApp } = Vue;
    const { createVuetify } = Vuetify;

    const vuetify = createVuetify();
    const app = createApp( {
        components: {
            formContent,
            formSettingsContent,
            restoreAndBackupContent,
            postManagerContent
        },
        data()
        {
            return {
                tab: Vue.ref( 1 ),
                formItemFromFormTab: Vue.ref( null ),
                shouldUpdateData: Vue.ref( false ),
                translate: Vue.ref( rbAjax.translate )
            };
        },
        watch: {
            tab()
            {
                localStorage.setItem( 'rbsm_admin_setting_tab', this.tab );
            }
        },
        mounted()
        {
            const tabValue = localStorage.getItem( 'rbsm_admin_setting_tab' ) ?? 0;
            this.tab = tabValue;
        },
        methods: {
            openFormSettings( formItem )
            {
                this.tab = 1;
                this.formItemFromFormTab = formItem;
            },
            openForm()
            {
                this.tab = 0;
                this.shouldUpdateData = true;
            },
            updateDataCompleted()
            {
                this.shouldUpdateData = false;
            }
        },
        template: `
             <v-app class="rbsm-app">
                <v-container class="rbsm-container">
                    <v-row class="ma-0 pa-0">
                        <v-col cols="12" class="ma-0 pa-0">
                            <v-card id="rbsm-introduce-card-settings" class="rbsm-card d-flex justify-space-between" elevation="0" >
                                <div class="rbsm-introduce-left">
                                      <h1 class="rbsm-admin-title"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M421.073 221.719c-0.578 11.719-9.469 26.188-23.797 40.094v183.25c-0.016 4.719-1.875 8.719-5.016 11.844-3.156 3.063-7.25 4.875-12.063 4.906H81.558c-4.781-0.031-8.891-1.844-12.047-4.906-3.141-3.125-4.984-7.125-5-11.844V152.219c0.016-4.703 1.859-8.719 5-11.844 3.156-3.063 7.266-4.875 12.047-4.906h158.609c12.828-16.844 27.781-34.094 44.719-49.906H81.558c-18.75-0.016-35.984 7.531-48.25 19.594-12.328 12.063-20.016 28.938-20 47.344v292.844c-0.016 18.406 7.672 35.313 20 47.344C45.573 504.469 62.808 512 81.558 512h298.641c18.781 0 36.016-7.531 48.281-19.594 12.297-12.031 20-28.938 19.984-47.344V203.469c0 0-0.125-0.156-0.328-0.313C440.37 209.813 431.323 216.156 421.073 221.719z"/><path d="M498.058 0c0 0-15.688 23.438-118.156 58.109C275.417 93.469 211.104 237.313 211.104 237.313c-15.484 29.469-76.688 151.906-76.688 151.906-16.859 31.625 14.031 50.313 32.156 17.656 34.734-62.688 57.156-119.969 109.969-121.594 77.047-2.375 129.734-69.656 113.156-66.531-21.813 9.5-69.906 0.719-41.578-3.656 68-5.453 109.906-56.563 96.25-60.031-24.109 9.281-46.594 0.469-51-2.188C513.386 138.281 498.058 0 498.058 0z"/></svg>
                                      {{translate.title}}
                                      </h1>
                                    <p class="rbsm-tagline">{{translate.description}}</p>
                                </div>
                                <div class="rbsm-introduce-right">
                                     <img :src="translate.introduceImage" alt="introduce">
                                </div>
                            </v-card>
                        </v-col>
                    </v-row>
                    <v-tabs class="rbsm-v-tabs" v-model="tab" slider-color="#42B978">
                        <v-card id="rbsm-tab-title">
                            <v-tab class="rbsm-vtab-title" :value="0"><v-icon>mdi-form-dropdown</v-icon>{{translate.formTab}}</v-tab>
                            <v-tab class="rbsm-vtab-title" :value="1"><v-icon>mdi-cog</v-icon>{{translate.formSettingTab}}</v-tab>
                            <v-tab class="rbsm-vtab-title" :value="2"><v-icon>mdi-file-document-outline</v-icon>{{translate.postManager}}</v-tab>
                            <v-tab class="rbsm-vtab-title" :value="3"><v-icon>mdi-restore</v-icon>{{translate.syncDataTab}}</v-tab>
                        </v-card>
                    </v-tabs>
                    <v-tabs-window v-model="tab" class="mt-3 no-overflow">
                        <v-tabs-window-item :value="0">
                            <formContent @open-form-settings="openFormSettings" @update-data-completed="updateDataCompleted" :shouldUpdateData="shouldUpdateData"/>
                        </v-tabs-window-item>
                        <v-tabs-window-item :value="1">
                            <formSettingsContent @open-form="openForm" :isTabVisible="tab===1" :formItemReceived="formItemFromFormTab"/>
                        </v-tabs-window-item>
                        <v-tabs-window-item :value="2">
                            <postManagerContent :isTabVisible="tab===2"/>
                        </v-tabs-window-item>
                        <v-tabs-window-item :value="3">
                            <restoreAndBackupContent @open-form="openForm" :isTabVisible="tab===3"/>
                        </v-tabs-window-item>
                    </v-tabs-window>
                </v-container>
            </v-app>
        `
    } );

    app.use( vuetify );
    app.mount( '#ruby-submission-settings-app' );
} );