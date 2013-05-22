<?php
/**
 * settings-default.php
 *
 * @author jrsouth (GitHub)
 * @package template_tool
 */


$debug = true;

$thumbnail_size = 150; // Size in px (square bounding box)
$preview_size = 300;  // Size in px (horizontal width)
$highres_size = 1200;  // Size in px (square bounding box)

$draw_grid = false; // Draw a reference grid to check positioning
$cache_location = 'cache/'; // Relative to the settings.php file (requires a trailing slash)

$db_server = 'localhost:3306';
// $db_database = 'template_tool';
$db_database = 'template_tool_working';
$db_user = 'ttuser';
$db_password = 'ttpassword';

$default_templates_layout = 'grid';
$default_templates_available = 'active';

require('settings.php');

?>
