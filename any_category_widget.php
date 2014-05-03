<?php

class any_category extends WP_Widget {
	
	public function __construct() {
		parent::__construct(
	 		'any_category',
			'Any Category AFO',
			array( 'description' => __( 'This widget will let you choose which specific categories you want to list.', 'text_domain' ), )
		);
	 }

	public function widget( $args, $instance ) {
		extract( $args );
		
		$wid_title = apply_filters( 'widget_title', $instance['wid_title'] );
		
		echo $args['before_widget'];
		if ( ! empty( $wid_title ) )
			echo $args['before_title'] . $wid_title . $args['after_title'];
			echo $this->getCategories();
		echo $args['after_widget'];
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['wid_title'] = strip_tags( $new_instance['wid_title'] );
		return $instance;
	}


	public function form( $instance ) {
		$wid_title = $instance[ 'wid_title' ];
		?>
		<p><label for="<?php echo $this->get_field_id('wid_title'); ?>"><?php _e('Title:'); ?> </label>
		<input class="widefat" id="<?php echo $this->get_field_id('wid_title'); ?>" name="<?php echo $this->get_field_name('wid_title'); ?>" type="text" value="<?php echo $wid_title; ?>" />
		</p>
		<p><?php
		$taxonomies=get_taxonomies('','names'); 
		foreach ($taxonomies as $taxonomy ) {
		  echo '<p>'. $taxonomy. '</p>';
		}
		?>
		</p>
		<?php 
	}
	
	public function getCategories(){
	
		$args = array(
			'show_option_all'    => '',
			'orderby'            => 'name',
			'order'              => 'ASC',
			'style'              => 'list',
			'show_count'         => 0,
			'hide_empty'         => 1,
			'use_desc_for_title' => 1,
			'child_of'           => 0,
			'feed'               => '',
			'feed_type'          => '',
			'feed_image'         => '',
			'exclude'            => '',
			'exclude_tree'       => '',
			'include'            => '',
			'hierarchical'       => 1,
			'title_li'           => __( 'Categories' ),
			'show_option_none'   => __( 'No categories' ),
			'number'             => null,
			'echo'               => 0,
			'depth'              => 0,
			'current_category'   => 0,
			'pad_counts'         => 0,
			'taxonomy'           => 'category',
			'walker'             => null
		);
		$ret .= '<ul>';
			$ret .= wp_list_categories($args);
		$ret .= '</ul>';
		return $ret;
		
	}
	
} 

add_action( 'widgets_init', create_function( '', 'register_widget( "any_category" );' ) );

?>
