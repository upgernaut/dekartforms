<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://aramkhachikyan.com
 * @since      1.0.0
 *
 * @package    Dekartforms
 * @subpackage Dekartforms/admin/partials
 */
?>

<div class="wrap">
 
    <?php screen_icon(); ?>
 
		<h1 class="wp-heading-inline">Add new form</h1>
		<hr class="wp-header-end">
        <div id="poststuff">
            <div id="post-body" class="metabox-holder columns-<?php echo 1 == get_current_screen()->get_columns() ? '1' : '2'; ?>">
                <div id="post-body-content">
					<form action="" method="post" >
						<input type="hidden" name="dekartFormEdit"  value="<?php echo $form_id; ?>">
						<div class="dekartFormTitle">
							<input type="text" placeholder="Title*" value="<?php echo $form->title; ?>" name="form_title">
						</div>
						
						<div class="dekartFormDropField">
							<h3>Drag a field into the red box or reorder existing!</h3>
							<ul id="edit_sortable" class="dekartSortable">
							<?php foreach($fields as $single_field): ?>
							<li class="dekartInputDraggable" data-id="<?php echo $single_field->id; ?>">
								<div>Input [type=text]</div>
								<input type="text" name="field[<?php echo $single_field->id; ?>][name]" placeholder="Name*" value="<?php echo $single_field->name; ?>"  class="nameInputEdit">
								<input type="text" name="label_title[]" placeholder="Label*" class="nameInput inputLabelTitle inputLabelTitleEdit" value="<?php echo $single_field->label; ?>">
								<button class="deleteDraggableEdit">delete</button>
							</li>
							<?php endforeach; ?>
							
							</ul>						
						</div>
						
						<div class="dekartFormDropSubmit">
							<button type="submit">Edit form</button>
						</div>
					</form>
                </div>
 
                <div id="postbox-container-1" class="postbox-container">
					<div class="dekartFormDragField">
						<h3>Available fields</h3>
						<ul>
							<li id="edit_draggable" class="dekartInputDraggable">
							
								<div>Input [type=text]</div>
								<input type="text" name="name[]" placeholder="Name*" class="nameInput nameInputEdit">
								<input type="text" name="label_title[]" placeholder="Label*" class="nameInput inputLabelTitle inputLabelTitleEdit">
								<button class="deleteDraggableEdit">delete</button>
								
							</li>
						</ul>
					</div>
                </div>
 

 
            </div> <!-- #post-body -->
 
        </div> <!-- #poststuff -->
 
 
</div><!-- .wrap -->