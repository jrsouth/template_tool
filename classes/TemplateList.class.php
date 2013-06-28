<?PHP

class TemplateList {

private $templates = null;
private $pdo;
private $permissions;
private $searchTerms;
private $showInactive;


function __construct($pdo, $permissions = '', $searchTerms = '', $showInactive = false) {

    $this->pdo = $pdo;
    $this->permissions = $permissions;
    $this->searchTerms = $searchTerms;
    $this->showInactive = $showInactive;

}

public function getTemplates() {
  if ($this->templates == null) {
		$whereClauseEntered = false;
		$sql = 'SELECT * FROM `templates`';
		if (!empty($this->permissions)) {
			$sql .= $whereClauseEntered?' AND ':' WHERE ';
			$sql .= $this->explodeSearchTerms('permissions', $this->permissions);
			$whereClauseEntered = true;
		}
		if (!empty($this->searchTerms)) {
			$sql .= $whereClauseEntered?' AND ':' WHERE ';
			$sql .= $this->explodeSearchTerms('name', $this->searchTerms);
			$whereClauseEntered = true;
		}
		$sql .= $this->showInactive?'':($whereClauseEntered?' AND ':' WHERE ') . '`active` = 1';
		
		$stmt = $this->pdo->prepare($sql);
		if ($stmt->execute() && $stmt->rowCount() >= 1) {
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$this->templates[] = new Template($row, $this->pdo);
			}
		} else { 
			debug('Error retrieving template list.');
		}

}

return $this->templates;




}

private static function explodeSearchTerms ($column, $termsString, $matchAll = false) {

	// remove any commas and any multi-spacing
	$termsString = preg_replace('/\s+/', ' ',str_replace(',',' ',$termsString));
	// explode based on spaces
	$terms = explode(' ', $termsString);
	// wrap in "AND"s and "%"s
	$termsString = '';
	foreach ($terms as $key => $term) {
		$termsString .= ' `'. $column .'` LIKE "%' . $term . '%"' . (($key < sizeof($terms)-1)?($matchAll?' AND':' OR'):'');
	}
	return ' ( ' . $termsString . ' ) ';
}

/*



*/

}

?>