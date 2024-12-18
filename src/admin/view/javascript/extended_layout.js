
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

function getDescription (val, overwrite) {
	var module_row = $(val).data('module-row');
	var sd = $(val).find('input[type=hidden][name*=extended_layout]').val();
	if (sd || overwrite) {
		$.ajaxQueue({
			url: extended_layout_data['links']['explain'],
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

function editModuleLayout(module_row) {
	new bootstrap.Modal(document.getElementById('els-modal')).show();
	$.ajax({
		url: extended_layout_data['links']['form'],
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
						url: extended_layout_data['links']['product_autocomplete'] + '&term=' +  encodeURIComponent(request),
						dataType: 'json',
						success: function(json) {
							if (json.results !== undefined) {
								response($.map(json.results, function(item) {
									return {
										label: item['text'],
										value: item['id']
									}
								}));
							}
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
			var ttrMap = [].slice.call(document.querySelectorAll('#form-module-extended-layout [data-bs-toggle="tooltip"]'));
			var tList = ttrMap.map(function (tooltipTriggerEl) {
				return new bootstrap.Tooltip(tooltipTriggerEl)
			})
		}
	});
}

function addExtendedLayoutModule(module_row, extended_layout = '', description = extended_layout_data['lang']['text_output_show'] + ' *') {
	var html = '';
	html += '<tr id="el-row' + module_row + '" class="extended_layout_row" data-module-row="' + module_row + '">';
	html += '<td class="table_fix_23">';
	html += '<span class="description"><i class="fa fa-long-arrow-up"></i>&nbsp;' + description + '</span> <input type="hidden" name="layout_module[' + module_row + '][extended_layout]" value="' + extended_layout + '">';
	html += '<button type="button" onclick="editModuleLayout(' + module_row + ');" data-bs-toggle="tooltip" title="' + extended_layout_data['lang']['text_form_edit'] + '" class="btn btn-primary btn-sm"><i class="fas fa-pencil-alt fa-fw"></i></button>';
	html += '</td>';
	html += '</tr>';
	return html;
}

				
function addModalContainer() {
	var html = '';
	html += '<div class="modal fade" tabindex="-1" role="dialog" id="els-modal" aria-hidden="true">';
	html += '	<div class="modal-dialog modal-dialog-centered modal-lg" role="document">';
	html += '	<div class="modal-content">';
	html += '		<div class="modal-header">';
	html += '			<h5 class="modal-title">' + extended_layout_data['lang']['text_form_title'] + '</h5>';
	html += '			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="' + extended_layout_data['lang']['text_form_close'] + '"></button>';
	html += '		</div>';
	html += '		<div class="modal-body">';
	html += '		<form action="" method="post" enctype="multipart/form-data" id="form-module-extended-layout" class="container-fluid">';
	html += '			<center><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i></center> ';
	html += '		</form>';
	html += '		</div>';
	html += '		<div class="modal-footer stripped-footer sticky-footer">';
	html += '			<button type="button" class="btn btn-sm btn-secondary clear">' + extended_layout_data['lang']['text_form_clear'] + '</button>';
	html += '			<button type="button" class="btn btn-sm btn-primary save">' + extended_layout_data['lang']['text_form_save'] + '</button>';
	html += '		</div>';
	html += '	</div>';
	html += '	</div>';
	html += '</div>';
	return html;
}

$(document).ready(function() {
	$('body').after(addModalContainer());
	
	$.each($('table[id^=module] tbody>tr'), function(index, val) {
		$row = $(val);
		var code = $row.find('select[name*=code]').val();
		var position = $row.find('input[name*=position]').val();
		var sort_order = $row.find('input[name*=sort_order]').val();

		var hash = code + '_' + position + '_' + sort_order;
		var extended_layout = extended_layout_data['hash_table'][hash];
		
		var html = addExtendedLayoutModule(index, extended_layout);
		$row.after(html);
	});

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