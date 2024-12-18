<?php
/**
 * @author Shashakhmetov Talgat <talgatks@gmail.com>
 */

namespace Opencart\Admin\Controller\Extension\ExtendedLayout\Module;
class ExtendedLayout extends \Opencart\System\Engine\Controller {
	
	public 	$_route 	= 'extension/extended_layout/module/extended_layout';
	public 	$_model 	= 'model_extension_extended_layout_module_extended_layout';
	private $_version 	= '1.2.3';
	private $_name 		= 'Extended layout';

	/**
	 * install
	 * @return void
	 **/
	public function install(): void {
		$this->load->model($this->_route);
		$this->{$this->_model}->install();
		$this->checkEvent();
	}

	public function uninstall(): void {
		$this->load->model($this->_route);
		$this->{$this->_model}->uninstall();
		$this->removeEvent();
	}

	public function index(): void {
		$this->checkEvent();
		$this->response->redirect($this->url->link('design/layout', 'user_token=' . $this->session->data['user_token'], true));
	}
	
	public function form(): void {
		$this->load->model($this->_route);
		$this->load->model('design/layout');
		$this->load->model('catalog/category');
		$this->load->model('catalog/information');
		$this->load->model('catalog/manufacturer');
		$this->load->model('catalog/product');
		$this->load->model('localisation/currency');
		$this->load->model('localisation/weight_class');
		$this->load->model('localisation/language');
		$this->load->model('setting/store');
		$this->load->model('customer/customer_group');

		$data = [];
		$data += $this->load->language($this->_route);

		// prepare data for form
		$data['module_row'] = (int) $this->request->post['module_row'];
		$data['layout_id']  = isset($this->request->get['layout_id']) ? (int) $this->request->get['layout_id'] : 0;
		
		$data['languages']      = $this->model_localisation_language->getLanguages();
		$data['currencies']     = $this->model_localisation_currency->getCurrencies();
		$data['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses();
		$data['categories']     = $this->model_catalog_category->getCategories();
		$data['informations']   = $this->model_catalog_information->getInformations();
		$data['manufacturers']  = $this->model_catalog_manufacturer->getManufacturers();
		$data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();

		$data['stores'] = [];
		
		$data['stores'][] = [
			'store_id' => 0,
			'name' => $this->config->get('config_name') . $this->language->get('text_default')
		];
		
		$results = $this->model_setting_store->getStores();
		
		foreach ($results as $result) {
			$data['stores'][] = [
				'store_id' => $result['store_id'],
				'name' => $result['name']
			];
		}
		
		$data['customer_groups'][] = [
			'name' => $this->language->get('text_unregistered'),
			'customer_group_id' => null
		];
				
		$data['mobile'] = [
			0 => $this->language->get('filter_mobile1'),
			1 => $this->language->get('filter_mobile2'),
			2 => $this->language->get('filter_mobile3')
		];

		// https://github.com/cbschuld/Browser.php
		$data['platforms'] = [
			'unknown', 'Windows', 'Windows CE', 'Apple', 'Linux', 'OS/2',
			'BeOS', 'iPhone', 'iPod', 'iPad', 'BlackBerry', 'Nokia',
			'FreeBSD', 'OpenBSD', 'NetBSD', 'SunOS', 'OpenSolaris', 'Android',
			'Sony PlayStation', 'Roku', 'Apple TV', 'Terminal', 'Fire OS', 'SMART-TV',
			'Chrome OS', 'Java/Android', 'Postman', 'Iframely'
		];

		// https://github.com/cbschuld/Browser.php
		$data['browsers'] = [
			'unknown', 'Opera', 'Opera Mini', 'WebTV', 'Edge', 'Internet Explorer', 'Pocket Internet Explorer', 'Konqueror',
			'iCab', 'OmniWeb', 'Firebird', 'Firefox', 'Brave', 'Palemoon', 'Iceweasel', 'Shiretoko', 'Mozilla', 'Amaya', 'Lynx', 'Safari',
			'iPhone', 'iPod', 'iPad', 'Chrome', 'Android', 'GoogleBot', 'cURL', 'Wget', 'UCBrowser', 'YandexBot', 'YandexImageResizer', 'YandexImages',
			'YandexVideo', 'YandexMedia', 'YandexBlogs', 'YandexFavicons', 'YandexWebmaster', 'YandexDirect', 'YandexMetrika', 
			'YandexNews', 'YandexCatalog', 'Yahoo! Slurp', 'W3C Validator', 'BlackBerry', 'IceCat', 'Nokia S60 OSS Browser', 'Nokia Browser', 
			'MSN Browser', 'MSN Bot', 'Bing Bot', 'Vivaldi', 'Yandex', 'Netscape Navigator', 'Galeon', 'NetPositive', 'Phoenix',
			'PlayStation', 'SamsungBrowser', 'Silk', 'Iframely', 'CocoaRestClient'
		];

		$layout_routes = $this->model_design_layout->getRoutes($data['layout_id']);
		
		$route = 'default';
		foreach ($layout_routes as $value) {
			$route = $value['route'];
		}

		switch ($route) {
			case 'product/product':
				$data['filters'] = [
					1 => $this->language->get('filter_type2'),
					2 => $this->language->get('filter_type5'),
					3 => $this->language->get('filter_type6')
				];
				$data['layout_type'] = 'product';
				break;
			case 'product/category':
				$data['filters'] = [
					4 => $this->language->get('filter_type1')
				];
				$data['layout_type'] = 'category';
				break;
			case 'product/manufacturer/info':
				$data['filters'] = [
					5 => $this->language->get('filter_type4')
				];
				$data['layout_type'] = 'manufacturer';
				break;
			case 'information/information':
				$data['filters'] = [
					6 => $this->language->get('filter_type3')
				];
				$data['layout_type'] = 'information';
				break;
			default:
				$data['filters'] = [
					7 => $this->language->get('filter_type0')
				];
				$data['layout_type'] = 'default';
				break;
		}

		// process form data
		$settings = [];
		
		if (isset($this->request->post['extended_layout'])) {
			parse_str(html_entity_decode($this->request->post['extended_layout']), $settings);
			if (empty($settings)) {
				$settings = [];
			}
		}

		if (isset($settings['product_id'])) {
			$products = $settings['product_id'];
		} else {
			$products = [];
		}
		
		$settings['products_parsed'] = [];
		
		foreach ($products as $product_id) {
			$product_info = $this->model_catalog_product->getProduct($product_id);
			
			if ($product_info) {
				$settings['products_parsed'][] = [
					'id' => $product_info['product_id'],
					'name' => html_entity_decode($product_info['name']),
					'selected' => true
				];
			}
		}

		$data['settings'] = $settings;
		
		$data['user_token'] = $this->session->data['user_token'];

		$this->response->setOutput($this->load->view($this->_route, $data));
	}
	
	public function explain(): void {
		$this->load->model($this->_route);
		$this->load->model('design/layout');
		$this->load->model('catalog/category');
		$this->load->model('catalog/information');
		$this->load->model('catalog/manufacturer');
		$this->load->model('catalog/product');
		$this->load->model('localisation/currency');
		$this->load->model('localisation/weight_class');
		$this->load->model('localisation/language');
		$this->load->model('setting/store');
		$this->load->model('customer/customer_group');

		$settings = $this->request->post;
		
		$settings['show'] = isset($settings['show']) ? $settings['show'] : 1;
		$settings['type'] = isset($settings['type']) ? $settings['type'] : 0;

		$this->load->language($this->_route);
		
		$response = [];
		if ((int) $settings['show'] == 1) {
			$response[] = $this->language->get('text_output_show');
		} else {
			$response[] = $this->language->get('text_output_hide');
		}

		switch ($settings['type']) {
			case 1:
				if (isset($settings['product_id'])) {
					$products = $settings['product_id'];
				} else {
					$products = [];
				}
				
				if ($products) {
					$response[] = $this->language->get('text_output_products');
					foreach ($products as $product_id) {
						$product_info = $this->model_catalog_product->getProduct($product_id);
						if ($product_info) {
							$response[] = '<span class="badge bg-secondary">' . $product_info['name'] . '</span>';
						}
					}
				}
				break;
			case 2:
			case 4:
				if (isset($settings['category_id'])) {
					$response[] = $this->language->get('text_output_categories');
					foreach ($settings['category_id'] as $category_id) {
						$category_info = $this->model_catalog_category->getCategory($category_id);
						if ($category_info) {
							$response[] = '<span class="badge bg-secondary">' . $category_info['name'] . '</span>';
						}
					}
				}
				break;
			case 3:
			case 5:
				if (isset($settings['manufacturer_id'])) {
					$response[] = $this->language->get('text_output_manufacturers');
					foreach ($settings['manufacturer_id'] as $manufacturer_id) {
						$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($manufacturer_id);
						if ($manufacturer_info) {
							$response[] = '<span class="badge bg-secondary">' . $manufacturer_info['name'] . '</span>';
						}
					}
				}
				break;
			case 6:
				if (isset($settings['information_id'])) {
					if ($settings['information_id']) {
						$response[] = $this->language->get('text_output_informations');
						foreach ($settings['information_id'] as $information_id) {
							$information_info = $this->model_catalog_information->getInformationDescriptions($information_id);
							
							if ($information_info) {
								$information = end($information_info);
								$response[]  = '<span class="badge bg-secondary">' . $information['title'] . '</span>';
							}
						}
					}
				}
				break;
			default:
				break;
		}
		if (isset($settings['mobile'])) {
			if ($settings['mobile']) {
				$response[] = $this->language->get('text_output_mobile');
				foreach ($settings['mobile'] as $mobile_id) {
					$mobile     = [
						0 => $this->language->get('filter_mobile1'),
						1 => $this->language->get('filter_mobile2'),
						2 => $this->language->get('filter_mobile3')
					];
					$response[] = '<span class="badge bg-secondary">' . $mobile[$mobile_id] . '</span>';
				}
			}
		}
		if (isset($settings['language_id'])) {
			if ($settings['language_id']) {
				$response[] = $this->language->get('text_output_languages');
				foreach ($settings['language_id'] as $language_id) {
					$language_info = $this->model_localisation_language->getLanguage($language_id);
					if ($language_info) {
						$response[] = '<span class="badge bg-secondary">' . $language_info['name'] . '</span>';
					}
				}
			}
		}
		if (isset($settings['currency_id'])) {
			if ($settings['currency_id']) {
				$response[] = $this->language->get('text_output_currencies');
				foreach ($settings['currency_id'] as $currency_id) {
					$currency_info = $this->model_localisation_currency->getCurrency($currency_id);
					if ($currency_info) {
						$response[] = '<span class="badge bg-secondary">' . $currency_info['title'] . '</span>';
					}
				}
			}
		}
		if (isset($settings['customer_group_id'])) {
			if ($settings['customer_group_id']) {
				$response[] = $this->language->get('text_output_customer_groups');
				foreach ($settings['customer_group_id'] as $customer_group_id) {
					if ($customer_group_id == null) {
						$response[] = '<span class="badge bg-secondary">' . $this->language->get('text_unregistered') . '</span>';
					} else {
						$customer_group_info = $this->model_customer_customer_group->getCustomerGroup($customer_group_id);
						if ($customer_group_info) {
							$response[] = '<span class="badge bg-secondary">' . $customer_group_info['name'] . '</span>';
						}
					}
				}
			}
		}
		if (isset($settings['store_id'])) {
			if ($settings['store_id']) {
				$response[] = $this->language->get('text_output_stores');
				foreach ($settings['store_id'] as $store_id) {
					if ($store_id == 0) {
						$store_info = [
							'store_id' => 0,
							'name' => $this->config->get('config_name') . $this->language->get('text_default')
						];
					} else {
						$store_info = $this->model_setting_store->getStore($store_id);
					}
					
					if ($store_info) {
						$response[] = '<span class="badge bg-secondary">' . $store_info['name'] . '</span>';
					}
				}
			}
		}
		if (isset($settings['sub_total'])) {
			if ($settings['sub_total']) {
				$currency = $this->model_localisation_currency->getCurrency($settings['sub_total']['currency_id']);
				$response[] = sprintf($this->language->get('text_output_sub_total'), $settings['sub_total']['min'], $settings['sub_total']['max'], $currency['title']);
			}
		}
		
		if (isset($settings['total'])) {
			if ($settings['total']) {
				$currency = $this->model_localisation_currency->getCurrency($settings['total']['currency_id']);
				$response[] = sprintf($this->language->get('text_output_total'), $settings['total']['min'], $settings['total']['max'], $currency['title']);
			}
		}

		if (isset($settings['weight'])) {
			if ($settings['weight']) {
				$currency = $this->model_localisation_weight_class->getWeightClass($settings['weight']['weight_class_id']);
				$response[] = sprintf($this->language->get('text_output_weight'), $settings['weight']['min'], $settings['weight']['max'], $currency['title']);
			}
		}

		if (isset($settings['custom'])) {
			if ($settings['custom']) {
				$response[] = sprintf($this->language->get('text_output_custom'), $settings['custom']['id'], $settings['custom']['values']);
			}
		}

		if (isset($settings['platform'])) {
			if ($settings['platform']) {
				$response[] = $this->language->get('text_output_platform');
				foreach ($settings['platform'] as $platform) {					
					$response[] = '<span class="badge bg-secondary">' . $platform . '</span>';
				}
			}
		}

		if (isset($settings['browser'])) {
			if ($settings['browser']) {
				$response[] = $this->language->get('text_output_browser');
				foreach ($settings['browser'] as $browser) {					
					$response[] = '<span class="badge bg-secondary">' . $browser . '</span>';
				}
			}
		}
		
		if (isset($settings['count'])) {
			if ($settings['count']) {
				$response[] = sprintf($this->language->get('text_output_count_products'), $settings['count']['min'], $settings['count']['max']);
			}
		}

		$json = [];
		
		if (isset($settings['module_row'])) {
			$json['module_row'] = $settings['module_row'];
		}
		
		$json['description'] = implode(' ', $response);
		if (count($response) == 1) {
			$json['description'] .= ' *';
		}
		
		$this->response->addHeader('Content-type:application/json;charset=utf-8');
		$this->response->setOutput(json_encode($json));
	}

	public function product_autocomplete(): void {
		
		$json = [];
		
		if (isset($this->request->get['term'])) {
			
			$this->load->model('catalog/product');
			
			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 5;
			}
			
			$filter_data = [
				'filter_name' => $this->request->get['term'],
				'filter_model' => '',
				'start' => 0,
				'limit' => $limit
			];
			
			$results = $this->model_catalog_product->getProducts($filter_data);
			
			foreach ($results as $result) {
				$json['results'][] = [
					'id' => (int) $result['product_id'],
					'text' => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
				];
			}
		}
		
		$this->response->addHeader('Content-type:application/json;charset=utf-8');
		$this->response->setOutput(json_encode($json));
	}

