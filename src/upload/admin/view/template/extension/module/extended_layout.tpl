<!-- show start -->
<input type="hidden" name="module_row" value="<?php echo $module_row ?>">

<div class="form-group form-group-sm sshow">
  <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $entry_show_help; ?>"><?php echo $entry_show; ?></span></label>
  <div class="col-sm-10">
    <?php if (isset($settings['show'])): ?>
    <label class="radio-inline">
      <?php if ($settings['show']) { ?>
      <input type="radio" name="show" value="1" checked="checked" />
      <?php echo $filter_show; ?>
      <?php } else { ?>
      <input type="radio" name="show" value="1" />
      <?php echo $filter_show; ?>
      <?php } ?>
    </label>
    <label class="radio-inline">
      <?php if (!$settings['show']) { ?>
      <input type="radio" name="show" value="0" checked="checked" />
      <?php echo $filter_hide; ?>
      <?php } else { ?>
      <input type="radio" name="show" value="0" />
      <?php echo $filter_hide; ?>
      <?php } ?>
    </label>
    <?php else: ?>
    <label class="radio-inline">
      <input type="radio" name="show" value="1" checked="checked" />
      <?php echo $filter_show; ?>
    </label>
    <label class="radio-inline">
      <input type="radio" name="show" value="0" />
      <?php echo $filter_hide; ?>
    </label>
    <?php endif ?>
  </div>
</div>
<!-- show end -->
<!-- sub filters choose start -->
<?php if (count($filters) <= 1): ?>
<input type="hidden" name="type" value="<?php echo key($filters) ?>">
<?php else: ?>
<div class="form-group form-group-sm">
  <label class="col-sm-2 control-label" for="type"><?php echo $entry_filter_type; ?></label>
  <div class="col-sm-10">
    <select class="form-control select_type" name="type" id="type"<?php echo (count($filters)==1) ? ' disabled' : ''; ?>>
      <?php foreach ($filters as $type_id => $type_caption): ?>
        <?php if (isset($settings['type']) && ($type_id == $settings['type'] || count($filters) == 1)){ ?>
          <option value="<?php echo $type_id ?>" selected="selected"><?php echo $type_caption ?></option>
        <?php } else { ?>
          <option value="<?php echo $type_id ?>"><?php echo $type_caption ?></option>
        <?php } ?>
      <?php endforeach ?>
    </select>
  </div>
</div>
<?php endif ?>
<!-- sub filters choose end -->
<!-- sub filters content start -->
<?php if ($layout_type == 'product'): ?>
<!-- product start -->
<div class="form-group form-group-sm sproducts">
  <label class="col-sm-2 control-label" for="input-search"><span data-toggle="tooltip" title="<?php echo $entry_product_help; ?>"><?php echo $entry_product; ?></span></span></label>
  <div class="col-sm-10">
    <input type="text" name="products_search" value="" placeholder="<?php echo $text_form_autocomplete; ?>" id="input-search" class="form-control" />
    <div id="product_id" class="well well-sm" style="height: 150px; overflow: auto;">
      <?php foreach ($settings['products_parsed'] as $product) { ?>
      <div id="product_id<?php echo $product['id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $product['name']; ?>
        <input type="hidden" name="product_id[]" value="<?php echo $product['id']; ?>" />
      </div>
      <?php } ?>
    </div>
  </div>
</div>

