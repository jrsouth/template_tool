<?php




/**
 * tools/createPDF.php
 *
 * @package default
 */

// Set up environment




require_once('../functions.php');
require_once('../process.php');




$thumbnail_location = $cache_path . 'thumbnails/template_'.$template['id'].'.png';
$preview_location = $cache_path . 'default/template_'.$template['id'].'_p' . (isset($_GET['page'])?$_GET['page']:'1') . '.png';





// Quick check for existing thumbnail or default preview - serve if available
if (isset($_GET['view']) && $_GET['view'] == 'thumbnail' && file_exists($thumbnail_location)) {
	header("Content-type: image/png");
	readfile($thumbnail_location);
	exit();
} else if (isset($_GET['reset']) && isset($_GET['view']) && $_GET['view'] == 'preview' && file_exists($preview_location)) {
	header("Content-type: image/png");
	readfile($preview_location);
	exit();
}


$working_template = array();

if ($working_template_id) {

	$sql = 'SELECT * FROM `working_templates` WHERE `id` = '.$working_template_id;
	if ($result = mysqli_query(DB::$conn,$sql)) {

		$result = mysqli_fetch_assoc($result);

		mb_parse_str($result['data'], $working_template);

	}
} else if (isset($_POST['template_id'])) {
	mb_parse_str(processTemplatePOSTData(), $working_template);
}



// String output renders in the browser as a PDF page, without needing to write a file to disk
$stringOutput = false;
if (isset($_GET['stringoutput']) && $_GET['stringoutput'] == 1) {
	$stringOutput = true;
}





