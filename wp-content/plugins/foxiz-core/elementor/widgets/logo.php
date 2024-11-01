<?php

namespace foxizElementor\Widgets;
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use function foxiz_get_option;
use function get_bloginfo;
use function wp_get_attachment_image_src;

/**
 * Class
 *
 * @package foxizElementor\Widgets
 */
class Logo extends Widget_Base {

	public function get_name() {

		return 'foxiz-logo';
	}

	public function get_title() {

		return esc_html__( 'Foxiz - Site Logo', 'foxiz-core' );
	}

	public function get_icon() {

		return 'eicon-logo';
	}

	public function get_keywords() {

		return [ 'foxiz', 'ruby', 'header', 'template', 'builder', 'brand', 'title', 'image' ];
	}

	public function get_categories() {

		return [ 'foxiz_header' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'general', [
				'label' => esc_html__( 'General', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'logo',
			[
				'label'       => esc_html__( 'Logo Image', 'foxiz-core' ),
				'description' => esc_html__( 'For optimal display, use a retina-ready logo, which is twice the height of its wrapper.', 'foxiz-core' ),
				'type'        => Controls_Manager::MEDIA,
				'ai'          => [ 'active' => false ],
			]
		);
		$this->add_control(
			'dark_logo',
			[
				'label'       => esc_html__( 'Dark Mode - Logo Image', 'foxiz-core' ),
				'description' => esc_html__( 'This logo should match the main logo but with colors adjusted to contrast well with a dark mode header background.', 'foxiz-core' ),
				'type'        => Controls_Manager::MEDIA,
				'ai'          => [ 'active' => false ],
			]
		);
		$this->add_control(
			'logo_link',
			[
				'label'       => esc_html__( 'Custom Logo URL', 'foxiz-core' ),
				'description' => esc_html__( 'Input a custom URL for the logo, Default will return to the homepage.', 'foxiz-core' ),
				'type'        => Controls_Manager::URL,
				'default'     => [
					'url'               => '',
					'is_external'       => false,
					'nofollow'          => false,
					'custom_attributes' => '',
				],
				'label_block' => true,
			]
		);
		$this->add_control(
			'heading_tag',
			[
				'label'       => esc_html__( 'Site Title Included', 'foxiz-core' ),
				'description' => esc_html__( 'Add the site title (H1) and description (hidden mode) to optimize for SEO. This setting is for the main site logo in the home page and should only be enabled once if you added multiple logos.', 'foxiz-core' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => '',
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'logo-style-section', [
				'label' => esc_html__( 'Style', 'foxiz-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'logo_width', [
				'label'       => __( 'Logo Width', 'foxiz-core' ),
				'description' => esc_html__( 'Set a max width for your logo', 'foxiz-core' ),
				'type'        => Controls_Manager::SLIDER,
				'range'       => [
					'px' => [
						'min' => 0,
						'max' => 800,
					],
				],
				'selectors'   => [ '{{WRAPPER}} .the-logo img' => 'max-width: {{SIZE}}px; width: {{SIZE}}px' ],
			]
		);
		$this->add_control(
			'sticky_logo_width', [
				'label'       => __( 'Sticky Logo Width', 'foxiz-core' ),
				'description' => esc_html__( 'Set a max width for the logo if your logo is included in the sticky menu bar.', 'foxiz-core' ),
				'type'        => Controls_Manager::SLIDER,
				'range'       => [
					'px' => [
						'min' => 0,
						'max' => 800,
					],
				],
				'selectors'   => [ '.sticky-on {{WRAPPER}} .the-logo img' => 'max-width: {{SIZE}}px; width: {{SIZE}}px' ],
			]
		);
		$this->add_control(
			'align', [
				'label'     => esc_html__( 'Alignment', 'foxiz-core' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => esc_html__( 'Left', 'foxiz-core' ),
						'icon'  => 'eicon-align-start-h',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'foxiz-core' ),
						'icon'  => 'eicon-align-center-h',
					],
					'right'  => [
						'title' => esc_html__( 'Right', 'foxiz-core' ),
						'icon'  => 'eicon-align-end-h',
					],
				],
				'selectors' => [ '{{WRAPPER}} .the-logo' => 'text-align: {{VALUE}};', ],
			]
		);
		$this->add_control(
			'feat_lazyload',
			[
				'label'       => esc_html__( 'Lazy Load', 'foxiz-core' ),
				'type'        => Controls_Manager::SELECT,
				'description' => esc_html__( 'Enable or disable lazy load for the logo if you put this block inside the body or footer', 'foxiz-core' ),
				'options'     => [
					'0' => esc_html__( '- Disable -', 'foxiz-core' ),
					'1' => esc_html__( 'Enable', 'foxiz-core' ),
				],
				'default'     => '0',
			]
		);
		$this->end_controls_section();
	}

	/**
	 * @return false
	 */
	protected function render() {

		$settings = $this->get_settings();

		if ( empty( $settings['logo']['url'] ) ) {
			$default_logo = foxiz_get_option( 'logo' );
			if ( ! empty( $default_logo['url'] ) ) {
				$settings['logo'] = $default_logo;
			}
		}

		if ( empty( $settings['logo']['url'] ) ) {
			return false;
		}

		if ( empty( $settings['dark_logo']['url'] ) ) {
			$default_dark_logo = foxiz_get_option( 'dark_logo' );
			if ( ! empty( $default_dark_logo['url'] ) ) {
				$settings['dark_logo'] = $default_dark_logo;
			}
		}

		$logo_width  = 1;
		$logo_height = 1;

		if ( ! empty( $settings['logo']['id'] ) ) {
			$attachment = wp_get_attachment_image_src( $settings['logo']['id'], 'full' );
			if ( ! empty( $attachment[1] ) ) {
				$logo_width = $attachment[1];
			}
			if ( ! empty( $attachment[2] ) ) {
				$logo_height = $attachment[2];
			}
		}
		if ( empty( $settings['logo_link']['url'] ) ) {
			$settings['logo_link']['url'] = home_url( '/' );
		}
		$loading = 'eager';
		if ( ! empty( $settings['feat_lazyload'] ) && '1' === (string) $settings['feat_lazyload'] ) {
			$loading = 'lazy';
		}

		if ( foxiz_is_amp() ) {
			$loading = '';
		}

		$this->add_link_attributes( 'logo_link', $settings['logo_link'] );
		?>
		<div class="the-logo">
			<a <?php echo $this->get_render_attribute_string( 'logo_link' ); ?>>
				<?php if ( ! empty( $settings['dark_logo']['url'] ) ) : ?>
					<img <?php if ( ! empty( $loading ) ) {
						echo 'loading="' . esc_attr( $loading ) . '" decoding="async"';
					} ?> data-mode="default" width="<?php echo $logo_width; ?>" height="<?php echo $logo_height; ?>" src="<?php echo $settings['logo']['url']; ?>" alt="<?php echo ( ! empty( $settings['logo']['alt'] ) ) ? esc_attr( $settings['logo']['alt'] ) : get_bloginfo( 'name' ); ?>"/>
					<img <?php if ( ! empty( $loading ) ) {
						echo 'loading="' . esc_attr( $loading ) . '" decoding="async"';
					} ?> data-mode="dark" width="<?php echo $logo_width; ?>" height="<?php echo $logo_height; ?>" src="<?php echo $settings['dark_logo']['url']; ?>" alt="<?php echo ( ! empty( $settings['dark_logo']['alt'] ) ) ? esc_attr( $settings['dark_logo']['alt'] ) : ''; ?>"/>
				<?php else : ?>
					<img <?php if ( ! empty( $loading ) ) {
						echo 'loading="' . esc_attr( $loading ) . '" decoding="async"';
					} ?> width="<?php echo $logo_width; ?>" height="<?php echo $logo_height; ?>" src="<?php echo $settings['logo']['url']; ?>" alt="<?php echo ( ! empty( $settings['logo']['alt'] ) ) ? esc_attr( $settings['logo']['alt'] ) : get_bloginfo( 'name' ); ?>"/>
				<?php endif; ?>
			</a>
			<?php if ( is_front_page() && ! empty( $settings['heading_tag'] ) && 'yes' === $settings['heading_tag'] ) : ?>
				<h1 class="logo-title is-hidden"><?php bloginfo( 'name' ); ?></h1>
				<?php if ( get_bloginfo( 'description' ) ) : ?>
					<p class="site-description is-hidden"><?php echo get_bloginfo( 'description' ); ?></p>
				<?php endif;
			endif; ?>
		</div>
		<?php
	}
}