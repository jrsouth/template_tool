<?php

function validateTemplatePostData ($id) {
global $template, $storage_path, $local_pdffile;

    $prefix = 'template-'.$id.'-';

    $flag = 0;
    $msg = '';
    $sql = '';

    
    // XXX TEMPORARILY PREVENT VALUES FROM BEING SAVED BY FORCING AN ERROR VALUE XXX
    //$flag++;
    
    
    
    // PDF Upload/Check
    $PDFUploaded = false;
    
    
    // Check uploaded file's existance, MIME type, size > 0, and extension
    if (isset($_FILES[$prefix.'pdf_file'])
	&& $_FILES[$prefix.'pdf_file']['type'] == 'application/pdf'
	&& $_FILES[$prefix.'pdf_file']['size'] > 0
	&& strcasecmp('pdf', pathinfo($_FILES[$prefix.'pdf_file']['name'], PATHINFO_EXTENSION) == 0) 
	)
	{
	  // Check first 5 bytes for '%PDF-' (Could also be used to check PDF version)
	  $handle = fopen($_FILES[$prefix.'pdf_file']['tmp_name'], 'r');
	  $headerBytes = fread($handle, 5);
	  fclose($handle);
	  	  
	  if ($headerBytes == '%PDF-') {
	  
	   // Move to storage with randomised prefix (to allow for duplicate original filenames)
	   $target_path = $storage_path . 'templates/'.substr(uniqid("", true),-6,6).'_'.$_FILES[$prefix.'pdf_file']['name'];
	   // XXX Should really check for uniqueness here, but it's incredibly unlikely we'll get a duplicate filename AND uniqid()
	   if(move_uploaded_file($_FILES[$prefix.'pdf_file']['tmp_name'], $target_path)) {
		echo('PDF File passed all tests and was uploaded successfully.');
		$PDFUploaded = true;
	   } else {
		echo('<br /><br /><strong>There was an error uploading the file, please try again!</strong><br /><br />');
	   }
	   
	  }
	  	  
    }
    
    if ($PDFUploaded = true) {
	  $template['pdf_file'] = pathinfo($target_path, PATHINFO_BASENAME);
	  $sql .= '`pdf_file` = "'.$template['pdf_file'].'"';
    } else { $flag++; $msg.='Error with PDF file<br />'; }
    
    if (isset($_POST[$prefix.'name']) && $_POST[$prefix.'name'] != '') {
      $template['name'] = $_POST[$prefix.'name'];
      $sql .= ', `name` = "'.$template['name'].'"';
    } else { $flag++; $msg.='Error with name<br />'; }


    if (isset($_POST[$prefix.'active'])) {
      $template['active'] = ($_POST[$prefix.'active']?'1':'0');
      $sql .= ', `active` = '.$template['active'];
    } else { $flag++; $msg.='Error with active setting<br />'; }


    if (isset($_POST[$prefix.'pagecount']) && ctype_digit($_POST[$prefix.'pagecount'])) {
      $template['pagecount'] = $_POST[$prefix.'pagecount'];
      $sql .= ', `pagecount` = '.$template['pagecount'];
    } else { $flag++; $msg.='Error with pagecount<br />'; }


    if (isset($_POST[$prefix.'bleed']) && ctype_digit($_POST[$prefix.'bleed'])) {
      $template['bleed'] = $_POST[$prefix.'bleed'];
      $sql .= ', bleed = '.$template['bleed'];
    } else { $flag++; $msg.='Error with bleed<br />'; }


    
    // Wrap SQL in INSERT or UPDATE/WHERE
    
    if ($id == 'new') {
      $sql = 'INSERT INTO `templates` SET ' . $sql . ';';
    } else {
      $sql = 'UPDATE `templates` SET ' . $sql . ' WHERE id = '.$id.';';
    }

    // Return an array consisting of the $flag, the $msg and the $sql statement
    return Array('flag' => $flag, 'msg' => $msg, 'sql' => $sql); 

}

