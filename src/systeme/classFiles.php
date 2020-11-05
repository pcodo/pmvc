<?php
// ENTITIES
require_once("model/entity/MailJob.php");
require_once("model/entity/Pays.php");
require_once("model/entity/Direction.php");
require_once("model/entity/Service.php");
require_once("model/entity/Division.php");
require_once("model/entity/Token.php");
require_once("model/entity/TokenBundle.php");
require_once("model/entity/TokenBundleType.php");
require_once("model/entity/TokenForward.php");
require_once("model/entity/TokenProgress.php");
require_once("model/entity/TokenProgressValidation.php");
require_once("model/entity/TokenState.php");

// BUILDERS
require_once("model/builder/MailJobObjectBuilder.php");
require_once("model/builder/TokenObjectBuilder.php");
require_once("model/builder/TokenBundleObjectBuilder.php");
require_once("model/builder/TokenBundleTypeObjectBuilder.php");
require_once("model/builder/TokenForwardObjectBuilder.php");
require_once("model/builder/TokenProgressObjectBuilder.php");
require_once("model/builder/TokenProgressValidationObjectBuilder.php");
require_once("model/builder/TokenStateObjectBuilder.php");

// CONTROLLERS
require_once("controller/SystemeFormErrorController.php");
require_once("controller/TokenFormManager.php");

// CONTROLLERS INITILIZATION
Systeme::init();
SystemeFormErrorController::init();
TokenFormManager::init();
?>