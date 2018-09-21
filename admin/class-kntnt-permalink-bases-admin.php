<?php

class Kntnt_Permalink_Bases_Admin {

	// Plugin's namespace.
	private $ns;

	public function __construct( $namespace ) {
		$this->ns = $namespace;
		add_action( 'load-options-permalink.php', [ $this, 'load_options_permalink' ] );
	}

	public function load_options_permalink() {
		$this->update_permalink_bases();
		$this->add_fields();
	}

	private function update_permalink_bases() {

		// Return if not a POST.
		if ( ! isset( $_POST[ $this->ns ] ) ) return;

		// Update options.
		$opt = get_option( $this->ns );
		$opt['author-base'] = $this->sanitize( $_POST[ $this->ns ]['author-base'] );
		$opt['date-base'] = $this->sanitize( $_POST[ $this->ns ]['date-base'] );
		update_option( $this->ns, $opt );

		// Update rewrite rules.
		global $wp_rewrite;
		$wp_rewrite->init(); // Really necessary or even correct here?
		$wp_rewrite->author_base = $this->prefix_base( $opt['author_base'] );
		$wp_rewrite->date_base = $this->prefix_base( $opt['date_base'] );

	}

	// See wp-admin/options-permalink.php
	private function prefix_base( $base ) {
		return $this->blog_prefix() . preg_replace( '#/+#', '/', '/' . str_replace( '#', '', $base ) );
	}

	// See wp-admin/options-permalink.php
	private function unfix_base( $base ) {
		return $this->blog_prefix() ? preg_replace( '|^/?blog|', '', $base ) : $base;
	}

	// See wp-admin/options-permalink.php
	private function blog_prefix() {
		return is_multisite() && ! is_subdomain_install() && is_main_site() && 0 === strpos( $permalink_structure, '/blog/' ) ? '/blog' : '';
	}

	private function sanitize( $base ) {
		// Author and date bases should be sanitized like tag base.
		return sanitize_option( 'tag_base', $base );
	}

	private function add_fields() {
		$this->add_settings_field( 'author-base', __( 'Author base', 'kntnt-permalink-bases' ) );
		$this->add_settings_field( 'date-base', __( 'Date base', 'kntnt-permalink-bases' ) );
	}

	private function add_settings_field( $id, $name ) {
		add_settings_field( "{$this->ns}-{$id}", $name, function ( $args ) {
			extract( $args );
			include "partials/settings-field.php";
		}, 'permalink', 'optional', [
			'field' => "{$this->ns}[{$id}]",
			'value' => $this->unfix_base( get_option( $this->ns )[ $id ] ),
		] );
	}

}
