<?php
require_once('../MYPDF.php');
// create new PDF document
$pdf = new MYPDF();
$date_debut = isset($_GET['date_debut'])?$db->escape($_GET['date_debut']):'';
$date_fin = isset($_GET['date_fin'])?$db->escape($_GET['date_fin']):'';
$id_salle = isset($_GET['id_salle'])?$db->escape($_GET['id_salle']):0;
$export_title = 'Planning du '.$date_debut.' au '.$date_fin;
$records = Demande::searchByReservationDateAsRecords(Systeme::dateToEnglish($date_debut),Systeme::dateToEnglish($date_fin));
$tab_filter_ids = Systeme::array_key_values($records,'id');
$demandes = Demande::inStates(array(VALIDATED),0,$tab_filter_ids);

$table_trs = '';
$dateDebut = DateTime::createFromFormat('d/m/Y',$date_debut);
$dateFin = DateTime::createFromFormat('d/m/Y',$date_fin);
$periode_str = $dateDebut->format('dmY').'_'.$dateFin->format('dmY');
$test = '';
$datas = array();
$diff1Day = new DateInterval('P1D');
$i = 0;
while($dateDebut<=$dateFin)
{
    $datas[$i]['date'] = $dateDebut->format('d/m/Y');
    $datas[$i]['horaire'] = '';
    $datas[$i]['objet'] = '';
    $datas[$i]['salle'] = '';
    $datas[$i]['html_content'] = '<table border="0" style=""><thead></thead>';
    $alt_color = true;
    foreach ($demandes as $key => $demande) {
      $reservations = $demande->reservations();
      foreach ($reservations as $key => $r) {
        $color = $alt_color?'#BDB76B':'#90EE90';
        $alt_color = !$alt_color;
         if($r->dateDebutWithoutTimes()==$dateDebut->format('Y-m-d'))
         {
            $datas[$i]['salle'].= '- '.$r->salle()->details().'<br>';
            $datas[$i]['horaire'].= '- '.substr($r->horaire(), 13).'<br>';
            $datas[$i]['objet'].= '- '.$demande->structure()->nom().': '.$demande->objet().'<br>';
            
            $datas[$i]['date'] = '<span style="background-color:'.$color.';">'.$dateDebut->format('d/m/Y').'</span>';
            $datas[$i]['html_content'].='<tr style="background-color:'.$color.';">
                    <td style="width:31%;">'.$r->salle()->details().'</td>
                    <td style="width:25%;">'.substr($r->horaire(), 13).'</td>
                    <td style="width:44%;">'.$demande->structure()->nom().': '.$demande->objet().'</td>
                </tr>';
         }
      }     
    }    
    $datas[$i]['html_content'].= '</table>';
    $dateDebut->add($diff1Day);
    $i++;
}

$i=0;
foreach ($datas as $key => $data) {
   $table_trs.='<tr>';
        $table_trs.='<td style="width:5%;text-align:center;">'.(++$i).'</td>';
        $table_trs.='<td style="width:15%;text-align:center;">'.$data['date'].'</td>';
        //$table_trs.='<td style="width:25%;">'.$data['salle'].'</td>';
        //$table_trs.='<td style="width:20%;">'.$data['horaire'].'</td>';
        //$table_trs.='<td style="width:35%;">'.$data['objet'].'</td>';
        $table_trs.='<td style="width:80%;">'.$data['html_content'].'</td>';        
    $table_trs.='</tr>';
}


// set font
$pdf->SetFont('times', '', 12);

// add a page
$pdf->AddPage();

// set some text to print
$html = <<<EOD
    <h3 style="text-align:center;"> $export_title </h3>
    <table cellpadding="1px;" border="1" style="border-collapse:collapse;text-align:left;">   
        <thead>
            <tr style="background-color:rgb(200,200,200);">  
                <th style="width:5%;text-align:center;">N°</th>                     
                <th style="width:15%;text-align:center;">Date</th>
                <th style="width:25%;text-align:center;">Salle</th>
                <th style="width:20%;text-align:center;">Heures</th>
                <th style="width:35%;text-align:center;">Objet</th>       
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
$pdf->Output('export_planning_'.$periode_str.'.pdf', 'D');// push for download

//============================================================+
// END OF FILE
//============================================================+