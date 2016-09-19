<?php
/**
 * process.php
 *
 * @author jrsouth (GitHub)
 * @package template_tool
 * @see editor.php
 * @see index.php
 * @see main.php
 */

require 'settings-core.php';

// Make DB superglobal (sort of).
// Use DB::$conn to get the mysqli connection object
class DB {
    public static $conn;
    private function __construct(){} //no instantiation
}
DB::$conn = new mysqli($db_server, $db_user, $db_password, $db_database,$db_port);



// Import libraries
require_once 'tools/fpdf/fpdf.php';
require_once 'tools/fpdi/fpdi.php';




// Adjust paths
$base_path = dirname(__FILE__) . '/' ;

$cache_path = $base_path . $cache_location;
$content_path = $base_path . 'content/';
$storage_path = $base_path . 'storage/';

// Set font path for FPDF
define('FPDF_FONTPATH', $base_path.'storage/fonts/');

// Set up variables
$data = array();
$stage = 0;
$image_locations = array();
$template = array();


// Horrible manipulation of input... :/
if (isset($_GET['template_id'])) {
	$_POST['template_id'] = $_GET['template_id'];
}
if (isset($_GET['view'])) {
	$_POST['view'] = $_GET['view'];
}


$working_template_id = 0;

if (isset($_POST['working_template_id'])) {
	$working_template_id = $_POST['working_template_id'];
} else if (isset($_GET['working_template_id'])) {
		$working_template_id = $_GET['working_template_id'];
	}

if ($working_template_id) {
	$sql = 'SELECT * FROM `working_templates` WHERE `id` = '.$working_template_id;
	$result = mysqli_fetch_assoc(mysqli_query(DB::$conn,$sql));
	$template['id'] = $result['template_id'];
	$sql = 'SELECT * FROM templates WHERE id = ' . $template['id'];
	$results = mysqli_query(DB::$conn,$sql);
	if (mysqli_num_rows($results) == 1) {
		$template = mysqli_fetch_assoc($results);
	}

}



if (isset($_FILES) && !isset($_POST['from-editor'])) { // Process any user input files
	foreach ($_FILES as $key => $image) {
		if ($image['error'] == 0 && getimagesize($image['tmp_name'])) { // upload successful and check for valid image file
			$target_path = $cache_path . 'upload/'.uniqid().'_'.basename($image['name']); // Potentially use userid in future for additional entropy/identification
			if (move_uploaded_file($image['tmp_name'], $target_path)) {
				$_FILES[$key]['upload'] = $target_path;
				$image_locations[$key] = $target_path; // Messy!
			} else {
				echo "There was an error uploading a file, please try again!";
			}
		} else if (isset($_POST[$key.'hidden']) && $_POST[$key.'hidden'] != '') {
				$image_locations[$key] = $_POST[$key.'hidden']; // Messy!
			}
	}
}


// Need a better way of determining that the form has been submitted. Should be easy.

if (isset($_POST['template_id']) && $_POST['template_id'] != 'new') {
	$sql = 'SELECT * FROM templates WHERE id = ' . $_POST['template_id'];
	$results = mysqli_query(DB::$conn,$sql);
	if (mysqli_num_rows($results) == 1) {
		$template = mysqli_fetch_assoc($results);
	}
}


if(!isset($template['id']) && isset($_POST['template_id'])) {
    $template['id'] = $_POST['template_id'];
}


if (isset($_POST['update_working_template'])) {
	updateWorkingTemplateData(processTemplatePOSTData());
}


// Set $stage
if (isset($_GET['step'])) {
	$step = $_GET['step'];
} else {
	$step = 0;
}

//   if (!isset($template)) {
//    $stage = 0;
//   } else if (isset($data['complete'])) {
//       $stage = 3;
//   } else if (isset($data['finish'])) {
//       $stage = 2;
//   } else {
//       $stage = 1;
//   }
// }





?>
