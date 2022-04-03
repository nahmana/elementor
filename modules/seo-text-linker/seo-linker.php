<?php

namespace Elementor\Modules\SeoTextLinker;

use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Seo_Linker {

	public function register() {

		add_filter( 'elementor/widget/render_content', function( $content ) {

			// Get content of current (Elementor) page:
			$kit = Plugin::$instance->kits_manager->get_active_kit_for_frontend();
			$settings = $kit->get_settings_for_display();

			// If no linker set, or first default linker is not set (no url to link), return regular the content:
			if ( ( count( $settings['seo_linker'] ) < 1 ) || ( count( $settings['seo_linker'] ) === 1 && '' === $settings['seo_linker'][0]['link']['url'] ) ) {
				return $content;
			}

			// If current page is in the exclude array, return regular content:
			$page_id = get_queried_object_id();
			$excludes = str_replace( ' ', '', $settings['seo_linker_exclude_pages'] );
			$excludes_array = explode( ',', $excludes );
			if ( in_array( $page_id, $excludes_array, true ) ) {
				return $content;
			}

			$new_content = $this->scan_content( $content, $settings );

			return $new_content;
		} );
	}

	public function scan_content( $content, $settings ) {

		$str = $content;
		$content_length = strlen( $str );
		$content_array = array();
		$p_content = ''; // 'p' means paragraph ( <p> tags).
		$other_content = '';
		$collect_p_mode = false;
		$is_a_tag = false; // 'a' means anchors ( <a> tags).
		$is_inside_p_tag = false;

		// Loop through "content" (DOM as string of current page) and find all paragraph (p tags),
		// excluding: a tag that wraps p tags, or a tag that are inside p tags.
		for ( $i = 0; $i < $content_length; $i++ ) {

			$current_char = $str[ $i ];

			if ( $collect_p_mode && '<' !== $current_char ) {
				$p_content .= $current_char;
				continue;
			}

			$other_content .= $current_char;

			if ( '<' === $current_char ) {
				if ( $collect_p_mode ) {
					// Finished collecting *current* <p> string content,
					// before inserting it to array of content, replace it with a link:
					$content_array[] = $this->replace_string( $p_content, $settings );
					$p_content = '';
				}

				$collect_p_mode = false;

				if ( 'a' === $str[ $i + 1 ] ) {
					$is_a_tag = true;
				} elseif ( '/' === $str[ $i + 1 ] && 'a' === $str[ $i + 2 ] ) {
					$is_a_tag = false;
				} elseif ( 'p' === $str[ $i + 1 ] ) {
					$is_inside_p_tag = true;
				} elseif ( '/' === $str[ $i + 1 ] && 'p' === $str[ $i + 2 ] ) {
					$is_inside_p_tag = false;
				}
			}

			if ( $is_inside_p_tag && '>' === $current_char && ! $is_a_tag ) {
				$collect_p_mode = true;
				// Finished collecting *none* <p> string:
				$content_array[] = $other_content;
				$other_content = '';
			}

			if ( $i === $content_length - 1 ) {
				$content_array[] = $other_content;
			}
		}

		// Re-build the DOM string content from the array of content:
		$new_content = implode( '', $content_array );

		return $new_content;
	}

	public function replace_string( $string, $settings ) {

		$underline_style = "style='text-decoration: var(--seo_linker_underline_exist); text-decoration-style: var(--seo_linker_underline_type); text-decoration-color: var(--seo_linker_underline_color)'";

		foreach ( $settings['seo_linker'] as $value ) {

			$word_to_search = $value['text'];
			$url_to_link = $value['link']['url'];
			$link_is_external = $value['link']['is_external'];

			$output = "<a href='" . $url_to_link . "'";
			if ( 'on' === $link_is_external ) {
				$output .= " target='_blank'";
			}
			$output .= ' ' . $underline_style . '>' . $word_to_search . '</a>';
			$string = str_replace( $word_to_search, $output, $string );
		}

		return $string;
	}
}
