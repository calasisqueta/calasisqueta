<?php
/* * * Widget code for Latest Gallery ** */
class latest_gallery extends WP_Widget {
    // constructor
    function latest_gallery() {
        $widget_ops = array('description' => __("Display latest gallery.", 'imic-framework-admin'));
        parent::__construct(false, $name = __('Latest Gallery','imic-framework-admin'), $widget_ops);
    }
    // widget form creation
    function form($instance) {
        // Check values
        if ($instance) {
            $title = esc_attr($instance['title']);
            $number = esc_attr($instance['number']);
        } else {
            $title = '';
            $number = '';
        }
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'latest_gallery'); ?></label>
            <input class="spTitle" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of gallery to show', 'imic-framework-admin'); ?></label>
            <input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" />
        </p> 
        <?php
    }
    // update widget
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        // Fields
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['number'] = strip_tags($new_instance['number']);
        return $instance;
    }
    // display widget
    function widget($args, $instance) {
        global $wp_query;
        $temp_wp_query = clone $wp_query;
        extract($args);
        // these are the widget options
        $post_title = apply_filters('widget_title', $instance['title']);
        $number = apply_filters('widget_number', $instance['number']);
        $numberPost = (!empty($number)) ? $number :6;
        echo $args['before_widget'];
      if (!empty($instance['title'])) {
           echo $args['before_title'];
            echo apply_filters('widget_title', $instance['title'], $instance, $this->id_base);
            echo $args['after_title'];
          
        }
       
        $posts = query_posts(array('order' => 'DESC', 'post_type' => 'gallery', 'posts_per_page' => $numberPost, 'post_status' => 'publish'));
        if (!empty($posts)) {
            echo '<ul>';
            foreach ($posts as $post) {
               $thumbnail_id=get_post_meta($post->ID, '_thumbnail_id','true');
                if(!empty($thumbnail_id)){
                     $large_src_i = wp_get_attachment_image_src($thumbnail_id, 'full');
                             $postImage = get_the_post_thumbnail($post->ID );
                                   echo'<li>';
								   if(isset($imic_options['switch_lightbox']) && $imic_options['switch_lightbox']== 0){
										$Lightbox_init = '<a href="'.esc_url($large_src_i[0]) .'" data-rel="prettyPhoto" class="media-box">';
									}elseif(isset($imic_options['switch_lightbox']) && $imic_options['switch_lightbox']== 1){
										$Lightbox_init = '<a href="'.esc_url($large_src_i[0]) .'" class="media-box magnific-image">';
									}
									echo $Lightbox_init;
									echo $postImage.'</a></li>';
            }}
            echo'</ul>';
        } else {
            _e('No Gallery Found','imic-framework-admin');
           }
        echo $args['after_widget'];
        $wp_query = clone $temp_wp_query;
    }
}
// register widget
add_action('widgets_init', create_function('', 'return register_widget("latest_gallery");'));
?>