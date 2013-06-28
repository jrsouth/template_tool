<?php

class TemplateElementField extends TemplateElement {

  private $type;
  private $default_text;
  private $force_uppercase;
  private $character_limit;
  private $font_id;
  private $font_size;
  private $colour_id;
  private $wrap_width;
  private $leading;
  private $parent;
  
  
  public function assignValuesFromArray($array) {
      $this->setType($array['type']);
      $this->setDefault_text($array['default_text']);
      $this->setForce_uppercase($array['force_uppercase']);
      $this->setCharacter_limit($array['character_limit']);
      $this->setFont($array['font_id'], $array['font_size'], $array['colour_id']);
      $this->setWrap_width($array['wrap_width']);
      $this->setLeading($array['leading']);
      $this->setParent($array['parent']);
  }

  public function getHtmlInputCode() { // display fields in order
  
  $position = $this->getPosition();
    
	    if ($this->getType() != 'wrapper') { // Don't display 'wrapper' fields
	
		$value = (isset($_POST['f'.$this->getId()])?$_POST['f'.$this->getId()]:$this->getDefault_text());


		echo '<p><span class="field-title">'.$this->getName().': </span> ';
		echo '<span class="field-character-count"> (Up to '.$this->getCharacter_limit().' characters)</span><br />';

		$type = ($this->getWrap_width() > 0 || $this->getParent() > 0)?"multi_line":"single_line";
		
			
		switch ($type) {
		case 'single_line' :
			echo '<input type="text" maxlength="'.$this->getCharacter_limit().'" name="f'.$this->getId().'" value="'.$value.'" /><br />';
			break;

		case 'multi_line' :
			echo '<textarea onKeyDown="javascript:limitText(this.form.f'.$this->getId().','.$this->getCharacter_limit().',null)" rows="4" name="f'.$this->getId().'">'.$value.'</textarea><br />';

		}
		echo '</p>';
	    
	    }
		
	}
  
  public function drawIntoPdf($fpdf) {
  
  }
  
  public function writeToDb($pdo) {
  
  }
  
  public function readFromDb($pdo) {
		$stmt = $pdo->prepare("SELECT * FROM `fields` where `id` = ?");
		if ($stmt->execute(array($this->id)) && $stmt->rowCount() == 1) {
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
   			$this->__construct($row);
			}
		} else { 
			debug('Error retrieving element details from database for field ' . $this->getId());
		}
	}      
      







// Generic Getter/Setter functions

public function getType() {
  return $this->type;
}
function setType($value) {
  $this->type = (string) $value;
}

public function getDefault_text() {
  return $this->default_text;
}
function setDefault_text($value) {
  $this->default_text = (string) $value;
}


public function getFont() {
  return array('font_id' => $this->font_id, 'font_size' => $this->font_size, 'colour_id' => $this->colour_id);
}
function setFont($newFont_id, $newFont_size, $newFont_colour) {
  $this->font_id = (int) $newFont_id;
  $this->font_size = (float) $newFont_size;
  $this->colour_id = (int) $newFont_colour;
}


public function getParent() {
  return $this->parent;
}
function setParent($value) {
  $this->parent = (int) $value;
}


public function getForce_uppercase() {
  return $this->force_uppercase;
}
function setForce_uppercase($value) {
  $this->force_uppercase = (boolean) $value;
}


public function getCharacter_limit() {
  return $this->character_limit;
}
function setCharacter_limit($value) {
  $this->character_limit = (int) $value;
}



public function getWrap_width() {
  return $this->wrap_width;
}
function setWrap_width($value) {
  $this->wrap_width = (float) $value;
}



public function getLeading() {
  return $this->leading;
}
function setLeading($value) {
  $this->leading = (float) $value;
}





}

?>