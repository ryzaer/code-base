<?php
require_once 'autobase.php';
$pdf=new setasign\tools\HTML2PDF();


$pdf->AddPage();
$pdf->SetFont('Arial','',12);

$html='<b>Testing</b> render  lkajs lkjaslkdj alkdjalskdj <i>alskdjsalkdj</i> saldkjsadlkjsad lsakjdlksajd lsakdjsal dlksajd salkjdla jdlksaj table <br><table border="1">
<tr>
<td width="100" height="30">cell 1 kashk</td>
<td width="200" height="30" bgcolor="#D0D0FF">cell 2</td>
</tr>
<tr>
<td width="100" height="30">cell 3</td>
<td width="200" height="30">cell 4</td>
</tr>
</table>';
$pdf->WriteHTML($html);
$html='<b>Testing</b> render lkajs lkjaslkdj alkdjalskdj <i>alskdjsalkdj</i> saldkjsadlkjsad lsakjdlksajd lsakdjsal dlksajd salkjdla jdlksaj table';
$pdf->TableRows(60,60,60);
$pdf->TableHTML([
    [$html,$html,$html],
    [$html,$html,$html],
    [$html,$html,$html]
]);
// var_dump($pdf);die;


$pdf->Output();