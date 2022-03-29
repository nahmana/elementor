<?php

namespace Elementor\Modules\SeoTextLinker;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Tab_Base;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Settings_Site_Seo_Text_Linker extends Tab_Base {

	const ID = 'settings-site-seo-text-linker';

	public function get_id() {
		return static::ID;
	}

	public function get_title() {
		return esc_html__( 'SEO Text Linker', 'elementor' );
	}

	public function get_group() {
		return 'settings';
	}

	public function get_icon() {
		return 'eicon-link';
	}

	protected function register_tab_controls() {

		$this->start_controls_section(
			'link_section_' . $this->get_id(),
			[
				'label' => esc_html__( 'Edit Text To Link', 'elementor' ),
				'tab' => $this->get_id(),
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'text',
			[
				'label' => esc_html__( 'Search For Text', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'link',
			[
				'label' => esc_html__( 'Link URL', 'elementor' ),
				'placeholder' => esc_html__( 'EXAMPLE: https://your-link.com', 'elementor' ),
				'type' => Controls_Manager::URL,
				'label_block' => true,
			]
		);

		$this->add_control(
			'seo_linker',
			[
				'label' => esc_html__( 'Items To Link', 'elementor' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'text' => esc_html__( 'Item #1', 'elementor' ),
						'link' => '',
					],
				],
				'title_field' => '{{{ text }}}',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'underline_section' . $this->get_id(),
			[
				'label' => esc_html__( 'Edit Underline', 'elementor' ),
				'tab' => $this->get_id(),
			]
		);

		$this->add_control(
			'seo_linker_underline_exist',
			[
				'label' => esc_html__( 'Choose Underline State', 'elementor' ),
				'label_block' => true,
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => [
						'title' => esc_html__( 'None', 'elementor' ),
					],
					'underline' => [
						'title' => esc_html__( 'Underline', 'elementor' ),
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--seo_linker_underline_exist: {{VALUE}};',
				],
				'default' => 'underline',
				'toggle' => false,
			]
		);

		$this->add_control(
			'seo_linker_underline_type',
			[
				'label' => esc_html__( 'Choose Underline Type', 'elementor' ),
				'label_block' => true,
				'type' => Controls_Manager::SELECT,
				'options' => [
					'solid' => [
						'title' => esc_html__( 'Solid', 'elementor' ),
					],
					'dashed' => [
						'title' => esc_html__( 'Dashed', 'elementor' ),
					],
					'dotted' => [
						'title' => esc_html__( 'Dotted', 'elementor' ),
					],
					'double' => [
						'title' => esc_html__( 'Double', 'elementor' ),
					],
					'wavy' => [
						'title' => esc_html__( 'Wavy', 'elementor' ),
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--seo_linker_underline_type: {{VALUE}};',
				],
				'condition' => [
					'seo_linker_underline_exist[value]' => 'underline',
				],
			]
		);

		$this->add_control(
			'seo_linker_underline_color',
			[
				'label' => esc_html__( 'Choose Underline Color', 'elementor' ),
				'label_block' => true,
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--seo_linker_underline_color: {{VALUE}};',
				],
				'condition' => [
					'seo_linker_underline_exist[value]' => 'underline',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'exclude_section' . $this->get_id(),
			[
				'label' => esc_html__( 'Exclude Pages', 'elementor' ),
				'tab' => $this->get_id(),
			]
		);

		$this->add_control(
			'seo_linker_exclude_pages',
			[
				'label' => esc_html__( 'Insert Pages IDs To Exclude From Linker', 'elementor' ),
				'label_block' => true,
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'EX: 15,16,45', 'elementor' ),
			]
		);

		$this->end_controls_section();
	}

	public function on_save( $data ) {

	//	if ( isset( $data['settings']['site_favicon'] ) ) {
	//		update_option( 'site_icon', $data['settings']['site_favicon']['id'] );
	//	}

	}
}


// 'description' => esc_html__( 'The `theme-color` meta tag will only be available in supported browsers and devices.', 'elementor' ),

// $this->add_control(
// 	$current_section['section'] . '_schemes_notice',
// 	[
// 		'name' => $current_section['section'] . '_schemes_notice',
// 		'type' => Controls_Manager::RAW_HTML,
// 		'raw' => sprintf(
// 			/* translators: 1: Link open tag, 2: Link close tag. */
// 			esc_html__( 'In order for Theme Style to affect all relevant Elementor elements, please disable Default Colors and Fonts from the %1$sSettings Page%2$s.', 'elementor' ),
// 			'<a href="' . esc_url( Settings::get_url() ) . '" target="_blank">',
// 			'</a>'
// 		),
// 		'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
// 		'render_type' => 'ui',
// 	]
// );
