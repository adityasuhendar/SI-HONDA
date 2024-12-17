<?php if ($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>", 'success');
</script>
<?php endif; ?>
<div class="card card-outline card-primary">
	<div class="card-header">
		<h3 class="card-title">List of Services</h3>
		<div class="card-tools">
			<a href="?page=maintenance/manage_service" class="btn btn-flat btn-primary">
				<span class="fas fa-plus"></span> Create New
			</a>
		</div>
	</div>
	<div class="card-body">
		<div class="container-fluid">
			<table class="table table-bordered table-stripped">
				<colgroup>
					<col width="5%">
					<col width="15%">
					<col width="15%">
					<col width="20%">
					<col width="20%">
					<col width="10%">
					<col width="10%">
				</colgroup>
				<thead>
					<tr class="bg-gradient-dark text-light">
						<th class="text-center">#</th>
						<th class="text-center">Date Created</th>
						<th class="text-center">Service Date</th>
						<th class="text-center">Service Name</th>
						<th class="text-center">Description</th>
						<th class="text-center">Status</th>
						<th class="text-center">Action</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$i = 1;
					$qry = $conn->query("SELECT * FROM `service_list` WHERE delete_flag = 0 ORDER BY service ASC");
					while ($row = $qry->fetch_assoc()):
						$row['description'] = strip_tags(html_entity_decode(stripslashes($row['description'])));
					?>
					<tr>
						<td class="text-center"><?= $i++; ?></td>
						<td class="text-center"><?= date("Y-m-d H:i", strtotime($row['date_created'])) ?></td>
						<td class="text-center">
							<?= isset($row['service_date']) ? date("Y-m-d", strtotime($row['service_date'])) : 'N/A' ?>
						</td>
						<td><?= $row['service'] ?></td>
						<td>
							<p class="truncate-3 m-0 lh-1"><small><?= $row['description'] ?></small></p>
						</td>
						<td class="text-center">
							<?php if ($row['status'] == 1): ?>
								<span class="badge badge-success">Active</span>
							<?php else: ?>
								<span class="badge badge-danger">Inactive</span>
							<?php endif; ?>
						</td>
						<td align="center">
							<div class="btn-group">
								<button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
									Action
									<span class="sr-only">Toggle Dropdown</span>
								</button>
								<div class="dropdown-menu" role="menu">
									<a class="dropdown-item" href="?page=maintenance/manage_service&id=<?= $row['id'] ?>">
										<span class="fa fa-edit text-primary"></span> Edit
									</a>
									<div class="dropdown-divider"></div>
									<a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?= $row['id'] ?>">
										<span class="fa fa-trash text-danger"></span> Delete
									</a>
								</div>
							</div>
						</td>
					</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script>
	$(document).ready(function () {
		$('.delete_data').click(function () {
			_conf("Are you sure to delete this service permanently?", "delete_service", [$(this).attr('data-id')]);
		});
		$('.table').dataTable({
			"columnDefs": [
				{ "orderable": false, "targets": [6] } // Disable sorting on Action column
			]
		});
	});

	function delete_service($id) {
		start_loader();
		$.ajax({
			url: _base_url_ + "classes/Master.php?f=delete_service",
			method: "POST",
			data: { id: $id },
			dataType: "json",
			error: err => {
				console.error(err);
				alert_toast("An error occurred.", 'error');
				end_loader();
			},
			success: function (resp) {
				if (typeof resp == 'object' && resp.status == 'success') {
					location.reload();
				} else {
					alert_toast("An error occurred.", 'error');
					end_loader();
				}
			}
		});
	}
</script>
