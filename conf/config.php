<?php
	// project root directory name
    define('project_root_directory_name','pmvc');
    
	// pagination on top of dataTable
	define('JQPAGESIZE',20);
	define('PDFEXPORTSIZE',500);
	
	// Titre de l'application
	define('app_name','PMVC');
	define('app_title','SYSTEME DE GESTION DES SALLES DE CONFERENCE DU ME');

	define('SERVER_IP','10.1.5.90');
	define('DBPWD_PROD','myAppPasswordX');
	define('DBPWD_DEV','');
	define('DBPWD','master');
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
	
