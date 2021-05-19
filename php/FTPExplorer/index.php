<?php
@session_start();

// DOC : https://www.php.net/manual/fr/book.ftp.php

// ftp_rawlist() - Fait une liste détaillée des fichiers d'un dossier
// ftp_mlsd() - Retourne la liste des fichiers d'un dossier donné
// ftp_delete — Efface un fichier sur un serveur FTP
// ftp_exec — Exécute une commande sur un serveur FTP
// ftp_mkdir — Crée un dossier sur un serveur FTP

if(!empty($_POST['password'])) {	
	$_SESSION['ftp_host'] = $_POST['host'];
	$_SESSION['ftp_user'] = $_POST['login'];
	$_SESSION['ftp_password'] = $_POST['password'];		
}

// construction de l'url FTP : ftp://user:password@server
$ftp_url = 'ftp://'.$_SESSION['ftp_user'].':'.$_SESSION['ftp_password'].'@'.$_SESSION['ftp_host'].'/';

// construction du dossier cible

$ftp_folder = ""; 			// dossier par défaut
if(!empty($_GET['dir'])) 
{
	$ftp_folder = $ftp_folder.$_GET['dir'];
}
$ftp_folder = str_replace('//','/',$ftp_folder);

//Connexion au ftp
$conn = ftp_connect($_SESSION['ftp_host']);
$login = ftp_login($conn, $_SESSION['ftp_user'], $_SESSION['ftp_password']);

$mode = ftp_pasv($conn, TRUE); //Enable PASV ( Note: must be done after ftp_login() )

//Login 
if ((!$conn) || (!$login) || (!$mode)) {
	$_SESSION['logged'] = false;
}
else{
	$_SESSION['logged'] = true;
		
	$_SESSION['msg_login'] = 'Connecté à '.$_SESSION['ftp_host'].' en tant que '.$_SESSION['ftp_user'] ;

	// Récupération du contenu du dossier cible
	$list = ftp_nlist($conn, $ftp_folder);
	$fulllist = ftp_rawlist($conn, $ftp_folder);
	
	foreach($list as $obj) 
	{
		if(is_dir($ftp_url.$ftp_folder.'/'.$obj))
		{						
			$data['folders'][] = [
				'name' => $obj,
				'path' => '',
				'type' => 'dir',
				'chmod' => '',
				'size' => '', 
				'updated_at' => ''
			];
		}
		else{
			$data_chmod = ftp_rawlist($conn, $ftp_folder.'/'.$obj);			
			$data_chmod = explode(' ',$data_chmod[0]);
			
			$data['files'][] = [
				'name' => $obj,
				'path' => '',
				'type' => 'file',
				'chmod' => $data_chmod[0],
				'size' => ftp_size($conn, $ftp_folder.'/'.$obj), 
				'updated_at' => date('Y-m-d H:i:s', ftp_mdtm($conn, $ftp_folder.'/'.$obj))
			];
		}
	}
	
	// var_dump($data);
	
	// traitement des formulaires :
	
		// ajout dossier
		if(!empty($_POST['dirname'])) {
			
			// on se place dans le dossier correspondant
			if (!ftp_chdir($conn, $ftp_folder)) {
				$_SESSION['flash'] =  "Impossible de changer de dossier vers : ".$ftp_folder;
			}
				echo 'dossier en cours (ftp) : '.ftp_pwd($conn);
			
			// création du dossier
			if(ftp_mkdir($conn,$_POST['dirname']))
			{
				$_SESSION['flash'] = 'Terminé : création du dossier : '.$_POST['dirname'];			
			}
			else{
				$_SESSION['flash'] = 'Erreur : création du dossier '.$_POST['dirname'].' impossible dans '.ftp_pwd($conn);
			}
		}
		
		// envoi fichiers
		
			$files_nb = count($_FILES['files']['name']);
			for($i = 0; $i < $files_nb; $i++)
			{
				$upload_file = $ftp_folder .'/'. basename($_FILES['files']['name'][$i]);
				$upload_file = str_replace('www/','',$upload_file);
				$_SESSION['flash'] = '';
				if(move_uploaded_file($_FILES['files']['tmp_name'][$i], $upload_file))
				{
					$_SESSION['flash'] .= '<br/>Terminé : envoi du fichier : '.$_FILES['files']['tmp_name'][$i].' vers '.$upload_file;
				}
				else{					
					$_SESSION['flash'] .= '<br/>Erreur : envoi du fichier : '.$_FILES['files']['tmp_name'][$i].' vers '.$upload_file;
				}
			}
}

