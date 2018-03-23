<aside id="statistiques">
	<h1 id="Titre_stats1">STATISTIQUES</h1>
	<?php
	if(isset($_POST['Trier_par'])) // si le bouton de tri des statistiques a été soumis
	{
		$valeur=mysqli_real_escape_string($connexion,$_POST['choix_tri']);
		// récupération de la valeur du bouton
		if(isset($_POST['choix_tri'])==0) // valeur par défaut du bouton de tri
		{
			$valeur=1;
		}

		$compt1=0;
		$compt2=0;
		$compt3=0;
		$compt4=0;
		// compteurs pour afficher un nombre maximum de statistiques(pas plus de 3 dans le cas présent)

		if($valeur==1) // si l'on souhaite trier par lieu
		{
			$req1="select distinct nomL,count(distinct pseudo) as count_pseudo from lieu natural join partie natural join joue_solo group by idL order by count(distinct pseudo) desc";
			// sélectionne les noms des lieux et le nombre de joueuses ayant joué dans ce lieu, ordonné par nombre de joueuses dans l'ordre croissant
			$res1=mysqli_query($connexion,$req1);
			while(($row1=mysqli_fetch_assoc($res1)) && ($compt1<3)) // tant qu'il reste des lieux où les joueuses ont joué, ou que le compteur ne dépasse pas 3
			{ // on affiche les statistiques
				?>
				<p id="texte_stats1"> Il y a <?php echo $row1['count_pseudo']; ?> joueuses qui ont joué à <?php echo $row1['nomL']; ?>. <p>
				<?php
				
				$compt1 ++;
			}
			?> <br> <?php

			$req3="select distinct nomL,count(idP) as count_partie from lieu natural join partie group by idL order by count(idP) desc";
			// sélectionne les lieux et le nombre de parties ayant été jouées dans chaque lieu, ordonné par le nombre de parties jouées pour chaque lieu dans l'ordre croissant
			$res3=mysqli_query($connexion,$req3);
			while(($row3=mysqli_fetch_assoc($res3)) && ($compt2<3)) // tant qu'il reste des lieux où une partie à été jouée, ou que le compteur ne dépasse pas 3
			{ // on affiche les statistiques
				?>
				<p id="texte_stats2"> Il y a eu <?php echo $row3['count_partie']; ?> parties jouées à <?php echo $row3['nomL']; ?>. <p>
				<?php
				
				$compt2 ++;
			}
			?> <br> <?php
		}
		if($valeur==0) // si l'on souhaite trier par année
		{
			$req5="select year(dateP),count(distinct pseudo) from partie natural join joue_solo group by year(dateP) order by count(distinct pseudo) desc";
			// sélectionne l'année et le nombre de joueuses ayant joué chaque année, ordonné par le nombre de joueuses ayant joué chaque année par ordre croissant
			$res5=mysqli_query($connexion,$req5);
			while(($row5=mysqli_fetch_assoc($res5)) && ($compt3<3))// tant qu'il reste des années où il y a eu des joueuses qui ont jouées, ou que le compteur est inférieur à 3
			{ // on affiche les statistiques
				?>
				<p id="texte_stats3"> Il y a <?php echo $row5['count(distinct pseudo)']; ?> joueuses différentes qui ont joué en <?php echo $row5['year(dateP)']; ?> . <p>
				<?php
		
				$compt3 ++;
			}
			?> <br> <?php

			$req7="select year(dateP),count(idP) from partie group by year(dateP) order by count(idP) desc";
			// selectionne l'année et le nombre de parties ayant été jouées chaque année, ordonné par le nombre de parties ayant été jouées chaque année par ordre croissant
			$res7=mysqli_query($connexion,$req7);
			while(($row7=mysqli_fetch_assoc($res7)) && ($compt4<3)) // tant qu'il reste des années où il y a eu des parties jouées,ou que le compteur ne dépasse pas 3
			{ // on affiche les statistiques
				?>						
				<p id="texte_stats4"> Il y a eu <?php echo $row7['count(idP)']; ?> parties jouées en <?php echo $row7['year(dateP)']; ?> . <p>
				<?php

				$compt4 ++;	
			}
			?> <br> <?php
		}
	}
	else // on affiche le bouton radio
	{?>
	<h2 id="titre_stats2">Trier par :</h2>
		<form id="formulaire_stats" method="post" action="#">
			<input type="radio" name="choix_tri" id="stats_lieu" value="1" checked />
			<label for="stats_lieu">Lieu </label>  

			<input type="radio" name="choix_tri" id="stats_année" value="0" />
			<label for="stats_année">Année </label>
			<br><br>

			<input type="submit" name="Trier_par" value="C'est parti!!!"/>
		</form>
	<?php } ?>
</aside>