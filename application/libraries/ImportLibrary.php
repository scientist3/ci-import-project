<?php

use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportLibrary
{
	public function __construct()
	{
		// Load the required helper and other necessary libraries
		$this->CI = &get_instance();
		$this->CI->load->helper('file');
	}

	public function index()
	{
		$this->load->view('import/upload_file'); // Make sure the path is correct
	}

	/**
	 * Import data from a file (CSV or Excel)
	 *
	 * @param string $filePath
	 * @return array|bool - Returns data as array on success, false on failure
	 */
	public function import($filePath)
	{
		$fileType = IOFactory::identify($filePath);
		$reader = IOFactory::createReader($fileType);

		try {
			// Load the spreadsheet file
			$spreadsheet = $reader->load($filePath);

			// Fetch all data as an array
			$data = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
			// Use the first row (headers) as the keys and the remaining rows as values
			$header = array_shift($data);  // Get the first row (header)
			$dataWithHeaders = [];

			foreach ($data as $row) {
				$dataWithHeaders[] = array_combine($header, $row);  // Combine headers with row data
			}
			// Remove the header row if present
			//array_shift($data); // Remove the first row which contains column headers

			return $dataWithHeaders;
		} catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
			log_message('error', 'Error loading file "' . pathinfo($filePath, PATHINFO_BASENAME) . '": ' . $e->getMessage());
			return false;
		}
	}

	/**
	 * Validate file type before import
	 *
	 * @param string $filePath
	 * @return bool - True if valid, false otherwise
	 */
	public function isValidFileType($filePath)
	{
		$allowedFileTypes = ['csv', 'xls', 'xlsx'];
		$fileExtension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

		return in_array($fileExtension, $allowedFileTypes);
	}

	/**
	 * Get the headers from the CSV or Excel file
	 *
	 * @param string $filePath
	 * @return array|bool - Returns headers as an array or false on failure
	 */
	public function getFileHeaders($filePath)
	{
		$fileType = IOFactory::identify($filePath);
		$reader = IOFactory::createReader($fileType);

		try {
			// Load the spreadsheet file
			$spreadsheet = $reader->load($filePath);

			// Get the headers (first row) as an array
			$sheet = $spreadsheet->getActiveSheet();
			$headers = $sheet->rangeToArray('A1:Z1', null, true, false)[0]; // Adjust the range if needed

			return $headers;
		} catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
			log_message('error', 'Error loading file "' . pathinfo($filePath, PATHINFO_BASENAME) . '": ' . $e->getMessage());
			return false;
		}
	}
}
