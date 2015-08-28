<?php
/**
 * functions.php
 *
 * @author jrsouth (GitHub)
 * @package template_tool
 * @see editor.php
 * @see index.php
 * @see main.php
 */


/**
 *
 *
 * @param unknown $msg
 */
function debug($msg) {
	global $debug;

	if ($debug) {
		echo '<div style="background-color:#ffcccc;border:#ff0000 1px solid;padding:5px;">';
		echo '<strong>Debug message:</strong><br />';
		echo '<pre>';
		echo $msg;
		echo '</pre>';
		echo '</div>';
	}

}


/**
 *
 *
 * @param unknown $step
 */
function displayProgressBar($step) {

	$index = $step-1; // Normalise for 0-index array versus 1-index "steps"
	$stages = array();
	$stages[] = array('Select a template', 'index.php?step=1');
	$stages[] = array('Customise it', '');
	//$stages[] = array('Finish up', '');
	//$stages[] = Array('Complete', '');

	// Display code
	$flag = 0;
	$start = 1;
	$end = count($stages)-1;
	echo '<div id="progressbar"><p>';

	foreach ($stages as $key => $stage) {

		echo '<span class="progressbar-text'.(($key == $index)?'-active':'').(($key == 0)?' progressbar-text-first':'').'">';
		$isLink = ($key != $index && $stage[1]!='');
		echo ($isLink?'<a href="'.$stage[1].'">':'').$stage[0].($isLink?'</a>':'');
		echo '</span>';

		if ($key != $end) {
			$jointype = 'normal';
			if ($key == $index) {$jointype = 'off';}
			if ($key+1 == $index) {$jointype = 'on';}
			echo ' &#0187; ';
		}
	}

	echo '</p></div>';


	// echo('<a href="index.php"><p class="main-link">Restart</p></a>');

}


/**
 *
 */
