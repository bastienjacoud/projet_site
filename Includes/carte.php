<aside>
<h1> Carte des lieux où des parties ont été jouées : </h1>

		<?php 
			/* recuperation des id des lieux*/
			$req="SELECT distinct idL FROM partie";
			$res=mysqli_query($connexion, $req);

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
  while($row=mysqli_fetch_assoc($res)) /* boucle pour la création des marqueurs pour chaque lieux */
{

	$req2="SELECT lattitude,longitude FROM lieu WHERE idL = ?";
	$stmt=mysqli_prepare($connexion, $req2);
	mysqli_stmt_bind_param($stmt,"i",$row['idL']);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_bind_result($stmt,$lat, $long);
	mysqli_stmt_fetch($stmt);
	$lattitude=$lat;
	$longitude=$long;
	mysqli_stmt_close($stmt);
	?>
  var marker=new google.maps.Marker({//création du marqueur
  	<?php 
  echo "position:new google.maps.LatLng($lattitude,$longitude),"
  	?>
  });
  marker.setMap(map);
  <?php } ?>//fin de la boucle
}



google.maps.event.addDomListener(window, 'load', initialize);//affichage de la carte

</script>


		<div id="googleMap" style="width:500px;height:380px;"></div>

</aside>