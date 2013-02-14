<?php
/**
 * main.php
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
	<title>LLR - Template Tool</title>
	<link rel="stylesheet" type="text/css" href="style.css" />
	<script src="functions.js" type="text/javascript"> </script>
</head>

<body>

<header>
<h1>Leukaemia &amp; Lymphoma Research<br />
<span style="font-size:75%;color:#7c7c7c;">Template Tool
<span style="font-size:75%;color:#acacac;"><sub>ALPHA</sub></span></span>
</h1>

<p>A basic proof-of-concept for a Leukaemia &amp; Lymphoma Research templating tool, with the goal of allowing supporters and staff members to independently generate on-brand posters, invitations and other materials.</p>
</header>

<?php

displayProgressBar($stage);


switch ($stage) {
case 0 :
?>

<div id="step2">
<h2><span style="color:#000000;">Step 1:</span> Select base template</h2>
<!-- Code to retrieve availabe templates from the database and select one -->
<?php
    displayAvailableTemplates()
?>


</div>


<?php
    break;
case 1 :
?>


<div id="step2">
<h2><span style="color:#000000;">Step 2:</span> Customise content and review</h2>
<!-- Form to provide customisable data (submission to return to this page with updated preview) -->
<div id="form-box">
<?php displayForm(); ?>
</div>
<!-- Display preview -->
<div id="preview-box">
<?php displayCurrentPreview(); ?>
</div>


<?php
    break;
case 2 :
?>


<div id="step3">
<h2><span style="color:#000000;">Step 3:</span> Create and download PDF</h2>
<!-- Final preview with download option -->
</div>


<?php
    break;
case 3 :
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
