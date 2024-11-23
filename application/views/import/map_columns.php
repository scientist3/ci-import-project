<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>Map Columns</title>
	<!-- AdminLTE v3 CSS -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1.0/dist/css/adminlte.min.css">
</head>

<body>
	<div class="container">
		<h2 class="mt-4">Map Columns</h2>
		<form action="<?= site_url('importcontroller/saveMappedData'); ?>" method="post">
			<div class="card card-primary">
				<div class="card-header">
					<h3 class="card-title">Map File Columns to Database Fields</h3>
				</div>
				<div class="card-body">
					<?php foreach ($dbFields as $dbField): ?>
						<div class="form-group row">
							<label class="col-sm-2 col-form-label"><?= ucfirst($dbField); ?></label>
							<div class="col-sm-6">
								<select name="mappings[<?= $dbField; ?>]" class="form-control">
									<option value="">-- Select Column --</option>
									<?php foreach ($headers as $header): ?>
										<option value="<?= $header; ?>"><?= $header; ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
					<?php endforeach; ?>
					<div class="card-footer">
						<button type="submit" class="btn btn-primary">Save Mappings & Import</button>
					</div>
				</div>
			</div>
		</form>

		<!-- Sample Data Preview Table -->
		<h3 class="mt-4">Sample Data Preview</h3>
		<table class="table table-bordered">
			<thead>
				<tr>
					<?php foreach ($headers as $header): ?>
						<th><?= $header; ?></th>
					<?php endforeach; ?>
				</tr>
			</thead>
			<tbody>
				<?php foreach (array_slice($sampleData, 0, 5) as $row): ?>
					<tr>
						<?php foreach ($row as $cell): ?>
							<td><?= htmlspecialchars($cell); ?></td>
						<?php endforeach; ?>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>

	<!-- AdminLTE Scripts -->
	<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.1.0/dist/js/adminlte.min.js"></script>
</body>

</html>