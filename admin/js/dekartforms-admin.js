(function( $ ) {
	'use strict';

  $( function() {
	// jQuery UI
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
			// Call a reorder function
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
	
	// Adding a field
    $( "#edit_draggable" ).draggable({
      connectToSortable: "#edit_sortable",
      helper: "clone",
      revert: "invalid",
	  stop: function(event, ui) {
			var elem = $(ui.helper[0]);
			var fieldType = 'input';
			editStatus(true);
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
				editStatus(false);
				reorder();	  
			});	  

		} 
    });	
	
    $( "ul, li" ).disableSelection();
	
	// Delete a field in add form page
	$('body').on( 'click', '.deleteDraggable',  function(){
		if(!$(this).closest('.dekartFormDragField').length) {
			$(this).closest('li').remove();
		}
	});
	
	// Delete a field in edit form page
	$('body').on( 'click', '.deleteDraggableEdit',  function(){
		if (!confirm('Are you sure?') )
		{
			return;
		}
		editStatus(true);
		if(!$(this).closest('.dekartFormDragField').length) {
			var elem = $(this).closest('.dekartInputDraggable').attr('data-id');
			$.ajax({
				method: "POST",
				url: "/wp-admin/admin.php?page=dekartforms&task=delete_field",
				data : {
					id: elem,
				},
				dataType: "json"
			}).done(function(){
				editStatus(false);
			});			
			
			$.when( $(this).closest('li').remove()).then( reorder() );
			
		}
	});	
	
	// Edit a label in edit form page
	$('body').on( 'keyup', '.inputLabelTitleEdit',  function(){
		editStatus(true);
		var elem = $(this).closest('.dekartInputDraggable').attr('data-id');
		
		$.ajax({
			method: "POST",
			url: "/wp-admin/admin.php?page=dekartforms&task=edit_field_label",
			data : {
				id: elem,
				label: $(this).val()
			
			},
			dataType: "json"
		}).done(function(){
			editStatus(false);
		});
	});		
	
	// Add a form
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
  }); // DOM ready
  
  
	// Reorder function (being called almost after all dekart form functions)
	function reorder() {
		editStatus(true);
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
		}).done(function(){
			editStatus(false);
			
		});			
	}  
	
	// Functions shows that user changes are being saved
	function editStatus(loading) {
		if(loading) {
			$('.dekartFormEditStatus').addClass('loading').html('Loading');
		} else {
			$('.dekartFormEditStatus').removeClass('loading').html('Saved');
			
		}
	}
	
})( jQuery );




