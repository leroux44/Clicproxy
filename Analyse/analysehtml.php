<?php
// Fonction du simulateur de robot
// 1. URL à tester
// 2. Activer ou non la colorisation du code (true/false)
// 3. Numéroter ou non les lignes de codes (true/false)
function auditclicproxy($page = '', $colorisation = false, $numerotation = false) {

	// Activation cURL
    $url = curl_init($page);

    curl_setopt($url, CURLOPT_HEADER, true);
    curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($url, CURLOPT_SSL_VERIFYPEER, false);
	
	// Récupération contenu
	$contenu = curl_exec($url);
	$code = '';

	// Fermeture cURL
	curl_close($url);

	$lignes = explode("\n", $contenu);

	foreach($lignes as $num => $ligne) {
		// Affichage optionnel du numéro de ligne
		if($numerotation == true) {
			$code .= $num.". ";
		}
		
		// Affichage balises HTML
		$ligne = htmlspecialchars($ligne);
		
		// Si color active
		if($colorisation == true) {
			// Color des attributs
			$regex = "#(.*)([a-zA-Z0-9:-]+)(=(&quot;|&apos;))#iU";
			$replace = "$1<span style='color:#FB5758'>$2</span>$3";
			$ligne = preg_replace($regex, $replace, $ligne);

			// Color valeurs d'attributs
			$regex = "#(=)((&quot;|&apos;).*(&quot;|&apos;))#iU";
			$replace = "$1<span style='color:#999'>$2</span>";
			$ligne = preg_replace($regex, $replace, $ligne);

			
			// Color balises
			$regex = "#(&lt;/?[a-zA-Z0-9]+[ ])#iU"; //
			$replace = "<span style='color:#0089E2'>$1</span>";
			$ligne = preg_replace($regex, $replace, $ligne);
			$regex = "#(&lt;/?[a-zA-Z0-9]+/?&gt;)#iU";
			$replace = "<span style='color:#0089E2'>$1</span>";
			$ligne = preg_replace($regex, $replace, $ligne);
			$regex = "#(/?&gt;)#iU";
			$replace = "<span style='color:#0089E2'>$1</span>";
			$ligne = preg_replace($regex, $replace, $ligne);
		}
		
		$code .= $ligne;
		$code .= "<br/>\n";
	}
	
	// affichage UTF-8
	preg_match("#charset=['\"]?([a-zA-Z0-9-]+)['\"]?[^a-zA-Z0-9-]#iU", $code, $result);
	if(!empty($result)) {
		$encodage = strtolower($result[1]);
		if($encodage != 'utf-8') {
			$code = mb_convert_encoding($code, "UTF-8", $encodage);
		}
	}

	// Affiche code source complet
	echo $code;
}
?>

<!DOCTYPE html>
<html>
    <head>
    <meta charset="utf-8"/>
    <title>Analyse Clicproxy</title>
        <link rel="stylesheet" href="style.css">

</head>

    <body>

        <div id="formulaire">

            <h1>Analyse Clicproxy</h1>

            <form method="post">
                <div class="bloc">
                    <input type="text" name="url" value="<?php if(isset($_POST['url'])) { echo $_POST['url']; } ?>"/>
                    <label for="url">URL</label>
                </div>

                <div class="bloc">
                    <select name="col">
                        <option value="0"
                                <?php if(isset($_POST['col']) && $_POST['col'] == 0) { echo 'selected="selected"'; } ?>>Non</option>
                        <option value="1"
                                <?php if(isset($_POST['col']) && $_POST['col'] == 1) { echo 'selected="selected"'; } ?>>Oui</option>
                    </select>
                    <label for="col">Colorisation du code</label>
                </div>

                <div class="bloc">
                    <select name="num">
                        <option value="0" <?php if(isset($_POST['num']) && $_POST['num'] == 0) { echo 'selected="selected"'; } ?>>Non</option>
                        <option value="1" <?php if(isset($_POST['num']) && $_POST['num'] == 1) { echo 'selected="selected"'; } ?>>Oui</option>
                    </select>
                    <label for="num">Numérotation des lignes</label>
                </div>

                <p id="bouton"><input type="submit" name="submit" value="Tester"/></p>
            </form>
        </div>

        <div id="resultat">

            <?php if(isset($_POST['submit']) && !empty($_POST['url'])) {	?>

            <?php // Traitement des données
                $url = htmlspecialchars($_POST['url']);
                $col = htmlspecialchars($_POST['col']);
                $num = htmlspecialchars($_POST['num']);
            ?>
            <h2>Code HTML : <?php echo $url; ?></h2>
            <p><?php auditclicproxy($url, $col, $num); ?></p>
            <?php } ?>

        </div>
    </body>
</html>