<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'foxiz_register_options_taxonomy' ) ) {
	function foxiz_register_options_taxonomy() {

		$fields = [
			[
				'id'       => 'section_start_tax_header',
				'type'     => 'section',
				'class'    => 'ruby-section-start',
				'title'    => esc_html__( 'Taxonomy Header', 'foxiz' ),
				'subtitle' => esc_html__( 'Please ensure that you construct the header template or add an H1 taxonomy somewhere if you choose to disable the header to avoid SEO issues.', 'foxiz' ),
				'indent'   => true,
			],
			[
				'id'       => 'tax_header',
				'title'    => esc_html__( 'Taxonomy Header', 'foxiz' ),
				'subtitle' => esc_html__( 'Disable or select a style for the taxonomy header.', 'foxiz' ),
				'type'     => 'select',
				'options'  => foxiz_config_archive_header( true ),
				'default'  => '0',
			],
			[
				'id'     => 'section_end_tax_header',
				'type'   => 'section',
				'class'  => 'ruby-section-end',
				'indent' => false,
			],
			[
				'id'     => 'section_start_tax_posts_per_page',
				'type'   => 'section',
				'class'  => 'ruby-section-start',
				'title'  => esc_html__( 'Posts per Page', 'foxiz' ),
				'indent' => true,
			],
			[
				'id'          => 'tax_posts_per_page',
				'title'       => esc_html__( 'Posts per Page', 'foxiz' ),
				'subtitle'    => esc_html__( 'Input posts per page for the taxonomy.', 'foxiz' ),
				'description' => esc_html__( 'The posts per page of the archive will apply if it is empty.', 'foxiz' ),
				'type'        => 'text',
				'validate'    => 'numeric',
				'default'     => '',
			],
			[
				'id'     => 'section_end_tax_posts_per_page',
				'type'   => 'section',
				'class'  => 'ruby-section-end',
				'indent' => false,
			],
			[
				'id'     => 'section_start_tax_template_global',
				'type'   => 'section',
				'class'  => 'ruby-section-start',
				'title'  => esc_html__( 'Global Listing Template', 'foxiz' ),
				'notice' => [
					esc_html__( 'The AJAX methods in the builder pagination settings of the global query block are not available. Numeric pagination will be used as a fallback option for the taxonomy pages.', 'foxiz' ),
					esc_html__( 'Create a blog listing layout with Ruby Template via Elementor page builder and assign the template shortcode here to create your own layout.', 'foxiz' ),
					esc_html__( 'Ensure "Use WP Global Query" under "Template Builder - Global Query > Query Mode" has been set in a block of your template to help the system understand that it is the main query.', 'foxiz' ),
				],
				'indent' => true,
			],
			[
				'id'          => 'tax_template_global',
				'title'       => esc_html__( 'Use Ruby Template for the Post Listing', 'foxiz' ),
				'subtitle'    => esc_html__( 'Input a "Ruby Template" shortcode to display as the main blog posts for the taxonomy. e.g: [Ruby_E_Template id="1"]', 'foxiz' ),
				'type'        => 'textarea',
				'rows'        => 2,
				'placeholder' => '[Ruby_E_Template id="1"]',
				'default'     => '',
			],
			[
				'id'     => 'section_end_tax_template_global',
				'type'   => 'section',
				'class'  => 'ruby-section-end',
				'indent' => false,
			],
		];

		$custom_taxonomy = apply_filters( 'ruby_taxonomies_config', [] );

		if ( ! empty( $custom_taxonomy ) && is_array( $custom_taxonomy ) ) {
			$count             = count( $custom_taxonomy );
			$index             = 0;
			$section_end_class = 'ruby-section-end';

			foreach ( $custom_taxonomy as $key => $taxonomy ) {
				$index ++;
				$label = ! empty( $taxonomy['label'] ) ? $taxonomy['label'] : $key;
				if ( $count === $index ) {
					$section_end_class = 'ruby-section-end no-border';
				}

				$fields[] = [
					'id'     => 'section_start_custom_taxonomy_' . $key,
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'for Taxonomy: ', 'foxiz' ) . $label,
					'notice' => [
						esc_html__( 'These settings apply specifically to this taxonomy as global configurations and will override any global settings configured above.', 'foxiz' ),
						esc_html__( 'You can configure settings for individual term slugs of this taxonomy on the edit term page.', 'foxiz' ),
					],
					'indent' => true,
				];
				$fields[] = [
					'id'       => $key . '_tax_header',
					'title'    => $label . ' - ' . esc_html__( 'Taxonomy Header', 'foxiz' ),
					'subtitle' => esc_html__( 'Disable or select a style for this taxonomy header.', 'foxiz' ),
					'type'     => 'select',
					'options'  => foxiz_config_archive_header( true ),
					'default'  => '0',
				];
				$fields[] = [
					'id'       => $key . '_tax_posts_per_page',
					'title'    => $label . ' - ' . esc_html__( 'Posts per Page', 'foxiz' ),
					'subtitle' => esc_html__( 'Input posts per page for this taxonomy.', 'foxiz' ),
					'type'     => 'text',
					'class'    => 'small-text',
					'validate' => 'numeric',
					'default'  => '',
				];
				$fields[] = [
					'id'          => $key . '_tax_template_global',
					'title'       => $label . ' - ' . esc_html__( 'Use Ruby Template for the Post Listing', 'foxiz' ),
					'subtitle'    => esc_html__( 'Input a "Ruby Template" shortcode to display as the main blog posts for this taxonomy. e.g: [Ruby_E_Template id="1"]', 'foxiz' ),
					'type'        => 'textarea',
					'rows'        => 2,
					'placeholder' => '[Ruby_E_Template id="1"]',
					'default'     => '',
				];
				$fields[] = [
					'id'     => 'section_start_end_taxonomy_' . $key,
					'type'   => 'section',
					'class'  => $section_end_class,
					'indent' => false,
				];
			}
		}

		return [
			'id'     => 'foxiz_config_section_tax',
			'title'  => esc_html__( 'Taxonomy', 'foxiz' ),
			'icon'   => 'el el-briefcase',
			'desc'   => esc_html__( 'The settings below allow you to create custom templates for all custom taxonomies created with third-party plugins such as Custom Post Type UI and Advanced Custom Fields (ACF). If no custom templates are set, the predefined blog layout settings from the archive panel will apply to the taxonomy page.', 'foxiz' ),
			'fields' => $fields,
		];
	}
}
