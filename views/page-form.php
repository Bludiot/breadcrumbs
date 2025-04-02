<?php
/**
 * Breadcrumbs options
 *
 * @package    Breadcrumbs
 * @subpackage Views
 * @category   Forms
 * @since      1.0.0
 */

// Access namespaced functions.
use function Breadcrumbs\{
	plugin,
	lang,
	trail
};

// Guide page URL.
$guide_page = DOMAIN_ADMIN . 'plugin/Breadcrumbs';

?>
<style>
.form-control-has-button {
	display: flex;
	align-items: center;
	flex-wrap: nowrap;
	gap: 0.25em;
	width: 100%;
	margin: 0;
	padding: 0;
}
.screen-reader-text {
	border: 0;
	clip: rect( 1px, 1px, 1px, 1px );
	-webkit-clip-path: inset(50%);
	        clip-path: inset(50%);
	height: 1px;
	margin: -1px;
	overflow: hidden;
	padding: 0;
	position: absolute !important;
	width: 1px;
	word-wrap: normal !important;
}
</style>
<div class="alert alert-primary alert-cats-list" role="alert">
	<p class="m-0"><?php lang()->p( "Go to the <a href='{$guide_page}'>breadcrumbs guide</a> page." ); ?></p>
</div>

<fieldset class="mt-4">
	<legend class="screen-reader-text mb-3"><?php lang()->p( 'Breadcrumbs Options' ) ?></legend>

		<div class="form-field form-group row">
			<label class="form-label col-sm-2 col-form-label" for="theme_hook"><?php lang()->p( 'Theme Hook' ); ?></label>
			<div class="col-sm-10">
				<select id="theme_hook" class="form-select" name="theme_hook">

					<option value="breadcrumbs" <?php echo ( $this->theme_hook() === 'breadcrumbs' ? 'selected' : '' ); ?>>breadcrumbs</option>

					<option value="pageBegin" <?php echo ( $this->theme_hook() === 'pageBegin' ? 'selected' : '' ); ?>>pageBegin</option>

					<option value="pageEnd" <?php echo ( $this->theme_hook() === 'pageEnd' ? 'selected' : '' ); ?>>pageEnd</option>

					<option value="none" <?php echo ( $this->theme_hook() === 'none' ? 'selected' : '' ); ?>><?php lang()->p( 'None' ); ?></option>
				</select>
				<small class="form-text"><?php lang()->p( 'The theme hook in which to display the breadcrumbs (see guide for manual display).' ); ?></small>
			</div>
		</div>

		<div class="form-field form-group row">
			<label class="form-label col-sm-2 col-form-label" for="separator"><?php lang()->p( 'Links Separator' ); ?></label>
			<div class="col-sm-10">
				<select id="separator" class="form-select" name="separator">

					<option value="angle" <?php echo ( $this->separator() === 'angle' ? 'selected' : '' ); ?>><?php lang()->p( 'Angle' ); ?></option>

					<option value="angles" <?php echo ( $this->separator() === 'angles' ? 'selected' : '' ); ?>><?php lang()->p( 'Double Angle' ); ?></option>

					<option value="arrow" <?php echo ( $this->separator() === 'arrow' ? 'selected' : '' ); ?>><?php lang()->p( 'Arrow' ); ?></option>
				</select>
				<small class="form-text"><?php lang()->p( 'The icon to display between breadcrumb links.' ); ?></small>
			</div>
		</div>

		<div class="form-field form-group row">
			<label class="form-label col-sm-2 col-form-label" for="widget"><?php lang()->p( 'Sidebar Widget' ); ?></label>
			<div class="col-sm-10">
				<select id="widget" class="form-select" name="widget">

					<option value="true" <?php echo ( $this->widget() === true ? 'selected' : '' ); ?>><?php lang()->p( 'Enabled' ); ?></option>

					<option value="false" <?php echo ( $this->widget() === false ? 'selected' : '' ); ?>><?php lang()->p( 'Disabled' ); ?></option>
				</select>
				<small class="form-text"><?php lang()->p( 'Display breadcrumb links in the site sidebar, if the theme supports it.' ); ?></small>
			</div>
		</div>

		<div id="widget-options" style="display: <?php echo ( $this->widget() == true ? 'block' : 'none' ); ?>;">

			<div class="form-field form-group row">
				<label class="form-label col-sm-2 col-form-label" for="label"><?php lang()->p( 'Widget Label' ); ?></label>
				<div class="col-sm-10">
					<input type="text" id="label" name="label" value="<?php echo $this->label(); ?>" placeholder="<?php echo $this->dbFields['label']; ?>" />
					<small class="form-text"><?php lang()->p( 'The text for the widget label. Leave blank for no label.' ); ?></small>
				</div>
			</div>

			<div class="form-field form-group row">
				<label class="form-label col-sm-2 col-form-label" for="label_wrap"><?php lang()->p( 'Label Wrap' ); ?></label>
				<div class="col-sm-10">
					<div class="form-control-has-button">
						<input type="text" id="label_wrap" name="label_wrap" value="<?php echo $this->getValue( 'label_wrap' ); ?>" placeholder="<?php lang()->p( 'h2' ); ?>" />
						<span class="btn btn-secondary btn-md button hide-if-no-js" onClick="$('#label_wrap').val('<?php echo $this->dbFields['label_wrap']; ?>');"><?php lang()->p( 'Default' ); ?></span>
					</div>
					<small class="form-text text-muted"><?php lang()->p( 'Wrap the label in an element, such as a heading. Accepts HTML tags without brackets (e.g. h3), and comma-separated tags (e.g. span,strong,em). Save as blank for no wrapping element.' ); ?></small>
				</div>
			</div>
		</div>

</fieldset>

<script>
jQuery(document).ready( function($) {
	$( '#widget' ).on( 'change', function() {
    	var show = $(this).val();
		if ( show == 'true' ) {
			$( "#widget-options" ).fadeIn( 250 );
		} else if ( show == 'false' ) {
			$( "#widget-options" ).fadeOut( 250 );
		}
    });
});
</script>
