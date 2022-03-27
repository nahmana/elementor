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

			$kit = Plugin::$instance->kits_manager->get_active_kit_for_frontend();
			$settings = $kit->get_settings_for_display();
			
			// If no linker set, or first default linker is not set (no url to link), return regular content:
			if( (count($settings['seo_linker']) < 1) || (count($settings['seo_linker']) === 1 && $settings['seo_linker'][0]['link']['url'] === '') ) {
				return $content;
			}

			// If current page is in exclude array, return regular content:
			$page_id = get_queried_object_id();
			$excludes = str_replace(' ', '', $settings['seo_linker_exclude_pages']);
			$excludes_array = explode(',', $excludes);
			if (in_array($page_id, $excludes_array)) {
				return $content;
			}

			$new_content = $this->scan_content($content, $settings);
			return $new_content;

		} );

	}

	public function scan_content($content, $settings) {

		$str = $content;
		$content_array = array();
		$p_content = ""; // 'p' means paragraph ( <p> tags).
		$other_content = "";
		$collect_p_mode = false;
		$is_a_tag = false; // 'a' means anchors ( <a> tags).
		$is_inside_p_tag = false;


		// Loop through "content" (DOM as string of current page) and find all paragraph (p tags),
		// excluding: a tag that wraps p tags, or a tag that are inside p tags.
		for ($i = 0; $i < strlen($str); $i++){

			$current_char = $str[$i];

			if ($collect_p_mode && $current_char !== "<") { 
				$p_content .= $current_char;
				continue;
			} 
			
			$other_content .= $current_char;
			
			if($current_char === "<") {
				if ($collect_p_mode) { 
					// Finished collecting *current* <p> string content,
					// before inserting it to array of content, replace it with a link: 
					$content_array[] = $this->replace_string($p_content, $settings); 
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
				// Finished collecting *none* <p> string:
				$content_array[] = $other_content; 
				$other_content = "";
			} 

			if ($i === strlen($str) -1) {
				$content_array[] = $other_content; 
			}
		}
		
		// Re-build the DOM string content from the array of content:
		$new_content = implode('', $content_array);
		return $new_content;
	}

	
	public function replace_string($string, $settings) {

		$underline_style = "style='text-decoration: var(--seo_linker_underline_exist); text-decoration-style: var(--seo_linker_underline_type); text-decoration-color: var(--seo_linker_underline_color)'";
	
		foreach ($settings['seo_linker'] as $value) {

			$word_to_search = $value['text'];
			$url_to_link = $value['link']['url'];
			$link_is_external = $value['link']['is_external'];

			$output = "<a href='" . $url_to_link . "'";
			if ($link_is_external === "on") {
				$output .=  " target='_blank'";
			}
			$output .= " " . $underline_style . ">" . $word_to_search . "</a>";
			$string = str_replace($word_to_search, $output, $string);
		}

		return $string;
	}
}



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
			// // print_r($settings['underline_exist']);

			// ________________________________________________
			// print_r($settings['seo_linker'][0]['text']);
			// echo "<br>";
			// print_r($settings['seo_linker'][0]['link']['url']);
			// echo "<br> is external: ";
			// print_r($settings['seo_linker'][0]['link']['is_external']);
			// echo "<br>";
			// print_r($settings['seo_linker'][0]['link']['nofollow']);
			// echo "<br> underline_exist: ";
			// print_r($settings['underline_exist']);
			// echo "<br>";
			// print_r($settings['underline_type']);
			// echo "<br>";
			// print_r($settings['exclude_pages']);
			// echo "<br>";
			
			// echo "<br>";