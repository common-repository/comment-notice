<?php
function comment_notice_author_meta_box(){
  echo "<p><a href='http://wp-coder.net'>Custom WP plugin Services</a></p>";
  echo "<p><a href='http://plugins.wp-coder.net/purchase/comment-notice/'>Leave you comments for this plugin</a></p>"; 
  echo "<p><a href='http://wordpress.org/extend/plugins/enl-newsletter/'>Give a good rating on WordPress.org</a></p>";	
}
function comment_notice_other_meta_box(){
  echo "<p><a href='http://codecanyon.net/item/comment-to-access/1009014'>comment to acess</a></p>";
  echo "<p><a href='http://wordpress.org/extend/plugins/enl-newsletter/'>WP Newsletter</a></p>";	
  echo "<p><a href='http://wordpress.org/extend/plugins/wp-rss-poster/'>WP RSS Poster</a></p>";
}


function comment_notice_email_style_meta_box(){
   global $comment_notice;
   $comment_notice_opts = get_option(COMMENT_NOTICE_OPTIONS);
   $body_bg = $comment_notice_opts['body_bg'];
   $text_color = $comment_notice_opts['text_color'];
   $footer_bg = $comment_notice_opts['footer_bg'];
?>
   <table class="form-table">
		<tr>
			<th class='body-bg'>
            	<label for="body_bg"><?php _e( 'Body Background:', 'comment-notice' ); ?></label> 
            </th>
            <td>
            	<input id="body_bg" name="body_bg" type="text" value="<?php echo $body_bg;?>" />
            </td>
		</tr>
		<tr></tr>
		<tr>
		    <th class='text-color'>
            	<label for="text_color"><?php _e( 'Text Color:', 'comment-notice' ); ?></label> 
             </th>
             <td>
		        <input id="text_color" name="text_color" type="text" value="<?php echo $text_color;?>" /> 
		   </td> 
		</tr>
		<tr></tr>
		<tr>
		    <th class='footer-bg'>
            	<label for="footer_bg"><?php _e( 'Footer Background:', 'comment-notice' ); ?></label> 
          </th>
          <td>
		       <input id="footer_bg" name="footer_bg" type="text" value="<?php echo $footer_bg;?>" />
		   </td>
		</tr>
	</table><!-- .form-table -->
<?php   	
}
function comment_notice_email_content_meta_box(){
   global $comment_notice;
   $comment_notice_opts = get_option(COMMENT_NOTICE_OPTIONS);
   
   $subject = $comment_notice_opts['email_subject'];
   $content = $comment_notice_opts['email_content'];
   $footer = $comment_notice_opts['email_footer'];                
?>
   <table class="form-table">
		<tr>
			<th class='email'>
            	<label for="subject"><?php _e( 'Subject:', 'comment-notice' ); ?></label> 
            </th>
            <td>
            	<input id="subject" name="subject" type="text" value="<?php echo $subject;?>" />
            </td>
		</tr>
		<tr></tr>
		<tr>
		    <th class='email'>
            	<label for="content"><?php _e( 'Content:', 'comment-notice' ); ?></label> 
             </th>
             <td>
		      <textarea rows="4" cols="65" name="content"><?php echo $content; ?></textarea>
		      <br />
		      <span>You can use the following tag on template:</span><br /><small>{post_title}{permalink}{comment_content}{author}{date}</small>
		   </td> 
		</tr>
		<tr></tr>
		<tr>
		    <th class='email'>
            	<label for="footer"><?php _e( 'Footer:', 'comment-notice' ); ?></label> 
          </th>
          <td>
		       <textarea rows="2" cols="65" name="footer"><?php echo $footer; ?></textarea>
		   </td>
		</tr>
	</table><!-- .form-table -->
<?php	
}

function comment_notice_settings_meta_box(){
   global $comment_notice;
   $comment_notice_opts = get_option(COMMENT_NOTICE_OPTIONS);
   
   $display_text = $comment_notice_opts['display_text'];
?>
   <table class="form-table">
		<tr>
			<th class='display'>
            	<label for="display"><?php _e( 'Display Text:', 'comment-notice' ); ?></label> 
            </th>
            <td>
            	<input id="display" name="display" type="text" value="<?php echo $display_text;?>" />
            </td>
		</tr>	
	</table><!-- .form-table -->
<?php	
}
?>
