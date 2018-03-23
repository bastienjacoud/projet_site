<aside id="ajout_joueur">
	<h1 id="titre1_ajout_joueur">Ajouter une joueuse</h1>

	<?php // marche !
	if (isset($_POST['boutonSubmit'])) // si le bouton pour ajouter le joueur a été soumi 
	{
		$pseudo=mysqli_real_escape_string ($connexion, $_POST['pseudo']);
		$nom=mysqli_real_escape_string ($connexion, $_POST['nom']);
		$prenom=mysqli_real_escape_string($connexion, $_POST['prenom']);
		$date_naissance=mysqli_real_escape_string($connexion, $_POST['date_naissance']);
		$rue=mysqli_real_escape_string($connexion, $_POST['rue']);
		$code_postal=mysqli_real_escape_string($connexion, $_POST['code_postal']);
		$ville=mysqli_real_escape_string($connexion, $_POST['ville']);
		$region=mysqli_real_escape_string($connexion, $_POST['region']);
		$Lieu=mysqli_real_escape_string($connexion,$_POST['Lieu']);
		// On récupère les données qui ont été saisies ou sélectionnées
		$req5="select pseudo from joueuses where pseudo like ?";
		$stmt1=mysqli_prepare($connexion,$req5);
		mysqli_stmt_bind_param($stmt1,"s",$pseudo);
		mysqli_stmt_execute($stmt1);
		mysqli_stmt_bind_result($stmt1,$pseudo_existant);
		mysqli_stmt_fetch($stmt1);
		// On regarde s'il existe un pseudo identiques à celui que l'on veut rentrer qui serait déjà dans la base de donnée 
		if($pseudo_existant != NULL) // Vérification de l'unicité du pseudo
		{
			echo '<p id="res_ajout_joueur1"> Le pseudo existe déjà! </p>';
		}
		mysqli_stmt_close($stmt1);
		if($pseudo==NULL) // Vérification que le pseudo que l'on souhaite rentrer n'est pas nul
		{
			echo'<p id="res_ajout_joueur1"> Choisissez un pseudo valide! </p>';
		}
		else
		{ // Si le pseudo est valide (non nul)
			$req4="select idL from lieu where nomL=?";
			// Récupération de l'idL correspondant au lieu sélectionné
			$stmt=mysqli_prepare($connexion,$req4);
			mysqli_stmt_bind_param($stmt,"s",$Lieu);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt,$idL);
			mysqli_stmt_fetch($stmt);
			$req2="INSERT INTO adresse_postale VALUES ('NULL', '$rue', '$code_postal', '$ville', '$region', '$idL')";
			// Insertion des valeurs dans la table 'adresse_postale'
			mysqli_stmt_close($stmt); // fermeture de la première requête préparée
			$res2=mysqli_query($connexion,$req2);
			$idA=mysqli_insert_id($connexion); // récupération de l'idA inséré en auto-incrément
			$req1="INSERT INTO joueuses VALUES ('$pseudo', '$nom', '$prenom', '$date_naissance', '$idA')";
			// Insertion des valeurs dans la table 'joueuses'
			$res1=mysqli_query($connexion,$req1);
			if(($res1==TRUE)&&($res2==TRUE)) // Vérification de l'insertion des tuples dans les 2 tables
			{
				echo'<p id="res_ajout_joueur2"> Insertion réussie ! Vous allez être redirigé vers accueil. </p>';
				echo header("refresh:3;url=index.php");//envoie sur la page d'acceuil au bout de 3s.
			}
			else
			{
				echo'<p id="res_ajout_joueur2"> Erreur Insertion ! </p>';
			}
		}	
	}
	else
	{ // formulaire d'ajout joueuse
	?>
	<form method="post" action="#">

		<h2 id="titre2_ajout_joueur"> Lieu dans lequel la joueuses habite</h2>

		<table id="formulaire1_ajout_joueur">
			<tr>
				<td>
					<label for='Lieu1'>Lieux existants :</label>
				</td>

				<td>
					<input type='radio' name='lieu' value='existant' id='Lieu1' />
					<label for='Lieu2'>Nouveau Lieu :</label>
					<input type='radio' name='lieu' value='nouveau' id='Lieu2' checked="checked" />
					<input type='submit' value='OK' />
				</td>
			</tr>
		</table>
			<br><br>
			<?php 
			
			if(isset($_POST['lieu'])) // Si le bouton radio pour choisir si on sélectionne un lieu ou si on en rentre un nouveau a été soumis, on récupère sa valeur 
			{
				$valeur_bouton = $_POST['lieu'];
			}
			else // sinon on lui donne une valeur par défaut
			{
				$valeur_bouton ='existant';
			}
			if($valeur_bouton == 'existant') // si l'on souhaite séléctionner un lieu, on affiche un menu déroulant des lieux triés par ordre alphabétique
			{?>
				<table id="formulaire5_ajout_joueur">
				<tr>
					<td>
						<label for="idL"> Lieu: </label>
					</td>
					<td>
						<select name="Lieu" id="idL" >
						<option value="" selected="selected"></option>
						<?php
						$req2="select distinct nomL from lieu order by nomL asc";
						$res2=mysqli_query($connexion,$req2);
						while($row2=mysqli_fetch_assoc($res2))
						{ ?>
							<option value="<?php echo $row2['nomL']; ?>" > <?php echo $row2['nomL']; ?> </option>
						<?php 
						}
						?>
						</select>
					</td>
				</tr>
				</table>
			<?php
			}
			if($valeur_bouton == 'nouveau') // si l'on souhaite rentrer un nouveau lieu, on affiche un formulaire permettant de rentrer un nouveau lieu
			{
				if(isset($_POST['bouton'])) // si le bouton pour rentrer un nouveau lieu a été soumis
				{
					$nomL=mysqli_real_escape_string($connexion,$_POST['nomL']);
					$lattitude=mysqli_real_escape_string($connexion,$_POST['lattitude']);
					$longitude=mysqli_real_escape_string($connexion,$_POST['longitude']);
					// on récupère les données saisies
					$req3="insert into lieu values('NULL','$nomL','$lattitude','$longitude')";
					// on les insère dans la table 'lieu'
					$res3=mysqli_query($connexion,$req3);

					$req6="select nomL from lieu where nomL like ?";
					$stmt2=mysqli_prepare($connexion,$req6);
					mysqli_stmt_bind_param($stmt2,"s",$nomL);
					mysqli_stmt_execute($stmt2);
					mysqli_stmt_bind_result($stmt2,$nomL_existant);
					mysqli_stmt_fetch($stmt2);
					if($nomL_existant != NULL) // vérification unicité du lieu 
					{
						echo '<p>Le lieu existe déjà ! <p>';
					}
					else
					{
						mysqli_stmt_close($stmt2);
						if($nomL == NULL) // vérification que le nom du lieu ne soit pas nul
						{
							echo '<p>Choisissez un nom de lieu valide ! <p>';
						}
						else
						{
							if($res3==TRUE) // vérification de l'insertion
							{
								echo '<p> Insertion Réussie ! <p>';
							}
							else
							{
								echo '<p> Erreur Insertion ! <p>';
							}
						}
					}
				} 
				else // formulaire d'ajout lieu
				{ ?>
					<form method="post" action="#">
					<table id="formulaire4_ajout_joueur">
						<tr>
							<td>
								<label for="nom_lieu"> Nom du lieu : </label>
							</td>
							<td>						
								<input type="text" name="nomL" id="nom_lieu" />
							</td>
						</tr>
						<tr>
							<td>
								<label for="Lattitude"> Lattitude : </label>
							</td>
							<td>
								<input type="text" name="lattitude" id="Lattitude" />
							</td>
						</tr>
						<tr>
							<td>
								<label for="Longitude"> Longitude : </label>
						
							</td>
							<td>
								<input type="text" name="longitude" id="Longitude" />
							</td>
						</tr>
						<tr>
							<td></td>
							<td>
								<input type="submit" name="bouton" value="Ajouter un nouveau lieu !" />
							</td>
						</tr>
					</table>
					</form>	
				<?php } ?>
			<?php } ?>
			<br><br>
		
		<!-- Suite du formulaire d'ajout joueuse -->
		<h2 id="titre3_ajout_joueur"> Informations générales </h2>

		<table id="formulaire2_ajout_joueur">
			<tr>
				<td>
					<label for="idPseudo"> Pseudo : </label>
				</td>
		
				<td>
					<input type="text" name="pseudo" id="idPseudo"  /><br>
				</td>
			</tr>
		
			<tr>
				<td>
					<label for="idNom"> Nom : </label>
				</td>
		
				<td>
					<input type="text" name="nom" id="idNom"  /><br>
				</td>
			</tr>
		
			<tr>
				<td>
					<label for="idPrenom"> Prénom : </label>
				</td>
		
				<td>
					<input type="text" name="prenom" id="idPrenom"  /><br>
				</td>
			</tr>

			<tr>
				<td>
					<label for="idDate"> Date de naissance : </label>
				</td>
		
				<td>
					<input type="date" name="date_naissance" id="idDate"  /><br>
				</td>
			</tr>
		</table>

		<h2 id="titre4_ajout_joueur"> Adresse </h2>

		<table id="formulaire3_ajout_joueur">
			<tr>
				<td>
					<label for="idRue"> Rue : </label>
				</td>
		
				<td>
					<input type="text" name="rue" id="idRue"  /><br>
				</td>
			</tr>
		
			<tr>
				<td>
					<label for="idCode"> Code postal : </label>
				</td>
		
				<td>
					<input type="int" name="code_postal" id="idCode"  /><br>
				</td>
			</tr>
		
			<tr>
				<td>
					<label for="idVille"> Ville : </label>
				</td>
		
				<td>
					<input type="text" name="ville" id="idVille"  /><br>
				</td>
			</tr>
		
			<tr>
				<td>
					<label for="idRegion"> Region : </label>
				</td>
		
				<td>
					<input type="text" name="region" id="idRegion"  /><br>
				</td>
			</tr>
		
			<tr>
				<td></td>
			
				<td>
					<input type="submit" name="boutonSubmit" value="Ajouter le joueur!"/>
				</td>
			</tr>
		</table>
	</form>
	<?php } ?>
</aside>