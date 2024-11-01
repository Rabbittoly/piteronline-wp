<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'rb_single_openai_template' ) ) :
	function rb_single_openai_template() {
	
		add_action( 'admin_footer', 'rb_openai_footer_templates', 900 );
		$rb_openai_writing_style = get_option( 'rb_openai_writing_style', 'creative' );
		$rb_openai_language      = get_option( 'rb_openai_language', 'english' );
		?>
		<div class="rb-openai-wrap">
			<div class="rb-openai-header">
				<h3><?php esc_html_e( 'Ruby OpenAI Assistant', 'rb-openai' ); ?></h3>
				<a class="rb-openai-setting-link" href="<?php echo esc_url( admin_url( 'admin.php?page=ruby-openai' ) ); ?>" target="_blank" title="<?php esc_attr_e( 'More Settings', 'rb-openai' ); ?>"><i class="dashicons dashicons-ellipsis"></i></a>
			</div>
			<div class="rb-openai-inner">
				<div class="rb-meta rb-select">
					<div class="rb-meta-title">
						<label for="content-type" class="rb-meta-label"><?php esc_html_e( 'Content Type', 'rb-openai' ); ?></label>
					</div>
					<div class="rb-meta-content">
						<?php $content_type = rb_openai_content_type_selection(); ?>
						<select class="rb-meta-select" id="content-type" name="content_type">
							<?php foreach ( $content_type as $key => $label ): ?>
								<?php $selected = ( $key === 'title' ) ? 'selected="selected"' : ''; ?>
								<option value="<?php echo esc_attr( $key ); ?>" <?php echo $selected; ?>>
									<?php echo esc_html( $label ); ?>
								</option>
							<?php endforeach; ?>
						</select>
						<span class="rb-meta-info"><?php esc_html_e( 'Select the type of content you would like AI to write. The content type will use max_token = 2000 if your setting is less than that. Ensure the full content will be returned.', 'rb-openai' ); ?></span>
					</div>
				</div>
				<div class="rb-meta rb-textarea">
					<div class="rb-meta-title">
						<label for="prompt" class="rb-meta-label"><?php esc_html_e( 'Prompt', 'rb-openai' ); ?></label>
					</div>
					<div class="rb-meta-content">
						<textarea class="rb-meta-textarea" id="prompt" name="prompt" rows="4"></textarea>
						<span class="rb-meta-info"><?php esc_html_e( 'Please input your keywords or the content you\'d like AI to write about.', 'rb-openai' ); ?></span>
						<div class="openai-generate-wrap">
							<a href="#" id="openai-generate-content" class="rb-button"><?php esc_html_e( 'Generate Data', 'rb-openai' ); ?></a>
							<div id="rb-openai-notice"></div>
						</div>
					</div>
				</div>
				<div class="rb-meta rb-textarea">
					<div class="rb-meta-title">
						<span class="rb-meta-label"><?php esc_html_e( 'Prompt Info', 'rb-openai' ); ?></span>
					</div>
					<div class="rb-meta-content rb-copy-wrap">
						<div id="openai-prompt-info" class="rb-copy-content"></div>
						<a href="#" class="rb-meta-copy-btn"><i class="dashicons dashicons-admin-page"></i><?php esc_html_e( 'Copy', 'rb-openai' ); ?>
						</a>
						<span class="rb-meta-info"><?php esc_html_e( 'Prompts are specific requests submitted to the AI. If you experience slow responses from the API, you can also copy and paste your request into ChatGPT for a faster response.', 'rb-openai' ); ?></span>
					</div>
				</div>
				<div class="rb-meta rb-textarea">
					<div class="rb-meta-title">
						<label for="openai-response" class="rb-meta-label"><?php esc_html_e( 'AI Response', 'rb-openai' ); ?></label>
					</div>
					<div class="rb-meta-content rb-copy-wrap">
						<textarea disabled id="openai-response" class="rb-copy-content" rows="5"></textarea>
						<a href="#" class="rb-meta-copy-btn"><i class="dashicons dashicons-admin-page"></i><?php esc_html_e( 'Copy', 'rb-openai' ); ?>
						</a>
					</div>
				</div>
				<div class="rb-meta rb-select">
					<div class="rb-meta-title">
						<label for="writing-style" class="rb-meta-label"><?php esc_html_e( 'Writing Style', 'rb-openai' ); ?></label>
					</div>
					<div class="rb-meta-content">
						<?php $writing_styles = rb_openai_writing_style_selection(); ?>
						<select id="writing-style" name="rb_openai_writing_style">
							<?php foreach ( $writing_styles as $key => $label ): ?>
								<?php $selected = ( $key === $rb_openai_writing_style ) ? 'selected="selected"' : ''; ?>
								<option value="<?php echo esc_attr( $key ); ?>" <?php echo $selected; ?>>
									<?php echo esc_html( $label ); ?>
								</option>
							<?php endforeach; ?>
						</select>
						<span class="rb-meta-info"><?php esc_html_e( 'Choosing a writing style depends on your specific goals and the context in which you plan to use it.', 'rb-openai' ); ?></span>
					</div>
				</div>
				<div class="rb-meta rb-select">
					<div class="rb-meta-title">
						<label for="writing-language" class="rb-meta-label"><?php esc_html_e( 'Language', 'rb-openai' ); ?></label>
					</div>
					<div class="rb-meta-content">
						<?php $languages = rb_openai_languages_selection(); ?>
						<select id="writing-language" name="rb_openai_language">
							<?php foreach ( $languages as $key => $label ): ?>
								<?php $selected = ( $key === $rb_openai_language ) ? 'selected="selected"' : ''; ?>
								<option value="<?php echo esc_attr( $key ); ?>" <?php echo $selected; ?>>
									<?php echo esc_html( $label ); ?>
								</option>
							<?php endforeach; ?>
						</select>
						<span class="rb-meta-info"><?php esc_html_e( 'Choosing a writing language.', 'rb-openai' ); ?></span>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
endif;

if ( ! function_exists( 'rb_openai_footer_templates' ) ) {
	function rb_openai_footer_templates() { ?>
		<script type="text/html" id="tmpl-prompt-title">
			<?php esc_html_e( 'Write a compelling title for an article about {{prompt}}. It should be written in {{language}} and adopt a {{style}} tone. Please ensure the title is engaging and relevant to the target audience, and contains important keywords related to the topic for maximum search engine visibility, It must be between 50 and 60 characters.', 'rb-openai' ); ?>
		</script>
		<script type="text/html" id="tmpl-prompt-excerpt">
			<?php esc_html_e( 'Write a 60-160 word excerpt in {{language}} with a {{style}} tone for an article about {{prompt}}. Ensure it provides a captivating and informative introduction to the article content.', 'rb-openai' ); ?>
		</script>
		<script type="text/html" id="tmpl-prompt-content">
			<?php esc_html_e( 'Write a comprehensive article about {{prompt}}. The article should be written in {{language}} and adopt {{style}} tone. The content should be 800-1000 words and should provide valuable information, insights, and engaging storytelling related to the topic. Use markdown for the headings (## ).', 'rb-openai' ); ?>
		</script>
		<script type="text/html" id="tmpl-prompt-meta-description">
			<?php esc_html_e( 'Write a meta description for an article about {{prompt}}. The description should be written in {{language}} and adopt {{style}} tone. It should be concise, engaging, and provide a clear and compelling summary of the article content within 150 characters, following the provided SEO guidelines.', 'rb-openai' ); ?>
		</script>
		<script type="text/html" id="tmpl-prompt-meta-keywords">
			<?php esc_html_e( 'Generate meta keywords for an article about {{prompt}}. These keywords should be relevant to the article content, {{language}} appropriate, and in line with {{style}} tone. Please provide a list of keywords, separated by commas, that best describe the main topics and focus of the article.', 'rb-openai' ); ?>
		</script>
		<script type="text/html" id="tmpl-prompt-tags">
			<?php esc_html_e( 'Generate a comma-separated list of SEO-friendly tags for the title: {{prompt}}. These tags should be relevant to the article content and suitable for {{language}} while adopting {{style}} tone.', 'rb-openai' ); ?>
		</script>
	<?php }
}