	private function addEditDeleteExtendedLayoutModule($layout_id, $layout_data): void {
		$this->load->model($this->_route);
		$this->load->model('design/layout');
		
		$hash_table = [];
		$layout_module = $this->model_design_layout->getModules($layout_id);
		foreach ($layout_module as $module) {
			extract($module);
			$key = $code . '_' . $position . '_' . $sort_order;
			$hash_table[$key] = $layout_module_id;
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "extended_layout WHERE layout_id = '" . (int)$layout_id . "'");

		if ($layout_data) {
			foreach ($layout_data['layout_module'] as $module) {
				if (isset($module['extended_layout']) && !empty($module['extended_layout'])) {
					extract($module);
					$key = $code . '_' . $position . '_' . $sort_order;
					$layout_module_id = $hash_table[$key];
					$this->db->query("INSERT INTO " . DB_PREFIX . "extended_layout SET layout_id = '" . (int)$layout_id . "', layout_module_id = '" . (int)$layout_module_id . "', data = '" . $this->db->escape(htmlspecialchars_decode($extended_layout)) . "'");
				}
			}
		}
	}

	public function eventViewDesignLayoutEditBefore(&$route, &$args): void {
		$this->document->addStyle('../extension/extended_layout/admin/view/stylesheet/extended_layout.css?v=' . $this->_version);
		$this->document->addScript('../extension/extended_layout/admin/view/javascript/extended_layout.js?v=' . $this->_version);
	}

