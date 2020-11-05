<?php
// ENTITIES
require_once("model/entity/Post.php");
require_once("model/entity/Departement.php");
require_once("model/entity/Commune.php");
require_once("model/entity/Structure.php");
require_once("model/entity/StructureType.php");
require_once("model/entity/Salle.php");

require_once("model/entity/ReservationSalle.php");
require_once("model/entity/Note.php");
require_once("model/entity/Demande.php");

// BUILDERS
require_once("model/builder/PostObjectBuilder.php");
require_once("model/builder/DepartementObjectBuilder.php");
require_once("model/builder/CommuneObjectBuilder.php");
require_once("model/builder/StructureObjectBuilder.php");
require_once("model/builder/StructureTypeObjectBuilder.php");
require_once("model/builder/SalleObjectBuilder.php");

require_once("model/builder/ReservationSalleObjectBuilder.php");
require_once("model/builder/NoteObjectBuilder.php");
require_once("model/builder/DemandeObjectBuilder.php");

// CONTROLLERS
require_once("controller/AppFormErrorController.php"); // does'nt need initialization (already done on parent class)
require_once("controller/ConfigFormManager.php");
require_once("controller/DemandeFormManager.php");


// CONTROLLERS INITIALIZATION
ConfigFormManager::init();
DemandeFormManager::init();
?>