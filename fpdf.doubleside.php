<?php
require_once 'autobase.php';

$pdf = new setasign\tools\DoubleSided_PDF();
$pdf->AliasNbPages();
$pdf->SetDoubleSided(20,10);
$pdf->SetFont('Arial','',12);
$pdf->AddPage();
for ($i=0; $i < 60; $i++ )
    $pdf->MultiCell(0, 10, str_repeat('a lot of text ',30)."...\n");
$pdf->SetDisplayMode('fullpage', 'single');
$pdf->Output();