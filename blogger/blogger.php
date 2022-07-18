<?php
/**
* Plugin Name: Blogger
* Author: Masunulla
* Version: 0.0.1
* Description: Ability publishes your multiple customer's blogposts in a single website. Full control in customer's activity.
* Tags: Blogger
* Lisence: GPL V2
*/
defined('ABSPATH') || die("You Can't Access This File Directly");

function my_plugin_activate() {
    wp_insert_post( array(
      'post_title' => 'Blogger Registration',
      'post_name' => "blogger-registration",
      'post_type' => 'page',
      'post_status' => 'publish',
    ) ); 
}
register_activation_hook( __FILE__, 'my_plugin_activate' );

function my_plugin_deactivate($value='')
{
  $found_post = null;

  if ( $posts = get_posts( array( 
      'post_name' => 'blogger-registration', 
      'post_type' => 'page',
      'post_status' => 'publish',
      'posts_per_page' => 1
  ) ) ) $found_post = $posts[0];

  if ( ! is_null( $found_post ) ){
  	wp_delete_post($found_post->ID);
  }
}
register_deactivation_hook( __FILE__, 'my_plugin_deactivate' );

function load_css_js() {
  if(is_page('blogger-registration') ){
    wp_enqueue_script('jquery');
    wp_enqueue_style( 'blogger-style', plugin_dir_url(__FILE__) . 'assets/style.css', false, NULL, 'all' );
    wp_enqueue_script('blogger-script', plugin_dir_url(__FILE__)."assets/script.js", array(), '0.0.1', true);
  }
}
add_action( 'wp_enqueue_scripts', 'load_css_js' );


function blogReg_page_template( $page_template )
{
  if ( is_page( 'blogger-registration' ) ) {
    $page_template = dirname( __FILE__ ) . '/page-blogger-registration.php';
  }
  return $page_template;
}
add_filter( 'page_template', 'blogReg_page_template' );


if(!function_exists('wp_get_current_user')) {
    include(ABSPATH . "wp-includes/pluggable.php"); 
}

$userExists = '';
if (isset($_POST['add_new_blogger'])) {
$username = $_POST['blogger_login'];
$email = $_POST['blogger_email'];
$num = 0;
if(username_exists($username) ){
// if(get_user_by('post_name',$username) ){
$num++;
}
if(email_exists($email) ){
// if(get_user_by('user_email',$email) ){
$num += 2;
}
if($num == 1){
$userExists = 'Username already exists';
}
else if($num == 2){
$userExists = 'Email already exists';
}
else if($num == 3){
$userExists = 'Username & Email already exists';
}
else{
$nickname = str_replace(' ', '-', strtolower($username));
$WP_array = array (
'user_login'    =>  $username,
'user_email'    =>  $email,
'user_pass'     =>  $_POST['blogger_pass'],
'nickname'      =>  $nickname
) ;
$id = wp_insert_user( $WP_array ) ;
wp_update_user( array ('ID' => $id, 'role' => 'author') ) ;
auth_redirect();
}
}

add_action('pre_get_posts', 'query_set_only_author' );
function query_set_only_author( $wp_query ) {
global $current_user;
if( is_admin() && !current_user_can('edit_others_posts') ) {
$wp_query->set( 'author', $current_user->ID );
add_filter('views_edit-post', 'fix_post_counts');
add_filter('views_upload', 'fix_media_counts');
}
}

