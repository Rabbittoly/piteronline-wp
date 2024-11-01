<?php

namespace foxizElementor\Widgets;
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;
use foxizElementorControl\Options;
use function foxiz_get_product_grid;

class Product_Grid extends Widget_Base {

	public function get_name() {

		return 'foxiz-product-grid';
	}

	public function get_title() {

		return esc_html__( 'Foxiz - Grid Products', 'foxiz-core' );
	}

	public function get_icon() {

		return 'eicon-products';
	}

	public function get_keywords() {

		return [ 'foxiz', 'ruby', 'shop', 'list', 'shortcode', 'product', 'woocommerce' ];
	}

	public function get_categories() {

		return [ 'foxiz-flex' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'query_filters', [
				'label' => esc_html__( 'Query Settings', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'shortcode_info',
			[
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => html_entity_decode( esc_html__( 'You can refer shortcodes: <a target="_blank" rel="nofollow" href="https://woocommerce.com/document/woocommerce-shortcodes/">Click here</a>', 'foxiz-core' ) ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);
		$this->add_control(
			'columns_info',
			[
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'Foxiz allows you to manage the responsive grid columns via Layout > Columns. Don\'t need to add columns params.', 'foxiz-core' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
			]
		);
		$this->add_control(
			'shortcode', [
				'label'       => esc_html__( 'WooCommerce Shortcodes', 'foxiz-core' ),
				'type'        => Controls_Manager::TEXTAREA,
				'ai'          => [ 'active' => false ],
				'placeholder' => '[products limit="4" orderby="popularity" on_sale="true" offset="0"]',
				'description' => esc_html__( 'To ensure the flexibility, Foxiz allows you to use the WooCommerce Shortcodes to filter any products to show.', 'foxiz-core' ),
				'default'     => '[products limit="4"]',
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'box_section', [
				'label' => esc_html__( 'Boxed', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'box_style',
			[
				'label'       => esc_html__( 'Box Style', 'foxiz-core' ),
				'type'        => Controls_Manager::SELECT,
				'description' => esc_html__( 'Select a box style for the product listing.', 'foxiz-core' ),
				'options'     => [
					'0'        => esc_html__( 'Default (Add to cart Invisible)', 'foxiz-core' ),
					'standard' => esc_html__( 'Standard', 'foxiz-core' ),
					'bg'       => esc_html__( 'Background', 'foxiz-core' ),
					'border'   => esc_html__( 'Border', 'foxiz-core' ),
					'shadow'   => esc_html__( 'Shadow', 'foxiz-core' ),
				],
				'default'     => '0',
			]
		);
		$this->add_control(
			'box_border',
			[
				'label'       => esc_html__( 'Border Radius', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => Options::border_description(),
				'selectors'   => [
					'{{WRAPPER}}' => '--wrap-border: {{VALUE}}px;',
				],
			]
		);
		$this->add_responsive_control(
			'box_padding', [
				'label'       => esc_html__( 'Box Padding', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => Options::el_spacing_description(),
				'selectors'   => [ '{{WRAPPER}}' => '--box-spacing: {{VALUE}}px;' ],
			]
		);
		$this->add_control(
			'box_color',
			[
				'label'       => esc_html__( 'Box Color', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'description' => Options::box_color_description(),
				'selectors'   => [ '{{WRAPPER}}' => '--box-color: {{VALUE}};' ],
			]
		);
		$this->add_control(
			'dark_box_color',
			[
				'label'       => esc_html__( 'Dark Mode - Box Color', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'description' => Options::box_dark_color_description(),
				'selectors'   => [ '{{WRAPPER}}' => '--dark-box-color: {{VALUE}};' ],
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'entry_category_section', [
				'label' => esc_html__( 'Entry Category', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'category_meta',
			[
				'label'        => esc_html__( 'Entry Category', 'foxiz-core' ),
				'type'         => Controls_Manager::SELECT,
				'description'  => esc_html__( 'Show or hide the product categories meta.', 'foxiz-core' ),
				'options'      => [
					'0'     => esc_html__( 'Show', 'foxiz-core' ),
					'hide'  => esc_html__( 'Hide', 'foxiz-core' ),
					'mhide' => esc_html__( 'Hide on Mobile', 'foxiz-core' ),
				],
				'prefix_class' => 'pcat-',
				'default'      => '0',
			]
		);
		$this->add_control(
			'category_color', [
				'label'       => esc_html__( 'Entry Category Color', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'description' => esc_html__( 'Select a color for the entry category.', 'foxiz-core' ),
				'selectors'   => [ '{{WRAPPER}}' => '--product-cat-color: {{VALUE}};' ],
			]
		);
		$this->add_control(
			'dark_category_color', [
				'label'       => esc_html__( 'Dark Mode - Entry Category Color', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'description' => esc_html__( 'Select a color for the entry category in dark mode.', 'foxiz-core' ),
				'selectors'   => [ '[data-theme="dark"] {{WRAPPER}}, {{WRAPPER}}.light-scheme' => '--product-cat-color: {{VALUE}};' ],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label'    => esc_html__( 'Custom Entry Category Font', 'foxiz-core' ),
				'name'     => 'category_font',
				'selector' => '{{WRAPPER}} .p-categories',
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'featured_section', [
				'label' => esc_html__( 'Featured Image', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'crop_size',
			[
				'label'       => esc_html__( 'Featured Image Size', 'foxiz-core' ),
				'type'        => Controls_Manager::SELECT,
				'description' => Options::crop_size(),
				'options'     => Options::crop_size_dropdown(),
				'default'     => '0',
			]
		);
		$this->add_responsive_control(
			'display_ratio', [
				'label'       => esc_html__( 'Custom Featured Ratio', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => Options::display_ratio_description(),
				'selectors'   => [
					'{{WRAPPER}}' => '--feat-ratio: {{VALUE}}',
				],
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'sale_section', [
				'label' => esc_html__( 'Sale Label', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'sale_color', [
				'label'       => esc_html__( 'Sale Color', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'description' => esc_html__( 'Select a color for the sale label.', 'foxiz-core' ),
				'selectors'   => [ '{{WRAPPER}}' => '--wc-sale-color: {{VALUE}};' ],
			]
		);
		$this->add_control(
			'sale_bg', [
				'label'       => esc_html__( 'Sale Background', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'description' => esc_html__( 'Select a background color for the sale label.', 'foxiz-core' ),
				'selectors'   => [ '{{WRAPPER}}' => '--wc-sale-bg: {{VALUE}};' ],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label'    => esc_html__( 'Sale Font', 'foxiz-core' ),
				'name'     => 'sale_font',
				'selector' => '{{WRAPPER}} .onsale',
			]
		);
		$this->add_responsive_control(
			'sale_padding',
			[
				'label'       => esc_html__( 'Inner Padding', 'foxiz-core' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'description' => esc_html__( 'Input a custom inner padding value for the sale label.', 'foxiz-core' ),
				'selectors'   => [
					'{{WRAPPER}} .onsale' => 'padding: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
				],
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'entry_title_section', [
				'label' => esc_html__( 'Product Title', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label'    => esc_html__( 'Title Font', 'foxiz-core' ),
				'name'     => 'title_font',
				'selector' => '{{WRAPPER}} .woocommerce-loop-product__title',
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'product_meta_section', [
				'label' => esc_html__( 'Product Price', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'price_color', [
				'label'       => esc_html__( 'Price Color', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'description' => esc_html__( 'Select a color for the price values.', 'foxiz-core' ),
				'selectors'   => [ '{{WRAPPER}}' => '--wc-price-color: {{VALUE}};' ],
			]
		);
		$this->add_control(
			'dark_price_color', [
				'label'       => esc_html__( 'Dark Mode - Price Color', 'foxiz-core' ),
				'type'        => Controls_Manager::COLOR,
				'description' => esc_html__( 'Select a color for the price values in dark mode.', 'foxiz-core' ),
				'selectors'   => [ '[data-theme="dark"] {{WRAPPER}}, {{WRAPPER}}.light-scheme' => '--wc-price-color: {{VALUE}};' ],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label'    => esc_html__( 'Price Font', 'foxiz-core' ),
				'name'     => 'price_font',
				'selector' => '{{WRAPPER}} .price',
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'add_cart_section', [
				'label' => esc_html__( 'Add to Cart', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'cart_button_width',
			[
				'label'        => esc_html__( 'Style', 'foxiz-core' ),
				'description'  => esc_html__( 'Select a style for the add to cart button.', 'foxiz-core' ),
				'type'         => Controls_Manager::SELECT,
				'options'      => [
					'inline'   => esc_html__( '- Default -', 'foxiz-core' ),
					'fw'       => esc_html__( 'Fullwidth', 'foxiz-core' ),
					'b-inline' => esc_html__( 'Inline with Border', 'foxiz-core' ),
					'b-fw'     => esc_html__( 'Fullwidth with Border', 'foxiz-core' ),
				],
				'prefix_class' => 'cart-style-',
				'default'      => 'inline',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label'    => esc_html__( 'Add to Cart Font', 'foxiz-core' ),
				'name'     => 'add_cart_font',
				'selector' => '{{WRAPPER}} .button',
			]
		);
		$this->add_responsive_control(
			'add_cart_padding',
			[
				'label'       => esc_html__( 'Button Padding', 'foxiz-core' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'description' => esc_html__( 'Input a custom inner padding value for the add to cart button.', 'foxiz-core' ),
				'selectors'   => [
					'{{WRAPPER}} .product-btn a' => 'padding: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
				],
			]
		);
		$this->add_control(
			'add_cart_border', [
				'label'     => esc_html__( 'Border Radius', 'foxiz-core' ),
				'type'      => Controls_Manager::NUMBER,
				'selectors' => [
					'{{WRAPPER}}' => '--wcac-border: {{VALUE}}px;',
				],
			]
		);
		$this->start_controls_tabs( 'add_cart_tabs' );
		$this->start_controls_tab( 'add_cart_normal',
			[
				'label' => esc_html__( 'Normal', 'foxiz-core' ),
			]
		);
		$this->add_control(
			'add_cart_color', [
				'label'     => esc_html__( 'Text Color', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ '{{WRAPPER}}' => '--wcac-color: {{VALUE}};' ],
			]
		);
		$this->add_control(
			'add_cart_bg', [
				'label'     => esc_html__( 'Background', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ '{{WRAPPER}}' => '--wcac-bg: {{VALUE}}; --wcac-bg-90: {{VALUE}}e6;' ],
			]
		);
		$this->add_control(
			'add_cart_bcolor', [
				'label'     => esc_html__( 'Border Color', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ '{{WRAPPER}}' => '--wcac-bcolor: {{VALUE}};' ],
			]
		);
		$this->add_control(
			'dark_add_cart_color', [
				'label'     => esc_html__( 'Dark Mode - Text Color', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ '[data-theme="dark"] {{WRAPPER}}, {{WRAPPER}}.light-scheme' => '--wcac-color: {{VALUE}};' ],
			]
		);
		$this->add_control(
			'dark_add_cart_bg', [
				'label'     => esc_html__( 'Dark Mode - Background', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ '[data-theme="dark"] {{WRAPPER}}, {{WRAPPER}}.light-scheme' => '--wcac-bg: {{VALUE}}; --wcac-bg-90: {{VALUE}}e6;' ],
			]
		);
		$this->add_control(
			'dark_add_cart_bcolor', [
				'label'     => esc_html__( 'Dark Mode - Border Color', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ '[data-theme="dark"] {{WRAPPER}}, {{WRAPPER}}.light-scheme' => '--wcac-bcolor: {{VALUE}};' ],
			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab( 'add_cart_hover',
			[
				'label' => esc_html__( 'Hover', 'foxiz-core' ),
			]
		);

		$this->add_control(
			'add_cart_hover_color', [
				'label'     => esc_html__( 'Text Color', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ '{{WRAPPER}}' => '--wcac-h-color: {{VALUE}};' ],
			]
		);
		$this->add_control(
			'add_cart_hover_bg', [
				'label'     => esc_html__( 'Background', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ '{{WRAPPER}}' => '--wcac-h-bg: {{VALUE}}; --wcac-h-bg-90: {{VALUE}}e6;' ],
			]
		);
		$this->add_control(
			'add_cart_hover_bcolor', [
				'label'     => esc_html__( 'Border Color', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ '{{WRAPPER}}' => '--wcac-h-bcolor: {{VALUE}};' ],
			]
		);
		$this->add_control(
			'dark_add_cart_hover_color', [
				'label'     => esc_html__( 'Dark Mode - Text Color', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ '[data-theme="dark"] {{WRAPPER}}, {{WRAPPER}}.light-scheme ' => '--wcac-h-color: {{VALUE}};' ],
			]
		);
		$this->add_control(
			'dark_add_cart_hover_bg', [
				'label'     => esc_html__( 'Dark Mode - Background', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ '[data-theme="dark"] {{WRAPPER}}, {{WRAPPER}}.light-scheme' => '--wcac-h-bg: {{VALUE}}; --wcac-h-bg-90: {{VALUE}}e6;' ],
			]
		);
		$this->add_control(
			'dark_add_cart_hover_bcolor', [
				'label'     => esc_html__( 'Dark Mode - Border Color', 'foxiz-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ '[data-theme="dark"] {{WRAPPER}}, {{WRAPPER}}.light-scheme' => '--wcac-h-bcolor: {{VALUE}};' ],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
		$this->start_controls_section(
			'color_section', [
				'label' => esc_html__( 'Text Color Scheme', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'color_scheme',
			[
				'label'        => esc_html__( 'Text Color Scheme', 'foxiz-core' ),
				'type'         => Controls_Manager::SELECT,
				'description'  => Options::color_scheme_description(),
				'prefix_class' => ' ',
				'options'      => [
					'0' => esc_html__( 'Default (Dark Text)', 'foxiz-core' ),
					'1' => esc_html__( 'Light Text', 'foxiz-core' ),
				],
				'default'      => '0',
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'layout_section', [
				'label' => esc_html__( 'Layout', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_LAYOUT,
			]
		);
		$this->add_control(
			'desktop_layout',
			[
				'label'       => esc_html__( 'Desktop Layout', 'foxiz-core' ),
				'type'        => Controls_Manager::SELECT,
				'description' => esc_html__( 'Select a layout for the product listing on the desktop.', 'foxiz-core' ),
				'options'     => Options::mobile_layout_dropdown( false ),
				'default'     => 'grid',
			]
		);
		$this->add_control(
			'tablet_layout',
			[
				'label'       => esc_html__( 'Tablet Layout', 'foxiz-core' ),
				'type'        => Controls_Manager::SELECT,
				'description' => Options::tablet_layout_description(),
				'options'     => Options::mobile_layout_dropdown( false ),
				'default'     => 'grid',
			]
		);
		$this->add_control(
			'mobile_layout',
			[
				'label'       => esc_html__( 'Mobile Layout', 'foxiz-core' ),
				'type'        => Controls_Manager::SELECT,
				'description' => Options::mobile_layout_description(),
				'options'     => Options::mobile_layout_dropdown( false ),
				'default'     => 'grid',
			]
		);
		$this->add_responsive_control(
			'featured_list_width', [
				'label'       => esc_html__( 'List - Image Width', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => Options::mobile_featured_width_description(),
				'placeholder' => '150',
				'selectors'   => [ '{{WRAPPER}}' => '--feat-list-width: {{VALUE}}px;' ],
			]
		);
		$this->add_control(
			'featured_list_position', [
				'label'       => esc_html__( 'List - Image Position', 'foxiz-core' ),
				'type'        => Controls_Manager::SELECT,
				'description' => Options::featured_position_description(),
				'options'     => Options::featured_position_dropdown( false ),
				'default'     => 'left',
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'block_columns', [
				'label' => esc_html__( 'Columns', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_LAYOUT,
			]
		);
		$this->add_control(
			'columns',
			[
				'label'       => esc_html__( 'Columns on Desktop', 'foxiz-core' ),
				'type'        => Controls_Manager::SELECT,
				'description' => Options::columns_description(),
				'options'     => Options::columns_dropdown(),
				'default'     => '0',
			]
		);
		$this->add_control(
			'columns_tablet',
			[
				'label'       => esc_html__( 'Columns on Tablet', 'foxiz-core' ),
				'type'        => Controls_Manager::SELECT,
				'description' => Options::columns_tablet_description(),
				'options'     => Options::columns_dropdown(),
				'default'     => '0',
			]
		);
		$this->add_control(
			'columns_mobile',
			[
				'label'       => esc_html__( 'Columns on Mobile', 'foxiz-core' ),
				'type'        => Controls_Manager::SELECT,
				'description' => Options::columns_mobile_description(),
				'options'     => Options::columns_dropdown( [ 0, 1, 2 ] ),
				'default'     => '0',
			]
		);
		$this->add_control(
			'column_gap',
			[
				'label'       => esc_html__( 'Column Gap', 'foxiz-core' ),
				'type'        => Controls_Manager::SELECT,
				'description' => Options::column_gap_description(),
				'options'     => Options::column_gap_dropdown(),
				'default'     => '0',
			]
		);
		$this->add_responsive_control(
			'column_gap_custom', [
				'label'       => esc_html__( '1/2 Custom Gap Value', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => Options::column_gap_custom_description(),
				'selectors'   => [
					'{{WRAPPER}} .is-gap-custom'                  => 'margin-left: -{{VALUE}}px; margin-right: -{{VALUE}}px; --column-gap: {{VALUE}}px;',
					'{{WRAPPER}} .is-gap-custom .block-inner > *' => 'padding-left: {{VALUE}}px; padding-right: {{VALUE}}px;',
				],
			]
		);

		$this->end_controls_section();
		$this->start_controls_section(
			'border_section', [
				'label' => esc_html__( 'Grid Borders', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_LAYOUT,
			]
		);
		$this->add_control(
			'border_info',
			[
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => Options::column_border_info(),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
			]
		);
		$this->add_control(
			'column_border',
			[
				'label'       => esc_html__( 'Column Border', 'foxiz-core' ),
				'type'        => Controls_Manager::SELECT,
				'description' => Options::column_border_description(),
				'options'     => Options::column_border_dropdown(),
				'default'     => '0',
			]
		);
		$this->add_control(
			'bottom_border',
			[
				'label'       => esc_html__( 'Bottom Border', 'foxiz-core' ),
				'type'        => Controls_Manager::SELECT,
				'description' => Options::bottom_border_description(),
				'options'     => Options::column_border_dropdown(),
				'default'     => '0',
			]
		);
		$this->add_control(
			'last_bottom_border',
			[
				'label'       => esc_html__( 'Last Bottom Border', 'foxiz-core' ),
				'type'        => Controls_Manager::SELECT,
				'description' => Options::last_bottom_border_description(),
				'options'     => Options::switch_dropdown( false ),
				'default'     => '-1',
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'spacing_section', [
				'label' => esc_html__( 'Spacing', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_LAYOUT,
			]
		);
		$this->add_responsive_control(
			'el_spacing', [
				'label'       => esc_html__( 'Custom Element Spacing', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => Options::el_spacing_description(),
				'selectors'   => [ '{{WRAPPER}}' => '--el-spacing: {{VALUE}}px;' ],
			]
		);
		$this->add_responsive_control(
			'bottom_margin', [
				'label'       => esc_html__( 'Custom Bottom Margin', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => Options::el_margin_description(),
				'selectors'   => [ '{{WRAPPER}} .block-wrap' => '--bottom-spacing: {{VALUE}}px;' ],
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'center_section', [
				'label' => esc_html__( 'Centering', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_LAYOUT,
			]
		);
		$this->add_control(
			'center_mode',
			[
				'label'       => esc_html__( 'Centering Content', 'foxiz-core' ),
				'type'        => Controls_Manager::SELECT,
				'description' => esc_html__( 'Centering text and content for the product listing.', 'foxiz-core' ),
				'options'     => Options::switch_dropdown( false ),
				'default'     => '-1',
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'mobile_scroll_section', [
				'label' => esc_html__( 'Tablet/Mobile Horizontal Scroll', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_LAYOUT,
			]
		);
		$this->add_control(
			'horizontal_scroll',
			[
				'label'       => esc_html__( 'Horizontal Scroll', 'foxiz-core' ),
				'type'        => Controls_Manager::SELECT,
				'description' => Options::horizontal_scroll_description(),
				'options'     => Options::horizontal_scroll_dropdown(),
				'default'     => '0',
			]
		);
		$this->add_control(
			'scroll_width_tablet', [
				'label'       => esc_html__( 'Tablet - Post Module Width', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'placeholder' => '300',
				'description' => Options::scroll_width_tablet_description(),
				'selectors'   => [ '{{WRAPPER}}' => '--tablet-scroll-width: {{VALUE}}px;' ],
			]
		);
		$this->add_control(
			'scroll_width_mobile', [
				'label'       => esc_html__( 'Mobile - Post Module Width', 'foxiz-core' ),
				'type'        => Controls_Manager::NUMBER,
				'placeholder' => '300',
				'description' => Options::scroll_width_mobile_description(),
				'selectors'   => [ '{{WRAPPER}}' => '--mobile-scroll-width: {{VALUE}}px;' ],
			]
		);
		$this->end_controls_section();
	}

	protected function render() {

		if ( class_exists( 'WooCommerce' ) && function_exists( 'foxiz_get_product_grid' ) ) {

			$settings         = $this->get_settings();
			$settings['uuid'] = 'uid_' . $this->get_id();
			echo foxiz_get_product_grid( $settings );
		}
	}
}