<div class="form-group form-group-sm sproduct_categories">
  <label class="col-sm-2 control-label"><?php echo $entry_category; ?></label>
  <div class="col-sm-10">
    <div class="well well-sm" style="max-height: 250px; overflow: auto;">
      <?php foreach ($categories as $category_key => $category_data) { ?>
      <div class="checkbox">
        <label>
          <?php if (isset($settings['category_id']) && in_array($category_data['category_id'], $settings['category_id'])) { ?>
          <input type="checkbox" name="category_id[<?php echo $category_data['category_id']; ?>]" id="category_id[<?php echo $category_data['category_id']; ?>]" value="<?php echo $category_data['category_id']; ?>" checked="checked" />
          <?php echo $category_data['name']; ?>
          <?php } else { ?>
          <input type="checkbox" name="category_id[<?php echo $category_data['category_id']; ?>]" id="category_id[<?php echo $category_data['category_id']; ?>]" value="<?php echo $category_data['category_id']; ?>" />
          <?php echo $category_data['name']; ?>
          <?php } ?>
        </label>
      </div>
      <?php } ?>
    </div>
    <a href="#" onclick="$(this).parent().find(':checkbox').prop('checked', true);return false;"><?php echo $text_select_all; ?></a> / <a href="#" onclick="$(this).parent().find(':checkbox').prop('checked', false);return false;"><?php echo $text_unselect_all; ?></a> <span class="pull-right"><?php echo $entry_mobile_desc ?></span>
  </div>
</div>

<div class="form-group form-group-sm sproduct_manufacturers">
  <label class="col-sm-2 control-label"><?php echo $entry_manufacturer; ?></label>
  <div class="col-sm-10">
    <div class="well well-sm" style="max-height: 250px; overflow: auto;">
      <?php foreach ($manufacturers as $manufacturer_key => $manufacturer_data) { ?>
      <div class="checkbox">
        <label>
          <?php if (isset($settings['manufacturer_id']) && in_array($manufacturer_data['manufacturer_id'], $settings['manufacturer_id'])) { ?>
          <input type="checkbox" name="manufacturer_id[<?php echo $manufacturer_data['manufacturer_id']; ?>]" id="manufacturer_id[<?php echo $manufacturer_data['manufacturer_id']; ?>]" value="<?php echo $manufacturer_data['manufacturer_id']; ?>" checked="checked" />
          <?php echo $manufacturer_data['name']; ?>
          <?php } else { ?>
          <input type="checkbox" name="manufacturer_id[<?php echo $manufacturer_data['manufacturer_id']; ?>]" id="manufacturer_id[<?php echo $manufacturer_data['manufacturer_id']; ?>]" value="<?php echo $manufacturer_data['manufacturer_id']; ?>" />
          <?php echo $manufacturer_data['name']; ?>
          <?php } ?>
        </label>
      </div>
      <?php } ?>
    </div>
    <a href="#" onclick="$(this).parent().find(':checkbox').prop('checked', true);return false;"><?php echo $text_select_all; ?></a> / <a href="#" onclick="$(this).parent().find(':checkbox').prop('checked', false);return false;"><?php echo $text_unselect_all; ?></a> <span class="pull-right"><?php echo $entry_mobile_desc ?></span>
  </div>
</div>
<!-- product end -->
<?php endif ?>
<?php if ($layout_type == 'category'): ?>
<!-- category start -->
<div class="form-group form-group-sm scategories">
  <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $entry_category_help; ?>"><?php echo $entry_category; ?></span></label>
  <div class="col-sm-10">
    <div class="well well-sm" style="max-height: 250px; overflow: auto;">
      <?php foreach ($categories as $category_key => $category_data) { ?>
      <div class="checkbox">
        <label>
          <?php if (isset($settings['category_id']) && in_array($category_data['category_id'], $settings['category_id'])) { ?>
          <input type="checkbox" name="category_id[<?php echo $category_data['category_id']; ?>]" id="category_id[<?php echo $category_data['category_id']; ?>]" value="<?php echo $category_data['category_id']; ?>" checked="checked" />
          <?php echo $category_data['name']; ?>
          <?php } else { ?>
          <input type="checkbox" name="category_id[<?php echo $category_data['category_id']; ?>]" id="category_id[<?php echo $category_data['category_id']; ?>]" value="<?php echo $category_data['category_id']; ?>" />
          <?php echo $category_data['name']; ?>
          <?php } ?>
        </label>
      </div>
      <?php } ?>
    </div>
    <a href="#" onclick="$(this).parent().find(':checkbox').prop('checked', true);return false;"><?php echo $text_select_all; ?></a> / <a href="#" onclick="$(this).parent().find(':checkbox').prop('checked', false);return false;"><?php echo $text_unselect_all; ?></a> <span class="pull-right"><?php echo $entry_mobile_desc ?></span>
  </div>
