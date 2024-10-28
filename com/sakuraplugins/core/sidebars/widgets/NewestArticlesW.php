<?php
if ( ! defined( 'ABSPATH' ) ) exit;
require_once(plugin_dir_path(__FILE__) . 'widgets.php');
require_once(plugin_dir_path(__FILE__) . '../../config.php');

class ATLAS_NewestArticlesW extends WP_Widget {

	const DEFAULT_POST_NO = 5;

	public function __construct(){
		parent::__construct(
			'atlas_newest_updated',
			esc_html__('Atlas: Newest articles', 'atlas-knb-textdomain'),
			array(
				'classname' => 'atlas_widget',
				'description' => esc_html__('Shows newest articles', 'atlas-knb-textdomain')
			)
		);
	}

	public function form($instance) {
		$title = isset($instance['title' ]) ? $instance['title'] : esc_html__('Newest articles', 'atlas-knb-textdomain');
		$postNo = isset($instance['postNo' ]) ? $instance['postNo'] : self::DEFAULT_POST_NO;
		?>
			<p>
				<?php ATLAS_WidgetManager::widgetTextFieldHelper($this->get_field_id('title'), $this->get_field_name('title'), esc_attr($title), esc_html__('Title', 'atlas-knb-textdomain')) ?>
				<br />
				<?php ATLAS_WidgetManager::widgetTextFieldHelper($this->get_field_id('postNo'), $this->get_field_name('postNo'), esc_attr($postNo), esc_html__('Posts number', 'atlas-knb-textdomain')) ?>							
			</p>
		<?php
	}

	public function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['postNo'] = strip_tags($new_instance['postNo']);
		return $instance;		
	}	

	public function widget($args, $instance){
		extract($args);
		$title = apply_filters('widget_title', $instance['title']);
		$posts_no = isset($instance['postNo']) ? intval($instance['postNo']) : self::DEFAULT_POST_NO;
		
		echo $before_widget;
		$posts_array = get_posts(array(
			'posts_per_page' => $posts_no,
			'post_type' => ATLAS_Config::getPostType(), 
			'post_status' => 'publish',
			'orderby' => 'publish_date',
			'order' => 'DESC',
		));
		?>
			<?= $before_title . $title. $after_title; ?>
				<?php global $post; ?>
				<?php foreach( $posts_array as $post ) : setup_postdata($post); ?>
				<a class="atlas-widget-link" href="<?= get_the_permalink(); ?>"
				><i class="fas fa-sticky-note"></i><?= get_the_title(); ?>
				</a>

				<?php endforeach; ?>
			
		<?php
		
		echo $after_widget;
	}
}
?>