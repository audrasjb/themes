<?php
/**
 * Child Theme Functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage Exford
 * @since 1.0.0
 */

if ( ! function_exists( 'exford_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function exford_setup() {

		// Add child theme editor styles, compiled from `style-child-theme-editor.scss`.
		add_editor_style( 'style-editor.css' );

		// Add child theme editor font sizes to match Sass-map variables in `_config-child-theme-deep.scss`.
		add_theme_support(
			'editor-font-sizes',
			array(
				array(
					'name'      => __( 'Small', 'exford' ),
					'shortName' => __( 'S', 'exford' ),
					'size'      => 16.6,
					'slug'      => 'small',
				),
				array(
					'name'      => __( 'Normal', 'exford' ),
					'shortName' => __( 'M', 'exford' ),
					'size'      => 20,
					'slug'      => 'normal',
				),
				array(
					'name'      => __( 'Large', 'exford' ),
					'shortName' => __( 'L', 'exford' ),
					'size'      => 28.8,
					'slug'      => 'large',
				),
				array(
					'name'      => __( 'Huge', 'exford' ),
					'shortName' => __( 'XL', 'exford' ),
					'size'      => 34.56,
					'slug'      => 'huge',
				),
			)
		);

		/*
		 * Get customizer colors and add them to the editor color palettes
		 *
		 * - if the customizer color is empty, use the default
		 */
		$colors_array = get_theme_mod('colors_manager'); // color annotations array()
		$background   = $colors_array['colors']['bg'];   // $config-global--color-background-default;
		$primary      = $colors_array['colors']['link']; // $config-global--color-primary-default;
		$foreground   = $colors_array['colors']['txt'];  // $config-global--color-foreground-default;
		$secondary    = $colors_array['colors']['fg1'];  // $config-global--color-secondary-default;

		// Editor color palette.
		add_theme_support(
			'editor-color-palette',
			array(
				array(
					'name'  => __( 'Primary', 'exford' ),
					'slug'  => 'primary',
					'color' => ! isset($primary) ? '#23883D' : $primary,
				),
				array(
					'name'  => __( 'Secondary', 'exford' ),
					'slug'  => 'secondary',
					'color' => ! isset($secondary) ? '#0963C4' : $secondary,
				),
				array(
					'name'  => __( 'Foreground', 'exford' ),
					'slug'  => 'foreground',
					'color' => ! isset($foreground) ? '#111111' : $foreground,
				),
				array(
					'name'  => __( 'Background', 'exford' ),
					'slug'  => 'background',
					'color' => ! isset($background) ? '#FFFFFF' : $background,
				),
			)
		);

		// Enable Full Site Editing
		add_theme_support( 'full-site-editing' );
	}
endif;
add_action( 'after_setup_theme', 'exford_setup', 12 );

/**
 * Filter the content_width in pixels, based on the child-theme's design and stylesheet.
 */
function exford_content_width() {
	return 750;
}
add_filter( 'varia_content_width', 'exford_content_width' );

/**
 * Add Google webfonts, if necessary
 *
 * - See: http://themeshaper.com/2014/08/13/how-to-add-google-fonts-to-wordpress-themes/
 */
function exford_fonts_url() {

	$fonts_url = '';

	/* Translators: If there are characters in your language that are not
	* supported by Source Serif Pro, translate this to 'off'. Do not translate
	* into your own language.
	*/
	$source_serif_pro = esc_html_x( 'on', 'Source Serif Pro font: on or off', 'exford' );

	/* Translators: If there are characters in your language that are not
	* supported by Source Sans Pro, translate this to 'off'. Do not translate
	* into your own language.
	*/
	$source_sans_pro = esc_html_x( 'on', 'Source Sans Pro font: on or off', 'exford' );

	if ( 'off' !== $source_serif_pro || 'off' !== $source_sans_pro ) {
		$font_families = array();

		if ( 'off' !== $source_serif_pro ) {
			$font_families[] = 'Source Serif Pro:400,700,400i,700i';
		}

		if ( 'off' !== $source_sans_pro ) {
			$font_families[] = 'Source Sans Pro:400,700,400i,700i';
		}

		$query_args = array(
			'family' => urlencode( implode( '|', $font_families ) ),
			'subset' => urlencode( 'latin,latin-ext' ),
		);

		$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
	}

	return esc_url_raw( $fonts_url );
}

/**
 * Enqueue scripts and styles.
 */
function exford_scripts() {

	// enqueue Google fonts, if necessary
	wp_enqueue_style( 'exford-fonts', exford_fonts_url(), array(), null );

	// dequeue parent styles
	wp_dequeue_style( 'varia-style' );

	// enqueue child styles
	wp_enqueue_style('exford-style', get_stylesheet_uri(), array(), wp_get_theme()->get( 'Version' ));

	// enqueue child RTL styles
	wp_style_add_data( 'exford-style', 'rtl', 'replace' );

}
add_action( 'wp_enqueue_scripts', 'exford_scripts', 99 );

/**
 * Enqueue theme styles for the block editor.
 */
function exford_editor_styles() {

	// Enqueue Google fonts in the editor, if necessary
	wp_enqueue_style( 'exford-editor-fonts', exford_fonts_url(), array(), null );
}
add_action( 'enqueue_block_editor_assets', 'exford_editor_styles' );
