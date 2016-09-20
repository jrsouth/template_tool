<?php

class Template {

private $id = 'NULL';
private $name = 'undefined';
private $pdf_file = 'undefined';
private $permissions = 'undefined';
private $bleed = 'undefined';
private $owner = 'undefined';
private $active = 'undefined';
private $pagecount = 'undefined';

private $templateElementList;




function __construct($value = null, $pdo = null) {

    if (is_int($value) && get_class($pdo) == 'PDO') {
	// Set id and read from database
	
	$this->setID($value);
	$this->readFromDb($pdo);
	
    } elseif (is_array($value)) {
	// Check array has correct associative elements and assign to local
	// properties.
	// NEED TO ADD CHECKING!
	
	$this->setId($value['id']);
	$this->setName($value['name']);
	$this->setPdf_file($value['pdf_file']);
	$this->setPermissions($value['permissions']);
	$this->setBleed($value['bleed']);
	$this->setOwner($value['owner']);
	$this->setActive($value['active']);
	$this->setPagecount($value['pagecount']);
	$this->setDimensions($value['width'], $value['height']);
	
	$this->templateElementList = new TemplateElementList($this->getId(), $pdo);
	
    } else {
    // No array given or invalid id/pdo supplied
	    debug('Invalid creation of ' . get_class($this) . ' object, using default values.');
    }
  }
  
  
public function getTemplateElements() {
	return $this->templateElementList->getTemplateElements();
}
  

public function readFromDb($pdo) {
	$stmt = $pdo->prepare("SELECT * FROM `templates` where `id` = ?");
	if ($stmt->execute(array($this->id)) && $stmt->rowCount() == 1) {
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$this->__construct($row);
		}
	} else { 
		debug('Error retrieving element details from database for template ' . $this->getId());
	}
}   


public function getHtmlForm() {
  $t = '';
  foreach($this->getTemplateElements() as $templateElement) {
      $t .= $templateElement->getHtmlInputCode();
  }
  return $t;
}

public function getHtmlThumbnail() {
  $link = $this->getResetLink();
  $imageUrl = $this->getThumbnailLocation();
  $t = '<div class="template-list-item">';
  $t .= '<a href=' . $link .'>';
  $t .= $this->getName();
  $t .= '<img src="' . $imageUrl . '" />';
  $t .= '</a>';
  $t .= '</div>';

  return $t;
}

private function getResetLink() {
  return '../index.php?template_id='.$this->getID().'&reset=1&step=2';
}

private function getThumbnailLocation() {
  return '../tools/create.php?view=thumbnail&template_id='.$this->getID();
}



// Generic Getter/Setter functions

public function getId() {
  return $this->id;
}
  function setId($value) {
    if ((int) $value > 0) {
      $this->id = (int) $value;
    } else {
      echo('<br />ERROR: Invalid template ID specified.<br />');
    }
  }



public function getName() {
  return $this->name;
}
function setName($value) {
  $this->name = (string) $value;
}



public function getPdf_file() {
  return $this->pdf_file;
}
function setPdf_file($value) {
  $this->pdf_file = (string) $value;
}



public function getPermissions() {
  return $this->permissions;
}
function setPermissions($value) {
  $this->permissions = (string) $value;
}



public function getBleed() {
  return $this->bleed;
}
function setBleed($value) {
  $this->bleed = (float) $value;
}



public function getOwner() {
  return $this->owner;
}
function setOwner($value) {
  $this->owner = (string) $value;
}



public function getActive() {
  return $this->active;
}
function setActive($value) {
  $this->active = (boolean) $value;
}



public function getPagecount() {
  return $this->pagecount;
}
function setPagecount($value) {
  $this->pagecount = (int) $value;
}


public function getDimensions() {
  return array('width' => $width, 'height' => $height);
}

public function setDimensions($newWidth, $newHeight) {
  $this->width = (float) $newWidth;
  $this->height = (float) $newHeight;
}



}

?>