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
					<form class="dekartForm" action="" method="post">
						<input type="hidden" name="dekartFormCreate" value="1">
						<div class="dekartFormTitle">
							<input type="text" placeholder="Title*" name="form_title">
						</div>
						
						<div class="dekartFormDropField">
							<h3>Drag a field into red box!</h3>
							<ul id="sortable" class="dekartSortable">
		
							</ul>						
						</div>
						
						<div class="dekartFormDropSubmit">
							<button type="submit">Add form</button>
						</div>
					</form>
                </div>
 
                <div id="postbox-container-1" class="postbox-container">
					<div class="dekartFormDragField">
						<h3>Available fields</h3>
						<ul>
							<li id="draggable" class="dekartInputDraggable">
							
								<div>Input [type=text]</div>
								<input type="text" name="label_title[]" placeholder="Label*" class="nameInput inputLabelTitle">
								<button class="deleteDraggable">delete</button>
								
							</li>
						</ul>
					</div>
                </div>
 

 
            </div> <!-- #post-body -->
 
        </div> <!-- #poststuff -->
 
 
</div><!-- .wrap -->