<?php
	if (isset($_SESSION['login']))
	{
		if (isset($_SESSION['admin']) && $_SESSION['admin'] == '1')
		{
			if (isset($_GET['action']))
			{
				$action = $_GET['action'];
				if ($action == 'delete')
				{
					if (isset($_GET['id']))
					{
						//Supprimer un article
						$id = $_GET['id'];
						$query = 'DELETE FROM articles 
						WHERE id = '.$id.' LIMIT 1';
						mysqli_query($link, $query);
						
						//Supprimer les commentaires
						/** Pascal : On verra les cascades bientôt ! **/
						/*$query = 'DELETE FROM comments
						WHERE id_article = '.$id;
						mysqli_query($link, $query);*/
						header('Location: index.php?page=home');
						exit;
					}
					else
						$error = 'Il manque l\'id de l\'article';
				}
				else if (isset($_POST['title'], $_POST['imgUrl'], $_POST['description'], $_POST['content'], $_POST['createDate']))
				{
					$title = mysqli_real_escape_string($link, $_POST['title']);
					$imgUrl = mysqli_real_escape_string($link, $_POST['imgUrl']);
					$description = mysqli_real_escape_string($link, $_POST['description']);
					$content = mysqli_real_escape_string($link, $_POST['content']);
					$createDate = $_POST['createDate'];

					if (strlen($title) < 3)
						$error = 'Titre trop court';
					else if (strlen($title) > 63)
						$error = 'Titre trop long';/** Pascal : Max dans la db : 63 **/

					if (!filter_var($imgUrl, FILTER_VALIDATE_URL))
						$error = 'L\'url de votre image n\'est pas valide';

					if (strlen($description) < 10 )
						$error ='Description trop courte';
					else if (strlen($description) > 127)
						$error = 'Description trop longue';

					if (strlen($content) < 30 )
						$error ='Le contenu est trop court';
					else if (strlen($content) > 1023)
						$error = 'Le contenu est trop long';

					if (empty($error))
					{
						if ($action == 'add')
						{
							//creation d'un article
							$author = $_SESSION['login'];
							$query = "SELECT id 
							FROM users 
							WHERE login = '".$author."'";
							$res = mysqli_query($link, $query);
							$author_id = mysqli_fetch_assoc($res);
							$query = "INSERT INTO articles (author, title, description, content, image) 
							VALUES ('".$author_id['id']."','".$title."','".$description."','".$content."','".$imgUrl."')";
							mysqli_query($link, $query);
							header('Location: index.php?page=home');
							exit;
						}
						else if ($action == 'edit');
						{
							if (isset($_GET['id']))
							{
								//modifier un article
								$id = $_GET['id'];
								$lastDate = date('Y-m-d H:i:s');
								$query = 'UPDATE articles 
								SET title = \''.$title.'\', 
								description = \''.$description.'\', 
								content = \''.$content.'\', 
								image = \''.$imgUrl.'\', 
								`date` = \''.$createDate.'\', 
								last_date = \''.$lastDate.'\' 
								WHERE id = '.$id;
								mysqli_query($link, $query);
								header('Location: index.php?page=home');
								exit;
							}
							else
								$error = 'Il manque l\'id de l\'article';
						}
					}
				}
				else 
					$error = 'Veuillez renseigner tous les champs!';
			}
		}
		else
		{
			//redirection + msg disant "Vous n'avez pas les droits necessaire pour acceder à cette page"
			//$error = 'Vous n\'avez pas les droits nécessaires';/** Pascal : Le message d'erreur ne s'affichera jamais si vous faites une redirection :) **/
			header('Location: index.php?page=home');
			exit;
		}
	}
	else
	{
		header('Location: index.php?page=login');//redirection si utilisateur non connecté
		exit;
	}

?>