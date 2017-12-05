<?php
/**
 * Created by PhpStorm.
 * User: Jonas
 * Date: 12/4/2017
 * Time: 18:03
 */
var_dump($_GET);
$stmt = $db->prepare('
                insert into evenement
               (
                evenement_id,
                account_id,
                titel,
                datum,
                begintijd,
                eindtijd,
                onderwerp,
                omschrijving,
                vervoer,
                min_leerlingen,
                max_leerlingen,
                locatie,
                lokaalnummer,
                soort,
                contactnr)
                VALUES 
                (
                  :evenement_id,
                  :account_id,
                  :titel,
                  :datum,
                  :begintijd,
                  :eindtijd,
                  :onderwerp,
                  :omschrijving,
                  :vervoer,
                  :min_leerlingen,
                  :max_leerlingen,
                  :locatie,
                  :lokaalnummer,
                  :soort,
                  :contactnr
                )
            ');
$stmt->bindParam('account_id',$account_id);
$stmt->bindParam('titel',$titel);
$stmt->bindParam('datum', $datum);
$stmt->bindParam('begintijd', $begintijd, PDO::PARAM_STR);
$stmt->bindParam('eindtijd', $eindtijd, PDO::PARAM_STR);
$stmt->bindParam('onderwerp', $functie, PDO::PARAM_STR);
$stmt->bindParam('geslacht', $geslacht, PDO::PARAM_STR);
$stmt->bindParam('geboortedatum', $geboortedatum, PDO::PARAM_STR);
$stmt->bindParam('locatie', $locatie, PDO::PARAM_STR);
$stmt->bindParam('telefoon', $telefoonummer, PDO::PARAM_STR);
$stmt->execute();
