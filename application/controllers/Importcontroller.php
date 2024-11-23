<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Importcontroller extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('upload');
		$this->load->library('ImportLibrary');
		$this->load->library('session');
		$this->load->helper('url');
	}

	public function index()
	{
		$this->load->view('import/upload_file'); // Load the upload form view
	}

	public function uploadFile()
	{
		if (empty($_FILES['file']['name'])) {
			echo "<div class='alert alert-danger'>No file selected. Please choose a file to upload.</div>";
			return;
		}

		$config['upload_path'] = './uploads/';
		$config['allowed_types'] = 'csv|xls|xlsx';
		$config['max_size'] = 2048; // 2MB limit
		$this->upload->initialize($config);

		if (!$this->upload->do_upload('file')) {
			$error = $this->upload->display_errors();
			echo "<div class='alert alert-danger'>$error</div>";
		} else {
			$fileData = $this->upload->data();
			$filePath = './uploads/' . $fileData['file_name'];

			// Store file path in session
			$this->session->set_userdata('uploaded_file_path', $filePath);

			// Redirect to column mapping
			redirect('importcontroller/mapColumns');
		}
	}

	public function mapColumns()
	{
		// Retrieve file path from session
		$filePath = $this->session->userdata('uploaded_file_path');
		if (!$filePath) {
			echo "File path not found. Please upload a file first.";
			return;
		}

		// Import data
		$data = $this->importlibrary->import($filePath);
		if ($data === false) {
			echo "Failed to load file data.";
			return;
		}
		// echo "<pre>";
		// print_r($data);
		// echo "</pre>";
		// Get headers from imported file
		$headers = array_keys($data[1]);
		array_shift($data);
		// Define actual candidate_tbl columns (these are your database columns)
		$dbFields = [
			'c_full_name',
			'c_father_name',
			'c_mobile',
			'c_email',
			'c_dob',
			'c_gender',
			'c_salutation',
			'c_marital_status',
			'c_education',
			'c_religion',
			'c_catagory',
			'c_disablity',
			'c_id_type',
			'c_id_no',
			'c_perm_address',
			'c_perm_tehsil',
			'c_perm_district',
			'c_perm_city',
			'c_perm_state',
			'c_perm_pincode',
			'c_perm_constituency',
			'c_comm_address',
			'c_comm_tehsil',
			'c_comm_district',
			'c_comm_city',
			'c_comm_state',
			'c_comm_pincode',
			'c_comm_constituency',
			'c_pre_traning_status',
			'c_prev_exp_sector',
			'c_prev_exp_no_of_months',
			'c_employed',
			'c_employment_status',
			'c_heard_about_us',
			'c_currently_enrolled',
			'c_training_status'
		];

		// Pass data to the view for column mapping
		$viewData = [
			'headers' => $headers,
			'dbFields' => $dbFields,
			'sampleData' => $data
		];
		echo "<pre>";
		// print_r($viewData);
		echo "</pre>";
		$this->load->view('import/map_columns', $viewData);
	}

	public function saveMappedData()
	{
		// Retrieve imported data from session
		$fileData = $this->session->userdata('imported_file_data');
		$mappings = $this->input->post('mappings');

		// Prepare the insert data
		$insertData = [];
		foreach ($fileData as $row) {
			$mappedRow = [];
			foreach ($mappings as $dbField => $column) {
				$mappedRow[$dbField] = $row[$column] ?? null;
			}
			$insertData[] = $mappedRow;
		}

		// Insert the data into candidate_tbl
		if ($this->db->insert_batch('candidate_tbl', $insertData)) {
			echo "Data imported successfully!";
		} else {
			echo "Error inserting data.";
		}
	}
}
