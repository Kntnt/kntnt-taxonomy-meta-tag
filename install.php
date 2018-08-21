<?php

defined( 'WPINC' ) || die;

add_option( 'kntnt-taxonomy-meta-tag', [
	'taxonomies' => [
		'category',
		'post_tag',
	],
] );
