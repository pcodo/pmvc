<?php
require_once('../MYPDF.php');
// create new PDF document
$pdf = new MYPDF();
$export_title = 'Liste des departements';
$departements = Departement::all();
$table_trs = '';
$i = 0;
foreach ($departements as $key => $departement) {
    $table_trs.='<tr>';
    $table_trs.='<td style="width:10%;">'.(++$i).'</td>';
    $table_trs.='<td style="width:25%;">'.$departement->nom().'</td>';
    $table_trs.='<td style="width:35%;">'.$departement->description().'</td>';
    $table_trs.='<td style="width:30%;">'.Systeme::dateTimeToFrench($departement->insertDate()).'</td>';
   $table_trs.='</tr>';
}
// set font
$pdf->SetFont('times', '', 12);

// add a page
$pdf->AddPage();

// set some text to print
$html = <<<EOD
    <h3 style="text-align:center;"> $export_title </h3>
    <table cellpadding="1px;" border="1" style="border-collapse:collapse;text-align:center;">   
        <thead>
            <tr style="background-color:rgb(200,200,200);">  
                <th style="width:10%;">N°</th>                     
                <th style="width:25%;">Désignation</th>
                <th style="width:35%;">Description</th>
                <th style="width:30%;">Enregistrement</th>       
            </tr> 
        </thead>
        $table_trs
    </table>
EOD;

// Print text using writeHTMLCell()
$pdf->writeHTMLCell(0,0,10,40, $html, 0, 1, 0, true, '', true);

// ---------------------------------------------------------

//Close and output PDF document
//$pdf->Output('example_003.pdf', 'I');// open in another tab
$pdf->Output('export_departement_'.date('Y').'.pdf', 'D');// push for download

//============================================================+
// END OF FILE
//============================================================+