</div>
<!-- category end -->
<?php endif ?>
<?php if ($layout_type == 'manufacturer'): ?>
<!-- manufacturer start -->
<div class="form-group form-group-sm smanufacturers">
  <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $entry_manufacturer_help; ?>"><?php echo $entry_manufacturer; ?></span></label>
  <div class="col-sm-10">
    <div class="well well-sm" style="max-height: 250px; overflow: auto;">
      <?php foreach ($manufacturers as $manufacturer_key => $manufacturer_data) { ?>
      <div class="checkbox">
        <label>
          <?php if (isset($settings['manufacturer_id']) && in_array($manufacturer_data['manufacturer_id'], $settings['manufacturer_id'])) { ?>
          <input type="checkbox" name="manufacturer_id[<?php echo $manufacturer_data['manufacturer_id']; ?>]" id="manufacturer_id[<?php echo $manufacturer_data['manufacturer_id']; ?>]" value="<?php echo $manufacturer_data['manufacturer_id']; ?>" checked="checked" />
          <?php echo $manufacturer_data['name']; ?>
          <?php } else { ?>
          <input type="checkbox" name="manufacturer_id[<?php echo $manufacturer_data['manufacturer_id']; ?>]" id="manufacturer_id[<?php echo $manufacturer_data['manufacturer_id']; ?>]" value="<?php echo $manufacturer_data['manufacturer_id']; ?>" />
          <?php echo $manufacturer_data['name']; ?>
          <?php } ?>
        </label>
      </div>
      <?php } ?>
    </div>
    <a href="#" onclick="$(this).parent().find(':checkbox').prop('checked', true);return false;"><?php echo $text_select_all; ?></a> / <a href="#" onclick="$(this).parent().find(':checkbox').prop('checked', false);return false;"><?php echo $text_unselect_all; ?></a> <span class="pull-right"><?php echo $entry_mobile_desc ?></span>
  </div>
</div>
<!-- manufacturer end -->
<?php endif ?>
<?php if ($layout_type == 'information'): ?>
<!-- information start -->
<div class="form-group form-group-sm sinformations">
  <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $entry_information_help; ?>"><?php echo $entry_information; ?></span></label>
  <div class="col-sm-10">
    <div class="well well-sm" style="max-height: 250px; overflow: auto;">
      <?php foreach ($informations as $information_key => $information_data) { ?>
      <div class="checkbox">
        <label>
          <?php if (isset($settings['information_id']) && in_array($information_data['information_id'], $settings['information_id'])) { ?>
          <input type="checkbox" name="information_id[<?php echo $information_data['information_id']; ?>]" id="information_id[<?php echo $information_data['information_id']; ?>]" value="<?php echo $information_data['information_id']; ?>" checked="checked" />
          <?php echo $information_data['title']; ?>
          <?php } else { ?>
          <input type="checkbox" name="information_id[<?php echo $information_data['information_id']; ?>]" id="information_id[<?php echo $information_data['information_id']; ?>]" value="<?php echo $information_data['information_id']; ?>" />
          <?php echo $information_data['title']; ?>
          <?php } ?>
        </label>
      </div>
      <?php } ?>
    </div>
    <a href="#" onclick="$(this).parent().find(':checkbox').prop('checked', true);return false;"><?php echo $text_select_all; ?></a> / <a href="#" onclick="$(this).parent().find(':checkbox').prop('checked', false);return false;"><?php echo $text_unselect_all; ?></a> <span class="pull-right"><?php echo $entry_mobile_desc ?></span>
    </div>
