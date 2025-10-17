<?php
/**
 * Integrate ACF color picker fields with the theme's color palette.
 *
 * @package cb-identity2025
 */

// Field name: bg_colour.
add_filter(
	'acf/load_field/name=bg_colour',
	function ( $field ) {
		// Prefer the theme palette; fall back to default/global if needed.
		$palette = wp_get_global_settings( array( 'color', 'palette', 'theme' ) );
		if ( empty( $palette ) ) {
			$palette = wp_get_global_settings( array( 'color', 'palette', 'default' ) );
		}

		$field['choices']    = array();
		$field['allow_null'] = 1;

		// Manually add white and black since WordPress filters them out.
		$field['choices']['white']           = 'White';
		$field['choice_attributes']['white'] = array( 'data-color' => '#fff' );
		$field['choices']['black']           = 'Black';
		$field['choice_attributes']['black'] = array( 'data-color' => '#000' );

		foreach ( (array) $palette as $c ) {
			if ( empty( $c['slug'] ) || empty( $c['name'] ) || empty( $c['color'] ) ) {
				continue;
			}
			$field['choices'][ $c['slug'] ] = $c['name'];
			if (!isset($field['choice_attributes'])) {
				$field['choice_attributes'] = array();
			}
			$field['choice_attributes'][ $c['slug'] ] = array(
				'data-color' => $c['color'],
			);
		}

		return $field;
	}
);

// Field name: fg_colour.
add_filter(
	'acf/load_field/name=fg_colour',
	function ( $field ) {
		// Prefer the theme palette; fall back to default/global if needed.
		$palette = wp_get_global_settings( array( 'color', 'palette', 'theme' ) );
		if ( empty( $palette ) ) {
			$palette = wp_get_global_settings( array( 'color', 'palette', 'default' ) );
		}

		$field['choices']    = array();
		$field['allow_null'] = 1;

		// Manually add white and black since WordPress filters them out.
		$field['choices']['white']           = 'White';
		$field['choice_attributes']['white'] = array( 'data-color' => '#fff' );
		$field['choices']['black']           = 'Black';
		$field['choice_attributes']['black'] = array( 'data-color' => '#000' );

		foreach ( (array) $palette as $c ) {
			if ( empty( $c['slug'] ) || empty( $c['name'] ) ) {
				continue;
			}
			$field['choices'][ $c['slug'] ] = $c['name'];
			$field['choice_attributes'][ $c['slug'] ] = array(
    		    'data-color' => $c['color'],
    		);
		}
		return $field;
	}
);
