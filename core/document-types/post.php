<?php
namespace Elementor\Core\DocumentTypes;

use Elementor\Controls_Manager;
use Elementor\Core\Base\Document;
use Elementor\Modules\PageTemplates\Module as PageTemplatesModule;
use Elementor\Group_Control_Background;
use Elementor\Plugin;
use Elementor\Settings;
use Elementor\Core\Settings\Manager as SettingsManager;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Post extends Document {

	public function get_name() {
		return 'post';
	}

	public static function get_title() {
		return __( 'Page', 'elementor' );
	}

	public function get_css_wrapper_selector() {
		return 'body.elementor-page-' . $this->get_main_id();
	}

	protected function _register_controls() {
		parent::_register_controls();

		self::register_hide_title_control( $this );

		self::register_post_fields_control( $this );

		self::register_style_controls( $this );
	}

	/**
	 * @param Document $document
	 */
	public static function register_hide_title_control( $document ) {
		$page_title_selector = SettingsManager::get_settings_managers( 'general' )->get_model()->get_settings( 'elementor_page_title_selector' );

		if ( ! $page_title_selector ) {
			$page_title_selector = 'h1.entry-title';
		}

		$document->start_injection( [
			'of' => 'post_status',
		] );

		$document->add_control(
			'hide_title',
			[
				'label' => __( 'Hide Title', 'elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => __( 'No', 'elementor' ),
				'label_on' => __( 'Yes', 'elementor' ),
				// translators: %s: Setting Page link
				'description' => sprintf( __( 'Not working? You can set a different selector for the title in the <a href="%s" target="_blank">Settings page</a>.', 'elementor' ), Settings::get_url() . '#tab-style' ),
				'selectors' => [
					'{{WRAPPER}} ' . $page_title_selector => 'display: none',
				],
			]
		);

		$document->end_injection();
	}

	/**
	 * @param Document $document
	 */
	public static function register_style_controls( $document ) {
		$document->start_controls_section(
			'section_page_style',
			[
				'label' => __( 'Body Style', 'elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$document->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'  => 'background',
			]
		);

		$document->add_responsive_control(
			'padding',
			[
				'label' => __( 'Padding', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}}' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$document->end_controls_section();

		Plugin::$instance->controls_manager->add_custom_css_controls( $document, Controls_Manager::TAB_STYLE );
	}

	/**
	 * @param Document $document
	 */
	public static function register_post_fields_control( $document ) {
		$document->start_injection( [
			'of' => 'post_status',
		] );

		if ( post_type_supports( $document->post->post_type, 'excerpt' ) ) {
			$document->add_control(
				'post_excerpt',
				[
					'label' => __( 'Excerpt', 'elementor' ),
					'type' => Controls_Manager::TEXTAREA,
					'default' => $document->post->post_excerpt,
					'label_block' => true,
				]
			);
		}

		if ( current_theme_supports( 'post-thumbnails' ) ) {
			$document->add_control(
				'post_featured_image',
				[
					'label' => __( 'Featured Image', 'elementor' ),
					'type' => Controls_Manager::MEDIA,
					'default' => [
						'id' => get_post_thumbnail_id(),
						'url' => get_the_post_thumbnail_url( $document->post->ID ),
					],
				]
			);
		}

		$document->end_injection();
	}

	public function __construct( array $data = [] ) {
		if ( $data ) {
			$template = get_post_meta( $data['post_id'], '_wp_page_template', true );
			if ( empty( $template ) ) {
				$template = 'default';
			}
			$data['settings']['template'] = $template;
		}

		parent::__construct( $data );
	}
}
