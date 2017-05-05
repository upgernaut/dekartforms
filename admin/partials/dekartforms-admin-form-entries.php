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
	<h2>Form "<?php echo $form->title; ?>" entries</h2>
	<table class="widefat" id="testme_admin_list">
	<thead>
		<tr>
			<th scope="col">ID</th>
			<th scope="col">Form</th>
			<th scope="col">Submitted</th>
			<th scope="col">Actions</th>
		</tr>
	</thead>
	<tbody id="the-list">
		<?php foreach($results as $single_result): ?>
		<tr class="alternate">
			<td><a href="<?php echo add_query_arg( array('entry_id' => $single_result->id, 'task' => 'single_entry')); ?>"><?php echo $single_result->id; ?></td>
			<td><?php echo $single_result->form_id; ?></td>
			<td><?php echo $single_result->submitted; ?></td>
			<td>
				<a href="<?php echo add_query_arg( array('entry_id' => $single_result->id, 'task' => 'delete_entry')); ?>"><span>Delete</a>
			</td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
</div>