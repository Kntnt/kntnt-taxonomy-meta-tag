<?php

namespace Kntnt\Taxonomy_Meta_Tag;

require_once __DIR__ . '/class-abstract-settings.php';

class Settings extends Abstract_Settings {

	// Returns title used as menu item and as head of settings page.
	protected function title() {
		return __( 'Taxonomy Meta Tag', 'kntnt-taxonomy-meta-tag' );
	}

	// Returns all fields used on the settings page.
	protected function fields() {

		$fields['taxonomies'] = [
			'type' => 'checkbox group',
			'options' => $this->get_taxonomies(),
			'label' => __( 'Taxonomies', 'kntnt-taxonomy-meta-tag' ),
			'description' => __( 'Choose taxonomies whose terms should be printed in meta tags.', 'kntnt-taxonomy-meta-tag' ),
		];

		return $fields;

	}

	// Returns an array where keys are taxonomies machine name and values are
	// corresponding name in clear text.
	private function get_taxonomies() {
		global $wp_taxonomies;
		foreach ( $wp_taxonomies as $taxonomy ) {
			$taxonomies[ $taxonomy->name ] = "$taxonomy->label ($taxonomy->name)";
		}
		return $taxonomies;
	}

}
