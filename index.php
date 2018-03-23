<?php
	session_start(); // début de session
	include('Includes/fonction_BDD.php'); // inclusion des fonctions externes
	connectBD(); // connexion à la base de données
	mysqli_set_charset($connexion,'utf8'); // pour mettre les caractères de la base de donnée en utf8
?>
<!DOCTYPE html>
<html>
<head>
	<title>Score Gneu Gneu</title>
	<link href="Includes/styles.css" rel="stylesheet" type="text/css"/>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
	<?php include('Includes/entete.php'); // inclusion entête ?>	
	<?php include('Includes/statistiques.php'); // inclusion statistiques ?>
	<section id="contenu">
		<?php 
			$nomPage = 'accueil.php'; // page par défaut
			if(isset($_GET['page'])) {
				if(file_exists('Includes/' . addslashes($_GET['page']) . ".php") && addslashes($_GET['page']) != 'index.php')// si on clique sur une autre page que php
					{$nomPage = addslashes($_GET['page']) . ".php";} // concaténation du nom de la page
				}
			include('Includes/'. $nomPage); // on ouvre cette page
		?>
		<br/><br/>
	</section>
	<?php include('Includes/pieddepage.php'); // inclusion du pied de page ?>
</body>
<?php
	deconnectBD(); // déconnexion à la base de données
?>
</html>