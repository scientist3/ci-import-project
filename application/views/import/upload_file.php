<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>Upload File</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1.0/dist/css/adminlte.min.css">
</head>

<body>
	<div class="container">
		<h2 class="mt-4">Upload File</h2>
		<div class="card card-primary">
			<div class="card-header">
				<h3 class="card-title">Select a CSV or Excel File to Upload</h3>
			</div>
			<form action="<?= site_url('importcontroller/uploadFile'); ?>" method="post" enctype="multipart/form-data">
				<div class="card-body">
					<div class="form-group">
						<label for="file">Choose File</label>
						<input type="file" name="file" class="form-control" required>
						<small class="form-text text-muted">Allowed file types: .csv, .xls, .xlsx</small>
					</div>
				</div>
				<div class="card-footer">
					<button type="submit" class="btn btn-primary">Upload File</button>
				</div>
			</form>
		</div>
	</div>
	<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.1.0/dist/js/adminlte.min.js"></script>
</body>

</html>