</div>
<!-- information end -->
<?php endif ?>
<!-- sub filters content end -->
<!-- default start -->
<div class="form-group form-group-sm suseragent module<?php echo $layout_id ?> common<?php echo $layout_id ?>">
  <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $entry_mobile_help; ?>"><?php echo $entry_mobile; ?></span></label>
  <div class="col-sm-10">
    <div class="well well-sm" style="max-height: 250px; overflow: auto;">
      <?php foreach ($mobile as $mobile_key => $mobile_name) { ?>
      <div class="checkbox">
        <label>
          <?php if (isset($settings['mobile']) && in_array($mobile_key, $settings['mobile'])) { ?>
          <input type="checkbox" name="mobile[<?php echo $mobile_key; ?>]" id="mobile[<?php echo $mobile_key; ?>]" value="<?php echo $mobile_key; ?>" checked="checked" />
          <?php echo $mobile_name; ?>
          <?php } else { ?>
          <input type="checkbox" name="mobile[<?php echo $mobile_key; ?>]" id="mobile[<?php echo $mobile_key; ?>]" value="<?php echo $mobile_key; ?>" />
          <?php echo $mobile_name; ?>
          <?php } ?>
        </label>
      </div>
      <?php } ?>
    </div>
    <a href="#" onclick="$(this).parent().find(':checkbox').prop('checked', true);return false;"><?php echo $text_select_all; ?></a> / <a href="#" onclick="$(this).parent().find(':checkbox').prop('checked', false);return false;"><?php echo $text_unselect_all; ?></a> <span class="pull-right"><?php echo $entry_mobile_desc ?></span>
  </div>
</div>

<?php if (count($languages) > 1): ?>
<div class="form-group form-group-sm scommon module<?php echo $layout_id ?> common<?php echo $layout_id ?>">
  <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $entry_languages_help; ?>"><?php echo $entry_languages; ?></span></label>
  <div class="col-sm-10">
    <div class="well well-sm" style="max-height: 250px; overflow: auto;">
      <?php foreach ($languages as $language_data) { ?>
      <div class="checkbox">
        <label>
          <?php if (isset($settings['language_id']) && in_array($language_data['language_id'], $settings['language_id'])) { ?>
          <input type="checkbox" name="language_id[<?php echo $language_data['language_id']; ?>]" id="language_id[<?php echo $language_data['language_id']; ?>]" value="<?php echo $language_data['language_id']; ?>" checked="checked" />
          <?php echo $language_data['name']; ?>
          <?php } else { ?>
          <input type="checkbox" name="language_id[<?php echo $language_data['language_id']; ?>]" id="language_id[<?php echo $language_data['language_id']; ?>]" value="<?php echo $language_data['language_id']; ?>" />
          <?php echo $language_data['name']; ?>
          <?php } ?>
        </label>
      </div>
      <?php } ?>
    </div>
    <a href="#" onclick="$(this).parent().find(':checkbox').prop('checked', true);return false;"><?php echo $text_select_all; ?></a> / <a href="#" onclick="$(this).parent().find(':checkbox').prop('checked', false);return false;"><?php echo $text_unselect_all; ?></a>
  </div>
</div>
<?php endif ?>

<?php if (count($currencies) > 1): ?>
<div class="form-group form-group-sm scommon module<?php echo $layout_id ?> common<?php echo $layout_id ?>">
  <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $entry_currencies_help; ?>"><?php echo $entry_currencies; ?></span></label>
  <div class="col-sm-10">
    <div class="well well-sm" style="max-height: 250px; overflow: auto;">
      <?php foreach ($currencies as $currency) { ?>
      <div class="checkbox">
        <label>
          <?php if (isset($settings['currency_id']) && in_array($currency['currency_id'], $settings['currency_id'])) { ?>
          <input type="checkbox" name="currency_id[<?php echo $currency['currency_id']; ?>]" id="currency_id[<?php echo $currency['currency_id']; ?>]" value="<?php echo $currency['currency_id']; ?>" checked="checked" />
          <?php echo $currency['title']; ?>
          <?php } else { ?>
          <input type="checkbox" name="currency_id[<?php echo $currency['currency_id']; ?>]" id="currency_id[<?php echo $currency['currency_id']; ?>]" value="<?php echo $currency['currency_id']; ?>" />
          <?php echo $currency['title']; ?>
          <?php } ?>
        </label>
      </div>
      <?php } ?>
    </div>
    <a href="#" onclick="$(this).parent().find(':checkbox').prop('checked', true);return false;"><?php echo $text_select_all; ?></a> / <a href="#" onclick="$(this).parent().find(':checkbox').prop('checked', false);return false;"><?php echo $text_unselect_all; ?></a>
  </div>
