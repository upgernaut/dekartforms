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
	<h1 class="wp-heading-inline">Forms</h1>
	<a href="<?php echo add_query_arg( array('task' => 'add_form')); ?>" class="page-title-action">Add New</a>
	<hr class="wp-header-end">
	<table class="widefat" id="testme_admin_list">
	<thead>
		<tr>
			<th scope="col">ID</th>
			<th scope="col">Form</th>
			<th scope="col">Actions</th>
		</tr>
	</thead>
	<tbody id="the-list">
		<?php foreach($results as $single_result): ?>
		<tr class="alternate">
			<td><?php echo $single_result->id; ?></td>
			<td><?php echo $single_result->title; ?></td>
			<td>
				<a href="<?php echo add_query_arg( array('form_id' => $single_result->id, 'task' => 'form_entries')); ?>">Entries</a> | 
				<a href="<?php echo add_query_arg( array('form_id' => $single_result->id, 'task' => 'edit_form')); ?>"><span>Edit</a> | 
				<a href="<?php echo add_query_arg( array('form_id' => $single_result->id, 'task' => 'delete_form')); ?>"><span>Delete</a>
			</td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
</div>