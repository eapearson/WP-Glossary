<?php
class WPG_Shortcode_Term_Usage Extends WPG {
    public function __construct () {
        add_shortcode('glossary_term_usage', array($this, 'term_usage'));
    }
    public function term_usage ($atts, $content='') {
    	global $post;
    
    	$options = get_option( 'wp_glossary', array() );
    	$reflinkopt = !isset($options['reflinkopt']) || $options['reflinkopt'] == 'on';
    	if ($reflinkopt && is_single() && get_post_type() == 'glossary') {
    		$termusage = isset($options['termusage']) ? $options['termusage'] : 'on';
    		if( $termusage == 'on' ) {
    			$usage = get_post_meta($post->ID, 'wpg_term_used');
    			if ($usage) {
    				$content    = '<div class="wpg-term-usage"><ul>';
    				foreach( $usage as $post_id ) {
    					$target   = get_post( $post_id );
    					$title    = get_the_title( $post_id );
    					$content .= '<li><a href="' . apply_filters('wpg_term_link', get_post_permalink($post_id)) . '" title="' . esc_attr($title) . '">' . $title . '</a></li>';
                    }
    				$content .= '</ul></div>';
                }
            } else {
                $content = '';
            }
    	}
    	return $content;    	 
    }
}    