</div>
<?php endif ?>

<div class="form-group form-group-sm scommon module<?php echo $layout_id ?> common<?php echo $layout_id ?>">
  <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $entry_customer_group_help; ?>"><?php echo $entry_customer_group; ?></span></label>
  <div class="col-sm-10">
    <div class="well well-sm" style="max-height: 250px; overflow: auto;">
      <?php foreach ($customer_groups as $customer_group_key => $customer_group_data) { ?>
      <div class="checkbox">
        <label>
          <?php if (isset($settings['customer_group_id']) && in_array($customer_group_data['customer_group_id'], $settings['customer_group_id'])) { ?>
          <input type="checkbox" name="customer_group_id[<?php echo $customer_group_data['customer_group_id']; ?>]" id="customer_group_id[<?php echo $customer_group_data['customer_group_id']; ?>]" value="<?php echo $customer_group_data['customer_group_id']; ?>" checked="checked" />
          <?php echo $customer_group_data['name']; ?>
          <?php } else { ?>
          <input type="checkbox" name="customer_group_id[<?php echo $customer_group_data['customer_group_id']; ?>]" id="customer_group_id[<?php echo $customer_group_data['customer_group_id']; ?>]" value="<?php echo $customer_group_data['customer_group_id']; ?>" />
          <?php echo $customer_group_data['name']; ?>
          <?php } ?>
        </label>
      </div>
      <?php } ?>
    </div>
    <a href="#" onclick="$(this).parent().find(':checkbox').prop('checked', true);return false;"><?php echo $text_select_all; ?></a> / <a href="#" onclick="$(this).parent().find(':checkbox').prop('checked', false);return false;"><?php echo $text_unselect_all; ?></a>
  </div>
</div>

<?php if (count($stores) > 1): ?>
<div class="form-group form-group-sm scommon module<?php echo $layout_id ?> common<?php echo $layout_id ?>">
  <label class="col-sm-2 control-label"><span data-toggle="tooltip" data-html="true" title="<?php echo $entry_stores_help; ?>"><?php echo $entry_stores; ?></span></label>
  <div class="col-sm-10">
    <div class="well well-sm" style="max-height: 250px; overflow: auto;">
      <?php foreach ($stores as $store_key => $store_data) { ?>
      <div class="checkbox">
        <label>
          <?php if (isset($settings['store_id']) && in_array($store_data['store_id'], $settings['store_id'])) { ?>
          <input type="checkbox" name="store_id[<?php echo $store_data['store_id']; ?>]" id="store_id[<?php echo $store_data['store_id']; ?>]" value="<?php echo $store_data['store_id']; ?>" checked="checked" />
          <?php echo $store_data['name']; ?>
          <?php } else { ?>
          <input type="checkbox" name="store_id[<?php echo $store_data['store_id']; ?>]" id="store_id[<?php echo $store_data['store_id']; ?>]" value="<?php echo $store_data['store_id']; ?>" />
          <?php echo $store_data['name']; ?>
          <?php } ?>
        </label>
      </div>
      <?php } ?>
    </div>
    <a href="#" onclick="$(this).parent().find(':checkbox').prop('checked', true);return false;"><?php echo $text_select_all; ?></a> / <a href="#" onclick="$(this).parent().find(':checkbox').prop('checked', false);return false;"><?php echo $text_unselect_all; ?></a>
  </div>
