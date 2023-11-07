<?php
//Definir le fuseau horaire d'Europe/Paris//
date_default_timezone_set('Europe/Paris');

//Présentation des règles//
echo "Bienvenue dans le jeu de devinette !";
echo PHP_EOL;
echo "Le but du jeu est de deviner un nombre entre 1 et 100.";
echo PHP_EOL;
echo "Vous avez 10 à 15 essais pour trouver le nombre.";
echo PHP_EOL;
echo "Bonne chance!";
echo PHP_EOL;
//Petit espace pour aérer le texte //
echo PHP_EOL;

//Menu//
while (true) { //While qui est utilisé sur tout le program ce qui permet de relancer le menu ci-dessous
echo "1. Jouer";
echo PHP_EOL;
echo "2. Voir les résultats";
echo PHP_EOL;
echo "3. Voir les résultats d'un jour";
echo PHP_EOL;
echo "4. Voir les résultats d'un joueur";
echo PHP_EOL;
echo "5. Quitter";
echo PHP_EOL;

//Variable//
$choix = (readline("Faites votre choix : ")); //Demander à l'utilisateur de faire un choix
$nombre_aleatoire = random_int(1,100);  //Nombre aléatoire entre 1 et 100
$date = date('d/m/Y H:i:s'); //date : jour/mois/année sous 4caractères - 24h/minutes/secondes

//Choix 1//
    if ($choix == 1) {
        // Début de partie & Variables
        $pseudo = readline("Saisir un pseudo : ");
        $tentative = random_int(10, 15); //Le nombre de tentatives maximum en une partie
        $nombreTentatives = 0; // Le nombre de tentatives utiliser stocker en variable

//Boucle while principal//
        while ($nombreTentatives < $tentative) {
            $nombre = readline("Saisir un nombre entre 1 et 100 : ");
            if ($nombre < 1 || $nombre > 100) {
                echo "Veuillez saisir un nombre entre 1 et 100.\n";
            } elseif ($nombre < $nombre_aleatoire) {
                echo "Le nombre est trop petit\n";
                $nombreTentatives++;
            } elseif ($nombre > $nombre_aleatoire) {
                echo "Le nombre est trop grand\n";
                $nombreTentatives++;
            } else {
                $nombreTentatives++; // Augmente le nombre de tentatives de 1
                break;
            }
        }
//Message pour le nombre de tentatives utilisé//
        if ($nombreTentatives >= $tentative) { //Si le nombre de tentatives effectuées est >= au nombre de tentatives donné random alors Perdu
            echo "Désolé $pseudo : vous avez atteint le nombre maximum de tentatives ! Le nombre était $nombre_aleatoire\n";
            $resultatMessage = "Désolé $pseudo : vous avez atteint le nombre maximum de tentatives ! Le nombre était $nombre_aleatoire";
        } elseif ($nombreTentatives <= 10) {
            echo "Excellent $pseudo : vous avez trouvé le nombre $nombre_aleatoire en " . $nombreTentatives . " tentatives!\n";
            $resultatMessage = "Excellent $pseudo : vous avez trouvé le nombre $nombre_aleatoire en " . $nombreTentatives . " tentatives!";
        } else {
            echo "Bien joué $pseudo : vous avez trouvé le nombre $nombre_aleatoire en " . $nombreTentatives . " tentatives!\n";
            $resultatMessage = "Bien joué $pseudo : vous avez trouvé le nombre $nombre_aleatoire en " . $nombreTentatives . " tentatives!";
        }

//Sauvegarde du Fichier resultats.txt//
        $fichier = fopen("resultats.txt", "a");

        if ($fichier) {
            $resultat = ($nombreTentatives >= $tentative) ? "Perdu" : "Gagné"; //savoir si c'est perdu ou gagné et l'écrire dans la ligne $ligne en dessous

            $ligne =  "$date - Pseudo: $pseudo - Résultat: $resultat - Message: $resultatMessage\n"; //ecrire la ligne

            if (fwrite($fichier, $ligne)) {
                echo "Résultats enregistrés dans resultats.txt.\n"; //confirmer que les résultats ont bien été enregistré 
            } else {
                echo "Erreur lors de l'enregistrement des résultats.\n"; //confimer que ça n'a pas été enregistré 
            }

            fclose($fichier); //Fermer le fichier Proprement
        } else {
            echo "Erreur lors de l'ouverture du fichier pour l'enregistrement des résultats.\n"; //Dire que le fichier n'a pas pu etre ouvert
        }
//Choix 2//
    } elseif ($choix == 2) { 

        // Ouvrir le fichier//
        $fichier = fopen("resultats.txt", "r");
        if ($fichier) {
            // Lire et afficher les résultats
            while (!feof($fichier)) {
                $ligne = fgets($fichier);
                echo $ligne;//afficher la ligne rechercher
            }
            fclose($fichier); // Fermer le fichier Proprement
        } else {
            echo "Erreur lors de l'ouverture du fichier des résultats.\n";
        }
//Choix 3//
    } elseif ($choix == 3) {

        $dateRecherchee = readline("Saisir la date au format (jj/mm/aaaa) : ");
        $trouve = false; // vérifier si des résultats ont été trouvé

        $fichier = fopen("resultats.txt", "r"); // ouvrir le fichier

        if ($fichier) {
            //afficher les résultats de la journée qu'on recherche
            while (!feof($fichier)) {
                $ligne = fgets($fichier);
                if (str_contains($ligne, $dateRecherchee)) {
                    echo $ligne; //afficher la ligne rechercher
                    $trouve = true;
                }
            }
            fclose($fichier);  //fermer le fichier proprement
        }

        if (!$trouve) { //Si ce n'est pas trouvé afficher ce message :
            echo "Aucun résultat trouvé pour la date $dateRecherchee.\n";
        }
    } elseif ($choix == 4) {

        $pseudoRecherche = readline("Saisir le pseudo du joueur : ");
        $trouve = false; // Variable pour vérifier si des résultats ont été trouvés

        // Ouvrir le fichier pour lecture
        $fichier = fopen("resultats.txt", "r");

        if ($fichier) {
            // Parcourir le fichier et afficher les résultats du joueur recherché
            while (!feof($fichier)) {
                $ligne = fgets($fichier);
                if (str_contains($ligne, "Pseudo: $pseudoRecherche")) { //prend les lignes où il y a noté "Pseudo + le pseudo qu'on cherche
                    echo $ligne;  //afficher la ligne rechercher
                    $trouve = true;
                }
            }
            fclose($fichier); //Fermer le fichier proprement
        }
        if (!$trouve) { //Si aucun pseudo trouvé, dire que rien n'a été trouvé
            echo "Aucun résultat trouvé pour le joueur $pseudoRecherche.\n";
        }
    } elseif ($choix == 5) { //Choix 5
        echo "À bientôt ! ;)\n";
        break; // arreter le programme
    } else {
        echo "Choix invalide, veuillez choisir un chiffre entre 1 et 5.\n";  //Si aucun des 5 chiffres a été écrit, dire une erreur
    }
}