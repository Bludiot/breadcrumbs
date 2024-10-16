<?php
/**
 * Functions
 *
 * @package    Breadcrumbs
 * @subpackage Core
 * @category   Functions
 * @since      1.0.0
 */

namespace Breadcrumbs;

// Access namespaced functions.
use function UPRO_Func\{
	user_slug
};
use function UPRO_Tags\{
	user_display_name
};

// Stop if accessed directly.
if ( ! defined( 'BLUDIT' ) ) {
	die( 'You are not allowed direct access to this file.' );
}

/**
 * Plugin object
 *
 * Gets this plugin's core class.
 *
 * @since  1.0.0
 * @return object Returns the class object.
 */
function plugin() {
	return new \Breadcrumbs();
}

/**
 * Site class object
 *
 * Function to use inside other functions and
 * methods rather than calling the global.
 *
 * @since  1.0.0
 * @global object $site Site class
 * @return object
 */
function site() {
	global $site;
	return $site;
}

/**
 * Url class object
 *
 * Function to use inside other functions and
 * methods rather than calling the global.
 *
 * @since  1.0.0
 * @global object $url Url class
 * @return object
 */
function url() {
	global $url;
	return $url;
}

/**
 * Language class object
 *
 * Function to use inside other functions and
 * methods rather than calling the global.
 *
 * @since  1.0.0
 * @global object $L Language class
 * @return object
 */
function lang() {
	global $L;
	return $L;
}

/**
 * Page class object
 *
 * Function to use inside other functions and
 * methods rather than calling the global.
 *
 * @since  1.0.0
 * @global object $page Page class
 * @return object
 */
function page() {
	global $page;
	return $page;
}

/**
 * Website domain
 *
 * Returns the site URL setting or
 * the DOMAIN_BASE constant.
 *
 * @since  1.0.0
 * @return string
 */
function site_domain() {

	if ( site()->url() ) {
		return site()->url();
	}
	return DOMAIN_BASE;
}

/**
 * Is home
 *
 * If the main loop is on the front page.
 *
 * @since  1.0.0
 * @return boolean
 */
function is_home() {

	if ( 'home' == url()->whereAmI() ) {
		return true;
	}
	return false;
}

/**
 * Is category
 *
 * Whether the current page is displaying
 * the category loop.
 *
 * @since  1.0.0
 * @return boolean Returns true if in the main loop.
 */
function is_cat() {

	if ( 'category' == url()->whereAmI() ) {
		return true;
	}
	return false;
}

/**
 * Is tag
 *
 * Whether the current page is displaying
 * the tag loop.
 *
 * @since  1.0.0
 * @return boolean Returns true if in the main loop.
 */
function is_tag() {

	if ( 'tag' == url()->whereAmI() ) {
		return true;
	}
	return false;
}

/**
 * Is main loop
 *
 * Whether the current page is displaying
 * the main posts loop.
 *
 * Excludes category and tag loops.
 *
 * @since  1.0.0
 * @return boolean Returns true if in the main loop.
 */
function is_main_loop() {

	if ( 'blog' == url()->whereAmI() && ! is_cat() && ! is_tag() ) {
		return true;
	}
	return false;
}

/**
 * Is loop page
 *
 * Whether the current page is displaying
 * a posts loop.
 *
 * Excludes search pages.
 *
 * @since  1.0.0
 * @return boolean Returns true if in a loop.
 */
function is_loop_page() {

	$loop_page = false;
	if ( is_main_loop() ) {
		$loop_page = true;
	}
	if ( is_cat() ) {
		$loop_page = true;
	}
	if ( is_tag() ) {
		$loop_page = true;
	}
	return $loop_page;
}

/**
 * Loop not home
 *
 * If the main loop is not the website home.
 *
 * @since  1.0.0
 * @return boolean Returns true if loop slug is set.
 */
function is_loop_not_home() {

	if ( site()->getField( 'uriBlog' ) ) {
		return true;
	}
	return false;
}

/**
 * Is static loop
 *
 * If a static page has the same slug as the loop slug.
 *
 * @since  1.0.0
 * @return boolean Return whether that page exists.
 */
function is_static_loop() {

	if ( static_loop_page() ) {
		return true;
	}
	return false;
}

/**
 * Static loop page
 *
 * @since  1.0.0
 * @return mixed Returns the static loop page object or
 *               returns false if the page doesn't exist.
 */
function static_loop_page() {

	// Get the blog slug setting.
	$loop_field = site()->getField( 'uriBlog' );

	// Remove slashes from field value, if set.
	$loop_key   = str_replace( '/', '', $loop_field );

	// Build a page using blog slug setting.
	$loop_page  = buildPage( $loop_key );

	// Return whether that page exists (could be built).
	if ( $loop_page ) {
		return $loop_page;
	}
	return false;
}