</div>
<?php endif ?>

<div class="form-group form-group-sm <?php echo !isset($settings['total']['currency_id']) ? 'more_hidden' : ''; ?> scart module<?php echo $layout_id ?> common<?php echo $layout_id ?>">
  <label class="col-sm-2 control-label"><span data-toggle="tooltip" data-html="true" title="<?php echo $entry_total_help; ?>"><?php echo $entry_total; ?></span></label>
  <div class="col-sm-10">
    <div class="input-group input-group-sm">
      <span class="input-group-addon fstw10"><?php echo $entry_total_addon1 ?></span>
      <select class="form-control auto_disabled" name="total[currency_id]">
          <option value="0" <?php echo (isset($settings['total']['currency_id']) && $settings['total']['currency_id'] == 0) ? 'selected="selected"' : ''; ?>><?php echo $text_disabled ?></option>
        <?php foreach ($currencies as $currency) { ?>
          <?php if (isset($settings['total']['currency_id']) && ($currency['currency_id'] == $settings['total']['currency_id'])){ ?>
            <option value="<?php echo $currency['currency_id'] ?>" selected="selected"><?php echo $currency['title'] ?></option>
          <?php } else { ?>
            <option value="<?php echo $currency['currency_id'] ?>"><?php echo $currency['title'] ?></option>
          <?php } ?>
        <?php } ?>
      </select>
      <span class="input-group-addon"><?php echo $entry_total_addon2 ?></span>
      <input type="text" class="form-control" name="total[min]" value="<?php echo isset($settings['total']['min']) ? $settings['total']['min'] : 0; ?>">
      <span class="input-group-addon"><?php echo $entry_total_addon3 ?></span>
      <input type="text" class="form-control" name="total[max]" value="<?php echo isset($settings['total']['max']) ? $settings['total']['max'] : 0; ?>">
    </div>
  </div>
</div>

<div class="form-group form-group-sm <?php echo !isset($settings['sub_total']['currency_id']) ? 'more_hidden' : ''; ?> scart module<?php echo $layout_id ?> common<?php echo $layout_id ?>">
  <label class="col-sm-2 control-label"><span data-toggle="tooltip" data-html="true" title="<?php echo $entry_sub_total_help; ?>"><?php echo $entry_sub_total; ?></span></label>
  <div class="col-sm-10">
    <div class="input-group input-group-sm">
      <span class="input-group-addon fstw10"><?php echo $entry_sub_total_addon1 ?></span>
      <select class="form-control auto_disabled" name="sub_total[currency_id]">
          <option value="0" <?php echo (isset($settings['sub_total']['currency_id']) && $settings['sub_total']['currency_id'] == 0) ? 'selected="selected"' : ''; ?>><?php echo $text_disabled ?></option>
        <?php foreach ($currencies as $currency) { ?>
          <?php if (isset($settings['sub_total']['currency_id']) && ($currency['currency_id'] == $settings['sub_total']['currency_id'])){ ?>
            <option value="<?php echo $currency['currency_id'] ?>" selected="selected"><?php echo $currency['title'] ?></option>
          <?php } else { ?>
            <option value="<?php echo $currency['currency_id'] ?>"><?php echo $currency['title'] ?></option>
          <?php } ?>
        <?php } ?>
      </select>
      <span class="input-group-addon"><?php echo $entry_sub_total_addon2 ?></span>
      <input type="text" class="form-control" name="sub_total[min]" value="<?php echo isset($settings['sub_total']['min']) ? $settings['sub_total']['min'] : 0; ?>">
      <span class="input-group-addon"><?php echo $entry_sub_total_addon3 ?></span>
      <input type="text" class="form-control" name="sub_total[max]" value="<?php echo isset($settings['sub_total']['max']) ? $settings['sub_total']['max'] : 0; ?>">
    </div>
  </div>
</div>