function displayAvailableTemplates() {
global $default_templates_layout, $default_templates_available;

	$layouts = Array('grid', 'list');
	$access_levels = Array('active', 'all');
	$tags = getAllTags();
	$tags_active = getActiveTags();
	$tags_active_string = getListOfTags($tags_active);

	$templates_layout = $default_templates_layout;
	if (isset($_GET['templates_layout']) && array_search($_GET['templates_layout'], $layouts) !== false) {
	    $templates_layout = $_GET['templates_layout'];
	}

	$templates_available = $default_templates_available;
	if (isset($_GET['templates_available']) && array_search($_GET['templates_available'], $access_levels) !== false) {
	    $templates_available = $_GET['templates_available'];
	}


	// Display options heading
	echo('<p class="inline-selector">');

	echo('Display as [ ');
	foreach ($layouts as $key => $layout) {
	    if ($layout == $templates_layout) {
		echo('<strong>' . $layout . '</strong> ');
	    } else {
		echo('<a href="'.$_SERVER['PHP_SELF'].'?step=1&tags='.$tags_active_string.'&templates_layout='.$layout.'&templates_available='.$templates_available.'" title="Display templates as '.$layout.'">'.$layout.'</a> ');
	    }
	    if ($key+1 < count($layouts)) {
		echo('&#124; ');
	    }
	}

	echo(' ]');

/*
	echo('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
	echo(' Access [ ');
	foreach ($access_levels as $key => $access_level) {
	    if ($access_level == $templates_available) {
		echo('<strong>' . $access_level . '</strong> ');
	    } else {
		echo('<a href="'.$_SERVER['PHP_SELF'].'?step=1&tags='.$tags_active_string.'&templates_layout='.$templates_layout.'&templates_available='.$access_level.'" title="Display '.$access_level.' templates">'.$access_level.'</a> ');
	    }
	    if ($key+1 < count($access_levels)) {
		echo('&#124; ');
	    }
	}
	echo(' ]');
*/

	echo('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
	echo(' Filter by [ ');
	foreach ($tags as $key => $tag) {
	    if (array_search($tag, $tags_active) !== false) {
		$tags_new_string = getListOfTags($tags_active, $tag);
		echo('<strong><a href="'.$_SERVER['PHP_SELF'].'?step=1&tags='.$tags_new_string.'&templates_layout='.$templates_layout.'&templates_available='.$templates_available.'" title="Hide &quot;'.$tag.'&quot; templates">'.$tag.'</a></strong> ');
	    } else {
		$tags_new_string = getListOfTags($tags_active) . '+' . $tag;
		echo('<a href="'.$_SERVER['PHP_SELF'].'?step=1&tags='.$tags_new_string.'&templates_layout='.$templates_layout.'&templates_available='.$templates_available.'" title="Display &quot;'.$tag.'&quot; templates">'.$tag.'</a> ');
	    }
	    if ($key+1 < count($tags)) {
		echo('&#124; ');
	    }
	}
	echo(' ]');

	if (count($tags_active) > 0) {
		echo(' <a class="action-link" href="'.$_SERVER['PHP_SELF'].'?step=1&templates_layout='.$templates_layout.'&templates_available='.$templates_available.'" title="Hide &quot;'.$tag.'&quot; templates"> clear filters</a>');
	}


	echo('</p>');


	echo('<hr />');


	// HACK Need better SQL generation

	// Generate default, always-true, "WHERE" SQL
	$where_clause = '1=1';
	// active?	
	$where_clause .= ($templates_available == 'active'?' AND `active` = 1':'');
	// tags
	$tags_clause = '';
	foreach ($tags_active as $key => $tag) {
		$tags_clause .= '`tags` LIKE "%' . $tag . '%"' . ($key+1 < count($tags_active)?' AND ':'');
	}
	if (count($tags_active) > 0) {
		$where_clause .= ' AND ( ' . $tags_clause . ' )';
	}
	$sql = 'SELECT * FROM `templates` WHERE '. $where_clause .' ORDER BY `name`';

	$results = mysql_query($sql);

	if (mysql_num_rows($results)) {
	    echo('<div class="template-display-'.$templates_layout.'">');
	    $odd_row = true;
	    while ($template = mysql_fetch_assoc($results)) {
		    echo '<div'.($odd_row?' class="odd" ':'').'>';
		    echo '<p>';
		    echo '<a href="index.php?template_id='.$template['id'].'&reset=1&step=2">'.$template['name'].'</a>';
		    echo '</p>';
		    echo '<a href="index.php?template_id='.$template['id'].'&reset=1&step=2"><img src="tools/createPDF.php?view=thumbnail&template_id='.$template['id'].'" class="thumb" /></a>';
		    echo '<br class="clear" />';
		echo('</div>');
		$odd_row = !$odd_row;
		}
	    echo('</div>');
	} else { // No results
		echo('<p style="padding:2em 1em 0 1em;"><em>No matching templates found.');
		if (count($tags_active) > 0) {
			echo(' (<a class="action-link" href="'.$_SERVER['PHP_SELF'].'?step=1&templates_layout='.$templates_layout.'&templates_available='.$templates_available.'" title="Hide &quot;'.$tag.'&quot; templates">Try clearing the filters</a>.)');
		}
		echo('</em></p>');

	}

}


/**
 *
 */
function displayCurrentPreview() {
	global $template, $working_template_id, $preview_size;

	$params = '&template_id='.$template['id'];

	$sql = 'SELECT * FROM images WHERE template_id = '. $template['id'];
	$results = mysql_query($sql);
	while ($image = mysql_fetch_assoc($results)) {
		if (isset($image_locations['img'.$image['id']])) {
			$params .= '&img'.$image['id'].'='.$image_locations['img'.$image['id']];
		}
	}

	$sql = 'SELECT * FROM fields WHERE template_id = '. $template['id'];
	$results = mysql_query($sql);
	while ($field = mysql_fetch_assoc($results)) {
		if (isset($_POST['f'.$field['id']])) {
			// Dodgy replacement of newlines with url encoded newlines
			$params .= '&f'.$field['id'].'='.urlencode($_POST['f'.$field['id']]);
			// ORIG: $params .= '&f'.$field['id'].'='.preg_replace("/\n/", "%0D%0A", ($_POST['f'.$field['id']]));
		}
	}

	if ($working_template_id > 0) {
		for ($currentPage = 1; $currentPage <= $template['pagecount'] ; $currentPage++) {
			echo('<img src="images/cursor.png" class="field-locator" id="fieldLocator'.$currentPage.'">');
			echo '<img id="previewPage'.$currentPage.'" width="'.$preview_size.'" src="tools/createPDF.php?working_template_id='.$working_template_id.'&view=preview&page='.$currentPage.(isset($_GET['reset'])?'&reset=1':'').'" class="preview" />';
			echo '<br />';
		}

	} else {
		for ($currentPage = 1; $currentPage <= $template['pagecount'] ; $currentPage++) {
			echo('<img src="images/cursor.png" class="field-locator" id="fieldLocator'.$currentPage.'" onload="rotateElement(this);">');
			echo '<img id="previewPage'.$currentPage.'" width="'.$preview_size.'" src="tools/createPDF.php?template_id='.$template['id'].'&view=preview&page='.$currentPage.(isset($_GET['reset'])?'&reset=1':'').'" class="preview" />';
			echo '<br />';
		}
	}

}


/**
 *
 */
function processTemplatePOSTData() {
	global $template, $image_locations, $working_template_id;

	$params = '&template_id='.$template['id'];

	$sql = 'SELECT * FROM images WHERE template_id = '. $template['id'];
	$results = mysql_query($sql);
	while ($image = mysql_fetch_assoc($results)) {
		if (isset($image_locations['img'.$image['id']])) {
			$params .= '&img'.$image['id'].'='.$image_locations['img'.$image['id']];
		}
	}

	$sql = 'SELECT * FROM fields WHERE template_id = '. $template['id'];
	$results = mysql_query($sql);
	while ($field = mysql_fetch_assoc($results)) {
		if (isset($_POST['f'.$field['id']])) {
			// Dodgy replacement of newlines with url encoded newlines
			$params .= '&f'.$field['id'].'='.urlencode($_POST['f'.$field['id']]);
		}
	}

	return $params;
}


function updateWorkingTemplateData($params) {
	global $template, $working_template_id;

	// Database storage rather than URL query string
	if ($working_template_id > 0) {
		$sql = 'UPDATE `working_templates` SET `data` = "'.$params.'" WHERE `id` = ' . $working_template_id;
		mysql_query($sql);
	} else {
		$sql = 'INSERT INTO `working_templates` SET `template_id` = '.$template['id'].', `data` = "'.$params.'"';
		mysql_query($sql);
		$working_template_id = mysql_insert_id();
	}
}


/**
 *
 *
 * @param unknown $pdffile
 * @param unknown $view
 * @param unknown $templateID
 * @param unknown $page       (optional)
 */
function displayPDF($pdffile, $view, $templateID, $page = 1) {
	global $preview_size, $thumbnail_size, $highres_size, $cache_path;
	// Displays a JPEG preview of a given PDF file, saving a thumbnail if applicable
	// $pdffile is the file
	// $view is the type of view (thumbnail/preview)
	// $templateID is the base template's ID - only used for saving previews
	// $page is the page number to generate the preview for
	// DisplayPDF is used for outputting images of single-page PDFs

	$thumbnail_location = $cache_path . 'thumbnails/template_'.$templateID.'.png';
	$preview_location = $cache_path . 'default/template_'.$templateID.'_p' . $page . '.png';

	if ($view == 'preview') {
		$xsize = $preview_size;
		$ysize = 2000;
		$resolution = 600;
	} else if ($view == 'highres') {
		$xsize = $highres_size;
		$ysize = $highres_size;
		$resolution = 600;
	} else {
		$xsize = $thumbnail_size;
		$ysize = $thumbnail_size;
		$resolution = 100;
	}


	$gsCommand = 'gs -dSAFER -dBATCH -dNOPAUSE -sDEVICE=png16m -dTextAlphaBits=4 -dGraphicsAlphaBits=4 -r' . $resolution . ' -sOutputFile='. $pdffile . '.png ' . $pdffile;
	exec($gsCommand);

	$im = new Imagick($pdffile . '.png');

	// Resize to correct dimensions
	$im->resizeImage ($xsize, $ysize, Imagick::FILTER_LANCZOS, 1, true);
//XXX	$im->scaleImage ($xsize, $ysize, true);

	if ($view == 'thumbnail') {
		$im->writeImage($thumbnail_location);
	} else if ($view == 'preview' && isset($_GET['reset']) && $_GET['reset'] == 1) {
		$im->writeImage($preview_location);
	}

        if (isset($_GET['download']) && $_GET['download'] == 1) { 
                header("Pragma: public");
                header("Expires: 0");
                header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                header("Cache-Control: public");
                header("Content-Description: File Transfer");
                header("Content-Type: application/octet-stream");
                header("Content-Disposition: attachment; filename=\"" . getTemplateName($templateID) . date(' d-m-Y H:i')  . ".png\"");
                header("Content-Transfer-Encoding: binary");
        } else {
		header( "Content-Type: image/png" );
	}

	
	echo $im;
	$im->destroy();

	unlink($pdffile . '.png');



	/* // Imagemagick Solution
	$im = new imagick();
	$im->setResolution($resolution, $resolution);
	$im->readImage($pdffile);
	$im = $im->flattenImages();
	$im->setCompression(Imagick::COMPRESSION_JPEG);
	$im->setCompressionQuality(90);
	$im->setImageFormat("jpeg");
	$im->thumbnailImage($xsize, $ysize, true);
	*/
}


function getTemplateName($template_id) {
	$sql = 'SELECT `name` FROM `templates` WHERE `id` = ' . $template_id;
	$result = mysql_fetch_assoc(mysql_query($sql));
echo('Template ID: ' . $template_id . ' || Name = ' . $result['name']);
	return($result['name']);
}

/**
 *
 *
 * @param unknown $template_id
 * @param unknown $pageno      (optional)
 * @return unknown
 */
function get_fields($template_id, $pageno = 0) {
	// Gets and sorts fields based on y_position and parent relationships
	$page_restriction = '';
	if ($pageno > 0) {
		$page_restriction = ' AND `page` = ' . $pageno;
	}

	// Get base parents (i.e. no parent of their own) and drop into an array
	$sql = 'SELECT * FROM `fields` WHERE `template_id` = '. $template_id . $page_restriction . ' AND `parent` = 0 ORDER BY `page`, `y_position`, `name`';
	$results = mysql_query($sql);
	$fields = array();
	while ($field = mysql_fetch_assoc($results)) {
		$fields[] = $field;
	}

	// Get children (i.e. with a parent of their own)
	$sql = 'SELECT * FROM `fields` WHERE `template_id` = '. $template_id . $page_restriction . ' AND `parent` > 0 ORDER BY `parent`';
	$results = mysql_query($sql);
	$children = array();
	while ($field = mysql_fetch_assoc($results)) {
		$children[] = $field;
	}

	// Cycle through $children array inserting after the parent
	// Repeat until empty, or until looping without change
	$loopflag = -1;
	while (count($children) > 0) {
		if ($loopflag == 0) {
			break; // Prevent infinite loop
		}
		$loopflag = 0;
		foreach ($children as $keyc => $child) {
			foreach ($fields as $keyp => $parent) {
				if ($child['parent'] == $parent['id']) {
					$loopflag++;
					array_splice($fields, $keyp+1, 0, array(array_merge($child)));
					unset($children[$keyc]);
				}
			}
		}
	}

	// Should probably do some double checking before returning...
	return $fields;
}





/**
 *
 *
 * @param unknown $template_id
 * @param unknown $pageno      (optional)
 * @return unknown
 */
function get_images($template_id, $pageno = 0) {
	// Gets images array sorted by y_position
	$page_restriction = '';
	if ($pageno > 0) {
		$page_restriction = ' AND `page` = ' . $pageno;
	}
	// Get sorted images
	$sql = 'SELECT * FROM `images` WHERE `template_id` = '. $template_id . $page_restriction . ' ORDER BY `page`, `y_position`';
	$results = mysql_query($sql);
	$images = array();
	while ($image = mysql_fetch_assoc($results)) {
		$images[] = $image;
	}
	return $images;
}





/**
 *
 */
function displayForm() {
	global $template, $working_template_id, $preview_size;

	// Display download box if not a blank template
	if ($working_template_id > 0) { displayDownloadBox(); };


	$images = get_images($template['id']);
	$fields = get_fields($template['id']);
	$scale = $preview_size/$template['width'];

	echo '<form action="index.php?step=2" method="POST" enctype="multipart/form-data">';
	echo '<input type="hidden" name="template_id" value="'.$template['id'].'" />';

	if ($working_template_id > 0) {
		echo '<input type="hidden" name="working_template_id" value="'.$working_template_id.'" />';
	}

	echo '<input type="submit" name="update_working_template" value="Update the preview" />';


	foreach ($images as $image) { // display image fields in order
          if ($image['hide'] != 1) {

		echo '<p><span class="field-title">'.$image['name'].': </span> ';
		echo '<span class="field-character-count"> (JPEG, PNG or GIF images only)</span><br />';

		$local_imgfile = ''; // Set up default value
		if (isset($_POST['img'.$image['id'].'hidden'])) { // Old value passed
			$local_imgfile = $_POST['img'.$image['id'].'hidden'];
		}
		if (isset($_FILES['img'.$image['id']]) && isset($_FILES['img'.$image['id']]['upload'])) { // New image file passed
			$local_imgfile = $_FILES['img'.$image['id']]['upload'];
		}

		echo '<input type="file" onmouseover="this.focus();" onfocus="showFieldLocator('.($image['x_position'] + 0.5*$image['width']).','.($image['y_position'] + 0.5*$image['height']).','.$image['page'].','.$scale.');" onblur="hideFieldLocator('.$image['page'].');" class="file-input" name="img'.$image['id'].'" />';

		if ($local_imgfile != '') { // Insert hidden form field if there's a value (and print)
			echo '<input type="hidden" name="'.('img'.$image['id'].'hidden').'" value="'.$local_imgfile.'" />';
			echo '<br /><span class="field-current-image"><strong>&nbsp;&nbsp;&nbsp;&bullet;&nbsp;Current file:</strong> '.substr(basename($local_imgfile), 14).'</span></p>';
		}
          }
	}
	
	$parentLocation_x = 0;
	$parentLocation_y = 0;

	foreach ($fields as $field) { // display fields in order
    
	    if ($field['type'] != 'wrapper') { // Don't display 'wrapper' fields
	
		$value = (isset($_POST['f'.$field['id']])?$_POST['f'.$field['id']]:$field['default_text']);


		echo '<p><span class="field-title">'.$field['name'].': </span> ';
		echo '<span class="field-character-count"> (Up to '.$field['character_limit'].' characters)</span><br />';

		$type = ($field['wrap_width'] > 0 || $field['parent'] > 0)?"multi_line":"single_line";
		
		if ($field['parent'] == 0) {
		  $parentLocation_x = 0;
		  $parentLocation_y = 0;
		}
		
		$location_x = $parentLocation_x + $field['x_position'];
		$location_y = $parentLocation_y + $field['y_position'] - ($type=='single_line'?1:-1) * $field['font_size']/5.66929133501;
		
		switch ($type) {
		case 'single_line' :
			echo '<input type="text" maxlength="'.$field['character_limit'].'" name="f'.$field['id'].'" value="'.$value.'"  onfocus="'. (($field['type'] != 'data')?'showFieldLocator('.$location_x.','.$location_y.','.$field['page'].','.$scale.');':'hideFieldLocator('.$field['page'].');') .  '" onblur="hideFieldLocator('.$field['page'].');"/><br />';
			break;

		case 'multi_line' :
			echo '<textarea onfocus="showFieldLocator('.$location_x.','.$location_y.','.$field['page'].','.$scale.');" onblur="hideFieldLocator('.$field['page'].');" onKeyDown="javascript:limitText(this.form.f'.$field['id'].','.$field['character_limit'].',null)" rows="4" name="f'.$field['id'].'">'.$value.'</textarea><br />';

		}
		echo '</p>';
		
		if ($field['parent'] == 0) {
		  $parentLocation_x = $field['x_position'];
		  $parentLocation_y = $field['y_position'];
		} else {;
		  $parentLocation_y += $field['y_position'] + $field['leading'] * 2 * 0.352777778;
		}
	    
	    }
		
	}

	echo '<input type="submit" name="update_working_template" value="Update the preview" />';

	echo '</form>';
}


/**
 *
 *
 * @param unknown $pdf
 * @param unknown $imgfile
 * @param unknown $bbxloc
 * @param unknown $bbyloc
 * @param unknown $bbwidth
 * @param unknown $bbheight
 * @param unknown $placement
 */
function placeImage($pdf, $imgfile, $bbxloc, $bbyloc, $bbwidth, $bbheight, $placement) {
	// $placement not yet used, defaults to centered
	// Intended options: center (default), fill, ul, ur, ll, lr
	global $cache_path;

	// Temp code to check file paths

	$placement = strtolower($placement);
	switch ($placement) {
	case 'fill' :
	case 'ul' :
	case 'ur' :
	case 'll' :
	case 'lr' : break;
	default: $placement = 'center';
	}

	if ($placement == 'fill') { // Generate a cropped, correct aspect ratio temp image
		$imgsize = getimagesize($imgfile);
		$imgratio = $imgsize[0]/$imgsize[1];
		$bbratio = $bbwidth/$bbheight;

		//echo('Performing "FILL" calculations...<br />');

		if ($imgratio != $bbratio) { // No cropping if ratios match

			$tmpimg = $cache_path . 'img/'.uniqid('TEMPIMG').'.jpg';

			$im = new imagick($imgfile);

			//echo('Original: ' .$im->getImageWidth(). 'x' .$im->getImageHeight(). '<br />');

			if ($imgratio < $bbratio) { // Image too tall
				//echo('Image too tall!<br />');
				//echo('Cropping to '.$im->getImageWidth().'x'.$im->getImageWidth()/$bbratio.'<br />');
				$im->cropImage($im->getImageWidth(), $im->getImageWidth()/$bbratio, 0, 0.5*($im->getImageHeight() - $im->getImageWidth()/$bbratio));
			} else { // Image too wide
				//echo('Image too wide!<br />');
				$im->cropImage($im->getImageHeight()*$bbratio, $im->getImageHeight(), 0.5*($im->getImageWidth() - $im->getImageHeight()*$bbratio), 0);
			}

			//echo('Cropped: ' .$im->getImageWidth(). 'x' .$im->getImageHeight(). '<br />');

			$im->setCompression(Imagick::COMPRESSION_JPEG);
			$im->setCompressionQuality(100);
			$im->setImageFormat("jpeg");
			$im->writeImage($tmpimg);

			$imgfile = $tmpimg;
		}
	}

	$imgsize = getimagesize($imgfile);
	$imgratio = $imgsize[0]/$imgsize[1];
	$bbratio = $bbwidth/$bbheight;

	if ($imgratio < $bbratio) { // Image too tall
		$width = $bbheight*$imgratio;
		$height = $bbheight;
	} else { // Image too wide (or exact fit)
		$width = $bbwidth;
		$height = $bbwidth/$imgratio;
	}

	// Set corner coordinates
	if (strlen($placement) == 2) {

		switch ($placement[0]) {
		case 'u' :
			$yloc = $bbyloc;
			break;
		case 'l' :
			$yloc = $bbyloc + $bbheight - $height;
			break;
		}

		switch ($placement[1]) {
		case 'l' :
			$xloc = $bbxloc;
			break;
		case 'r' :
			$xloc = $bbxloc + $bbwidth - $width;
			break;
		}

	} else { // Set "centered" coordinates
		$xloc = $bbxloc + 0.5*($bbwidth-$width);
		$yloc = $bbyloc + 0.5*($bbheight-$height);
	}

	$pdf->Image($imgfile, $xloc, $yloc, $width, $height);

}


function displayDownloadBox() {
	global $working_template_id;
        echo '<div id="download-box">';
        echo '<a href="tools/createPDF.php?working_template_id='.$working_template_id.'" target="llrtemplatepreview">View PDF</a>';
        echo ' | <a href="tools/createPDF.php?working_template_id='.$working_template_id.'&download=1" target="llrtemplatepreview">Download PDF</a>';
        echo ' <br /><a href="tools/createPDF.php?working_template_id='.$working_template_id.'&view=highres&page=1" target="llrtemplatepreview">View JPG</a>';
        echo ' | <a href="tools/createPDF.php?working_template_id='.$working_template_id.'&view=highres&page=1&download=1" target="llrtemplatepreview">Download JPG</a>';
        echo '</div><br />';
}


function getAllTags() {
        $tags = Array();

        $sql = 'SELECT DISTINCT `tags` FROM `templates` WHERE `tags` != ""'; // DISTINCT limits the redundant work that has to be done later
        $results = mysql_query($sql);

        // Loop through results and add new terms to the array 
        while ($template_subset = mysql_fetch_assoc($results)) {
                $subset = explode(' ', $template_subset['tags']);
                foreach ($subset as $tag) {
                        if (array_search($tag, $tags) === false) {
                                $tags[] = $tag;
                        }
                }
        }
        natcasesort($tags);
	$tags = array_values($tags);
        return($tags);
}

function getActiveTags() {
	$tags_active = Array();
	if (isset($_GET['tags']) && $_GET['tags'] != '') {
		$tags_active = explode(' ', trim($_GET['tags']));
	}
	
	return($tags_active);
}

function getListOfTags($tags_array, $tag_to_remove = null) {
	$tags_string = '';
	foreach ($tags_array as $tag) {
		if ($tag != $tag_to_remove && $tag != '') {
			$tags_string .= '+' . $tag;
		}
	}
	return($tags_string);
}
