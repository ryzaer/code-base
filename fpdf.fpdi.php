<?php 
require_once('autobase.php');

function createPDF($text, $outputPath) {
    $pdf = new setasign\FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);
    
    $pdf->Cell(0, 10, $text, 0, 1, 'C');

    // export 
    $pdf->Output($outputPath, 'F');
}

function createFromHtml($or="P",$html, $outputPath) {
    $pdf=new setasign\tools\HTML2PDF();
    $pdf->AddPage($or);
    $pdf->SetFont('Arial','',12);

    $pdf->WriteHTML($html);
    
    // export 
    $pdf->Output($outputPath, 'F');
}

// merge multiple pdf files
function mergePDFsWithProtection($inputFiles, $outputFile, $password, $ownerpass=null) {
    // example 
    // $inputFiles = [$createdFile, 'assets/pdf/existing1.pdf', 'assets/pdf/existing2.pdf'];
    // mergePDFsWithProtection($inputFiles, $mergedFile, $password);

    $pdf = new setasign\Fpdi\FpdiProtection();
    
    // empty array all protected, Set to unprotection options: print, copy , modify , annot-forms
    $pdf->SetProtection(['print'],$password,$ownerpass);

    // Iterate through each input PDF file
    foreach ($inputFiles as $inputFile) {
        // Set the source PDF file
        $pageCount = $pdf->setSourceFile($inputFile);

        // Import and add all pages of the source PDF to the output PDF
        for ($page = 1; $page <= $pageCount; $page++) {
            $tplidx = $pdf->importPage($page);
            $specs = $pdf->getTemplateSize($tplidx);
            $pdf->addPage($specs['height'] > $specs['width'] ? 'P' : 'L');
            $pdf->useTemplate($tplidx);
        }
    }

    // Save the merged PDF with password protection
    $pdf->Output($outputFile, 'F');
}


// Usage example
$createdFile = 'assets/pdf/created.pdf';
$mergedFile = 'assets/pdf/merged.pdf';
$password = 'mypassword';

createFromHtml('P','Hello World this is <i>the example</i> <font face="times">The </font><b><font color="#7070D0">FPDF</font></b> library', 'assets/pdf/existing2.pdf');
$html='<table border="1">
    <tr>
    <td width="200" height="30">cell 1</td><td width="200" height="30" bgcolor="#D0D0FF">cell 2</td>
    </tr>
    <tr>
    <td width="200" height="30">cell 3</td><td width="200" height="30">cell 4</td>
    </tr>
    </table>';
createFromHtml('L',$html, 'assets/pdf/another.pdf');

mergePDFsWithProtection([$createdFile, 'assets/pdf/another.pdf', 'assets/pdf/existing2.pdf'],$mergedFile,$password);


print "<a href=\"$mergedFile\" target=\"_blank\">PDF encryption here</a>  password: $password";