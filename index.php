<?php
//error_reporting(E_ALL);
//ini_set('display_errors', '1');

/**
 * index.php
 *
 * @author jrsouth (GitHub)
 * @package template_tool
 */


// Require function and class files, and run process.php to handle input
require 'functions.php';
require 'process.php';

// Output XML header
echo '<?xml version="1.0" encoding="utf-8" ?>';

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">

<head>
	<title><?php echo($site_name); ?> - Template Tool</title>
	<link rel="stylesheet" type="text/css" href="style.css" />
	<script src="functions.js" type="text/javascript"> </script>
</head>

<body>

<?php require $content_path . 'header.chunk'; ?>

<hr />

<?php

if ($step > 0) {
	displayProgressBar($step);
	echo('<hr />');
}

switch ($step) {
case 0 :

//	require $content_path . 'welcome.chunk';
//	break;

case 1 :
?>

<div id="step1">
<!-- <h2><span style="color:#000000;">Step 1:</span> Select base template</h2> NOTE REMOVED FOR STYLE -->
<!-- Code to retrieve availabe templates from the database and select one -->
<?php
	displayAvailableTemplates()
?>


</div>


<?php
	break;
case 2 :
?>


<div id="step2">
<!-- <h2><span style="color:#000000;">Step 2:</span> Customise content and review</h2> NOTE REMOVED FOR STYLE -->


<!-- Display preview -->
<div id="preview-box">
<?php displayCurrentPreview(); ?>
</div>

<!-- Form to provide customisable data (submission to return to this page with updated preview) -->
<div id="form-box">
<?php displayForm(); ?>
</div>



<?php
	break;
case 3 :
?>


<div id="step3">
<!-- <h2><span style="color:#000000;">Step 3:</span> Create and download PDF</h2> NOTE REMOVED FOR STYLE -->
<!-- Final preview with download options NOTE not yet implemented -->
</div>


<?php
	break;
case 4 :
?>


<div id="step4">
<h2><span style="color:#000000;">Complete</span></h2>

<!-- Exit options including "start again" -->

</div>

<?php
	break;
}
?>
<br style="clear:both;" />
</body>

</html>
