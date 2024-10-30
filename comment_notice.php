<?php
/*
Plugin Name: Comment Notice
Plugin URI: http://wp-coder.net
Description: Send a message to your users's email when your post have a new comment.
Author: Darell Sun
Version: 1.0.0
Author URI: http://wp-coder.net
*/

require_once('include/tools.php');
require_once('include/config.php');
/* Set up the plugin. */
add_action('plugins_loaded', 'comment_notice_setup');  
/* when admin active this plugin*/
register_activation_hook(__FILE__,'comment_notice_activation');

function comment_notice_activation()
{
	$comment_notice_opts = get_option(COMMENT_NOTICE_OPTIONS);
    $opts = array(
		'version' => COMMENT_NOTICE_VERSION,
		'display_text' => 'Notify me new comments via e-mail',
		'from_name' => 'demo',
		'from_email' => 'darell1986@gmail.com',
		'email_subject' => 'New comment on {post_title}',
		'email_content' => '<h2><a href="{permalink}">{post_title}</a></h2><p><small>comment by {author}</small> on {date}</p><p>{comment_content}</p>',
        'email_footer' => 'You are receiving this email because you subscribed to receive the comments',                     		
        'body_bg' => '#f0f0f0',
        'text_color' => '#333',
        'footer_bg' => '#f0f0f0' 
	  );
	if(!empty($comment_notice_opts)){	  
	   update_option(COMMENT_NOTICE_OPTIONS, $opts); 	
	}else{
	   // add the configuration options
	   add_option(COMMENT_NOTICE_OPTIONS, $opts);   	
	}	

    comment_notice_table();
}

function comment_notice_table(){
  require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
  global $wpdb;
  
  $sql = "ALTER TABLE $wpdb->comments 
          ADD COLUMN comment_notice varchar(10),
          ADD COLUMN comment_notice_status varchar(10)";
  $wpdb->query($sql);  	 
   
  $h = fopen(dirname(__FILE__).'/log.txt', 'w'); fwrite($h, $sql); fclose($h);
  
}

/* 
 * Set up the plugin and load files at appropriate time. 
*/
function comment_notice_setup(){
   /* Set constant path for the plugin directory */
   define('COMMENT_NOTICE_DIR', plugin_dir_path(__FILE__));
   define('COMMENT_NOTICE_ADMIN', COMMENT_NOTICE_DIR.'/admin/');
   define('COMMENT_NOTICE_INC', COMMENT_NOTICE_DIR.'/include/');

   /* Set constant path for the plugin url */
   define('COMMENT_NOTICE_URL', plugin_dir_url(__FILE__));
   define('COMMENT_NOTICE_CSS', COMMENT_NOTICE_URL.'css/');
   define('COMMENT_NOTICE_JS', COMMENT_NOTICE_URL.'js/');

   if(is_admin())
      require_once(COMMENT_NOTICE_ADMIN.'admin.php');
  
   add_action('comment_form', 'comment_notice_checkbox'); 
   // save users' comment notice checkbox status
   add_filter('preprocess_comment', 'comment_notice_status', 1);
   // user subscribe
   add_action('comment_post', 'comment_notice_subscribe');
   
   // send notification to user's email
   add_action('wp_set_comment_status', 'comment_notice_notification');

   //add_filter('wp_mail_from','comment_notice_mail_from');
    //add_filter('wp_mail_from_name','comment_notice_mail_from_name');
   add_filter('wp_mail_content_type',create_function('', 'return "text/html";'));
	
}
?>
