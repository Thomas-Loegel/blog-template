<?php
	if (isset($_SESSION['login']))
	{
		if (isset($_SESSION['admin']) && $_SESSION['admin'] == '1') 
			require 'views/header_admin.phtml';
		else
			require 'views/header_user.phtml';
	}
	
	else
		require 'views/header_guest.phtml';
?> 