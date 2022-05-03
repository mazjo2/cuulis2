<?php
session_start(); 


ob_start();


echo'<!DOCTYPE html><html> 
<head>
<title> Äänestä </title>';

include("yhteys.php");

// server should keep session data for AT LEAST 1 hour
// each client should remember their session id for EXACTLY 1 hour


 // ready to go!
if (!isset($_SESSION["KurssiId"])) {
    header('location: omatkurssit.php');
}
if (isset($_SESSION["Kayttajatunnus"])) {
    if ($_SESSION["Rooli"] == "opettaja" || $_SESSION["Rooli"] == "admin" || $_SESSION["Rooli"] == "admink" || $_SESSION["Rooli"] == "opeadmin") {
        include("kurssisivustonheader.php");

        echo '<div class="cm8-container7" style="margin-top: 0px; margin-bottom: 0px; padding-top: 0px; padding-bottom: 60px">';



        echo'<nav class="topnav" id="myTopnav">
	 <a href="kurssi.php?id=' . $_SESSION["KurssiId"] . '">Etusivu</a><a href="tiedostot.php"  >Materiaalit</a>  
	  
	  <a href="itsetyot.php" onclick="loadProgress()" >Tehtävälista</a><a href="ryhmatyot.php" >Palautukset</a><a href="itsearviointi.php" >Itsearviointi</a><a href="kysely.php"  >Kyselylomake</a>
		
	 ';
        if (!$haeakt = $db->query("select distinct kysakt from kurssit where id='" . $_SESSION["KurssiId"] . "'")) {
            die('<br><br><b style="font-size: 1em; color: #FF0000">Tietokantayhteydessä ongelmia!<br><br> Ota yhteyttä oppimisympäristön ylläpitäjään <a href="yhteydenotto.php" style="text-decoration: underline"><u>tästä.</b></u><br><br></div></div></div></div><footer class="cm8-containerFooter" style="padding: 20px 0px 20px 0px"><b>Copyright &copy;  <br><a href="admininfo.php">Marianne Sjöberg</b></a></footer>');
        }

        while ($rowa = $haeakt->fetch_assoc()) {

            $kysakt = $rowa[kysakt];
        }
        if ($kysakt == 1) {
            
        } else {
            // echo'<a  href="kysymyksetkommentit.php">Kysy/kommentoi</a>';
        }


        echo'
	  <a href="keskustelut.php" >Keskustele</a> 
	  <a href="aanestykset.php" class="currentLink" >Äänestä</a><a href="osallistujat.php"   >Osallistujat</a>  	  
	   <a href="javascript:void(0);" class="icon" onclick="myFunction(this)"><div class="bar1"></div>
  <div class="bar2"></div>
  <div class="bar3"></div></a>
	</nav>';




        echo'

<script>
function myFunction(y) {
  y.classList.toggle("change");
    var x = document.getElementById("myTopnav");
    if (x.className === "topnav") {
        x.className += " responsive";
    } else {
        x.className = "topnav";
    }
}
</script>';








        if (isset($_POST['painikelisaa'])) {


            echo'<div class="cm8-quarter" style="width: 300px; padding-left: 20px"> <h2 style="padding-top: 40px">Äänestä</h2>';

            if (!$haeaanestykset = $db->query("select * from aanestykset where kurssi_id='" . $_SESSION["KurssiId"] . "'")) {
                die('<br><br><b style="font-size: 1em; color: #FF0000">Tietokantayhteydessä ongelmia!<br><br> Ota yhteyttä oppimisympäristön ylläpitäjään <a href="yhteydenotto.php" style="text-decoration: underline"><u>tästä.</b></u><br><br></div></div></div></div><footer class="cm8-containerFooter" style="padding: 20px 0px 20px 0px"><b>Copyright &copy;  <br><a href="admininfo.php">Marianne Sjöberg</b></a></footer>');
            }
            if ($haeaanestykset->num_rows != 0) {
                echo'<div class="cm8-sidenav" style="padding-top: 20px; margin-top:0px; height: 100%; padding-left: 0px">';
                while ($rowP = $haeaanestykset->fetch_assoc()) {
                    $kysymys = $rowP[kysymys];
                    $id = $rowP[id];



                    if ($_POST[id] == $id) {

                        echo'<a href="aanestykset.php?a=' . $id . '" class="btn-info3" style="font-weight: normal; margin-right: 20px; margin-bottom: 5px;  padding: 3px 6px 3px 20px"><b style="font-size: 1.1em; ">&#9997 &nbsp&nbsp&nbsp</b> ' . $kysymys . '</a>';
                    } else {

                        echo'<a href="aanestykset.php?a=' . $id . '" class="btn-info3" style="font-weight: normal; margin-right: 20px; margin-bottom: 5px;  padding: 3px 6px 3px 20px">' . $kysymys . '</a>';
                    }
                }

                echo'</div>';
            }



            echo'</div>';








            echo'<div class="cm8-half">';
            echo '<form action="aktivoiaanestys2eka.php" method="post" class="form-style-k"><fieldset>';

            echo' <legend >Lisää äänestys (1/2)</legend>';
            echo'<a href="aanestykset.php" class="palaa">&#8630 &nbsp&nbsp&nbspPalaa takaisin</a><br><br>';


            //tässä luodaan äänestys

            echo'<p style="width: 50%">Kysymys: <br>
					<textarea name="kysymys" rows="2"  style="font-size: 1em"></textarea></p><br>

						<p style="width: 50%">Vaihtoehtojen lukumäärä:&nbsp&nbsp&nbsp	<select name="lkm">';

            for ($i = 1; $i <= 20; $i++) {
                echo'
						<option value=' . $i . '>' . $i;
            }

            echo'</select></p><br> 
					<input type="hidden" name="onko" value="ei">
					<input type="hidden" name="id" value=' . $_SESSION["KurssiId"] . '>
					<br><input type="submit" value="Jatka &#8631" class="myButton9">			
						</fieldset></form>';



            echo'</div><div class="cm8-half"></div></div></div>';
        } else if (isset($_GET[a])) {
            if (!$haekys = $db->query("select distinct * from aanestykset where id='" . $_GET[a] . "'")) {
                die('<br><br><b style="font-size: 1em; color: #FF0000">Tietokantayhteydessä ongelmia!<br><br> Ota yhteyttä oppimisympäristön ylläpitäjään <a href="yhteydenotto.php" style="text-decoration: underline"><u>tästä.</b></u><br><br></div></div></div></div><footer class="cm8-containerFooter" style="padding: 20px 0px 20px 0px"><b>Copyright &copy;  <br><a href="admininfo.php">Marianne Sjöberg</b></a></footer>');
            }

            while ($rowk = $haekys->fetch_assoc()) {
                $kysymys = $rowk[kysymys];
                $lkm = $rowk[lkm];
                $aanid = $rowk[id];
            }

            echo'<div class="cm8-quarter" style="width: 300px; padding-left: 20px"> <h2 style="padding-top: 40px">Äänestä</h2>';

            if (!$haeaanestykset = $db->query("select * from aanestykset where kurssi_id='" . $_SESSION["KurssiId"] . "'")) {
                die('<br><br><b style="font-size: 1em; color: #FF0000">Tietokantayhteydessä ongelmia!<br><br> Ota yhteyttä oppimisympäristön ylläpitäjään <a href="yhteydenotto.php" style="text-decoration: underline"><u>tästä.</b></u><br><br></div></div></div></div><footer class="cm8-containerFooter" style="padding: 20px 0px 20px 0px"><b>Copyright &copy;  <br><a href="admininfo.php">Marianne Sjöberg</b></a></footer>');
            }
            if ($haeaanestykset->num_rows != 0) {
                echo'<div class="cm8-sidenav" style="padding-top: 20px; margin-top:0px; height: 100%; padding-left: 0px">';
                while ($rowP = $haeaanestykset->fetch_assoc()) {
                    $kysymys = $rowP[kysymys];
                    $id = $rowP[id];

                    $lkm = $rowP[lkm];

                    if ($_POST[id] == $id) {

                        echo'<a href="aanestykset.php?a=' . $id . '" class="btn-info3" style="font-weight: normal; margin-right: 20px; margin-bottom: 5px;  padding: 3px 6px 3px 20px"><b style="font-size: 1.1em; ">&#9997 &nbsp&nbsp&nbsp</b> ' . $kysymys . '</a>';
                    } else {

                        echo'<a href="aanestykset.php?a=' . $id . '" class="btn-info3" style="font-weight: normal; margin-right: 20px; margin-bottom: 5px;  padding: 3px 6px 3px 20px">' . $kysymys . '</a>';
                    }
                }

                echo'</div>';
            }



            echo'</div>';








            echo'<div class="cm8-half">';
            echo '<form action="aktivoiaanestys2eka.php" method="post" class="form-style-k"><fieldset>';

            echo' <legend >Lisää äänestys (1/2)</legend>';
            echo'<a href="poistaaanestys.php?id=' . $_GET[a] . '" class="palaa"> &#8630 &nbsp&nbsp&nbsp Lopeta äänestyksen lisääminen </a><br><br>';


            //tässä luodaan äänestys

            echo'<p style="width: 50%">Kysymys: <br>
					<textarea name="kysymys" rows="2"  style="font-size: 1em">' . $kysymys . '</textarea></p><br>

						<p style="width: 50%">Vaihtoehtojen lukumäärä: &nbsp&nbsp&nbsp	<select name="lkm">';
            echo'<option value=' . $lkm . ' selected>' . $lkm;
            for ($i = 1; $i <= 20; $i++) {
                echo'
						<option value=' . $i . '>' . $i;
            }

            echo'</select></p><br> '
            . '<input type="hidden" name="aanid" value=' . $_GET[a] . '>
					<input type="hidden" name="onko" value="on">
					<input type="hidden" name="id" value=' . $_SESSION["KurssiId"] . '>
					<br><input type="submit" value="Jatka &#8631" class="myButton9">			
						</fieldset></form>';



            echo'</div><div class="cm8-half"></div></div></div>';
        } else if (isset($_POST['painikeaktivoi'])) {
            $db->query("update aanestykset set aktiivinen=1 where id = '" . $_POST[id] . "'");

            header("location: aanestykset.php?a=" . $_POST[id]);
        } else if (isset($_POST['painikesulje'])) {
            $db->query("update aanestykset set aktiivinen=0 where id = '" . $_POST[id] . "'");
            header("location: aanestykset.php?a=" . $_POST[id]);
        } else if (isset($_POST['painikenayta'])) {
            $db->query("update aanestykset set nakyvissa=1 where id = '" . $_POST[id] . "'");

            header("location: aanestykset.php?a=" . $_POST[id]);
        } else if (isset($_POST['painikepiilota'])) {
            $db->query("update aanestykset set nakyvissa=0 where id = '" . $_POST[id] . "'");
            header("location: aanestykset.php?a=" . $_POST[id]);
        }
    }
} else {
    $url = $_SERVER[REQUEST_URI];
    $url = substr($url, 1);
    $url = strtok($url, '?');
    header("location: kirjautuminenuusi.php?url=" . $url);
exit();
}



include("footer.php");
?>

</body>
</html>			