<?php
/**
 * @author Shashakhmetov Talgat <talgatks@gmail.com>
 */

namespace Opencart\Catalog\Controller\Extension\ExtendedLayout\Module;
class ExtendedLayout extends \Opencart\System\Engine\Controller {

	public function filter(&$route, &$args, &$data) {
		
		if(!$this->registry->has('extended_layout')){
			$this->extended_layout = new \Opencart\Extension\Extended_layout\System\Library\Extended_layout\Extended_Layout($this->registry);
		}
		
		$layout_id = $args[0];
		$this->extended_layout->getLayoutData($layout_id);

		foreach ($data as $key => $module) {
			if (isset($module['layout_module_id']) && !$this->extended_layout->filterModule($module['layout_module_id'])){
				unset($data[$key]);
			}	
		}
	}	
}
