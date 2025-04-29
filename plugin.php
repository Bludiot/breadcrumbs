<?php
/**
 * Breadcrumbs
 *
 * Plugin core class, do not namespace.
 *
 * @package    Breadcrumbs
 * @subpackage Core
 * @since      1.0.0
 */

// Stop if accessed directly.
if ( ! defined( 'BLUDIT' ) ) {
	die( 'You are not allowed direct access to this file.' );
}

// Access namespaced functions.
use function Breadcrumbs\{
	trail,
	sidebar
};

class Breadcrumbs extends Plugin {

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {

		// Run parent constructor.
		parent :: __construct();

		// Include functionality.
		if ( $this->installed() ) {
			$this->get_files();
		}
	}

	/**
	 * Prepare plugin for installation
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function prepare() {
		$this->get_files();
	}

	/**
	 * Include functionality
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function get_files() {

		// Plugin path.
		$path = $this->phpPath();

		// Get plugin functions.
		foreach ( glob( $path . 'includes/*.php' ) as $filename ) {
			require_once $filename;
		}
	}

	/**
	 * Initiate plugin
	 *
	 * @since  1.0.0
	 * @access public
	 * @global object $L Language class.
	 * @return void
	 */
	public function init() {

		$this->dbFields = [
			'theme_hook' => 'breadcrumbs',
			'separator'  => 'angle',
			'widget'     => false,
			'label'      => '',
			'label_wrap' => 'h2'
		];

		// Array of custom hooks.
		$this->customHooks = [
			'breadcrumbs'
		];

		if ( ! $this->installed() ) {
			$Tmp = new dbJSON( $this->filenameDb );
			$this->db = $Tmp->db;
			$this->prepare();
		}
	}

	/**
	 * Admin settings form
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string Returns the markup of the form.
	 */
	public function form() {

		$html  = '';
		ob_start();
		include( $this->phpPath() . '/views/page-form.php' );
		$html .= ob_get_clean();

		return $html;
	}

	/**
	 * Admin controller
	 *
	 * Change the text of the `<title>` tag.
	 *
	 * @since  1.0.0
	 * @access public
	 * @global object $L The Language class.
	 * @global array $layout
	 * @return string Returns the head content.
	 */
	public function adminController() {
		global $L, $layout, $site;
		$layout['title'] = $L->get( 'Breadcrumbs Guide' ) . ' | ' . $site->title();
	}

	/**
	 * Admin info pages
	 *
	 * @since  1.0.0
	 * @access public
	 * @global object $L Language class.
	 * @global object $site Site class.
	 * @return string Returns the markup of the page.
	 */
	public function adminView() {

		// Access global variables.
		global $L, $site;

		$html  = '';
		ob_start();
		include( $this->phpPath() . '/views/page-guide.php' );
		$html .= ob_get_clean();

		return $html;
	}

	/**
	 * Head section
	 *
	 * @since  1.0.0
	 * @access public
	 * @global object $url Url class.
	 * @return string Returns the head content.
	 */
	public function siteHead() {

		// Access global variables.
		global $url;

		// Maybe get non-minified assets.
		$suffix = '.min';
		if ( defined( 'DEBUG_MODE' ) && DEBUG_MODE ) {
			$suffix = '';
		}
		$assets = '';
		$assets .= '<link rel="stylesheet" type="text/css" href="' . $this->domainPath() . "assets/css/frontend{$suffix}.css?version=" . $this->getMetadata( 'version' ) . '" />' . PHP_EOL;

		return $assets;
	}

	/**
	 * Sidebar list
	 *
	 * @since  1.0.0
	 * @access public
	 * @global object $L Language class.
	 * @global object $site Site class.
	 * @return mixed
	 */
	public function siteSidebar() {

		if ( $this->widget() ) {
			return sidebar();
		}
		return false;
	}

	/**
	 * Breadcrumbs hook
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string
	 */
	public function breadcrumbs() {
		if ( 'breadcrumbs' == $this->theme_hook() ) {
			return trail();
		}
	}

	/**
	 * Page begin
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string
	 */
	public function pageBegin() {
		if ( 'pageBegin' == $this->theme_hook() ) {
			return trail();
		}
	}

	/**
	 * Page end
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string
	 */
	public function pageEnd() {
		if ( 'pageEnd' == $this->theme_hook() ) {
			return trail();
		}
	}

	/**
	 * Option return functions
	 *
	 * @since  1.0.0
	 * @access public
	 */

	// @return string
	public function theme_hook() {
		return $this->getValue( 'theme_hook' );
	}

	// @return string
	public function separator() {
		return $this->getValue( 'separator' );
	}

	// @return boolean
	public function widget() {
		return $this->getValue( 'widget' );
	}

	// @return string
	public function label() {
		return $this->getValue( 'label' );
	}

	// @return string
	public function label_wrap() {
		return $this->getValue( 'label_wrap' );
	}
}
