<html>
<head>
<script>

var submitter = function(evt) {
    document.getElementById('theForm').submit();
}

<?php

if (isset($_GET['ref']) && $_GET['ref'] === 'a22b8d32') {

  echo("window.addEventListener('load', submitter);");

}

?>

</script>
</head>
<body>
<?php
if (isset($_GET['ref']) && $_GET['ref'] === 'a22b8d32') {

$formHTML = <<<'XXXX'

<form id="theForm" style="display:none;" action="http://templates.jrsouth.com/create/Supperclub_Owen_Bowden.pdf" method="post">
<input type="hidden" name="template_id" value="3">
<input name="download_name" type="hidden" value="Supperclub_Owen_Bowden.pdf">
<input type="hidden" name="f12" value="Owen">
<input type="hidden" name="f15" value="Bowden">
<input type="hidden" name="f13" value="obowden@bloodwise.org.uk">
<input type="hidden" name="f14" value="07123 123 456">
<input type="hidden" name="f10" value="20th December 2016">
</form>

XXXX;

echo($formHTML);


} else {

    echo('<p>Sorry, an error occurred.</p>');

}



?>
</body>
</html>
