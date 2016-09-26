<?php
/**
 * installer.php
 *
 * @author jrsouth (GitHub)
 * @package template_tool
 */

// Require authentication (super simple and not particularly secure)

// Code for managing php_fpm
// Needs lines below in .htaccess
//
// RewriteEngine on
// RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization},L]


// split the user/pass parts

if (!isset($_SERVER['PHP_AUTH_USER'])) {
    list($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']) = explode(':', base64_decode(substr($_SERVER['HTTP_AUTHORIZATION'], 6)));
    if (strlen($_SERVER['PHP_AUTH_USER']) == 0 || strlen($_SERVER['PHP_AUTH_PW']) == 0) {
        unset($_SERVER['PHP_AUTH_USER']);
        unset($_SERVER['PHP_AUTH_PW']);
    }
}

// Do the Authentication
require('settings-core.php');
if (!isset($_SERVER['PHP_AUTH_USER']) || $_SERVER['PHP_AUTH_USER'] != $editor_username || $_SERVER['PHP_AUTH_PW'] != $editor_password) {
    header('WWW-Authenticate: Basic realm="Template Editor"');
    header('HTTP/1.0 401 Unauthorized');
    echo('You must be authorised to access this page.');
    exit;
}
unset($editor_username, $editor_password);


// Require function and class files, and run process.php to handle input
require 'functions.php';
require 'functions-editor.php';
require 'process.php';



// Output XML header
echo '<?xml version="1.0" encoding="utf-8" ?>';

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">

<head>
	<title>Template Tool Database Installer</title>
	<link rel="stylesheet" type="text/css" href="style-editor.css" />
	<script src="functions.js" type="text/javascript"> </script>
	<script src="functions-editor.js" type="text/javascript"> </script>
</head>

<body>


<?php require $content_path . 'header.chunk'; ?>


<?php // Link out to editor
  echo '<br style="clear:both;"/><a href="editor.php">&lt;&lt; Back to the template editor</a>';
?>

<hr />


<?php

// Check for existing installation

$result = mysqli_query(DB::$conn,'SELECT COUNT(id) FROM colours');
if (mysqli_error(DB::$conn)) {
  $db_exists = 0;
} else {
  $db_exists = 1;
}

echo('<br /><br />');

if (isset($_POST['confirm']) && (strtolower($_POST['confirm']) === 'yes')) {

	// DO THE DATABASE DROP/LOAD
        echo('<pre>');
	// CODE STOLEN FROM http://stackoverflow.com/a/19752106

	echo('Loading up a fresh database... ');

	$importfile = 'install/default_database.sql';
	// Temporary variable, used to store current query
	$templine = '';
	// Read in entire file
	$lines = file($importfile);
	// Loop through each line
	foreach ($lines as $line)
	{
		// Skip it if it's a comment
		if (substr($line, 0, 2) == '--' || $line == '')	
		    continue;
	
		// Add this line to the current segment
		$templine .= $line;
		// If it has a semicolon at the end, it's the end of the query
		if (substr(trim($line), -1, 1) == ';')
		{
		    // Perform the query
		    mysqli_query(DB::$conn,$templine) or print('Error performing query \'<strong>' . $templine . '\': ' . mysqli_error() . '<br /><br />');
		    // Reset temp variable to empty
		    $templine = '';
		}
	}
	 echo "Successful\n";


       // DELETE TEMPLATE FILES (INCLUDING IMAGES AND FONTS)
       echo('Removing any existing template files... ');
	$files = array_merge(
	  glob('storage/templates/*'),
	  glob('storage/templates/default_images/*'),
	  glob('storage/fonts/*')
	);
	if ($files) {
	  foreach ($files as $file) {
            if($file == '.gitignore') continue;
	    if(filetype($file) == 'dir') continue;
	    unlink($file);
	  }
	}
       echo("Removed\n");


       // COPY TEST TEMPLATE AND DEFAULT FONTS (LUCIDA SANS UNICODE / ROBOTO VARIANTS)
       echo('Copying sample template files... ');

	copy('install/test_template.pdf', 'storage/templates/000000_test_template.pdf');
	$files = glob('install/fonts/*');
	if ($files) {
	  foreach ($files as $file) {
	    copy($file, 'storage/fonts/'.basename($file));
	  }
	}

       echo("Copied\n");


       // CLEAR CACHES
       echo('Clearing caches... ');
	$files = glob('cache/*/*');
	if ($files) {
	  foreach ($files as $file) {
            if($file == '.gitignore') continue;
	    if(filetype($file) == 'dir') continue;
	    unlink($file);
	  }
	}
       echo("Cleared\n");

       echo('</pre><p>Assuming no errors were reported, you should be able to test out the default template <a href="index.php">here</a>.</p>');

} else {
	echo('<div style="border: 2px solid #cc0000; padding: 1em 2em; margin: 1em 3em; background-color: #ffcccc;">');
	if ($db_exists) {
	    echo('<p style="font-size:1.5em;color:#cc0000;font-weight:bold;">CAUTION: The database referred to in your <strong>settings.php</strong> file ("'.$db_database.'" on '.$db_server.') already contains data. Proceeding will reset the database to its default state and will remove any templates, fonts, and colours that may be stored in it.');
	} else {
	    echo('<p style="font-size:1.5em;color:#cc0000;font-weight:bold;">CAUTION: The database referred to in your <strong>settings.php</strong> file ("'.$db_database.'" on '.$db_server.') appears to be empty, but please be aware that proceeding will reset the database to a default state, removing any templates, fonts, and colours that may be stored in it.');
	}

	// PRESENT OPTION TO CONFIRM PROCESS
	echo('<hr style="border: 1px solid #cc0000;"/><form method="POST"><p style="font-size:1.2m;font-weight:bold;">Do you understand that any template data that may exist in the "'.$db_database.'" database on '.$db_server.' will be permanently removed and the database returned to a freshly-installed state?</p><input type="text" name="confirm" label="Type YES to confirm" /> <sub>(Type "YES" to confirm)</sub></p><input type="Submit" />'.(isset($_POST['confirm'])?' You must type "YES" into the input box to proceed.':'').'</form></div>');
}

    

?>
<br style="clear:both;" />
</body>

</html>