/**
 * Is page
 *
 * If on a page, static or not.
 *
 * @since  1.0.0
 * @return boolean
 */
function is_page() {

	if ( 'page' == url()->whereAmI() ) {
		return true;
	}
	return false;
}

/**
 * Is search
 *
 * If on a search page.
 *
 * No need to check for a search plugin because
 * it is the plugin that defines the location,
 * so the `whereAmI()` function will return false.
 *
 * @since  1.0.0
 * @return boolean
 */
function is_search() {

	if ( 'search' == url()->whereAmI() ) {
		return true;
	}
	return false;
}

/**
 * Is 404
 *
 * @since  1.0.0
 * @return boolean
 */
function is_404() {

	if ( url()->notFound() ) {
		return true;
	}
	return false;
}

/**
 * Is user profile
 *
 * @since  1.0.0
 * @return boolean
 */
function is_profile() {

	$profiles = getPlugin( 'User_Profiles' );
	if ( ! $profiles ) {
		return false;
	}

	$slug = $profiles->users_slug();
	if ( str_contains( '/' . $slug . '/', url()->whereAmI() ) ) {
		return true;
	}
	return false;
}

/**
 * Page type
 *
 * Whether the page object is static or not.
 *
 * @since  1.0.0
 * @return mixed Returns the page type or null.
 */
function page_type() {

	if ( ! is_page() ) {
		return null;
	}

	if ( page()->isStatic() ) {
		return 'page';
	}
	return 'post';
}

/**
 * Is front page
 *
 * If the front page is not the loop.
 *
 * @since  1.0.0
 * @return boolean
 */
function is_front_page() {

	if ( ! is_page() ) {
		return false;
	}

	if ( site()->homepage() && page()->key() == site()->homepage() ) {
		return true;
	}
	return false;
}

/**
 * Get SVG icon
 *
 * @since  1.0.0
 * @param  string $$file Name of the SVG file.
 * @return array
 */
function icon( $filename = '', $wrap = true, $class = '' ) {

	$exists = file_exists( sprintf(
		plugin()->phpPath() . 'assets/images/%s.svg',
		$filename
	) );
	if ( ! empty( $filename ) && $exists ) {

		if ( true == $wrap ) {
			return sprintf(
				'<span class="svg-icon breadcrumbs-icon %s" role="presentation">%s</span>',
				$class,
				file_get_contents( plugin()->phpPath() . "assets/images/{$filename}.svg" )
			);
		} else {
			return file_get_contents( plugin()->phpPath() . "assets/images/{$filename}.svg" );
		}
	}
	return '';
}

/**
 * Breadcrumbs separator
 *
 * Returns the SVG separator icon as per
 * the option in the plugin settings.
 *
 * @since  1.0.0
 * @return string
 */
function separator() {
	return icon( plugin()->separator() );
}

/**
 * Loop label
 *
 * Compatible with the Configure 8 theme
 * options plugin.
 *
 * @since  1.0.0
 * @return string
 */
function loop_label() {

	$label = lang()->get( 'Blog' );
	if ( static_loop_page() ) {
		$label = str_replace( [ '/', '-', '_' ], ' ', static_loop_page()->slug() );
	}

	$cfep = getPlugin( 'configureight' );
	if (
		'configureight' == site()->theme() &&
		$cfep
	) {
		if ( $cfep->main_nav_loop_label() ) {
			$label = $cfep->main_nav_loop_label();
		}
	}
	return ucwords( $label );
}

/**
 * Home crumbs
 *
 * @since  1.0.0
 * @return void
 */
function home_crumbs() {

	return sprintf(
		'<span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a href="%s" itemprop="item">%s</a><meta itemprop="position" content="1" /></span>',
		site_domain(),
		lang()->get( 'Home' )
	);
}

/**
 * Posts loop crumbs
 *
 * @since  1.0.0
 * @return void
 */
function loop_crumbs() {

	$html = sprintf(
		'<span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a href="%s" itemprop="item">%s</a></span><meta itemprop="position" content="2" />',
		site_domain(),
		loop_label()
	);

	if ( is_static_loop() && is_main_loop() ) {
		$html = sprintf(
			'%s %s <span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">%s</span><meta itemprop="position" content="2" /></span>',
			home_crumbs(),
			separator(),
			loop_label()
		);
	} elseif ( is_static_loop() ) {
		$html = sprintf(
			'%s %s <span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a href="%s" itemprop="item">%s</a><meta itemprop="position" content="2" /></span>',
			home_crumbs(),
			separator(),
			static_loop_page()->permalink(),
			loop_label()
		);
	}
	return $html;
}

