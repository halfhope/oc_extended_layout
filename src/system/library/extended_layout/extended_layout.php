<?php
/**
 * @author Shashakhmetov Talgat <talgatks@gmail.com>
 */

namespace Opencart\Extension\Extended_layout\System\Library\Extended_Layout;
class Extended_layout { 
	
	public function __construct($registry) {
		$this->db       = $registry->get('db');
		$this->session  = $registry->get('session');
		$this->request  = $registry->get('request');
		$this->config   = $registry->get('config');
		$this->currency = $registry->get('currency');
		$this->weight   = $registry->get('weight');
		$this->cart     = $registry->get('cart');
		$this->customer = $registry->get('customer');
		
		$this->language_id = $this->config->get('config_language_id');
		$this->config_store_id    = $this->config->get('config_store_id');
	}
	
	public function getLayoutData($layout_id) {
		if (!isset($this->layout_data)) {
			$layout_data = $this->db->query("SELECT layout_module_id, data FROM " . DB_PREFIX . "extended_layout WHERE layout_id = " . (int) $layout_id);
			if ($layout_data->num_rows) {
				foreach ($layout_data->rows as $value) {
					parse_str($value['data'], $this->layout_data[(int) $value['layout_module_id']]);
				}
			} else {
				$this->layout_data = [];
			}
		}
		$this->layout_id = $layout_id;
		return $this->layout_data;
	}
	
	public function getProductId() {
		if (!isset($this->product_id)) {
			if (isset($this->request->get['product_id'])) {
				$this->product_id = (int) $this->request->get['product_id'];
			} else {
				$this->product_id = 0;
			}
		}
		return $this->product_id;
	}
	
	public function getInformationId() {
		if (!isset($this->information_id)) {
			if (isset($this->request->get['information_id'])) {
				$this->information_id = (int) $this->request->get['information_id'];
			} else {
				$this->information_id = 0;
			}
		}
		return $this->information_id;
	}