//close
ftp_close($conn);
?>
<!doctype html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		
		<title>FTPExplorer</title>
		
		<!-- FontAwesome CDN -->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		
		<style type="text/css">

			body {font-size:14px;font-family:Roboto, Arial;background:#ddd;line-height:1.5rem;}
			header {background:#444;color:#fff;padding:1rem;}
			header h1 {margin:5px 0;padding:0;}
			main {background:#efefef;}
			a {color:inherit;text-decoration:none;}
			.ftp-nav {min-width:30%;}
			.ftp-content {flex:1;}
			textarea.file-content {width:100%;height:700px;line-height:1.5rem;background:#444;color:#ddd;}
			
			footer {background:#000; color:#fff;border-top:1 px solid silver;}
			
			table {width:100%;}
			th {text-align:left;}
			tr:hover{background:#ddd;}
		</style>
	</head>
	<body>
		<header>
			<h1><a href="<?= $_SERVER['PHP_SELF']; ?>" title="Homepage">FTP Explorer</a></h1>
			<form method="post">
				<input type="text" name="host" value="ftp.cluster010.hosting.ovh.net" placeholder="Adresse du serveur FTP" />
				<input type="text" name="login" value="gsientre" placeholder="Nom d'utilisateur" />
				<input type="password" name="password" value="" placeholder="Mot de passe" />
				<button type="submit">Connexion</button>
			</form>	
		</header>
		<main>	
			<?php
				// AFFICHAGE DE LA CONNEXION AU SERVEUR
				if(!empty($_SESSION['msg_login'])) {
					echo '<div class="alert alert-success">'.$_SESSION['msg_login'].'</div><hr/>';
				}
			?>
			
			<?php 
				// AFFICHAGE DES MESSAGES FLASH
				if(!$_SESSION['logged']) {die("Imposible de se connecter au serveur FTP");}
				
				if(!empty($_SESSION['flash'])) {
					echo $_SESSION['flash'].'<hr/>';
					unset($_SESSION['flash']);
					
				}
			?>
			
			Dossier en cours : 
			
			<?php 
				$ftp_folder_arr = explode('/',$ftp_folder);
				$link = ''; $previous_folder = '';
				foreach($ftp_folder_arr as $element)
				{
					$link .= '<a href="?dir='.$previous_folder.$element.'" title="">'.$element.'</a> > ';						
					
					$previous_folder .= $element.'/';
				}
				echo $link;
				
			?>
			<hr/>
			<!--<br/><input type="text" name="folder" value="<?=$ftp_folder;?>" />-->
			
			<div style="display:flex;">
				<div class="ftp-nav">
					<strong><?= $ftp_folder;?></strong>
					<?php	
						// NAVIGATEUR FTP
						foreach($list as $obj)
						{	
							echo '<br/><a href="?dir='.$ftp_folder.'/'.$obj.'" title="Ouvrir ">'.$obj.'</a>';
						}
					?>
				</div>
				<div class="ftp-content">
					<div class="" style="float:right;">
						<button type="button" id="btn-add-folder">Nouveau dossier</button>	
						<button type="button" id="btn-add-file">Ajouter des fichiers</button>
						
						<form method="post" id="form-add-file" style="display:none;" enctype="multipart/form-data">
							<input type="file"  multiple="multiple" name="files[]"/>
							<button type="submit">Envoyer</button>
						</form>
											
						<form method="post" id="form-add-folder" style="display:none;">
							<input type="text" name="dirname"/>
							<button type="submit">Envoyer</button>
						</form>
					</div>
					
					<?php /* lise des dossiers et fichiers du dossier ciblé */ ?>
					<?php if(is_dir($ftp_url.$ftp_folder)) : ?>
				
						<table>
						
							<tr>
								<th>Nom</th>
								<th>Taille</th>
								<th>Droits</th>
								<th>Dernière modification</th>
							</tr>
							<?php foreach($data['folders'] as $key => $obj) : ?>
							<tr>
								<td>
									<i class="fa fa-folder"></i> 
									<a href="?dir=<?php echo $ftp_folder.'/'.$obj['name']; ?>" title="Ouvrir "><?php echo $obj['name']; ?></a>
								</td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
							<?php endforeach; ?>
							<?php foreach($data['files'] as $obj) : ?>
							<tr>
								<td>
									<i class="fa fa-file"></i> 
									<a href="?dir=<?php echo $ftp_folder.'/'.$obj['name']; ?>" title="Ouvrir "><?php echo $obj['name']; ?></a>
								</td>
								<td><a href="?dir=<?php echo $ftp_folder.'/'.$obj['name']; ?>" title="Ouvrir "><?php echo $obj['size']; ?></a></td>
								<td><a href="?dir=<?php echo $ftp_folder.'/'.$obj['name']; ?>" title="Ouvrir "><?php echo $obj['chmod']; ?></a></td>
								<td><a href="?dir=<?php echo $ftp_folder.'/'.$obj['name']; ?>" title="Ouvrir "><?php echo $obj['updated_at']; ?></a></td>
							</tr>
							<?php endforeach; ?>
						</table>
						
					<?php else : ?>
						Contenu du fichier : <?= $ftp_folder; ?>
						<textarea name="file_content" class="file-content"><?= htmlentities(file_get_contents($ftp_url.$_GET['dir'])) ;?></textarea>
					<?php endif; ?>
				</div>
			</div>
		</main>
		<footer>
			
		</footer>
		
		
		<script src="https://reseau-net.fr/public/js/show-hide.js"></script>
		<script type="text/javascript">
			ShowHide('#btn-add-file','#form-add-file');
			ShowHide('#btn-add-folder','#form-add-folder');
		</script>
	</body>
</html>