	public function eventViewDesignLayoutFormAfter(&$route, &$args, &$tpl) {
		$this->load->model($this->_route);
		$this->load->model('design/layout');
		
		$data = [];
		$data += $this->load->language($this->_route);

		$hash_table = [];
		if (isset($this->request->get['layout_id'])) {
			$layout_module = $this->model_design_layout->getModules($this->request->get['layout_id']);
			foreach ($layout_module as $module) {
				extract($module);
				$key = $code . '_' . $position . '_' . $sort_order;
				$hash_table[$key] = $this->{$this->_model}->getExtendedLayoutData($layout_module_id);
			}
		}

		if(isset($this->request->get['layout_id'])){
			$layout_id_param = '&layout_id=' . $this->request->get['layout_id'];
		} else {
			$layout_id_param = '';
		}
		
		$extended_layout_data = json_encode(
			[
				'links' => [
					'explain' => htmlspecialchars_decode($this->url->link($this->_route . '|explain', 'user_token=' . $this->session->data['user_token'] . $layout_id_param, 'SSL')),
					'form' => htmlspecialchars_decode($this->url->link($this->_route . '|form', 'user_token=' . $this->session->data['user_token'] . $layout_id_param, 'SSL')),
					'product_autocomplete' => htmlspecialchars_decode($this->url->link($this->_route . '|product_autocomplete', 'user_token=' . $this->session->data['user_token'] . $layout_id_param, 'SSL')),
				],
				'lang' => [
					'text_form_select' 		=> $data['text_form_select'],
					'text_form_edit' 		=> $data['text_form_edit'],
					'text_output_show' 		=> $data['text_output_show'],
					'text_form_autocomplete' => $data['text_form_autocomplete'],
					'text_form_close' 		=> $data['text_form_close'],
					'text_form_title' 		=> $data['text_form_title'] . '<span class="pull-right">v' . $this->_version . '&nbsp;</span>',
					'text_form_clear' 		=> $data['text_form_clear'],
					'text_form_save' 		=> $data['text_form_save']
				],
				'hash_table' => $hash_table
			]
		);

		// provide data for javascript
		$common_tpl = "<script>var extended_layout_data = {$extended_layout_data};</script>";
		$replace = '<footer id="footer"';
		$tpl = str_replace($replace, PHP_EOL . $common_tpl . PHP_EOL . $replace, $tpl);

		// js addModule append
		$add_module_tpl = "\thtml += addExtendedLayoutModule(module_row);";
		$replace = "$('#module-' + type + ' tbody').append(html);";
		$tpl = str_replace($replace, PHP_EOL . $add_module_tpl . PHP_EOL . $replace, $tpl);

		// js compatible remove module for runtime
		$remove_module_tpl = "$(\'#el-row' + module_row + '\').remove();";
		$replace = "$(\'#module-row' + module_row + '\').remove();";
		$tpl = str_replace($replace, $remove_module_tpl . $replace, $tpl);

		// js compatible remove module for existed
		$remove_module_tpl = "/\\$\('#module-row-(\d)*'\).remove\(\);/im";
		$replace = "$('#module-row-\$1').remove();$('#el-row\$1').remove();";
		$tpl = preg_replace($remove_module_tpl, $replace, $tpl);
	}
	// crud
	public function eventModelDesignLayoutAddLayoutAfter(&$route, &$args, &$result): void {
		$layout_id = $result;
		$layout_data = $args[0];
		$this->addEditDeleteExtendedLayoutModule($layout_id, $layout_data);		
	}
	
