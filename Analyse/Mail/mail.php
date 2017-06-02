<?php

$mail = 'testaudit@mail.fr'; // Déclaration de l'adresse de destination , Doit importer mail depuis DB
{
    $passage_ligne = "\r\n";
}

else
{
    $passage_ligne = "\n";
}
// Déclaration des messages au format texte et au format HTML.
$message_txt = "BOnjour, voici les resultats de l'analyse.";
$message_html = "<html><head></head><body><b>Bonjour</b>, voici les resusltats de l'analyse.</body></html>";

// Lecture et mise en forme de la pièce jointe.
$fichier   = fopen("fichier.csv", "r");
$attachement = fread($fichier, filesize("fichier.csv"));
fclose($fichier);

// Sujet
$sujet = "Resultats Analayse";


// Création du header de l'e-mail.
$header = "From: \"testaudit\"<testaudit@mail.fr>".$passage_ligne;
$header.= "Reply-to: \"testaudit\" <testaudit@mail.fr>".$passage_ligne;
$header.= "MIME-Version: 1.0".$passage_ligne;
$header.= "Content-Type: multipart/mixed;".$passage_ligne .$passage_ligne;

// Création du message.
$message = $passage_ligne."--".$passage_ligne;
$message.= "Content-Type: multipart/alternative;".$passage_ligne .$passage_ligne;
$message.= $passage_ligne."--" .$passage_ligne;

// Ajout du message au format texte.
$message.= "Content-Type: text/plain; charset=\"ISO-8859-1\"".$passage_ligne;
$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
$message.= $passage_ligne.$message_txt.$passage_ligne;

// Ajout de la pièce jointe.
$message.= "Content-Type: Fichierresultats/csv; name=\"fichier.csv\"".$passage_ligne;
$message.= "Content-Transfer-Encoding: base64".$passage_ligne;
$message.= "Content-Disposition: attachment; filename=\"fichier.csv\"".$passage_ligne;
$message.= $passage_ligne.$attachement.$passage_ligne.$passage_ligne;
$message.= $passage_ligne."--" ."--".$passage_ligne;

// Envoi de l'e-mail.
mail($mail,$sujet,$message,$header);

?>
