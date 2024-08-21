<?php 
namespace setasign\tools;

// example use
// $pdf = new setasign\tools\DoubleSided_PDF();
// $pdf->AliasNbPages();
// $pdf->SetDoubleSided(20,10);
// $pdf->SetFont('Arial','',12);
// $pdf->AddPage();
// for ($i=0; $i < 60; $i++ )
//     $pdf->MultiCell(0, 10, str_repeat('a lot of text ',30)."...\n");
// $pdf->SetDisplayMode('fullpage', 'single');
// $pdf->Output();

class DoubleSided_PDF extends \setasign\FPDF
{
    protected $doubleSided;    // layout like books?
    protected $innerMargin;
    protected $outerMargin;
    protected $xDelta;         // if double-sided, difference between outer and inner

    function __construct($orientation='P', $unit='mm', $size='A4')
    {
        parent::__construct($orientation,$unit,$size);
        $this->doubleSided = false;
        $this->innerMargin = 10;
        $this->outerMargin = 10;
        $this->xDelta = 0;
    }

    function SetDoubleSided($inner=7, $outer=13)
    {
        if($outer != $inner) {
            $this->doubleSided = true;
            $this->innerMargin = $inner;
            $this->outerMargin = $outer;
        }
    }

    function Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')
    {
        // Output a cell
        $k = $this->k;
        if($this->y+$h>$this->PageBreakTrigger && !$this->InHeader && !$this->InFooter && $this->AcceptPageBreak())
        {
            // Automatic page break
            $x = $this->x;
            $ws = $this->ws;
            if($ws>0)
            {
                $this->ws = 0;
                $this->_out('0 Tw');
            }
            $this->AddPage($this->CurOrientation,$this->CurPageSize,$this->CurRotation);
            $this->x = $x+$this->xDelta;
            if($ws>0)
            {
                $this->ws = $ws;
                $this->_out(sprintf('%.3F Tw',$ws*$k));
            }
        }
        if($w==0)
            $w = $this->w-$this->rMargin-$this->x;
        $s = '';
        if($fill || $border==1)
        {
            if($fill)
                $op = ($border==1) ? 'B' : 'f';
            else
                $op = 'S';
            $s = sprintf('%.2F %.2F %.2F %.2F re %s ',$this->x*$k,($this->h-$this->y)*$k,$w*$k,-$h*$k,$op);
        }
        if(is_string($border))
        {
            $x = $this->x;
            $y = $this->y;
            if(strpos($border,'L')!==false)
                $s .= sprintf('%.2F %.2F m %.2F %.2F l S ',$x*$k,($this->h-$y)*$k,$x*$k,($this->h-($y+$h))*$k);
            if(strpos($border,'T')!==false)
                $s .= sprintf('%.2F %.2F m %.2F %.2F l S ',$x*$k,($this->h-$y)*$k,($x+$w)*$k,($this->h-$y)*$k);
            if(strpos($border,'R')!==false)
                $s .= sprintf('%.2F %.2F m %.2F %.2F l S ',($x+$w)*$k,($this->h-$y)*$k,($x+$w)*$k,($this->h-($y+$h))*$k);
            if(strpos($border,'B')!==false)
                $s .= sprintf('%.2F %.2F m %.2F %.2F l S ',$x*$k,($this->h-($y+$h))*$k,($x+$w)*$k,($this->h-($y+$h))*$k);
        }
        $txt = (string)$txt;
        if($txt!=='')
        {
            if(!isset($this->CurrentFont))
                $this->Error('No font has been set');
            if($align=='R')
                $dx = $w-$this->cMargin-$this->GetStringWidth($txt);
            elseif($align=='C')
                $dx = ($w-$this->GetStringWidth($txt))/2;
            else
                $dx = $this->cMargin;
            if($this->ColorFlag)
                $s .= 'q '.$this->TextColor.' ';
            $s .= sprintf('BT %.2F %.2F Td (%s) Tj ET',($this->x+$dx)*$k,($this->h-($this->y+.5*$h+.3*$this->FontSize))*$k,$this->_escape($txt));
            if($this->underline)
                $s .= ' '.$this->_dounderline($this->x+$dx,$this->y+.5*$h+.3*$this->FontSize,$txt);
            if($this->ColorFlag)
                $s .= ' Q';
            if($link)
                $this->Link($this->x+$dx,$this->y+.5*$h-.5*$this->FontSize,$this->GetStringWidth($txt),$this->FontSize,$link);
        }
        if($s)
            $this->_out($s);
        $this->lasth = $h;
        if($ln>0)
        {
            // Go to next line
            $this->y += $h;
            if($ln==1)
                $this->x = $this->lMargin;
        }
        else
            $this->x += $w;
    }

    function _beginpage($orientation, $size, $rotation)
    {
        parent::_beginpage($orientation,$size,$rotation);
        if ( $this->doubleSided ) {
            if( $this->page % 2 == 0 ) {
                $this->xDelta = $this->outerMargin - $this->innerMargin;
                $this->SetLeftMargin($this->outerMargin);
                $this->SetRightMargin($this->innerMargin);
            } else {
                $this->xDelta = $this->innerMargin - $this->outerMargin;
                $this->SetLeftMargin($this->innerMargin);
                $this->SetRightMargin($this->outerMargin);
            }
            $this->x = $this->lMargin;
            $this->y = $this->tMargin;
        }
    }

    /// format here...
    function Header()
    {
        if ( $this->PageNo() % 2 == 0 ) {
            $this->Cell(30,0,$this->PageNo(),0,0,'L');
            $this->Cell(0,0,'This chapter has a title',0,0,'R');
        }
        else {
            $this->Cell(160,0,'Topic of this chapter',0,0,'L');
            $this->Cell(0,0,$this->PageNo(),0,2,'R');
        }
        //Line break
        $this->SetY(20);
        $this->SetLineWidth(0.01);
        $this->Line($this->lMargin, 18, 210 - $this->rMargin, 18);
        $this->Ln(15);
    }

    function Footer()
    {
        //Position at 1.5 cm from bottom
        $this->SetLineWidth(0.01);
        $this->Line($this->lMargin, 281.2, 210 - $this->rMargin, 281.2);
        $this->SetY(-10);
        //Page number
        $this->Cell(0,3,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }
}