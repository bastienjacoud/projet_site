<aside id="ajout_equipe">

	<h1 id="titre_ajout_equipe"> Ajouter une équipe!</h1>

	<?php //marche !

	if (isset($_POST['boutonSubmit'])) // si le bouton pour ajouter une équipe a été soumis
	{
		$nom=mysqli_real_escape_string ($connexion, $_POST['nom']);
		$devise=mysqli_real_escape_string ($connexion, $_POST['devise']);
		$date=mysqli_real_escape_string($connexion, $_POST['date_creation']);
		// on récupère les valeurs des variables saisies
		$req1="select nomE from equipe where nomE =?";
		$stmt=mysqli_prepare($connexion,$req1);
		mysqli_stmt_bind_param($stmt,"s",$nom);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt,$nom_existant);
		mysqli_stmt_fetch($stmt);
		if($nom_existant != NULL) // vérification unicité du nom de l'équipe
		{
			echo '<p> Le nom d équipe existe déjà! </p>';
		}
		mysqli_stmt_close($stmt);
		if($nom==NULL) // vérification de l'existence du nom d'équipe (non nul)
		{
			echo '<p> Choisissez un nom d équipe valide <p>';
		}
		else
		{
			$req="INSERT INTO equipe VALUES ('$nom','$date','$devise')";
			// insertion des tuples dans la table 'equipe'
			$res=mysqli_query ($connexion, $req);
			if ($res==TRUE) // vérification de l'insertion des tuples
			{
				echo '<p id="res_ajout_equipe"> Insertion reussie! Vous allez être redirigé sur la page accueil.</p>';
				echo header("refresh:3;url=index.php");
			}
			else 
			{
				echo '<p id="res_ajout_equipe"> Erreur insertion! </p>';
			}
		}
	}
	else
	{ // formulaire d'ajout de l'équipe
	?>

	<form method="post" action="#">
	<table id="formulaire_ajout_equipe">
		<tr>
			<td>
				<label for="idNom"> Nom de l'équipe : </label>
			</td>
			<td>
				<input type="text" name="nom" id="idNom" required /><br>
			</td>
		</tr>
		<tr>
			<td>
				<label for="idDevise"> Devise de l'équipe : </label>
			</td>
			<td>
				<input type="text" name="devise" id="idDevise" required /> <br>
			</td>
		</tr>
		<tr>
			<td>
				<label for="idDate"> Date de creation de l'équipe : </label>
			</td>
			<td>
				<input type="date" name="date_creation" id="idDate" required /><br>
			</td>
		</tr>
		<tr>
			<td></td>
			<td>
				<input type="submit" name="boutonSubmit" value="Ajouter l'équipe!" />
			</td>
		</tr>
	</table>
	</form>
	<?php }?>

</aside>