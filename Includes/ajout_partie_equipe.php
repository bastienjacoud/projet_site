<aside id="ajout_partie_solo">

	<h1 id="titre_ajout_score_equipe">Ajouter le score d'une partie en équipe</h1>
	<?php // marche !
	if(isset($_POST['boutonSubmit'])) // si le bouton pour ajouter le score a été soumis
	{
		$nomE=mysqli_real_escape_string($connexion, $_POST['nomE']);
		$Date=mysqli_real_escape_string($connexion,$_POST['DatePartie']);
		$Durée=mysqli_real_escape_string($connexion,$_POST['DuréePartie']);
		$Score=mysqli_real_escape_string($connexion,$_POST['ScorePartie']);
		$Gagnant=mysqli_real_escape_string($connexion,$_POST['gagner']);
		$Jeu=mysqli_real_escape_string($connexion,$_POST['Jeu']);
		$Lieu=mysqli_real_escape_string($connexion,$_POST['Lieu']);
		// on récupère les valeurs des variables saisies
		$req4="select idL from lieu where nomL=?";
		// récupération de l'idL correspondant au lieu sélectionné
		$stmt1=mysqli_prepare($connexion,$req4);
		mysqli_stmt_bind_param($stmt1,"s",$Lieu);
		mysqli_stmt_execute($stmt1);
		mysqli_stmt_bind_result($stmt1,$idL);
		mysqli_stmt_fetch($stmt1);
		$req5="insert into partie values('NULL','$Score','$Durée','$Gagnant','$Date','$idL')";
		// insertion des tuples dans la table 'partie'
		mysqli_stmt_close($stmt1);
		$res5=mysqli_query($connexion,$req5);
		$idP=mysqli_insert_id($connexion);
		// récupération de la valeur de l'idP inséré
		$req6="insert into joue_equipe values('$idP','$nomE')";
		// insertion des tuples dans la table 'joue_equipe'
		$res6=mysqli_query($connexion,$req6);
		$req7="select idJ from jeu where nomJ=?";
		// récupération de l'idJ correspondant au jeu sélectionné
		$stmt2=mysqli_prepare($connexion,$req7);
		mysqli_stmt_bind_param($stmt2,"s",$Jeu);
		mysqli_stmt_execute($stmt2);
		mysqli_stmt_bind_result($stmt2,$idJ);
		mysqli_stmt_fetch($stmt2);
		$req8="insert into jouer values('$idP','$idJ')";
		// insertion dans la table 'jouer'
		mysqli_stmt_close($stmt2);
		$res8=mysqli_query($connexion,$req8);
		if(($res5==TRUE) && ($res6==TRUE) && ($res8==TRUE)) // vérification de l'insertion dans les 3 tables
		{
			echo '<p id="res_ajout_partie_equipe"> Insertion Réussie, vous allez être redirigé sur accueil. <p>';
			echo header("refresh:3;url=index.php");//envoie sur la page d'acceuil au bout de 3s.
		}
		else
		{
			echo '<p id="res_ajout_partie_equipe"> Erreur Insertion <p>';
		}
	}
	else
	{ // forulaire pour ajouter un score en solo
	?>
		<form method="post" action="#">
	
			<p id="titre2_ajout_score_equipe"> Lieu dans lequel s'est déroulé la partie <p>
			<table id="formulaire1_ajout_score_equipe">
				<tr>
					<td>
						<label for='Lieu1'>Lieux existants :</label>
					</td>
					<td>
						<input type='radio' name='lieu' value='existant' id='Lieu1' />
						<label for='Lieu2'>Nouveau Lieu :</label>
						<input type='radio' name='lieu' value='nouveau' id='Lieu2' checked="checked" />
						<input type='submit' value='OK' />
						<br>
					</td>
				</tr>
			</table>
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
			<table id="formulaire2_ajout_score_equipe">
				<tr>
					<td>
						<label for="idL"> Lieu: </label>
					</td>
					<td>
						<select id="idL" name="Lieu">
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
					<table id="formulaire3_ajout_score_equipe">
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

			<!-- Suite du formulaire d'ajout score en solo -->
			<table id="formulaire4_ajout_score_equipe">
				<tr>
					<td>
						<label for="idEquipe"> Nom de l'équipe: </label>
					</td>
					<td>
						<select name="nomE" id="idEquipe">
							<option value="" selected="selected"></option>
						<?php 
						$req1="SELECT distinct nomE FROM equipe order by nomE asc";
						$res1=mysqli_query($connexion, $req1);
						while($row1=mysqli_fetch_assoc($res1))
							{ ?>
								<option value="<?php echo $row1['nomE']; ?>" > <?php echo $row1['nomE']; ?> </option>
							<?php } ?>
						</select>
						<br>
					</td>
				</tr>
				<tr>
					<td>
						<label for="Date">Date de la partie : </label>
					</td>
					<td>
						<input type="date" name="DatePartie" id="Date" />
						<br>
					</td>
				</tr>
				<tr>
					<td>	
						<label for="Durée">Durée de la partie (en minutes) :</label>
					</td>
					<td>
						<input type="time" name="DuréePartie" id="Durée" />
						<br>
					</td>
				</tr>
				<tr>
					<td>
						<label for="idJeu"> Jeu auquel l'équipe à joué : </label>
					</td>
					<td>
						<select name="Jeu" id="idJeu">
							<option value="" selected="selected"></option>
						<?php 
						$req1="SELECT distinct nomJ FROM jeu order by nomJ asc";
						$res1=mysqli_query($connexion, $req1);
						while($row1=mysqli_fetch_assoc($res1))
							{ ?>
								<option value="<?php echo $row1['nomJ']; ?>" > <?php echo $row1['nomJ']; ?> </option>
							<?php } ?>
						</select>
						<br>
					</td>
				</tr>
				<tr>
					<td>
						<label for="Score">Score de l'équipe :</label>
					</td>
					<td>
						<input type="int" name="ScorePartie" id="Score" />
						<br>
					</td>
				</tr>
			</table>
						<p id="titre3_ajout_score_equipe"> L'équipe a-t-elle gagnée ? <p>
			<table id="formulaire5_ajout_score_equipe">
				<tr>
					<td>
						<input type="radio" name="gagner" id="Gagnant_oui" value="1" checked />
						<label for="Gagnant_oui">Oui </label>  
						<input type="radio" name="gagner" id="Gagnant_non" value="0" />
						<label for="Gagnant_non">Non </label>
					</td>
				</tr>
				<tr>
					<td></td>
					<td>	
						<input type="submit" name="boutonSubmit" value="Ajouter le score !"/>
					</td>
				</tr>
			</table>
		</form>
	<?php } ?>
</aside>