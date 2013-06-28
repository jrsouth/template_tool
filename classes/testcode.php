<?php
// Test code for OOP stuff
  spl_autoload_register(function ($class) {
      include $class . '.class.php';
  });
  
  
  $settings = parse_ini_file ('settings.ini'); 
  
  
function debug($msg) {
	global $settings;

	if ($settings['debug']) {
		echo '<div style="background-color:#ffcccc;border:#ff0000 1px solid;padding:5px;">';
		echo '<strong>Debug message:</strong><br />';
		echo '<pre>';
		echo $msg;
		echo '</pre>';
		echo '</div>';
	}

}  
  
$pdoString = 'mysql:host=' . $settings['dbHost'] . ';dbname=' . $settings['dbName'];
$pdo = new PDO($pdoString, $settings['dbUser'], $settings['dbPassword']);




$templateList = new TemplateList($pdo, '', 'test page', true);

foreach($templateList->getTemplates() as $template) {
  $template->getHtmlForm();
}




?>