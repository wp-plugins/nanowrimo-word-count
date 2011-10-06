<?php
//Style Stuff
add_action('wp_print_styles', 'add_my_stylesheet');
function add_my_stylesheet() {
	wp_register_style('nwc-style', plugins_url('nwc.css', __FILE__));
	wp_enqueue_style('nwc-style');
}

// Code for table of contents page
function nwc_table_contents() {	
    $totalcount = 0;
    $ooldcount = 0;
    $count = 0;
    
    $the_query = new WP_Query(
    array(
        'cat' => get_option('cat'),
        'post_status' => 'publish',
        )
    );
    
    // The Loop
    while ( $the_query->have_posts() ) : $the_query->the_post();
        ob_start();
        echo get_the_content();
        $content = ob_get_clean();
        $count = sizeof(explode(" ", $content));
        echo '<div class="nano_title"><a href="' . get_permalink() . '">';
       	echo the_title(); 
        echo '</a></div>';
        echo '<div class="nano_count">' . number_format($count) . '</div>';
        $totalcount = $count + $oldcount;
        $oldcount = $totalcount;
    endwhile; 
    echo '<div class="clear"></div>Total number of words ' . number_format($totalcount);
}
add_shortcode('nanowc', 'nwc_table_contents');

// This displays the word count at the end of a post in the category
function nwc_words($content) {
	$cat_id = get_option('cat');
	if (get_option('page_options') == 0 && in_category($cat_id)) {
		ob_start();
        echo get_the_content();
        $content_size = ob_get_clean();
        $count = sizeof(explode(" ", $content_size));
		$content .= '<br /><div class="nano_count">Word Count: ' . number_format($count) . '</div>';
		return $content;
	} else {
		return $content;
	};
};
add_filter ('the_content', 'nwc_words');