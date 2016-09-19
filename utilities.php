<?php
/**
 * utilities.php
 *
 * @author jrsouth (GitHub)
 * @package template_tool
 */

 // Get database settings
 require('settings-core.php');

 // Create database connection
$db_connection = mysqli_connect($db_server, $db_user, $db_password) or die(mysqli_error());
mysqli_select_db($db_database) or die(mysqli_error());

/* ---------------------------------------------------------- /*
Various cleanup and tidy up scripts



NOTE
2 week code:

select `id`, `timestamp` from `working_templates` WHERE `timestamp` < DATE_SUB(NOW(), INTERVAL 2 WEEK);


/* ---------------------------------------------------------- */
$message = '';
$success = true;


if (isset($_GET['action'])) {

	switch ($_GET['action']) {


	case 'clearTemplateCache' :

		$message = 'Template cache cleared XXX NOT YET IMPLEMENTED';
		$success = false;
		break;



	case 'clearWorkingTemplates' :

		$message = 'Template cache cleared XXX NOT YET IMPLEMENTED';
		$success = false;
		break;



	case 'clearImageUploadCache' :

		$message = 'Image uploads cleared XXX NOT YET IMPLEMENTED';
		$success = false;
		break;

      case 'updatePDFDetails' :
      
		$message = '<strong>Updating PDF details (PageCount, Dimensions)</strong>';
		$success = true;
		
		$sql = "SELECT * FROM `templates` ORDER BY `id`";
		$results = mysqli_query(DB::$conn,$sql);
		while ($template = mysqli_fetch_assoc($results)) {
		  // Code adapted from AndrewR found at http://stackoverflow.com/questions/9622357/php-get-height-and-width-in-pdf-file-proprieties
		  $output = shell_exec('pdfinfo -box "storage/templates/' . $template['pdf_file'] . '"');
		  
		  // find page count
		  preg_match('/Pages:\s+([0-9]+)/', $output, $pagecountmatches);
		  $pagecount = $pagecountmatches[1];

		  // find page sizes
		  preg_match('/Page size:\s+([0-9]{0,5}\.?[0-9]{0,3}) x ([0-9]{0,5}\.?[0-9]{0,3})/', $output, $pagesizematches);
		  $width = round($pagesizematches[1]/2.83);
		  $height = round($pagesizematches[2]/2.83);
		  
		  
		  $sql = 'UPDATE `templates` SET `pagecount` = '.$pagecount.', `width` = '.$width.', `height` = '.$height.' WHERE `id` = '.$template['id'];
		  $result = mysqli_query(DB::$conn,$sql);
		  $message = $message . '<pre>'.$sql.'<br /> &gt;&gt; <strong>'.($result?'SUCCESS':'FAILED').'</strong></pre>';
		}
		break;

	}

}


?>

<html>

<head>

<style type="text/css">

* {
  font-family: sans-serif;
}

.success {
  margin: 10px;
  padding: 10px;
  border: 1px solid #666666;
  background-color: #ddffdd;
}

.failure {
  margin: 10px;
  padding: 10px;
  border: 1px solid #666666;
  background-color: #ddffdd;
}

a {
  text-decoration: none;
  color: #dd0000;
  background: transparent;
}

a:hover {
  color: #000000;
  background: #dd9999;
}

</style>

</head>


<body>

<?php

if ($message != '') {
	echo '<div class="'.($success?'success':'failure').'">'.$message.'</div>';
}

?>

<h1>Template tool utilities</h1>

<p>Clear template cache (<a href="utilities.php?action=clearTemplateCache">Execute</a>)</p>

<p>Clear working templates (<a href="utilities.php?action=clearWorkingTemplates&limit=2W">Older than two weeks</a> | <a href="utilities.php?action=clearWorkingTemplates&limit=2W">All</a>)</p>

<p>Clear image upload cache (<a href="utilities.php?action=clearImageUploadCache&limit=2W">Older than two weeks</a> | <a href="utilities.php?action=clearImageUploadCache&limit=2W">All</a>)</p>

<p>Update PDF sizes and page counts (<a href="utilities.php?action=updatePDFDetails">Execute</a>)</p>



</body>

</html>
