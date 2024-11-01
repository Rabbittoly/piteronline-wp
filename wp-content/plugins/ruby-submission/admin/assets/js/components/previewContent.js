var rbAjax;
const previewContent = Vue.defineComponent( {
    name: 'previewContent',
    components: {
        recaptchaContent
    },
    data()
    {
        return {
            tags: Vue.ref( [] ),
            title: Vue.ref( '' ),
            quill: Vue.ref( null ),
            excerpt: Vue.ref( '' ),
            categories: Vue.ref( [] ),
            customFields: Vue.ref( [] ),
            tagsSelected: Vue.ref( '' ),
            userNameInput: Vue.ref( '' ),
            imagePreview: Vue.ref( null ),
            userEmailInput: Vue.ref( '' ),
            recaptchaSiteKey: Vue.ref( '' ),
            allowAddNewTags: Vue.ref( true ),
            isDisplayDialog: Vue.ref( false ),
            categoriesSelected: Vue.ref( '' ),
            editorType: Vue.ref( 'RawHTML' ),
            allowDisplayTags: Vue.ref( true ),
            featuredInputLabel: Vue.ref( '' ),
            isUsingRecaptcha: Vue.ref( true ),
            allowDisplayTitle: Vue.ref( true ),
            featuredImageFile: Vue.ref( null ),
            allowMultipleTags: Vue.ref( true ),
            customFieldsContent: Vue.ref( [] ),
            allowDisplayExcerpt: Vue.ref( true ),
            allowDisplayUserName: Vue.ref( true ),
            allowDisplayUserEmail: Vue.ref( true ),
            translate: Vue.ref( rbAjax.translate ),
            allowDisplayCategories: Vue.ref( true ),
            allowMultipleCategories: Vue.ref( true ),
            allowDisplayFeaturedImage: Vue.ref( true ),
            submitButtonText: Vue.ref( 'SUBMIT POST' ),
        }
    },
    methods: {
        initQuillEditor()
        {
            this.quill = new Quill( '#editor', {
                theme: 'snow',
                modules: {
                    toolbar: this.editorType === 'Rich Editor'
                        ? [
                            [ { header: [ 2, 3, 4, 5, 6, false ] } ],
                            this.amountUploadMedia > 0 ?
                                [ 'image', 'bold', 'italic', 'underline', 'strike' ] :
                                [ 'bold', 'italic', 'underline', 'strike' ],
                            [ { list: 'ordered' }, { list: 'bullet' } ],
                            [ 'link', 'blockquote', 'code-block' ],
                            [ { align: [] } ],
                            [ { 'indent': '-1' }, { 'indent': '+1' } ],
                            [ { direction: 'rtl' } ]
                        ]
                        : []
                }
            } );

            this.quill.on( 'text-change', () =>
            {
                this.content = this.quill.root.innerHTML;
            } );
        },
        displayFormPreview( formSettingsData )
        {
            this.isDisplayDialog = true;

            const formData = JSON.parse( formSettingsData.data );
            const formFields = formData[ 'form_fields' ];
            const securityFields = formData[ 'security_fields' ];

            this.allowDisplayTitle = formFields[ 'post_title' ] !== 'Disable';
            this.allowDisplayExcerpt = formFields[ 'tagline' ] !== 'Disable';
            this.allowDisplayFeaturedImage = formFields[ 'featured_image' ][ 'status' ] !== 'Disable';
            this.allowDisplayUserName = formFields[ 'user_name' ] !== 'Disable';
            this.allowDisplayUserEmail = formFields[ 'user_email' ] !== 'Disable';
            this.editorType = formFields[ 'editor_type' ] ?? 'Rich Editor';
            this.customFields = formFields[ 'custom_field' ];
            this.isUsingRecaptcha = securityFields[ 'recaptcha' ][ 'status' ];
            this.recaptchaSiteKey = securityFields[ 'recaptcha' ][ 'recaptcha_site_key' ];

            this.$nextTick( () =>
            {
                this.initQuillEditor();
                this.updateHeightEditor( formFields );
            } );
        },
        updateHeightEditor( formFields )
        {
            const rbTextAreaEditor = document.querySelector( '#rbTextAreaEditor' );
            const richEditor = document.querySelector( '#editor' );
            const height = this.getFitHeightForEditor( formFields );

            rbTextAreaEditor.style.height = `${height}px`;
            richEditor.style.height = `${height}px`;
        },
        getFitHeightForEditor( formFields )
        {
            let propertiesColHeight = 354;
            propertiesColHeight += formFields[ 'featured_image' ][ 'status' ] !== 'Disable' ? 156 : 0;
            propertiesColHeight += formFields[ 'user_email' ] !== 'Disable' ? 156 : 0;
            propertiesColHeight += formFields[ 'user_name' ] !== 'Disable' ? 156 : 0;
            propertiesColHeight += formFields[ 'editor_type' ] === 'RawHTML' ? 79 : 0;

            return Math.max( propertiesColHeight - 156, 500 );
        }
    },
    template: `
        <v-container>
            <v-row>
                <div class="pa-4 text-center">
                    <v-dialog v-model="isDisplayDialog" max-width="1280" transition="dialog-bottom-transition">
                        <v-card block>
                            <v-row class="ml-5 mt-5">
                                <v-col cols="8">
                                    <v-text-field v-show="allowDisplayTitle" v-model="title" label="Title" variant="outlined"></v-text-field>
                                    <v-text-field v-show="allowDisplayExcerpt" v-model="excerpt" label="Excerpt" variant="outlined"></v-text-field>
                                    <div id="editorContainer">
                                        <div id="richEditor" v-show="editorType === 'Rich Editor'">
                                            <div id="editor"></div>
                                        </div>
                                        <v-textarea id="rbTextAreaEditor" variant="outlined" v-show="editorType === 'RawHTML'"></v-textarea>
                                    </div>
                                </v-col>
                                <v-col cols="4">
                                    <v-card v-if="allowDisplayCategories" :title="translate.categories" variant="outlined" class="mb-5">
                                        <v-col cols="11">
                                            <v-autocomplete
                                                density="compact"
                                                clearable
                                                chips
                                                v-model="categoriesSelected"
                                                variant="outlined"
                                                :label="translate.chooseCategories"
                                                :items="categories"
                                                :multiple="allowMultipleCategories"
                                            ></v-autocomplete>
                                        </v-col>
                                    </v-card>
                                    <v-card  v-if="allowDisplayTags" :title="translate.tags" variant="outlined" class="mb-5">
                                        <v-col cols="11">
                                            <v-combobox
                                                v-show="allowAddNewTags"
                                                clearable
                                                chips
                                                :multiple="allowMultipleTags"
                                                v-model="tagsSelected"
                                                variant="outlined"
                                                :label="translate.chooseTags"
                                                :items="tags"
                                            ></v-combobox>
                                            <v-autocomplete
                                                density="compact"
                                                v-show="!allowAddNewTags"
                                                clearable
                                                chips
                                                v-model="tagsSelected"
                                                variant="outlined"
                                                :label="translate.chooseTags"
                                                :items="tags"
                                                :multiple="allowMultipleTags"
                                            ></v-autocomplete>
                                        </v-col>
                                    </v-card>
                                    <v-card  v-if="allowDisplayFeaturedImage" :title="translate.featuredImage" variant="outlined" class="mb-5">
                                        <v-col cols="11">
                                            <v-form ref="form">
                                                <v-img
                                                    v-if="imagePreview"
                                                    :src="imagePreview"
                                                    max-width="400"
                                                ></v-img>

                                                <v-file-input
                                                    class="mt-5"
                                                    v-model="featuredImageFile"
                                                    :label="featuredInputLabel"
                                                    accept="image/*"
                                                    variant="outlined"
                                                    prepend-icon="mdi-image"
                                                    :show-size="1000"
                                                ></v-file-input>
                                            </v-form>
                                        </v-col>
                                    </v-card>
                                    <v-card v-show="allowDisplayUserName" :title="translate.userName" variant="outlined" class="mb-5">
                                        <v-col cols="11">
                                            <v-text-field v-model="userNameInput" variant="outlined" :label="translate.typeUserName"></v-text-field>
                                        </v-col>
                                    </v-card>
                                    <v-card v-show="allowDisplayUserEmail" :title="translate.userEmail" variant="outlined" class="mb-5">
                                        <v-col cols="11">
                                            <v-text-field v-model="userEmailInput" variant="outlined":label="translate.typeUserEmail"></v-text-field>
                                        </v-col>
                                    </v-card>
                                </v-col>
                            </v-row>
                            <v-row class="ml-5">
                                <v-col cols="8">
                                    <div v-for="(field, index) in customFields">
                                        <v-card :title="field['custom_field_label']" variant="outlined" class="mb-5">
                                            <v-col cols="11">
                                                <v-text-field
                                                    v-show="field['field_type'] === 'Text'"
                                                    v-model="customFieldsContent[index + '_Text']"
                                                    :label="translate.typeAnythings"
                                                    variant="outlined">
                                                </v-text-field>
                                                <v-textarea
                                                    v-show="field['field_type'] === 'Textarea'"
                                                    v-model="customFieldsContent[index + '_Textarea']"
                                                    :label="translate.message"
                                                    variant="outlined">
                                                </v-textarea>

                                                <v-file-input
                                                    v-show="field['field_type'] === 'Upload'"
                                                    v-model="customFieldsContent[index + '_Upload']"
                                                    :label="translate.uploadFile"
                                                    outlined
                                                    variant="outlined"
                                                ></v-file-input>
                                            </v-col>
                                        </v-card>
                                    </div>
                                </v-col>
                            </v-row>
                            <v-row class="ml-5">
                                <v-col v-show="isUsingRecaptcha">
                                    <recaptchaContent
                                        :shouldLoadRecaptcha="isUsingRecaptcha"
                                        :siteKey="recaptchaSiteKey"
                                        ref="recaptchaComponent"
                                    />
                                </v-col>
                            </v-row>
                            <v-row class="ml-5 mb-5">
                                <v-col cols="2">
                                    <v-btn
                                        color="blue"
                                    >{{submitButtonText}}
                                    </v-btn>
                                </v-col>
                            </v-row>
                        </v-card>
                    </v-dialog>
                </div>
            </v-row>
        </v-container>
    `
} );