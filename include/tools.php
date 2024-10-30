<?php
function comment_notice_notification($cid){
   global $wpdb;
   $comment_notice_opts = get_option(COMMENT_NOTICE_OPTIONS);
   
   $cid = (int) $cid;
   $comment = $wpdb->get_row("SELECT * FROM $wpdb->comments WHERE comment_ID='$cid' LIMIT 1");
   $post = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE ID='$comment->comment_post_ID' LIMIT 1");

   if ( $comment->comment_approved == '1' && $comment->comment_type == '' ) {
	 // Comment has been approved and isn't a trackback or a pingback, so we should send out notifications
     $permalink = get_permalink($comment->comment_post_ID);
     
     //replace tags on content template 
     $tags = array('{post_title}', '{permalink}', '{author}', '{date}', '{comment_content}');
     $replace = array($post->post_title, $permalink, $comment->comment_author, $comment->comment_date, $comment->comment_content); 
     $content .= str_replace($tags, $replace, $comment_notice_opts['email_content']); 
     
     $content_style = '<div id="content" style="color:'.$comment_notice_opts['text_color']
                      .';background-color:'.$comment_notice_opts['body_bg']
                      .';padding:20px;">';
      
     $footer_style = '<div id="footer" style="background-color:'.$comment_notice_opts['footer_bg']
                     .';padding:20px;">';
                      
     $message = $content_style. $content ."</div>"
                .$footer_style .$comment_notice_opts['email_footer']."</div>";
                   
     $message = stripslashes($message);
     	
	 $subject = str_replace('{post_title}', $post->post_title, $comment_notice_opts['email_subject']);;

	 $subscribers = comment_notice_subscriber_from_post($comment->comment_post_ID);
	 foreach ( (array) $subscribers as $email ) {		 
			if ( $email != $comment->comment_author_email && is_email($email) ) {
			        //$h = fopen(dirname(__FILE__).'/log.txt', 'w'); fwrite($h, $message); fclose($h);
			        comment_notice_mail($email, $subject, $message);
	        }
	  } // foreach subscription
   } // end if comment approved
   return $cid;	
}

function comment_notice_mail($to, $subject, $message){
    $subject = '[' . get_bloginfo('name') . '] ' . $subject;

	$site_name = get_bloginfo('name');
	$site_email = get_bloginfo('admin_email');
	$charset = get_bloginfo('charset');
	
	//$headers = 'From: '.$site_name.' <'.$site_email.'>' . "\r\n";
	//$headers = 'From: My Name <myname@mydomain.com>' . "\r\n";
    
	wp_mail($to, $subject, $message);	
}

function comment_notice_mail_from($orig){
	$comment_notice_opts = get_option(COMMENT_NOTICE_OPTIONS);
	$from_email = $comment_notice_opts['from_email'];
	// This is copied from pluggable.php lines 348-354 as at revision 10150
	// http://trac.wordpress.org/browser/branches/2.7/wp-includes/pluggable.php#L348
	
	// Get the site domain and get rid of www.
	$sitename = strtolower( $_SERVER['SERVER_NAME'] );
	if ( substr( $sitename, 0, 4 ) == 'www.' ) {
		$sitename = substr( $sitename, 4 );
	}

	$default_from = 'wordpress@' . $sitename;
	// End of copied code
	
	// If the from email is not the default, return it unchanged
	if ( $orig != $default_from ) {
		return $orig;
	}
	
	if (is_email($from_email)){
	    //$h = fopen(dirname(__FILE__).'/log.txt', 'aw'); fwrite($h, $from_email.'\n'); fclose($h);   
		return $from_email;
    }		
	
	// If in doubt, return the original value
	return $orig;
}

function comment_notice_mail_from_name ($orig) {
	$comment_notice_opts = get_option(COMMENT_NOTICE_OPTIONS);
	$from_name = $comment_notice_opts['from_name'];
	
	// Only filter if the from name is the default
	if ($orig == 'WordPress') {
		if ( $from_name != "" && is_string($from_name) )
			//$h = fopen(dirname(__FILE__).'/log.txt', 'aw'); fwrite($h, $from_name.'\n'); fclose($h);
			return $from_name;
	}
	
	// If in doubt, return the original value
	return $orig;
	
}

function comment_notice_subscriber_from_post($postid) {
	global $wpdb;
	$postid = (int) $postid;
	$subscribers = $wpdb->get_col("SELECT comment_author_email FROM $wpdb->comments WHERE comment_post_ID = '$postid' AND comment_notice='true' AND comment_notice_status='true' AND comment_author_email != '' AND comment_approved = '1' GROUP BY LCASE(comment_author_email)");
	return $subscribers;
}

/* display comment notice checkbox on comment area */
function comment_notice_checkbox ($id='0') {
	$comment_notice_opts = get_option(COMMENT_NOTICE_OPTIONS);
    //check whether user have checked
	$checked = ( !empty($_COOKIE['comment_notice_'.COOKIEHASH]) && 'checked' == $_COOKIE['comment_notice_'.COOKIEHASH] ) ? true : false;
    if(!current_user_can('manage_options')){
?>

	<p class="comment-notice" style="clear: both;">
	<input type="checkbox" name="notice" id="notice" value="notice" style="width: auto;" <?php if ( $checked ) echo 'checked="checked" '; ?>/>
	<label for="notice"><?php echo $comment_notice_opts['display_text']; ?></label>
	</p>

<?php
   }
}

function comment_notice_status($data) {
	if ( isset($_POST['notice']) )
		setcookie('comment_notice_'. COOKIEHASH, 'checked', time() + 30000000, COOKIEPATH);
	else
		setcookie('comment_notice_'. COOKIEHASH, 'unchecked', time() + 30000000, COOKIEPATH);
	return $data;
}

function comment_notice_subscribe($cid){
    global $wpdb;
	$cid = (int) $cid;	

	//$previously_subscribed = ( $wpdb->get_var("SELECT comment_subscribe from $wpdb->comments WHERE comment_post_ID = '$postid' AND LCASE(comment_author_email) = '$email_sql' AND comment_subscribe = 'Y' LIMIT 1") || in_array($email, (array) get_post_meta($postid, '_sg_subscribe-to-comments')) ) ? true : false;

	// If user wants to be notified or has previously subscribed, set the flag on this current comment
	if ($_POST['notice'] == 'notice') {
		$wpdb->query("UPDATE $wpdb->comments SET comment_notice = 'true', comment_notice_status = 'true' where comment_ID = '$cid'");
	}
	return $cid;	
}
?>
