<?php	
	session_start();

	$access = array('home', 'login', 'register', 'admin_article', 'article', 'commentaires', 'logout');
	$page = 'home' /*page courante : home par default*/ ;
	$error = '';
	require('config.php');
	$link = mysqli_connect("".$localhost.", ".$login.", ".$pass.", ".$datatbase."");

	if (!$link)
	{
	    require('views/bigerror.phtml');
	    exit;
	}

	if (isset($_GET['page']))
	{
		if (in_array($_GET['page'], $access))
			$page = $_GET['page'];
	}

	$access_traitement = array('login', 'register', 'admin_article', 'commentaires', 'logout');
	
	if (in_array($page, $access_traitement))
		require('apps/treatments/traitement_'.$page.'.php');// apps/traitement_login.php ou apps/traitement_register.php ou apps/traitement_contact.php
	
	require 'apps/skel.php';
?>