function fix_post_counts($views) {
global $current_user, $wp_query;
unset($views['mine']);
$types = array(
array( 'status' =>  NULL ),
array( 'status' => 'publish' ),
array( 'status' => 'draft' ),
array( 'status' => 'pending' ),
array( 'status' => 'trash' )
);
foreach( $types as $type ) {
$query = array(
'author'      => $current_user->ID,
'post_type'   => 'post',
'post_status' => $type['status']
);
$result = new WP_Query($query);
if( $type['status'] == NULL ):
$class = ($wp_query->query_vars['post_status'] == NULL) ? ' class="current"' : '';
$views['all'] = sprintf(__('<a href="%s"'. $class .'>All <span class="count">(%d)</span></a>', 'all'),
admin_url('edit.php?post_type=post'),
$result->found_posts);
elseif( $type['status'] == 'publish' ):
$class = ($wp_query->query_vars['post_status'] == 'publish') ? ' class="current"' : '';
$views['publish'] = sprintf(__('<a href="%s"'. $class .'>Published <span class="count">(%d)</span></a>', 'publish'),
admin_url('edit.php?post_status=publish&post_type=post'),
$result->found_posts);
elseif( $type['status'] == 'draft' ):
$class = ($wp_query->query_vars['post_status'] == 'draft') ? ' class="current"' : '';
$views['draft'] = sprintf(__('<a href="%s"'. $class .'>Draft'. ((sizeof($result->posts) > 1) ? "s" : "") .' <span class="count">(%d)</span></a>', 'draft'),
admin_url('edit.php?post_status=draft&post_type=post'),
$result->found_posts);
elseif( $type['status'] == 'pending' ):
$class = ($wp_query->query_vars['post_status'] == 'pending') ? ' class="current"' : '';
$views['pending'] = sprintf(__('<a href="%s"'. $class .'>Pending <span class="count">(%d)</span></a>', 'pending'),
admin_url('edit.php?post_status=pending&post_type=post'),
$result->found_posts);
elseif( $type['status'] == 'trash' ):
$class = ($wp_query->query_vars['post_status'] == 'trash') ? ' class="current"' : '';
$views['trash'] = sprintf(__('<a href="%s"'. $class .'>Trash <span class="count">(%d)</span></a>', 'trash'),
admin_url('edit.php?post_status=trash&post_type=post'),
$result->found_posts);
endif;
}
return $views;
}

function fix_media_counts($views) {
global $wpdb, $current_user, $post_mime_types, $avail_post_mime_types;
$views = array();
$count = $wpdb->get_results( "
SELECT post_mime_type, COUNT( * ) AS num_posts 
FROM $wpdb->posts 
WHERE post_type = 'attachment' 
AND post_author = $current_user->ID 
AND post_status != 'trash' 
GROUP BY post_mime_type
", ARRAY_A );
foreach( $count as $row )
$_num_posts[$row['post_mime_type']] = $row['num_posts'];
$_total_posts = array_sum($_num_posts);
$detached = isset( $_REQUEST['detached'] ) || isset( $_REQUEST['find_detached'] );
if ( !isset( $total_orphans ) )
$total_orphans = $wpdb->get_var("
SELECT COUNT( * ) 
FROM $wpdb->posts 
WHERE post_type = 'attachment' 
AND post_author = $current_user->ID 
AND post_status != 'trash' 
AND post_parent < 1
");
$matches = wp_match_mime_types(array_keys($post_mime_types), array_keys($_num_posts));
foreach ( $matches as $type => $reals )
foreach ( $reals as $real )
$num_posts[$type] = ( isset( $num_posts[$type] ) ) ? $num_posts[$type] + $_num_posts[$real] : $_num_posts[$real];
$class = ( empty($_GET['post_mime_type']) && !$detached && !isset($_GET['status']) ) ? ' class="current"' : '';
$views['all'] = "<a href='upload.php'$class>" . sprintf( __('All <span class="count">(%s)</span>', 'uploaded files' ), number_format_i18n( $_total_posts )) . '</a>';
foreach ( $post_mime_types as $mime_type => $label ) {
$class = '';
if ( !wp_match_mime_types($mime_type, $avail_post_mime_types) )
continue;
if ( !empty($_GET['post_mime_type']) && wp_match_mime_types($mime_type, $_GET['post_mime_type']) )
$class = ' class="current"';
if ( !empty( $num_posts[$mime_type] ) )
$views[$mime_type] = "<a href='upload.php?post_mime_type=$mime_type'$class>" . sprintf( translate_nooped_plural( $label[2], $num_posts[$mime_type] ), $num_posts[$mime_type] ) . '</a>';
}
$views['detached'] = '<a href="upload.php?detached=1"' . ( $detached ? ' class="current"' : '' ) . '>' . sprintf( __( 'Unattached <span class="count">(%s)</span>', 'detached files' ), $total_orphans ) . '</a>';
return $views;
}

add_filter( 'wp_insert_post_data', 'filter_handler', '99', 2 );
function filter_handler( $data ) {
$user = wp_get_current_user();
$userRole = $user->roles ? $user->roles[0] : false;
if(isset($data['post_title']) && $data['post_title'] != "Auto Draft" &&  $userRole === 'author' && $data['post_status'] != 'trash'){
$data['post_status'] = 'draft';
return $data;
}
$data['post_title'] = '';
return $data;
}

?>
