<?php

/**
 * Adds RelatedPostsWidget widget.
 */

class RelatedPostsWidget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
			'wcms19-myrelatedposts', // Base ID
			'WCMS19 Related Posts', // Name
			[
				'description' => __('A Widget for displaying the latest posts', 'myrelatedposts'),
			] // Args
		);
    }
    
    /**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget($args, $instance) {
        if (!is_single()) {
            return;
        }

        extract($args);
        $title = apply_filters('widget_title', $instance['title']);

        // Start widget
        echo $before_widget;

        // Title
        if (!empty($title)) {
            echo $before_title . $title . $after_title;
        }

        // Content
        echo wrp_get_related_posts([
			'show_metadata' =>  $instance['show_metadata'],
			'categories'	=>	$instance['categories'],
            'posts'         =>  $instance['num_posts'],
            'title'         =>  false,
        ]);
		
		// close widget
		echo $after_widget;
    }

    /**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form($instance) {
        if (isset($instance['title'])) {
			$title = $instance['title'];
		} else {
			$title = __('Related Posts', 'myrelatedposts');
		}

		$categories = isset($instance['categories'])
			? $instance['categories']
			: '';

		if (isset($instance['num_posts'])) {
			$num_posts = $instance['num_posts'];
		} else {
			$num_posts = 3;
		}

		$show_metadata = isset($instance['show_metadata'])
			? $instance['show_metadata']
			: false;

		?>

		<!-- title -->
		<p>
			<label
				for="<?php echo $this->get_field_name('title'); ?>"
			>
				<?php _e('Title:'); ?>
			</label>

			<input
				class="widefat"
				id="<?php echo $this->get_field_id('title'); ?>"
				name="<?php echo $this->get_field_name('title'); ?>"
				type="text"
				value="<?php echo esc_attr($title); ?>"
			/>
		</p>
		<!-- /title -->

		<!-- categories -->
		<p>
			<label 
				for="<?php echo $this->get_field_name('categories'); ?>"
			>
				<?php _e('Categories to get related posts from:', 'wcms19-relatedposts-widget'); ?>
			</label>

			<input 
				class="widefat"
				id="<?php echo $this->get_field_id('categories'); ?>"
				name="<?php echo $this->get_field_name('categories'); ?>"
				type="text"
				placeholder="<?php _e('Category IDs (comma-separated pls)', 'wcms19-relatedposts-widget'); ?>"
				value="<?php echo $categories; ?>"
			/>
			<?php var_dump($categories); ?>
		</p>
		<!-- /categories -->

		<!-- number of posts to show -->
		<p>
			<label
				for="<?php echo $this->get_field_name('num_posts'); ?>"
			>
				<?php _e('Number of posts to show:'); ?>
			</label>

			<input
				class="widefat"
				id="<?php echo $this->get_field_id('num_posts'); ?>"
				name="<?php echo $this->get_field_name('num_posts'); ?>"
				type="number"
				min="1"
				value="<?php echo $num_posts; ?>"
			/>
		 </p>
		 <!-- /number of posts to show -->

		<!-- show metadata about post -->
		<p>
			<label
				for="<?php echo $this->get_field_name('show_metadata'); ?>"
			>
				<?php _e('Show metadata?'); ?>
			</label>

			<input
				class="widefat"
				id="<?php echo $this->get_field_id('show_metadata'); ?>"
				name="<?php echo $this->get_field_name('show_metadata'); ?>"
				type="checkbox"
				value="1"
				<?php echo $show_metadata ? 'checked="checked"' : ''; ?>
			/>
			<?php var_dump($instance['show_metadata']); ?>

		 </p>
		 <!-- /show metadata about post -->
	<?php
    }

    /**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update($new_instance, $old_instance) {
        $instance = [];

		$instance['title'] = (!empty($new_instance['title']))
			? strip_tags($new_instance['title'])
			: '';

		$instance['categories'] = (!empty($new_instance['categories']))
			? str_replace(' ', '', strip_tags($new_instance['categories']))
			: '';

		$instance['num_posts'] = (!empty($new_instance['num_posts']) && $new_instance['num_posts'] > 0)
			? intval($new_instance['num_posts'])
			: 3;

		$instance['show_metadata'] = (!empty($new_instance['show_metadata']));

		return $instance;
    }
} // Class RelatedPostsWidget