<?php
/*
Plugin Name: Tweets Aside
Plugin URI: http://wordpress.org/extend/plugins/tweets-aside/
Description: Shows your or someones tweets as an aside posts right in your blog line, not in a widget.
Author: Sergey Rozenblat
Version: 0.2
Author URI: http://sergey.rozenblat.kz
*/

add_action('admin_menu', 'tweets_aside_menu');
add_action( 'admin_init', 'register_tweets_aside_settings');
add_action('tweets_aside_update_event', 'tweets_aside_update');

register_deactivation_hook(__FILE__, 'deactivate_cron');
function deactivate_cron() { wp_clear_scheduled_hook('tweets_aside_update_event'); }

function register_tweets_aside_settings() {
  register_setting( 'tweets-aside-group', 'tweet-from' );
  register_setting( 'tweets-aside-group', 'tweet-update' );
  register_setting( 'tweets-aside-group', 'tweet-manual-update' );
  
  if(get_option('tweet-update') === "hourly" or get_option('tweet-update') === "twicedaily" or get_option('tweet-update') === "daily" ) {
  	wp_schedule_event(time(), 'hourly', 'tweets_aside_update_event');
  } else wp_clear_scheduled_hook('tweets_aside_update_event');
  
}

function tweets_aside_menu() {
	add_options_page('Tweets Aside Options', 'Tweets Aside', 'manage_options', 'tweets-aside', 'tweets_aside_options');
}

function tweets_aside_update() {
	global $post;
	$args = array('numberposts' => 1000, 'post_status' => array('publish', 'trash'));
	$myposts = get_posts( $args );
	$my = array();
	foreach( $myposts as $post ) :	setup_postdata($post); 
		if( get_post_format( $post->ID ) == 'aside' ) {
		$my[] = $post->post_date;
		}
	endforeach;	
	
	$username = get_option('tweet-from');
	$fn=0;
	$doc = new DOMDocument();
	$doc->load("http://search.twitter.com/search.atom?q=from:" . $username . "&rpp=30");
	foreach ($doc->getElementsByTagName('entry') as $node) {
		$t = $node->getElementsByTagName('content')->item(0)->nodeValue;
		$d = $node->getElementsByTagName('published')->item(0)->nodeValue;
		$d = str_replace("T", " ", $d);
		$d = str_replace("Z", "", $d);
		foreach($my as $sg) {
			if($d == $sg) $fn=1;
		}
		if(!$fn) {
			$topost = array(
			     'post_content' => $t,
			     'post_status' => 'publish',
			     'post_date' => $d,
			     'post_author' => 1
			);
			$post_id = wp_insert_post($topost);
			set_post_format( $post_id, 'aside' );	
		
		}
		$fn=0;$t=0;$d=0;$post_id='';
	}
	
	update_option( 'tweet-manual-update', 0 );

}

	
function tweets_aside_updated() {
	echo "<div class='updated fade'><p>"._e('Update completed successfully'). "</p></div>";
}

function tweets_aside_options() {
	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}

	echo '<div class="wrap">';
	echo '<h2>Tweets Aside</h2>';
	
	echo '<form method="post" action="options.php">';
	settings_fields( 'tweets-aside-group' );
	$times = array(
	    'manual' => array(
	        'value' => 'manual',
	        'label' => 'Manually'
	    ),
	    'hourly' => array(
	        'value' => 'hourly',
	        'label' => 'Hourly'
	    ),
	    'twicedaily' => array(
	        'value' => 'twicedaily',
	        'label' => 'Twice a day'
	    ),
	    'daily' => array(
	        'value' => 'daily',
	        'label' => 'Daily'
	    ),
	);
	

		if ( get_option('tweet-manual-update') ) {
			tweets_aside_update();
		}
	
		?>
	
	    <table class="form-table">
        <tr valign="top">
        <th scope="row">Your Twitter username</th>
        <td><input type="text" name="tweet-from" value="<?php echo get_option('tweet-from'); ?>" /></td>
        </tr>
         
        <tr valign="top">
        <th scope="row">Update interval</th>
        <td><select name="tweet-update">
        <?php
    		foreach ( $times as $time ) :
        	$label = $time['label'];
        	$selected = '';
        	if ( $time['value'] == get_option('tweet-update') )
            $selected = 'selected="selected"';
       		echo '<option value="' . esc_attr( $time['value'] ) . '" ' . $selected . '>' . $label . '</option>';
    		endforeach;
    	?>
    	</select> &nbsp; <input type="submit" class="button-secondary" name='tweet-manual-update' value="<?=_e('Update')?>" />
    	</td>
        </tr>

   		</table>
    
    <?

	echo '<p class="submit">';
	echo '<input type="submit" class="button-primary" value="'; echo _e('Save Changes'); echo '" />';
	echo '</p>';
	echo '</form>';
	echo '</div>';
}

?>
