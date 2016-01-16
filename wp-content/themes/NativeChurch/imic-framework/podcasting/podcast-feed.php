<?php
header("Content-Type: application/rss+xml; charset=UTF-8");

$options = get_option('imic_options');
wp_redirect( home_url('/feed/?post_type=sermons'), 301 );
exit;

$args = array(
	'post_type' => 'sermons',
	'posts_per_page' => -1,
	'meta_key' => 'post_date',
	'meta_value' => date("m/d/Y"),
	'meta_compare' => '>=',
	'orderby' => 'meta_value',
	'order' => 'DESC'
);
$sermon_podcast_query = new WP_Query($args);

echo '<?xml version="1.0" encoding="UTF-8"?>' ?>

<rss xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd" xmlns:atom="http://www.w3.org/2005/Atom" version="2.0">
	<channel>
		<title><?php echo esc_url( $options['podcast_title'] ) ?></title>
		<link><?php echo esc_url( $options['podcast_website_url'] ) ?></link>
		<atom:link href="<?php if ( !empty($_SERVER['HTTPS']) ) { echo 'https://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']; } else { echo 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']; } ?>" rel="self" type="application/rss+xml" />
		<language><?php echo esc_html( $options['podcast_language'] ) ?></language>
		<copyright><?php echo esc_html( $options['podcast_copyright'] ) ?></copyright>
		<itunes:subtitle><?php echo esc_html( $options['podcast_itunes_subtitle'] ) ?></itunes:subtitle>
		<itunes:author><?php echo esc_html( $options['podcast_itunes_author'] ) ?></itunes:author>
		<itunes:summary><?php echo esc_html( $options['podcast_itunes_summary'] ) ?></itunes:summary>
		<description><?php echo esc_html( $options['podcast_description'] ) ?></description>
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
<?php if ( $sermon_podcast_query->have_posts() ) : while ( $sermon_podcast_query->have_posts() ) : $sermon_podcast_query->the_post(); ?>
<?php
global $post;

$speaker = strip_tags( get_the_term_list( $post->ID, 'sermons-speaker', '', ' &amp; ', '' ) );
$series = strip_tags( get_the_term_list( $post->ID, 'sermons-category', '', ', ', '' ) );
$topic = strip_tags( get_the_term_list( $post->ID, 'sermons-tag', '', ', ', '' ) );
$topic = ( $topic ) ? sprintf( '<itunes:keywords>%s</itunes:keywords>', $topic ) : null;

$post_image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'large' );
$post_image = ( $post_image ) ? $post_image['0'] : null;

$attach_full_audio= imic_sermon_attach_full_audio(get_the_ID());
$audio_file = $attach_full_audio;
$audio_file_size = get_post_meta($post->ID, '_imic_sermon_size', 'true'); //now using custom field T Hyde 9 Oct 2013
if ($audio_file_size < 0 ) $audio_file_size = 0; //itunes needs this to be zero if undefined
$audio_duration = get_post_meta($post->ID, '_imic_sermon_duration', 'true'); // now using custom field T Hyde 9 Oct 2013
$podcast_desc = get_post_meta($post->ID, 'imic_sermons_podcast_description', 'true');
?>
<?php if ( $audio_file && $audio_duration ) : ?>
		<item>
			<title><?php the_title() ?></title>
			<link><?php the_permalink() ?></link>
			<description><?php echo esc_html( $podcast_desc ); ?></description>
			<itunes:author><?php echo esc_html( $speaker ); ?></itunes:author>
			<itunes:subtitle><?php echo esc_html( $series ); ?></itunes:subtitle>
			<itunes:summary><?php echo esc_html( $podcast_desc); ?></itunes:summary>
			<?php if ( $post_image ) : ?>
			<itunes:image href="<?php echo $post_image; ?>" />
			<?php endif; ?>
			<enclosure url="<?php echo esc_url( $audio_file ); ?>" length="<?php echo $audio_file_size; ?>" type="audio/mpeg" />
			<guid><?php echo $audio_file ?></guid>
			<pubDate><?php get_the_date('D, d M Y H:i:s O'); ?></pubDate>
			<itunes:duration><?php echo esc_html( $audio_duration ); ?></itunes:duration>
			<?php if ( $topic ) : ?>
			<?php echo esc_html( $topic) . "\n" ?>
			<?php endif; ?>
		</item>
<?php endif; ?>
<?php endwhile; endif; wp_reset_query(); ?>
	</channel>
</rss>