<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'foxiz_wc_plugin_status_info' ) ) {
	function foxiz_wc_plugin_status_info( $id = 'wc_status_info' ) {

		return [
			[
				'id'    => $id,
				'type'  => 'info',
				'style' => 'warning',
				'desc'  => html_entity_decode( esc_html__( 'Woocommerce Plugin is missing! Please install and activate <a href="https://wordpress.org/plugins/woocommerce">Woocommerce</a> plugin to enable the settings.', 'foxiz' ) ),
			],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_woocommerce' ) ) {
	function foxiz_register_options_woocommerce() {

		return [
			'id'    => 'foxiz_config_section_woocommerce',
			'title' => esc_html__( 'WooCommerce', 'foxiz' ),
			'desc'  => esc_html__( 'Select options for the shop.', 'foxiz' ),
			'icon'  => 'el el-shopping-cart',
		];
	}
}

/**
 * @return array
 * single product
 */
if ( ! function_exists( 'foxiz_register_options_wc_page' ) ) {
	function foxiz_register_options_wc_page() {

		return [
			'id'         => 'foxiz_config_section_wc_page',
			'title'      => esc_html__( 'Shop & Archives', 'foxiz' ),
			'desc'       => esc_html__( 'Select options for the shop and archive and single product pages.', 'foxiz' ),
			'icon'       => 'el el-folder-open',
			'subsection' => true,
			'fields'     => ! class_exists( 'WooCommerce' ) ? foxiz_wc_plugin_status_info() :
				[
					[
						'id'     => 'section_start_wc_shop',
						'type'   => 'section',
						'class'  => 'ruby-section-start',
						'title'  => esc_html__( 'Shop Page', 'foxiz' ),
						'indent' => true,
					],
					[
						'id'          => 'wc_shop_template',
						'type'        => 'textarea',
						'rows'        => 2,
						'title'       => esc_html__( 'Header Template Shortcode', 'foxiz' ),
						'subtitle'    => esc_html__( 'Input your template shortcode you would like to use Elementor builder to create a featured section at the top of shop page.', 'foxiz' ),
						'placeholder' => '[Ruby_E_Template id="1"]',
						'default'     => '',
					],
					[
						'id'       => 'wc_shop_posts_per_page',
						'type'     => 'text',
						'class'    => 'small-text',
						'validate' => 'numeric',
						'title'    => esc_html__( 'Products per Page', 'foxiz' ),
						'subtitle' => esc_html__( 'Select number of products per page for the shop page.', 'foxiz' ),
						'default'  => false,
					],
					[
						'id'       => 'wc_shop_sidebar_position',
						'type'     => 'image_select',
						'title'    => esc_html__( 'Shop Sidebar Position', 'foxiz' ),
						'subtitle' => esc_html__( 'Select sidebar position for the shop page if you enabled the sidebar.', 'foxiz' ),
						'options'  => foxiz_config_sidebar_position( false ),
						'default'  => 'none',
					],
					[
						'id'       => 'wc_shop_sidebar_name',
						'type'     => 'select',
						'title'    => esc_html__( 'Shop Sidebar Name', 'foxiz' ),
						'subtitle' => esc_html__( 'Select a sidebar for the shop page if you enabled the sidebar.', 'foxiz' ),
						'options'  => foxiz_config_sidebar_name(),
						'default'  => 'foxiz_sidebar_default',
					],
					[
						'id'     => 'section_end_wc_shop',
						'type'   => 'section',
						'class'  => 'ruby-section-end',
						'indent' => false,
					],
					[
						'id'     => 'section_start_wc_archive',
						'type'   => 'section',
						'class'  => 'ruby-section-start',
						'title'  => esc_html__( 'Category & Archives', 'foxiz' ),
						'indent' => true,
					],
					[
						'id'       => 'wc_archive_posts_per_page',
						'type'     => 'text',
						'class'    => 'small-text',
						'validate' => 'numeric',
						'title'    => esc_html__( 'Products per Page', 'foxiz' ),
						'subtitle' => esc_html__( 'Select number of products per page for the category pages.', 'foxiz' ),
						'default'  => false,
					],
					[
						'id'       => 'wc_archive_sidebar_position',
						'type'     => 'image_select',
						'title'    => esc_html__( 'Archive Sidebar Position', 'foxiz' ),
						'subtitle' => esc_html__( 'Select a sidebar position for product category and archive pages if you enabled the sidebar.', 'foxiz' ),
						'options'  => foxiz_config_sidebar_position( false ),
						'default'  => 'none',
					],
					[
						'id'       => 'wc_archive_sidebar_name',
						'type'     => 'select',
						'title'    => esc_html__( 'Archive Sidebar Name', 'foxiz' ),
						'subtitle' => esc_html__( 'Select a sidebar for product category and archive pages if you enabled the sidebar.', 'foxiz' ),
						'options'  => foxiz_config_sidebar_name(),
						'default'  => 'foxiz_sidebar_default',
					],
					[
						'id'     => 'section_end_wc_archive',
						'type'   => 'section',
						'class'  => 'ruby-section-end',
						'indent' => false,
					],
					[
						'id'     => 'section_start_wc_sidebar',
						'type'   => 'section',
						'class'  => 'ruby-section-start',
						'title'  => esc_html__( 'Sidebar', 'foxiz' ),
						'indent' => true,
					],
					[
						'id'          => 'wc_shop_sidebar_width',
						'type'        => 'text',
						'title'       => esc_html__( 'Shop Sidebar Width', 'foxiz' ),
						'subtitle'    => esc_html__( 'Input a custom % width (1 to 100) for the shop, product category, and archive sidebars.', 'foxiz' ),
						'placeholder' => '30',
						'default'     => '',
					],
					[
						'id'     => 'section_end_wc_sidebar',
						'type'   => 'section',
						'class'  => 'ruby-section-end',
						'indent' => false,
					],
					[
						'id'       => 'section_start_wc_spacing',
						'type'     => 'section',
						'class'    => 'ruby-section-start',
						'title'    => esc_html__( 'Product Listing Gap', 'foxiz' ),
						'subtitle' => [
							esc_html__( 'These settings apply to the main product listings on the shop, product category, and archive pages.', 'foxiz' ),
							esc_html__( 'For blocks created with "Foxiz - Grid Product," you can adjust all spacing in the Layout tab of the block settings.', 'foxiz' ),
						],
						'indent'   => true,
					],
					[
						'id'          => 'wc_product_gap_desktop',
						'title'       => esc_html__( 'Desktop - 1/2 Column Gap', 'foxiz' ),
						'subtitle'    => esc_html__( 'Set the column spacing (in pixels) between product listings for desktop.', 'foxiz' ),
						'description' => esc_html__( 'The actual gap will be twice the value you input.', 'foxiz' ),
						'placeholder' => '20',
						'type'        => 'text',
						'default'     => '',
					],
					[
						'id'          => 'wc_product_gap_tablet',
						'title'       => esc_html__( 'Tablet - 1/2 Column Gap', 'foxiz' ),
						'subtitle'    => esc_html__( 'Set the column spacing (in pixels) between product listings for tablet.', 'foxiz' ),
						'description' => esc_html__( 'The actual gap will be twice the value you input.', 'foxiz' ),
						'placeholder' => '15',
						'type'        => 'text',
						'default'     => '',
					],
					[
						'id'     => 'section_end_wc_spacing',
						'type'   => 'section',
						'class'  => 'ruby-section-end',
						'indent' => false,
					],
					[
						'id'       => 'section_start_wc_bottom_margin',
						'type'     => 'section',
						'class'    => 'ruby-section-start',
						'title'    => esc_html__( 'Product Listing Bottom Margin', 'foxiz' ),
						'subtitle' => [
							esc_html__( 'These settings apply to the main product listings on the shop, product category, and archive pages.', 'foxiz' ),
							esc_html__( 'For blocks created with "Foxiz - Grid Product," you can adjust all spacing in the Layout tab of the block settings.', 'foxiz' ),
						],
						'indent'   => true,
					],
					[
						'id'          => 'wc_product_margin_desktop',
						'title'       => esc_html__( 'Desktop - Bottom Margin', 'foxiz' ),
						'subtitle'    => esc_html__( 'Input custom bottom margin values (in pixels) between products in the listing for desktop.', 'foxiz' ),
						'placeholder' => '35',
						'type'        => 'text',
						'default'     => '',
					],
					[
						'id'          => 'wc_product_margin_tablet',
						'title'       => esc_html__( 'Tablet - Bottom Margin', 'foxiz' ),
						'subtitle'    => esc_html__( 'Input custom bottom margin values (in pixels) between products in the listing for tablet.', 'foxiz' ),
						'placeholder' => '35',
						'type'        => 'text',
						'default'     => '',
					],
					[
						'id'          => 'wc_product_margin_mobile',
						'title'       => esc_html__( 'Mobile - Bottom Margin', 'foxiz' ),
						'subtitle'    => esc_html__( 'Input custom bottom margin values (in pixels) between products in the listing for mobile.', 'foxiz' ),
						'placeholder' => '35',
						'type'        => 'text',
						'default'     => '',
					],
					[
						'id'     => 'section_end_wc_bottom_margin',
						'type'   => 'section',
						'class'  => 'ruby-section-end no-border',
						'indent' => false,
					],
				],
		];
	}
}

/**
 * @return array
 * styling
 */
if ( ! function_exists( 'foxiz_register_options_wc_style' ) ) {
	function foxiz_register_options_wc_style() {

		return [
			'id'         => 'foxiz_config_section_wc_style',
			'title'      => esc_html__( 'General', 'foxiz' ),
			'desc'       => esc_html__( 'Select styles and layout for the product listing.', 'foxiz' ),
			'icon'       => 'el el-adjust-alt',
			'subsection' => true,
			'fields'     => ! class_exists( 'WooCommerce' ) ? foxiz_wc_plugin_status_info( 'wc_style_info' ) :
				[
					[
						'id'    => 'info_product_block',
						'type'  => 'info',
						'style' => 'success',
						'desc'  => esc_html__( 'You can use "Foxiz - Grid Products" to display products on the homepage and other pages. This block also supports individual responsive settings.', 'foxiz' ),
					],
					[
						'id'     => 'section_start_wc_color',
						'type'   => 'section',
						'class'  => 'ruby-section-start',
						'title'  => esc_html__( 'Color', 'foxiz' ),
						'indent' => true,
					],
					[
						'id'          => 'wc_global_color',
						'title'       => esc_html__( 'Highlight Color', 'foxiz' ),
						'subtitle'    => esc_html__( 'Select a global color for WooCommerce pages. This setting will override the global color.', 'foxiz' ),
						'type'        => 'color',
						'transparent' => false,
						'validate'    => 'color',
					],
					[
						'id'     => 'section_end_wc_color',
						'type'   => 'section',
						'class'  => 'ruby-section-end',
						'indent' => false,
					],
					[
						'id'     => 'section_start_wc_boxed',
						'type'   => 'section',
						'class'  => 'ruby-section-start',
						'title'  => esc_html__( 'Boxed', 'foxiz' ),
						'indent' => true,
					],
					[
						'id'          => 'wc_box_style',
						'title'       => esc_html__( 'Box Style', 'foxiz' ),
						'subtitle'    => esc_html__( 'Select a box style for the product listing.', 'foxiz' ),
						'description' => esc_html__( 'Add to cart Invisible style will only apply to desktop.', 'foxiz' ),
						'type'        => 'select',
						'options'     => [
							'0'        => esc_html__( 'Default (Add to cart Invisible)', 'foxiz' ),
							'standard' => esc_html__( 'Standard', 'foxiz' ),
							'bg'       => esc_html__( 'Background', 'foxiz' ),
							'border'   => esc_html__( 'Border', 'foxiz' ),
							'shadow'   => esc_html__( 'Shadow', 'foxiz' ),
						],
						'default'     => '0',
					],
					[
						'id'          => 'wc_box_color',
						'title'       => esc_html__( 'Box Color', 'foxiz' ),
						'subtitle'    => esc_html__( 'Select a color for the background or border style.', 'foxiz' ),
						'type'        => 'color',
						'transparent' => false,
					],
					[
						'id'          => 'wc_dark_box_color',
						'title'       => esc_html__( 'Dark Mode - Box Color', 'foxiz' ),
						'subtitle'    => esc_html__( 'Select a color for the background or border style in dark mode.', 'foxiz' ),
						'type'        => 'color',
						'transparent' => false,
					],
					[
						'id'     => 'section_end_wc_boxed',
						'type'   => 'section',
						'class'  => 'ruby-section-end',
						'indent' => false,
					],
					[
						'id'       => 'section_start_wc_responsive',
						'type'     => 'section',
						'class'    => 'ruby-section-start',
						'title'    => esc_html__( 'Responsive List/Grid & Centering', 'foxiz' ),
						'subtitle' => esc_html__( 'This setting will apply only to main product loop of the standard WooCommerce pages such as shop, category and archives.', 'foxiz' ),
						'indent'   => true,
					],
					[
						'id'       => 'wc_responsive_list',
						'type'     => 'switch',
						'title'    => esc_html__( 'Mobile List Layout', 'foxiz' ),
						'subtitle' => esc_html__( 'Display product list in the gird layout on mobile devices.', 'foxiz' ),
						'default'  => true,
					],
					[
						'id'       => 'wc_centered',
						'type'     => 'switch',
						'title'    => esc_html__( 'Centering Products', 'foxiz' ),
						'subtitle' => esc_html__( 'Center the product title and meta in the listing.', 'foxiz' ),
						'default'  => false,
					],
					[
						'id'     => 'section_end_wc_responsive',
						'type'   => 'section',
						'class'  => 'ruby-section-end',
						'indent' => false,
					],
					[
						'id'     => 'section_start_wc_sale_style',
						'type'   => 'section',
						'class'  => 'ruby-section-start',
						'title'  => esc_html__( 'Sale Label', 'foxiz' ),
						'indent' => true,
					],
					[
						'id'       => 'wc_sale_percent',
						'type'     => 'switch',
						'title'    => esc_html__( 'Percentage Saved', 'foxiz' ),
						'subtitle' => esc_html__( 'Display Percentage saved on WooCommerce sale products', 'foxiz' ),
						'default'  => true,
					],
					[
						'id'          => 'wc_sale_text',
						'title'       => esc_html__( 'Text Color', 'foxiz' ),
						'subtitle'    => esc_html__( 'Select a text color value for the sale icon.', 'foxiz' ),
						'type'        => 'color',
						'transparent' => false,
						'validate'    => 'color',
					],
					[
						'id'          => 'wc_sale_color',
						'title'       => esc_html__( 'Background', 'foxiz' ),
						'subtitle'    => esc_html__( 'Select a background color value for the sale icon.', 'foxiz' ),
						'type'        => 'color',
						'transparent' => false,
						'validate'    => 'color',
					],
					[
						'id'     => 'section_end_wc_sale_style',
						'type'   => 'section',
						'class'  => 'ruby-section-end',
						'indent' => false,
					],
					[
						'id'     => 'section_start_wc_price',
						'type'   => 'section',
						'class'  => 'ruby-section-start',
						'title'  => esc_html__( 'Price', 'foxiz' ),
						'indent' => true,
					],
					[
						'id'          => 'wc_price_color',
						'title'       => esc_html__( 'Price Color', 'foxiz' ),
						'subtitle'    => esc_html__( 'Select a color value for the product price.', 'foxiz' ),
						'type'        => 'color',
						'transparent' => false,
						'validate'    => 'color',
					],
					[
						'id'          => 'wc_dark_price_color',
						'title'       => esc_html__( 'Dark Mode - Price Color', 'foxiz' ),
						'subtitle'    => esc_html__( 'Select a color value for the product price in dark mode.', 'foxiz' ),
						'type'        => 'color',
						'transparent' => false,
						'validate'    => 'color',
					],
					[
						'id'     => 'section_end_wc_price',
						'type'   => 'section',
						'class'  => 'ruby-section-end',
						'indent' => false,
					],
					[
						'id'     => 'section_start_wc_review',
						'type'   => 'section',
						'class'  => 'ruby-section-start',
						'title'  => esc_html__( 'Review', 'foxiz' ),
						'indent' => true,
					],
					[
						'id'          => 'wc_star_color',
						'title'       => esc_html__( 'Review Start Color', 'foxiz' ),
						'subtitle'    => esc_html__( 'Select a color value for the stars review.', 'foxiz' ),
						'type'        => 'color',
						'transparent' => false,
						'validate'    => 'color',
					],
					[
						'id'          => 'wc_dark_star_color',
						'title'       => esc_html__( 'Dark Mode - Review Start Color', 'foxiz' ),
						'subtitle'    => esc_html__( 'Select a color value for the stars review in dark mode.', 'foxiz' ),
						'type'        => 'color',
						'transparent' => false,
						'validate'    => 'color',
					],
					[
						'id'     => 'section_end_wc_review',
						'type'   => 'section',
						'class'  => 'ruby-section-end',
						'indent' => false,
					],
					[
						'id'     => 'section_start_wc_add_to_cart',
						'type'   => 'section',
						'class'  => 'ruby-section-start',
						'title'  => esc_html__( 'Add to Cart Style', 'foxiz' ),
						'indent' => true,
					],
					[
						'id'       => 'wc_add_cart_style',
						'title'    => esc_html__( 'Button Style', 'foxiz' ),
						'subtitle' => esc_html__( 'Select a style for the add to cart button.', 'foxiz' ),
						'type'     => 'select',
						'options'  => [
							'inline'   => esc_html__( '- Default -', 'foxiz' ),
							'fw'       => esc_html__( 'Fullwidth', 'foxiz' ),
							'b-inline' => esc_html__( 'Inline with Border', 'foxiz' ),
							'b-fw'     => esc_html__( 'Fullwidth with Border', 'foxiz' ),
						],
						'default'  => 'inline',
					],
					[
						'id'       => 'wc_add_cart_border',
						'title'    => esc_html__( 'Border Radius', 'foxiz' ),
						'subtitle' => esc_html__( 'Input a custom border radius value for "Add to Cart" button.', 'foxiz' ),
						'type'     => 'text',
						'validate' => 'numeric',
					],
					[
						'id'     => 'section_end_wc_add_to_cart',
						'type'   => 'section',
						'class'  => 'ruby-section-end',
						'indent' => false,
					],
					[
						'id'     => 'section_start_wc_add_to_cart_normal',
						'type'   => 'section',
						'class'  => 'ruby-section-start',
						'title'  => esc_html__( 'Add to Cart - Normal', 'foxiz' ),
						'indent' => true,
					],
					[
						'id'          => 'wc_add_cart_text',
						'title'       => esc_html__( 'Text Color', 'foxiz' ),
						'subtitle'    => esc_html__( 'Select a text color for the "Add to Cart" button.', 'foxiz' ),
						'type'        => 'color',
						'transparent' => false,
						'validate'    => 'color',
					],
					[
						'id'          => 'wc_add_cart_color',
						'title'       => esc_html__( 'Background', 'foxiz' ),
						'subtitle'    => esc_html__( 'Select a background color for the "Add to Cart" button.', 'foxiz' ),
						'type'        => 'color',
						'transparent' => false,
						'validate'    => 'color',
					],
					[
						'id'          => 'wc_add_cart_bcolor',
						'title'       => esc_html__( 'Border', 'foxiz' ),
						'subtitle'    => esc_html__( 'Select a border color for the "Add to Cart" button.', 'foxiz' ),
						'type'        => 'color',
						'transparent' => false,
						'validate'    => 'color',
					],
					[
						'id'          => 'wc_dark_add_cart_text',
						'title'       => esc_html__( 'Dark Mode - Text Color', 'foxiz' ),
						'subtitle'    => esc_html__( 'Select a text color for the "Add to Cart" button in dark mode.', 'foxiz' ),
						'type'        => 'color',
						'transparent' => false,
						'validate'    => 'color',
						'default'     => '#ffffff',
					],
					[
						'id'          => 'wc_dark_add_cart_color',
						'title'       => esc_html__( 'Dark Mode - Background', 'foxiz' ),
						'subtitle'    => esc_html__( 'Select a background color for the "Add to Cart" button in dark mode.', 'foxiz' ),
						'type'        => 'color',
						'transparent' => false,
						'validate'    => 'color',
					],
					[
						'id'          => 'wc_dark_add_cart_bcolor',
						'title'       => esc_html__( 'Dark Mode - Border', 'foxiz' ),
						'subtitle'    => esc_html__( 'Select a border color for the "Add to Cart" button in dark mode.', 'foxiz' ),
						'type'        => 'color',
						'transparent' => false,
						'validate'    => 'color',
					],
					[
						'id'     => 'section_end_wc_add_to_cart_normal',
						'type'   => 'section',
						'class'  => 'ruby-section-end',
						'indent' => false,
					],
					[
						'id'     => 'section_start_wc_add_to_cart_hover',
						'type'   => 'section',
						'class'  => 'ruby-section-start',
						'title'  => esc_html__( 'Add to Cart - Hover', 'foxiz' ),
						'indent' => true,
					],
					[
						'id'          => 'wc_add_cart_hover_text',
						'title'       => esc_html__( 'Text Color', 'foxiz' ),
						'subtitle'    => esc_html__( 'Select a text color when hovering.', 'foxiz' ),
						'type'        => 'color',
						'transparent' => false,
						'validate'    => 'color',
					],
					[
						'id'          => 'wc_add_cart_hover_color',
						'title'       => esc_html__( 'Background', 'foxiz' ),
						'subtitle'    => esc_html__( 'Select a background color when hovering.', 'foxiz' ),
						'type'        => 'color',
						'transparent' => false,
						'validate'    => 'color',
					],
					[
						'id'          => 'wc_add_cart_hover_bcolor',
						'title'       => esc_html__( 'Border', 'foxiz' ),
						'subtitle'    => esc_html__( 'Select a border color when hovering.', 'foxiz' ),
						'type'        => 'color',
						'transparent' => false,
						'validate'    => 'color',
					],
					[
						'id'          => 'wc_dark_add_cart_hover_text',
						'title'       => esc_html__( 'Dark Mode - Text Color', 'foxiz' ),
						'subtitle'    => esc_html__( 'Select a border color when hovering in dark mode.', 'foxiz' ),
						'type'        => 'color',
						'transparent' => false,
						'validate'    => 'color',
					],
					[
						'id'          => 'wc_dark_add_cart_hover_color',
						'title'       => esc_html__( 'Dark Mode - Background', 'foxiz' ),
						'subtitle'    => esc_html__( 'Select a background color when hovering in dark mode.', 'foxiz' ),
						'type'        => 'color',
						'transparent' => false,
						'validate'    => 'color',
					],
					[
						'id'          => 'wc_dark_add_cart_hover_bcolor',
						'title'       => esc_html__( 'Dark Mode - Border', 'foxiz' ),
						'subtitle'    => esc_html__( 'Select a border color when hovering in dark mode.', 'foxiz' ),
						'type'        => 'color',
						'transparent' => false,
						'validate'    => 'color',
					],
					[
						'id'     => 'section_end_wc_add_to_cart_hover',
						'type'   => 'section',
						'class'  => 'ruby-section-end',
						'indent' => false,
					],
					[
						'id'     => 'section_start_wc_add_popup',
						'type'   => 'section',
						'class'  => 'ruby-section-start',
						'title'  => esc_html__( 'Add to Cart Notification', 'foxiz' ),
						'indent' => true,
					],
					[
						'id'       => 'wc_add_cart_popup',
						'title'    => esc_html__( 'Popup Notification', 'foxiz' ),
						'subtitle' => esc_html__( 'Show a popup notification at the bottom when a product is added to the cart.', 'foxiz' ),
						'type'     => 'switch',
						'default'  => true,
					],
					[
						'id'     => 'section_end_wc_add_popup',
						'type'   => 'section',
						'class'  => 'ruby-section-end no-border',
						'indent' => false,
					],
				],
		];
	}
}

/**
 * @return array
 * styling
 */
if ( ! function_exists( 'foxiz_register_options_wc_single' ) ) {
	function foxiz_register_options_wc_single() {

		return [
			'id'         => 'foxiz_config_section_wc_single',
			'title'      => esc_html__( 'Single Product', 'foxiz' ),
			'desc'       => esc_html__( 'Select settings for the single product page.', 'foxiz' ),
			'icon'       => 'el el-shopping-cart',
			'subsection' => true,
			'fields'     => ! class_exists( 'WooCommerce' ) ? foxiz_wc_plugin_status_info( 'wc_single_info' ) :
				[
					[
						'id'     => 'section_start_wc_single_style',
						'type'   => 'section',
						'class'  => 'ruby-section-start',
						'title'  => esc_html__( 'Group Product Type', 'foxiz' ),
						'indent' => true,
					],
					[
						'id'       => 'wc_group_thumbnail',
						'type'     => 'switch',
						'title'    => esc_html__( 'Group Product Images', 'foxiz' ),
						'subtitle' => esc_html__( 'Enable or disable the group product featured images.', 'foxiz' ),
						'default'  => true,
					],
					[
						'id'          => 'wc_gallery_nav_columns',
						'title'       => esc_html__( 'Gallery Nav Columns', 'foxiz' ),
						'subtitle'    => esc_html__( 'Enter the number of columns for the gallery navigation on the single product page.', 'foxiz' ),
						'description' => esc_html__( 'This section is located under the featured image on the single product page.', 'foxiz' ),
						'type'        => 'text',
						'validate'    => 'numeric',
						'placeholder' => '4',
						'default'     => '',
					],
					[
						'id'          => 'wc_gallery_nav_ratio',
						'title'       => esc_html__( 'Gallery Nav Ratio', 'foxiz' ),
						'subtitle'    => esc_html__( 'Input custom ratio percent (height*100/width) for featured image you would like. e.g. 50', 'foxiz' ),
						'type'        => 'text',
						'validate'    => 'numeric',
						'placeholder' => '100',
						'default'     => '',
					],
					[
						'id'     => 'section_end_wc_single_style',
						'type'   => 'section',
						'class'  => 'ruby-section-end',
						'indent' => false,
					],
					[
						'id'     => 'section_start_wc_single_section',
						'type'   => 'section',
						'class'  => 'ruby-section-start',
						'title'  => esc_html__( 'Footer Sections', 'foxiz' ),
						'indent' => true,
					],
					[
						'id'       => 'wc_box_review',
						'type'     => 'switch',
						'title'    => esc_html__( 'Show Review Box', 'foxiz' ),
						'subtitle' => esc_html__( 'enable or disable the review box in the single product page.', 'foxiz' ),
						'default'  => true,
					],
					[
						'id'       => 'wc_related_posts_per_page',
						'type'     => 'text',
						'class'    => 'small-text',
						'validate' => 'numeric',
						'title'    => esc_html__( 'Total Related Products', 'foxiz' ),
						'subtitle' => esc_html__( 'Select total related product to show at once. leave blank if you want to set as default.', 'foxiz' ),
						'default'  => 4,
					],
					[
						'id'     => 'section_end_wc_single_section',
						'type'   => 'section',
						'class'  => 'ruby-section-end no-border',
						'indent' => false,
					],
				],
		];
	}
}

if ( ! function_exists( 'foxiz_register_options_typo_wc' ) ) {
	/**
	 * @return array
	 */
	function foxiz_register_options_typo_wc() {

		return [
			'id'         => 'foxiz_config_section_wc_typography',
			'title'      => esc_html__( 'WooCommerce', 'foxiz' ),
			'desc'       => esc_html__( 'Select font values for your shop.', 'foxiz' ),
			'icon'       => 'el el-font',
			'subsection' => true,
			'fields'     => ! class_exists( 'WooCommerce' ) ? foxiz_wc_plugin_status_info( 'wc_typo_info' ) :
				[
					[
						'id'     => 'section_start_wc_product_font',
						'type'   => 'section',
						'class'  => 'ruby-section-start',
						'title'  => esc_html__( 'Product Title', 'foxiz' ),
						'indent' => true,
					],
					[
						'id'             => 'font_product',
						'type'           => 'typography',
						'title'          => esc_html__( 'Product Title Font', 'foxiz' ),
						'subtitle'       => esc_html__( 'Select a custom font for the product listing title.', 'foxiz' ),
						'google'         => true,
						'font-backup'    => true,
						'text-align'     => false,
						'color'          => true,
						'text-transform' => true,
						'letter-spacing' => true,
						'line-height'    => false,
						'font-size'      => true,
						'units'          => 'px',
						'default'        => [],
					],
					[
						'id'       => 'font_product_size_tablet',
						'type'     => 'text',
						'validate' => 'numeric',
						'title'    => esc_html__( 'Tablet Font Size', 'foxiz' ),
						'subtitle' => esc_html__( 'Select a font size (in pixels) for product listing title on tablet devices (max screen width: 1024px), Leave this option blank to set the default value.', 'foxiz' ),
					],
					[
						'id'       => 'font_product_size_mobile',
						'type'     => 'text',
						'validate' => 'numeric',
						'title'    => esc_html__( 'Mobile Font Size', 'foxiz' ),
						'subtitle' => esc_html__( 'Select a font size (in pixels) for the product listing title on mobile devices (max screen width: 767px), Leave this option blank to set the default value.', 'foxiz' ),
					],
					[
						'id'     => 'section_end_wc_product_font',
						'type'   => 'section',
						'class'  => 'ruby-section-end no-border',
						'indent' => false,
					],
					[
						'id'     => 'section_end_wc_product_font',
						'type'   => 'section',
						'class'  => 'ruby-section-end no-border',
						'indent' => false,
					],
					[
						'id'       => 'section_start_wc_single_font',
						'type'     => 'section',
						'class'    => 'ruby-section-start',
						'title'    => esc_html__( 'Single Title', 'foxiz' ),
						'subtitle' => esc_html__( 'These settings below will apply to the single product.', 'foxiz' ),
						'indent'   => true,
					],
					[
						'id'       => 'font_sproduct_size',
						'type'     => 'text',
						'class'    => 'small-text',
						'validate' => 'numeric',
						'title'    => esc_html__( 'Font Size', 'foxiz' ),
						'subtitle' => esc_html__( 'Select a font size (in pixels) for the single product title.', 'foxiz' ),
					],
					[
						'id'       => 'font_sproduct_size_tablet',
						'type'     => 'text',
						'class'    => 'small-text',
						'validate' => 'numeric',
						'title'    => esc_html__( 'Tablet Font Size', 'foxiz' ),
						'subtitle' => esc_html__( 'Select a font size (in pixels) for the single product title on tablet devices (max screen width: 1024px), Leave this option blank to set the default value.', 'foxiz' ),
					],
					[
						'id'       => 'font_sproduct_size_mobile',
						'type'     => 'text',
						'class'    => 'small-text',
						'validate' => 'numeric',
						'title'    => esc_html__( 'Mobile Font Size', 'foxiz' ),
						'subtitle' => esc_html__( 'Select a font size (in pixels) for the single product title on mobile devices (max screen width: 767px), Leave this option blank to set the default value.', 'foxiz' ),
					],
					[
						'id'     => 'section_end_wc_single_font',
						'type'   => 'section',
						'class'  => 'ruby-section-end',
						'indent' => false,
					],
					[
						'id'       => 'section_start_wc_price_font',
						'type'     => 'section',
						'class'    => 'ruby-section-start',
						'title'    => esc_html__( 'Price Font', 'foxiz' ),
						'subtitle' => esc_html__( 'The font size values will apply only to the loop listing.', 'foxiz' ),
						'indent'   => true,
					],
					[
						'id'             => 'font_price',
						'type'           => 'typography',
						'title'          => esc_html__( 'Price Font', 'foxiz' ),
						'subtitle'       => esc_html__( 'Select a custom font for the product price.', 'foxiz' ),
						'google'         => true,
						'font-backup'    => true,
						'text-align'     => false,
						'color'          => false,
						'text-transform' => true,
						'letter-spacing' => true,
						'line-height'    => false,
						'font-size'      => true,
						'units'          => 'px',
						'default'        => [],
					],
					[
						'id'       => 'font_price_size_tablet',
						'type'     => 'text',
						'class'    => 'small-text',
						'validate' => 'numeric',
						'title'    => esc_html__( 'Tablet Font Size', 'foxiz' ),
						'subtitle' => esc_html__( 'Select a font size (in pixels) for the price title on tablet devices.', 'foxiz' ),
					],
					[
						'id'       => 'font_price_size_mobile',
						'type'     => 'text',
						'class'    => 'small-text',
						'validate' => 'numeric',
						'title'    => esc_html__( 'Mobile Font Size', 'foxiz' ),
						'subtitle' => esc_html__( 'Select a font size (in pixels) for the price on mobile devices.', 'foxiz' ),
					],
					[
						'id'     => 'section_end_wc_price_font',
						'type'   => 'section',
						'class'  => 'ruby-section-end no-border',
						'indent' => false,
					],
				],
		];
	}
}