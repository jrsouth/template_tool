<?php

abstract class TemplateElement {

  protected $id = 'null';
  protected $template_id;
  protected $name;
  protected $x_position;
  protected $y_position;
  protected $page;
 

  protected $childElements = array();


  public abstract function assignValuesFromArray($array);
  public abstract function getHtmlInputCode();
  public abstract function drawIntoPdf($fpdf);
  public abstract function writeToDb($pdo);
  public abstract function readFromDb($pdo);


  function __construct($value = null, $pdo = null) {

    if (is_int($value) && get_class($pdo) == 'PDO') {
	// Set id and read from database
	
	$this->setID($value);
	$this->readFromDb($pdo);
	
    } elseif (is_array($value)) {
	// Check array has correct associative elements and assign to local
	// properties, then pass to assignValuesFromArray() function for 
	// implementation-specific values.
	
	$this->setId($value['id']);
	$this->setTemplate_id($value['template_id']);
	$this->setName($value['name']);
	$this->setPosition($value['x_position'], $value['y_position']);
	$this->setPage($value['page']);
	
	$this->assignValuesFromArray($value);
	
    } else {
    // No array given or invalid id/pdo supplied
	    debug('Invalid creation of ' . get_class($this) . ' object');
    }
  }
  
  

  public function getId() {
    return $this->id;
  }
  function setId($value) {
    if ((int) $value > 0) {
      $this->id = (int) $value;
    } else {
      echo('<br />ERROR: Invalid ID specified.<br />');
    }
  }


  public function getTemplate_id() {
    return $this->template_id;
  }
  function setTemplate_id($value) {
    if ((int) $value > 0) {
      $this->template_id = (int) $value;
    } else {
      echo('<br />ERROR: Invalid Template ID specified.<br />');
    }
  }



  public function getName() {
    return $this->name;
  }
  function setName($value) {
    $this->name = (string) $value;
  }



  public function getPosition() {
    return array(0 => $this->x_position, 1 => $this->y_position, 'x_position' => $this->x_position, 'y_position' => $this->y_position);
  }
  function setPosition($newX_position, $newY_position) {
    $this->x_position = (float) $newX_position;
    $this->y_position = (float) $newY_position;
  }


 
  public function getPage() {
    return $this->page;
  }
  function setPage($value) {
    $this->page = (int) $value;
  }


}

?>