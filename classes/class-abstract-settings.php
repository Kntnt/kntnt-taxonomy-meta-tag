<?php

namespace Kntnt\Taxonomy_Meta_Tag;

abstract class Abstract_Settings {

	public function run() {
		add_action( 'admin_menu', [ $this, 'add_options_page' ] );
	}

	// Add settings page to the option menu.
	public function add_options_page() {
		add_options_page( $this->title(), $this->title(), $this->capability(), Plugin::ns(), [ $this, 'show_settings_page' ] );
	}

	// Returns title used as menu item and as head of settings page.
	abstract protected function title();

	// Returns all fields used on the settigs page.
	abstract protected function fields();

	// Returns necessary capability to access the settings page.
	protected function capability() {
		return 'manage_options';
	}

	// Returns path to settings page.
	protected function settings_page_teplate() {
		return Plugin::plugin_dir( 'includes/settings-page.php' );
	}

	// Show settings page and update options.
	public function show_settings_page() {

		// Abort if current user has not permission to access the settings page.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'Unauthorized use.', 'kntnt-taxonomy-meta-tag' ) );
		}

		// Update options if the page is showned after a form post.
		if ( isset( $_POST[ Plugin::ns() ] ) ) {

			// Abort if the form's nonce is not correct or expired.
			if ( ! wp_verify_nonce( $_POST['_wpnonce'], Plugin::ns() ) ) {
				wp_die( __( 'Nonce failed.', 'kntnt-taxonomy-meta-tag' ) );
			}

			// Update options.
			$this->update_options( $_POST[ Plugin::ns() ] );

		}

		// Variables that will be visible for the settings-page template.
		$ns = Plugin::ns();
		$title = $this->title();
		$fields = $this->fields();
		$values = Plugin::option();

		// Default values that will be visible for the settings-page template.
		foreach ( $fields as $id => $field ) {
			if ( ! isset( $values[ $id ] ) && isset( $field['default'] ) ) {
				$values[ $id ] = $field['default'];
			}
		}

		// Render settings page; include the settings-page template.
		include $this->settings_page_teplate();

	}

	// Sanitize and save field values.
	private function update_options( $opt ) {
		$fields = $this->fields();
		foreach ( $opt as $id => &$val ) {
			if ( isset( $fields[ $id ]['sanitizer'] ) ) {
				$sanitizer = $fields[ $id ]['sanitizer'];
				$opt[ $id ] = $sanitizer( $val );
			}
		}
		update_option( Plugin::ns(), $opt );
	}

}