/**
 * Is paged
 *
 * If the content is divided into navigable pages.
 *
 * @since  1.0.0
 * @return void
 */
function is_paged() {

	if ( \Paginator :: numberOfPages() > 1 ) {
		return true;
	}
	return false;
}

/**
 * Paged number
 *
 * Gets the page number of paged content.
 *
 * @since  1.0.0
 * @return void
 */
function paged_index() {

	$page = false;
	if ( is_paged() ) {
		$page = '1';
		if ( isset( $_GET['page'] ) ) {
			$page = $_GET['page'];
		}
	}
	return $page;
}

/**
 * Post crumbs
 *
 * Non-static breadcrumbs include the loop link.
 *
 * @since  1.0.0
 * @return void
 */
function post_crumbs() {

	if ( ! is_page() ) {
		return false;
	}

	if ( page()->isStatic() ) {
		return false;
	}

	if ( page()->parent() ) {
		$html = child_crumbs();
	} else {
		$html = sprintf(
			'%s %s <span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">%s<meta itemprop="position" content="3" /></span>',
			loop_crumbs(),
			separator(),
			page()->title()
		);
	}
	return $html;
}

/**
 * Page crumbs
 *
 * Static breadcrumbs do not include the loop link.
 *
 * @since  1.0.0
 * @return void
 */
function page_crumbs() {

	if ( ! is_page() ) {
		return false;
	}

	if ( ! page()->isStatic() ) {
		return false;
	}

	if ( page()->parent() ) {
		$html = child_crumbs();
	} else {
		$html = sprintf(
			'%s %s <span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">%s<meta itemprop="position" content="2" /></span>',
			home_crumbs(),
			separator(),
			page()->title()
		);
	}
	return $html;
}

/**
 * Parent page crumbs
 *
 * @since  1.0.0
 * @return mixed
 */
function parent_crumbs() {

	if ( ! is_page() ) {
		return false;
	}

	$html = '';
	if ( page()->parent() && 'page' == page_type() ) {
		$html = sprintf(
			'%s %s <span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a href="%s" itemprop="item">%s</a><meta itemprop="position" content="2" /></span>',
			home_crumbs(),
			separator(),
			page()->parentMethod( 'permalink' ),
			page()->parentMethod( 'title' )
		);
	} elseif ( page()->parent() ) {
		$html = sprintf(
			'%s %s <span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a href="%s" itemprop="item">%s</a><meta itemprop="position" content="3" /></span>',
			loop_crumbs(),
			separator(),
			page()->parentMethod( 'permalink' ),
			page()->parentMethod( 'title' )
		);
	}
	return $html;
}

/**
 * Child page crumbs
 *
 * @since  1.0.0
 * @return mixed
 */
function child_crumbs() {

	if ( ! is_page() ) {
		return false;
	}

	if ( ! page()->isChild() ) {
		return false;
	}

	$html = sprintf(
		'%s %s <span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">%s<meta itemprop="position" content="%s" /></span>',
		parent_crumbs(),
		separator(),
		page()->title(),
		( 'page' == page_type() ? '3' : '4' )
	);
	return $html;
}

/**
 * Category crumbs
 *
 * @since  1.0.0
 * @return mixed
 */
function cat_crumbs() {

	if ( ! is_cat() ) {
		return false;
	}

	$html = '';
	$cat  = new \Category( url()->slug() );
	$slug = str_replace( [ '/', '-', '_' ], ' ', site()->getField( 'uriCategory' ) );

	if ( is_paged() && paged_index() > 1 ) {
		$html = sprintf(
			'%s %s <span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">%s<meta itemprop="position" content="%s" /></span> %s <span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a href="%s" itemprop="item">%s</a><meta itemprop="position" content="%s" /></span> %s <span class="crumb-paged-no" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">%s<meta itemprop="position" content="%s" /></span>',
			loop_crumbs(),
			separator(),
			ucwords( $slug ),
			( is_static_loop() ? '3' : '2' ),
			separator(),
			$cat->permalink(),
			$cat->name(),
			( is_static_loop() ? '4' : '3' ),
			separator(),
			paged_index(),
			( is_static_loop() ? '5' : '4' )
		);
	} else {
		$html = sprintf(
			'%s %s <span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">%s<meta itemprop="position" content="%s" /></span> %s <span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">%s<meta itemprop="position" content="%s" /></span>',
			loop_crumbs(),
			separator(),
			ucwords( $slug ),
			( is_static_loop() ? '3' : '2' ),
			separator(),
			$cat->name(),
			( is_static_loop() ? '4' : '3' )
		);
	}
	return $html;
}

