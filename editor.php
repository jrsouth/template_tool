<?php

/**
 * editor.php
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
	<title><?php echo($site_name); ?> - Template Tool Field Editor</title>
	<link rel="stylesheet" type="text/css" href="style-editor.css" />
	<script src="functions.js" type="text/javascript"> </script>
	<script src="functions-editor.js" type="text/javascript"> </script>
</head>

<body>

<?php require $content_path . 'header.chunk'; ?>


<?php // Put restart link in if template already selected
if (isset($_GET['template_id']) || isset($_POST['template_id'])) {
    echo '<br style="clear:both;"/><a href="'.$_SERVER['PHP_SELF'].'">&lt;&lt; Back to template selection</a>';
}
?>

<hr />


<?php

if (isset($_POST['template_id']) && $_POST['template_id'] != 'new') {

    // Clear out cached previews and thumbnail
    clearTemplateCache($_POST['template_id']);

    if (isset($_GET['action']) && $_GET['action'] == 'duplicate') {

	// Duplicate existing template
	//
	// Needs error checking/confirmation of success. 
	// Maybe give option for new name as part of process instead of appending "(copy)"?

	// Get current template setting
    	$template = getTemplate($_POST['template_id']);

	// Get current template fields
	$fields = getTemplateFields($_POST['template_id']);

	// Get current template images
	$images = getTemplateImages($_POST['template_id']);


	// Create new template: "Template Name (copy)"

	$sql = 'INSERT INTO `templates` VALUES (
		NULL,
		"' . $template['name'] . ' (copy)",
		"' . $template['tags'] . '",
		"' . $template['pdf_file'] . '",
		"' . $template['permissions'] . '",
		' . $template['bleed'] . ',
		"' . $template['owner'] . '",
		' . $template['active'] . ',
		' . $template['pagecount'] . ',
		' . $template['height'] . ',
		' . $template['width'] . '
		)'; 
	mysqli_query(DB::$conn,$sql);
	$new_template_id = mysqli_insert_id(DB::$conn);


        // Add fields to new template
	// XXXX TODO Still Needs to account for parent-child relationships between fields

	foreach ($fields as $field) {

	$sql = 'INSERT INTO `fields` VALUES (
		NULL,
		' . $new_template_id . ',
		"' . $field['type'] . '",
		"' . $field['name'] . '",
		"' . $field['default_text'] . '",
		' . $field['force_uppercase'] . ',
		' . $field['character_limit'] . ',
		' . $field['font_id'] . ',
		' . $field['font_size'] . ',
		' . $field['colour_id'] . ',
		' . $field['x_position'] . ',
		' . $field['y_position'] . ',
		' . $field['wrap_width'] . ',
		' . $field['leading'] . ',
		' . $field['parent'] . ',
		' . $field['page'] . ',
		' . $field['kerning'] . '
		)';

	mysqli_query(DB::$conn,$sql);

	}

        // Add images to new template

	foreach ($images as $image) {

	$sql = 'INSERT INTO `images` VALUES (
		NULL,
		' . $new_template_id . ',
		"' . $image['name'] . '",
		' . $image['x_position'] . ',
		' . $image['y_position'] . ',
		' . $image['width'] . ',
		' . $image['height'] . ',
		"' . $image['alignment'] . '",
		' . $image['page'] . '
		)';

	mysqli_query(DB::$conn,$sql);

	}

	// Make the page display the new template rather than the current one
	$_POST['template_id'] = $new_template_id;

    }


    if (isset($_GET['removeField'])) { // Removing a field

        echo '<div class="delete-box"><h3>Delete</h3>';

        $deleteField = getField($_GET['removeField']);

        if (isset($_GET['confirm']) && $_GET['confirm'] == 'yes') {

            // DELETE FIELD
            $delete_sql = 'DELETE FROM `fields` WHERE `id` = '.$_GET['removeField'];
            if (mysqli_query(DB::$conn,$delete_sql)) {
                echo 'FIELD DELETED!';
            } else {
                echo 'AN ERROR HAPPENED :(';
            }

        } else { // Get confirmation

            echo 'Are you sure you want to delete <strong>"'.$deleteField['name'].'"</strong> from <strong>"'.$template['name'].'"</strong>?<br />';
            echo '<a href="'.$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'].'&confirm=yes">Delete</a> | <a href="'.$_SERVER['PHP_SELF'].'?template_id='.$template['id'].'">Cancel</a>';

        }

        echo '</div>';

    }

    if (isset($_GET['removeImage'])) { // Removing a field

        echo '<div class="delete-box"><h3>Delete</h3>';

        $deleteImage = getImage($_GET['removeImage']);

        if (isset($_GET['confirm']) && $_GET['confirm'] == 'yes') {

            // DELETE IMAGE
            $delete_sql = 'DELETE FROM `images` WHERE `id` = '.$_GET['removeImage'];
            if (mysqli_query(DB::$conn,$delete_sql)) {
                echo 'IMAGE DELETED!';
            } else {
                echo 'AN ERROR HAPPENED :(';
            }

        } else { // Get confirmation

            echo 'Are you sure you want to delete <strong>"'.$deleteImage['name'].'"</strong> from <strong>"'.$template['name'].'"</strong>?<br />';
            echo '<a href="'.$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'].'&confirm=yes">Delete</a> | <a href="'.$_SERVER['PHP_SELF'].'?template_id='.$template['id'].'">Cancel</a>';

        }

        echo '</div>';

    }


    // Get font and colour values

    $colours = getAllColours();
    $fonts = getAllFonts();
    $fields = getTemplateFields($_POST['template_id']);
    $images = getTemplateImages($_POST['template_id']);
    $template = getTemplate($_POST['template_id']);





    if (isset($_POST['savefield'])) { // If save field button has been pressed

        if (isset($_POST['create']) && $_POST['create'] == 'new') { // If it's a new field

            // Validate entered values - make sure numbers are numbers, etc.
            $validation = validateFieldPostData('new');
            $flag = $validation['flag'];
            $msg = $validation['msg'];
            $sql = $validation['sql'];

            $insert_sql = 'INSERT INTO `fields` SET `id` = NULL, ' . $sql;

            if (!$flag && mysqli_query(DB::$conn,$insert_sql)) {
                echo 'FIELD INSERTED!<br />(Values are kept below in case you need to enter a similar new field)';
                // Update $fields to reflect new insertion
                $fields = getTemplateFields($_POST['template_id']);
            } else {
                debug('<p><strong>AN ERROR OCCURRED :( </strong></p><p>FLAG = '.$flag.'</p><p>$msg: <br />'.$msg.'</p><p>SQL statement:<br />'.$insert_sql.'</p><p>mysqli_error: '.mysqli_error(DB::$conn).'</p>');
            }

        } else { // If it's an update to existing field(s)

            $update_error = false;

            foreach ($fields as $field) {
                // Validate entered values - make sure numbers are numbers, etc.
                $validation = validateFieldPostData($field['id']);
                $flag = $validation['flag'];
                $msg = $validation['msg'];
                $sql = $validation['sql'];

                $update_sql = 'UPDATE `fields` SET ' . $sql . ' WHERE `id` = ' . $field['id'];

                if ($flag || !mysqli_query(DB::$conn,$update_sql)) {
                    debug('<p><strong>AN ERROR OCCURRED :( </strong></p><p>FLAG = '.$flag.'</p><p>$msg: <br />'.$msg.'</p><p>SQL statement:<br />'.$update_sql.'</p>');
                    $update_error = true;
                }


            }

            if (!$update_error) {
                echo 'FIELD(S) UPDATED!';
                // Update $fields to reflect new insertions
                $fields = getTemplateFields($_POST['template_id']);
            }

        }

    }



// PROCESS IMAGE SAVING

   if (isset($_POST['saveimage'])) { // If save image button has been pressed

        if (isset($_POST['create']) && $_POST['create'] == 'new') { // If it's a new image

            // Validate entered values - make sure numbers are numbers, etc.
            $validation = validateImagePostData('new');
            $flag = $validation['flag'];
            $msg = $validation['msg'];
            $sql = $validation['sql'];

            $insert_sql = 'INSERT INTO `images` SET `id` = NULL, ' . $sql;

            if (!$flag && mysqli_query(DB::$conn,$insert_sql)) {
                debug('IMAGE INSERTED!');
                // Update $images to reflect new insertion
                $images = getTemplateImages($_POST['template_id']);
                
                // Upload default image
                $new_image_id = mysqli_insert_id(DB::$conn);
                if (isset($_FILES['image-new-default-image'])) {
                // Image uploaded
                $upload_tmp = $_FILES['image-new-default-image']['tmp_name'];
                $target_path = $storage_path . 'templates/default_images/default_image_'.$new_image_id.'.'.pathinfo($_FILES['image-new-default-image']['name'], PATHINFO_EXTENSION);                
                
                if ($success = move_uploaded_file($upload_tmp, $target_path)) {
							debug('New default image file uploaded successfully.');
				    	} else {
							debug('Error uploading new default image file from<br/>'.$upload_tmp.'<br /> to <br />'.$target_path);		
							debug('Upload error was: '.$_FILES['image-'.$image['id'].'-default-image']['error']);	
							debug('Return value of move_uploaded_file() was: '.(gettype($success)).' '.($success?'true':'false'));	
							debug('is_uploaded_file(): ' . gettype(is_uploaded_file($upload_tmp)) .' '. (is_uploaded_file($upload_tmp)?'true':'false'));
							debug('[upload] value: ' . gettype($_FILES['image-'.$image['id'].'-default-image']['upload']) .' '. $_FILES['image-'.$image['id'].'-default-image']['upload']);
							debug('Size of uploaded file: '.$_FILES['image-'.$image['id'].'-default-image']['size'].' bytes.');
							debug('Size of uploaded file (filesystem): '.filesize($upload_tmp));
							debug('Client-reported MIME type of uploaded file: '.$_FILES['image-'.$image['id'].'-default-image']['type']);
				 	 	}
                }
                
            } else {
                debug('<p><strong>AN ERROR OCCURRED INSERTING THE IMAGE :( </strong></p><p>FLAG = '.$flag.'</p><p>$msg: <br />'.$msg.'</p><p>SQL statement:<br />'.$insert_sql.'</p><p>SQL Error: '.mysqli_error(DB::$conn).'</p>');
            }

        } else { // If it's an update to existing image(s)

            $update_error = false;

            foreach ($images as $image) {
                // Validate entered values - make sure numbers are numbers, etc.
                $validation = validateImagePostData($image['id']);
                $flag = $validation['flag'];
                $msg = $validation['msg'];
                $sql = $validation['sql'];

                $update_sql = 'UPDATE `images` SET ' . $sql . ' WHERE `id` = ' . $image['id'];

                if ($flag || !mysqli_query(DB::$conn,$update_sql)) {
                    debug('<p><strong>AN ERROR OCCURRED :( </strong></p><p>FLAG = '.$flag.'</p><p>$msg: <br />'.$msg.'</p><p>SQL statement:<br />'.$update_sql.'</p>');
                    $update_error = true;
                }
                
                if (isset($_FILES['image-'.$image['id'].'-default-image'])) {
                	debug('New default image detected');
                	
                	$upload_tmp = $_FILES['image-'.$image['id'].'-default-image']['tmp_name'];
                	$target_path = $storage_path . 'templates/default_images/default_image_'.$image['id'].'.'.pathinfo($_FILES['image-'.$image['id'].'-default-image']['name'], PATHINFO_EXTENSION);
                	
					 	if ($success = move_uploaded_file($upload_tmp, $target_path)) {
							debug('New default image file uploaded successfully.');
				    	} else {
							debug('Error uploading new default image file from<br/>'.$upload_tmp.'<br /> to <br />'.$target_path);		
							debug('Upload error was: '.$_FILES['image-'.$image['id'].'-default-image']['error']);	
							debug('Return value of move_uploaded_file() was: '.(gettype($success)).' '.($success?'true':'false'));	
							debug('is_uploaded_file(): ' . gettype(is_uploaded_file($upload_tmp)) .' '. (is_uploaded_file($upload_tmp)?'true':'false'));
							debug('[upload] value: ' . gettype($_FILES['image-'.$image['id'].'-default-image']['upload']) .' '. $_FILES['image-'.$image['id'].'-default-image']['upload']);
							debug('Size of uploaded file: '.$_FILES['image-'.$image['id'].'-default-image']['size'].' bytes.');
							debug('Size of uploaded file (filesystem): '.filesize($upload_tmp));
							debug('Client-reported MIME type of uploaded file: '.$_FILES['image-'.$image['id'].'-default-image']['type']);
				 	 	}
               
                }


            }

            if (!$update_error) {
                echo 'IMAGES(S) UPDATED!';
                // Update $images to reflect new insertions
                $images = getTemplateImages($_POST['template_id']);
            }

        }

    }








    echo '<div style="float:left;"><img class="thumb" src="tools/create.php?view=thumbnail&template_id='.$template['id'].'" /></div>';
    echo '<div style="float:left;padding-left:10px"><h2 style="border:0;">'.$template['name'].'<br /><sub>(template #'.$template['id'].')</sub></h2><p style="padding-left:2em;"><a href="'.dirname($_SERVER['PHP_SELF']).'index.php?template_id='.$template['id'].'&reset=1&step=2" target="_blank">&gt;&nbsp;Test this template</a></p></div>';
    echo '<div style="float:right;padding-right:10px;padding-top:10px"><a  href="'.$_SERVER['PHP_SELF'].'?action=duplicate&template_id='.$template['id'].'"><img alt="Duplicate this template" title="Duplicate this template" src="images/duplicate.png" style="height:2em;" /></a></div>';
    echo '<hr />';




    // ------------ top tabs -------------------------------------

    echo '<div class="top-tabs">';

    echo '<div class="top-tab" style="background-color: #666666; color: #FFFFFF;" onclick="hideAllBut(\'section-parent\', \'section-new-field\');makeInverted(this);">New field</div>';
    echo '<div class="top-tab" onclick="hideAllBut(\'section-parent\', \'section-edit-fields\');makeInverted(this);">Edit fields</div>';
    echo '<div class="top-tab" onclick="hideAllBut(\'section-parent\', \'section-new-image\');makeInverted(this);">New image</div>';
    echo '<div class="top-tab" onclick="hideAllBut(\'section-parent\', \'section-edit-images\');makeInverted(this);">Edit images</div>';
    echo '<div class="top-tab" onclick="hideAllBut(\'section-parent\', \'section-edit-template\');makeInverted(this);">Edit template</div>';
    echo '<div class="top-tab last" onclick="hideAllBut(\'section-parent\', \'section-html-form\');makeInverted(this);">Embeddable HTML</div>';
    echo '<br class="clear" />';

    echo '</div>';




    echo '<div id="section-parent">';

    // ----------------- CREATE NEW FIELD ---------------------------------------------



    // New field form
    // echo('<h3>Create new field</h3>');

    echo '<div id="section-new-field">';


    echo '<form action="'.$_SERVER['PHP_SELF'].'" method="POST">';

    echo '<input type="hidden" name="template_id" value="'.$_POST['template_id'].'" />';
    echo '<input type="hidden" name="create" value="new" />';

    echo '<table class="editor">';

    // echo('<tr class="header"><td colspan="2">New field</td></tr>');


    // Create new field
    $emptyField = array(
        'id' => 'new',
        'template_id' => $template['id'],
        'name' => (isset($_POST['field-new-name'])?$_POST['field-new-name']:''),
        'default_text' => (isset($_POST['field-new-default_text'])?$_POST['field-new-default_text']:''),
        'force_uppercase' => 0,
        'character_limit' => (isset($_POST['field-new-character_limit'])?$_POST['field-new-character_limit']:0),
        'font_id' => (isset($_POST['field-new-font_id'])?$_POST['field-new-font_id']:''),
        'font_size' => (isset($_POST['field-new-font_size'])?$_POST['field-new-font_size']:''),
        'colour_id' => (isset($_POST['field-new-colour_id'])?$_POST['field-new-colour_id']:''),
        'x_position' => (isset($_POST['field-new-x_position'])?$_POST['field-new-x_position']:''),
        'y_position' => (isset($_POST['field-new-y_position'])?$_POST['field-new-y_position']:''),
        'wrap_width' => (isset($_POST['field-new-wrap_width'])?$_POST['field-new-wrap_width']:''),
        'leading' => (isset($_POST['field-new-leading'])?$_POST['field-new-leading']:''),
        'kerning' => (isset($_POST['field-new-kerning'])?$_POST['field-new-kerning']:'0'),
        'parent' => 0,
        'page' => 1,
    );
    // Show editor

    displayFieldEditor($emptyField);

    echo '</table>';

    echo '<br /><input class="button" type="submit" name="savefield" value="Create new field" />';

    echo '</form>';

    echo '</div>'; // End new field section






    // ----------------- EDIT EXISITING FIELDS ---------------------------------------------

    echo '<div id="section-edit-fields" style="display: none;">';

    echo '<form action="'.$_SERVER['PHP_SELF'].'" method="POST">';

    echo '<input type="hidden" name="template_id" value="'.$_POST['template_id'].'" />';

    echo '<input class="button" type="submit" name="savefield" value="Save Changes" />';

    echo '<table class="editor">';

    foreach ($fields as $field) {
        displayFieldEditor($field);
    }

    echo '</table>';

    echo '<br /><input class="button" type="submit" name="savefield" value="Save Changes" />';

    echo '</form>';


    echo '</div>'; // end edit fields section




// ----------------- CREATE NEW IMAGE ---------------------------------------------



    // New field form
    // echo('<h3>Create new field</h3>');

    echo '<div id="section-new-image" style="display: none;">';


    echo '<form action="'.$_SERVER['PHP_SELF'].'" method="POST" enctype="multipart/form-data">';

    echo '<input type="hidden" name="template_id" value="'.$_POST['template_id'].'" />';
    echo '<input type="hidden" name="create" value="new" />';
    echo '<input type="hidden" name="from-editor" value="true" />';

    echo '<table class="editor">';

    // echo('<tr class="header"><td colspan="2">New field</td></tr>');


    // Create new image
    $emptyImage = array(
        'id' => 'new',
        'template_id' => $template['id'],
        'name' => (isset($_POST['field-new-name'])?$_POST['field-new-name']:''),
        'x_position' => (isset($_POST['field-new-x_position'])?$_POST['field-new-x_position']:''),
        'y_position' => (isset($_POST['field-new-y_position'])?$_POST['field-new-y_position']:''),
        'width' => (isset($_POST['field-new-width'])?$_POST['field-new-width']:''),
        'height' => (isset($_POST['field-new-height'])?$_POST['field-new-height']:''),
        'alignment' => (isset($_POST['field-new-alignment'])?$_POST['field-new-alignment']:''),
        'page' => 1,
    );
    // Show editor

    displayImageEditor($emptyImage);

    echo '</table>';

    echo '<br /><input class="button" type="submit" name="saveimage" value="Insert new image" />';

    echo '</form>';

    echo '</div>'; // End new image section






    // ----------------- EDIT IMAGES ---------------------------------------------

    echo '<div id="section-edit-images" style="display: none;">';

    echo '<form action="'.$_SERVER['PHP_SELF'].'?template_id='.$_POST['template_id'].'" method="POST" enctype="multipart/form-data">';

    echo '<input type="hidden" name="template_id" value="'.$_POST['template_id'].'" />';
    echo '<input type="hidden" name="from-editor" value="true" />';
    
    echo '<input class="button" type="submit" name="saveimage" value="Save Changes" />';

    echo '<table class="editor">';

    foreach ($images as $image) {
        displayImageEditor($image);
    }

    echo '</table>';

    echo '<br /><input class="button" type="submit" name="saveimage" value="Save Changes" />';

    echo '</form>';


    echo '</div>'; // end edit fields section





    // ----------------- TEMPLATE OPTIONS ---------------------------------------------


    // echo('<h3>Template settings</h3>');

    echo '<div id="section-edit-template" style="display: none;">';


    displayTemplateEditor($template['id']);


    echo '</div>'; // End edit template section
    
    



    // ----------------- HTML FORM ---------------------------------------------


    // echo('<h3>Bare-bones HTML form</h3>');

    echo '<div id="section-html-form" style="display: none;">';
    
    echo('<p>You can paste the below into a static or CMS-generated page, and customise it as needed.</p>');
    echo('<ul>');
    echo('<li>Inputs can be changed to <code>type="hidden"</code> and given a value in order to lock fields.</li>');
    echo('<li>The end of the <code>action</code> URL is the name of the tab when displayed in Chrome\'s PDF viewer.</li>');
    echo('<li>The <code>download_name</code> input is the default name used to download the output.</li>');
    echo('<li>Inputs can be changed to radio buttons/selectors/jqueryUI inputs, although testing is definitely advised.</li>');
    echo('</ul>');
    
    
    echo('<h3>Code:</h3><pre>' . htmlspecialchars(generateSimpleHTMLForm($template['id'])) . '</pre>');
    
    echo('<h3>Preview:</h3>'.generateSimpleHTMLForm($template['id']).'<br><br><br>');


    echo '</div>'; // End HTML form section

    
    
    
    

    echo '</div>'; // end main content section



} else { // If no template selected yet display color/font editors, available templates, and new template form

    
    if (isset($_POST['action']) && $_POST['action'] === 'saveColours') { // Colour form submitted

  
        for ($i = 0; $i < intval($_POST['number']); $i++) {
            $sql = '';  
            if (isset($_POST['c'.$i.'_keep']) && $_POST['c'.$i.'_keep'] === 'on') {
                if ($_POST['c'.$i.'_id'] !== 'new') {
                    $sql .= 'UPDATE `colours` SET `name` = "' . $_POST['c'.$i.'_name'] . '", `RGBA` = "' . substr($_POST['c'.$i.'_colourPicker'],1,6) . 'FF" WHERE `id` = ' . $_POST['c'.$i.'_id'] . ';';
                } else { // New colour
                    $sql .= 'INSERT INTO `colours` (`name`,`RGBA`) VALUES ("'.$_POST['c'.$i.'_name'].'","'.substr($_POST['c'.$i.'_colourPicker'],1,6) . 'FF");';
                }
            } else if ($_POST['c'.$i.'_id'] !== 'new') { // Can't delete it if it's not in there yet
                $sql .= 'DELETE FROM `colours` WHERE `id` = ' . $_POST['c'.$i.'_id'] . ';';
            }
            if ($sql !== '') mysqli_query(DB::$conn,$sql);
            //debug($sql.'<br>ERROR: '.mysqli_error(DB::$conn).'<br><br>');
        }
        
        // Set any fields using deleted colour IDs to the first available colour
        $newColour = getAllColours()[0]['id'];
        
        $sql = 'UPDATE `fields` SET `colour_id` = '.$newColour.' WHERE `colour_id` NOT IN (SELECT `id` FROM `colours`);';
        mysqli_query(DB::$conn,$sql);
        
        
    }
    
        if (isset($_POST['action']) && $_POST['action'] === 'saveFonts') { // Font form submitted
        
        // Check for uploaded files, process as needed (makeFont.php)

  
        for ($i = 0; $i < intval($_POST['number']); $i++) {
        
        
                $font_file = 'Error: no file';
                $original_file = 'Error: no file';
                
                $font_dir = 'storage/fonts/';
                       
            if ($_POST['font'.$i.'_id'] !== 'new') {
                $originalFontData = getFont($_POST['font'.$i.'_id']);
                $font_file = $originalFontData['font_file'];
                $original_file = $originalFontData['original_file'];
                
            } 
            
            $sql = '';
            
            if (isset($_POST['font'.$i.'_keep']) && $_POST['font'.$i.'_keep'] === 'on') {
            
                if(isset($_FILES['font'.$i.'_upload']) && $_FILES['font'.$i.'_upload']['error'] === 0) { // IF there's a new font file successfully uploaded
                
                    $newFontFile = $_FILES['font'.$i.'_upload'];
                    
                    
                    if (isTTF($newFontFile['tmp_name'])) { // upload successful and check for valid font file
                        
                        
                        require_once('tools/fpdf/makefont/makefontinline.php'); // Inline version, only load it if it's needed.
                        
			$font_internal_name = uniqid().pathinfo(basename($newFontFile['name']),PATHINFO_FILENAME);

			
			// Process with MakeFont and move .php and .z files into place
			$fontUpload = $font_dir . $font_internal_name . '.ttf';
			move_uploaded_file($newFontFile['tmp_name'], $fontUpload);
			
			// Let the magic happen...
			MakeFont($fontUpload, $font_dir);
			
			// Remove TTF file as no longer needed (FPDF uses the .z version)
			unlink($fontUpload);
			
                        $font_file = $font_internal_name;
       			$original_file = $newFontFile['name'];
                
                    
                    }
                    
                    
                    
                    
                    
                }
                if ($_POST['font'.$i.'_id'] !== 'new') {
                    $sql .= 'UPDATE `fonts` SET `name` = "' . $_POST['font'.$i.'_name'] . '", `font_file` = "' . $font_file . '", `original_file` = "'.$original_file.'" WHERE `id` = ' . $_POST['font'.$i.'_id'] . ';';
                } else { // New font
                    $sql .= 'INSERT INTO `fonts` (`name`,`font_file`, `original_file`) VALUES ("'.$_POST['font'.$i.'_name'].'","'.$font_file.'","'.$original_file.'");';
                }
            } else if ($_POST['font'.$i.'_id'] !== 'new') { // Can't delete it if it's not in there yet
                
                // Remove from database
                $sql .= 'DELETE FROM `fonts` WHERE `id` = ' . $_POST['font'.$i.'_id'] . ';';
                
                // Remove files
                unlink($font_dir.$originalFontData['font_file'].'.php');
                unlink($font_dir.$originalFontData['font_file'].'.z');
                
                
            }
            if ($sql !== '') mysqli_query(DB::$conn,$sql);
            //debug($sql.'<br>ERROR: '.mysqli_error(DB::$conn).'<br><br>');
        }
        
        // Set any fields using deleted fonts to the first available font
        $newFont = getAllFonts()[0]['id'];
        
        $sql = 'UPDATE `fields` SET `font_id` = '.$newFont.' WHERE `font_id` NOT IN (SELECT `id` FROM `fonts`);';
        mysqli_query(DB::$conn,$sql);
        //debug($sql);
        //debug(mysqli_error(DB::$conn));
        
    }
    

    
    
    
    
        
  
    
    

    echo('<h2>Template Editor</h2>');
    
    
    $templates = getAllTemplates();


    $templates_active = '';
    $templates_inactive = '';

    // Split list of all templates by `active` value (Datatype BIT, 1 = active, 0 = inactive)
    foreach ($templates as $template) {
        if ($template['active']) {
            $templates_active .= ('<li><a href="'.$_SERVER['PHP_SELF'].'?template_id='.$template['id'].'">'.$template['name'].'</a></li>');
        } else {
            $templates_inactive .= ('<li><a href="'.$_SERVER['PHP_SELF'].'?template_id='.$template['id'].'">'.$template['name'].'</a></li>');
        }
    }
    
    
    
    echo('<h3 onclick="toggleBlock(\'current_templates\');">Current templates</h3>');
    echo('<div id="current_templates"  class="editor_section" style="display:block;">');
    echo '<div class="list-2-1"><h4>Active templates</h4><ul class="basic-list">'.$templates_active.'</ul></div>';
    echo '<div class="list-2-2"><h4>Inactive templates</h4><ul class="basic-list">'.$templates_inactive.'</ul></div>';
    //echo '<br style="clear:both;"/>';
    
    echo('<br style="clear:both;"></div>');
    
    
    
    
    
    echo '<h3 onclick="toggleBlock(\'new_template\');">Add a new template</h3>';
    
    echo('<div id="new_template" class="editor_section">');
    displayTemplateEditor('new');
    echo('<br style="clear:both;"></div>');

    
    
    
    
    echo('<h3 onclick="toggleBlock(\'colour_editor\');">Colour editor</h3>');
    
    echo('<div id="colour_editor" class="editor_section">');
    echo('<p class="warningNote"><strong>WARNING: Changes made here affect multiple templates. Use&nbsp;with&nbsp;caution!</strong><br>&bull; Unchecked colours will be removed<br>&bull; Text fields using unchecked colours will be set to use the first remaining colour.</p>');
    
        $colours = getAllColours();
        $colours[] = Array('id' => 'new', 'name' => "New", 'RGBA' => 'CCCCCCFF');
        
        
        echo('<form action="'.$_SERVER["PHP_SELF"].'" method="post">');
        echo('<input type="hidden" name="action" value="saveColours">');
        echo('<input type="hidden" name="number" value="'.count($colours).'">');
        echo('<table>');
        for ($i = 0; $i < count($colours); $i++) {
            echo('<input type="hidden" name="c'.$i.'_id" value="'.$colours[$i]['id'].'">');
            echo('<tr>');
            
            echo('<td><input type="checkbox" name="c'.$i.'_keep"'.($i<count($colours)-1?' checked':'').'></td>');
            
            echo('<td><input id="c'.$i.'_colourPicker" type="color" name="c'.$i.'_colourPicker" value="#'.substr($colours[$i]['RGBA'],0,6).'" onchange="syncColourInputs(this);"></td>');
            
            echo('<td><input type="text" id="c'.$i.'_colourField" name="c'.$i.'_colourField" value="#'.substr($colours[$i]['RGBA'],0,6).'" oninput="syncColourInputs(this);"></td>');
            
            echo('<td><input class="longInput" type="text" name="c'.$i.'_name" value="'.$colours[$i]['name'].'"></td>');
            
            
            echo('<td>');
            $usageInTemplates = getColourUsage($colours[$i]['id']);
            if (count($usageInTemplates) > 0) {
                echo('</p>');   
                echo('<strong>Used in '.count($usageInTemplates).' template'.(count($usageInTemplates)===1?'':'s').':</strong> ');
                for ($j = 0; $j < count($usageInTemplates); $j++) {
                    echo('<a href="'.$_SERVER['PHP_SELF'].'?template_id='.$usageInTemplates[$j]['template_id'].'">'.getTemplate($usageInTemplates[$j]['template_id'])['name'] . '</a>' . ($j<count($usageInTemplates)-1?', ':''));                    
                }
                echo('</p>');
            }
            echo('</td>');
            echo('</tr>');
        }
        echo('</table>');
        echo('<input type="submit" value="Save changes">');
        echo('</form>');
        
    
    echo('<br style="clear:both;"></div>');
    
    
    
    
      echo '<h3 onclick="toggleBlock(\'font_editor\');">Font editor</h3>';
    
    echo('<div id="font_editor" class="editor_section">');
    echo('<p class="warningNote"><strong>WARNING: Changes made here affect multiple templates. Use&nbsp;with&nbsp;caution!</strong><br>&bull; Unchecked fonts will be removed<br>&bull; Only TTF font files are currently supported (<a href="https://everythingfonts.com/otf-to-ttf" target="_blank">font format tools here</a>)<br>&bull; Text fields using removed fonts will be set to use the first remaining font.</p>');
    
    $fonts = getAllFonts();
    $fonts[] = Array('id' => 'new', 'name' => 'New font', 'font_file' => '&lt;no font&gt;', 'original_file' => 'No file');
    
        echo('<form action="'.$_SERVER["PHP_SELF"].'" method="post" enctype="multipart/form-data">');
        echo('<input type="hidden" name="action" value="saveFonts">');
        echo('<input type="hidden" name="number" value="'.count($fonts).'">');
        for ($i = 0; $i < count($fonts); $i++) {
            echo('<div class="font-block">');
            echo('<p class="name">'.$fonts[$i]['name'].' <span class="edit-link" onclick="toggleBlock(\'font-editor-'.$i.'\');">EDIT</span></p>');
            
            echo('<input type="hidden" name="font'.$i.'_id" value="'.$fonts[$i]['id'].'">');
                        
            echo('<div class="font-preview-holder"><div class="font-preview-fader"></div><img class="font-preview" src="tools/fontPreview.php?id='.$fonts[$i]['id'].'" onclick="toggleBlock(\'font-editor-'.$i.'\');"></div>');
            
            echo('<div class="font-editor" id="font-editor-'.$i.'">');     
            
            $usageInTemplates = getFontUsage($fonts[$i]['id']);
            if (count($usageInTemplates) > 0) {
                echo('<p>');
                echo('<strong>Used in '.count($usageInTemplates).' template'.(count($usageInTemplates)===1?'':'s').':</strong> ');
                for ($j = 0; $j < count($usageInTemplates); $j++) {
                    echo('<a href="'.$_SERVER['PHP_SELF'].'?template_id='.$usageInTemplates[$j]['template_id'].'">'.getTemplate($usageInTemplates[$j]['template_id'])['name'] . '</a>' . ($j<count($usageInTemplates)-1?', ':''));
                }
            }
            echo('<br><br><input type="checkbox" name="font'.$i.'_keep"'.($i<count($fonts)-1?' checked':'').'> Keep font?');
            
            
            echo('<br><label for="font'.$i.'_name">Display name</label><br><input id="font'.$i.'_name" class="longInput" type="text" name="font'.$i.'_name" value="'.$fonts[$i]['name'].'">');
            
            echo('<br><label for="font'.$i.'_original_file">New TTF file</label><br><input type="file" id="font'.$i.'_upload" name="font'.$i.'_upload"><br><span class="field-current-image"><strong>&nbsp;&nbsp;&nbsp;&bullet;&nbsp;Current file:</strong> ' . $fonts[$i]['original_file'] .'</span>');

            echo('</div>');
            echo('</div>');
        }
        echo('<input type="submit" value="Save changes">');
        echo('</form>');
        
    
    echo('<br style="clear:both;">');

    
    echo('</div>');
    
    
    
    
    

}


?>
<br style="clear:both;" />

<?php echo($debug?debug():''); ?>

</body>

</html>
