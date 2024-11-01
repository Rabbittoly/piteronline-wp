<?php

/** Don't load directly */
defined('ABSPATH') || exit;

if (! class_exists('Ruby_Submission_Client_Translate', false)) {
    class Ruby_Submission_Client_Translate
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
                'ok'                                   => esc_html__('OK', 'ruby-submission'),
                'delete'                               => esc_html__('Delete', 'ruby-submission'),
                'confirmDelete'                        => esc_html__('Confirm Delete', 'ruby-submission'),
                'submit'                               => esc_html__('Submit', 'ruby-submission'),
                'cancel'                               => esc_html__('Cancel', 'ruby-submission'),
                'post'                                 => esc_html__('Post Title', 'ruby-submission'),
                'categories'                           => esc_html__('Categories', 'ruby-submission'),
                'createdDate'                          => esc_html__('Created', 'ruby-submission'),
                'status'                               => esc_html__('Status', 'ruby-submission'),
                'views'                                => esc_html__('Views', 'ruby-submission'),
                'actions'                              => esc_html__('Actions', 'ruby-submission'),
                'noPostShowTitle'                      => esc_html__('It looks like there are no posts to display yet!', 'ruby-submission'),
                'noPostShowDesc'                       => esc_html__('We would love to see your contributions! Please submit your posts to showcase them here.', 'ruby-submission'),
                'needLogin'                            => esc_html__('Please Log In to Continue', 'ruby-submission'),
                'morePosts'                            => esc_html__('Load More Posts', 'ruby-submission'),
                'postListLabel'                        => esc_html__('Your Posts', 'ruby-submission'),
                'editPost'                             => esc_html__('Edit Post', 'ruby-submission'),
                'submitPost'                           => esc_html__('Submit a Post', 'ruby-submission'),
                'postDeleteSuccessfulMessage'          => esc_html__('%s has been deleted successfully', 'ruby-submission'),
                'confirmDeleteMessage'                 => esc_html__('Are you sure you want to delete the post: %s ?', 'ruby-submission'),
                'yourName'                             => esc_html__('Your Name', 'ruby-submission'),
                'hindYourName'                         => esc_html__('Enter your full name as you would like it to appear.', 'ruby-submission'),
                'yourEmail'                            => esc_html__('Your Email', 'ruby-submission'),
                'hindYourEmail'                        => esc_html__('Your email address will not be publicly displayed. It is used solely for administrative purposes and to contact you about your post.', 'ruby-submission'),
                'challengeTitle'                       => esc_html__('Competition Challenge', 'ruby-submission'),
                'challengeLabel'                       => esc_html__('Please answer the question:', 'ruby-submission'),
                'invalidAnswer'                        => esc_html__('The answer provided is invalid. Please enter a valid response.', 'ruby-submission'),
                'addPostTitle'                         => esc_html__('Add Title', 'ruby-submission'),
                'hindPostTitle'                        => esc_html__('Short and sweet is the way to go! Try to keep your post title between 50 and 70 characters to make it more engaging.', 'ruby-submission'),
                'addPostExcerpt'                       => esc_html__('Add an Excerpt', 'ruby-submission'),
                'hindPostExcerpt'                      => esc_html__('Aim for a length of about 20 to 55 words. A concise excerpt captures the essence of your post without giving everything away.', 'ruby-submission'),
                'addPostContent'                       => esc_html__('Add Post Content', 'ruby-submission'),
                'textAreaFormPlaceholder'              => esc_html__('Start writing your content here...', 'ruby-submission'),
                'hindPostContent'                      => esc_html__('Begin with a captivating introduction that grabs your readers attention and gives them a reason to keep reading.', 'ruby-submission'),
                'titlePostCategories'                  => esc_html__(' Categories', 'ruby-submission'),
                'choosePostCategories'                 => esc_html__('Choose Categories', 'ruby-submission'),
                'hindPostCategories'                   => esc_html__('Choose categories that accurately reflect your post content.', 'ruby-submission'),
                'titlePostTags'                        => esc_html__('Post Tags', 'ruby-submission'),
                'addPostTags'                          => esc_html__('Add Tags', 'ruby-submission'),
                'hindPostTags'                         => esc_html__('Choose tags that are most relevant to your post contents.', 'ruby-submission'),
                'titlePostFeatured'                    => esc_html__('Featured Image', 'ruby-submission'),
                'hindPostFeatured'                     => esc_html__('A relevant, high-quality image effectively conveys the essence of your content and captures the attention of your audience.', 'ruby-submission'),
                'chooseFeaturedImage'                  => esc_html__('Choose a Featured Image', 'ruby-submission'),
                'editFeaturedImage'                    => esc_html__('Edit Featured Image', 'ruby-submission'),
                'submitPostLabel'                      => esc_html__('Submit Your Post', 'ruby-submission'),
                'updatePostLabel'                      => esc_html__('Update This Post', 'ruby-submission'),
                'featuredButtonLabel'                  => esc_html__('Upload Image', 'ruby-submission'),
                'challengeRequiredInput'               => esc_html__('An answer is required!', 'ruby-submission'),
                'verifyFailedRecaptchaMessage'         => esc_html__('reCAPTCHA was not verified', 'ruby-submission'),
                'maxAmountImages'                      => esc_html__('Maximum Images Upload', 'ruby-submission'),
                'maxAmountImagesMessage'               => esc_html__('You have reached the maximum limit of %s images.', 'ruby-submission'),
                'updating'                             => esc_html__('Updating', 'ruby-submission'),
                'submitting'                           => esc_html__('Submitting', 'ruby-submission'),
                'updatePostSuccessMessage'             => esc_html__('%s was updated successfully!', 'ruby-submission'),
                'submitPostSuccessMessage'             => esc_html__('%s was added successfully!', 'ruby-submission'),
                'updatePostSuccessTitle'               => esc_html__('Successfully Update', 'ruby-submission'),
                'submitPostSuccessTitle'               => esc_html__('Successfully Submit', 'ruby-submission'),
                'updatePostFailedTitle'                => esc_html__('Update Failed', 'ruby-submission'),
                'submitPostFailedTitle'                => esc_html__('Submission Failed.', 'ruby-submission'),
                'recaptchaLoadFailed'                  => esc_html__('reCAPTCHA not available', 'ruby-submission'),
                'register'                             => esc_html__('Register', 'ruby-submission'),
                'login'                                => esc_html__('Login', 'ruby-submission'),
                'requiredLoginTitlePattern'            => esc_html__('Login Required to Submit', 'ruby-submission'),
                'requiredLoginDescPattern'             => esc_html__('To continue editing your post, please log in to your account.', 'ruby-submission'),
                'userPostsRequiredLoginTitlePattern'   => esc_html__('Login Required to Access Posts', 'ruby-submission'),
                'userPostsRequiredLoginMessagePattern' => esc_html__('You need to be logged in to view all posts. Please log in to continue.', 'ruby-submission'),
                'editPostRequiredLoginTitlePattern'    => esc_html__('Login Required to Edit Post', 'ruby-submission'),
                'editPostRequiredLoginMessagePattern'  => esc_html__('You must be logged in to edit this post. Please log in to proceed.', 'ruby-submission'),
                'titleRequiredRule' => esc_html__('Title is required!', 'ruby-submission'),
                'excerptRequiredRule' => esc_html__('Excerpt is required!', 'ruby-submission'),
                'userNameRequiredRule' => esc_html__('User name is required!', 'ruby-submission'),
                'userEmailRequiredRule' => esc_html__('User email is required!', 'ruby-submission'),
                'title' => esc_html__('title', 'ruby-submission'),
                'excerpt' => esc_html__('excerpt', 'ruby-submission'),
                'userName' => esc_html__('user name', 'ruby-submission'),
                'userEmail' => esc_html__('user email', 'ruby-submission'),
                'isMissing' => esc_html__(' is missing', 'ruby-submission')
            ];
        }

        public function get_translate_array()
        {

            return $this->translate_array;
        }
    }
}
