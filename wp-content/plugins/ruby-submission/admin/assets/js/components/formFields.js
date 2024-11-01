var rbAjax;
const formFieldsContent = Vue.defineComponent( {
    name: 'formFieldsContent',
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
            snackbar: Vue.ref( false ),
            snackbarClass: Vue.ref( 'rbsm-failed-snackbar' ),
            snackbarMessage: Vue.ref( '' ),
            tagIds: [],
            categoryIds: [],
            maxCustomFields: 2,
            tags: Vue.ref( [] ),
            panel: Vue.ref( [] ),
            categories: Vue.ref( [] ),
            excludeTagSelectedIds: [],
            customFields: Vue.ref( [] ),
            autoAssignTagSelectedIds: [],
            imagePreview: Vue.ref( null ),
            excludeCategorySelectedIds: [],
            allowMultiTags: Vue.ref( false ),
            autoAssignCategorySelectedIds: [],
            featuredImagePanel: Vue.ref( [] ),
            allowAddNewTags: Vue.ref( false ),
            excludeTagsSelected: Vue.ref( [] ),
            translate: Vue.ref( rbAjax.translate ),
            defaultFeaturedImageID: Vue.ref( 0 ),
            taglineSelected: Vue.ref( 'Require' ),
            autoAssignTagsSelected: Vue.ref( [] ),
            userNameSelected: Vue.ref( 'Require' ),
            excludeCategorySelected: Vue.ref( [] ),
            uploadFileSizeLimitInput: Vue.ref( 0 ),
            allowMultiCategories: Vue.ref( false ),
            userEmailSelected: Vue.ref( 'Require' ),
            postTitleSelected: Vue.ref( 'Require' ),
            autoAssignCategotySelected: Vue.ref( [] ),
            featuredImageSelected: Vue.ref( 'Disable' ),
            editorTypeSelected: Vue.ref( 'Rich Editor' ),
            taglineItems: Vue.ref( [ 'Require', 'Optional', 'Disable' ] ),
            customFieldItems: Vue.ref( [ 'Text', 'Textarea', 'Upload' ] ),
            userNameItems: Vue.ref( [ 'Require', 'Optional', 'Disable' ] ),
            postTitleItems: Vue.ref( [ 'Require', 'Optional', 'Disable' ] ),
            userEmailItems: Vue.ref( [ 'Require', 'Optional', 'Disable' ] ),
            featuredImageItems: Vue.ref( [ 'Require', 'Optional', 'Disable' ] ),
            editorTypeItems: Vue.ref( [ 'Rich Editor', 'RawHTML' ] ),
            isFeaturedImageLoading: Vue.ref( false ),
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
                    "form_fields": {
                        "user_name": this.userNameSelected, // ["Require", "Optional", "Disable"]
                        "user_email": this.userEmailSelected, // ["Require", "Optional", "Disable"]
                        "post_title": this.postTitleSelected, // ["Require", "Optional", "Disable"]
                        "tagline": this.taglineSelected, // ["Require", "Optional", "Disable"]
                        "editor_type": this.editorTypeSelected, // ["Rich Editor", "Textarea", "HTML Editor"]
                        "featured_image": {
                            "status": this.featuredImageSelected, // ["Require", "Optional", "Disable"]
                            "upload_file_size_limit": this.uploadFileSizeLimitInput,
                            "default_featured_image": this.defaultFeaturedImageID
                        },
                        "categories": {
                            "multiple_categories": this.allowMultiCategories,
                            "exclude_categories": this.excludeCategorySelected,
                            "exclude_category_ids": this.excludeCategorySelectedIds,
                            "auto_assign_categories": this.autoAssignCategotySelected,
                            "auto_assign_category_ids": this.autoAssignCategorySelectedIds
                        },
                        "tags": {
                            "multiple_tags": this.allowMultiTags,
                            "allow_add_new_tag": this.allowAddNewTags,
                            "exclude_tags": this.excludeTagsSelected,
                            "exclude_tag_ids": this.excludeTagSelectedIds,
                            "auto_assign_tags": this.autoAssignTagsSelected,
                            "auto_assign_tag_ids": this.autoAssignTagSelectedIds
                        },
                        "custom_field": this.customFields
                    }
                };

                if( !this.isValidCustomFieldsName() )
                {
                    alert( this.translate.warningSameCustomFieldName );
                    this.sendDataToSave( {} );
                }
                else
                {
                    this.sendDataToSave( data );
                }
            }
        },
        panel()
        {
            localStorage.setItem( 'rbsm_admin_form_fields_panel', this.panel );
        }
    },
    mounted()
    {
        this.getLocalStorageValue();
        this.getCategories();
        this.getTags();
    },
    methods: {
        getLocalStorageValue()
        {
            const panelValue = localStorage.getItem( 'rbsm_admin_form_fields_panel' ) || [];
            this.panel = panelValue;
        },
        getCategories()
        {
            return new Promise( ( resolve, reject ) =>
            {
                const formData = new FormData();
                formData.append( 'action', 'rbsm_admin_get_categories' );
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
                            this.categories = data.data.map( category => category.name );
                            this.categoryIds = data.data.map( category => category.term_id );
                            resolve();
                        }
                        else
                        {
                            resolve();
                        }
                    } )
                    .catch( error =>
                    {
                        this.displayErrorDialog( error );
                        console.log( error );
                    } );
            } );
        },
        getTags()
        {
            return new Promise( ( resolve, reject ) =>
            {
                const formData = new FormData();
                formData.append( 'action', 'rbsm_admin_get_tags' );
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
                            this.tags = data.data.map( tag => tag.name );
                            this.tagIds = data.data.map( tag => tag.term_id );
                            resolve();
                        }
                        else
                        {
                            resolve();
                        }
                    } )
                    .catch( error =>
                    {
                        this.displayErrorDialog( error );
                        console.log( error );
                    } );
            } );
        },
        addNewField()
        {
            if( this.customFields.length >= this.maxCustomFields )
            {
                alert( this.translate.maxCustomField );
                return;
            }

            this.customFields.push( {
                custom_field_label: Vue.ref( '' ),
                custom_field_name: Vue.ref( '' ),
                field_type: Vue.ref( 'Text' )
            } );
        },
        deleteCustomField( customFieldIndex )
        {
            this.customFields.splice( customFieldIndex, 1 );
        },
        featuredImageChange( value )
        {
            if( value === 'Disable' )
                this.featuredImagePanel = [];
            else
                this.featuredImagePanel = [ 0 ];

            this.displayDefaultFeaturedImage();
        },
        displayDefaultFeaturedImage()
        {
            if( typeof wp === 'undefined' || typeof wp.media === 'undefined' )
            {
                alert( this.translate.wordpressMediaError );
                return;
            }

            const attachment = wp.media.attachment( this.defaultFeaturedImageID );

            attachment.fetch().then( () =>
            {
                this.imagePreview = attachment.attributes.url;
            } );
        },
        updateUIWithData()
        {
            this.userNameSelected = this.data[ 'user_name' ] ?? '';
            this.userEmailSelected = this.data[ 'user_email' ] ?? '';
            this.postTitleSelected = this.data[ 'post_title' ] ?? '';
            this.taglineSelected = this.data[ 'tagline' ] ?? '';
            this.editorTypeSelected = this.data[ 'editor_type' ] ?? '';
            this.featuredImageSelected = this.data[ 'featured_image' ]?.[ 'status' ] ?? '';
            this.uploadFileSizeLimitInput = this.data[ 'featured_image' ]?.[ 'upload_file_size_limit' ] ?? 0;
            this.defaultFeaturedImageID = this.data[ 'featured_image' ]?.[ 'default_featured_image' ] ?? '';
            this.allowMultiCategories = this.data[ 'categories' ]?.[ 'multiple_categories' ] ?? false;
            this.excludeCategorySelected = this.data[ 'categories' ]?.[ 'exclude_categories' ] ?? [];
            this.excludeCategorySelectedIds = this.data[ 'categories' ]?.[ 'exclude_category_ids' ] ?? [];
            this.autoAssignCategotySelected = this.data[ 'categories' ]?.[ 'auto_assign_categories' ] ?? [];
            this.autoAssignCategorySelectedIds = this.data[ 'categories' ]?.[ 'auto_assign_category_ids' ] ?? [];
            this.allowMultiTags = this.data[ 'tags' ]?.[ 'multiple_tags' ] ?? false;
            this.allowAddNewTags = this.data[ 'tags' ]?.[ 'allow_add_new_tag' ] ?? false;
            this.excludeTagsSelected = this.data[ 'tags' ]?.[ 'exclude_tags' ] ?? [];
            this.excludeTagSelectedIds = this.data[ 'tags' ]?.[ 'exclude_tag_ids' ] ?? [];
            this.autoAssignTagsSelected = this.data[ 'tags' ]?.[ 'auto_assign_tags' ] ?? [];
            this.autoAssignTagSelectedIds = this.data[ 'tags' ]?.[ 'auto_assign_tag_ids' ] ?? [];
            this.customFields = this.data[ 'custom_field' ] ?? [];

            this.featuredImageChange( this.featuredImageSelected );
        },
        changeAllowMultiCategories( value )
        {
            this.allowMultiCategories = value;
        },
        changeAllowMultiTags( value )
        {
            this.allowMultiTags = value;
        },
        changeAllowAddNewTags( value )
        {
            this.allowAddNewTags = value;
        },
        excludeCategoryChange( values )
        {
            this.excludeCategorySelectedIds = [];
            values.forEach( value =>
            {
                const index = this.categories.findIndex( category => category === value );
                this.excludeCategorySelectedIds.push( this.categoryIds[ index ] );
            } );
        },
        autoAssignCategoryChange( values )
        {
            this.autoAssignCategorySelectedIds = [];
            values.forEach( value =>
            {
                const index = this.categories.findIndex( category => category === value );
                this.autoAssignCategorySelectedIds.push( this.categoryIds[ index ] );
            } );
        },
        excludeTagChange( values )
        {
            this.excludeTagSelectedIds = [];
            values.forEach( value =>
            {
                const index = this.tags.findIndex( tag => tag === value );
                this.excludeTagSelectedIds.push( this.tagIds[ index ] );
            } );
        },
        autoAssignTagChange( values )
        {
            this.autoAssignTagSelectedIds = [];
            values.forEach( value =>
            {
                const index = this.tags.findIndex( tag => tag === value );
                this.autoAssignTagSelectedIds.push( this.tagIds[ index ] );
            } );
        },
        isValidCustomFieldsName()
        {
            if( this.customFields <= 1 ) return true;

            const arraySet = this.customFields.map( customField => customField[ 'custom_field_name' ] );

            return new Set( arraySet ).size === this.customFields.length;
        },
        openMediaUploader()
        {
            const self = this;

            if( typeof wp === 'undefined' || typeof wp.media === 'undefined' )
            {
                console.log( this.translate.wordpressMediaError );
                return;
            }

            const mediaUploader = wp.media( {
                title: this.translate.selectMedia,
                button: {
                    text: this.translate.useThisMedia
                },
                multiple: false
            } );

            mediaUploader.on( 'select', function ()
            {
                const attachment = mediaUploader.state().get( 'selection' ).first().toJSON();

                if( self.imagePreview !== attachment.url ) self.isFeaturedImageLoading = true;

                self.imagePreview = attachment.url;
                self.defaultFeaturedImageID = attachment.id;
            } );

            mediaUploader.open();
        },
        removeDefaultFeaturedImage()
        {
            this.imagePreview = null;
            this.defaultFeaturedImageID = 0;
        },
        onFeaturedImageLoaded()
        {
            this.isFeaturedImageLoading = false;
        },
        displayErrorDialog( errorMessage )
        {
            this.snackbarClass = 'rbsm-failed-snackbar';
            this.snackbarMessage = errorMessage;
            this.snackbar = true;
        }
    },
    template: `
        <div class="rbsm-fullwidth rbsm-snackbar-container">
            <v-snackbar v-model="snackbar" :class="[snackbarClass]" :timeout="3000">
                <v-icon class="pr-2">mdi-alert-outline</v-icon>{{ snackbarMessage }}
            </v-snackbar>
            <v-expansion-panels v-model="panel" multiple class="rbsm-expansion-panel" elevation="0">
                <v-expansion-panel>
                    <v-expansion-panel-title>
                        <p class="rbsm-settings-title"><v-icon class="mr-2">mdi-checkbox-marked-outline</v-icon>{{translate.formFields}}</p>
                    </v-expansion-panel-title>
                    <v-expansion-panel-text>
                        <div class="rbsm-settings-list">
                             <v-row class="d-flex align-center rbsm-row-settings">
                                <v-col class="pa-0" cols="12" md="6">
                                    <p class="rbsm-setting-properties-title">{{translate.userName}}</p>
                                    <div class="rbsm-setting-properties-content">{{translate.userNameDesc}}</div>
                                </v-col>
                                <v-col cols="12" md="6" class="rbsm-settings-input">
                                    <v-select
                                        class="rbsm-select"
                                        density="compact"
                                        v-model="userNameSelected"
                                        :items="userNameItems"
                                        variant="outlined"
                                        hide-details
                                    ></v-select>
                                </v-col>
                            </v-row>
                            <v-row class="d-flex align-center rbsm-row-settings">
                                <v-col class="pa-0" cols="12" md="6">
                                    <p class="rbsm-setting-properties-title">{{translate.userEmail}}</p>
                                    <div class="rbsm-setting-properties-content">{{translate.userEmailDesc}}</div>
                                </v-col>
                                <v-col cols="12" md="6" class="rbsm-settings-input">
                                    <v-select
                                        class="rbsm-select"
                                        density="compact"
                                        v-model="userEmailSelected"
                                        :items="userEmailItems"
                                        variant="outlined"
                                        hide-details
                                    ></v-select>
                                </v-col>
                            </v-row>
                            <v-row class="d-flex align-center rbsm-row-settings">
                                <v-col class="pa-0" cols="12" md="6">
                                    <p class="rbsm-setting-properties-title">{{translate.postTitle}}</p>
                                    <div class="rbsm-setting-properties-content">{{translate.postTitleDesc}}</div>
                                </v-col>
                                <v-col cols="12" md="6" class="rbsm-settings-input">
                                    <v-select
                                        class="rbsm-select"
                                        density="compact"
                                        v-model="postTitleSelected"
                                        :items="postTitleItems"
                                        variant="outlined"
                                        hide-details
                                    ></v-select>
                                </v-col>
                            </v-row>
                            <v-row class="d-flex align-center rbsm-row-settings">
                                <v-col cols="12" md="6" class="pa-0">
                                    <p class="rbsm-setting-properties-title">{{translate.tagline}}</p>
                                    <div class="rbsm-setting-properties-content">{{translate.taglineDesc}}</div>
                                </v-col>
                                <v-col cols="12" md="6" class="rbsm-settings-input">
                                    <v-select
                                        class="rbsm-select"
                                        density="compact"
                                        v-model="taglineSelected"
                                        :items="taglineItems"
                                        variant="outlined"
                                        hide-details
                                    ></v-select>
                                </v-col>
                            </v-row>
                            <v-row class="d-flex align-center rbsm-row-settings">
                                <v-col cols="12" md="6" class="pa-0">
                                    <p class="rbsm-setting-properties-title">{{translate.editorType}}</p>
                                    <div class="rbsm-setting-properties-content">{{translate.editorTypeDesc}}</div>
                                </v-col>
                                <v-col cols="12" md="6" class="rbsm-settings-input">
                                    <v-select
                                        class="rbsm-select"
                                        density="compact"
                                        v-model="editorTypeSelected"
                                        :items="editorTypeItems"
                                        variant="outlined"
                                        hide-details
                                    ></v-select>
                                </v-col>
                            </v-row>
                            <v-row class="d-flex align-center rbsm-row-settings rbsm-top-border">
                                <v-col cols="12" md="6" class="pa-0">
                                    <p class="rbsm-setting-properties-title"><v-icon class="rbsm-setting-icon">mdi-image-outline</v-icon>{{translate.featuredImage}}</p>
                                    <div class="rbsm-setting-properties-content">{{translate.featuredImageDesc}}</div>
                                </v-col>
                                <v-col cols="12" md="6" class="rbsm-settings-input">
                                    <v-select
                                        class="rbsm-select"
                                        density="compact"
                                        v-model="featuredImageSelected"
                                        :items="featuredImageItems"
                                        variant="outlined"
                                        @update:modelValue="featuredImageChange"
                                        hide-details
                                    ></v-select>
                                </v-col>
                            </v-row>
                            <v-row class="d-flex align-center rbsm-row-settings">
                                <v-expansion-panels v-model="featuredImagePanel" multiple class="rbsm-mini-expansion-panel" elevation="0">
                                    <v-expansion-panel>
                                        <v-expansion-panel-text>
                                            <v-row class="d-flex align-center rbsm-row-settings-2">
                                                <v-col cols="12" md="3" class="pa-0">
                                                    <p class="rbsm-setting-properties-title-2">{{translate.uploadFileSizeLimit}}</p>
                                                    <div class="rbsm-setting-properties-content-2">{{translate.uploadFileSizeLimitDesc}}</div>
                                                </v-col>
                                                <v-col offset-md="3" md="6" cols="12" class="rbsm-settings-input">
                                                    <input class="rbsm-text-input" type="number" v-model="uploadFileSizeLimitInput">
                                                </v-col>
                                            </v-row>
                                            <v-row class="rbsm-row-settings-2">
                                                <v-col cols="12" md="3" class="pa-0">
                                                    <p class="rbsm-setting-properties-title-2">{{translate.defaultFeaturedImage}}</p>
                                                    <div class="rbsm-setting-properties-content-2">{{translate.defaultFeaturedImageDesc}}</div>
                                                </v-col>
                                                <v-col offset-md="3" md="6" cols="12" class="rbsm-settings-input">
                                                    <button class="rbsm-black-btn mb-4 rbsm-transition rbsm-access-btn" @click="openMediaUploader">Choose Image</button>
                                                    <v-row>
                                                        <v-col cols="8">
                                                            <v-img
                                                                v-if="imagePreview"
                                                                :src="imagePreview"
                                                                max-width="400"
                                                                @load="onFeaturedImageLoaded"
                                                                @error="onFeaturedImageLoaded"
                                                            ></v-img>
                                                            <v-progress-circular indeterminate size="26" v-show="isFeaturedImageLoading"></v-progress-circular>
                                                        </v-col>
                                                        <v-col cols="4" class="d-flex align-end justify-end">
                                                            <button v-show="imagePreview !== null" class="rbsm-remove-field-btn rbsm-white-btn rbsm-btn-red rbsm-delete-customfield rbsm-transition" @click="removeDefaultFeaturedImage">
                                                                <v-icon>mdi-delete-outline</v-icon>Remove Image
                                                            </button>
                                                        </v-col>
                                                    </v-row>
                                                </v-col>
                                            </v-row>
                                        </v-expansion-panel-text>
                                    </v-expansion-panel>
                                </v-expansion-panels>
                            </v-row>
                            <v-row class="d-flex align-center rbsm-row-settings-2 rbsm-top-border">
                                <v-col cols="6" class="pa-0">
                                    <p class="rbsm-setting-properties-title-2"><v-icon class="rbsm-setting-icon">mdi-folder-file-outline</v-icon>{{translate.multipleCategories}}</p>
                                    <div class="rbsm-setting-properties-content-2">{{translate.multipleCategoriesDesc}}</div>
                                </v-col>
                                <v-col offset="4" offset-md="0" cols="2" class="pa-0 rbsm-checkbox-layout">
                                    <label class="rbsm-import-checkbox rbsm-checkbox">
                                        <input v-model="allowMultiCategories" type="checkbox" checked="checked">
                                        <span class="rbsm-checkbox-style"><i></i></span>
                                    </label>
                                </v-col>
                            </v-row>
                            <v-row class="d-flex align-center rbsm-row-settings-2">
                                <v-col cols="12" md="6" class="pa-0">
                                    <p class="rbsm-setting-properties-title-2">{{translate.autoAssignCategories}}</p>
                                    <div class="rbsm-setting-properties-content-2">{{translate.autoAssignCategoriesDesc}}</div>
                                </v-col>
                                <v-col cols="12" md="6" class="rbsm-settings-input">
                                    <v-autocomplete
                                        class="rbsm-autocomplete"
                                        density="compact"
                                        clearable
                                        chips
                                        v-model="autoAssignCategotySelected"
                                        variant="outlined"
                                        :label="translate.autoAssignCategories"
                                        :items="categories"
                                        multiple
                                        @update:modelValue="autoAssignCategoryChange"
                                    ></v-autocomplete>
                                </v-col>
                            </v-row>
                            <v-row class="d-flex align-center rbsm-row-settings-2">
                                <v-col cols="12" md="6" class="pa-0">
                                    <p class="rbsm-setting-properties-title-2">{{translate.excludeCategories}}</p>
                                    <div class="rbsm-setting-properties-content-2">{{translate.excludeCategoriesDesc}}</div>
                                </v-col>
                                <v-col cols="12" md="6" class="rbsm-settings-input">
                                    <v-autocomplete
                                        class="rbsm-autocomplete"
                                        density="compact"
                                        clearable
                                        chips
                                        v-model="excludeCategorySelected"
                                        variant="outlined"
                                        :label="translate.excludeCategories"
                                        :items="categories"
                                        multiple
                                        @update:modelValue="excludeCategoryChange"
                                        elevation="0"
                                    ></v-autocomplete>
                                </v-col>
                            </v-row>
                            <v-row class="d-flex align-center rbsm-row-settings-2 rbsm-top-border">
                                <v-col cols="6" class="pa-0">
                                    <p class="rbsm-setting-properties-title-2"><v-icon class="rbsm-setting-icon">mdi-tag-multiple-outline</v-icon>{{translate.multipleTags}}</p>
                                    <div class="rbsm-setting-properties-content-2">{{translate.multipleTagsDesc}}</div>
                                </v-col>
                                <v-col offset="4" offset-md="0" cols="2" class="pa-0 rbsm-checkbox-layout">
                                    <label class="rbsm-import-checkbox rbsm-checkbox">
                                        <input v-model="allowMultiTags" type="checkbox" checked="checked">
                                        <span class="rbsm-checkbox-style"><i></i></span>
                                    </label>
                                </v-col>
                            </v-row>
                             <v-row class="d-flex align-center rbsm-row-settings-2">
                                <v-col cols="12" md="6" class="pa-0">
                                    <p class="rbsm-setting-properties-title-2">{{translate.autoAssignTags}}</p>
                                    <div class="rbsm-setting-properties-content-2">{{translate.autoAssignTagsDesc}}</div>
                                </v-col>
                                <v-col cols="12" md="6" class="rbsm-settings-input">
                                    <v-autocomplete
                                        class="rbsm-autocomplete"
                                        density="compact"
                                        clearable
                                        chips
                                        v-model="autoAssignTagsSelected"
                                        variant="outlined"
                                        :label="translate.autoAssignTags"
                                        :items="tags"
                                        multiple
                                        @update:modelValue="autoAssignTagChange"
                                    ></v-autocomplete>
                                </v-col>
                            </v-row>
                            <v-row class="d-flex align-center rbsm-row-settings-2">
                                <v-col cols="12" md="6" class="pa-0">
                                    <p class="rbsm-setting-properties-title-2">{{translate.excludeTags}}</p>
                                    <div class="rbsm-setting-properties-content-2">{{translate.excludeTagsDesc}}</div>
                                </v-col>
                                <v-col cols="12" md="6" class="rbsm-settings-input">
                                    <v-autocomplete
                                        class="rbsm-autocomplete"
                                        density="compact"
                                        clearable
                                        chips
                                        v-model="excludeTagsSelected"
                                        variant="outlined"
                                        :label="translate.excludeTags"
                                        :items="tags"
                                        multiple
                                        @update:modelValue="excludeTagChange"
                                    ></v-autocomplete>
                                </v-col>
                            </v-row>
                            <v-row class="d-flex align-center rbsm-row-settings-2">
                                <v-col cols="6" class="pa-0">
                                    <p class="rbsm-setting-properties-title-2">{{translate.allowAddNewTags}}</p>
                                    <div class="rbsm-setting-properties-content-2">{{translate.allowAddNewTagsDesc}}</div>
                                </v-col>
                                <v-col offset="4" offset-md="0" cols="2" class="pa-0 rbsm-checkbox-layout">
                                    <label class="rbsm-import-checkbox rbsm-checkbox">
                                        <input v-model="allowAddNewTags" type="checkbox" checked="checked">
                                        <span class="rbsm-checkbox-style"><i></i></span>
                                    </label>
                                </v-col>
                            </v-row>
                            <v-row class="d-flex align-center rbsm-row-settings rbsm-top-border">
                                <v-col cols="12" sm="6" class="pa-0">
                                    <p class="rbsm-setting-properties-title"><v-icon class="rbsm-setting-icon">mdi-tag-edit-outline</v-icon>{{translate.customFields}}</p>
                                    <div class="rbsm-setting-properties-content">{{translate.customFieldsDesc}}</div>
                                </v-col>
                                <v-col cols="12" sm="6" class="d-flex justify-end pa-0">
                                    <button @click="addNewField" class="rbsm-black-btn rbsm-access-btn rbsm-transition">
                                        <v-icon>mdi-plus</v-icon>{{translate.addNewField}}
                                    </button>
                                </v-col>
                            </v-row>
                            <v-row class="d-flex align-center rbsm-row-settings">
                                <v-col cols="12" class="pa-0" v-for="(field, index) in customFields">
                                    <v-card class="rbsm-mini-card" elevation="0">
                                        <v-row class="d-flex align-center rbsm-row-settings-2">
                                            <v-col cols="12" md="6" class="pa-0">
                                                <p class="rbsm-setting-properties-title-2"><v-icon class="rbsm-setting-icon">mdi-key-outline</v-icon>{{translate.customFieldName}}</p>
                                                <div class="rbsm-setting-properties-content-2">{{translate.customFieldNameDesc}}</div>
                                            </v-col>
                                            <v-col cols="12" md="6" class="rbsm-settings-input">
                                                <input class="rbsm-text-input" type="text" v-model="field['custom_field_name']">
                                            </v-col>
                                        </v-row>
                                        <v-row class="d-flex align-center rbsm-row-settings-2">
                                            <v-col cols="12" md="6" class="pa-0">
                                                <p class="rbsm-setting-properties-title-2">{{translate.customFieldLabel}}</p>
                                                <div class="rbsm-setting-properties-content-2">{{translate.customFieldLabelDesc}}</div>
                                            </v-col>
                                            <v-col cols="12" md="6" class="rbsm-settings-input">
                                                <input class="rbsm-text-input" type="text" v-model="field['custom_field_label']">
                                            </v-col>
                                        </v-row>
                                        <v-row class="d-flex align-center rbsm-row-settings-2">
                                            <v-col cols="12" md="6" class="pa-0">
                                                <p class="rbsm-setting-properties-title-2">{{translate.fieldType}}</p>
                                                <div class="rbsm-setting-properties-content-2">{{translate.fieldTypeDesc}}</div>
                                            </v-col>
                                            <v-col cols="12" md="6" class="rbsm-settings-input">
                                                <v-select
                                                    class="rbsm-select"
                                                    density="compact"
                                                    v-model="field['field_type']"
                                                    :items="customFieldItems"
                                                    variant="outlined"
                                                    hide-details
                                                ></v-select>
                                            </v-col>
                                            <v-col cols="12" md="2" offset-md="10" class="d-flex justify-end pa-0">
                                                <button class="rbsm-remove-field-btn rbsm-white-btn rbsm-btn-red rbsm-delete-customfield rbsm-transition" @click="deleteCustomField(index)">
                                                    <v-icon>mdi-delete-outline</v-icon>{{translate.removeField}}
                                                </button>
                                            </v-col>
                                        </v-row>
                                    </v-card>
                                </v-col>
                            </v-row>
                        </div>
                    </v-expansion-panel-text>
                </v-expansion-panel>
            </v-expansion-panels>
        </div>
    `
} );
