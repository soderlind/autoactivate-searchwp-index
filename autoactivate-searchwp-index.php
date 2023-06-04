<?php
/**
 * Autoactivate SearchWP Default Index
 *
 * @package     Namespace
 * @author      Per Soderlind
 * @copyright   2021 Per Soderlind
 * @license     GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name: Autoactivate SearchWP Default Index
 * Plugin URI:  https://github.com/soderlind/autoactivate-searchwp-index
 * GitHub Plugin URI: https://github.com/soderlind/autoactivate-searchwp-index
 * Description: Will autoactivate SearchWP default index when the SearchWP plugin is activated.
 * Version:     1.0.0
 * Author:      Per Soderlind
 * Author URI:  https://soderlind.no
 * Text Domain: autoactivate-searchwp-index
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

declare( strict_types = 1 );
namespace Soderlind\Plugin\AutoactivateSearchWPIndex;

if ( ! defined( 'ABSPATH' ) ) {
	wp_die();
}

\add_action( 'plugins_loaded',__NAMESPACE__ . '\\action_plugins_loaded' );

/**
 * Fires once activated plugins have loaded.
 *
 */
function action_plugins_loaded() : void {
	\add_action( 'admin_init', __NAMESPACE__ . '\\searchwp_activate', 9 );
	\add_action( 'deactivated_plugin', __NAMESPACE__ . '\\searchwp_deactivate', 10, 1 );
}

/**
 * Programmatically activate SearchWP.
 *
 * @return void
 */
function searchwp_activate() : void {
	// Activate the license.
	if ( false === \get_option( 'soderlind_searchwp_license_activated', false ) && \defined( 'SEARCHWP_LICENSE_KEY' ) && \class_exists( '\SearchWP\License' ) ) {

		// Don't redirect to the welcome page.
		\update_option( 'searchwp_new_activation', false );

		activate_license();
		activate_default_search_engine();

		\update_option( 'soderlind_searchwp_license_activated', true );
	}
}

/**
 * On SearchWP plugin deactivation, nuke SearchWP from the site.
 *
 * @param string $plugin Path to the plugin file relative to the plugins directory.
 */
function searchwp_deactivate( string $plugin ) {
	if ( 'searchwp/searchwp.php' === $plugin || 'searchwp/index.php' === $plugin ) {
		\delete_option( 'soderlind_searchwp_license_activated' );
	}
}

/**
 * On SearchWP plugin activation, activate the license.
 */
function activate_license() {
	$license = new \SearchWP\License(); // phpcs:ignore
	$license->activate( \SEARCHWP_LICENSE_KEY );
}

/**
 * Activate( i.e. create and save ) the default index.
 * 
 * This function emulates the "Save" button on the SearchWP Engines tab.
 * 
 * @return void
 */
