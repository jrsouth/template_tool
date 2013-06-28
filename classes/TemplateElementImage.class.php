<?php

class TemplateElementImage extends TemplateElement {

private $width;
private $height;
private $alignment;
 

  public function assignValuesFromArray($array) {
    $this->setDimensions($array['width'], $array['height']);
    $this->setAlignment($array['alignment']);
  }

  public function getHtmlInputCode() {
  // display image fields in order
  
  $t = '';

  $t .= '<p><span class="field-title">'.$this->getName().': </span> ';
  $t .= '<span class="field-character-count"> (JPEG, PNG or GIF images only)</span><br />';

  $local_imgfile = ''; // Set up default value
  if (isset($_POST['img'.$this->getId().'hidden'])) { // Old value passed
	  $local_imgfile = $_POST['img'.$this->getId().'hidden'];
  }
  if (isset($_FILES['img'.$this->getId()]) && isset($_FILES['img'.$this->getId()]['upload'])) { // New image file passed
	  $local_imgfile = $_FILES['img'.$this->getId()]['upload'];
  }
  
  $position = $this->getPosition();
  $dimensions = $this->getDimensions();
  $scale = 400/$dimensions['width'];
  // XXX HARD CODED PREVIEW SIZE XXX HACK

  $t .= '<input type="file" onmouseover="this.focus();" onfocus="showFieldLocator('.($position['x_position'] + 0.5*$dimensions['width']).','.($position['y_position'] + 0.5*$dimensions['height']).','.$this->getPage().','.$scale.');" onblur="hideFieldLocator('.$this->getPage().');" class="file-input" name="img'.$this->getId().'" />';

  if ($local_imgfile != '') { // Insert hidden form field if there's a value (and print)
	  $t .= '<input type="hidden" name="'.('img'.$this->getId().'hidden').'" value="'.$local_imgfile.'" />';
	  $t .= '<br /><span class="field-current-image"><strong>&nbsp;&nbsp;&nbsp;&bullet;&nbsp;Current file:</strong> '.substr(basename($local_imgfile), 14).'</span></p>';
  } 
  
  return $t;
  
  }
  
  public function drawIntoPdf($fpdf) {
  
  }
  
  public function writeToDb($pdo) {
  
  }
  
  public function readFromDb($pdo) {
		$stmt = $pdo->prepare("SELECT * FROM `images` where `id` = ?");
		if ($stmt->execute(array($this->id)) && $stmt->rowCount() == 1) {
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
   			$this->__construct($row);
			}
		} else { 
			debug('Error retrieving element details from database for image ' . $this->getId());
		}
	}      
  







// Generic Getter/Setter functions

public function getDimensions() {
  return array('width' => $this->width, 'height' => $this->height);
}

public function setDimensions($newWidth, $newHeight) {
  $this->width = (float) $newWidth;
  $this->height = (float) $newHeight;
}


public function getAlignment() {
  return $this->alignment;
}

function setAlignment($value) {
  $this->alignment = (string) $value;
}




}

?>