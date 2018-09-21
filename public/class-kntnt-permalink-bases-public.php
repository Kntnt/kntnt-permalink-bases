<?php

class Kntnt_Permalink_Bases_Public {

	// Plugin's namespace.
	private $ns;

	public function __construct( $ns ) {
		$this->ns = $ns;
		add_action( 'init', [ $this, 'update_author_base' ] );
		add_action( 'init', [ $this, 'update_date_base' ] );
	}

	public function update_author_base() {

		global $wp_rewrite;

		// Set author base.
		$wp_rewrite->author_base = get_option( $this->ns )['author-base'];

	}

	public function update_date_base() {

		global $wp_rewrite;

		// Generates $wp_rewrite->date_structure if not existing. Once generated,
		// it will not be re-generated.
		$wp_rewrite->get_date_permastruct();

		// If the permalink structure includes post id in the three first position,
		// WordPress prefixes $wp_rewrite->date_structure with `date/` which need
		// to be removed before we add our date base.
		if ( 'date/' == substr( $wp_rewrite->date_structure, 0, 5 ) ) {
			$wp_rewrite->date_structure = substr( $wp_rewrite->date_structure, 5 );
		}

		// Add date base to the date structure.
		$wp_rewrite->date_structure = get_option( $this->ns )['date-base'] . $wp_rewrite->date_structure;

	}

}
