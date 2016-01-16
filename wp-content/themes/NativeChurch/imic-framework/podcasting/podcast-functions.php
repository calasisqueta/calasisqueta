<?php
// add the itunes namespace to the RSS opening element
function imic_podcast_add_namespace() {
	echo 'xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd"';
}
// add itunes specific info to each item
function imic_podcast_add_head(){
	
	remove_filter('the_content', 'add_imic_sermon_content');
	$options = get_option('imic_options'); ?>
	<copyright><?php echo esc_html( $options['podcast_copyright'] ) ?></copyright>
	<itunes:subtitle><?php echo esc_html( $options['podcast_itunes_subtitle'] ) ?></itunes:subtitle>
	<itunes:author><?php echo esc_html( $options['podcast_itunes_author'] ) ?></itunes:author>
	<itunes:summary><?php echo wp_filter_nohtml_kses( $options['podcast_itunes_summary'] ) ?></itunes:summary>	
	<itunes:owner>
		<itunes:name><?php echo esc_html( $options['podcast_itunes_owner_name'] ) ?></itunes:name>
		<itunes:email><?php echo esc_html( $options['podcast_itunes_owner_email'] ) ?></itunes:email>
	</itunes:owner>
	<itunes:explicit>no</itunes:explicit>
		<?php global $imic_options;
		$cover_image = $imic_options['podcast_itunes_cover_image']['url'];
		if($cover_image != ''){
		 ?>
			<itunes:image href="<?php echo $cover_image ?>" />
        <?php } else { ?>
			<itunes:image href="<?php echo get_template_directory_uri() ?>/images/cover.png" />
        <?php } ?>
	<itunes:category text="<?php echo esc_attr( $options['podcast_itunes_top_category'] ) ?>">
	<itunes:category text="<?php echo esc_attr( $options['podcast_itunes_sub_category'] ) ?>"/>
	</itunes:category>
	<?php
}
// add itunes specific info to each item
function imic_podcast_add_item(){
	
	global $post;
	$attach_full_audio= imic_sermon_attach_full_audio(get_the_ID());
    $audio = $attach_full_audio;
	$speaker = strip_tags( get_the_term_list( $post->ID, 'sermons-speakers', '', ' &amp; ', '' ) );
	$series = strip_tags( get_the_term_list( $post->ID, 'sermons-category', '', ', ', '' ) );
	
	// Sermon Topics
	$topics = wp_get_post_terms( get_the_ID() , 'sermons-tag' );
	$topics = false;
	if( $topic_list && count( $topic_list ) > 0 ) {
		$c = 0;
		foreach( $topic_list as $t ) {
			if( $c == 0 ) {
				$topics = esc_html( $t->name );
				++$c;
			} else {
				$topics .= ', ' . esc_html( $t->name );
			}
		}
	}
	$post_image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'large' );
	$post_image = ( $post_image ) ? $post_image['0'] : null;
	
	$audio_duration = get_post_meta($post->ID, 'imic_sermon_duration', 'true');
	if ($audio_duration == '' ) $audio_duration = '0:00'; //zero if undefined
	$podcast_desc = get_post_meta($post->ID, 'imic_sermons_podcast_description', 'true');
	?>
    
	<itunes:author><?php echo esc_html( $speaker ); ?></itunes:author>
	<itunes:subtitle><?php echo esc_html(  $series ); ?></itunes:subtitle>
	<itunes:summary><?php echo esc_html(  $podcast_desc ); ?></itunes:summary>
	<?php if ( $post_image ) : ?>
	<itunes:image href="<?php echo $post_image; ?>" />
	<?php endif; ?>
	<?php if ( $enclosure == '' ) : ?>
		<enclosure url="<?php echo $audio; ?>" length="0" type="audio/mpeg"/>
	<?php endif; ?>
	<itunes:duration><?php echo esc_html( $audio_duration ); ?></itunes:duration>
	<?php if( $topics ) { ?>
		<itunes:keywords><?php echo esc_html( $topics ); ?></itunes:keywords><?php } 
}
//Display the sermon description as the podcast summary
function imic_podcast_summary ($content) {
	global $post;
	$podcast_desc = get_post_meta($post->ID, 'imic_sermons_podcast_description', 'true');
	//$content = '';
	$content = $podcast_desc;
}
//Filter published date for podcast: use sermon date instead of post date
function imic_podcast_item_date ($time, $d = 'U', $gmt = false) {
 
	$time = get_the_date('D, d M Y H:i:s O');
	return $time;
}
// Filter the date on sermons only
function imic_modify_sermon_date( $query ) {
	if ( !is_admin() && $query->is_main_query() && $query->is_feed() ) :
	if( is_post_type_archive('sermons') || is_tax( 'sermons-speakers' ) || is_tax( 'sermons-tag' ) || is_tax( 'sermons-category' ) ) {
		add_filter ( 'get_post_time', 'imic_podcast_item_date', 10, 3);
		add_action( 'rss_ns', 'imic_podcast_add_namespace' );
		add_action( 'rss2_ns', 'imic_podcast_add_namespace' );
		add_action('rss_head', 'imic_podcast_add_head');
		add_action('rss2_head', 'imic_podcast_add_head');
		add_action('rss_item', 'imic_podcast_add_item');
		add_action('rss2_item', 'imic_podcast_add_item');
		add_filter( 'the_content_feed', 'imic_podcast_summary', 10, 3);
		add_filter( 'the_excerpt_rss', 'imic_podcast_summary');
	}
	endif;
}
add_action('pre_get_posts', 'imic_modify_sermon_date', 9999);
/**
 * Podcast Settings
 */
// Create custom RSS feed for sermon podcasting
function imic_sermon_podcast_feed() {
	load_template( IMIC_SERMONS . 'podcast-feed.php');
}
add_action('do_feed_podcast', 'imic_sermon_podcast_feed', 10, 1);
// Custom rewrite for podcast feed
function imic_sermon_podcast_feed_rewrite($wp_rewrite) {
	$feed_rules = array(
		'feed/(.+)' => 'index.php?feed=' . $wp_rewrite->preg_index(1),
		'(.+).xml' => 'index.php?feed='. $wp_rewrite->preg_index(1)
	);
	$wp_rewrite->rules = $feed_rules + $wp_rewrite->rules;
}
add_filter('generate_rewrite_rules', 'imic_sermon_podcast_feed_rewrite');
// Get the filesize of a remote file, used for Podcast data
function imic_get_filesize( $url, $timeout = 10 ) {
	$headers = wp_get_http_headers( $url);
    $duration = isset( $headers['content-length'] ) ? (int) $headers['content-length'] : 0;
	
	if( $duration ) {
			sscanf( $duration , "%d:%d:%d" , $hours , $minutes , $seconds );
			
			$length = isset( $seconds ) ? $hours * 3600 + $minutes * 60 + $seconds : $hours * 60 + $minutes;
			if( ! $length ) {
					$length = (int) $duration;
			}
			return $length;
	}
	return 0;	
}
//Returns duration of .mp3 file
function imic_mp3_duration($mp3_url) {
	if ( ! class_exists( 'getID3' ) ) {
		require_once IMIC_SERMONS . 'getid3/getid3.php'; 
	}
	$filename = tempnam('/tmp','getid3');
	if (file_put_contents($filename, file_get_contents($mp3_url, false, null, 0, 300000))) {
		  $getID3 = new getID3;
		  $ThisFileInfo = $getID3->analyze($filename);
		  unlink($filename);
	}
	$playtime_string = $ThisFileInfo['playtime_string'];
		return $playtime_string;
	
}
?>