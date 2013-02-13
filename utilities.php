<?php
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
  echo('<div class="'.($success?'success':'failure').'">'.$message.'</div>');
}

?>

<h1>Template tool utilities</h1>

<p>Clear template cache (<a href="utilities.php?action=clearTemplateCache">Execute</a>)</p>

<p>Clear working templates (<a href="utilities.php?action=clearWorkingTemplates&limit=2W">Older than two weeks</a> | <a href="utilities.php?action=clearWorkingTemplates&limit=2W">All</a>)</p>

<p>Clear image upload cache (<a href="utilities.php?action=clearImageUploadCache&limit=2W">Older than two weeks</a> | <a href="utilities.php?action=clearImageUploadCache&limit=2W">All</a>)</p>



</body>

</html>