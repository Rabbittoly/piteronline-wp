var rbLocalizeData;
const userPostsContent = Vue.defineComponent( {
    name: 'userPostsContent',
    data()
    {
        return {
            paged: 1,
            formSubmissionId: -1,
            postIdShouldRemove: -1,
            userPosts: Vue.ref( [] ),
            snackbar: Vue.ref( false ),
            snackbarMessage: Vue.ref( '' ),
            deleteDialogMessage: Vue.ref( '' ),
            isSamePageWithEditor: Vue.ref( false ),
            shouldDisplayPostView: Vue.ref( false ),
            isDisplayMorePostButton: Vue.ref( true ),
            translate: Vue.ref( rbLocalizeData.translate ),
            isDisplayDeleteConfirmationDialog: Vue.ref( false ),
            overlayVisible: Vue.ref( false ),
            shouldDisplayPostAction: Vue.ref( false ),
            shouldDisplayPostEdit: Vue.ref( false ),
            shouldDisplayPostDelete: Vue.ref( false ),
            postEditingUrl: '',
            isLoadingPosts: Vue.ref( false ),
            yesStorage: false,
        };
    },
    created()
    {
        this.yesStorage = this.isStorageAvailable();
        this.updatePostManagerSettings();
        this.getUserPostsStart();
    },
    methods: {
        isStorageAvailable()
        {

            let storage;
            try
            {
                storage = window[ 'localStorage' ];
                storage.setItem( '__rbStorageSet', 'x' );
                storage.getItem( '__rbStorageSet' );
                storage.removeItem( '__rbStorageSet' );
                return true;
            } catch( e )
            {
                return false;
            }
        },
        setStorage( key, data )
        {
            this.yesStorage && localStorage.setItem( key, typeof data === 'string' ? data : JSON.stringify( data ) );

        },
        getStorage( key, defaultValue )
        {

            if( !this.yesStorage ) return null;

            const data = localStorage.getItem( key );
            if( data === null ) return defaultValue;

            try
            {
                return JSON.parse( data );
            } catch( e )
            {
                return data;
            }
        },
        deleteStorage( key )
        {
            this.yesStorage && localStorage.removeItem( key );
        },
        updatePostManagerSettings()
        {
            this.shouldDisplayPostEdit = rbsmUserPostsData?.postManagerSettings?.user_profile?.allow_edit_post ?? false;
            this.shouldDisplayPostDelete = rbsmUserPostsData?.postManagerSettings?.user_profile?.allow_delete_post ?? false;
            this.shouldDisplayPostAction = this.shouldDisplayPostEdit || this.shouldDisplayPostDelete;
            this.postEditingUrl = rbsmUserPostsData?.postManagerSettings?.edit_post_form?.edit_post_url ?? '';
        },
        getUserPostsStart()
        {
            const userPostsData = rbsmUserPostsData?.userPostsData ?? undefined;
            if( userPostsData === undefined )
            {
                console.log( 'Invalid user post data.' );
                return;
            }

            this.convertFormatUserPostsToDisplay( userPostsData.user_posts );
            this.visibleMorePostButton( !userPostsData.is_final_page );
            this.shouldDisplayPostView = userPostsData?.should_display_post_view ?? false;
        },
        getUserPosts()
        {
            return new Promise( ( resolve, reject ) =>
            {
                const formData = new FormData();
                formData.append( 'action', 'rbsm_get_user_posts' );
                formData.append( '_nonce', rbLocalizeData.nonce );
                formData.append( 'data', JSON.stringify( {
                    paged: this.paged
                } ) );

                fetch( rbLocalizeData.ajaxUrl, {
                    method: 'POST',
                    body: formData
                } )
                    .then( response => response.json() )
                    .then( data =>
                    {
                        if( data.success )
                        {
                            this.convertFormatUserPostsToDisplay( data.data.user_posts );
                            this.visibleMorePostButton( !data.data.is_final_page );
                            this.shouldDisplayPostView = data?.data?.should_display_post_view ?? false;
                            resolve();
                        } else
                        {
                            this.convertFormatUserPostsToDisplay( [] );
                            resolve();
                        }

                        this.isLoadingPosts = false;
                    } )
                    .catch( error =>
                    {
                        this.isLoadingPosts = false;
                        console.log( error );
                        reject();
                    } );
            } );
        },
        convertFormatUserPostsToDisplay( userPostsRaw )
        {
            if( userPostsRaw.length === 0 ) this.paged--;

            const newUserPost = userPostsRaw.map( post =>
            {
                let categories = '';
                let tags = '';

                post.categories.forEach( ( category, index ) =>
                {
                    if( index < post.categories.length - 1 )
                        categories += category + ", ";
                    else categories += category;
                } );

                post.tags.forEach( ( tag, index ) =>
                {
                    if( index < post.tags.length - 1 )
                        tags += tag + ", ";
                    else tags += tag;
                } );

                return {
                    title: post.title,
                    categories,
                    tags,
                    date: post.date,
                    postId: post.post_id,
                    view: post.post_view,
                    status: post.status,
                    link: post.link,
                    shortDesc: post.short_desc
                };
            } );

            this.userPosts = this.userPosts.concat( newUserPost );
        },
        visibleMorePostButton( isVisible )
        {
            this.isDisplayMorePostButton = isVisible;
        },
        async postEditClicked( postId )
        {
            if( this.postEditingUrl === '' )
            {
                console.log( 'The post editing page was not configurated.' )
            }
            else
            {
                this.setStorage( 'rbsm_is_from_post_manager', true );
                let param = `?rbsm-id=${postId}`;
                window.location.href = this.postEditingUrl + param;
            }

        },
        async getFormSubmissionId( postId )
        {
            return new Promise( ( resolved, reject ) =>
            {
                const formData = new FormData();
                formData.append( 'action', 'rb_get_form_submission_id' );
                formData.append( '_nonce', rbLocalizeData.nonce );
                formData.append( 'data', JSON.stringify( { postId } ) );

                fetch( rbLocalizeData.ajaxUrl, {
                    method: 'POST',
                    body: formData
                } )
                    .then( response => response.json() )
                    .then( data =>
                    {
                        if( data.success )
                        {
                            resolved( data.data );
                        } else
                        {
                            reject( -1 );
                        }
                    } )
                    .catch( error =>
                    {
                        console.log( error );
                    } );
            } );
        },
        showMorePosts()
        {
            if( this.isLoadingPosts ) return;

            this.isLoadingPosts = true;
            this.paged++;
            this.getUserPosts();
        },
        async postTrashClicked( postId )
        {
            this.isDisplayDeleteConfirmationDialog = true;
            const post = this.userPosts.find( post => post.postId === postId );

            if( !post ) return;

            this.deleteDialogMessage = this.translate.confirmDeleteMessage.replace( '%s', post.title );
            this.postIdShouldRemove = postId;
        },
        async trashPost( postId, title )
        {
            return new Promise( ( resolve, reject ) =>
            {
                const formData = new FormData();
                formData.append( 'action', 'rbsm_trash_post' );
                formData.append( '_nonce', rbLocalizeData.nonce );
                formData.append( 'data', JSON.stringify( { postId, title } ) );

                fetch( rbLocalizeData.ajaxUrl, {
                    method: 'POST',
                    body: formData
                } )
                    .then( response => response.json() )
                    .then( data =>
                    {
                        if( data.success )
                        {
                            resolve( data.data );
                            this.updateTrashPostSTatusOnUI( postId );
                            this.displayDeleteFormSnackbar( title );
                            this.overlayVisible = false;
                        } else
                        {
                            reject( '' );
                            this.overlayVisible = false;
                        }
                    } )
                    .catch( error =>
                    {
                        console.log( error );
                        this.overlayVisible = false;
                    } );
            } );
        },
        displayDeleteFormSnackbar( formDeletedTitle )
        {
            this.snackbar = true;
            this.snackbarMessage = this.translate.postDeleteSuccessfulMessage.replace( '%s', formDeletedTitle );
        },
        updateTrashPostSTatusOnUI( postId )
        {
            const condition = ( post ) => post.postId === postId;
            this.userPosts = this.userPosts.filter( post => !condition( post ) );
        },
        confirmDeletePost()
        {
            this.isDisplayDeleteConfirmationDialog = false;
            if( this.postIdShouldRemove === -1 ) return;

            this.overlayVisible = true;
            const post = this.userPosts.find( post => post.postId === this.postIdShouldRemove );
            const title = post ? post.title : '';

            this.trashPost( this.postIdShouldRemove, title );
        },
        cancelDeletePost()
        {
            this.isDisplayDeleteConfirmationDialog = false;
            this.postIdShouldRemove = -1;
        }
    },
    template: `
        <div>
            <v-dialog v-model="overlayVisible" persistent >
            </v-dialog>
            <v-snackbar v-model="snackbar" class="rbsm-snackbar" :timeout="3000">
                <v-icon class="pr-2">mdi-delete-empty-outline</v-icon>{{ snackbarMessage }}
            </v-snackbar>
            <v-dialog class="rbsm-popup-box" v-model="isDisplayDeleteConfirmationDialog" persistent>
            <v-card>
                <v-card-title class="headline"><v-icon>mdi-information-outline</v-icon>{{ translate.confirmDelete }}</v-card-title>
                <v-card-text>{{deleteDialogMessage}}</v-card-text>
                    <template v-slot:actions>
                        <v-btn @click="confirmDeletePost"><v-icon>mdi-delete</v-icon>{{ translate.delete }}</v-btn>
                        <v-btn @click="cancelDeletePost"><v-icon>mdi-cancel</v-icon>{{ translate.cancel }} </v-btn>
                    </template>
                </v-card>
            </v-dialog>
              <div v-if="userPosts.length === 0" class="rbsm-table-empty">
                <v-icon rbsm-table-empy-icon>mdi-file-document-alert-outline</v-icon>
                <h3 class="rbsm-table-empty-title">{{ translate.noPostShowTitle }}</h3>
                <p class="rbsm-table-empty-desc">{{ translate.noPostShowDesc }}</p>
             </div>
              <div class="rbsm-table-wrap" v-if="userPosts.length !== 0">
                  <div class="rbsm-table" :class="{ 'yes-view': shouldDisplayPostView, 'yes-actions': shouldDisplayPostAction }">
                    <div class="rbsm-table-row rbsm-table-row-header h5">
                            <div class="rbsm-table-col is-grow"><div class="rbsm-table-title"><v-icon>mdi-file-certificate-outline</v-icon>{{ translate.post }}</div></div>
                            <div class="rbsm-table-col">{{ translate.categories }}</div>
                            <div class="rbsm-table-col">{{ translate.createdDate }}</div>
                            <div v-if="shouldDisplayPostView" class="rbsm-table-col rbsm-table-centered">{{ translate.views }}</div>
                            <div class="rbsm-table-col rbsm-table-centered">{{ translate.status }}</div>
                            <div v-if="shouldDisplayPostAction" class="rbsm-table-col rbsm-table-centered">{{ translate.actions }}</div>
                    </div>
                    <div class="rbsm-table-row" v-for="(post, index) in userPosts">
                        <div class="rbsm-table-col is-grow rbsm-post-title-col">
                            <h6><a v-if="post.status === 'publish'"
                            target="_blank"
                            :href="post.link" :class="['rbsm-post-title', 'h-link', 'rbsm-title-is-' + post.status]">
                            {{ post.title }}<v-icon>mdi-open-in-new</v-icon>
                            </a>
                            <span v-else :class="['rbsm-post-title', 'rbsm-title-is-' + post.status]">
                            {{ post.title }}
                            </span>
                            </h6>
                            <p class="rbsm-table-excerpt">{{ post.shortDesc }}</p>
                        </div>
                        <div class="rbsm-table-col"><span class="rbsm-table-label rbsm-category-label">{{ post.categories }}</span></div>
                        <div class="rbsm-table-col"><span class="rbsm-table-label rbsm-date-label">{{ post.date }}</span></div>
                        <div class="rbsm-table-col rbsm-table-centered" v-if="shouldDisplayPostView"><span class="rbsm-table-label rbsm-view-label">{{ post.view }}</span></div>
                        <div class="rbsm-table-col rbsm-table-centered"><span  :class="'rbsm-status rbsm-status-' + post.status">{{ post.status }}</span></div>
                        <div v-if="shouldDisplayPostAction" class="rbsm-table-col rbsm-post-actions-col">
                            <v-btn v-if="shouldDisplayPostEdit" @click="postEditClicked(post.postId)"><v-icon>mdi-file-edit-outline</v-icon></v-btn>
                            <v-btn v-if="shouldDisplayPostDelete" :disabled="post.status === 'trash'" class="rbsm-btn-red" @click="postTrashClicked(post.postId)"><v-icon>mdi-file-document-remove-outline</v-icon></v-btn>
                        </div>
                    </div>
                </div>
                <div class="rbsm-post-pagination" v-if="isDisplayMorePostButton">
                    <a class="rbsm-pagination-link is-btn" @click="showMorePosts">
                        {{ translate.morePosts }}
                        <v-icon v-show="!isLoadingPosts">mdi-arrow-right-thin</v-icon>
                        <v-icon v-show="isLoadingPosts" class="rbsm-loading-icon">mdi-loading</v-icon>
                    </a>
                </div>
            </div>
        </div>
        `
} );