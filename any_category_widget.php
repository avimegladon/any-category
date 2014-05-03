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
			echo $this->getCategories($instance);
		echo $args['after_widget'];
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['wid_title'] = strip_tags( $new_instance['wid_title'] );
		$instance['taxonomy_type'] = strip_tags( $new_instance['taxonomy_type'] );
		$instance['category_to'] = strip_tags( $new_instance['category_to'] );
		$instance['category_list'] = trim(strip_tags( $new_instance['category_list'] ),',');
		return $instance;
	}


	public function form( $instance ) {
		$wid_title = $instance[ 'wid_title' ];
		$taxonomy_type = $instance[ 'taxonomy_type' ];
		$category_to = $instance[ 'category_to' ];
		$category_list = trim($instance[ 'category_list' ],',');
		?>
		<p>
          <a href="http://donateafo.net84.net/" target="_blank"><img src="https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" border="0" alt="Make a donation with PayPal"></a>
        <br />Even $0.60 Can Make A Difference
		</p>
		<p><label for="<?php echo $this->get_field_id('wid_title'); ?>"><?php _e('Title:'); ?> </label>
		<input class="widefat" id="<?php echo $this->get_field_id('wid_title'); ?>" name="<?php echo $this->get_field_name('wid_title'); ?>" type="text" value="<?php echo $wid_title; ?>" />
		</p>
		
		<p><label for="<?php echo $this->get_field_id('taxonomy_type'); ?>"><?php _e('Taxonomy:'); ?> </label>
		<select name="<?php echo $this->get_field_name('taxonomy_type'); ?>" id="<?php echo $this->get_field_id('taxonomy_type'); ?>">
		<?php
		$args = array(
		  'public'   => true,
		 // '_builtin' => false
		); 
		$taxonomies = get_taxonomies($args,'names'); 
		foreach ($taxonomies as $taxonomy ) {
			if($taxonomy == $taxonomy_type){
		  		echo '<option value="'.$taxonomy.'" selected="selected">'.$taxonomy.'</option>';
		  	} else {
				echo '<option value="'.$taxonomy.'">'.$taxonomy.'</option>';
			}
		}
		?>
		</select>
		</p>
		
		<p><label for="<?php echo $this->get_field_id('category_to'); ?>"><?php _e('Category To:'); ?> </label>
		<select name="<?php echo $this->get_field_name('category_to'); ?>" id="<?php echo $this->get_field_id('category_to'); ?>">	
			<option value="" <?php echo $category_to == ""?'selected="selected"':'' ?>>--</option>
			<option value="include" <?php echo $category_to == "include"?'selected="selected"':'' ?>>include</option>
			<option value="exclude" <?php echo $category_to == "exclude"?'selected="selected"':'' ?>>exclude</option>
		</select>&nbsp;Blank to list all categories
		</p>
		
		<p><label for="<?php echo $this->get_field_id('category_list'); ?>"><?php _e('Category List:'); ?> </label>
		<input class="widefat" id="<?php echo $this->get_field_id('category_list'); ?>" name="<?php echo $this->get_field_name('category_list'); ?>" type="text" value="<?php echo $category_list; ?>" />&nbsp;Category ids separated by comma (,) This will work only if <strong>Category To</strong> is not blank.
		</p>
		<?php 
	}
	
	public function getCategories($instance){
		
		if($instance[ 'category_to' ] == "include"){
			$include = trim($instance[ 'category_list' ],',');
			$exclude = '';
		} else if($instance[ 'category_to' ] == "exclude"){
			$include = '';
			$exclude = trim($instance[ 'category_list' ],',');
		} else {
			$include = '';
			$exclude = '';
		}
		
		$args = array(
			'show_option_all'    => '',
			'orderby'            => 'name',
			'order'              => 'ASC',
			'style'              => 'list',
			'show_count'         => 0,
			'hide_empty'         => 0,
			'use_desc_for_title' => 1,
			'exclude'            => $exclude,
			'include'            => $include,
			'hierarchical'       => 1,
			'title_li'           => __( '' ),
			'show_option_none'   => __( 'No categories' ),
			'number'             => null,
			'echo'               => 0,
			'depth'              => 0,
			'pad_counts'         => 0,
			'taxonomy'           => $instance[ 'taxonomy_type' ],
		);
		$ret .= '<ul>';
			$ret .= wp_list_categories($args);
		$ret .= '</ul>';
		return $ret;
		
	}
	
} 

add_action( 'widgets_init', create_function( '', 'register_widget( "any_category" );' ) );

?>
