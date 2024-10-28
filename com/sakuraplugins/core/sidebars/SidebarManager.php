<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * manage sidebars
 */
class ATLAS_SidebarManager {
	
	static private $instance;
	private $sidebars;

	const SIDEBAR_SINGLE_ID = 'atlas-article-sidebar';
	const SIDEBAR_CATEGORY_ID = 'atlas-category-sidebar';
	const SIDEBAR_SEARCH_RESULTS_ID = 'atlas-search-results-sidebar';
	const SIDEBAR_HOME_ID = 'atlas-home-sidebar';

	function __construct() {
		$this->sidebars = array();
	}

	public function registerSidebar($args) {
		if(function_exists('register_sidebar')) {
			register_sidebar($args);
			array_push($this->sidebars, array(
					'id' => $args['id'],
					'name' => $args['name']
				)
			);
		}
	}

	//get sidebar name based on ID
	public function getSidebarName($id) {
		$name = '';
		for ($i=0; $i < sizeof($this->sidebars); $i++) { 
			if(isset($this->sidebars[$i])){
				if($this->sidebars[$i]['id'] === $id){
					$name = $this->sidebars[$i]['name'];
					break;
				}
			}
		}
		return $name;
	}

	public function getSidebarContent($sidebarId, $isHome = false) {
		if (is_active_sidebar($sidebarId)) {
			ob_start();
			dynamic_sidebar($sidebarId);
			$out = ob_get_contents();
			ob_end_clean();
			if (!$isHome) {
				return '<div class="knb-sidebar">' . $out . '</div>';
			}
			return '<div class="knb-home-sidebar">' . $out . '</div>';
		}
		return '';
	}

	public function displaySidebarContent($sidebarId) {
		?>
		<?php if (is_active_sidebar($sidebarId)): ?>
			<div id="atlas-sidebar-content" class="knb-sidebar">
				<?php dynamic_sidebar($sidebarId); ?>
			</div>
		<?php endif; ?>
		<?php
	}

	static public function getInstance() {
		if(!isset(self::$instance)){
			self::$instance = new ATLAS_SidebarManager();
		}
		return self::$instance;
	}
}

?>