<div class="form-group form-group-sm <?php echo !isset($settings['weight']['weight_class_id']) ? 'more_hidden' : ''; ?> scart module<?php echo $layout_id ?> common<?php echo $layout_id ?>">
  <label class="col-sm-2 control-label"><span data-toggle="tooltip" data-html="true" title="<?php echo $entry_weight_help; ?>"><?php echo $entry_weight; ?></span></label>
  <div class="col-sm-10">
    <div class="input-group input-group-sm">
      <span class="input-group-addon fstw10"><?php echo $entry_weight_addon1 ?></span>
      <select class="form-control auto_disabled" name="weight[weight_class_id]">
          <option value="0" <?php echo (isset($settings['weight']['weight_class_id']) && $settings['weight']['weight_class_id'] == 0) ? 'selected="selected"' : ''; ?>><?php echo $text_disabled ?></option>
        <?php foreach ($weight_classes as $weight_class) { ?>
          <?php if (isset($settings['weight']['weight_class_id']) && ($weight_class['weight_class_id'] == $settings['weight']['weight_class_id'])){ ?>
            <option value="<?php echo $weight_class['weight_class_id'] ?>" selected="selected"><?php echo $weight_class['title'] ?></option>
          <?php } else { ?>
            <option value="<?php echo $weight_class['weight_class_id'] ?>"><?php echo $weight_class['title'] ?></option>
          <?php } ?>
        <?php } ?>
      </select>
      <span class="input-group-addon"><?php echo $entry_weight_addon2 ?></span>
      <input type="text" class="form-control" name="weight[min]" value="<?php echo isset($settings['weight']['min']) ? $settings['weight']['min'] : 0; ?>">
      <span class="input-group-addon"><?php echo $entry_weight_addon3 ?></span>
      <input type="text" class="form-control" name="weight[max]" value="<?php echo isset($settings['weight']['max']) ? $settings['weight']['max'] : 0; ?>">
    </div>
  </div>
</div>

<div class="form-group form-group-sm <?php echo !isset($settings['count']['enabled']) ? 'more_hidden' : ''; ?> scart module<?php echo $layout_id ?> common<?php echo $layout_id ?>">
  <label class="col-sm-2 control-label"><span data-toggle="tooltip" data-html="true" title="<?php echo $entry_cart_products_help; ?>"><?php echo $entry_cart_products; ?></span></label>
  <div class="col-sm-10">
    <div class="input-group input-group-sm">
      <select class="form-control auto_disabled fstw10" name="count[enabled]">
          <?php if (isset($settings['count']['enabled'])): ?>
          <option value="0"><?php echo $text_disabled ?></option>
          <option value="1" selected="selected"><?php echo $text_enabled ?></option>
          <?php else: ?>
          <option value="0" selected="selected"><?php echo $text_disabled ?></option>
          <option value="1"><?php echo $text_enabled ?></option>
          <?php endif ?>
      </select>
      <span class="input-group-addon"><?php echo $entry_cart_products_addon1 ?></span>
      <input type="text" class="form-control" name="count[min]" value="<?php echo isset($settings['count']['min']) ? $settings['count']['min'] : 0; ?>">
      <span class="input-group-addon"><?php echo $entry_cart_products_addon2 ?></span>
      <input type="text" class="form-control" name="count[max]" value="<?php echo isset($settings['count']['max']) ? $settings['count']['max'] : 0; ?>">
    </div>
  </div>
</div>

