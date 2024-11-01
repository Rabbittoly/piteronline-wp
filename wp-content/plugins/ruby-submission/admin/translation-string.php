<?php

/** Don't load directly */
defined('ABSPATH') || exit;

if (! class_exists('Ruby_Submission_Translate', false)) {
    class Ruby_Submission_Translate
    {
        private static $instance;
        private $translate_array;

        public static function get_instance()
        {

            if (self::$instance === null) {
                return new self();
            }

            return self::$instance;
        }

        public function __construct()
        {

            $this->translate_array = $this->initTranslateArray();
        }

        private function initTranslateArray()
        {

            return [
                'title'                               => esc_html__('Ruby Submission', 'ruby-submission'),
                'description'                         => esc_html__('A lightweight and user-friendly WordPress plugin designed to let users submit content from the frontend with ease. Customizable forms, file uploads, and user role restrictions. Ideal for guest posts, user-generated content, and community contributions.', 'ruby-submission'),
                'formTab'                             => esc_html__('Forms Overview', 'ruby-submission'),
                'formSettingTab'                      => esc_html__('Form Settings', 'ruby-submission'),
                'syncDataTab'                         => esc_html__('Backup and Restore', 'ruby-submission'),
                'createFormTitle'                     => esc_html__('Start Creating Your Form', 'ruby-submission'),
                'createFormDesc'                      => esc_html__('To get started, click the button and enter your desired form name.', 'ruby-submission'),
                'createFormInputPlaceholder'          => esc_html__('Enter the form ID without special characters...', 'ruby-submission'),
                'createFormInputRequired'             => esc_html__('Form title is required!', 'ruby-submission'),
                'submitForm'                          => esc_html__('Save New Form', 'ruby-submission'),
                'cancel'                              => esc_html__('Cancel', 'ruby-submission'),
                'addNewForm'                          => esc_html__('Add New Form', 'ruby-submission'),
                'formNotFoundTitle'                   => esc_html__('No Forms Found!', 'ruby-submission'),
                'formNotFoundDescription'             => esc_html__('Click here to add a new form and begin your journey.', 'ruby-submission'),
                'goToAddNew'                          => esc_html__('Go to Add New Form', 'ruby-submission'),
                'formListTitle'                       => esc_html__('Your Forms', 'ruby-submission'),
                'formSettings'                        => esc_html__('Form Settings', 'ruby-submission'),
                'delete'                              => esc_html__('Delete', 'ruby-submission'),
                'deleteFormText'                      => esc_html__('Are you sure you want to delete this form?', 'ruby-submission'),
                'deleteFormTitle'                     => esc_html__('Confirm Deletion', 'ruby-submission'),
                'currentSettingsFor'                  => esc_html__('Form Settings for:', 'ruby-submission'),
                'changeFormLabel'                     => esc_html__('Select a form:', 'ruby-submission'),
                'generalSettings'                     => esc_html__('General', 'ruby-submission'),
                'userLogin'                           => esc_html__('User Login', 'ruby-submission'),
                'formFields'                          => esc_html__('Form Fields', 'ruby-submission'),
                'userProfile'                         => esc_html__('User Posts Management', 'ruby-submission'),
                'rubySubmissionManager'               => esc_html__('To display the post listings to users, create a page and insert the shortcode below. Adjust the settings below to your preference.', 'ruby-submission'),
                'securityFields'                      => esc_html__('Security Fields', 'ruby-submission'),
                'emails'                              => esc_html__('Emails', 'ruby-submission'),
                'preview'                             => esc_html__('Preview', 'ruby-submission'),
                'saveSettings'                        => esc_html__('Save Settings', 'ruby-submission'),
                'postStatus'                          => esc_html__('Post Status', 'ruby-submission'),
                'postStatusDesc'                      => esc_html__('The status of the post after it is submitted.', 'ruby-submission'),
                'urlDirection'                        => esc_html__('Redirect URL After Successful Submission', 'ruby-submission'),
                'urlDirectionDesc'                    => esc_html__('Redirect users to this URL after their post is successfully submitted.', 'ruby-submission'),
                'successMessage'                      => esc_html__('Success Message', 'ruby-submission'),
                'successMessageDesc'                  => esc_html__('The message displayed after a post is successfully submitted.', 'ruby-submission'),
                'errorMessage'                        => esc_html__('Error Message', 'ruby-submission'),
                'errorMessageDesc'                    => esc_html__('The message displayed when a post submission fails.', 'ruby-submission'),
                'uniqueTitle'                         => esc_html__('Require Unique Post Title', 'ruby-submission'),
                'uniqueTitleDesc'                     => esc_html__('Ensure all submitted post titles are unique to avoid duplication.', 'ruby-submission'),
                'authorAccess'                        => esc_html__('Author Access', 'ruby-submission'),
                'authorAccessDesc'                    => esc_html__('Require users to log in to WordPress to view or submit forms.', 'ruby-submission'),
                'loginType'                           => esc_html__('Login Action Choice', 'ruby-submission'),
                'loginTypeDesc'                       => esc_html__('Choose whether to show a notification or redirect to the login page when a visitor accesses this page without being logged in.', 'ruby-submission'),
                'assignAuthor'                        => esc_html__('Assign Author', 'ruby-submission'),
                'assignAuthorDesc'                    => esc_html__('The author named for the post.', 'ruby-submission'),
                'userName'                            => esc_html__('User Name', 'ruby-submission'),
                'typeUserName'                        => esc_html__('Type User Name', 'ruby-submission'),
                'typeAnythings'                       => esc_html__('type anythings', 'ruby-submission'),
                'typeUserEmail'                       => esc_html__('Type User Email', 'ruby-submission'),
                'userNameDesc'                        => esc_html__('Requirement for the user name.', 'ruby-submission'),
                'userEmail'                           => esc_html__('User Email', 'ruby-submission'),
                'userEmailDesc'                       => esc_html__('Requirement for the user email.', 'ruby-submission'),
                'postTitle'                           => esc_html__('Post Title', 'ruby-submission'),
                'postTitleDesc'                       => esc_html__('Requirement for the post title.', 'ruby-submission'),
                'tagline'                             => esc_html__('Tagline', 'ruby-submission'),
                'taglineDesc'                         => esc_html__('Requirement for the tagline.', 'ruby-submission'),
                'editorType'                          => esc_html__('Post Content Editor', 'ruby-submission'),
                'editorTypeDesc'                      => esc_html__('Select the layout for the content editor: use a WYSIWYG editor for visual editing or choose raw HTML for manual input.', 'ruby-submission'),
                'featuredImage'                       => esc_html__('Featured Image', 'ruby-submission'),
                'featuredImageDesc'                   => esc_html__('Requirement for the featured image.', 'ruby-submission'),
                'uploadFileSizeLimit'                 => esc_html__('Upload File Size Limit', 'ruby-submission'),
                'uploadFile'                          => esc_html__('Upload file', 'ruby-submission'),
                'grecapchaLoadError'                  => esc_html__('Recaptcha not available', 'ruby-submission'),
                'validId'                             => esc_html__('Valid id', 'ruby-submission'),
                'validTitle'                          => esc_html__('Valid title', 'ruby-submission'),
                'invalidTitle'                        => esc_html__('Invalid title', 'ruby-submission'),
                'validData'                           => esc_html__('Valid data', 'ruby-submission'),
                'invalidData'                         => esc_html__('Invalid_data', 'ruby-submission'),
                'uploadFileSizeLimitDesc'             => esc_html__('The maximum file upload size in MB.', 'ruby-submission'),
                'defaultFeaturedImage'                => esc_html__('Default Featured Image', 'ruby-submission'),
                'defaultFeaturedImageDesc'            => esc_html__('The default image to use if no featured image is provided.', 'ruby-submission'),
                'categories'                          => esc_html__('Categories', 'ruby-submission'),
                'multipleCategories'                  => esc_html__('Multiple Categories', 'ruby-submission'),
                'multipleCategoriesDesc'              => esc_html__('Allow the selection of multiple categories.', 'ruby-submission'),
                'excludeCategories'                   => esc_html__('Exclude Categories', 'ruby-submission'),
                'excludeCategoriesDesc'               => esc_html__('Categories that cannot be selected in this form.', 'ruby-submission'),
                'autoAssignCategories'                => esc_html__('Auto Assign Categories', 'ruby-submission'),
                'autoAssignCategoriesDesc'            => esc_html__('Automatically assign default categories if no categories are selected during the post submission.', 'ruby-submission'),
                'tags'                                => esc_html__('Tags', 'ruby-submission'),
                'multipleTags'                        => esc_html__('Multiple Tags', 'ruby-submission'),
                'multipleTagsDesc'                    => esc_html__('Allow the selection of multiple tags.', 'ruby-submission'),
                'allowAddNewTags'                     => esc_html__('Allow Add New Tags', 'ruby-submission'),
                'allowAddNewTagsDesc'                 => esc_html__('Allow users to add new tags.', 'ruby-submission'),
                'excludeTags'                         => esc_html__('Exclude Tags', 'ruby-submission'),
                'excludeTagsDesc'                     => esc_html__('Tags that will be excluded from the post.', 'ruby-submission'),
                'autoAssignTags'                      => esc_html__('Auto Assign Tags', 'ruby-submission'),
                'autoAssignTagsDesc'                  => esc_html__('Automatically assign default tags if no tags are selected during the post submission.', 'ruby-submission'),
                'customFields'                        => esc_html__('Custom Fields', 'ruby-submission'),
                'customFieldsDesc'                    => esc_html__('Allow users to add new custom fields.', 'ruby-submission'),
                'addNewField'                         => esc_html__('Add New Field', 'ruby-submission'),
                'customFieldName'                     => esc_html__('Custom Field Name', 'ruby-submission'),
                'customFieldNameDesc'                 => esc_html__('The name of the custom field. Please use plain text without special characters, as it will be used to set the meta key. Use underscores (_) instead of spaces.', 'ruby-submission'),
                'customFieldLabel'                    => esc_html__('Custom Field Label', 'ruby-submission'),
                'customFieldLabelDesc'                => esc_html__('The label of the custom field.', 'ruby-submission'),
                'fieldType'                           => esc_html__('Field Type', 'ruby-submission'),
                'fieldTypeDesc'                       => esc_html__('The type of the custom field.', 'ruby-submission'),
                'removeField'                         => esc_html__('Remove Field', 'ruby-submission'),
                'challenge'                           => esc_html__('Challenge', 'ruby-submission'),
                'challengeDesc'                       => esc_html__('The user must complete the challenge before submitting the post.', 'ruby-submission'),
                'challengeQuestion'                   => esc_html__('Challenge Question', 'ruby-submission'),
                'challengeQuestionDesc'               => esc_html__('This question requires a response from the user', 'ruby-submission'),
                'challengeResponse'                   => esc_html__('Challenge Response', 'ruby-submission'),
                'challengeResponseDesc'               => esc_html__('The correct answer.', 'ruby-submission'),
                'recaptcha'                           => esc_html__('ReCaptcha', 'ruby-submission'),
                'recaptchaDesc'                       => esc_html__('Require users to complete ReCaptcha verification before submitting the post.', 'ruby-submission'),
                'recaptchaSiteKey'                    => esc_html__('ReCaptcha Site Key', 'ruby-submission'),
                'recaptchaSiteKeyDesc'                => esc_html__('Input the ReCaptcha Site Key.', 'ruby-submission'),
                'recaptchaSecretKey'                  => esc_html__('ReCaptcha Secret Key', 'ruby-submission'),
                'recaptchaSecretKeyDesc'              => esc_html__('Input the ReCaptcha Secret Key', 'ruby-submission'),
                'adminEmail'                          => esc_html__('Admin Email', 'ruby-submission'),
                'adminEmailDesc'                      => esc_html__('Notify the admin via email when a post is submitted.', 'ruby-submission'),
                'adminEmailAddress'                   => esc_html__('Admin Email Address', 'ruby-submission'),
                'subject'                             => esc_html__('Subject', 'ruby-submission'),
                'emailSubjectDesc'                    => esc_html__('The subject of the email. You can use custom tags such as {{post_title}} and {{post_link}} to provide dynamic information.', 'ruby-submission'),
                'emailTitle'                          => esc_html__('Email Title', 'ruby-submission'),
                'emailTitleDesc'                 	  => esc_html__('The title of the email. Allow custom tags such as {{post_title}}, {{post_link}} to display support information.', 'ruby-submission'),
                'message'                             => esc_html__('Message', 'ruby-submission'),
                'emailMessageDesc'               	  => esc_html__('The email content. You can use custom tags such as {{post_title}} and {{post_link}} for dynamic information.', 'ruby-submission'),
                'postSubmitNotification'              => esc_html__('Post Submit Notification', 'ruby-submission'),
                'postSubmitNotificationDesc'          => esc_html__('Allow notification to the user by email when a post is submitted.', 'ruby-submission'),
                'postPublishNotification'             => esc_html__('Post Publish Notification', 'ruby-submission'),
                'postPublishNotificationDesc'         => esc_html__('Allow notification to the user by email when a post is published.', 'ruby-submission'),
                'postTrashNotification'               => esc_html__('Post Trash Notification', 'ruby-submission'),
                'postTrashNotificationDesc'           => esc_html__('Allow notification to the user by email when a post is trashed.', 'ruby-submission'),
                'importSestoreData'                   => esc_html__('Import Settings', 'ruby-submission'),
                'import'                              => esc_html__('Import', 'ruby-submission'),
                'backupData'                          => esc_html__('Export Settings', 'ruby-submission'),
                'copy'                                => esc_html__('Copy', 'ruby-submission'),
                'copied'                              => esc_html__('Copied!', 'ruby-submission'),
                'paste'                               => esc_html__('Paste From Clipboard', 'ruby-submission'),
                'updateSuccessful'                    => esc_html__('Saved Changes', 'ruby-submission'),
                'updateSuccessfulMessage'             => esc_html__('Settings have been saved successfully!', 'ruby-submission'),
                'ok'                                  => esc_html__('OK', 'ruby-submission'),
                'wasUpdatedSuccessfully'              => esc_html__('Updated successfully!', 'ruby-submission'),
                'restoreDataSuccessMessage'           => esc_html__('Data has been restored successfully!', 'ruby-submission'),
                'restoreDataSuccessTitle'             => esc_html__('Data Restoration Successful', 'ruby-submission'),
                'restoreDataFailedTitle'              => esc_html__('Data Restoration Failed!', 'ruby-submission'),
                'restoreDataDuplicateKeyErrorMessage' => esc_html__('There are two duplicate values. That value is:', 'ruby-submission'),
                'formCreateSuccess'                   => esc_html__('Form Created Successfully', 'ruby-submission'),
                'formCreateSuccessMessage'            => esc_html__('%s  has been added successfully!', 'ruby-submission'),
                'formRemovedMessage'                  => esc_html__('Form %s was removed!', 'ruby-submission'),
                'warningSameCustomFieldName'          => esc_html__('Custom field names must be unique!', 'ruby-submission'),
                'maxCustomField'                      => esc_html__('Max custom field', 'ruby-submission'),
                'useThisMedia'                        => esc_html__('Use This Media', 'ruby-submission'),
                'selectMedia'                         => esc_html__('Select Media', 'ruby-submission'),
                'urlDirectionError'                   => esc_html__('The URL direction is invalid!', 'ruby-submission'),
                'chooseCategories'                    => esc_html__('Choose categories', 'ruby-submission'),
                'chooseTags'                          => esc_html__('Choose tags', 'ruby-submission'),
                'wordpressMediaError'                 => esc_html__('WordPress media scripts are unavailable.', 'ruby-submission'),
                'successMessagePattern'               => esc_html__('ost Submission Successful!', 'ruby-submission'),
                'errorMessagePattern'                 => esc_html__('Post Submission Failed!', 'ruby-submission'),
                'loginMessagePattern'                 => esc_html__('Please log in to securely submit your content. If you do not have an account, sign up quickly to get started!', 'ruby-submission'),
                'emailAdminSubjectPattern'            => esc_html__('New Post Submitted', 'ruby-submission'),
                'emailAdminTitlePattern'              => esc_html__('Notification: A New Post Has Been Submitted', 'ruby-submission'),
                'emailAdminMessagePattern'            => html_entity_decode(esc_html__('Dear Admin, <br>We would like to inform you that a new post titled "{{post_title}}" has been successfully submitted. Please check and review the post in the system. <br>Best regards, The Support Team', 'ruby-submission')),
                'emailPostSubmitSubjectPattern'       => esc_html__('Your Post Has Been Successfully Submitted', 'ruby-submission'),
                'emailPostSubmitTitlePattern'         => esc_html__('Confirmation: Your Post Submission', 'ruby-submission'),
                'emailPostSubmitMessagePattern'       => html_entity_decode(esc_html__('Dear Author, <br>We would like to inform you that your post titled "{{post_title}}" has been successfully submitted. Our team will review your post and notify you once its published. Thank you for your contribution! <br>Best regards, The Support Team', 'ruby-submission')),
                'emailPostPublishSubjectPattern'      => esc_html__('Your Post Has Been Published', 'ruby-submission'),
                'emailPostPublishTitlePattern'        => esc_html__('Congratulations: Your Post Is Now Live', 'ruby-submission'),
                'emailPostPublishMessagePattern'      => html_entity_decode(esc_html__('Dear author, <br>We are excited to inform you that your post titled "{{post_title}}" has been successfully published on our platform. You can now view your post live here: {{post_link}} Thank you for your contribution, and we look forward to more great content from you! <br>Best regards, The Support Team', 'ruby-submission')),
                'emailPostTrashSubjectPattern'        => esc_html__('Your Post Has Been Deleted', 'ruby-submission'),
                'emailPostTrashTitlePattern'          => esc_html__('Notice: Your Post Has Been Removed', 'ruby-submission'),
                'emailPostTrashMessagepattern'        => html_entity_decode(esc_html__('Dear Author, <br>We regret to inform you that your post titled "{{post_title}}" has been removed from our platform. If you have any questions or concerns about this, please feel free to contact us. Thank you for your understanding. <br>Best regards, The Support Team', 'ruby-submission')),
                'postManager'                         => esc_html__('Post Manager', 'ruby-submission'),
                'editPostForm'                        => esc_html__('Submission Edit Post Form', 'ruby-submission'),
                'editPostUrl'                         => esc_html__('Post Submission Edit Page URL', 'ruby-submission'),
                'editPostUrlDesc'                     => esc_html__('Enter the URL of the page that displays the submission edit form when users click the Edit Post button in the post manager.', 'ruby-submission'),
                'allowEditPost'                       => esc_html__('Allow Edit Post', 'ruby-submission'),
                'allowEditPostDesc'                   => esc_html__('Allows displaying the edit post button on the post manager page.', 'ruby-submission'),
                'allowDeletePost'                     => esc_html__('Allow Delete Post', 'ruby-submission'),
                'allowDeletePostDesc'                 => esc_html__('Allows displaying the remove post button on the post manager page.', 'ruby-submission'),
                'formSubmissionDefault'               => esc_html__('Form Submission Default ID', 'ruby-submission'),
                'formSubmissionDefaultDesc'           => esc_html__('This is the ID of the default form submission shortcode (e.g., [ruby_submission_form id=1]). It will be used when displaying the Edit Post form if the form submission that belonged to that post was deleted.', 'ruby-submission'),
                'updatePostManagerSuccessfulMessage'  => esc_html__('Post manager settings were saved successful!', 'ruby-submission'),
                'customRequiredLoginTitle'            => esc_html__('Notification Title', 'ruby-submission'),
                'customRequiredLoginTitleDesc'        => esc_html__('Enter your notification title for users who are not logged in.', 'ruby-submission'),
                'customRequiredLoginDescLabel'        => esc_html__('Notification Message', 'ruby-submission'),
                'customRequiredLoginDescLabelDesc'    => esc_html__('Enter your notification description for users who are not logged in.', 'ruby-submission'),
                'register'                            => esc_html__('Register', 'ruby-submission'),
                'requiredLoginTitlePattern'           => esc_html__('Login Required to Submit', 'ruby-submission'),
                'requiredLoginTitleDescPattern'       => esc_html__('You must be logged in to submit a new post. Please log in to continue.', 'ruby-submission'),
                'submissionFormLayoutType'            => esc_html__('Form Layout', 'ruby-submission'),
                'submissionFormLayoutTypeDesc'        => esc_html__('This option is used to display the submission form by the number of columns.', 'ruby-submission'),
                'parseRestoreDataFailed'              => esc_html__('Invalid import data', 'ruby-submission'),
                'invalidId'                           => esc_html__('Invalid Id', 'ruby-submission'),
                'validateFailedPostManager'           => esc_html__('Post manager data import is invalid', 'ruby-submission'),
                'validateSuccessPostManager'          => esc_html__('Valid post manager data has been imported.', 'ruby-submission'),
                'rubySubmissionEdit'                  => esc_html__('To display the editing post to users, create a page and insert the shortcode below. Adjust the settings to your preference.', 'ruby-submission'),
                'customLoginAndRegister'              => esc_html__('Login and Registration Buttons', 'ruby-submission'),
                'loginActionChoice'                   => esc_html__('Login Action Choice', 'ruby-submission'),
                'loginActionChoiceDesc'               => esc_html__('Choose whether to show a notification or redirect to the login page when a visitor accesses this page without being logged in.', 'ruby-submission'),
                'userPostsRequiredLoginTitle'         => esc_html__('Notification Title', 'ruby-submission'),
                'userPostsRequiredLoginTitleDesc'     => esc_html__('Enter your notification title for users who are not logged in.', 'ruby-submission'),
                'userPostsRequiredLoginMessage'       => esc_html__('Notification Message', 'ruby-submission'),
                'userPostsRequiredLoginMessageDesc'   => esc_html__('Enter your notification description for users who are not logged in.', 'ruby-submission'),
                'editPostRequiredLoginTitle'          => esc_html__('Notification Title', 'ruby-submission'),
                'editPostRequiredLoginTitleDesc'      => esc_html__('Enter your notification title for users who are not logged in.', 'ruby-submission'),
                'editPostRequiredLoginMessage'        => esc_html__('Notification Message', 'ruby-submission'),
                'editPostRequiredLoginMessageDesc'    => esc_html__('Enter your notification description for users who are not logged in.', 'ruby-submission'),
                'customLoginButtonLabel'               => esc_html__('Login Button Label', 'ruby-submission'),
                'customLoginButtonLabelDesc'           => esc_html__('This label is used for the button that redirects users to the login page.', 'ruby-submission'),
                'loginLinkLabelPattern'                => esc_html__('Continue Login', 'ruby-submission'),
                'customLoginLink'                      => esc_html__('Custom Login Page URL', 'ruby-submission'),
                'customLoginLinkDesc'                  => esc_html__('Enter your custom login URL here. This link will redirect users to your specified login page.', 'ruby-submission'),
                'customRegisterButtonLabel'            => esc_html__('Registration Button Label', 'ruby-submission'),
                'customRegisterButtonLabelDesc'        => esc_html__('This label is used for the button that redirects users to the register page.', 'ruby-submission'),
                'customRegisterLink'                   => esc_html__('Custom Registration Page URL', 'ruby-submission'),
                'customRegisterLinkDesc'               => esc_html__('Enter your custom registration URL here. This link will redirect users to your specified registration page.', 'ruby-submission'),
                'userPostsRequiredLoginTitlePattern'   => esc_html__('Login Required to Access Posts', 'ruby-submission'),
                'userPostsRequiredLoginMessagePattern' => esc_html__('You need to be logged in to view all posts. Please log in to continue.', 'ruby-submission'),
                'editPostRequiredLoginTitlePattern'    => esc_html__('Login Required to Edit Post', 'ruby-submission'),
                'editPostRequiredLoginMessagePattern'  => esc_html__('You must be logged in to edit this post. Please log in to proceed.', 'ruby-submission'),
                'introduceImage'                       => '//api.themeruby.com/images/ruby-submission.gif?v=1.0',
            ];
        }

        public function get_translate_array()
        {

            return $this->translate_array;
        }
    }
}