	public function eventModelDesignLayoutEditLayoutAfter(&$route, &$args, &$result): void {
		$layout_id = $args[0];
		$layout_data = $args[1];
		$this->addEditDeleteExtendedLayoutModule($layout_id, $layout_data);
	}

	public function eventModelDesignLayoutDeleteLayoutAfter(&$route, &$args, &$data): void {
		$layout_id = $args[0];
		$layout_data = [];
		$this->addEditDeleteExtendedLayoutModule($layout_id, $layout_data);
	}

	private $_events = [
		[
			'code'		=> 'extended_layout_edit_before',
			'description'		=> '',
			'trigger'	=> 'admin/controller/design/layout|form/before',
			'action'	=> '|eventViewDesignLayoutEditBefore'
		],
		[
			'code'		=> 'extended_layout_form_view_after',
			'description'		=> '',
			'trigger'	=> 'admin/view/design/layout_form/after',
			'action'	=> '|eventViewDesignLayoutFormAfter'
		],
		// crud
		[
			'code'		=> 'extended_layout_add_layout',
			'description'		=> '',
			'trigger'	=> 'admin/model/design/layout/addLayout/after',
			'action'	=> '|eventModelDesignLayoutAddLayoutAfter'
		],
		[
			'code'		=> 'extended_layout_edit_layout',
			'description'		=> '',
			'trigger'	=> 'admin/model/design/layout/editLayout/after',
			'action'	=> '|eventModelDesignLayoutEditLayoutAfter'
		],
		[
			'code'		=> 'extended_layout_delete_layout',
			'description'		=> '',
			'trigger'	=> 'admin/model/design/layout/deleteLayout/after',
			'action'	=> '|eventModelDesignLayoutDeleteLayoutAfter'
		],
		// catalog filter event
		[
			'code'		=> 'extended_layout_filter_module',
			'description'		=> '',
			'trigger'	=> 'catalog/model/design/layout/getModules/after',
			'action'	=> '|filter'
		]
	];

	private function checkEvent(): void {
		$this->load->model('setting/event');

		foreach($this->_events as $event) {
			if(!$result = $this->model_setting_event->getEventByCode($event['code'])) {

				$event['action'] = $this->_route . $event['action'];
				$event['status'] = 1;
				$event['sort_order'] = 1;

				$this->model_setting_event->addEvent($event);
			}
		}
	}

	private function removeEvent(): void {
		$this->load->model('setting/event');

		foreach($this->_events as $event) {
			$this->model_setting_event->deleteEventByCode($event['code']);
		}
	}
}