if (isset($template['pdf_file']) && file_exists($base_path . 'storage/templates/'.$template['pdf_file'])) {

	$pdf = new FPDI();

	$pdf->SetMargins(0, 0);
	$pdf->cMargin = 0;
	$pdf->AutoPageBreak = 0;

	// Get template PDF file and extract 1st page
	$pagecount = $pdf->setSourceFile($base_path . 'storage/templates/'.$template['pdf_file']);
	$currentpage = 0;


	if (isset($_GET['page'])) { // If specified only generate one page
		$pagecount = $_GET['page'];
		$currentpage = $_GET['page']-1;
	}



	// Per page loop
	while (++$currentpage <= $pagecount) {

		$template_page = $pdf->importPage($currentpage);

		// Set imported page as "background"
		$page_size = $pdf->getTemplateSize($template_page);
		if ($page_size['w'] >= $page_size['h']) {
			$orientation = 'L';
		}else {
			$orientation = 'P';
		}

		$pdf->addPage($orientation, array($page_size['w'], $page_size['h']));


		if ($draw_grid) { // true for grid, false for no grid
			// ---------- REFERENCE GRID ----------------------
			// ------------------------------------------------

			// Draw a 1mm grid for reference
			$pdf->SetLineWidth(.05);
			$pdf->SetDrawColor(220);
			$spacing = 1;
			$numLines = 300;
			for ($i = 0; $i <= $numLines; $i++) {
				$pdf->Line(0, $i*$spacing, $spacing*$numLines, $i*$spacing);
				$pdf->Line($i*$spacing, 0, $i*$spacing, $spacing*$numLines);
			}
			// Draw a 10mm grid for reference
			$pdf->SetLineWidth(.1);
			$pdf->SetDrawColor(180);
			$spacing = 10;
			$numLines = 30;
			for ($i = 0; $i <= $numLines; $i++) {
				$pdf->Line(0, $i*$spacing, $spacing*$numLines, $i*$spacing);
				$pdf->Line($i*$spacing, 0, $i*$spacing, $spacing*$numLines);
			}
			// ------------------------------------------------
			// ------------------------------------------------
		}



		$pdf->useTemplate($template_page, 0, 0, 0, 0, true);




		// Loop through fields and place either provided text or default

		// Code to select fields from database
		$images = get_images($template['id'], $currentpage);
		$fields = get_fields($template['id'], $currentpage);

		foreach ($images as $image) { // display images in order

		$imgfile = $base_path . getDefaultImage($image['id']);

			if (isset($working_template['img'.$image['id']])) { // Override if set
				$imgfile = $working_template['img'.$image['id']];
			}
			placeImage($pdf, $imgfile, $image['x_position'], $image['y_position'], $image['width'], $image['height'], $image['alignment']);

		}


		foreach ($fields as $field) {
		if ($field['type'] != 'data') {

			// Get font details
			$sql = 'SELECT * FROM `fonts` WHERE `id` = ' . $field['font_id'];
			$font = mysqli_fetch_assoc(mysqli_query(DB::$conn,$sql));

			// Get colour details
			$sql = 'SELECT * FROM `colours` WHERE `id` = ' . $field['colour_id'] ;
			$colour = mysqli_fetch_assoc(mysqli_query(DB::$conn,$sql));
			// Set colour RGB value (Strip alpha value)
			$colourValue = array(
				'R' => hexdec(substr($colour['RGBA'], 0, 2)),
				'G' => hexdec(substr($colour['RGBA'], 2, 2)),
				'B' => hexdec(substr($colour['RGBA'], 4, 2)),
				'A' => hexdec(substr($colour['RGBA'], 6, 2))
			);

			// Set default text
			$content = $field['default_text'];

			// Replace with text provided if available
			if (isset($working_template['f'.$field['id']])) {
				$content = $working_template['f'.$field['id']];
			}
			
			if ($field['type'] == 'wrapper') {
			// insert content from data fields into wrapper fields
			// Pretty HACK-y at the moment...
			      
			      $sections = explode('{', $content);
			      $content = '';
			      foreach ($sections as $sectionNum => $section) {
				  if ($sectionNum > 0) {
					$parts = explode('}', $section);
					
					// Set default data HACK
					foreach ($fields as $searchField) {
					    if ($searchField['id'] == $parts[0]) {
						$data = $searchField['default_text'];
					    }
					}

					// Replace with data provided if available
					if (isset($working_template['f'.$parts[0]])) {
						$data = $working_template['f'.$parts[0]];
					}

					$content .= $data . $parts[1];
				  } else {
					$content .= $section;
				  }
			      }
			}

			// Change encoding (FPDF doesn't handle UTF-8)
			$content = iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $content);

			// Force to UPPERCASE if required
			if ($field['force_uppercase']) {
				$content = strtoupper($content);
			}

			// Add in \n line breaks
			// $content = "Test\nline2";

			$pdf->AddFont($font['display_name'], '', $font['font_file']);
			$pdf->SetFont($font['display_name']);
			$pdf->SetFontSize($field['font_size']);
			$pdf->SetFontSpacing($field['kerning']); // XXXX TESTING ONLY
			$pdf->SetTextColor($colourValue['R'], $colourValue['G'], $colourValue['B']); // Need to convert CMYK to RGB (or allow CMYK text)
			$leadingmm = 0.352777778 * $field['leading']; // Convert points to mm
			if ($field['parent'] > 0) {
				// Put the text under the parent - using the same wrapping, offset by y_position
				//$pdf->Ln();
				$pdf->SetY($pdf->GetY()+$field['y_position']);
				// Original Write method // $pdf->Write($leadingmm, $content);
				$pdf->MultiCell(0, $leadingmm, $content, 0, 'L');
			} else if ($field['wrap_width'] > 0) {
					// Reset wrapping and put the text within a bounding box
					$pdf->SetXY($field['x_position'], $field['y_position']);
					$pdf->setRightMargin($page_size['w']-$field['x_position']-$field['wrap_width']);
					$pdf->setLeftMargin($field['x_position']);
					// Original Write method // $pdf->Write($leadingmm, $content);
					$pdf->MultiCell(0, $leadingmm, $content, 0, 'L');
				} else {
				// Put single-line text at defined point (baseline)
				$pdf->Text($field['x_position'], $field['y_position'], $content);
			}
		}
	} // end skipping of display for fields of 'data' type (i.e. inserted into other 'wrapper' text)
	}

} else { // Generate error PDF

	$pdf = new FPDI();
	$pdf->addPage('L', array(150, 80));
	$pdf->AddFont('Lucida Sans Unicode', '', 'lucida_sans_unicode.php');
	$pdf->SetFont('Lucida Sans Unicode');
	$pdf->SetFontSize(50);
	$pdf->SetTextColor(226, 0, 26);
	$pdf->Text(25, 25, "OH NO!");
	$pdf->SetFontSize(25);
	$pdf->SetTextColor(127, 127, 127);
	$pdf->Text(25, 35, "An error occurred...");

}




if (isset($_GET['view'])) {  // Output a JPEG preview/thumbnail

	$pdffile = $cache_path . 'pdf/'.uniqid('LLR').'.pdf';

	$handle = fopen($pdffile, 'cb');
	fwrite($handle, $pdf->Output('LLR.pdf', 'S'));
	fclose($handle);
	displayPDF($pdffile, $_GET['view'], $template['id'], (isset($_GET['page'])?$_GET['page']:'1'));

} else { // Let the browser handle the PDF

	$pdf->Output($template['name'].'.pdf', 'I');
}



