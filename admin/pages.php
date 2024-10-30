<?php
function comment_notice_subscriptions_page(){
    //Create an instance of our package class...
    $subscriptionsListTable = new Subscriptions_List_Table();
    //Fetch, prepare, sort, and filter our data...
    $subscriptionsListTable->prepare_items();
?>
 <div class="wrap">
        
        <?php if ( function_exists( 'screen_icon' ) ) screen_icon(); ?>
        <h2><?php _e( 'Subscriptions List', 'comment-notice' ); ?></h2>
        <?php if ( isset( $_GET['updated'] ) && 'true' == esc_attr( $_GET['updated'] ) ) comment_notice_updated_message(); ?>
        <!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
        <form id="subscriptions-filter" method="get">
            <!-- For plugins, we also need to ensure that the form posts back to our current page -->
            <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
            <!-- Now we can render the completed list table -->
            <?php $subscriptionsListTable->display() ?>
        </form>
        
    </div>
    <?php    	
}

function comment_notice_email_settings(){
  global $comment_notice;
  add_meta_box( 
             'comment-notice-other-meta-box'
            ,__( 'By the same Author', 'newsletter' )
            ,'comment_notice_other_meta_box'
            ,$comment_notice->email
            ,'other'
            ,'high'
        );  
  add_meta_box( 
             'comment-notice-author-meta-box'
            ,__( 'Like this Plugin', 'newsletter' )
            ,'comment_notice_author_meta_box'
            ,$comment_notice->email
            ,'author'
            ,'high'
        );
  add_meta_box( 
             'comment-notice-email-content-meta-box'
            ,__( 'Email Content', 'comment-notice' )
            ,'comment_notice_email_content_meta_box'
            ,$comment_notice->email
            ,'email_content'
            ,'high'
        ); 
   add_meta_box( 
             'comment-notice-email-style-meta-box'
            ,__( 'Email Style', 'comment-notice' )
            ,'comment_notice_email_style_meta_box'
            ,$comment_notice->email
            ,'email_style'
            ,'high'
        );           
              	
}

function comment_notice_email_page(){
  global $comment_notice;
   $plugin_data = get_plugin_data( COMMENT_NOTICE_DIR . 'comment_notice.php' ); 	
?>
   
	<div class="wrap">
		
        <?php if ( function_exists( 'screen_icon' ) ) screen_icon(); ?>
        
		<h2><?php _e( 'Email Settings', 'comment-notice' ); ?></h2>
        <?php if ( isset( $_GET['updated'] ) && 'true' == esc_attr( $_GET['updated'] ) ) comment_notice_updated_message(); ?>
        	     
        <form id="email" method="post">
		
		<div id="poststuff" class="metabox-holder has-right-sidebar">			               
				<div id="side-info-column" class="inner-sidebar">
					<div id="side-sortables" class="meta-box-sortables">
					   <?php do_meta_boxes( $comment_notice->email, 'author', $plugin_data ); ?>	
				   	   <?php do_meta_boxes( $comment_notice->email, 'other', $plugin_data ); ?>
				    </div>
				</div>	
				<div id="post-body">
				   <div id="post-body-content">
				      
				      <?php do_meta_boxes( $comment_notice->email, 'email_content', $plugin_data ); ?>
				       <?php do_meta_boxes( $comment_notice->email, 'email_style', $plugin_data ); ?> 	   
                      				       
				   </div><!-- #post-body-content -->
				</div><!-- post-body -->
									
		</div><!-- #poststuff -->
		<br class="clear">
        <input class="button button-primary" type="submit" value="<?php _e('Save'); ?>" name="comment_notice_email_settings" />
        </form>
	</div><!-- .wrap -->  
<?php		
}

function comment_notice_settings_page(){
   global $comment_notice;
   $plugin_data = get_plugin_data( COMMENT_NOTICE_DIR . 'comment_notice.php' ); 	
?>
   
	<div class="wrap">
		
        <?php if ( function_exists( 'screen_icon' ) ) screen_icon(); ?>
        
		<h2><?php _e( 'Settings', 'comment-notice' ); ?></h2>
        <?php if ( isset( $_GET['updated'] ) && 'true' == esc_attr( $_GET['updated'] ) ) comment_notice_updated_message(); ?>
        	     
        <form id="settings" method="post">
		
		<div id="poststuff" class="metabox-holder has-right-sidebar">			               
					
				<div id="post-body">
				   <div id="post-body-content">
				      
				      <?php do_meta_boxes( $comment_notice->settings, 'settings', $plugin_data ); ?>	   
				       
				   </div><!-- #post-body-content -->
				</div><!-- post-body -->
									
		</div><!-- #poststuff -->
		<br class="clear">
        <input class="button button-primary" type="submit" value="<?php _e('Save'); ?>" name="comment_notice_main_settings" />
        </form>
	</div><!-- .wrap -->  
<?php		 	
}

function comment_notice_config_settings(){
  global $comment_notice;
  add_meta_box( 
             'comment-notice-settings-meta-box'
            ,__( 'Settings', 'comment-notice' )
            ,'comment_notice_settings_meta_box'
            ,$comment_notice->settings
            ,'settings'
            ,'high'
        );       
              	
}
?>
