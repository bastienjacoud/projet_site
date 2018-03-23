<?php
	
	
	function connectBD() // fonction pour se connecter à la base de données
	{
		$SERVEUR="localhost"; // serveur utilisé
		$UTILISATEUR="root"; // nom d'utilisateur par défaut
		$MOTDEPASSE=""; // mot de passe par défaut
		$BDD="projet"; // nom de la base de données
		$connexion = NULL;
		global $connexion; // déclaration de la variable de connexion
		$connexion = mysqli_connect($SERVEUR, $UTILISATEUR, $MOTDEPASSE, $BDD); // connexion à la base de données
		if (mysqli_connect_errno()) // En cas d'erreur de connexion
		{ // afficher un message d'erreur
		    printf("Échec de la connexion : %s\n", mysqli_connect_error());
		    exit();
		}
	}

	function deconnectBD()  // fonction pour se déconnecter de la base de donnée
	{
		global $connexion;
		mysqli_close($connexion); // fermeture de la connexion
	}


	function date_aleatoire($date_min, $date_max)
	{ // marche !
		$annee_min=date('Y', strtotime($date_min)); // récupère l'année minimum
		$mois_min=date('m', strtotime($date_min)); // récupère le mois minimum avec un entier commencant par 0 si inf à 10
		$jour_min=date('d', strtotime($date_min)); // récupère le jour minimum avec un entier commencant par 0 si inf à 10

		$annee_max=date('Y', strtotime($date_max)); // récupère l'année maximum
		$mois_max=date('m', strtotime($date_max)); // récupère le mois maximum avec un entier commencant par 0 si inf à 10
		$jour_max=date('d', strtotime($date_max)); // récupère le jour maximum avec un entier commencant par 0 si inf à 10

		
		if($annee_min==$annee_max) // même année
		{
			$annee_rand=$annee_min; // affectation de l'année 
			if($mois_min==$mois_max) // même mois
			{
				$mois_rand=$mois_min; // affectation du mois
				if($jour_min==$jour_max) // même jour
				{
					$jour_rand=$jour_min; // affectation de jour
				}
				else // jour différent
				{
					$jour_rand=rand($jour_min,$jour_max);// affectation du jour
				}
			}
			else // mois différent
			{
				$mois_rand=rand($mois_min,$mois_max); // affectation du mois
				if(($mois_rand==$mois_min)&&(($mois_rand=='01')||($mois_rand=='03')||($mois_rand=='05')||($mois_rand=='07')||($mois_rand=='08')||($mois_rand=='10')||($mois_rand=='12')))
				{ // si le mois possède 31 jours et que c'est le mois minimal
					$jour_rand=rand($jour_min, 31); // affectation du jour
				}
				else if(($mois_rand==$mois_min)&&(($mois_rand=='04')||($mois_rand=='06')||($mois_rand=='09')||($mois_rand=='11')))
				{ // si le mois possède 30 jours et que c'est le mois minimal
					$jour_rand=rand($jour_min, 30); // affectation du jour
				}
				else if (($mois_rand==$mois_min)&&($mois_rand=='02'))
				{ // si le mois est février (on suppose que le mois de février possède toujours 28 jours) et que c'est le mois minimal		
					$jour_rand==rand($jour_min, 28);// affectation du jour
				}


				else if(($mois_rand==$mois_max)&&(($mois_rand=='01')||($mois_rand=='03')||($mois_rand=='05')||($mois_rand=='07')||($mois_rand=='08')||($mois_rand=='10')||($mois_rand=='12')))
				{ // si le mois possède 31 jours et que c'est le mois maximal
					$jour_rand=rand(01, $jour_max); // affectation du jour
				}
				else if(($mois_rand==$mois_max)&&(($mois_rand=='04')||($mois_rand=='06')||($mois_rand=='09')||($mois_rand=='11')))
				{ // si le mois possède 30 jours et que c'est le mois maximal
					$jour_rand=rand(01, $jour_max); // affectation du jour
				}
				else if (($mois_rand==$mois_max)&&($mois_rand=='02'))
				{ // si le mois est février et que c'est le mois maximal
					$jour_rand==rand(01, $jour_max); // affectation du jour
				}


				else if (($mois_rand=='01')||($mois_rand=='03')||($mois_rand=='05')||($mois_rand=='07')||($mois_rand=='08')||($mois_rand=='10')||($mois_rand=='12'))
				{ // si le mois possède 31 jours
					$jour_rand=rand(01, 31);  // affectation du jour
				}
				else if (($mois_rand=='04')||($mois_rand=='06')||($mois_rand=='09')||($mois_rand=='11'))
				{ // si le mois possède 30 jours
				$jour_rand=rand(01, 30);  // affectation du jour
				}
				else if($mois_rand=='02')
				{ // si le mois est février
					$jour_rand=rand(01, 28);  // affectation du jour
				}
			}
		
		}
		else // année différente
		{
			$annee_rand=rand($annee_min, $annee_max);
			if($annee_rand==$annee_min) // si l'année aléatoire est l'année minimale
			{
				$mois_rand=rand($mois_min, 12); // on définit le mois aléatoire
				if(($mois_rand==$mois_min)&&(($mois_rand=='01')||($mois_rand=='03')||($mois_rand=='05')||($mois_rand=='07')||($mois_rand=='08')||($mois_rand=='10')||($mois_rand=='12')))
				{ // si le mois possède 31 jours et que c'est le mois minimal
					$jour_rand=rand($jour_min, 31); // affectation du jour
				}
				else if(($mois_rand==$mois_min)&&(($mois_rand=='04')||($mois_rand=='06')||($mois_rand=='09')||($mois_rand=='11')))
				{ // si le mois possède 30 jours et que c'est le mois minimal
					$jour_rand=rand($jour_min, 30); // affectation du jour
				}
				else if (($mois_rand==$mois_min)&&($mois_rand=='02'))
				{ // si le mois est février et que c'est le mois minimal
					$jour_rand==rand($jour_min, 28); // affectation du jour
				}

				else if (($mois_rand=='01')||($mois_rand=='03')||($mois_rand=='05')||($mois_rand=='07')||($mois_rand=='08')||($mois_rand=='10')||($mois_rand=='12'))
				{ // si le mois possède 31 jours
					$jour_rand=rand(01, 31); // affectation du jour
				}
				else if (($mois_rand=='04')||($mois_rand=='06')||($mois_rand=='09')||($mois_rand=='11'))
				{ // si le mois possède 30 jours
					$jour_rand=rand(01, 30); // affectation du jour
				}
				else if($mois_rand=='02')
				{ // si le mois est février
					$jour_rand=rand(01, 28); // affectation du jour
				}
			}
			else if ($annee_rand==$annee_max) // si l'année aléatoire est l'année maximale
			{
				$mois_rand=rand(01, $mois_max); // on génère le mois aléatoire
				if (($mois_rand==$mois_max) && (($mois_rand=='01')||($mois_rand=='03')||($mois_rand=='05')||($mois_rand=='07')||($mois_rand=='08')||($mois_rand=='10')||($mois_rand=='12')))
				{ // si le mois possède 31 jours et que c'est le mois maximal
					$jour_rand=rand(01, $jour_max); // affectation du jour
				}
				else if (($mois_rand==$mois_max) && (($mois_rand=='04')||($mois_rand=='06')||($mois_rand=='09')||($mois_rand=='11')))
				{ // si le mois possède 30 jours et que c'est le mois maximal
					$jour_rand=rand(01, $jour_max); // affectation du jour
				}
				else if (($mois_rand==$mois_max) && ($mois_rand=='02'))
				{ // si le mois est février et que c'est le mois maximal
					$jour_rand=rand(01, $jour_max); // affectation du jour
				}

				else if (($mois_rand=='01')||($mois_rand=='03')||($mois_rand=='05')||($mois_rand=='07')||($mois_rand=='08')||($mois_rand=='10')||($mois_rand=='12'))
				{ // si le mois possède 31 jours	
					$jour_rand=rand(01, 31); // affectation du jour
				}
				else if (($mois_rand=='04')||($mois_rand=='06')||($mois_rand=='09')||($mois_rand=='11'))
				{ // si le mois possède 30 jours
					$jour_rand=rand(01, 30); // affectation du jour
				}
				else if($mois_rand=='02')
				{ // si le mois est février
					$jour_rand=rand(01, 28); // affectation du jour
				}

			}
			else // si l'année aléatoire n'est ni le mois maximal ni le mois minimal
			{
				$mois_rand=rand(01, 12); // affectation du mois
				if (($mois_rand=='01')||($mois_rand=='03')||($mois_rand=='05')||($mois_rand=='07')||($mois_rand=='08')||($mois_rand=='10')||($mois_rand=='12'))
				{ // si le mois possède 31 jours
					$jour_rand=rand(01, 31); // affectation du jour
				}
				else if (($mois_rand=='04')||($mois_rand=='06')||($mois_rand=='09')||($mois_rand=='11'))
				{ // si le mois possède 30 jours
					$jour_rand=rand(01, 30); // affectation du jour
				}
				else if ($mois_rand=='02')
				{ // si le mois est février
					$jour_rand=rand(01, 28); // affectation du jour
				}
			}
		}


		$date_rand=$annee_rand . '-' . $mois_rand . '-' . $jour_rand; // concaténation de la date 
		return $date_rand; // on retourne la date générée
	}

	function update_table($connexion)//met les idL de lieu dans la table adresse_postale lorsque Ville=nomL
	{ // a utiliser une seule fois !
		$req10 ="UPDATE adresse_postale SET idL = ? WHERE Ville = ?";
		// on modifie les valeurs de idL dans adresse_postale
		$update = mysqli_prepare($connexion,$req10);
		$U['id'] = NULL;
		$U['ville'] = NULL;
		// on définit 2 tableaux contenant tous les paramètres de la requête préparée
		mysqli_stmt_bind_param($update, "is", $U['id'], $U['ville']);
		$req11 = 'SELECT nomL AS ville, idL AS id FROM lieu;';
		// récupération des noms des lieux et des idL de la table lieu
		$Lieu = mysqli_query($connexion, $req11);
		$T = array();
		while($E = mysqli_fetch_assoc($Lieu))
		{
		   	$T = array_merge($T, array($E['ville'] => $E['id'])); // on copie $E['ville'] et $E['id'] dans le tableau $T
		}
		mysqli_free_result($Lieu);
		foreach($T as $key => $val) // on modifie la table autant de fois que nécessaire (tant qu'il y a des idL qui correspondent)
		{
			$U['id'] = $val;
			$U['ville'] = $key;
			mysqli_stmt_execute($update);
		}
		mysqli_stmt_close($update);
	}
?>