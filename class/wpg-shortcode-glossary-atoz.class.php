<?php
class WPG_Shortcode_ATOZ Extends WPG{
	function __construct() {
		add_shortcode( 'glossary_atoz', array($this, 'glossary_atoz') );
	}

	function glossary_atoz( $atts, $content='' ){
		global $post, $tcb_wpg_scripts;
    extract( shortcode_atts(array('group'=>false), $atts) );


		// Global variable that tells WP to print related js files.
		$tcb_wpg_scripts = true;
	
		$args = array(
			'post_type'           => 'glossary',
			'posts_per_page'      => '-1',
			'orderby'             => 'title',
			'order'               => 'ASC',
			'ignore_sticky_posts' => 1,
		);
	
    // Restrict list to specific glossary group or groups
    if( $group ):
      $tax_query = array(
        'taxonomy' => 'wpglossarygroup',
        'field'    => 'slug',
        'terms'    => $group,
      );
      $args['tax_query'] = array( $tax_query );
    endif;

		$list       = '<p>' . __('There are no glossary items.','wp-glossary') . '</p>';
		$glossaries = get_posts( $args );
		if( !count($glossaries) ) return $list;
	
		$atoz = array();
		foreach( $glossaries as $post ) : setup_postdata( $post );
			$href  = get_permalink();
			$title = get_the_title();
			//$alpha = strtoupper( mb_substr($post->post_title, 0, 1, 'UTF-8') );
			$alpha = strtolower( mb_substr($title, 0, 1, 'UTF-8') );
			//$alpha = strtolower( substr($title,0,1) );
	
			$item  = '<li class="glossary-item atoz-li atoz-li-' . $alpha . '">';
			$item .= '<a href="' . $href . '" title="' . esc_attr($title) . '">' . $title . '</a>';
			$item .= '</li>';
	
			$atoz[$alpha][] = $item;
		endforeach; wp_reset_postdata();
	
		$menu  = '<ul class="glossary-menu-atoz">';
		$range = apply_filters( 'wpg_atoz_range', array_keys($atoz) );
		foreach( $range as $alpha ) :
			$count = count( $atoz[$alpha] );
			$menu .= '<li class="glossary-menu-item atoz-menu-' . $alpha . ' atoz-clickable atozmenu-off" title="' . esc_attr__('Terms','wp-glossary') . ': ' . $count . '"  alpha="' . $alpha . '">';
			$menu .= '<a href="#' . $alpha . '">' . strtoupper($alpha) . '</a></li>';
		endforeach;
		$menu .= '</ul>';
	
		$list = '<div class="glossary-list-wrapper">';
		foreach( $atoz as $alpha => $items ) :
			$list .= '<ul class="glossary-list glossary-list-' . $alpha . ' atozitems-off">';
			$list .= implode( '', $items );
			$list .= '</ul>';
		endforeach;
		$list .= '</div>';
	
		$clear    = '<div style="clear: both;"></div>';
		$plsclick = apply_filters( 'wpg_please_select', '<div class="wpg-please-select"><p>Please select from the menu above</p></div>' );
		return '<div class="glossary-atoz-wrapper">' . $menu . $clear . $plsclick . $clear . $list . '</div>';
	}
}
