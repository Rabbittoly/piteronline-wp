var rbAjax;
const emailsContent = Vue.defineComponent( {
    name: 'emailsContent',
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
            adminMailPanel: Vue.ref( [] ),
            postTrashPanel: Vue.ref( [] ),
            adminMailInput: Vue.ref( '' ),
            postSubmitPanel: Vue.ref( [] ),
            postPublishPanel: Vue.ref( [] ),
            allowAdminMail: Vue.ref( false ),
            adminMailTitleInput: Vue.ref( '' ),
            mailTrashTitleInput: Vue.ref( '' ),
            mailSubmitTitleInput: Vue.ref( '' ),
            translate: Vue.ref( rbAjax.translate ),
            adminMailSubjectInput: Vue.ref( '' ),
            adminMailMessageInput: Vue.ref( '' ),
            mailTrashSubjectInput: Vue.ref( '' ),
            mailPublishTitleInput: Vue.ref( '' ),
            mailTrashMessageInput: Vue.ref( '' ),
            mailSubmitSubjectInput: Vue.ref( '' ),
            mailSubmitMessageInput: Vue.ref( '' ),
            mailPublishSubjectInput: Vue.ref( '' ),
            mailPublishMessageInput: Vue.ref( '' ),
            allowPostTrashNotification: Vue.ref( false ),
            allowPostSubmitNotification: Vue.ref( false ),
            allowPostPublishNotification: Vue.ref( false ),
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
                    "email": {
                        "admin_mail": {
                            "status": this.allowAdminMail,
                            "email": this.adminMailInput,
                            "subject": this.adminMailSubjectInput,
                            "title": this.adminMailTitleInput,
                            "message": this.adminMailMessageInput
                        },
                        "post_submit_notification": {
                            "status": this.allowPostSubmitNotification,
                            "subject": this.mailSubmitSubjectInput,
                            "title": this.mailSubmitTitleInput,
                            "message": this.mailSubmitMessageInput
                        },
                        "post_publish_notification": {
                            "status": this.allowPostPublishNotification,
                            "subject": this.mailPublishSubjectInput,
                            "title": this.mailPublishTitleInput,
                            "message": this.mailPublishMessageInput
                        },
                        "post_trash_notification": {
                            "status": this.allowPostTrashNotification,
                            "subject": this.mailTrashSubjectInput,
                            "title": this.mailTrashTitleInput,
                            "message": this.mailTrashMessageInput
                        }
                    }
                };

                this.sendDataToSave( data );
            }
        },
        panel()
        {
            localStorage.setItem( 'rbsm_admin_email_panel', this.panel );
        }
    },
    mounted()
    {
        this.getLocalStorageValue();
    },
    methods: {
        getLocalStorageValue()
        {
            const panelValue = localStorage.getItem( 'rbsm_admin_email_panel' ) || [];
            this.panel = panelValue;
        },
        allowAdminMailChange()
        {
            this.adminMailPanel = this.allowAdminMail ? [ 0 ] : [];
        },
        allowPostSubmitNotificationChange()
        {
            this.postSubmitPanel = this.allowPostSubmitNotification ? [ 0 ] : [];
        },
        allowPostPublishNotificationChange()
        {
            this.postPublishPanel = this.allowPostPublishNotification ? [ 0 ] : [];
        },
        allowPostTrashNotificationChange()
        {
            this.postTrashPanel = this.allowPostTrashNotification ? [ 0 ] : [];
        },
        updateUIWithData()
        {
            this.allowAdminMail = this.data[ 'admin_mail' ]?.[ 'status' ] ?? false;
            this.adminMailInput = this.data[ 'admin_mail' ]?.[ 'email' ] ?? '';
            this.adminMailSubjectInput = this.data[ 'admin_mail' ]?.[ 'subject' ] ?? '';
            this.adminMailTitleInput = this.data[ 'admin_mail' ]?.[ 'title' ] ?? '';
            this.adminMailMessageInput = this.data[ 'admin_mail' ]?.[ 'message' ] ?? '';
            this.allowPostSubmitNotification = this.data[ 'post_submit_notification' ]?.[ 'status' ] ?? false;
            this.mailSubmitSubjectInput = this.data[ 'post_submit_notification' ]?.[ 'subject' ] ?? '';
            this.mailSubmitTitleInput = this.data[ 'post_submit_notification' ]?.[ 'title' ] ?? '';
            this.mailSubmitMessageInput = this.data[ 'post_submit_notification' ]?.[ 'message' ] ?? '';
            this.allowPostPublishNotification = this.data[ 'post_publish_notification' ]?.[ 'status' ] ?? false;
            this.mailPublishSubjectInput = this.data[ 'post_publish_notification' ]?.[ 'subject' ] ?? '';
            this.mailPublishTitleInput = this.data[ 'post_publish_notification' ]?.[ 'title' ] ?? '';
            this.mailPublishMessageInput = this.data[ 'post_publish_notification' ]?.[ 'message' ] ?? '';
            this.allowPostTrashNotification = this.data[ 'post_trash_notification' ]?.[ 'status' ] ?? false;
            this.mailTrashSubjectInput = this.data[ 'post_trash_notification' ]?.[ 'subject' ] ?? '';
            this.mailTrashTitleInput = this.data[ 'post_trash_notification' ]?.[ 'title' ] ?? '';
            this.mailTrashMessageInput = this.data[ 'post_trash_notification' ]?.[ 'message' ] ?? '';

            this.allowAdminMailChange( this.allowAdminMail );
            this.allowPostSubmitNotificationChange( this.allowPostSubmitNotification );
            this.allowPostPublishNotificationChange( this.allowPostPublishNotification );
            this.allowPostTrashNotificationChange( this.allowPostTrashNotification );
        }
    },
    template: `
        <div class="rbsm-fullwidth">
            <v-expansion-panels v-model="panel" multiple class="rbsm-expansion-panel" elevation="0">
                <v-expansion-panel>
                    <v-expansion-panel-title>
                        <p class="rbsm-settings-title"><v-icon class="mr-2">mdi-email-outline</v-icon>{{translate.emails}}</p>
                    </v-expansion-panel-title>
                    <v-expansion-panel-text>
                        <div class="rbsm-settings-list">
                             <v-row class="d-flex align-center rbsm-row-settings">
                            <v-col cols="6" class="pa-0">
                                <p class="rbsm-setting-properties-title"><v-icon class="rbsm-setting-icon">mdi-shield-crown-outline</v-icon>{{translate.adminEmail}}</p>
                                <div class="rbsm-setting-properties-content">{{translate.adminEmailDesc}}</div>
                            </v-col>
                            <v-col offset="4" offset-md="0" cols="2" class="pa-0 rbsm-checkbox-layout">
                                <label class="rbsm-import-checkbox rbsm-checkbox">
                                    <input v-model="allowAdminMail" @change="allowAdminMailChange" type="checkbox" checked="checked">
                                    <span class="rbsm-checkbox-style"><i></i></span>
                                </label>
                            </v-col>
                        </v-row>
                        <v-row class="d-flex align-center rbsm-row-settings">
                            <v-expansion-panels v-model="adminMailPanel" class="rbsm-mini-expansion-panel" elevation="0">
                                <v-expansion-panel>
                                    <v-expansion-panel-text>
                                        <div class="rbsm-mini-card">
                                            <v-row class="d-flex align-center rbsm-row-settings-2">
                                                <v-col cols="12" md="6" class="pa-0">
                                                        <p class="rbsm-setting-properties-title-2"><v-icon class="rbsm-setting-icon">mdi-email-fast-outline</v-icon>{{ translate.adminEmailAddress }}</p>
                                                        <div class="rbsm-setting-properties-content-2">{{translate.adminEmailDesc}}</div>
                                                    </v-col>
                                                    <v-col cols="12" md="6" class="rbsm-settings-input">
                                                        <input class="rbsm-text-input" type="text" v-model="adminMailInput" placeholder="email@domain.com">
                                                </v-col>
                                            </v-row>
                                            <v-row class="d-flex align-center rbsm-row-settings-2">
                                                <v-col cols="12" md="6" class="pa-0">
                                                    <p class="rbsm-setting-properties-title-2">{{translate.subject}}</p>
                                                    <div class="rbsm-setting-properties-content-2">{{translate.emailSubjectDesc}}</div>
                                                </v-col>
                                                <v-col cols="12" md="6" class="rbsm-settings-input">
                                                    <input class="rbsm-text-input" type="text" v-model="adminMailSubjectInput">
                                                </v-col>
                                            </v-row>
                                            <v-row class="d-flex align-center rbsm-row-settings-2">
                                                <v-col cols="12" md="6" class="pa-0">
                                                    <p class="rbsm-setting-properties-title-2">{{translate.emailTitle}}</p>
                                                    <div class="rbsm-setting-properties-content-2" >{{translate.emailTitleDesc}}</div>
                                                </v-col>
                                                <v-col cols="12" md="6" class="rbsm-settings-input">
                                                    <input class="rbsm-text-input" type="text" v-model="adminMailTitleInput">
                                                </v-col>
                                            </v-row>
                                            <v-row class="d-flex rbsm-row-settings-2">
                                                <v-col cols="12" md="6" class="pa-0">
                                                    <p class="rbsm-setting-properties-title-2">{{translate.message}}</p>
                                                    <div class="rbsm-setting-properties-content-2" >{{translate.emailMessageDesc}}</div>
                                                </v-col>
                                                <v-col cols="12" md="6" class="rbsm-settings-input">
                                                    <textarea class="rbsm-text-area" v-model="adminMailMessageInput"></textarea>
                                                </v-col>
                                            </v-row>
                                        </div>
                                    </v-expansion-panel-text>
                                </v-expansion-panel>
                            </v-expansion-panels>
                        </v-row>
                        <v-row class="d-flex align-center rbsm-row-settings">
                            <v-col cols="6" class="pa-0">
                                <p class="rbsm-setting-properties-title"><v-icon class="rbsm-setting-icon">mdi-pencil-outline</v-icon>{{translate.postSubmitNotification}}</p>
                                <div class="rbsm-setting-properties-content">{{translate.postSubmitNotificationDesc}}</div>
                            </v-col>
                           <v-col offset="4" offset-md="0" cols="2" class="pa-0 rbsm-checkbox-layout">
                                <label class="rbsm-import-checkbox rbsm-checkbox">
                                    <input v-model="allowPostSubmitNotification" @change="allowPostSubmitNotificationChange" type="checkbox" checked="checked">
                                    <span class="rbsm-checkbox-style"><i></i></span>
                                </label>
                            </v-col>
                        </v-row>
                        <v-row class="d-flex align-center rbsm-row-settings">
                            <v-expansion-panels v-model="postSubmitPanel" multiple class="rbsm-mini-expansion-panel" elevation="0">
                                <v-expansion-panel>
                                    <v-expansion-panel-text>
                                    <div class="rbsm-mini-card">
                                         <v-row class="d-flex align-center rbsm-row-settings-2">
                                            <v-col cols="12" md="6" class="pa-0">
                                                <p class="rbsm-setting-properties-title-2"><v-icon class="rbsm-setting-icon">mdi-send-outline</v-icon>{{translate.subject}}</p>
                                                <div class="rbsm-setting-properties-content-2" >{{translate.emailSubjectDesc}}</div>
                                            </v-col>
                                            <v-col cols="12" md="6" class="rbsm-settings-input">
                                                <input class="rbsm-text-input" type="text" v-model="mailSubmitSubjectInput">
                                            </v-col>
                                        </v-row>
                                        <v-row class="d-flex align-center rbsm-row-settings-2">
                                            <v-col cols="12" md="6" class="pa-0">
                                                <p class="rbsm-setting-properties-title-2">{{translate.emailTitle}}</p>
                                                <div class="rbsm-setting-properties-content-2" >{{translate.emailTitleDesc}}</div>
                                            </v-col>
                                            <v-col cols="12" md="6" class="rbsm-settings-input">
                                                <input class="rbsm-text-input" type="text" v-model="mailSubmitTitleInput">
                                            </v-col>
                                        </v-row>
                                        <v-row class="d-flex rbsm-row-settings-2">
                                            <v-col cols="12" md="6" class="pa-0">
                                                <p class="rbsm-setting-properties-title-2">{{translate.message}}</p>
                                                <div class="rbsm-setting-properties-content-2" >{{translate.emailMessageDesc}}</div>
                                            </v-col>
                                            <v-col cols="12" md="6" class="rbsm-settings-input">
                                                <textarea class="rbsm-text-area" v-model="mailSubmitMessageInput"></textarea>
                                            </v-col>
                                        </v-row>
                                    </div>
                                    </v-expansion-panel-text>
                                </v-expansion-panel>
                            </v-expansion-panels>
                        </v-row>
                        <v-row class="d-flex align-center rbsm-row-settings">
                            <v-col cols="6" class="pa-0">
                                <p class="rbsm-setting-properties-title"><v-icon class="rbsm-setting-icon">mdi-publish</v-icon>{{translate.postPublishNotification}}</p>
                                <div class="rbsm-setting-properties-content">{{translate.postPublishNotificationDesc}}</div>
                            </v-col>
                            <v-col offset="4" offset-md="0" cols="2" class="pa-0 rbsm-checkbox-layout">
                                <label class="rbsm-import-checkbox rbsm-checkbox">
                                    <input v-model="allowPostPublishNotification" @change="allowPostPublishNotificationChange" type="checkbox" checked="checked">
                                    <span class="rbsm-checkbox-style"><i></i></span>
                                </label>
                            </v-col>
                        </v-row>
                        <v-row class="d-flex align-center rbsm-row-settings">
                            <v-expansion-panels v-model="postPublishPanel" multiple class="rbsm-mini-expansion-panel" elevation="0">
                                <v-expansion-panel>
                                    <v-expansion-panel-text>
                                    <div class="rbsm-mini-card">
                                        <v-row class="d-flex rbsm-row-settings-2">
                                            <v-col cols="12" md="6" class="pa-0">
                                                <p class="rbsm-setting-properties-title-2"><v-icon class="rbsm-setting-icon">mdi-send-outline</v-icon>{{translate.subject}}</p>
                                                <div class="rbsm-setting-properties-content-2" >{{translate.emailSubjectDesc}}</div>
                                            </v-col>
                                            <v-col cols="12" md="6" class="rbsm-settings-input">
                                                <input class="rbsm-text-input" type="text" v-model="mailPublishSubjectInput">
                                            </v-col>
                                        </v-row>
                                        <v-row class="d-flex rbsm-row-settings-2">
                                            <v-col cols="12" md="6" class="pa-0">
                                                <p class="rbsm-setting-properties-title-2">{{translate.emailTitle}}</p>
                                                <div class="rbsm-setting-properties-content-2" >{{translate.emailTitleDesc}}</div>
                                            </v-col>
                                            <v-col cols="12" md="6" class="rbsm-settings-input">
                                                <input class="rbsm-text-input" type="text" v-model="mailPublishTitleInput">
                                            </v-col>
                                        </v-row>
                                        <v-row class="d-flex rbsm-row-settings-2">
                                            <v-col cols="12" md="6" class="pa-0">
                                                <p class="rbsm-setting-properties-title-2">{{translate.message}}</p>
                                                <div class="rbsm-setting-properties-content-2" >{{translate.emailMessageDesc}}</div>
                                            </v-col>
                                            <v-col cols="12" md="6" class="rbsm-settings-input">
                                                <textarea class="rbsm-text-area" v-model="mailPublishMessageInput"></textarea>
                                            </v-col>
                                        </v-row>
                                      </div>
                                    </v-expansion-panel-text>
                                </v-expansion-panel>
                            </v-expansion-panels>
                        </v-row>
                        <v-row class="d-flex align-center rbsm-row-settings">
                            <v-col cols="6" class="pa-0">
                                <p class="rbsm-setting-properties-title"><v-icon class="rbsm-setting-icon">mdi-delete-variant</v-icon>{{translate.postTrashNotification}}</p>
                                <div class="rbsm-setting-properties-content">{{translate.postTrashNotificationDesc}}</div>
                            </v-col>
                            <v-col offset="4" offset-md="0" cols="2" class="pa-0 rbsm-checkbox-layout">
                                <label class="rbsm-import-checkbox rbsm-checkbox">
                                    <input v-model="allowPostTrashNotification" @change="allowPostTrashNotificationChange" type="checkbox" checked="checked">
                                    <span class="rbsm-checkbox-style"><i></i></span>
                                </label>
                            </v-col>
                        </v-row>
                        <v-row class="d-flex align-center rbsm-row-settings">
                            <v-expansion-panels v-model="postTrashPanel" multiple class="rbsm-mini-expansion-panel" elevation="0">
                                <v-expansion-panel>
                                    <v-expansion-panel-text>
                                        <div class="rbsm-mini-card">
                                            <v-row class="d-flex rbsm-row-settings-2">
                                                <v-col cols="12" md="6" class="pa-0">
                                                    <p class="rbsm-setting-properties-title-2"><v-icon class="rbsm-setting-icon">mdi-send-outline</v-icon>{{translate.subject}}</p>
                                                    <div class="rbsm-setting-properties-content-2" >{{translate.emailSubjectDesc}}</div>
                                                </v-col>
                                                <v-col cols="12" md="6" class="rbsm-settings-input">
                                                    <input class="rbsm-text-input" type="text" v-model="mailTrashSubjectInput">
                                                </v-col>
                                            </v-row>
                                            <v-row class="d-flex rbsm-row-settings-2">
                                                <v-col cols="12" md="6" class="pa-0">
                                                    <p class="rbsm-setting-properties-title-2">{{translate.emailTitle}}</p>
                                                    <div class="rbsm-setting-properties-content-2" >{{translate.emailTitleDesc}}</div>
                                                </v-col>
                                                <v-col cols="12" md="6" class="rbsm-settings-input">
                                                    <input class="rbsm-text-input" type="text" v-model="mailTrashTitleInput">
                                                </v-col>
                                            </v-row>
                                            <v-row class="d-flex rbsm-row-settings-2">
                                                <v-col cols="12" md="6" class="pa-0">
                                                    <p class="rbsm-setting-properties-title-2">{{translate.message}}</p>
                                                    <div class="rbsm-setting-properties-content-2" >{{translate.emailMessageDesc}}</div>
                                                </v-col>
                                                <v-col cols="12" md="6" class="rbsm-settings-input">
                                                    <textarea class="rbsm-text-area" v-model="mailTrashMessageInput"></textarea>
                                                </v-col>
                                            </v-row>
                                        </div>
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