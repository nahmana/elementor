<?php

namespace Elementor\Modules\SeoTextLinker;

use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Seo_Linker
{

	public function register() {
		// Get site settings (Elementor panel -> "site-settings" -> "settings" section -> "SEO Text Linker")
			// get strings to convert to a link
			// get underline settings
		// Get all <p> tags on site and convert the right strings


		add_filter( 'elementor/frontend/the_content', function($content) {
			$settings = Plugin::$instance->kits_manager->get_active_kit_for_frontend()->get_settings_for_display();




			// print_r($settings['underline_exist']);
			print_r($settings['seo_repeater'][0]['text']);
			echo "<br>";
			print_r($settings['seo_repeater'][0]['link']['url']);
			echo "<br>";
			print_r($settings['seo_repeater'][0]['link']['is_external']);
			echo "<br>";
			print_r($settings['seo_repeater'][1]['text']);
			echo "<br>";
			print_r($settings['seo_repeater'][1]['link']['url']);
			echo "<br>";
			print_r($settings['seo_repeater'][1]['link']['is_external']);
			echo "<br>";
			// $this->ScanContent($content);
			






			// _____________________________________________________


			// $xml_content = simplexml_load_string($content);
			// print_r($xml_content);

			// $str = htmlentities($content);
		

			// $bla = $this->MyHelper("bla");

			// echo "<br><hr><br>";
			// echo $bla;

			

			// echo "<br><hr><br>";
			// // echo $p_content;
			// // echo htmlentities($p_content);
			// // print_r(htmlentities($content_array));
			// foreach ($content_array as $key => $value) {
			// 	echo htmlentities($value);
			// 	echo "<br><hr><br>";
			// }

			// return  htmlentities($content);

		
				// foreach ($content_array as $value) {
		// 	echo htmlentities($value);
		// 	echo "<br><hr><br>";
		// }


			
			// $dom = new \DomDocument();
			// libxml_use_internal_errors(true);
			
			// $dom->loadHTML($content);
			// $xpath = new \DOMXpath($dom);

			// $elements = $xpath->query('//p');

			// foreach ($elements as $entry) {
			// 	echo "<br>>";
			// 	print_r($entry->childNode);
			// 	echo "<br><hr><br>";
			// }

			// print_r($xpath->query('//p'));
			// var_dump($xpath->query('//p'));

			// // $elements = $xpath->query('//p');
			// // var_dump($elements);

			// libxml_clear_errors();

			

			// var_dump($content);
			// content of specific page
			wp_die();
		} );

	}

	public function ScanContent($content) {

		$str = $content;
		$content_array = array();
		$p_content = "";
		$other_content = "";
		$collect_p_mode = false;
		$is_a_tag = false;
		$is_inside_p_tag = false;

		for ($i = 0; $i < strlen($str); $i++){

			$current_char = $str[$i];

			if ($collect_p_mode && $current_char !== "<") { 
				$p_content .= $current_char;
				continue;
			} 
			
			$other_content .= $current_char;
			
			if($current_char === "<") {
				if ($collect_p_mode) { 
					// Finished collecting current <p> string, convert and save it:
					$content_array[] = str_replace("ipsum", "<a href='/' >nachman</a>", $p_content); // replace before inserting to array |  
					$p_content = "";
				} 
				$collect_p_mode = false;
				if ($str[$i +1] === "a") {
					$is_a_tag = true;
				} else if ($str[$i +1] === "/" && $str[$i +2] === "a") {
					$is_a_tag = false;
				} else if ($str[$i +1] === "p") {
					$is_inside_p_tag = true;
				} else if ($str[$i +1] === "/" && $str[$i +2] === "p") {
					$is_inside_p_tag = false;
				} 
			}

			if($is_inside_p_tag && $current_char === ">" && !$is_a_tag ){
				$collect_p_mode = true;
				$content_array[] = $other_content; 
				$other_content = "";
			} 

			if ($i === strlen($str) -1) {
				$content_array[] = $other_content; 
			}
		}
		
		echo implode('', $content_array);
		// echo htmlentities(implode('', $content_array));
	}


	public function ReplaceStrings($string, $underline) {

	}


}
