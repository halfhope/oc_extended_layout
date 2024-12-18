// ajaxQueue
(function($) {
	var b = $({});
	$.ajaxQueue = function(c) {
		var d = c.complete;
		b.queue(function(b) {
			c.complete = function() {
				d && d.apply(this, arguments);
				b()
			};
			$.ajax(c)
		})
	}
})(jQuery);

// Extended layout
var getDescription = function(val, overwrite) {
	var module_row = $(val).data('module-row');
	var sd = $(val).find('input[type=hidden][name*=extended_layout]').val();
	if (sd || overwrite) {
		$.ajaxQueue({
			url: extended_layout_data['explain'],
			type: 'post',
			dataType: 'json',
			data: (sd.length > 0) ? sd : {
				module_row: module_row
			},
			success: function(response) {
				$('#el-row' + module_row).find('span.description').html('<i class="fa fa-long-arrow-up"></i>&nbsp;' + response.description);
			},
			error: function(err) {
				alert(err.responseText);
			}
		});
	}
}
var editModuleLayout = function(module_row) {
	$('#els-modal').modal();
	$.ajax({
		url: extended_layout_data['form'],
		type: 'POST',
		dataType: 'html',
		data: {
			module_row: module_row,
			extended_layout: $('#el-row' + module_row).find('input[type=hidden][name*=extended_layout]').val()
		},
		success: function(response) {
			$('#form-module-extended-layout').html(response);
			$('#form-module-extended-layout .form-group.more_hidden').detach().appendTo('#form-module-extended-layout');
			$('#els-modal #type').on('change load', function(event) {
				switch (parseInt($(this).val())) {
					case 1:
						$('.sproducts').show();
						$('.scategories' + ', .sinformations' + ', .smanufacturers' + ', .sproduct_categories' + ', .sproduct_manufacturers').hide();
						break;
					case 2:
						$('.sproduct_categories').show();
						$('.scategories' + ', .sproducts' + ', .sinformations' + ', .smanufacturers' + ', .sproduct_manufacturers').hide();
						break;
					case 3:
						$('.sproduct_manufacturers').show();
						$('.scategories' + ', .sproducts' + ', .sinformations' + ', .smanufacturers' + ', .sproduct_categories').hide();
						break;
				}
			});
			$('#form-module-extended-layout #type').trigger('change');
			$('#form-module-extended-layout input[name=\'products_search\']').autocomplete({
				source: function(request, response) {
					$.ajax({
						url: extended_layout_data['product_autocomplete'] + '&term=' +  encodeURIComponent(request),
						dataType: 'json',
						success: function(json) {
							response($.map(json.results, function(item) {
								return {
									label: item['text'],
									value: item['id']
								}
							}));
						}
					});
				},
				select: function(item) {
					$('#form-module-extended-layout input[name=\'products_search\']').val('');
					$('#form-module-extended-layout #product_id' + item['value']).remove();
					$('#form-module-extended-layout #product_id').append('<div id="product_id' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="product_id[]" value="' + item['value'] + '" /></div>');	
					$('#form-module-extended-layout #product_id #product_id' + item['value']).click()
				}
			});
			$('#form-module-extended-layout #product_id').delegate('.fa-minus-circle', 'click', function() {
				$(this).parent().remove();
			});
		}
	});
}
$(document).ready(function() {
	$.each($('.extended_layout_row'), function(index, val) {
		getDescription(val, false);
	});
	$('#els-modal').on('hidden.bs.modal', function(e) {
		$('#form-module-extended-layout').html('<center><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i></center>');
	});
	$('button.save').on('click', function(event) {
		var module_row_node = $('#form-module-extended-layout input[name=module_row]');
		var module_row = $(module_row_node).val();
		$('#form-module-extended-layout input[name=module_row]').remove();
		$('#form-module-extended-layout input[name^=explain]').remove();
		$.each($('#form-module-extended-layout .auto_disabled'), function(index, val) {
			if ($(val).val() == 0) {
				$(val).parent('.input-group').find('select,input').remove();
			}
		});
		var serialized = $('#form-module-extended-layout').serialize();
		$('#el-row' + module_row).append(module_row_node);
		$('#els-modal').modal('hide');
		$('#el-row' + module_row).find('input[type=hidden][name*=extended_layout]').val(serialized);
		getDescription($('#el-row' + module_row), true);
	});
	$('button.clear').on('click', function(event) {
		var module_row = $('#form-module-extended-layout>input[name=module_row]').val();
		$('#el-row' + module_row).find('input[type=hidden][name*=extended_layout]').val('');
		$('#els-modal').modal('hide');
		getDescription($('#el-row' + module_row), true);
	});

});