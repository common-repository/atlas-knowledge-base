<?php
if ( ! defined( 'ABSPATH' ) ) exit;
require_once(plugin_dir_path(__FILE__) . 'widgets.php');
require_once(plugin_dir_path(__FILE__) . '../../config.php');
require_once(plugin_dir_path(__FILE__) . '../../utils/SessionUtils.php');


class ATLAS_RecentlyViewedW extends WP_Widget {

	public function __construct() {
		parent::__construct(
			'atlas_recent_viewd',
			esc_html__('Atlas: Recently viewed', 'atlas-knb-textdomain'),
			array(
				'classname' => 'atlas_widget',
				'description' => esc_html__('Shows recently viewed articles. Note! This widget displays the latest 5 articles and uses the session.', 'atlas-knb-textdomain')
			)
		);
	}

	public function form($instance) {
		$title = isset($instance['title' ]) ? $instance['title'] : esc_html__('Recently viewed', 'atlas-knb-textdomain');
		?>
			<p>
				<?php ATLAS_WidgetManager::widgetTextFieldHelper($this->get_field_id('title'), $this->get_field_name('title'), esc_attr($title), esc_html__('Title', 'atlas-knb-textdomain')) ?>
			</p>
		<?php
	}

	public function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		return $instance;		
	}	

	public function widget($args, $instance){
		extract($args);
        $title = apply_filters('widget_title', $instance['title']);
        
        $recentlyViewedIds = ATLAS_SessionUtils::getInstance()->getViewedArticles();

        if (sizeof($recentlyViewedIds) === 0) {
            return;
        }

        echo $before_widget;
        
		$posts_array = get_posts(array(
			'posts_per_page' => 5,
			'post_type' => ATLAS_Config::getPostType(),
            'post_status' => 'publish',
            'post__in' => array_unique($recentlyViewedIds),
		));

		if (sizeof($posts_array) === 0) {
			return;
		}

		$parsedPosts = array();
		foreach ($recentlyViewedIds as $id) {
			foreach ($posts_array as $p) {
				if ($p->ID === $id) {
					array_push($parsedPosts, $p);
				}
			}
		}
        
		?>
			<?= $before_title . $title. $after_title; ?>
				<?php global $post; ?>
				<?php foreach( $parsedPosts as $post ) : setup_postdata($post); ?>
				<a class="atlas-widget-link" href="<?= get_the_permalink(); ?>"
				><i class="fas fa-sticky-note"></i><?= get_the_title(); ?>
				</a>

				<?php endforeach; ?>
			
		<?php
		
		echo $after_widget;
	}
}
?>