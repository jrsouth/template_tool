<?php
/**
 * settings-core.php
 *
 * @author jrsouth (GitHub)
 * @package template_tool
 *
 * Resets all variables to default values before loading install-specific settings and any overrides from settings.php
 */

$debug = false;

$thumbnail_size = 150; // Size in px (square bounding box)
$preview_size = 300;  // Size in px (horizontal width)
$highres_size = 1200;  // Size in px (square bounding box)

$draw_grid = false; // Draw a reference grid to check positioning
$cache_location = 'cache/'; // Relative to the settings.php file (requires a trailing slash)

$default_templates_layout = 'grid';
$default_templates_available = 'active';

require('settings.php');

