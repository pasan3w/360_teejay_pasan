<?php
// Include PHPWord library
require_once 'vendor/autoload.php';

// Get HTML content from POST request
$html = $_POST['html'];

// Create new PHPWord object
$phpWord = new \PhpOffice\PhpWord\PhpWord();
$section = $phpWord->addSection();
\PhpOffice\PhpWord\Shared\Html::addHtml($section, $html);

// Save PHPWord object as Word document
$filename = 'document.docx';
$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save($filename);

// Send filename back to client
echo $filename;
?>
