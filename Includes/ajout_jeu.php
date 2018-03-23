<aside id="ajout_jeu">

	<h1 id="titre_ajout_jeu"> Ajouter un jeu! </h1>

	<?php // marche !

	if(isset($_POST['boutonSubmit'])) // si le bouton pour ajouter le jeu a été soumis
	{
		$nomJeu=mysqli_real_escape_string($connexion, $_POST['nomJ']);
		$DateSortie=mysqli_real_escape_string($connexion, $_POST['dateSortie']);
		$NbJoueursMin=mysqli_real_escape_string($connexion, $_POST['nbJoueursMin']);
		$NbJoueursMax=mysqli_real_escape_string($connexion, $_POST['nbJoueursMax']);
		$Categorie=mysqli_real_escape_string($connexion, $_POST['categorie']);
		$public=mysqli_real_escape_string($connexion, $_POST['public']);
		$NomEditeur=mysqli_real_escape_string($connexion, $_POST['nomEdit']);
		$NomAuteur=mysqli_real_escape_string($connexion, $_POST['nomA']);
		// on récupère les valeurs des variables saisies par l'utilisateur
		$req5="select nomJ from jeu where nomJ like ?";
		$stmt1=mysqli_prepare($connexion,$req5);
		mysqli_stmt_bind_param($stmt1,"s",$nomJeu);
		mysqli_stmt_execute($stmt1);
		mysqli_stmt_bind_result($stmt1,$nomJeu_existant);
		mysqli_stmt_fetch($stmt1);
		if($nomJeu_existant != NULL) // vérification unicité du jeu
		{
			echo '<p id="res_ajout_jeu1"> Le nom du jeu existe déjà! </p>';
		}
		mysqli_stmt_close($stmt1);
		if($nomJeu==NULL) // vérification de l'existence du jeu (non nul)
		{
			echo '<p id="res_ajout_jeu2"> Choisissez un nom de jeu valide <p>';
		}
		else
		{
			$req6="select nomEdit from editeur where nomEdit like ?";
			$stmt2=mysqli_prepare($connexion,$req6);
			mysqli_stmt_bind_param($stmt2,"s",$NomEditeur);
			mysqli_stmt_execute($stmt2);
			mysqli_stmt_bind_result($stmt2,$nom_editeur_existant);
			mysqli_stmt_fetch($stmt2);
			if($nom_editeur_existant == NULL) // vérification unicité du nom de l'éditeur
			{ // on ne vérifie pas si le nom de l'éditeur est non nul car le champ est obligatoire à remplir
				$req2="Insert into editeur values('$NomEditeur')";
				// Insertion du tuple dans la table 'editeur'
				$res2=mysqli_query($connexion,$req2);
				if($res2==TRUE) // vérification de l'insertion
					{
						echo '<p id="res_ajout_jeu3"> Insertion editeur réussie ! </p>';
						?><br><br><?php
					}
					else
					{
						echo '<p id="res_ajout_jeu3" Erreur insertion editeur !</p>';
					}
			}
			mysqli_stmt_close($stmt2);
			if($NomEditeur == NULL) // si le nom de l'éditeur existe (n'est pas nul)
			{ ?>
				<p id="res_ajout_jeu4"> Choisissez un nom d'éditeur valide <p>;
			  <?php 
			}
			else
			{
				$req1="Insert into jeu VALUES ('NULL','$nomJeu','$DateSortie','$NbJoueursMin','$NbJoueursMax','$Categorie','$public','$NomEditeur')";
				// insertion des tuples dans la table 'jeu'
				$res1=mysqli_query($connexion, $req1);
				$IdJ=mysqli_insert_id($connexion);
				$req7="select nomA from auteur where nomA like ?";
				$stmt3=mysqli_prepare($connexion,$req7);
				mysqli_stmt_bind_param($stmt3,"s",$NomAuteur);
				mysqli_stmt_execute($stmt3);
				mysqli_stmt_bind_result($stmt3,$nom_auteur_existant);
				mysqli_stmt_fetch($stmt3);
				if($nom_auteur_existant == NULL) // vérification unicité du nom de l'auteur
				{ // on ne vérifie pas que le nom de l'auteur ne soit pas nul car le champ est obligatoire à remplir
					$req3="Insert into auteur values('$NomAuteur')";
					// insertion du tuple dans la table 'auteur'
					$res3=mysqli_query($connexion,$req3);
					if($res3==TRUE) // vérification de l'insertion
					{
						echo '<p id="res_ajout_jeu5"> Insertion auteur réussie ! </p>';
						?><br><br><?php
					}
					else
					{
						echo '<p id="res_ajout_jeu5"> Erreur insertion auteur ! </p>';
					}
				}
				mysqli_stmt_close($stmt3);
				if($NomAuteur == NULL) // vérification de l'existence du nom de l'auteur
				{ ?>
					<p> Choisissez un nom d'auteur valide <p>;
				  <?php 
				}
				else
				{
					$req4="Insert into cree values('$NomAuteur','$IdJ')";
					// insertion dans la table 'cree'
					$res4=mysqli_query($connexion,$req4);
					if(($res1==TRUE) && ($res4==TRUE)) // vérification des insertions dans la table 'cree' et 'jeu'
					{
						echo'<p id="res_ajout_jeu"> Insertion réussie ! Vous allez être redirigé vers accueil </p>';
						echo header("refresh:3;url=index.php");//envoie sur la page d'acceuil au bout de 3s.
					}
					else
					{
						echo'<p id="res_ajout_jeu"> Erreur Insertion ! </p>';
					}
				}
				
			}
			
		}
		
	}

	else // formulaire d'ajout de jeu
	{?>

		<form method="post" action="#">
		<table id="formulaire_ajout_jeu">
			<tr>
				<td>
					<label for="idNom"> Nom du jeu : </label>
				</td>
				<td>
					<input type="text" name="nomJ" id="idNom" required />	<br>
				</td>
			</tr>
			<tr>
				<td>
					<label for="idDate"> Date de sortie du jeu : </label>
				</td>
				<td>
					<input type="date" name="dateSortie" id="idDate" required /><br>
				</td>
			</tr>
			<tr>
				<td>
					<label for="idMin"> Nombre de joueurs minimum pour jouer : </label>
				</td>
				<td>
					<input type="int"name="nbJoueursMin" id="idMin" required /> <br>
				</td>
			</tr>
			<tr>
				<td>
					<label for="idMax"> Nombre de Joueur maximum pour jouer : </label>
				</td>
				<td>
					<input type="int" name="nbJoueursMax" id="idMax" required /> <br>
				</td>
			</tr>
			<tr>
				<td>
					<label for="Idpublic">Public pour lequel le jeu est destiné :</label>
				</td>
				<td>
					<input type="text" name="public" id="Idpublic" required /> <br>
				</td>
			</tr>
			<tr>
				<td>
					<label for="idCat"> Catégorie du jeu : </label>
				</td>
				<td>
					<input type="text" name="categorie" id="idCat" required /> <br>
				</td>
			</tr>
			<tr>
				<td>
					<label for="idEdit"> Editeur du jeu : </label>
				</td>
				<td>
					<input type="text" name="nomEdit" id="idEdit" required /> <br>
				</td>
			</tr>
			<tr>
				<td>
					<label for="idAuteur"> Auteur du jeu : </label>
				</td>
				<td>
					<input type="text" name="nomA" id="idAuteur" required /> <br>
				</td>
			</tr>
			<tr>
				<td></td>
				<td>
					<input type="submit" name="boutonSubmit" value="Ajouter le jeu!"/>
				</td>
			</tr>
		</table>

		</form>
	<?php }  
	?>
</aside>