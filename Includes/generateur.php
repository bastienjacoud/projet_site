<aside id="generateur">
	<h1 id="titre_generateur"> Generer des parties! </h1>


	<?php // marche !
	if (isset($_POST['boutonSubmit'])) // si le bouton pour générer des parties a été soumis
	{
		$nb=mysqli_real_escape_string ($connexion, $_POST['nombre_parties']);
		$score_min=mysqli_real_escape_string ($connexion, $_POST['score_mini']);
		$score_maxi=mysqli_real_escape_string ($connexion, $_POST['score_max']);
		$duree_min=mysqli_real_escape_string ($connexion, $_POST['duree_mini']);
		$duree_maxi=mysqli_real_escape_string ($connexion, $_POST['duree_max']);
		$date_min=mysqli_real_escape_string ($connexion, $_POST['date_mini']);
		$date_maxi=mysqli_real_escape_string ($connexion, $_POST['date_max']);
		$lieu=mysqli_real_escape_string ($connexion, $_POST['lieu']);
		$jeu=mysqli_real_escape_string ($connexion, $_POST['jeu']);
		$resultats=mysqli_real_escape_string($connexion, $_POST['resultat']);
		$joueuse=mysqli_real_escape_string($connexion,$_POST['Pseudo']);
		// on récupère les valeurs des variables
		
		$test=1; // valeur par défaut de la vérification d'insertion des tuples

		if(($score_min<0) || ($score_maxi<0) || ($duree_min<0) || ($duree_maxi<0))
		{ // vérification que le score maximum, le score minimum, la durée minimal et la durée maximale soient positives 
		  // pas de vérification d'existence car les données ci-dessus sont obligatoires à rentrer	
			?>
			<p> Veuillez rentrer des données valides ! <p>
			<?php
		} 
		else
		{
		if ($lieu==NULL)//cas ou on a pas rentre le lieu
			{ //on suppose que les idL se suivent
				$req5="select min(idL) from lieu";
				// sélectionne l'idL le plus petit
				$res5=mysqli_query($connexion,$req5);
				$row5=mysqli_fetch_assoc($res5);
				$nb_min_lieu=$row5['min(idL)'];

				$req6="select max(idL) from lieu";
				// sélectionne l'idL le plus grand
				$res6=mysqli_query($connexion,$req6);
				$row6=mysqli_fetch_assoc($res6);
				$nb_max_lieu=$row6['max(idL)'];

				if($jeu!=NULL)//cas ou on a pas rentre le lieu mais on a rentre le jeu
				{

					for($n=0; $n<$nb; $n+=1) // boucle permettant l'insertion de tous les tuples demandés
					{

						$nb_ins=rand($nb_min_lieu, $nb_max_lieu);
						$score_ins=rand($score_min, $score_maxi);
						$duree_ins=rand($duree_min, $duree_maxi);
						$date_ins=date_aleatoire($date_min, $date_maxi);
						// création des valeurs aléatoires à rentrer

						$req="INSERT INTO partie VALUES ('NULL', '$score_ins', '$duree_ins', '$resultats', '$date_ins', '$nb_ins')";
						// insertion des tuples dans la table 'partie'
						$res=mysqli_query($connexion, $req);
						$idP=mysqli_insert_id($connexion);
						// récupération de l'idP

						$req3="SELECT idJ FROM jeu WHERE nomJ=?";
						// sélection de l'idJ correspondant au jeu sélectionné
						$stmt2=mysqli_prepare($connexion,$req3);
						mysqli_stmt_bind_param($stmt2,"s",$jeu);
						mysqli_stmt_execute($stmt2);
		  				mysqli_stmt_bind_result($stmt2,$idJ);
		  				mysqli_stmt_fetch($stmt2);

						$req2="INSERT INTO jouer VALUES ('$idP', '$idJ')";
						// insertion des tuples dans la table 'jouer'
						mysqli_stmt_close($stmt2);
						$res2=mysqli_query($connexion, $req2);

						$req9="insert into joue_solo values('$idP','$joueuse')";
						// insertion des tuples dans la table 'joue_solo'
						$res9=mysqli_query($connexion,$req9);
					}
				}
				else//cas ou on a rentre ni le lieu ni le jeu
				{ // on suppose que les idJ se suivent
					$req7="select min(idJ) from jeu";
					// sélectionne l'idJ le plus petit
					$res7=mysqli_query($connexion,$req7);
					$row7=mysqli_fetch_assoc($res7);
	  				$idJ_mini_jeu=$row7['min(idJ)'];

					$req8="select max(idJ) from jeu";
					// sélectionne l'idJ le plus grand
					$res8=mysqli_query($connexion,$req8);
					$row8=mysqli_fetch_assoc($res8);
	  				$idJ_maxi_jeu=$row8['max(idJ)'];
	  				
					for($n=0; $n<$nb; $n+=1) // boucle permettant l'insertion de tous les tuples demandés
					{

						$nb_lieu_ins=rand($nb_min_lieu, $nb_max_lieu);
						$idJ_ins=rand($idJ_mini_jeu, $idJ_maxi_jeu);
						$score_ins=rand($score_min, $score_maxi);
						$duree_ins=rand($duree_min, $duree_maxi);
						$date_ins=date_aleatoire($date_min, $date_maxi);
						// création des valeurs aléatoires à rentrer
					
						$req="INSERT INTO partie VALUES ('NULL', '$score_ins', '$duree_ins', '$resultats', '$date_ins', '$nb_lieu_ins')";
						// insertion des tuples dans la table 'partie'
						$res=mysqli_query($connexion, $req);
						$idP=mysqli_insert_id($connexion);
						// récupération de l'idP venant d'être créé

						$req2="INSERT INTO jouer VALUES ('$idP', '$idJ_ins')";
						// insertion des tuples dans la table 'jouer'
						$res2=mysqli_query($connexion, $req2);

						$req9="insert into joue_solo values('$idP','$joueuse')";
						//insertion des tuples dans la table 'joue_solo'
						$res9=mysqli_query($connexion,$req9);
					}
				}
			}
		else//cas ou on a rentre le lieu
			{
				if($jeu==NULL)//cas ou on a rentre le lieu mais pas le jeu
				{//on suppose que les idJ se suivent
					$req7="select min(idJ) from jeu";
					// récupère l'idJ le plus petit
					$res7=mysqli_query($connexion,$req7);
					$row7=mysqli_fetch_assoc($res7);
	  				$idJ_mini_jeu=$row7['min(idJ)'];

					$req8="select max(idJ) from jeu";
					// récupère l'idJ le plus grand
					$res8=mysqli_query($connexion,$req8);
					$row8=mysqli_fetch_assoc($res8);
	  				$idJ_maxi_jeu=$row8['max(idJ)'];

					for($n=0; $n<$nb; $n+=1) // insertion de tous les tuples demandés
					{

						$idJ_ins=rand($idJ_mini_jeu, $idJ_maxi_jeu);
						$score_ins=rand($score_min, $score_maxi);
						$duree_ins=rand($duree_min, $duree_maxi);
						$date_ins=date_aleatoire($date_min, $date_maxi);
						// création des variables aléatoires à rentrer

						$req4="SELECT idL FROM lieu WHERE nomL=?";
						// récupération de l'idL correspondant au lieu sélectionné
						$stmt=mysqli_prepare($connexion,$req4);
						mysqli_stmt_bind_param($stmt,"s",$lieu);
						mysqli_stmt_execute($stmt);
	  					mysqli_stmt_bind_result($stmt,$idL);
	  					mysqli_stmt_fetch($stmt);
				
						$req="INSERT INTO partie VALUES ('NULL', '$score_ins', '$duree_ins', '$resultats', '$date_ins', '$idL')";
						// insertion des tuples dans la table 'partie'
						mysqli_stmt_close($stmt);
						$res=mysqli_query($connexion, $req);
						$idP=mysqli_insert_id($connexion);
						// récupération de l'idP venant d'être rentré

						$req2="INSERT INTO jouer VALUES ('$idP', '$idJ_ins')";
						// insertion des tuples dans la table 'jouer'
						$res2=mysqli_query($connexion, $req2);

						$req9="insert into joue_solo values('$idP','$joueuse')";
						// insertion des tuples dans la table 'joue_solo'
						$res9=mysqli_query($connexion,$req9);
					}
				}
				else//cas ou on a rentre le lieu et le jeu
				{
					for($n=0; $n<$nb; $n+=1) // insertion de tous les tuples demandés
					{
						$score_ins=rand($score_min, $score_maxi);
						$duree_ins=rand($duree_min, $duree_maxi);
						$date_ins=date_aleatoire($date_min, $date_maxi);
						// création des variables aléatoires à rentrer

						$req4="SELECT idL FROM lieu WHERE nomL=?";
						// récupération de l'idL correspondant au lieu sélectionné
						$stmt=mysqli_prepare($connexion,$req4);
						mysqli_stmt_bind_param($stmt,"s",$lieu);
						mysqli_stmt_execute($stmt);
	  					mysqli_stmt_bind_result($stmt,$idL);
	  					mysqli_stmt_fetch($stmt);
				
						$req="INSERT INTO partie VALUES ('NULL', '$score_ins', '$duree_ins', '$resultats', '$date_ins', '$idL')";
						// insertion des tuples dans la table 'partie'
						mysqli_stmt_close($stmt);
						$res=mysqli_query($connexion, $req);
						$idP=mysqli_insert_id($connexion);

						$req3="SELECT idJ FROM jeu WHERE nomJ=?";
						// récupération de l'idJ correspondant au jeu sélectionné
						$stmt2=mysqli_prepare($connexion,$req3);
						mysqli_stmt_bind_param($stmt2,"s",$jeu);
						mysqli_stmt_execute($stmt2);
	  					mysqli_stmt_bind_result($stmt2,$idJ);
	  					mysqli_stmt_fetch($stmt2);

						$req2="INSERT INTO jouer VALUES ('$idP', '$idJ')";
						// insertion des tuples dans la table 'jouer'
						mysqli_stmt_close($stmt2);
						$res2=mysqli_query($connexion, $req2);

						$req9="insert into joue_solo values('$idP','$joueuse')";
						// insertion des tuples dans la table 'joue_solo'
						$res9=mysqli_query($connexion,$req9);
					}
				}

			if(($res==FALSE) || ($res2==FALSE) || ($res9==FALSE)) // vérification des 3 insertions
			{
					$test=0;
			}
			

		}
	
	

		if ($test==1) // message d'erreur ou de réussite d'insertion
			{
			 echo '<p id="res_generateur"> insertion reussie, vous allez être redirigé sur accueil. </p>';
			 echo header("refresh:3;url=index.php");//envoie sur la page d'acceuil au bout de 3s.
			}
		else {echo '<p id="res_generateur"> probleme d insertion </p>';}
		}	
	} else//formulaire de génération de parties 

	{
	?>
		<form method="post" action="#">
		<table id="formulaire_generateur">
		<tr>
			<td>
			<label for="idNbParties"> Nombres de parties à générer : </label>
			</td>
			<td>
			<input type="number" required name="nombre_parties" id="idNbParties" /><br/>
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
							<option value="<?php echo $row['pseudo']; ?>" > <?php echo $row['pseudo']; ?> </option>
						<?php } ?>
					</select><br>
				</td>
			</tr>
		<tr>
			<td>
			<label for="idResultat"> Résultat des parties : </label>
			</td>
			<td>
			<input type="radio" name="resultat" id="idResultat" value="1" checked />Victoire
			<input type="radio" required name="resultat" id="idResultat" value="0" />Défaite<br/>
			</td>
		</tr>
		<tr>
			<td>
			<label for="idScoreMin"> Score minimal : </label>
			</td>
			<td>
			<input type="number" required name="score_mini" id="idScoreMin" />
			</td>
		</tr>
		<tr>
			<td>
			<label for="idScoreMax"> Score maximal : </label>
			</td>
			<td>
			<input type="number" required name="score_max" id="idScoreMax" /><br/>
			</td>
		</tr>
		<tr>
			<td>
			<label for="idDureeMin"> Duree minimale : </label>
			</td>
			<td>
			<input type="number" required name="duree_mini" id="idDureeMin" />
			</td>
		</tr>
		<tr>
			<td>
			<label for="idDureeMax"> Duree maximale : </label>
			</td>
			<td>
			<input type="number" required name="duree_max" id="idDureeMax" /><br/>
			</td>
		</tr>
		<tr>
			<td>
			<label for="idDateMin"> Date minimale : </label>
			</td>
			<td>
			<input type="date" required name="date_mini" id="idDateMin" />
			</td>
		</tr>
		<tr>
			<td>
			<label for="idDateMax"> Date maximale : </label>
			</td>
			<td>
			<input type="date" required name="date_max" id="idDateMax" /><br/>
			</td>
		</tr>
		<tr>
			<td>
			<label for="idLieu"> Lieu : </label>
			</td>
			<td>
			<select name="lieu" id="idLieu">
				<?php 
				$req="SELECT nomL FROM lieu order by nomL asc";
				$res=mysqli_query($connexion, $req);
				?><option value="" selected="selected"></option>
				<?php while($row=mysqli_fetch_assoc($res))
				{ ?>
				<option value="<?php echo $row['nomL']; ?>" > <?php echo $row['nomL']; ?> </option>
				<?php } ?>
			</select><br>
			</td>
		</tr>
		<tr>
			<td>
			<label for="idJeu"> Jeu : </label>
			</td>
			<td>
			<select name="jeu" id="idJieu">
				<?php 
				$req="SELECT distinct nomJ FROM jeu order by nomJ asc";
				$res=mysqli_query($connexion, $req);
				?><option value="" selected="selected"></option>
				<?php while($row=mysqli_fetch_assoc($res))
				{ ?>
				<option value="<?php echo $row['nomJ']; ?>" > <?php echo $row['nomJ']; ?> </option>
				<?php } ?>
			</select><br>
			</td>
		</tr>
		<tr>
		<td></td>
		<td>
			<input type="submit" name="boutonSubmit" value="Générer!"/>
		</td>
		
		</tr>
		</table>
		</form>


	<?php } ?>

</aside>