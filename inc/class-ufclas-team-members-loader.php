<?php
if ( ! class_exists( 'Gamajo_Template_Loader' ) ) {
	  require UFCLAS_TEAMMEMBER_PLUGIN_DIR . 'inc/class-gamajo-template-loader.php';
}
/**
 * Class to load plugin templates.
 *
 * Define properties
 * @package UFCLAS_Team_Member
 */
 
class UFCLAS_TEAMMEMBER_Template_Loader extends Gamajo_Template_Loader {
	/**
	 * Prefix for filter names.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	protected $filter_prefix = 'clas_team_members';

	/**
	 * Directory name where custom templates for this plugin should be found in the theme.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	protected $theme_template_directory = 'ufclas-team-members';

	/**
	 * Reference to the root directory path of this plugin.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	protected $plugin_directory = UFCLAS_TEAMMEMBER_PLUGIN_DIR;

	/**
	 * Directory name where templates are found in this plugin.
	 *
	 * @since 1.1.0
	 * @var string
	 */
	protected $plugin_template_directory = 'templates';

}
