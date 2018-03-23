<aside id="ajout_joueuse_equipe">

<h1 id="titre_ajout_joueuse_equipe"> Ajouter une joueuse à une équipe! </h1>
<?php // marche !
	if (isset($_POST['boutonSubmit'])) // si le bouton pour ajouter une joueuse à une équipe a été soumis
	{
		$nomE=mysqli_real_escape_string($connexion,$_POST['nom_equipe']);
		$pseudo=mysqli_real_escape_string($connexion,$_POST['Pseudo']);
		$dateArrivee=mysqli_real_escape_string($connexion,$_POST['dateA']);
		// récupération des valeurs des variables
		$req="Insert into comporte values('$dateArrivee','$nomE','$pseudo')";
		// insertion dans la table 'comporte'
		$res=mysqli_query($connexion,$req);
		if($res==TRUE) // vérification de l'insertion
		{
			echo'<p id="res_ajout_joueur_equipe"> Insertion Réussie ! Vous allez être redirigé sur accueil.<p>';
			echo header("refresh:3;url=index.php");//envoie sur la page d'acceuil au bout de 3s.
		}
		else
		{
			echo'<p id="res_ajout_joueur_equipe"> Erreur Insertion !<p>';
		}

	}
	else
	{ // formulaire d'ajout d'une joueuse à une équipe
	?>
	<form method="post" action="#">
		<table id="formulaire_ajout_joueuse_equipe">

			<tr>
				<td>
					<label for="idEquipe"> Nom de l'équipe: </label>
				</td>
				<td>
					<select required name="nom_equipe" id="idEquipe">
					<option value="" selected="selected"></option>
					<?php 
						$req="SELECT distinct nomE FROM equipe order by nomE asc";
						$res=mysqli_query($connexion, $req);
						while($row=mysqli_fetch_assoc($res))
						{ ?>
							<option value="<?php echo $row['nomE']; ?>" > <?php echo $row['nomE']; ?> </option>
					<?php } ?>
					</select><br>
				</td>
			</tr>

			<tr>
				<td>
					<label for="idJoueuse"> Nom de la joueuse: </label>
				</td>

				<td>
					<select required name="Pseudo" id="idJoueuse">
					<option value="" selected="selected"></option>
						<?php 
							$req="SELECT distinct pseudo FROM joueuses order by pseudo asc";
							$res=mysqli_query($connexion, $req);
							while($row=mysqli_fetch_assoc($res))
						{ ?>
							<option value="<?php echo $row['pseudo']; ?>" > <?php echo $row["pseudo"]; ?> </option>
						<?php } ?>
					</select><br>
				</td>
			</tr>

			<tr>
				<td>
					<label for="dateArrivee">Date d'arrivée de la joueuse dans l'équipe :</label>
				</td>
	
				<td>
					<input required type="date" name="dateA" id="dateArrivee"/><br>
				</td>
			</tr>
	
			<tr>
				<td>
				</td>

				<td>
					<input type="submit" name="boutonSubmit" value="Ajouter la joueuse à l'équipe!"/>
				</td>
			</tr>
		</table>
	</form>
	<?php } ?>
</aside>