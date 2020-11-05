<?php
require_once('tcpdf_include.php');
//============================================================+
// File name   : example_003.php
// Begin       : 2008-03-04
// Last Update : 2013-05-14
//
// Description : Example 003 for TCPDF class
//               Custom Header and Footer
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: Custom Header and Footer
 * @author Nicola Asuni
 * @since 2008-03-04
 */

// Include the main TCPDF library (search for installation path).

class MYPDF extends TCPDF {

    //Page header
    public function __construct($orientation = PDF_PAGE_ORIENTATION,
            $unit = PDF_UNIT,
            $format =  PDF_PAGE_FORMAT,
            $unicode = true,
            $encoding = 'UTF-8',
            $diskcache = false,
            $pdfa = false )
    {
        parent::__construct($orientation,$unit,$format,$unicode,$encoding,$diskcache,$pdfa);

        // set document information
        $this->SetCreator(PDF_CREATOR);
        $this->SetAuthor('Nicola Asuni');
        //$this->SetTitle('TCPDF Example 003');
        $this->SetSubject('TCPDF Tutorial');
        $this->SetKeywords('TCPDF, PDF, example, test, guide');

        // set default header data
        $this->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

        // set header and footer fonts
        $this->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $this->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $this->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $this->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $this->SetHeaderMargin(PDF_MARGIN_HEADER);
        $this->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $this->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $this->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
            require_once(dirname(__FILE__).'/lang/eng.php');
            $this->setLanguageArray($l);
        }
    }
    public function Header() {
        // Logo
        $siteInfo = Systeme::siteInfo();
        // Logo
        //$image_file = K_PATH_IMAGES.'logo.jpg';
        $image_file = '../../../../'.$siteInfo['logo'];
        //$this->Image($image_file, 10, 10, 20, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        $this->Image($image_file, 10, 10, 20, '', '', '', 'T', false, 300, '', false, false, 0, false, false, false);
        // Set font
        $this->SetFont('helvetica', '', 8);
        // Title
        /*$this->setXY(20,15);
        $this->Cell(0, 15, $siteInfo['nom'], 0, false, 'C', 0, '', 0, false, 'M', 'M');
        $width = $this->getPageWidth();
        $lineLength = 50;
        $x1 =  $width/2-$lineLength/2 + 10;
        $x2 =  $width/2+$lineLength/2;
        $y = 20;
        $this->Line($x1,$y,$x2,$y);*/
        //$this->SetHeaderMargin
        //$this->SetFont('times', '', 12);
        /*Cell    ($w,  $h = 0, $txt = “,$border = 0, $ln = 0, $align = “, $fill = false,$link = “, $stretch = 0, $ignore_min_height = false, $calign = ’T’, $valign = ’M’ )       
        MultiCell ($w, $h, $txt, $border=0, $align=‘J’, $fill=false, $ln=1, $x=“, $y=”, $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0, $valign=’T’, $fitcell=false);
        Rect($x, $y, $w, $h, $style = “, $border_style = array(),$fill_color = array());*/
        $this->setXY(40,12);
        $this->Cell(0, 15, 'Ministère', 0, false, 'L', 0, '', 0, false, 'M', 'M');
        $this->setXY(40,16);
        $this->Cell(0, 15, 'De l\'Economie', 0, false, 'L', 0, '', 0, false, 'M', 'M');
        $this->setXY(40,20);
        $this->Cell(0, 15, 'Et des Finances', 0, false, 'L', 0, '', 0, false, 'M', 'M');
        
        $style = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 255, 0));
        $this->line(40,23,55,23,$style);
        $style = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(255, 255, 0));
        $this->line(55,23,70,23,$style);
        $style = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(200, 0, 0));
        $this->line(70,23,85,23,$style);
        $this->setXY(40,26);
        $this->Cell(0, 15, 'REPUBLIQUE DU BENIN', 0, false, 'L', 0, '', 0, false, 'M', 'M');

        $this->setXY(150,12);
        $this->Cell(0, 15, 'Tél: 21 30 10 20 - Fax: 21 30 18 51', 0, false, 'R', 0, '', 0, false, 'M', 'M');
        $this->setXY(150,16);
        $this->Cell(0, 15, '01 BP: 302 COTONOU - ROUTE DE L\'AEROPORT', 0, false, 'R', 0, '', 0, false, 'M', 'M');
        $this->setXY(150,20);
        $this->Cell(0, 15, 'www.finances.bj', 0, false, 'R', 0, '', 0, false, 'M', 'M');




    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        //$this->SetFont('helvetica', 'I', 8);
        //$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages().' généré sur e-pension @ '.date('d/m/Y'), 0, false, 'C', 0, '', 0, false, 'T', 'M');
        $width = $this->getPageWidth();
        $x = $width/2 - 22.5;
        $style = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 255, 0));
        $this->Rect($x, $this->getY(), 15, 3, 'DF', $style, array(0, 255, 0));
        $this->Rect($x+15, $this->getY(), 15, 3, 'DF', $style, array(255, 255, 0));
        $this->Rect($x+30, $this->getY(), 15, 3, 'DF', $style, array(200, 0, 0));
        $this->setXY(5,$this->getY()+2);
        $this->SetFont('helvetica', 'I', 8);
        $this->Cell($width,10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages().' généré sur E-pension 16.03.01 @ '.date('d/m/Y'), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}

?>