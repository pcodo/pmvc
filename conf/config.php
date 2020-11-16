<?php
	// project root directory name
    define('project_root_directory_name','pmvc');
    
	// pagination on top of dataTable
	define('JQPAGESIZE',20);
	define('PDFEXPORTSIZE',500);
	
	// Titre de l'application
	define('app_name','conf-rooms-manager');
	define('app_title','SYSTEME DE GESTION DES SALLES DE CONFERENCE DU ME');

	define('SERVER_IP','10.1.5.90');
	define('DBUSER','c1_confroom');
	define('DBNAME','c1_confroomDB');
	define('DBPWD','MTz4!B4nkknEge5YS_Gm');
	define('SMS_SERVER_IP','10.1.9.12');
	define('SMS_SERVER_FULL_ADDRESS','http://10.1.9.12:13013/cgi-bin/sendsms?username=admin&password=bar');

	// token states
	define('REGISTERED',1);
	define('OPEN',2);
	define('TREATED',3);
	define('ON_GOING',4);
	define('TREATED_STEP_1',5);
	define('TREATED_STEP_2',6);
	define('TREATED_STEP_3',7);
	define('TREATED_STEP_4',8);
	define('TREATED_STEP_5',9);	
	define('VALIDATED',10);
	define('REJECTED',11);
	define('FAIL_CLOSED',12);
	define('CLOSED',13);
	

	define('KB', 1024);
	define('MB', 1048576);
	define('GB', 1073741824);
	define('TB', 1099511627776);
	
