<?php
// Inclusion des librairies
include "fpdf/fpdf.php";
define("TVA",0.18);
/**
 * Classe PDF hérite de fpdf, permet de générer des fichiers PDF
 */
class PdfDataTable extends fpdf{
var $db;
var $data;
var $site;
var $titre = '';
var $al = array();
var $col_max_strlen = array();
/**
* Constructeur
*/
 
function PdfDataTable(){
	parent::fpdf();
	$this->SetCreator("CODO Paterne");
}
function setData($data,$titre='',$params = array())
{
	$this->data = $data;
	$this->titre = $titre!=''?$titre:'Liste des données';
	$this->al = $params['al'];
	$this->col_max_strlen = $params['col_max_strlen'];
}
public static function utf8_text($text)
{
	return utf8_decode($text);
} 
function text($text)
{
	return utf8_decode($text);
} 
//En-tête de la facture
function hautDePage(){
	$position=0;
	$this->SetXY(10,10);
	$this->SetFont('Arial','',8);
	$this->SetTextColor(0,0,0);
	$this->Cell(190,4,$this->text($this->titre),0,2,'C');
	$this->setXY(10,$this->getY()+5);
}
 
//Préparation de la génération de la table
function dataTable(){
	//Entête du tableau des articles
	$header=array();
	$w=array();
	$al=$this->al;
	if(count($this->data)!=0)
    {   
	    $d = $this->data[0];
		$fixedWidth = intval(190/count($d));
		$somme_total = array_sum($this->col_max_strlen);
		$j = 0;
		$len_ajusted = true;
		$len_ajuster_val = 9;
		$len_ajuster = $len_ajuster_val;
		// echo $len_ajuster; exit();
		foreach($d as $cle=>$val)
		{
			$header[] = $this->text($val);
			$len = (($this->col_max_strlen[$j]/$somme_total)*190);
			$w[] = $len;
			$j++;
		}
		
		$col_size = count($w);
		$wrong = true;
		$ajusted = array();
		while($wrong)
		{
			$wrong = false;
			for($i=0;$i<$col_size; $i++)
			{
				if($w[$i]<=$len_ajuster)
				{	
					$w[$i]+=$len_ajuster; 
					$len_ajusted = false;
					$wrong = true;
					$ajusted[] = $i;
					while(!$len_ajusted)
					{
						for($j=0;$j<$col_size;$j++)
						{
							if($len_ajuster!=0){
								if(!in_array($j,$ajusted)){
									if($w[$j]>20&&$len_ajuster>=4){
										$w[$j]-= 4;
										$len_ajuster-=4;
									}
									else if( $w[$j]>10&&$len_ajuster>=2){
										$w[$j]-=2;
										$len_ajuster-=2;
									}
									else{
										$w[$j] -= 1;
										$len_ajuster--;
									}
								}
							}
							else{
								$len_ajusted = true; // pas besoin car je ne m'en servirai plus mais c'est juste pour dire que tout est ajusté alors !
								break;
							}
						}
					}
					$len_ajuster = $len_ajuster_val;
				}
				
			}
		}
		array_shift($this->data); // On retire la premiere ligne du tableau qui est déjà dans header.
		// if($len_ajusted) echo 'YES =====> Line ajusted '.array_sum($w); 
		// else echo 'NO UNFORTUNATLY =====> Line not ajusted '.array_sum($w); 
		// exit();
	}	
	$this->table($header,$w,$al,$this->data);
}
// table(données de l'entête tableau, largeur des colonnes, alignement des colonnes, données)
function table($header,$w,$al,$datas){
	//Impression de l'entête tableau
	$this->SetLineWidth(.3);
	$this->printTableHeader($header,$w);
	$this->printTableRows($header,$datas,$w,$al);
	// $this->simpleTable($w,$al,$datas,$header);
	
}
// simpleTable equivaut à : printTableHeader suivi de printTableRows
function simpleTable($w,$al,$datas,$header=array()){
	//Impression de l'entête tableau
	$this->SetLineWidth(.3);
	$this->printTableHeader($header,$w);
	
 	$posStartX=$this->getX();	
	$posBeforeX=$posStartX;
 
	$posBeforeY=$this->getY();
	$posAfterY=$posBeforeY;
	$posStartY=$posBeforeY;
 
	//On parcours le tableau des données
	$k=0;// variable pour compter les itterations de data
	foreach($datas as $row){
	    $k++;
		$posBeforeX=$posStartX;
		$posBeforeY=$posAfterY;
 
		//On vérifie qu'il n'y a pas débordement de page.
		$nb=0;
		for($i=0,$j=count($w);$i<$j;$i++){
			$nb=max($nb,$this->NbLines($w[$i],$row[$i]));
		}
		$h=6*$nb;
 
		//Effectue un saut de page si il y a débordement
		$resultat = $this->CheckPageBreak($h,$w,$header,$posStartX,$posStartY,$posAfterY);
		if($resultat>0){
			$posAfterY=$resultat;
			$posBeforeY=$resultat;
			$posStartY=$resultat;
		}
		
		//Impression de la ligne de l'article courante n°k
		for($i=0,$j=count($w);$i<$j;$i++){
			$this->MultiCell($w[$i],6,$this->text(strip_tags($row[$i])),'LR',$al[$i],false);
			//On enregistre la plus grande hauteur de cellule
			if($posAfterY<$this->getY()){
				$posAfterY=$this->getY();
			}
			$posBeforeX+=$w[$i];
			$this->setXY($posBeforeX,$posBeforeY);
		}
						
		//Tracé de la ligne du dessous
		$this->Line($posStartX,$posAfterY,$posBeforeX,$posAfterY);
		$this->setXY($posStartX,$posAfterY);
		
	}
 	//Tracé des colonnes
	$this->PrintCols($w,$posStartX,$posStartY,$posAfterY);
}
//Impression de l'entête du tableau
function printTableHeader($header,$w){
	//Couleurs, épaisseur du trait et police grasse
	if(count($header)!=0) // Cette condition if que j'ai completé permet de tracer un simple trait s'il n'y a pas d'entête. Ce trait constituera le debut du tableau sinon le tableau aura une ouverture en haut (cas des tableau simple : simpleTable)
	{
		$this->SetFillColor(171,205,239);
		$this->SetTextColor(0);
		$this->SetDrawColor(0,0,0);
		$this->SetFont('Arial','B',9);
		for($i=0,$j=count($header);$i<$j;$i++)
			$this->Cell($w[$i],7,$header[$i],1,0,'C',1);
		$this->Ln();
		//Restauration des couleurs et de la police pour les données du tableau
		$this->SetFillColor(245,245,245);
		$this->SetTextColor(0);
		
	}
	else
	{
		$this->Line(10,$this->getY(),200,$this->getY());
	}
}
// Impression de l'entête de chaque si disponible
function printRowHeader($volet_title)
{
    // Je verifie si en ajoutant le titre du volet, je changerai de page
	$nb = 6;
	$nb=max($nb,$this->NbLines(190,$volet_title));
    $page_break_h = $this->CheckSimplePageBreak($nb); // le retour est le y actuel s'il y a eu saut de page
	$this->SetY($this->GetY());
	$this->MultiCell(190,6,$this->text($volet_title),1,'C',true);
}
// Impression des lignes du tableaux
function printTableRows($header,$datas,$w,$al)
{ // Les données qui sont envoyés sont des articles probablement regroupés par rubriques ou volets
	
	$posStartX=$this->getX();	
	$posBeforeX=$posStartX;
 
	$posBeforeY=$this->getY();
	$posAfterY=$posBeforeY;
	$posStartY=$posBeforeY;
 
	//On parcours le tableau des données
	foreach($datas as $row){
	    $posBeforeX=$posStartX;
		$posBeforeY=$posAfterY;
 
		//On vérifie qu'il n'y a pas débordement de page.
		$nb=0;
		for($i=0,$j=count($header);$i<$j;$i++){
			$nb=max($nb,$this->NbLines($w[$i],$row[$i]));
		}
		$h=6*$nb;
 
		//Effectue un saut de page si il y a débordement
		$resultat = $this->CheckPageBreak($h,$w,$header,$posStartX,$posStartY,$posAfterY);
		if($resultat>0){
			$posAfterY=$resultat;
			$posBeforeY=$resultat;
			$posStartY=$resultat;
		}
		
		//Impression de la ligne de l'article courante n°k
		for($i=0,$j=count($header);$i<$j;$i++){
			$this->MultiCell($w[$i],6,$this->text(strip_tags($row[$i])),'LR',$al[$i],false);
			//On enregistre la plus grande hauteur de cellule
			if($posAfterY<$this->getY()){
				$posAfterY=$this->getY();
			}
			$posBeforeX+=$w[$i];
			$this->setXY($posBeforeX,$posBeforeY);
		}
				
		//Tracé de la ligne du dessous
		$this->Line($posStartX,$posAfterY,$posBeforeX,$posAfterY);
		$this->setXY($posStartX,$posAfterY);
	}
	//Tracé des colonnes
	$this->PrintCols($w,$posStartX,$posStartY,$posAfterY);
} 
//Pied de page
function Footer(){
	//Positionnement à 1,5 cm du bas
	$this->SetY(-20);
	//Police Arial italique 8
	$this->SetFont('Arial','I',8);
	
}

//Tracé des colonnes
function PrintCols($w,$posStartX,$posStartY,$posAfterY){
	$this->Line($posStartX,$posStartY,$posStartX,$posAfterY);
	$colX=$posStartX;
	//On trace la ligne pour chaque colonne
	foreach($w as $row){
		$colX+=$row;
		$this->Line($colX,$posStartY,$colX,$posAfterY);
	}
}
 
//Vérification du débordement de page pendant l'écriture des lignes du tableau des articles
function CheckPageBreak($h,$w,$header,$posStartX,$posStartY,$posAfterY){
	//Si la hauteur h provoque un débordement, saut de page manuel
	if($this->GetY()+$h>$this->PageBreakTrigger){
		//On imprime les colonnes de la page actuelle
		$this->PrintCols($w,$posStartX,$posStartY,$posAfterY);
		//On ajoute une page
		$this->AddPage();
		//On réimprime l'entête du tableau
		$this->printTableHeader($header,$w);
		//On renvoi la position courante sur la nouvelle page
		return $this->GetY();
	}
	//On a pas effectué de saut on revoie 0
	return 0;
}

// vérification du débordement pendant l'ajout manuel de donnée sur le fichier
function CheckSimplePageBreak($h){
	//Si la hauteur h provoque un débordement, saut de page manuel
	if($this->GetY()+$h>$this->PageBreakTrigger){
		//On ajoute une page
		$this->AddPage();
		//On renvoi la position courante sur la nouvelle page
		return $this->GetY();
	}
	//On a pas effectué de saut on revoie 0
	return 0;
}
 
//Calcule le nombre de lignes qu'occupe un MultiCell de largeur w
function NbLines($w,$txt){
    $cw=&$this->CurrentFont['cw'];
    if($w==0)
        $w=$this->w-$this->rMargin-$this->x;
    $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
    $s=str_replace("\r",'',$txt);
    $nb=strlen($s);
    if($nb>0 and $s[$nb-1]=="\n")
        $nb--;
    $sep=-1;
    $i=0;
    $j=0;
    $l=0;
    $nl=1;
    while($i<$nb)
    {
        $c=$s[$i];
        if($c=="\n")
        {
            $i++;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
            continue;
        }
        if($c==' ')
            $sep=$i;
        $l+=$cw[$c];
        if($l>$wmax)
        {
            if($sep==-1)
            {
                if($i==$j)
                    $i++;
            }
            else
                $i=$sep+1;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
        }
        else
            $i++;
    }
    return $nl;
}
 
}
 

 
?>