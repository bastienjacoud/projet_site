<aside id="info_joueuse">
	<h1 id="titre_info_joueur"> Information sur les joueuses et suggestions</h1>
	<?php
	if(isset($_POST['boutonSubmit'])) // si le bouton pour afficher les informations sur la joueuse a été soumis
	{ 
		$pseudo=mysqli_real_escape_string($connexion,$_POST['Pseudo']);
		// on récupère le pseudo sélectionné
		$req1="select nomJ,prenomJ,dateNaissance,idA from joueuses where pseudo like ?";
		// on récupère les informations sur la joueuse ayant ce pseudo
		$stmt1=mysqli_prepare($connexion,$req1);
		mysqli_stmt_bind_param($stmt1,"s",$pseudo);
		mysqli_stmt_execute($stmt1);
		mysqli_stmt_bind_result($stmt1,$nom,$prenom,$dateNaissance,$idA);
		mysqli_stmt_fetch($stmt1);
		// puis on affiche ces informations
		?>
		<h2 id="ST_info_joueur1">Informations sur la joueuse </h2>
		<p id="info_joueur">Pseudo de la joueuse : <?php echo $pseudo; ?><br/>
		Prénom de la joueuse : <?php echo $prenom; ?><br/>
		Nom de la joueuse : <?php echo $nom; ?><br/>
		Date de Naissance de la joueuse : <?php echo date('d', strtotime($dateNaissance)) .'/' . date('m', strtotime($dateNaissance)) .'/' . date('Y', strtotime($dateNaissance)); ?></p>
		<?php mysqli_stmt_close($stmt1); 

		$req5="select rue,codePostal,Ville from adresse_postale natural join joueuses where pseudo like ?";
		// récupération de l'adresse de la joueuse
		$stmt5=mysqli_prepare($connexion,$req5);
		mysqli_stmt_bind_param($stmt5,"s",$pseudo);
		mysqli_stmt_execute($stmt5);
		mysqli_stmt_bind_result($stmt5,$rue,$codePostal,$Ville);
		mysqli_stmt_fetch($stmt5);
		// affichage de l'adresse de la joueuse
		?>
		<p id="adresse_joueur">Adresse de la joueuse : <?php echo $rue . ',' . $codePostal . ',' . $Ville; ?></p>
		<?php
		mysqli_stmt_close($stmt5);
		?>

		<h2 id="ST_info_joueur2">Recommandations </h2>
		
		<?php
		$req2="select nomJ from jeu natural join jouer where idJ not in(select idj from jeu natural join jouer natural join partie natural join joue_solo where pseudo like ?) group by nomJ order by count(nomJ) desc";
		// sélectionne les recommandations de jeux auquels la joueuse devrait jouer (ceux qui ont été joués et auquels elle n'a pas encore joué)
		$stmt2=mysqli_prepare($connexion,$req2);
		mysqli_stmt_bind_param($stmt2,"s",$pseudo);
		mysqli_stmt_execute($stmt2);
		mysqli_stmt_bind_result($stmt2,$nomJ);
		mysqli_stmt_fetch($stmt2);
		
		$compt1=0; // compteur pour afficher 5 jeux au maximum

		if($nomJ!=NULL) // affichage sous forme de liste des recommandations de jeux
		{
			?>
			<p id="texte2_info_joueur">La joueuse <?php echo $pseudo; ?> devrait essayer les jeux :<p>
			<ul>
				<?php
				while(($nomJ != NULL) && ($compt1<5))
				{
					?>
					<li id="liste_info_joueur2"><?php echo $nomJ; ?></li>
					<?php
					mysqli_stmt_bind_result($stmt2,$nomJ);
					mysqli_stmt_fetch($stmt2);
					$compt1 ++;
				} ?>
			</ul>
			<?php
			mysqli_stmt_close($stmt2);
		}
		?>

		<p id="texte1_info_joueur">La joueuse <?php echo $pseudo; ?> devrait jouer avec :</p>
		<?php
		$req4="select pseudo from joue_solo natural join partie where pseudo not like ? and idL in (select idL from partie natural join joue_solo where pseudo like ?) group by pseudo order by count(pseudo) desc";
		// récupère les pseudos des joueuses qui on joué à un jeu au même lieu que la joueuse sélectionnée
		$stmt3=mysqli_prepare($connexion,$req4);
		mysqli_stmt_bind_param($stmt3,"ss",$pseudo,$pseudo);
		mysqli_stmt_execute($stmt3);
		mysqli_stmt_bind_result($stmt3,$pseudo_joueuse_conseillée_lieu);
		mysqli_stmt_fetch($stmt3);

		$compt2=0; // compteur pour afficher 5 joueuses au maximum

		if($pseudo_joueuse_conseillée_lieu != NULL) // affichage des joueuses conseillées en fonction du lieu sous forme de liste
		{
			?>
			<ul>
				<p id="texte3_info_joueur">- les joueuses ayant joué dans le même lieu qu'elle :<p>
				<?php
				while(($pseudo_joueuse_conseillée_lieu != NULL) && ($compt2<5))
				{
					?>
					<li id="liste_info_joueur"><?php echo $pseudo_joueuse_conseillée_lieu; ?></li>
					<?php
					mysqli_stmt_bind_result($stmt3,$pseudo_joueuse_conseillée_lieu);
					mysqli_stmt_fetch($stmt3);
					$compt2 ++;
				} ?>
			</ul>
			<?php
		}
		else
		{ 
			?>
			<p id="texte4_info_joueur">Aucune joueuse n'a joué à un jeu au même endroit qu'elle.
			<?php
		}
		mysqli_stmt_close($stmt3);

		$req3="select pseudo from joue_solo natural join partie natural join jouer where pseudo not like ? and idJ in (select idJ from jouer natural join partie natural join joue_solo where pseudo like ?) group by pseudo order by count(pseudo) desc";
		// sélectionne les joueuses qui ont joué aux mêmes jeux que la joueuse sélectionnée
		$stmt4=mysqli_prepare($connexion,$req3);
		mysqli_stmt_bind_param($stmt4,"ss",$pseudo,$pseudo);
		mysqli_stmt_execute($stmt4);
		mysqli_stmt_bind_result($stmt4,$pseudo_joueuse_conseillée_jeu);
		mysqli_stmt_fetch($stmt4);

		$compt3=0; // compteur pour afficher 5 joueuses au maximum
		
		if($pseudo_joueuse_conseillée_jeu != NULL) // affichage des joueuses conseillées en fonction des jeux joués sous forme de liste
		{
			?>
			<ul>
				<p id="texte5_info_joueur">- les joueuses qui ont joué au même jeu qu'elle :<p>
				<?php
				while(($pseudo_joueuse_conseillée_jeu != NULL) && ($compt3<5))
				{
					?>
					<li id="liste_info_joueur3"><?php echo $pseudo_joueuse_conseillée_jeu; ?></li>
					<?php
					mysqli_stmt_bind_result($stmt4,$pseudo_joueuse_conseillée_jeu);
					mysqli_stmt_fetch($stmt4);
					$compt3 ++;
				} ?>
			</ul>
			<?php
		}
		else
		{
			?>
			<p id="texte6_info_joueur">Aucune joueuses n'a joué aux mêmes jeux qu'elle.<p>
			<?php
		}
		mysqli_stmt_close($stmt4);

		/* recuperation des id des lieux*/
			$req4="SELECT lattitude,longitude FROM lieu natural join partie natural join joue_solo WHERE pseudo like ?";
			$stmt5=mysqli_prepare($connexion, $req4);
			mysqli_stmt_bind_param($stmt5,"s", $pseudo);
			mysqli_stmt_execute($stmt5);
			mysqli_stmt_bind_result($stmt5,$latt,$long);
			mysqli_stmt_fetch($stmt5);

			echo '<p id="texte7_info_joueur"> Carte des lieux où a joué la joueuse : </p>'
?>
	<!-- creation de la carte -->
	<script src="http://maps.googleapis.com/maps/api/js"></script>

<script>
function initialize() {
  var mapProp = {
    center:new google.maps.LatLng(46.328698,2.8441219999999703), //centrage sur Bézenet, au centre de la France
    zoom:5,
    mapTypeId:google.maps.MapTypeId.ROADMAP
  };
  var map=new google.maps.Map(document.getElementById("googleMap"),mapProp);
  <?php
  while(($latt!= NULL) && ($long != NULL)) /* boucle pour la création des marqueurs pour chaque lieux */
{
	?>
  var marker=new google.maps.Marker({//création du marqueur
  	<?php 
  echo "position:new google.maps.LatLng($latt,$long),"
  	?>
  });
  marker.setMap(map);
  <?php
  mysqli_stmt_bind_result($stmt5,$latt,$long);
  mysqli_stmt_fetch($stmt5);
  ?>

  <?php } //fin de la boucle
  mysqli_stmt_close($stmt5);
  ?>
}



google.maps.event.addDomListener(window, 'load', initialize);//affichage de la carte

</script>


		<div id="googleMap" style="width:500px;height:380px;"></div>
<?php }
	
	else // formulaire de sélection de joueuse
	{?>
	<form method="post" action="#">
		<table id="formulaire_info_joueuses">

			<tr>
				<td>
					<label for="idJoueuse"> Nom de la joueuse: </label>
				</td>

				<td>
					<select required name="Pseudo" id="idJoueuse">
					<option value="" selected="selected"></option>
						<?php 
							$req="SELECT distinct pseudo FROM joueuses order by pseudo 	asc";
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
				</td>

				<td>
					<input type="submit" name="boutonSubmit" value="Voir les informations!"/>
				</td>
			</tr>

		</table>
	</form>
	<?php } ?>
</aside>