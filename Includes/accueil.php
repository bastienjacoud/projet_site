<aside id="page_d'accueil">
	<p id="description">
	<br/><br/><br/>
	Bienvenue sur Score Gneu Gneu<br/>
	Ce site vous permet de gerer tous vos scores et vos equipes.<br/>
	</p>
	<p id="historique">
	<?php 
	$req="SELECT max(idP) from partie";
	// sélectionne la dernière partie jouée
	$res=mysqli_query($connexion, $req);
	$row=mysqli_fetch_assoc($res);
	$idp=$row['max(idP)'];
	

	$req2="SELECT score,duree,gagnant,idL FROM partie WHERE idP=?";
	// sélectionne le score, la durée, le gagnant et l'idL correspondant à la dernière partie jouée
	$stmt=mysqli_prepare($connexion, $req2);
	mysqli_stmt_bind_param($stmt,"i",$idp);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_bind_result($stmt,$score,$duree,$resultat,$idL);
	mysqli_stmt_fetch($stmt);

	$idl=$idL;
	



	if($resultat!=0) // transformation du booleen en 'gagnée' ou 'perdue'
		{$resultats="gagnée";}
	else {$resultats="perdue";}


	echo "La derniere partie ajoutée a eu un score de $score, une durée de $duree minutes, a été $resultats  et a eu lieu à ";
	// affiche les informations sur la dernière partie jouée
	mysqli_stmt_close($stmt);

	$req7="SELECT nomL FROM lieu WHERE idL=?";
	// sélectionne le nom du lieu correspondant à l'idL
	$stmt6=mysqli_prepare($connexion, $req7);
	mysqli_stmt_bind_param($stmt6,"i", $idl);
	mysqli_stmt_execute($stmt6);
	mysqli_stmt_bind_result($stmt6, $lieu);
	mysqli_stmt_fetch($stmt6);
	echo "$lieu.";
	mysqli_stmt_close($stmt6);

	$req8="SELECT max(idP) FROM joue_solo";
	// récupère l'idP de la dernière partie qui a été jouée en solo
	$res8=mysqli_query($connexion, $req8);
	$row=mysqli_fetch_assoc($res8);
	$idp2=$row['max(idP)'];

	$req9="SELECT pseudo FROM joue_solo WHERE idP=?";
	// récupère le pseudo de la joueuse qui a joué lors de cette dernière partie 
	$stmt7=mysqli_prepare($connexion, $req9);
	mysqli_stmt_bind_param($stmt7, "i", $idp2);
	mysqli_stmt_execute($stmt7);
	mysqli_stmt_bind_result($stmt7, $pseudo);
	mysqli_stmt_fetch($stmt7);

	echo "<br/> La dernière joueuse à avoir joué en solo est $pseudo.";
	// affiche les informations sur cette joueuse
	mysqli_stmt_close($stmt7);



	// affichage d'une carte avec un marqueur sur le lieu de la dernière partie 
	$req3="SELECT lattitude, longitude FROM lieu WHERE idL=?";
	// récupération de la lattitude et de la longitude du lieu dans lequel s'est déroulé la denrière partie 
	$stmt2=mysqli_prepare($connexion, $req3);
	mysqli_stmt_bind_param($stmt2,"i",$idl);
	mysqli_stmt_execute($stmt2);
	mysqli_stmt_bind_result($stmt2,$lat, $long);
	mysqli_stmt_fetch($stmt2);
	$lattitude=$lat;
	$longitude=$long;
	mysqli_stmt_close($stmt2);
	?>
	<!--carte-->
	<p id="carte_dernier_lieu">Position du lieu où la dernière partie a été jouée:</p>
	<br>
	<script src="http://maps.googleapis.com/maps/api/js"></script>
<script>
function initialize() {
  var mapProp = {
    center:new google.maps.LatLng(45.750000,4.850000),
    zoom:5,
    mapTypeId:google.maps.MapTypeId.ROADMAP
  };
  var map=new google.maps.Map(document.getElementById("googleMap"),mapProp);
  var marker=new google.maps.Marker({
  	<?php 
  	// création du marqueur
  	echo "position:new google.maps.LatLng($lattitude,$longitude),"
  	?>
  });
  //placement du marqueur sur la carte
  marker.setMap(map);
}


google.maps.event.addDomListener(window, 'load', initialize);

</script>
<!-- fin carte -->

		<div id="googleMap" style="width:500px;height:380px;"></div> <!-- code pour afficher la carte -->
	
	</p>
	
</aside>
	