	public function getCategoryIds() {
		if (!isset($this->category_ids)) {
			$category_ids = [];
			$path = 0;
			if (isset($this->request->get['path'])) {
				$path = explode('_', $this->request->get['path']);

				$inclusive = true;
				if ($inclusive) {
					// add all categories from path
					foreach ($path as $key => $value) {
						$category_ids[] = (int) $value;
					}
				} else {
					// add only latest category from path
					$category_ids[] = (int) end($path);
				}
			} elseif (isset($this->request->get['category_id'])) {
				$category_ids[] = (int) $this->request->get['category_id'];
			} elseif (isset($this->request->get['product_id'])) {
				$product_id = $this->getProductId();                
				$query = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "product_to_category WHERE product_id = " . (int) $product_id);
				foreach ($query->rows as $key => $value) {
					$category_ids[] = (int) $value['category_id'];
				}
			} else {
				$category_ids = [];
			}

			$this->category_ids = $category_ids;
		}
		return $this->category_ids;
	}
	
	public function getManufacturerId() {
		if (!isset($this->manufacturer_id)) {
			$this->manufacturer_id = 0;
			if (isset($this->request->get['manufacturer_id'])) {
				$this->manufacturer_id = (int) $this->request->get['manufacturer_id'];
			} elseif (isset($this->request->get['route']) && $this->request->get['route'] == 'product/product') {
				$product_id      = $this->getProductId();
				$query           = $this->db->query("SELECT manufacturer_id FROM " . DB_PREFIX . "product WHERE product_id = " . (int) $product_id);
				$this->manufacturer_id = (int) $query->num_rows ? $query->row['manufacturer_id'] : 0;
			} else {
				$this->manufacturer_id = 0;
			}
		}
		
		return $this->manufacturer_id;
	}

	public function getCustomerGroupId() {
		if (!isset($this->customer_group_id)) {
			if ($customer_id = $this->customer->isLogged()) {
				$this->customer_group_id = (int) $this->customer->getGroupId();
			} else {
				$this->customer_group_id = 0;
			}
		}
		
		return $this->customer_group_id;
	}
	
	public function getMobile() {
		$session_cached = false;
		// cache
		if ($session_cached && isset($this->session->data['md'])) {
			$this->mobile = $this->session->data['md'];
			return $this->mobile;
		}
		
		if (!isset($this->mobile)) {
			if (!class_exists('Mobile_Detect')) {
				require_once 'mobile_detect.php';
			}

			$Mobile_Detect = new Mobile_Detect();
			
			if ($Mobile_Detect->isMobile()) {
				if ($Mobile_Detect->isTablet()) {
					$this->mobile = 1; // tablet
				} else {
					$this->mobile = 0; // mobile
				}
			} else {
				$this->mobile = 2; // desktop
			}
		}
		// cache
		if ($session_cached) {
			$this->session->data['md'] = $this->mobile;
		}
		return $this->mobile;
	}

	public function getBrowser() {
		if (!isset($this->browser)) {
			if (!class_exists('Browser')) {
				require_once 'Browser.php';
			}
			
			$Browser = new Browser(isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '');
			$this->browser = $Browser;
		}

		return $this->browser->getBrowser();
	}

	public function getPlatform() {
		if (!isset($this->browser)) {
			if (!class_exists('Browser')) {
				require_once 'Browser.php';
			}

			$Browser = new Browser(isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '');
			$this->browser = $Browser;
		}

		return $this->browser->getPlatform();
	}
	
	public function getTotal($currency) {
		if (!isset($this->cart_total)) {
			$this->cart_total = (int) $this->cart->getTotal();
			if ($currency !== $this->session->data['currency']) {
				$this->cart_total = (int) $this->currency->convert($this->cart_total, $this->session->data['currency'], $currency);
			} else {
				$this->cart_total = 0;
			}
		}
		
		return $this->cart_total;
	}
	
	public function getSubTotal($currency) {
		if (!isset($this->cart_sub_total)) {
			$this->cart_sub_total = (int) $this->cart->getSubTotal();
			if ($currency !== $this->session->data['currency']) {
				$this->cart_sub_total = (int) $this->currency->convert($this->cart_sub_total, $this->session->data['currency'], $currency);
			} else {
				$this->cart_sub_total = 0;
			}
		}
		
		return $this->cart_sub_total;
	}
	
	public function getWeight($weight_class_id) {
		if (!isset($this->cart_weight)) {
			$this->cart_weight = (int) $this->cart->getSubTotal();
			if ($weight_class_id !== $this->config->get('config_weight_class_id')) {
				$this->weight->convert($this->cart_weight, $this->config->get('config_weight_class_id'), $weight_class_id);
			} else {
				$this->cart_weight = 0;
			}
		}
		
		return $this->cart_weight;
	}

	public function getCountProducts() {
		if (!isset($this->cart_count_products)) {
			$this->cart_count_products = (int) $this->cart->countProducts();
		}
		
		return $this->cart_count_products;
	}

	public function getCurrencyId() {
		if (!isset($this->currency_id)) {
			$code = $this->session->data['currency'];
			$this->currency_id = $this->currency->getId($code);
		}
		return $this->currency_id;
	}

	public static function withinRange($value, $min, $max, $inclusive = true) {
		if ($min == 0 && $max == 0) {
			return true;
		} elseif ($min == 0) {
			return $inclusive ? ($value <= $max) : ($value < $max);
		} elseif ($max == 0) {
			return $inclusive ? ($value >= $min) : ($value > $min);
		} else {
			return $inclusive ? ($value >= $min && $value <= $max) : ($value > $min && $value < $max);
		}
	}

	public function getCustom($id) {
		if (isset($this->request->get[$id])) {
			return $this->request->get[$id];
		}
		return false;
	}

	public function filterModule($layout_module_id){
		$show = true;

		if (isset($this->layout_data[$layout_module_id])) {
			$filtter_data = $this->layout_data[$layout_module_id];
			
			$show = (boolean) $filtter_data['show'];
			
			$show = $this->filter($filtter_data) ? $show : !$show;

		}
		return $show;
	}

	public function filter($filter_data) {
		$result = true;

		foreach (array_filter($filter_data) as $key => $value) {

			switch ($key) {
				case 'category_id':       $result = !empty(array_intersect($this->getCategoryIds(), $value)); break;
				case 'product_id':        $result = in_array($this->getProductId(), array_filter($value)); break;
				case 'manufacturer_id':   $result = in_array($this->getManufacturerId(), $value); break;
				case 'information_id':    $result = in_array($this->getInformationId(), $value); break;
				
				case 'language_id':       $result = in_array($this->language_id, $value); break;
				case 'currency_id':       $result = in_array($this->getCurrencyId(), $value); break;
				case 'customer_group_id': $result = in_array($this->getCustomerGroupId(), $value); break;
				case 'store_id':          $result = in_array($this->config_store_id, $value); break;
				
				case 'mobile':            $result = in_array($this->getMobile(), $value); break;
				case 'platform':          $result = in_array($this->getPlatform(), $value); break;
				case 'browser':           $result = in_array($this->getBrowser(), $value); break;
				// e.g. blog_id
				case 'custom':            $result = in_array($this->getCustom($value['id']), explode(',', $value['values'])); break;
				
				case 'sub_total':         $result = self::withinRange($this->getSubTotal($value['currency']), $value['min'], $value['max']); break;
				case 'total':             $result = self::withinRange($this->getTotal($value['currency']), $value['min'], $value['max']); break;
				case 'weight':            $result = self::withinRange($this->getWeight($value['weight_class_id']), $value['min'], $value['max']); break;
				case 'count':             $result = self::withinRange($this->getCountProducts(), $value['min'], $value['max']); break;
			}
			
			if (!$result) {
				return $result;
			}
		}

		return $result;
	}
}
