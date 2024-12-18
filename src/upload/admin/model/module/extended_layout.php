<?php 
/**
 * @author Shashakhmetov Talgat <talgatks@gmail.com>
 */

class ModelModuleExtendedLayout extends Model {

	public function install() {
		$this->db->query("CREATE TABLE IF NOT EXISTS `". DB_PREFIX ."extended_layout` ( 
			`layout_module_id` int(11) NOT NULL, 
			`layout_id` int(11) NOT NULL, 
			`data` text NOT NULL, 
			PRIMARY KEY (`layout_module_id`), 
			KEY `layout_id` (`layout_id`) 
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
	}

	public function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS `". DB_PREFIX ."extended_layout`");
	}

	public function getExtendedLayoutData($layout_module_id){
		$query  = $this->db->query("SELECT data FROM " . DB_PREFIX . "extended_layout WHERE layout_module_id = '" . (int)$layout_module_id . "'");
		if ($query->num_rows) {
			return $query->row['data'];
		}else{
			return '';
		}
	}
}

?>