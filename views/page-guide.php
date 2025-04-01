<?php
/**
 * Breadcrumbs guide
 *
 * @package    Breadcrumbs
 * @subpackage Views
 * @category   Guides
 * @since      1.0.0
 */

// Access namespaced functions.
use function Breadcrumbs\{
	site,
	lang,
	crumbs_list,
	themes_compat
};

// Form page URL.
$form_page = DOMAIN_ADMIN . 'configure-plugin/Breadcrumbs';

// Plugin hooks by version.
if ( function_exists( 'execPluginsByHook' ) ) {
	$hook = [
		'plugin' => "execPluginsByHook( 'breadcrumbs' );",
		'begin'  => "execPluginsByHook( 'pageBegin' );",
		'end'    => "execPluginsByHook( 'pageEnd' );"
	];
} else {
	$hook = [
		'plugin' => "Theme::plugins( 'breadcrumbs' );",
		'begin'  => "Theme::plugins( 'pageBegin' );",
		'end'    => "Theme::plugins( 'pageEnd' );"
	];
}

// Function for templates.
$trail = 'Breadcrumbs\\trail( &#36;separator );';

?>
<h1><span class="page-title-icon fa fa-book"></span> <span class="page-title-text"><?php $L->p( 'Breadcrumbs Guide' ) ?></span></h1>

<div class="alert alert-primary alert-cats-list" role="alert">
	<p class="m-0"><?php $L->p( "Go to the <a href='{$form_page}'>breadcrumbs options</a> page." ); ?></p>
</div>

<p><?php $L->p( 'The Breadcrumbs plugin adds SEO friendly breadcrumb style navigation links to singular and loop pages, search pages, error pages, and if the User Profiles plugin is active, profile pages.' ); ?></p>

<p><?php $L->p( 'The plugin also includes an optional sidebar widget to display the breadcrumbs, provided the active theme has the default Bludit sidebar.' ); ?></p>

<h2 class="form-heading "><?php $L->p( 'Template Locations' ) ?></h2>

<p><?php $L->p( "The breadcrumbs navigation may be added automatically via theme template hook. Options include one custom hook provided by the Breadcrumbs plugin is <code class='select'>{$hook['plugin']}</code> and must be added to the active theme where you want the breadcrumbs to appear. Two other options are hooks provided by Bludit, <code class='select'>{$hook['begin']}</code> and <code class='select'>{$hook['end']}</code>, however there is no guarantee that the active theme will call these hooks." ); ?></p>

<?php
if ( in_array( site()->theme(), themes_compat() ) ) {
	printf(
		'<p>%s</p>',
		lang()->get( 'The active theme is compatible with the Breadcrumbs plugin.' )
	);
}
?>

<h2 class="form-heading "><?php $L->p( 'Template Tags' ) ?></h2>

<p><?php $L->p( "As long as the Breadcrumbs plugin is active, various template tag functions are available to use in custom page templates. In case the plugin is deactivated it is best to wrap the template tags in <code>if ( getPlugin( 'Breadcrumbs' ) { ... }</code> to prevent your website breaking." ); ?></p>

<p><?php $L->p( "Following is a list of template tags available. All tags must be echoed to print the result. Also, all tags accept a string parameter for custom separators (e.g. <code>trail( '&raquo;' );</code> )." ); ?></p>

<ul>
<?php
foreach ( crumbs_list() as $function => $location ) {
	printf(
		'<li><code class="select">Breadcrumbs\\%s();</code> %s %s</li>',
		$function,
		lang()->get( 'for' ),
		$location
	);
}
?>
</ul>
