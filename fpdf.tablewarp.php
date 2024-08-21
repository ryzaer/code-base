<?php 
require_once 'autobase.php';
function GenerateWord()
{
    // Get a random word
    $nb = rand(3,10);
    $w = '';
    for($i=1;$i<=$nb;$i++)
        $w .= chr(rand(ord('a'),ord('z')));
    return $w;
}

function GenerateSentence($words=70)
{
    // Get a random sentence
    $nb = rand(20,$words);
    $s = '';
    for($i=1;$i<=$nb;$i++)
        $s .= GenerateWord().' ';
    return substr($s,0,-1);
}

$pdf = new setasign\tools\PDFMultiTable('P','pt','A4');
// you can protect the document
// $password = 'mypassword';
// $ownerpass = null;
// $pdf->SetProtection(['print'],$password,$ownerpass);
// count all generated page number
$pdf->setTitle("My List Documents");
$pdf->AliasNbPages();
$pdf->AddPage('P');
$pdf->SetFont('Arial','',11);
// $pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}',0,0,'C');
$pdf->Ln();
$pdf->MultiCell(0,10,'Example to build a table over more than one page kjzhjhdkjahsd asjd kajs askj aksjdhad kajshdkhasd kjhkjsahd lksajdlk jdlksajd',null,"C");
$pdf->Ln();
$pdf->SetFont('Arial','',10);
// table widths default 90;
$pdf->tablewidths = array(135, 135, 135, 135); 
for($i=0;$i<20;$i++) {
    $data[] = array(GenerateSentence(), GenerateSentence(), GenerateSentence(), GenerateSentence());
}
$pdf->morepagestable($data,50,11);



// var_dump($pdf);die;

$pdf->Output();