<?php
/* Admin functions to set and save settings of the 
 * @package COMMENT_NOTICE
*/
require_once('pages.php');
require_once('meta_box.php');
require_once(COMMENT_NOTICE_INC.'list_table.php');
/* Initialize the theme admin functions */
add_action('init', 'comment_notice_admin_init');

function comment_notice_admin_init(){
			
    add_action('admin_menu', 'comment_notice_settings_init');
    add_action('admin_init', 'comment_notice_actions_handler');
    add_action('admin_init', 'comment_notice_admin_style');
    add_action('admin_init', 'comment_notice_admin_script');
    
}

function comment_notice_settings_init(){
   global $comment_notice; 
   add_menu_page('Comment Notice', 'Notice', 'manage_options', 'comment-subscriptions', 'comment_notice_subscriptions_page' ); 
   $comment_notice->subscriptions = add_submenu_page('comment-subscriptions', 'Subscriptions', 'Subscriptions', 'manage_options', 'comment-subscriptions', 'comment_notice_subscriptions_page' );
   $comment_notice->email = add_submenu_page('comment-subscriptions', 'Email Format', 'Email Format', 'manage_options', 'comment-email', 'comment_notice_email_page' );
   $comment_notice->settings = add_submenu_page('comment-subscriptions', 'Settings', 'Settings', 'manage_options', 'comment-settings', 'comment_notice_settings_page' );
   /* Make sure the settings are saved. */
   add_action( "load-{$comment_notice->email}", 'comment_notice_email_settings');
   add_action( "load-{$comment_notice->settings}", 'comment_notice_config_settings');
}

function comment_notice_admin_style(){
  $plugin_data = get_plugin_data( COMMENT_NOTICE_DIR . 'comment_notice.php' );
	
	wp_enqueue_style( 'comment_notice-admin', COMMENT_NOTICE_CSS . 'style.css', false, $plugin_data['Version'], 'screen' );	
           
}
function comment_notice_admin_script(){
    wp_enqueue_script('postbox');
	wp_enqueue_script('dashboard');
	wp_enqueue_script('jquery');	
}
function comment_notice_actions_handler(){  
  
   if(isset($_GET['action']) && $_GET['action']=='comment-notice-ban'){
	  global $wpdb;
	  $query = "UPDATE $wpdb->comments SET comment_notice_status = 'false' where comment_ID=".$_GET['id']; 
	  $wpdb->query($query);
	  $redirect = admin_url( 'admin.php?page=comment-subscriptions' ); 
      wp_redirect($redirect);
   }
   
   if(isset($_GET['action']) && $_GET['action']=='comment-notice-active'){
	  global $wpdb;
	  $query = "UPDATE $wpdb->comments SET comment_notice_status = 'true' where comment_ID=".$_GET['id']; 
	  $wpdb->query($query);
	  $redirect = admin_url( 'admin.php?page=comment-subscriptions' ); 
      wp_redirect($redirect);
   }
  
    if(isset($_POST['comment_notice_email_settings'])){
	  $comment_notice_opts = get_option(COMMENT_NOTICE_OPTIONS);
	  $comment_notice_opts['email_subject'] = $_POST['subject'];
	  $comment_notice_opts['email_content'] = stripslashes($_POST['content']);
	  $comment_notice_opts['email_footer'] = $_POST['footer'];
	  $comment_notice_opts['body_bg'] = $_POST['body_bg'];
	  $comment_notice_opts['text_color'] = $_POST['text_color'];
	  $comment_notice_opts['footer_bg'] = $_POST['footer_bg'];
	  update_option(COMMENT_NOTICE_OPTIONS, $comment_notice_opts);  
	  $redirect = admin_url( 'admin.php?page=comment-email&updated=true' ); 
      wp_redirect($redirect); 
  }
  
   if(isset($_POST['comment_notice_main_settings'])){
	  $comment_notice_opts = get_option(COMMENT_NOTICE_OPTIONS);
	  $comment_notice_opts['display_text'] = $_POST['display'];	  
	  update_option(COMMENT_NOTICE_OPTIONS, $comment_notice_opts);  
	  $redirect = admin_url( 'admin.php?page=comment-settings&updated=true' ); 
      wp_redirect($redirect); 
  }  
}
function comment_notice_error_message(){
   echo '<div class="error">
		<p>Error</p>
  </div>';  
}

function comment_notice_updated_message(){
   echo '<div class="updated fade">
		<p>Settings Updated</p>
  </div>';  	
}
?>
