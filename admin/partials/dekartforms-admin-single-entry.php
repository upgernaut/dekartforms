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
	<h2>Entry "<?php echo $entry_id; ?>" </h2>
	<table class="widefat" id="testme_admin_list">
	<thead>
		<tr>
			<th scope="col">ID</th> 
			<th scope="col">Field</th>
			<th scope="col">Content</th>
		</tr>
	</thead>
	<tbody id="the-list">
		<?php foreach($entries_fields as $single_result): ?>
		<tr class="alternate">
			<td><?php echo $single_result->id; ?></td>
			<td><?php echo $fields[$single_result->field_id]; ?></td>
			<td><?php echo $single_result->content; ?></td>
			
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
</div>