function activate_default_search_engine() : void {

	$index    = \SearchWP::$index;
	$original = \SearchWP\Settings::_get_engines_settings();
	if ( empty( $original ) ) {
		$index->drop_site( get_current_blog_id() );
	}
	$doing_import    = false;
	$default_configs = '{"default":{"name":"default","label":"Default","settings":{"stemming":true,"adminengine":false},"sources":{"post.post":{"name":"post.post","labels":{"plural":"Posts","singular":"Post"},"attributes":{"title":{"name":"title","label":"Title","notes":[],"tooltip":"","settings":300,"default":300,"options":false,"allow_custom":false,"special":[],"get_options":false},"content":{"name":"content","label":"Content","notes":[],"tooltip":"","settings":1,"default":1,"options":false,"allow_custom":false,"special":[],"get_options":false},"slug":{"name":"slug","label":"Slug","notes":[],"tooltip":"","settings":300,"default":300,"options":false,"allow_custom":false,"special":[],"get_options":false},"excerpt":{"name":"excerpt","label":"Excerpt","notes":[],"tooltip":"","settings":1,"default":1,"options":false,"allow_custom":false,"special":[],"get_options":false},"meta":{"name":"meta","label":"Custom+Fields","notes":["Tip:+Match+multiple+keys+using+*+as+wildcard+and+hitting+Enter"],"tooltip":"","settings":null,"default":1,"options":[],"allow_custom":true,"special":[{"label":"Any+Meta+Key","value":"*","icon":"dashicons+dashicons-star-filled"}],"get_options":"searchwp_post_post_attribute_meta_options"},"taxonomy":{"name":"taxonomy","label":"Taxonomies","notes":[],"tooltip":"","settings":null,"default":300,"options":[],"allow_custom":false,"special":[{"label":"Categories+(category)","value":"category","icon":""},{"label":"Tags+(post_tag)","value":"post_tag","icon":""},{"label":"Formats+(post_format)","value":"post_format","icon":""}],"get_options":"searchwp_post_post_attribute_taxonomy_options"}},"rules":{"taxonomy":{"name":"taxonomy","label":"Taxonomy","notes":[],"tooltip":"","options":[{"label":"Categories+(category)","value":"category","icon":""},{"label":"Tags+(post_tag)","value":"post_tag","icon":""},{"label":"Formats+(post_format)","value":"post_format","icon":""}],"conditions":["IN","NOT+IN"],"values":[],"get_values":"searchwp_post_post_rule_taxonomy_option_values","settings":[]},"published":{"name":"published","label":"Publish+Date","notes":[],"tooltip":"Any+strtotime()-compatible+string+e.g.+\"6+months+ago\"","options":false,"conditions":["<",">"],"values":false,"get_values":false,"settings":[]},"post_id":{"name":"post_id","label":"ID","notes":[],"tooltip":"","options":false,"conditions":["IN","NOT+IN"],"values":false,"get_values":false,"settings":[]}},"ruleGroups":[],"options":[{"name":"weight_transfer","label":"Transfer+Weight","tooltip":"Transfer+the+weight+of+the+search+result+to+the+parent+entry+(if+applicable)","options":[{"label":"To+Post+ID","value":"id","icon":""}],"option":"id","value":"","enabled":false}],"notices":[]},"post.page":{"name":"post.page","labels":{"plural":"Pages","singular":"Page"},"attributes":{"title":{"name":"title","label":"Title","notes":[],"tooltip":"","settings":300,"default":300,"options":false,"allow_custom":false,"special":[],"get_options":false},"content":{"name":"content","label":"Content","notes":[],"tooltip":"","settings":1,"default":1,"options":false,"allow_custom":false,"special":[],"get_options":false},"slug":{"name":"slug","label":"Slug","notes":[],"tooltip":"","settings":300,"default":300,"options":false,"allow_custom":false,"special":[],"get_options":false},"excerpt":{"name":"excerpt","label":"Excerpt","notes":[],"tooltip":"","settings":1,"default":1,"options":false,"allow_custom":false,"special":[],"get_options":false},"meta":{"name":"meta","label":"Custom+Fields","notes":["Tip:+Match+multiple+keys+using+*+as+wildcard+and+hitting+Enter"],"tooltip":"","settings":null,"default":1,"options":[],"allow_custom":true,"special":[{"label":"Any+Meta+Key","value":"*","icon":"dashicons+dashicons-star-filled"}],"get_options":"searchwp_post_page_attribute_meta_options"},"taxonomy":{"name":"taxonomy","label":"Taxonomies","notes":[],"tooltip":"","settings":null,"default":300,"options":[],"allow_custom":false,"special":[],"get_options":"searchwp_post_page_attribute_taxonomy_options"}},"rules":{"published":{"name":"published","label":"Publish+Date","notes":[],"tooltip":"Any+strtotime()-compatible+string+e.g.+\"6+months+ago\"","options":false,"conditions":["<",">"],"values":false,"get_values":false,"settings":[]},"post_id":{"name":"post_id","label":"ID","notes":[],"tooltip":"","options":false,"conditions":["IN","NOT+IN"],"values":false,"get_values":false,"settings":[]},"ancestor":{"name":"ancestor","label":"Ancestor+ID","notes":[],"tooltip":"Ancestor+and+all+descendants+will+apply+to+this+Rule,+comma+separate+multiple+ancestors","options":false,"conditions":["IN","NOT+IN"],"values":false,"get_values":false,"settings":[]},"post_parent":{"name":"post_parent","label":"Post+Parent+ID","notes":[],"tooltip":"Applies+only+to+children,+add+another+Rule+to+consider+Post+Parent+itself+if+necessary","options":false,"conditions":["IN","NOT+IN"],"values":false,"get_values":false,"settings":[]}},"ruleGroups":[],"options":[{"name":"weight_transfer","label":"Transfer+Weight","tooltip":"Transfer+the+weight+of+the+search+result+to+the+parent+entry+(if+applicable)","options":[{"label":"To+Page+ID","value":"id","icon":""},{"label":"To+Page+Parent","value":"col","icon":""}],"option":"id","value":"","enabled":false}],"notices":[]},"post.attachment":{"name":"post.attachment","labels":{"plural":"Media","singular":"Media"},"attributes":{"document_content":{"name":"document_content","label":"Document+Content","notes":[],"tooltip":"","settings":1,"default":1,"options":false,"allow_custom":false,"special":[],"get_options":false},"pdf_metadata":{"name":"pdf_metadata","label":"PDF+Metadata","notes":[],"tooltip":"","settings":null,"default":false,"options":false,"allow_custom":false,"special":[],"get_options":false},"image_exif":{"name":"image_exif","label":"Image+EXIF","notes":[],"tooltip":"","settings":null,"default":false,"options":false,"allow_custom":false,"special":[],"get_options":false},"title":{"name":"title","label":"Title","notes":[],"tooltip":"","settings":300,"default":300,"options":false,"allow_custom":false,"special":[],"get_options":false},"content":{"name":"content","label":"Content","notes":[],"tooltip":"","settings":1,"default":1,"options":false,"allow_custom":false,"special":[],"get_options":false},"slug":{"name":"slug","label":"Slug","notes":[],"tooltip":"","settings":300,"default":300,"options":false,"allow_custom":false,"special":[],"get_options":false},"excerpt":{"name":"excerpt","label":"Excerpt","notes":[],"tooltip":"","settings":1,"default":1,"options":false,"allow_custom":false,"special":[],"get_options":false},"meta":{"name":"meta","label":"Custom+Fields","notes":["Tip:+Match+multiple+keys+using+*+as+wildcard+and+hitting+Enter"],"tooltip":"","settings":null,"default":1,"options":[],"allow_custom":true,"special":[{"label":"Any+Meta+Key","value":"*","icon":"dashicons+dashicons-star-filled"}],"get_options":"searchwp_post_attachment_attribute_meta_options"},"taxonomy":{"name":"taxonomy","label":"Taxonomies","notes":[],"tooltip":"","settings":null,"default":300,"options":[],"allow_custom":false,"special":[],"get_options":"searchwp_post_attachment_attribute_taxonomy_options"}},"rules":{"filetype":{"name":"filetype","label":"File+Type","notes":[],"tooltip":"","options":false,"conditions":["IN","NOT+IN"],"values":[{"label":"All+Documents","value":"documents","icon":""},{"label":"PDFs","value":"pdf","icon":""},{"label":"Plain+Text","value":"text","icon":""},{"label":"Images","value":"image","icon":""},{"label":"Videos","value":"video","icon":""},{"label":"Audio","value":"audio","icon":""},{"label":"Office+Documents","value":"office","icon":""},{"label":"OpenOffice+Documents","value":"openoffice","icon":""},{"label":"iWork+Documents","value":"iwork","icon":""}],"get_values":false,"settings":[]},"filename":{"name":"filename","label":"Filename","notes":[],"tooltip":"Rule+will+apply+if+ANY+part+of+the+filename+matches+(includes+upload+path,+excluding+upload+base,+and+is+case-insensitive)","options":false,"conditions":["LIKE","NOT+LIKE"],"values":false,"get_values":false,"settings":[]},"published":{"name":"published","label":"Publish+Date","notes":[],"tooltip":"Any+strtotime()-compatible+string+e.g.+\"6+months+ago\"","options":false,"conditions":["<",">"],"values":false,"get_values":false,"settings":[]},"post_id":{"name":"post_id","label":"ID","notes":[],"tooltip":"","options":false,"conditions":["IN","NOT+IN"],"values":false,"get_values":false,"settings":[]}},"ruleGroups":[],"options":[{"name":"weight_transfer","label":"Transfer+Weight","tooltip":"Transfer+the+weight+of+the+search+result+to+the+parent+entry+(if+applicable)","options":[{"label":"To+Media+ID","value":"id","icon":""},{"label":"To+Media+Parent","value":"col","icon":""}],"option":"id","value":"","enabled":false}],"notices":[]}}}}';

	$configs = json_decode( $default_configs, true );
	$engines = call_user_func_array(
		'array_merge',
		array_map(
			function( $name, $config ) use ( $doing_import ) {
				// Build an Engine model.
				$engine = new \SearchWP\Engine( $name, ! $doing_import ? \SearchWP\Utils::normalize_engine_config( $config ) : $config );

				// Extract a (validated) config from the model.
				$config = \SearchWP\Utils::normalize_engine_config( json_decode( \wp_json_encode( $engine ), true ) );

				unset( $config['name'] );

				return [ $name => $config ];
			},
			array_keys( $configs ),
			array_values( $configs )
		)
	);

	$outdated = \SearchWP\Admin\Views\EnginesView::apply_new_engines_config( $original, $engines );
	\SearchWP::$indexer->trigger();
}