<div class="form-group form-group-sm <?php echo !isset($settings['custom']['enabled']) ? 'more_hidden' : ''; ?> ssystem module<?php echo $layout_id ?> common<?php echo $layout_id ?>">
  <label class="col-sm-2 control-label"><span data-toggle="tooltip" data-html="true" title="<?php echo $entry_custom_help; ?>"><?php echo $entry_custom; ?></span></label>
  <div class="col-sm-10">
    <div class="input-group input-group-sm">
      <select class="form-control auto_disabled fstw10" name="custom[enabled]">
          <?php if (isset($settings['custom']['enabled'])): ?>
          <option value="0"><?php echo $text_disabled ?></option>
          <option value="1" selected="selected"><?php echo $text_enabled ?></option>
          <?php else: ?>
          <option value="0" selected="selected"><?php echo $text_disabled ?></option>
          <option value="1"><?php echo $text_enabled ?></option>
          <?php endif ?>
      </select>
      <span class="input-group-addon"><?php echo $entry_custom_addon1 ?></span>
      <input type="text" class="form-control" name="custom[id]" value="<?php echo isset($settings['custom']['id']) ? $settings['custom']['id'] : ''; ?>">
      <span class="input-group-addon"><?php echo $entry_custom_addon2 ?></span>
      <input type="text" class="form-control" name="custom[values]" value="<?php echo isset($settings['custom']['values']) ? $settings['custom']['values'] : ''; ?>">
    </div>
  </div>
</div>

<div class="form-group form-group-sm <?php echo !isset($settings['platform']) ? 'more_hidden' : ''; ?> suseragent module<?php echo $layout_id ?> common<?php echo $layout_id ?>">
  <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $entry_platform_help; ?>"><?php echo $entry_platform; ?></span></label>
  <div class="col-sm-10">
    <div class="well well-sm" style="max-height: 250px; overflow: auto;">
      <?php foreach ($platforms as $platform) { ?>
      <div class="checkbox">
        <label>
          <?php if (isset($settings['platform']) && in_array($platform, $settings['platform'])) { ?>
          <input type="checkbox" name="platform[<?php echo $platform; ?>]" id="platform[<?php echo $platform; ?>]" value="<?php echo $platform; ?>" checked="checked" />
          <?php echo $platform; ?>
          <?php } else { ?>
          <input type="checkbox" name="platform[<?php echo $platform; ?>]" id="platform[<?php echo $platform; ?>]" value="<?php echo $platform; ?>" />
          <?php echo $platform; ?>
          <?php } ?>
        </label>
      </div>
      <?php } ?>
    </div>
    <a href="#" onclick="$(this).parent().find(':checkbox').prop('checked', true);return false;"><?php echo $text_select_all; ?></a> / <a href="#" onclick="$(this).parent().find(':checkbox').prop('checked', false);return false;"><?php echo $text_unselect_all; ?></a> <span class="pull-right"><?php echo $entry_platform_desc ?></span>
  </div>
</div>

<div class="form-group form-group-sm <?php echo !isset($settings['browser']) ? 'more_hidden' : ''; ?> suseragent module<?php echo $layout_id ?> common<?php echo $layout_id ?>">
  <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $entry_browser_help; ?>"><?php echo $entry_browser; ?></span></label>
  <div class="col-sm-10">
    <div class="well well-sm" style="max-height: 250px; overflow: auto;">
      <?php foreach ($browsers as $browser) { ?>
      <div class="checkbox">
        <label>
          <?php if (isset($settings['browser']) && in_array($browser, $settings['browser'])) { ?>
          <input type="checkbox" name="browser[<?php echo $browser; ?>]" id="browser[<?php echo $browser; ?>]" value="<?php echo $browser; ?>" checked="checked" />
          <?php echo $browser; ?>
          <?php } else { ?>
          <input type="checkbox" name="browser[<?php echo $browser; ?>]" id="browser[<?php echo $browser; ?>]" value="<?php echo $browser; ?>" />
          <?php echo $browser; ?>
          <?php } ?>
        </label>
      </div>
      <?php } ?>
    </div>
    <a href="#" onclick="$(this).parent().find(':checkbox').prop('checked', true);return false;"><?php echo $text_select_all; ?></a> / <a href="#" onclick="$(this).parent().find(':checkbox').prop('checked', false);return false;"><?php echo $text_unselect_all; ?></a> <span class="pull-right"><?php echo $entry_browser_desc ?></span>
  </div>
</div>
<button class="btn-block show_more" onClick="$('.more_hidden').removeClass('more_hidden');$(this).remove();"><?php echo $text_show_more ?></button>
<!-- default end -->