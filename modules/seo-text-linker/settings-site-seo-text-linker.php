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

// TODO: after adding docs anable.
//	public function get_help_url() {
//		return 'https://go.elementor.com/global-site-identity';
//	}

	protected function register_tab_controls() {

		$this->start_controls_section(
			'link_section_' . $this->get_id(),
			[
				'label' => esc_html__( 'Text Linker', 'elementor' ),
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
				'required' => true,
			]
		);

		$repeater->add_control(
			'link',
			[
				'label' => esc_html__( 'Link', 'elementor' ),
				'placeholder' => esc_html__( 'https://your-link.com', 'elementor' ),
				'type' => Controls_Manager::URL,
				'label_block' => true,
				'required' => true,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'seo_repeater',
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
				//				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'underline_exist',
			[
				'label' => esc_html__( 'Choose Underline State', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => [
						'title' => esc_html__( 'None', 'elementor' ),
					],
					'underline' => [
						'title' => esc_html__( 'Underline', 'elementor' ),
					],
				],
				'default' => 'underline',
				'toggle' => false,
			]
		);

		$this->add_control(
			'underline_type',
			[
				'label' => esc_html__( 'Choose Underline Type', 'elementor' ),
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
				'condition' => [
					'underline_exist[value]' => 'underline',
				],
			]
		);

		$this->end_controls_section();
	}

	public function on_save( $data ) {

//		if ( isset( $data['settings']['site_favicon'] ) ) {
//			update_option( 'site_icon', $data['settings']['site_favicon']['id'] );
//		}
	}
}
