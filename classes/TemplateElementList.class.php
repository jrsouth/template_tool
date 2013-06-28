<?PHP

class TemplateElementList {

private $templateElements = null;
private $templateId;
private $pdo;


function __construct($templateId, $pdo) {
    $this->templateId = $templateId;
    $this->pdo = $pdo;
}


function getTemplateElements() {
    if ($this->templateElements == null) {
    
		// Get Image elements
		$stmt = $this->pdo->prepare("SELECT * FROM `images` where `template_id` = ? ORDER BY `page`, `x_position`");
		if ($stmt->execute(array($this->templateId))) {
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			    $this->templateElements[] = new TemplateElementImage($row);
			}
		} else { 
			debug('Error retrieving image elements details from database for template ' . $templateId);
		}		
		
		
		// Get field elements (only fields without parents)
		$stmt = $this->pdo->prepare("SELECT * FROM `fields` WHERE `template_id` = ? AND `parent` = 0 ORDER BY `page`, `x_position`");
		if ($stmt->execute(array($this->templateId))) {
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			    $this->templateElements[] = new TemplateElementField($row);
			}
		} else { 
			debug('Error retrieving image elements details from database for template ' . $templateId);
		}
	}
	
	return $this->templateElements;
}


}

?>