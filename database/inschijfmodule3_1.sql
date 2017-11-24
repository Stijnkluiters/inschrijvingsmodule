-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema inschrijfmodule2
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema inschrijfmodule2
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `inschrijfmodule2` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `inschrijfmodule2` ;

-- -----------------------------------------------------
-- Table `inschrijfmodule2`.`soort`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `inschrijfmodule2`.`soort` (
  `soort` VARCHAR(255) NOT NULL COMMENT '',
  `benodigdheid` VARCHAR(255) NOT NULL COMMENT '',
  PRIMARY KEY (`soort`)  COMMENT '')
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `inschrijfmodule2`.`rolnaam`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `inschrijfmodule2`.`rolnaam` (
  `rolid` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `rolnaam` VARCHAR(255) NOT NULL COMMENT '',
  PRIMARY KEY (`rolid`)  COMMENT '')
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `inschrijfmodule2`.`account`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `inschrijfmodule2`.`account` (
  `account_id` INT(11) NOT NULL COMMENT '',
  `gebruikersnaam` VARCHAR(255) NOT NULL COMMENT '',
  `wachtwoord` VARCHAR(255) NOT NULL COMMENT '',
  `rol_id` INT NOT NULL COMMENT '',
  PRIMARY KEY (`account_id`)  COMMENT '',
  INDEX `fk_Account_rolnaam1_idx` (`rol_id` ASC)  COMMENT '',
  CONSTRAINT `fk_Account_rolnaam1`
    FOREIGN KEY (`rol_id`)
    REFERENCES `inschrijfmodule2`.`rolnaam` (`rolid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `inschrijfmodule2`.`evenement`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `inschrijfmodule2`.`evenement` (
  `evenement_id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `account_id` INT NOT NULL COMMENT '',
  `titel` VARCHAR(255) NOT NULL COMMENT '',
  `datum` DATETIME NOT NULL COMMENT '',
  `begintijd` DATETIME NOT NULL COMMENT '',
  `eindtijd` VARCHAR(255) NOT NULL COMMENT '',
  `onderwerp` VARCHAR(255) NOT NULL COMMENT '',
  `omschrijving` VARCHAR(255) NOT NULL COMMENT '',
  `vervoer` VARCHAR(255) NULL COMMENT '',
  `min_leerlingen` INT NULL COMMENT '',
  `max_leerlingen` INT NULL COMMENT '',
  `comment` VARCHAR(255) NULL COMMENT '',
  `locatie` VARCHAR(255) NOT NULL COMMENT '',
  `lokaalnummer` VARCHAR(255) NULL COMMENT '',
  `soort` VARCHAR(255) NOT NULL COMMENT '',
  `contactnr` INT NULL COMMENT '',
  PRIMARY KEY (`evenement_id`)  COMMENT '',
  INDEX `fk_evenement_Soort1_idx` (`soort` ASC)  COMMENT '',
  INDEX `fk_evenement_account1_idx` (`account_id` ASC)  COMMENT '',
  CONSTRAINT `fk_evenement_Soort1`
    FOREIGN KEY (`soort`)
    REFERENCES `inschrijfmodule2`.`soort` (`soort`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_evenement_account1`
    FOREIGN KEY (`account_id`)
    REFERENCES `inschrijfmodule2`.`account` (`account_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
ROW_FORMAT = REDUNDANT;


-- -----------------------------------------------------
-- Table `inschrijfmodule2`.`leerling`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `inschrijfmodule2`.`leerling` (
  `leerlingnummer` INT(11) NOT NULL COMMENT '',
  `geslacht` VARCHAR(255) NULL COMMENT '',
  `roepnaam` VARCHAR(255) NOT NULL COMMENT '',
  `tussenvoegsel` VARCHAR(255) NOT NULL COMMENT '',
  `achternaam` VARCHAR(255) NOT NULL COMMENT '',
  `opleiding` VARCHAR(255) NOT NULL COMMENT '',
  `geboortedatum` DATETIME NULL COMMENT '',
  `postcode` VARCHAR(255) NULL COMMENT '',
  `plaats` VARCHAR(255) NULL COMMENT '',
  `begindatum` DATETIME NOT NULL COMMENT '',
  `einddatum` DATETIME NULL COMMENT '',
  `plaatsing` TINYINT(1) NULL COMMENT '',
  `LWOO` TINYINT(1) NULL COMMENT '',
  `LGF` TINYINT(1) NULL COMMENT '',
  `groepscode` VARCHAR(255) NOT NULL COMMENT '',
  PRIMARY KEY (`leerlingnummer`)  COMMENT '')
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `inschrijfmodule2`.`inschrijving`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `inschrijfmodule2`.`inschrijving` (
  `leerlingnummer` INT NOT NULL COMMENT '',
  `evenement_id` INT NOT NULL COMMENT '',
  `present` TINYINT(1) NULL COMMENT '',
  `datum` DATETIME NOT NULL COMMENT '',
  `comment` VARCHAR(255) NULL COMMENT '',
  PRIMARY KEY (`evenement_id`, `leerlingnummer`)  COMMENT '',
  INDEX `fk_inschrijving_evenement1_idx` (`evenement_id` ASC)  COMMENT '',
  INDEX `fk_inschrijving_leerling1_idx` (`leerlingnummer` ASC)  COMMENT '',
  CONSTRAINT `fk_inschrijving_evenement1`
    FOREIGN KEY (`evenement_id`)
    REFERENCES `inschrijfmodule2`.`evenement` (`evenement_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_inschrijving_leerling1`
    FOREIGN KEY (`leerlingnummer`)
    REFERENCES `inschrijfmodule2`.`leerling` (`leerlingnummer`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `inschrijfmodule2`.`medewerker`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `inschrijfmodule2`.`medewerker` (
  `afkorting` VARCHAR(255) NOT NULL COMMENT '',
  `roepnaam` VARCHAR(255) NOT NULL COMMENT '',
  `tussenvoegsel` VARCHAR(255) NOT NULL COMMENT '',
  `achternaam` VARCHAR(255) NOT NULL COMMENT '',
  `functie` VARCHAR(255) NULL COMMENT '',
  `geslacht` VARCHAR(255) NOT NULL COMMENT '',
  `geboortedatum` DATETIME NOT NULL COMMENT '',
  `locatie` VARCHAR(255) NOT NULL COMMENT '',
  `telefoon` INT NOT NULL COMMENT '',
  PRIMARY KEY (`afkorting`)  COMMENT '')
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `inschrijfmodule2`.`invertarisatie`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `inschrijfmodule2`.`invertarisatie` (
  `invertarisatie_id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `vakgebied` LONGTEXT NULL COMMENT '',
  `onderwerp` LONGTEXT NULL COMMENT '',
  `aantal_gastcollege` MEDIUMTEXT NULL COMMENT '',
  `voorkeur_dag` MEDIUMTEXT NULL COMMENT '',
  `voorkeur_dagdeel` MEDIUMTEXT NULL COMMENT '',
  `hulpmiddel` MEDIUMTEXT NULL COMMENT '',
  `doelstelling` MEDIUMTEXT NULL COMMENT '',
  `verwachting` MEDIUMTEXT NULL COMMENT '',
  PRIMARY KEY (`invertarisatie_id`)  COMMENT '')
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `inschrijfmodule2`.`bedrijf`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `inschrijfmodule2`.`bedrijf` (
  `bedrijf_id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `inventarisatie_id` INT NOT NULL COMMENT '',
  `bedrijfnaam` VARCHAR(255) NOT NULL COMMENT '',
  `branche` VARCHAR(255) NOT NULL COMMENT '',
  `webadres` VARCHAR(255) NULL COMMENT '',
  `adres` VARCHAR(255) NOT NULL COMMENT '',
  `postcode` VARCHAR(255) NOT NULL COMMENT '',
  `plaatsnaam` VARCHAR(255) NOT NULL COMMENT '',
  `contactnr` INT NOT NULL COMMENT '',
  INDEX `fk_bedrijf_invertarisatie1_idx` (`inventarisatie_id` ASC)  COMMENT '',
  PRIMARY KEY (`bedrijf_id`)  COMMENT '',
  CONSTRAINT `fk_bedrijf_invertarisatie1`
    FOREIGN KEY (`inventarisatie_id`)
    REFERENCES `inschrijfmodule2`.`invertarisatie` (`invertarisatie_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `inschrijfmodule2`.`contactpersoon`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `inschrijfmodule2`.`contactpersoon` (
  `contact_id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `bedrijf_id` INT NOT NULL COMMENT '',
  `roepnaam` VARCHAR(255) NOT NULL COMMENT '',
  `tussenvoegsel` VARCHAR(255) NOT NULL COMMENT '',
  `achternaam` VARCHAR(255) NOT NULL COMMENT '',
  `functie` VARCHAR(255) NOT NULL COMMENT '',
  `telefoonnr.` VARCHAR(255) NOT NULL COMMENT '',
  `email-adres` VARCHAR(255) NOT NULL COMMENT '',
  `inventarisatie_id` INT NOT NULL COMMENT '',
  PRIMARY KEY (`contact_id`)  COMMENT '',
  INDEX `fk_contactpersoon_bedrijf1_idx` (`bedrijf_id` ASC)  COMMENT '',
  CONSTRAINT `fk_contactpersoon_bedrijf1`
    FOREIGN KEY (`bedrijf_id`)
    REFERENCES `inschrijfmodule2`.`bedrijf` (`bedrijf_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `inschrijfmodule2`.`antwoord`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `inschrijfmodule2`.`antwoord` (
  `antwoord_id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `antwoord` VARCHAR(255) NOT NULL COMMENT '',
  PRIMARY KEY (`antwoord_id`)  COMMENT '')
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `inschrijfmodule2`.`vragen`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `inschrijfmodule2`.`vragen` (
  `vraag_id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `vraag` VARCHAR(255) NOT NULL COMMENT '',
  `antwoord_id` INT NOT NULL COMMENT '',
  PRIMARY KEY (`vraag_id`)  COMMENT '',
  INDEX `fk_vragen_antwoord1_idx` (`antwoord_id` ASC)  COMMENT '',
  CONSTRAINT `fk_vragen_antwoord1`
    FOREIGN KEY (`antwoord_id`)
    REFERENCES `inschrijfmodule2`.`antwoord` (`antwoord_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `inschrijfmodule2`.`vragenlijst`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `inschrijfmodule2`.`vragenlijst` (
  `lijst` VARCHAR(255) NOT NULL COMMENT '',
  `soort` VARCHAR(255) NOT NULL COMMENT '',
  `vraag_id` INT NOT NULL COMMENT '',
  PRIMARY KEY (`lijst`)  COMMENT '',
  INDEX `fk_vragenlijst_soort1_idx` (`soort` ASC)  COMMENT '',
  INDEX `fk_vragenlijst_vragen1_idx` (`vraag_id` ASC)  COMMENT '',
  CONSTRAINT `fk_vragenlijst_soort1`
    FOREIGN KEY (`soort`)
    REFERENCES `inschrijfmodule2`.`soort` (`soort`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_vragenlijst_vragen1`
    FOREIGN KEY (`vraag_id`)
    REFERENCES `inschrijfmodule2`.`vragen` (`vraag_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `inschrijfmodule2`.`rechten`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `inschrijfmodule2`.`rechten` (
  `rolnaam` VARCHAR(255) NOT NULL COMMENT '',
  `aanmaken_evenement` TINYINT(1) NOT NULL COMMENT '',
  `wijzigen_evenement` TINYINT(1) NOT NULL COMMENT '',
  `bekijken_evenement` TINYINT(1) NOT NULL COMMENT '',
  `bekijken_persoonsgegevens` TINYINT(1) NOT NULL COMMENT '',
  `wijzigen_persoonsgegevens` TINYINT(1) NOT NULL COMMENT '',
  `verwijderen_persoonsgegevens` TINYINT(1) NOT NULL COMMENT '',
  `inschrijven_leerling` TINYINT(1) NOT NULL COMMENT '',
  `goedkeuren_aanmelding` TINYINT(1) NOT NULL COMMENT '',
  `whitelisten_leerling` TINYINT(1) NOT NULL COMMENT '',
  `rolid` INT NOT NULL COMMENT '',
  PRIMARY KEY (`rolnaam`)  COMMENT '',
  INDEX `fk_rechten_rolnaam1_idx` (`rolid` ASC)  COMMENT '',
  CONSTRAINT `fk_rechten_rolnaam1`
    FOREIGN KEY (`rolid`)
    REFERENCES `inschrijfmodule2`.`rolnaam` (`rolid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `inschrijfmodule2`.`foto`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `inschrijfmodule2`.`foto` (
  `foto_id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `fotonaam` VARCHAR(255) NOT NULL COMMENT '',
  `evenement_id` INT NOT NULL COMMENT '',
  `comment` VARCHAR(255) NULL COMMENT '',
  PRIMARY KEY (`foto_id`)  COMMENT '',
  INDEX `fk_foto_evenement1_idx` (`evenement_id` ASC)  COMMENT '',
  CONSTRAINT `fk_foto_evenement1`
    FOREIGN KEY (`evenement_id`)
    REFERENCES `inschrijfmodule2`.`evenement` (`evenement_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `inschrijfmodule2`.`medewerker_heeft_account`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `inschrijfmodule2`.`medewerker_heeft_account` (
  `medewerker_afkorting` VARCHAR(255) NOT NULL COMMENT '',
  `account_id` INT NOT NULL COMMENT '',
  PRIMARY KEY (`medewerker_afkorting`, `account_id`)  COMMENT '',
  INDEX `fk_medewerker_has_account_account1_idx` (`account_id` ASC)  COMMENT '',
  INDEX `fk_medewerker_has_account_medewerker1_idx` (`medewerker_afkorting` ASC)  COMMENT '',
  CONSTRAINT `fk_medewerker_has_account_medewerker1`
    FOREIGN KEY (`medewerker_afkorting`)
    REFERENCES `inschrijfmodule2`.`medewerker` (`afkorting`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_medewerker_has_account_account1`
    FOREIGN KEY (`account_id`)
    REFERENCES `inschrijfmodule2`.`account` (`account_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `inschrijfmodule2`.`contactpersoon_heeft_account`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `inschrijfmodule2`.`contactpersoon_heeft_account` (
  `contactpersoon_contact_id` INT NOT NULL COMMENT '',
  `account_account_id` INT NOT NULL COMMENT '',
  PRIMARY KEY (`contactpersoon_contact_id`, `account_account_id`)  COMMENT '',
  INDEX `fk_contactpersoon_has_account_account1_idx` (`account_account_id` ASC)  COMMENT '',
  INDEX `fk_contactpersoon_has_account_contactpersoon1_idx` (`contactpersoon_contact_id` ASC)  COMMENT '',
  CONSTRAINT `fk_contactpersoon_has_account_contactpersoon1`
    FOREIGN KEY (`contactpersoon_contact_id`)
    REFERENCES `inschrijfmodule2`.`contactpersoon` (`contact_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_contactpersoon_has_account_account1`
    FOREIGN KEY (`account_account_id`)
    REFERENCES `inschrijfmodule2`.`account` (`account_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `inschrijfmodule2`.`beoordeling`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `inschrijfmodule2`.`beoordeling` (
  `beoordeling_id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `leerlingnummer` INT NOT NULL COMMENT '',
  `evenement_id` INT NOT NULL COMMENT '',
  `score` INT NOT NULL COMMENT '',
  PRIMARY KEY (`beoordeling_id`, `leerlingnummer`, `evenement_id`)  COMMENT '',
  INDEX `fk_leerling_has_evenement_evenement1_idx` (`evenement_id` ASC)  COMMENT '',
  INDEX `fk_leerling_has_evenement_leerling1_idx` (`leerlingnummer` ASC)  COMMENT '',
  CONSTRAINT `fk_leerling_has_evenement_leerling1`
    FOREIGN KEY (`leerlingnummer`)
    REFERENCES `inschrijfmodule2`.`leerling` (`leerlingnummer`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_leerling_has_evenement_evenement1`
    FOREIGN KEY (`evenement_id`)
    REFERENCES `inschrijfmodule2`.`evenement` (`evenement_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `inschrijfmodule2`.`leerling_heeft_account`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `inschrijfmodule2`.`leerling_heeft_account` (
  `leerlingnummer` INT(11) NOT NULL COMMENT '',
  `account_id` INT(11) NOT NULL COMMENT '',
  PRIMARY KEY (`leerlingnummer`, `account_id`)  COMMENT '',
  INDEX `fk_leerling_has_account_account1_idx` (`account_id` ASC)  COMMENT '',
  INDEX `fk_leerling_has_account_leerling1_idx` (`leerlingnummer` ASC)  COMMENT '',
  CONSTRAINT `fk_leerling_has_account_leerling1`
    FOREIGN KEY (`leerlingnummer`)
    REFERENCES `inschrijfmodule2`.`leerling` (`leerlingnummer`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_leerling_has_account_account1`
    FOREIGN KEY (`account_id`)
    REFERENCES `inschrijfmodule2`.`account` (`account_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
