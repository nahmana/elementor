<?php

namespace Elementor\Modules\SeoTextLinker;

use Elementor\Core\Kits\Documents\Kit;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends \Elementor\Core\Base\Module {

	public function __construct() {
		$this->add_actions();

		$linker = new Seo_Linker();
		$linker->register();
	}

	public function add_actions() {
		add_action( 'elementor/kit/register_tabs', function( Kit $kit ) {
			$kit->register_tab( Settings_Site_Seo_Text_Linker::ID, Settings_Site_Seo_Text_Linker::class );
		} );
	}

	/**
	 * Retrieve the module name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'seo-text-linker';
	}
}