/**
 * Tag crumbs
 *
 * @since  1.0.0
 * @return mixed
 */
function tag_crumbs() {

	if ( ! is_tag() ) {
		return false;
	}

	$html = '';
	$tag  = new \Tag( url()->slug() );
	$slug = str_replace( [ '/', '-', '_' ], ' ', site()->getField( 'uriTag' ) );

	if ( is_paged() && paged_index() > 1 ) {
		$html = sprintf(
			'%s %s <span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">%s<meta itemprop="position" content="%s" /></span> %s <span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a href="%s" itemprop="item">%s</a><meta itemprop="position" content="%s" /></span> %s <span class="crumb-paged-no" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">%s<meta itemprop="position" content="%s" /></span>',
			loop_crumbs(),
			separator(),
			ucwords( $slug ),
			( is_static_loop() ? '3' : '2' ),
			separator(),
			$tag->permalink(),
			$tag->name(),
			( is_static_loop() ? '4' : '3' ),
			separator(),
			paged_index(),
			( is_static_loop() ? '5' : '4' )
		);
	} else {
		$html = sprintf(
			'%s %s <span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">%s<meta itemprop="position" content="%s" /></span> %s <span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">%s<meta itemprop="position" content="%s" /></span>',
			loop_crumbs(),
			separator(),
			ucwords( $slug ),
			( is_static_loop() ? '3' : '2' ),
			separator(),
			$tag->name(),
			( is_static_loop() ? '4' : '3' )
		);
	}
	return $html;
}

/**
 * User profile crumbs
 *
 * Compatible with the User Profiles plugin.
 *
 * @since  1.0.0
 * @return mixed
 */
function user_crumbs() {

	$profiles = getPlugin( 'User_Profiles' );
	if ( ! $profiles ) {
		return false;
	}

	$slug = $profiles->users_slug();
	if ( $slug !== url()->whereAmI() ) {
		return false;
	}
	$html = sprintf(
		'%s %s <span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">%s<meta itemprop="position" content="2" /></span> %s <span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">%s<meta itemprop="position" content="3" /></span>',
		home_crumbs(),
		separator(),
		ucwords( $slug ),
		separator(),
		user_display_name( user_slug() )
	);
	return $html;
}

/**
 * 404 error screen crumbs
 *
 * @since  1.0.0
 * @return mixed
 */
function error_crumbs() {

	if ( ! is_404() ) {
		return false;
	}

	$html = sprintf(
		'%s %s <span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">%s<meta itemprop="position" content="2" /></span>',
		home_crumbs(),
		separator(),
		lang()->get( 'URL Not Found' )
	);
	return $html;
}

/**
 * Search crumbs
 *
 * Compatible with the Search Forms plugin.
 *
 * @since  1.0.0
 * @return mixed
 */
function search_crumbs() {

	$search = getPlugin( 'Search_Forms' );
	if ( ! $search ) {
		return false;
	}

	$slug  = url()->slug();
	$terms = '';
	if ( str_contains( $slug, 'search/' ) ) {
		$terms = str_replace( 'search/', '', $slug );
		$terms = str_replace( '+', ' ', $terms );
	}

	$html = sprintf(
		'%s %s <span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">%s<meta itemprop="position" content="2" /></span> %s <span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">%s<meta itemprop="position" content="3" /></span>',
		home_crumbs(),
		separator(),
		lang()->get( 'Search' ),
		separator(),
		$terms
	);
	return $html;
}

/**
 * Breadcrumbs navigation
 *
 * @since  1.0.0
 * @return mixed
 */
function trail() {

	if ( is_home() || is_front_page() ) {
		return false;
	}

	$html = '<nav class="breadcrumbs-nav" itemscope itemtype="https://schema.org/BreadcrumbList">';

	if ( is_404() ) {
		$html .= error_crumbs();
	} elseif ( is_main_loop() ) {
		$html .= loop_crumbs();
	} elseif ( is_cat() ) {
		$html .= cat_crumbs();
	} elseif ( is_tag() ) {
		$html .= tag_crumbs();
	} elseif ( is_profile() ) {
		$html .= user_crumbs();
	} elseif ( is_page() && 'page' == page_type() ) {
		$html .= page_crumbs();
	} elseif ( is_page() ) {
		$html .= post_crumbs();
	} elseif ( is_search() ) {
		$html .= search_crumbs();
	}
	$html .= '</nav}>';

	return $html;
}
