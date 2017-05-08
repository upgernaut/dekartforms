(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
  $( function() {
    $( "#sortable" ).sortable({
      revert: true
    });
    $( "#draggable" ).draggable({
      connectToSortable: "#sortable",
      helper: "clone",
      revert: "invalid"
    });
	
    $( "#edit_sortable" ).sortable({
      revert: true,
	  update: function(event, ui) {
			console.log('reorder');
			reorder();

		}
    });
	
	
	// Edit form name
	$('.dekartFormTitle input').keyup(function(){
			var form_id = $('input[name=dekartFormEdit]').val();
			var form_name = $(this).val();
			
			$.ajax({
				method: "POST",
				url: "/wp-admin/admin.php?page=dekartforms&task=edit_form_name",
				data: {
					form_id: form_id,
					form_name: form_name
					
				},
				dataType: "json"
			}).done(function( data ) {
				// elem.attr('data-id', data.insert);
				// reorder();	  
			});	 		
		
	});
	
    $( "#edit_draggable" ).draggable({
      connectToSortable: "#edit_sortable",
      helper: "clone",
      revert: "invalid",
	  stop: function(event, ui) {
		  //console.log($(ui.helper[0]).attr('class'));
			var elem = $(ui.helper[0]);
			var fieldType = 'input';
			
			$.ajax({
				method: "POST",
				url: "/wp-admin/admin.php?page=dekartforms&task=insert_field",
				data: {
					data : { 
						field: fieldType,
						form_id: $('input[name=dekartFormEdit]').val()
					}
					
				},
				dataType: "json"
			}).done(function( data ) {
				elem.attr('data-id', data.insert);
				reorder();	  
			});	  

		} 
    });	
	
    $( "ul, li" ).disableSelection();
	
	$('body').on( 'click', '.deleteDraggable',  function(){
		if(!$(this).closest('.dekartFormDragField').length) {
			$(this).closest('li').remove();
			
		}
	});
	
	$('body').on( 'click', '.deleteDraggableEdit',  function(){
		if (!confirm('Are you sure?') )
		{
			return;
		}
		
		if(!$(this).closest('.dekartFormDragField').length) {
			var elem = $(this).closest('.dekartInputDraggable').attr('data-id');
			$.ajax({
				method: "POST",
				url: "/wp-admin/admin.php?page=dekartforms&task=delete_field",
				data : {
					id: elem,
				},
				dataType: "json"
			});			
			
			$.when( $(this).closest('li').remove()).then( reorder() );
			
		}
	});	
	
	$('body').on( 'keyup', '.nameInputEdit',  function(){
		var elem = $(this).closest('.dekartInputDraggable').attr('data-id');
		
		$.ajax({
			method: "POST",
			url: "/wp-admin/admin.php?page=dekartforms&task=edit_field_name",
			data : {
				id: elem,
				name: $(this).val()
			
			},
			dataType: "json"
		});
	});	
	
	$('body').on( 'keyup', '.inputLabelTitleEdit',  function(){
		var elem = $(this).closest('.dekartInputDraggable').attr('data-id');
		
		$.ajax({
			method: "POST",
			url: "/wp-admin/admin.php?page=dekartforms&task=edit_field_label",
			data : {
				id: elem,
				label: $(this).val()
			
			},
			dataType: "json"
		});
	});		
	
	
	
	$('.dekartForm').submit(function(e){
		e.preventDefault();
		var inputsJSON = [];
		$(this).find('.dekartInputDraggable').each(function(){
			var tempArr = {
				'name' : $(this).find(".inputTitle").val(),
				'label' : $(this).find(".inputLabelTitle").val(),
			};
			
			inputsJSON.push(tempArr);
		});
		
		$.ajax({
		  method: "POST",
		  url: "/wp-admin/admin.php?page=dekartforms&task=insert_form",
		  data: {
				form_title: $('.dekartFormTitle input').val(),
				dekartFormCreate: 1,
				data : inputsJSON,
			},
		  dataType: "json"
		}).done(function( data ) {
			  if(data.status == "success") {
				window.location.href = "/wp-admin/admin.php?page=dekartforms&task=return";
			  }
		});
		
	});
  } );
  
	function reorder() {

		var newOrder = [];
		$('.dekartFormDropField .dekartInputDraggable').each(function(){
			var tempArr = {
				'id' : $(this).attr("data-id")
			};

			newOrder.push(tempArr);
		});		


		$.ajax({
			method: "POST",
			url: "/wp-admin/admin.php?page=dekartforms&task=reorder_fields",
			data: {
				data : newOrder,
			},
			dataType: "json"
		});			
	}  
	
	

	
})( jQuery );




