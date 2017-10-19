jQuery(function($) {
    var $no_result_category = $('#bookly-categories-wrapper .no-result');
    // Remember user choice in the modal dialog.
    var update_staff_choice = null,
        $new_rank_popover = $('#bookly-new-rank'),
      
        $new_rank_form = $('#new-rank-form'),
       
        $new_rank_name = $('#bookly-rank-name');
      

    $new_rank_popover.popover({
        html: true,
        placement: 'bottom',
        template: '<div class="popover" role="tooltip"><div class="popover-arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>',
        content: $new_rank_form.show().detach(),
        trigger: 'manual'
    }).on('click', function () {
        $(this).popover('toggle');
    }).on('shown.bs.popover', function () {
        // focus input
        $new_rank_name.focus();
    }).on('hidden.bs.popover', function () {
        //clear input
        $new_rank_name.val('');
    });
	

    
    // Save new rank.
    $new_rank_form.on('submit', function() {
        var data = $(this).serialize();

        $.post(ajaxurl, data, function(response) {
            $('#bookly-rank-item-list').append(response.data.html);
            var $new_rank = $('.bookly-rank-item:last');
            // add created rank to category
            $('select[name="rank_id"]').append('<option value="' + $new_rank.data('rank-id') + '">' + $new_rank.find('input').val() + '</option>');
            $new_rank_popover.popover('hide');
        });
        return false;
    });
    
    
    // Cancel button.
    $new_rank_form.on('click', 'button[type="button"]', function (e) {
        $new_rank_popover.popover('hide');
    });

    // Save rank.
    function saveRank() {
        var $this = $(this),
            $item = $this.closest('.bookly-rank-item'),
            field = $this.attr('name'),
            value = $this.val(),
            id    = $item.data('rank-id'),
            data  = { action: 'bookly_update_rank', id: id, csrf_token : BooklyL10n.csrf_token };
        data[field] = value;
        $.post(ajaxurl, data, function(response) {
            // Hide input field.
            $item.find('input').hide();
            $item.find('.displayed-value').show();
            // Show modified rank name.
            $item.find('.displayed-value').text(value);
            // update edited rank's name for category
            $('select[name="rank_id"] option[value="' + id + '"]').text(value);
        });
    }
    

    // Categories list delegated events.
    $('#bookly-ranks-list')

        // On rank item click.
        .on('click', '.bookly-rank-item', function(e) {
            if ($(e.target).is('.bookly-js-handle')) return;
            $('#bookly-js-category-list').html('<div class="bookly-loading"></div>');
            var $clicked = $(this);

            $.get(ajaxurl, {action:'bookly_get_category_ranks', rank_id: $clicked.data('rank-id'), csrf_token : BooklyL10n.csrf_token}, function(response) {
                if ( response.success ) {
                    $('.bookly-rank-item').not($clicked).removeClass('active');
                    $clicked.addClass('active');
                    $('.bookly-rank-title').text($clicked.text());
                    refreshList1(response.data, 0);
                }
            });
        })

        // On edit rank click.
        .on('click', '.bookly-js-edit', function(e) {
            // Keep rank item click from being executed.
            e.stopPropagation();
            // Prevent navigating to '#'.
            e.preventDefault();
            var $this = $(this).closest('.bookly-rank-item');
            $this.find('.displayed-value').hide();
            $this.find('input').show().focus();
        })

        // On blur save changes.
        .on('blur', 'input', saveRank)
        

        // On press Enter save changes.
        .on('keypress', 'input', function (e) {
            var code = e.keyCode || e.which;
            if (code == 13) {
                saveRank.apply(this);
            }
        })
   

        // On delete rank click.
        .on('click', '.bookly-js-delete', function(e) {
            // Keep rank item click from being executed.
            e.stopPropagation();
            // Prevent navigating to '#'.
            e.preventDefault();
            // Ask user if he is sure.
            if (confirm(BooklyL10n.are_you_sure)) {
                var $item = $(this).closest('.bookly-rank-item');
                var data = { action: 'bookly_delete_rank', id: $item.data('rank-id'), csrf_token : BooklyL10n.csrf_token };
                $.post(ajaxurl, data, function(response) {
                    // Remove rank item from Category
                    $('select[name="rank_id"] option[value="' + $item.data('rank-id') + '"]').remove();
                    // Remove rank item from DOM.
                    $item.remove();
                    if ($item.is('.active')) {
                        $('.bookly-js-all-ranks').click();
                    }
                });
            }
        })

        .on('click', 'input', function(e) {
            e.stopPropagation();
        });
	
	
	// Services list delegated events.
	$('#bookly-ranks-wrapper')
	// On click on 'Add Service' button.
	
	
		
		.on('change', 'input.bookly-check-all-entities, input.bookly-js-check-entity', function () {
			var $container = $(this).parents('.form-group');
			if ($(this).hasClass('bookly-check-all-entities')) {
				$container.find('.bookly-js-check-entity').prop('checked', $(this).prop('checked'));
			} else {
				$container.find('.bookly-check-all-entities').prop('checked', $container.find('.bookly-js-check-entity:not(:checked)').length == 0);
			}
			updateSelectorButton($container);
		});
	
	// Modal window events.
	var $modal = $('#bookly-update-category-settings');
	$modal
		.on('click', '.bookly-yes', function() {
			$modal.modal('hide');
			if ( $('#bookly-remember-my-choice').prop('checked') ) {
				update_staff_choice = true;
			}
			submitCategoryFrom($modal.data('input'),true);
		})
		.on('click', '.bookly-no', function() {
			if ( $('#bookly-remember-my-choice').prop('checked') ) {
				update_staff_choice = false;
			}
			submitCategoryFrom($modal.data('input'),false);
		});
	
	function refreshList1(response,category_id) {
		var $list = $('#bookly-js-rank-list');
		$list.html(response);
		if (response.indexOf('panel') >= 0) {
			$no_result_category.hide();
			onCollapseInitChildren1();
			$list.booklyHelp();
		} else {
			$no_result_category.show();
		}
		$('#category_' + category_id).collapse('show');
		$('#category_' + category_id).find('input[name=title]').focus();
	}
    
	
	function submitCategoryFrom($form, update_staff) {
		$form.find('input[name=update_staff]').val(update_staff ? 1 : 0);
		var ladda = rangeTools.ladda($form.find('button.ajax-category-send[type=submit]').get(0)),
			data = $form.serializeArray();
		$(document.body).trigger( 'category.submitForm', [ $form, data ] );
		$.post(ajaxurl, data, function (response) {
			if (response.success) {
				var $panel = $form.parents('.bookly-js-collapse'),
				$category_rank = $form.find('[name=rank_id]');
				$panel.find('.bookly-js-category-title').html(response.data.title);
				$category_rank.data('last_value', $category_rank.val());
				
				booklyAlert(response.data.alert);
				if (response.data.new_extras_list_cat) {
					ExtrasL10n.list = response.data.new_extras_list_cat
				}
				$.each(response.data.new_extras_cat_ids, function (front_id, real_id) {
					var $li = $('li.extra.new[data-extra-id="' + front_id + '"]', $form);
					$('[name^="extras"]', $li).each(function () {
						var name = $(this).attr('name');
						name = name.replace('[' + front_id + ']', '[' + real_id + ']');
						$(this).attr('name', name);
					});
					$li.data('extra-id', real_id).removeClass('new');
					$li.append('<input type="hidden" value="' + real_id + '" name="extras[' + real_id + '][id]">');
				});
			} else {
				booklyAlert({error: [response.data.message]});
			}
		}, 'json').always(function() {
			ladda.stop();
		});
	}
	
	function updateSelectorButton1($container) {
		var entity_checked = $container.find('.bookly-js-check-entity:checked').length,
			$check_all = $container.find('.bookly-check-all-entities');
		if (entity_checked == 0) {
			$container.find('.bookly-entity-counter').text($check_all.data('nothing'));
		} else if (entity_checked == 1) {
			$container.find('.bookly-entity-counter').text($container.find('.bookly-js-check-entity:checked').data('title'));
		} else if (entity_checked == $container.find('.bookly-js-check-entity').length) {
			$container.find('.bookly-entity-counter').text($check_all.data('title'));
			$check_all.prop('checked', true);
		} else {
			$container.find('.bookly-entity-counter').text(entity_checked + '/' + $container.find('.bookly-js-check-entity').length);
		}
	}
	function checkCapacityError1($panel) {
		if ( ! $panel.find('[name="rank_id"]').val()) {
			$panel.find('form .bookly-js-categories-error').html(BooklyL10n.capacity_error);
			$panel.find('form .ajax-category-send').prop('disabled', true);
		} else {
			$panel.find('form .bookly-js-categories-error').html('');
			$panel.find('form .ajax-category-send').prop('disabled', false);
		}
	}
	
	var $rank = $('#bookly-rank-item-list');
	$rank.sortable({
		axis   : 'y',
		handle : '.bookly-js-handle',
		update : function( event, ui ) {
			var data = [];
			$rank.children('li').each(function() {
				var $this = $(this);
				var position = $this.data('rank-id');
				data.push(position);
			});
			$.ajax({
				type : 'POST',
				url  : ajaxurl,
				data : { action: 'bookly_update_rank_position', position: data, csrf_token : BooklyL10n.csrf_token }
			});
		}
	});

	
	function onCollapseInitChildren1() {
		$('.panel-collapse').on('show.bs.collapse.bookly', function () {
			var $panel = $(this);
			
			$('[data-toggle="popover"]').popover({
				html: true,
				placement: 'top',
				trigger: 'hover',
				template: '<div class="popover bookly-font-xs" style="width: 220px" role="tooltip"><div class="popover-arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>'
			});
			
			$.each($('.bookly-js-entity-selector-container',$(this)), function () {
				updateSelectorButton1($(this));
			});
			
			$panel.find('.bookly-js-capacity').on('keyup change', function () {
				checkCapacityError1($(this).parents('.bookly-js-collapse'));
			});
			
			$panel.find('.ajax-category-send').on('click', function (e) {
				e.preventDefault();
				var $form = $(this).parents('form'),
					show_modal = false;
				if(update_staff_choice === null) {
					$('.bookly-question', $form).each(function () {
						if ($(this).data('last_value') != $(this).val()) {
							show_modal = true;
						}
					});
				}
				if (show_modal) {
					$modal.data('input', $form).modal('show');
				} else {
					submitCategoryFrom($form, update_staff_choice);
				}
			});
			
			$panel.find('.js-reset').on('click', function () {
				$(this).parents('form').trigger('reset');
				var $panel = $(this).parents('.bookly-js-collapse');
				$panel.find('.parent-range-start').trigger('change');
				$.each($('.bookly-js-entity-selector-container',$panel), function () {
					updateSelectorButton1($(this));
				});
				checkCapacityError1($panel);
			});
			$panel.find('.bookly-question').each(function () {
				$(this).data('last_value', $(this).val());
			});
			$panel.unbind('show.bs.collapse.bookly');
			$(document.body).trigger( 'category.initForm', [ $panel, $panel.closest('.panel').data('category-id') ] );
		});
	}
	
	onCollapseInitChildren1();

    
});