function displayTemplateEditor ($id) {
global $template;

if ($id == 'new') { // Set up blank values
$template = Array('name' => '',
                  'pdf_file' => '123456_None selected',
                  'pagecount' => 1,
                  'bleed' => 0,
                  'active' => 1,
                  'permissions' => 'NULL',
                  'owner' => 'NULL'
                 );
} 
/* REDUNDANT AS WILL ALREADY BE SET
else { // Get template details from database
$template = getTemplate($id);
} */

if (isset($_POST['savetemplate'])) { // If save template button has been pressed

    // Validate entered values - make sure numbers are numbers, etc.
    $validation = validateTemplatePostData($id);
    $flag = $validation['flag'];
    $msg = $validation['msg'];
    $sql = $validation['sql'];

    debug($sql);
    
    if (!$flag && mysql_query($sql)) {
      echo('TEMPLATE INSERTED/UPDATED!<br />');
      // Update $templates to reflect new insertion
      if ($id == 'new') {$id = mysql_insert_id();}
      $template = getTemplate($id);
    } else {
      echo('AN ERROR OCCURRED :(');
      echo('<h1>FLAG = '.$flag.'</h1><p>'.$msg.'</p><p>SQL statement:<br />'.$sql.'</p>');
    }

}



echo('<form action="'.$_SERVER['PHP_SELF'].'" method="POST" enctype="multipart/form-data">');

echo('<input type="hidden" name="template_id" value="'.$id.'" />');
echo('<input type="hidden" name="alter_template" value="yes" />');

echo('<input class="button" type="submit" name="savetemplate" value="Save template" />');

echo('<table class="editor">');

 // echo('<tr class="header"><td colspan="2">New field</td></tr>');
  
  echo('<tr>');
  echo('<td id="templatemenu" class="field-menu">');
  echo('<span id="templatemenu-basic" style="font-weight:bold;" class="fake-link"
  onclick="hideAllBut(\'template-main\', \'template-basic\');makeBold(this);">Basic settings</span><hr />');
  echo('<span id="templatemenu-access" class="fake-link"
  onclick="hideAllBut(\'template-main\', \'template-access\');makeBold(this);">Access</span><hr />');
  echo('</td>');
  
  
  echo('<td>');
  echo('<div id="template-main">');
  
  
  echo('<div id="template-basic">');  
  //echo('Template Name:<br /><input type="text" name="template-'.$id.'-name" value="'.(isset($_POST['template-'.$id.'-name'])?$_POST['template-'.$id.'-name']:'').'" /></br />');
  echo('Template Name:<br /><input type="text" name="template-'.$id.'-name" value="'.$template['name'].'" /></br />');
  
  echo('Base PDF File:<br />');
		
		$local_pdffile = $template['pdf_file']; // Set up default value
		if (isset($_POST['template-'.$id.'-pdf_file-hidden'])) { // Old value passed
			$local_pdffile = $_POST['template-'.$id.'-pdf_file-hidden'];
		}
		if (isset($_FILES['template-'.$id.'-pdf_file']) && isset($_FILES['template-'.$id.'-pdf_file']['upload'])) { // New PDF file passed
			$local_pdffile = $_FILES['template-'.$id.'-pdf_file']['upload'];
		} 

		echo('<input type="file" class="file-input" name="template-'.$id.'-pdf_file" />');
		
		if ($local_pdffile != '') { // Insert hidden form field if there's a value (and print)
			echo('<input type="hidden" name="template-'.$id.'-pdf_file-hidden" value="'.$local_pdffile.'" />');
			echo('<br /><span class="field-current-image"><strong>&nbsp;&nbsp;&nbsp;&bullet;&nbsp;Current file:</strong> '.substr(basename($local_pdffile),7).'</span></p>');
		}
		
		
  echo('Number of pages: <input class="short" type="text" name="template-'.$id.'-pagecount" value="'.$template['pagecount'].'" /></br />');
  echo('Bleed: <input class="short" type="text" name="template-'.$id.'-bleed" value="'.$template['bleed'].'" /></br />');
  
  echo('</div>');
  
  
  
  echo('<div id="template-access" style="display:none;">');
  
  echo('Is the template active?:<br /><input type="radio" name="template-'.$id.'-active" value="1"'.($template['active']?' checked="checked"':'').'>Active</input><br /><input type="radio" name="template-'.$id.'-active" value="0"'.(!$template['active']?' checked="checked"':'').'>Inactive</input></br />');
  
  echo('</div>');
  
  
  echo('</div>');
  echo('</td>');
  
  
  
  
  echo('</tr>');
  
  echo('</table>');
  
  echo('<br /><input class="button" type="submit" name="savetemplate" value="Save template" />');
  
  echo('</form>');


}

function validateFieldPostData ($fieldID) {
global $template;

$prefix = 'field-' . $fieldID . '-';

$flag = 0;
$msg = '';
$sql = '`template_id` = '.$template['id'];

if (isset($_POST[$prefix.'name']) && trim($_POST[$prefix.'name']) != '') {
  $sql .= ', `name` = "'.trim($_POST[$prefix.'name']).'"';
} else { $flag++; $msg.='Error with name<br />'; }


if (isset($_POST[$prefix.'default_text']) && trim($_POST[$prefix.'default_text']) != '') {
  $sql .= ', `default_text` = "'.trim($_POST[$prefix.'default_text']).'"';
} else { $flag++; $msg.='Error with default text<br />'; }


if (isset($_POST[$prefix.'character_limit']) && ctype_digit($_POST[$prefix.'character_limit'])) {
  $sql .= ', `character_limit` = '.$_POST[$prefix.'character_limit'];
} else { $flag++; $msg.='Error with character_limit<br />'; }


if (isset($_POST[$prefix.'parent'])) {
  $sql .= ', `parent` = '.$_POST[$prefix.'parent'];
} else { $flag++; $msg.='Error with parent<br />'; }


if (isset($_POST[$prefix.'page'])) {
  $sql .= ', `page` = '.$_POST[$prefix.'page'];
} else { $flag++; $msg.='Error with page<br />'; }


if (isset($_POST[$prefix.'x_position']) && is_numeric($_POST[$prefix.'x_position'])) {
$sql .= ', `x_position` = '. $_POST[$prefix.'x_position'];
} else { $flag++; $msg.='Error with x_position<br />'; }


if (isset($_POST[$prefix.'y_position']) && is_numeric($_POST[$prefix.'y_position'])) {
$sql .= ', `y_position` = '. $_POST[$prefix.'y_position'];
} else { $flag++; $msg.='Error with y_position<br />'; }


if (isset($_POST[$prefix.'wrap_width']) && is_numeric($_POST[$prefix.'wrap_width'])) {
  $sql .= ', `wrap_width` = '.$_POST[$prefix.'wrap_width'];
} else {
  $sql .= ', `wrap_width` = 0';
  $_POST[$prefix.'wrap_width'] = 0;
}


if (isset($_POST[$prefix.'font_size']) && is_numeric($_POST[$prefix.'font_size'])) {
  $sql .= ', `font_size` = '.$_POST[$prefix.'font_size'];
} else { $flag++; $msg.='Error with font_size<br />'; }


if (isset($_POST[$prefix.'leading']) && is_numeric($_POST[$prefix.'leading'])) {
  $sql .= ', `leading` = '.$_POST[$prefix.'leading'];
} else { $flag++; $msg.='Error with leading<br />'; }


if (isset($_POST[$prefix.'font_id'])) {
  $sql .= ', `font_id` = '.$_POST[$prefix.'font_id'];
} else { $flag++; $msg.='Error with font_id<br />'; }


if (isset($_POST[$prefix.'colour_id'])) {
  $sql .= ', `colour_id` = '.$_POST[$prefix.'colour_id'];
} else { $flag++; $msg.='Error with colour_id<br />'; }


if (isset($_POST[$prefix.'force_uppercase'])) {
  $sql .= ', `force_uppercase` = 1';
} else { 
  $sql .= ', `force_uppercase` = 0';
}

return Array('flag' => $flag, 'msg' => $msg, 'sql' => $sql); 

}

function displayFieldEditor ($field) {
global $fonts, $colours, $template, $fields;

if ($field['id'] == 'new') {
  echo('<tr class="header"><td colspan="2">New field</td></tr>');
} else {
  echo('<tr class="header"><td colspan="2">'.$field['name'].' <a style="color:#990000;font-size:.5em;" href="editor.php?template_id='.$field['template_id'].'&remove='.$field['id'].'">[delete]</a></td></tr>');
}  
  
  echo('<tr>');
  
  
  echo('<td id="field'.$field['id'].'menu" class="field-menu">');
  echo('<span id="field'.$field['id'].'menu-basic" style="font-weight:bold;" class="fake-link"
  onclick="hideAllBut(\'field'.$field['id'].'main\', \'field'.$field['id'].'basic\');makeBold(this);">Basic information</span><hr />');
  echo('<span id="field'.$field['id'].'menu-pos" class="fake-link"
  onclick="hideAllBut(\'field'.$field['id'].'main\', \'field'.$field['id'].'pos\');makeBold(this);">Positioning</span><hr />');
  echo('<span id="field'.$field['id'].'menu-font" class="fake-link"
  onclick="hideAllBut(\'field'.$field['id'].'main\', \'field'.$field['id'].'font\');makeBold(this);">Font</span><hr />');
  echo('</td>');
  
  
  echo('<td>');
  echo('<div id="field'.$field['id'].'main">');
  
  
  echo('<div id="field'.$field['id'].'basic">');  
  
      echo('Field Name:<br /><input type="text" name="field-'.$field['id'].'-name" value="'.$field['name'].'" /></br />');
      
            
      echo('Default Text:<br /><textarea rows="4" name="field-'.$field['id'].'-default_text">'.$field['default_text'].'</textarea><br />');      

      
      echo('Character limit:<br /><input type="text" name="field-'.$field['id'].'-character_limit" value="'.$field['character_limit'].'" /><br />');
      
      // PARENT SELECTOR
      echo('Parent: <select name="field-'.$field['id'].'-parent">');
      echo('<option value="0"'.($field['parent']==0?' selected="selected"':'').'>No parent</option>');
      foreach ($fields as $f) {
        if ($f['id'] != $field['id']) {
	  echo('<option value="'.$f['id'].'"'.($field['parent']==$f['id']?' selected="selected"':'').'>'.$f['name'].'</option>');
	}
      }
      echo('</select> ');
            
      // PAGE SELECTOR
      echo('Page: <select name="field-'.$field['id'].'-page">');
      for ($i = 1; $i <= $template['pagecount']; $i++) {
	  echo('<option value="'.$i.'"'.($field['page']==$i?' selected="selected"':'').'>Page '.$i.'</option>');
      }
      echo('</select><br />');
  
  echo('</div>');
  
  
  
  echo('<div id="field'.$field['id'].'pos" style="display:none;">');  
  
      echo('X-pos (mm): <input class="short" type="text" name="field-'.$field['id'].'-x_position" value="'.$field['x_position'].'" /><br />');
      echo('Y-pos (mm): <input class="short" type="text" name="field-'.$field['id'].'-y_position" value="'.$field['y_position'].'" /><br />');
      
      echo('Wrap width (if applicable):<br /><input class="short" type="text" name="field-'.$field['id'].'-wrap_width" value="'.$field['wrap_width'].'" /><br />');
      
      
  echo('</div>');
  
  
  
  echo('<div id="field'.$field['id'].'font" style="display:none;">'); 
  
        echo('Font size:<br /><input class="short" type="text" name="field-'.$field['id'].'-font_size" value="'.$field['font_size'].'" /><br />');
      
      echo('Leading:<br /><input class="short" type="text" name="field-'.$field['id'].'-leading" value="'.$field['leading'].'" /><br />');
      // FONT SELECTOR
      echo('Font: <select name="field-'.$field['id'].'-font_id">');
      foreach ($fonts as $font) {
	echo('<option value="'.$font['id'].'"'.($field['font_id']==$font['id']?' selected="selected"':'').'>'.$font['display_name'].'</option>');
      }
      echo('</select> ');
      
      // COLOUR SELECTOR
      echo('Colour: <select name="field-'.$field['id'].'-colour_id">');
      foreach ($colours as $colour) {
	echo('<option value="'.$colour['id'].'"'.($field['colour_id']==$colour['id']?' selected="selected"':'').'>'.$colour['name'].'</option>');
      }
      echo('</select><br />');
	
      echo('<input type="checkbox" name="field-'.$field['id'].'-force_uppercase"'.($field['force_uppercase']>0?' checked="checked"':'').' /> Force Uppercase?<br />');
  
  echo('</div>');
  
  
  echo('</div>');
  echo('</tr>');

}

function getAllTemplates () {
	global $db_connection;
	$sql = 'SELECT * FROM `templates` ORDER BY `name`';
	$results = mysql_query($sql);
	$templates = array();
	while ($result = mysql_fetch_array($results)) {
		$templates[] = $result;
	}
	return $templates;
}

function getTemplate ($template_id) {
	global $db_connection;
	$sql = 'SELECT * FROM `templates` WHERE `id` = '.$template_id;
	$results = mysql_query($sql);
	$template = mysql_fetch_array($results);
	return $template;
}

function getAllImageAlignments () {

    // Hard coded for now - no need for DB connection

    $alignments = array();
    $alignments[] = array('value' => 'center', 'display' => 'Centered: fitted to bounding box, placed in center of frame');
    $alignments[] = array('value' => 'fill', 'display' => 'Fill: proportionally expanded to fill bounding box (some cropping)');
    $alignments[] = array('value' => 'ul', 'display' => 'Upper-left: fit to bounding box, placed in top left corner');
    $alignments[] = array('value' => 'ur', 'display' => 'Upper-right: fit to bounding box, placed in top right corner');
    $alignments[] = array('value' => 'ul', 'display' => 'Lower-left: fit to bounding box, placed in lower left corner');
    $alignments[] = array('value' => 'ul', 'display' => 'Lower-right: fit to bounding box, placed in lower right corner');

    return $alignments;

}

function getAllColours () {
	global $db_connection;
	$sql = 'SELECT * FROM `colours` ORDER BY `name`';
	$results = mysql_query($sql);
	$colours = array();
	while ($result = mysql_fetch_array($results)) {
		$colours[] = $result;
	}
	return $colours;
}

function getAllFonts () {
	global $db_connection;
	$sql = 'SELECT * FROM `fonts` ORDER BY `display_name`';
	$results = mysql_query($sql);
	$fonts = array();
	while ($result = mysql_fetch_array($results)) {
		$fonts[] = $result;
	}
	return $fonts;
}

function getTemplateFields ($template_id) {
	global $db_connection;
	$sql = 'SELECT * FROM `fields` WHERE `template_id` = '.$template_id.' ORDER BY `name`';
	$results = mysql_query($sql);
	$fields = array();
	while ($result = mysql_fetch_array($results)) {
		$fields[] = $result;
	}
	return $fields;
}

function getField ($field_id) {
	global $db_connection;
	$sql = 'SELECT * FROM `fields` WHERE `id` = '.$field_id;
	$results = mysql_query($sql);
	$field = mysql_fetch_array($results);
	return $field;
}

?>