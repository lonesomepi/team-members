<?php
/*
Plugin Name: UFCLAS Team Members
Plugin URI: https://mediaservices.clas.ufl.edu/
Description: Manage Team Members for department sites
Version: 1.0.0
Author: Media Services
Author URI: https://mediaservices.clas.ufl.edu/
License: GPL2
Build Date: 20191118
*/

  // Path to the root of the plugin, used for including template files
  define( 'UFCLAS_TEAMMEMBER_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
  require UFCLAS_TEAMMEMBER_PLUGIN_DIR . 'inc/class-ufclas-team-members-loader.php';

  /**
  * Register team members page custom post type
  * @since 1.0.0
  */
  function ufclas_register_team_members() {
    $labels = array(
    'name'                  => _x( 'Team Members', 'Post Type General Name', 'clas_team_members' ),
    'singular_name'         => _x( 'Team Member', 'Post Type Singular Name', 'clas_team_members' ),
    'menu_name'             => __( 'Team Members', 'clas_team_members' ),
    'name_admin_bar'        => __( 'Team Members', 'clas_team_members' ),
    'archives'              => __( 'Team Member Archives', 'clas_team_members' ),
    'attributes'            => __( 'Team Member Attributes', 'clas_team_members' ),
    'parent_item_colon'     => __( 'Parent Team Members:', 'clas_team_members' ),
    'all_items'             => __( 'All Team Members', 'clas_team_members' ),
    'add_new_item'          => __( 'Add New Team Member', 'clas_team_members' ),
    'add_new'               => __( 'Add New', 'clas_team_members' ),
    'new_item'              => __( 'New Item', 'clas_team_members' ),
    'edit_item'             => __( 'Edit Team Member', 'clas_team_members' ),
    'update_item'           => __( 'Update Item', 'clas_team_members' ),
    'view_item'             => __( 'View Item', 'clas_team_members' ),
    'view_items'            => __( 'View Items', 'clas_team_members' ),
    'search_items'          => __( 'Search Item', 'clas_team_members' ),
    'not_found'             => __( 'Not found', 'clas_team_members' ),
    'not_found_in_trash'    => __( 'Not found in Trash',    'clas_team_members' ),
    'featured_image'        => __( 'Featured Image',        'clas_team_members' ),
    'set_featured_image'    => __( 'Set featured image',    'clas_team_members' ),
    'remove_featured_image' => __( 'Remove featured image', 'clas_team_members' ),
    'use_featured_image'    => __( 'Use as featured image', 'clas_team_members' ),
    'insert_into_item'      => __( 'Insert into item', 'clas_team_members' ),
    'uploaded_to_this_item' => __( 'Uploaded to this item', 'clas_team_members' ),
    'items_list'            => __( 'Items list',            'clas_team_members' ),
    'items_list_navigation' => __( 'Items list navigation', 'clas_team_members' ),
    'filter_items_list'     => __( 'Filter items list',     'clas_team_members' ),
    );

    $rewrite = array(
    'slug'                  => 'people'
    );

    $args = array(
    'label'                 => __( 'Team Member', 'clas_team_members' ),
    'description'           => __( 'Team members of department', 'clas_team_members' ),
    'labels'                => $labels,
    'supports'              => array( 'title', 'thumbnail' ),
    'taxonomies'            => array(),
    'hierarchical'          => false,
    'public'                => true,
    'publicly_queryable'    => true,
    'show_ui'               => true,
    'show_in_menu'          => true,
    'menu_position'         => 5,
    'menu_icon'             => 'dashicons-admin-users',
    'show_in_admin_bar'     => true,
    'show_in_nav_menus'     => true,
    'can_export'            => true,
    'has_archive'           => true,
    'exclude_from_search'   => false,
    'query_var'             => true,
    'rewrite'               => $rewrite,
    'capability_type'       => 'post',
    );

    register_post_type( 'clas_team_members', $args );

  }

  /*====================================
  Registers Custom taxonmy for Team members
  =======================================*/

  function initialize_taxonomy() {
    register_taxonomy(
      'team_member_position',
      'clas_team_members',
      array (
        'labels' => array(
        'name'          => 'Team Member Position',
        'add_new_item'  => 'Add New Position',
        'new_item_name' => "New Team Member Position"
      ),
      'show_ui'       => true,
      'show_tagcloud' => false,
      'hierarchical'  => true,
      'rewrite'       => array(
          'slug'       => 'position',
          'with_front' => false
          )
      )
    );
  }

  add_action( 'init', 'initialize_taxonomy', 0 );

  // Register the team member archive custom post type and taxonomies
  add_action( 'init', 'ufclas_register_team_members', 0 );

  /**
  * Flush rewrite rules on activation and deactivation
  * @since 1.0.0
  */

  function ufclas_team_members_activation() {

    // Register post type and taxonomy rewrite rules
    ufclas_register_team_members();

    // Then flush them
    flush_rewrite_rules();

  }

  function ufclas_team_members_deactivation() {
    flush_rewrite_rules();
  }

  register_activation_hook(   __FILE__, 'ufclas_team_members_activation');
  register_deactivation_hook( __FILE__, 'ufclas_team_members_deactivation');

  /* =====   General   ===== */

  /**
  * Add custom templates depending on theme
  * @since 1.0.0
  */
  function ufclas_team_members_templates( $template_path ) {

    // Change template for archive page if files exist in theme
    if( is_singular( 'clas_team_members' ) ){
      $templates     = new UFCLAS_TEAMMEMBER_Template_Loader;
      $template_path = $templates->get_template_part( 'single', 'team-member', false );
    }

    if( is_post_type_archive( 'clas_team_members' ) ){
      $templates     = new UFCLAS_TEAMMEMBER_Template_Loader;
      $template_path = $templates->get_template_part( 'archive', 'team-member', false );
    }

    if( is_tax( 'team_member_position' ) ){
      $templates     = new UFCLAS_TEAMMEMBER_Template_Loader;
      $template_path = $templates->get_template_part( 'archive', 'position', false );
    }

    return $template_path;

  }

  add_filter( 'template_include', 'ufclas_team_members_templates', 1 );


  /* -- If admin area -- */

  if (is_admin()) {
    // include dependencies || call ADMIN/clas-people-metabox.php
    require_once plugin_dir_path(__FILE__) . 'admin/clas-people-metabox.php';
  }


  function ufclas_team_member_styles() {
    // wp_register_style('ufclas-team-members-style', plugins_url('/css/team-member-styles.css', __FILE__));
    // wp_register_style('ufclas-team-members-styles',	plugins_url("ufclas-team-members/css/team-member-styles.css"));
    // Load the Internet Explorer 8 specific stylesheet.
    // wp_enqueue_style( 'ufclas-team-members-styles', get_template_directory_uri() . '/css/team-member-styles.css' );
    // wp_register_style('ufclas-team-members-styles', plugins_url('/css/team-member-styles.css', __FILE__));
    // wp_enqueue_style( 'ufclas-team-members-styles', get_template_directory_uri() . '/css/baz.css' );
    wp_register_style('ufclas-team-members-styles', plugins_url('/css/team-member-styles.css', __FILE__));
    wp_register_style('ufclas-team-members-styles',	plugins_url("ufclas-team-members/css/team-member-styles.css"));
    wp_enqueue_style('ufclas-team-members-styles');
  }

  add_action( 'wp_enqueue_scripts', 'ufclas_team_member_styles');

  // calling the function, that calls register and enque
  // add_action( 'wp_enqueue_styles', 'ufclas_team_member_styles' );



  // function showHide_admins() {
    // wp_register_script('showHide', plugins_url("ufclas-team-members/js/showHide.js"));
    // wp_enqueue_script('showHide');
  // }

  // // add_action( 'plugins_loaded', 'showHide_admins' );
  // add_action( 'admin_footer', 'showHide_admins' );




  /*==============================
  Organize by last word in title
  ==============================*/

  function posts_orderby_lastname ($orderby_statement) {
    $orderby_statement = "RIGHT(post_title, LOCATE(' ', REVERSE(post_title)) - 1) ASC";
    return $orderby_statement;
  }

?>
