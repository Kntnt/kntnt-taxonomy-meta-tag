<?php

namespace Kntnt\Taxonomy_Meta_Tag;

final class Meta_Tag_Writer {

	private $taxonomies;

	public function run() {
		$this->taxonomies = Plugin::option( 'taxonomies', [] );
		add_action( 'wp_head', [ $this, 'print_meta_tag' ] );
	}

	public function print_meta_tag() {
		global $post;
		foreach ( $this->taxonomies as $taxonomy ) {
			if ( ! is_wp_error( $terms = wp_get_object_terms( $post->ID, $taxonomy, [ 'fields' => 'slugs' ] ) ) ) {
				echo '<meta itemprop="' . $taxonomy . '" content="' . join( ',', $terms ) . '">' . PHP_EOL;
			}
		}
	}

}
