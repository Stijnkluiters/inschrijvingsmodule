-- MySQL Script generated by MySQL Workbench
-- 11/30/17 12:57:41
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema inschrijfmodule
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema inschrijfmodule
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `inschrijfmodule` DEFAULT CHARACTER SET utf8 ;
USE `inschrijfmodule` ;

-- -----------------------------------------------------
-- Table `inschrijfmodule`.`soort`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `inschrijfmodule`.`soort` ;

CREATE TABLE IF NOT EXISTS `inschrijfmodule`.`soort` (
  `soort` VARCHAR(255) NOT NULL,
  `benodigdheid` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`soort`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `inschrijfmodule`.`rolnaam`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `inschrijfmodule`.`rolnaam` ;

CREATE TABLE IF NOT EXISTS `inschrijfmodule`.`rolnaam` (
  `rolid` INT NOT NULL AUTO_INCREMENT,
  `rolnaam` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`rolid`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `inschrijfmodule`.`account`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `inschrijfmodule`.`account` ;

CREATE TABLE IF NOT EXISTS `inschrijfmodule`.`account` (
  `account_id` INT(11) NOT NULL AUTO_INCREMENT,
  `gebruikersnaam` VARCHAR(255) NOT NULL,
  `wachtwoord` VARCHAR(255) NOT NULL,
  `rol_id` INT NOT NULL,
  PRIMARY KEY (`account_id`),
  CONSTRAINT `fk_Account_rolnaam1`
    FOREIGN KEY (`rol_id`)
    REFERENCES `inschrijfmodule`.`rolnaam` (`rolid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_Account_rolnaam1_idx` ON `inschrijfmodule`.`account` (`rol_id` ASC);


-- -----------------------------------------------------
-- Table `inschrijfmodule`.`evenement`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `inschrijfmodule`.`evenement` ;

CREATE TABLE IF NOT EXISTS `inschrijfmodule`.`evenement` (
  `evenement_id` INT NOT NULL AUTO_INCREMENT,
  `account_id` INT NOT NULL,
  `titel` VARCHAR(255) NOT NULL,
  `datum` DATE NOT NULL,
  `begintijd` TIME NOT NULL,
  `eindtijd` VARCHAR(255) NOT NULL,
  `onderwerp` VARCHAR(255) NOT NULL,
  `omschrijving` VARCHAR(255) NOT NULL,
  `vervoer` VARCHAR(255) NULL,
  `min_leerlingen` INT NULL,
  `max_leerlingen` INT NULL,
  `comment` VARCHAR(255) NULL,
  `locatie` VARCHAR(255) NOT NULL,
  `lokaalnummer` VARCHAR(255) NULL,
  `soort` VARCHAR(255) NOT NULL,
  `contactnr` INT NULL,
  PRIMARY KEY (`evenement_id`),
  CONSTRAINT `fk_evenement_Soort1`
    FOREIGN KEY (`soort`)
    REFERENCES `inschrijfmodule`.`soort` (`soort`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_evenement_account1`
    FOREIGN KEY (`account_id`)
    REFERENCES `inschrijfmodule`.`account` (`account_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
ROW_FORMAT = REDUNDANT;

CREATE INDEX `fk_evenement_Soort1_idx` ON `inschrijfmodule`.`evenement` (`soort` ASC);

CREATE INDEX `fk_evenement_account1_idx` ON `inschrijfmodule`.`evenement` (`account_id` ASC);


-- -----------------------------------------------------
-- Table `inschrijfmodule`.`leerling`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `inschrijfmodule`.`leerling` ;

CREATE TABLE IF NOT EXISTS `inschrijfmodule`.`leerling` (
  `leerlingnummer` INT(11) NOT NULL,
  `account_id` INT(11) NOT NULL,
  `geslacht` VARCHAR(255) NULL,
  `roepnaam` VARCHAR(255) NOT NULL,
  `tussenvoegsel` VARCHAR(255) NULL,
  `achternaam` VARCHAR(255) NOT NULL,
  `opleiding` VARCHAR(255) NOT NULL,
  `geboortedatum` DATETIME NULL,
  `postcode` VARCHAR(255) NULL,
  `plaats` VARCHAR(255) NULL,
  `begindatum` DATETIME NOT NULL,
  `einddatum` DATETIME NULL,
  `plaatsing` TINYINT(1) NULL,
  `LWOO` TINYINT(1) NULL,
  `LGF` TINYINT(1) NULL,
  `groepscode` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`leerlingnummer`),
  CONSTRAINT `fk_leerling_account1`
    FOREIGN KEY (`account_id`)
    REFERENCES `inschrijfmodule`.`account` (`account_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_leerling_account1_idx` ON `inschrijfmodule`.`leerling` (`account_id` ASC);


-- -----------------------------------------------------
-- Table `inschrijfmodule`.`inschrijving`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `inschrijfmodule`.`inschrijving` ;

CREATE TABLE IF NOT EXISTS `inschrijfmodule`.`inschrijving` (
  `leerlingnummer` INT NOT NULL,
  `evenement_id` INT NOT NULL,
  `present` TINYINT(1) NULL,
  `datum` DATETIME NOT NULL,
  `comment` VARCHAR(255) NULL,
  `toestemming` TINYINT(1) NOT NULL,
  PRIMARY KEY (`evenement_id`, `leerlingnummer`),
  CONSTRAINT `fk_inschrijving_evenement1`
    FOREIGN KEY (`evenement_id`)
    REFERENCES `inschrijfmodule`.`evenement` (`evenement_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_inschrijving_leerling1`
    FOREIGN KEY (`leerlingnummer`)
    REFERENCES `inschrijfmodule`.`leerling` (`leerlingnummer`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_inschrijving_evenement1_idx` ON `inschrijfmodule`.`inschrijving` (`evenement_id` ASC);

CREATE INDEX `fk_inschrijving_leerling1_idx` ON `inschrijfmodule`.`inschrijving` (`leerlingnummer` ASC);


-- -----------------------------------------------------
-- Table `inschrijfmodule`.`medewerker`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `inschrijfmodule`.`medewerker` ;

CREATE TABLE IF NOT EXISTS `inschrijfmodule`.`medewerker` (
  `afkorting` VARCHAR(255) NOT NULL,
  `account_id` INT(11) NOT NULL,
  `roepnaam` VARCHAR(255) NOT NULL,
  `tussenvoegsel` VARCHAR(255)   NULL,
  `achternaam` VARCHAR(255) NOT NULL,
  `functie` VARCHAR(255) NULL,
  `geslacht` VARCHAR(255) NOT NULL,
  `geboortedatum` DATETIME NOT NULL,
  `locatie` VARCHAR(255) NULL,
  `telefoon` VARCHAR(255) NOT NULL,
  `deleted` TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`afkorting`),
  CONSTRAINT `fk_medewerker_account1`
    FOREIGN KEY (`account_id`)
    REFERENCES `inschrijfmodule`.`account` (`account_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_medewerker_account1_idx` ON `inschrijfmodule`.`medewerker` (`account_id` ASC);


-- -----------------------------------------------------
-- Table `inschrijfmodule`.`invertarisatie`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `inschrijfmodule`.`invertarisatie` ;

CREATE TABLE IF NOT EXISTS `inschrijfmodule`.`invertarisatie` (
  `invertarisatie_id` INT NOT NULL AUTO_INCREMENT,
  `vakgebied` LONGTEXT NULL,
  `onderwerp` LONGTEXT NULL,
  `aantal_gastcollege` MEDIUMTEXT NULL,
  `voorkeur_dag` MEDIUMTEXT NULL,
  `voorkeur_dagdeel` MEDIUMTEXT NULL,
  `hulpmiddel` MEDIUMTEXT NULL,
  `doelstelling` MEDIUMTEXT NULL,
  `verwachting` MEDIUMTEXT NULL,
  PRIMARY KEY (`invertarisatie_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `inschrijfmodule`.`bedrijf`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `inschrijfmodule`.`bedrijf` ;

CREATE TABLE IF NOT EXISTS `inschrijfmodule`.`bedrijf` (
  `bedrijf_id` INT NOT NULL AUTO_INCREMENT,
  `inventarisatie_id` INT NOT NULL,
  `bedrijfnaam` VARCHAR(255) NOT NULL,
  `branche` VARCHAR(255) NOT NULL,
  `webadres` VARCHAR(255) NULL,
  `adres` VARCHAR(255) NOT NULL,
  `postcode` VARCHAR(255) NOT NULL,
  `plaatsnaam` VARCHAR(255) NOT NULL,
  `contactnr` INT NOT NULL,
  PRIMARY KEY (`bedrijf_id`),
  CONSTRAINT `fk_bedrijf_invertarisatie1`
    FOREIGN KEY (`inventarisatie_id`)
    REFERENCES `inschrijfmodule`.`invertarisatie` (`invertarisatie_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_bedrijf_invertarisatie1_idx` ON `inschrijfmodule`.`bedrijf` (`inventarisatie_id` ASC);


-- -----------------------------------------------------
-- Table `inschrijfmodule`.`contactpersoon`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `inschrijfmodule`.`contactpersoon` ;

CREATE TABLE IF NOT EXISTS `inschrijfmodule`.`contactpersoon` (
  `contact_id` INT NOT NULL AUTO_INCREMENT,
  `account_id` INT(11) NOT NULL,
  `bedrijf_id` INT NOT NULL,
  `roepnaam` VARCHAR(255) NOT NULL,
  `tussenvoegsel` VARCHAR(255) NULL,
  `achternaam` VARCHAR(255) NOT NULL,
  `functie` VARCHAR(255) NOT NULL,
  `telefoonnummer` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `inventarisatie_id` INT NOT NULL,
  `deleted` TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`contact_id`),
  CONSTRAINT `fk_contactpersoon_bedrijf1`
    FOREIGN KEY (`bedrijf_id`)
    REFERENCES `inschrijfmodule`.`bedrijf` (`bedrijf_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_contactpersoon_account1`
    FOREIGN KEY (`account_id`)
    REFERENCES `inschrijfmodule`.`account` (`account_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_contactpersoon_bedrijf1_idx` ON `inschrijfmodule`.`contactpersoon` (`bedrijf_id` ASC);

CREATE INDEX `fk_contactpersoon_account1_idx` ON `inschrijfmodule`.`contactpersoon` (`account_id` ASC);


-- -----------------------------------------------------
-- Table `inschrijfmodule`.`antwoord`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `inschrijfmodule`.`antwoord` ;

CREATE TABLE IF NOT EXISTS `inschrijfmodule`.`antwoord` (
  `antwoord_id` INT NOT NULL AUTO_INCREMENT,
  `antwoord` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`antwoord_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `inschrijfmodule`.`vragen`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `inschrijfmodule`.`vragen` ;

CREATE TABLE IF NOT EXISTS `inschrijfmodule`.`vragen` (
  `vraag_id` INT NOT NULL AUTO_INCREMENT,
  `vraag` VARCHAR(255) NOT NULL,
  `antwoord_id` INT NOT NULL,
  PRIMARY KEY (`vraag_id`),
  CONSTRAINT `fk_vragen_antwoord1`
    FOREIGN KEY (`antwoord_id`)
    REFERENCES `inschrijfmodule`.`antwoord` (`antwoord_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_vragen_antwoord1_idx` ON `inschrijfmodule`.`vragen` (`antwoord_id` ASC);


-- -----------------------------------------------------
-- Table `inschrijfmodule`.`vragenlijst`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `inschrijfmodule`.`vragenlijst` ;

CREATE TABLE IF NOT EXISTS `inschrijfmodule`.`vragenlijst` (
  `lijst` VARCHAR(255) NOT NULL,
  `soort` VARCHAR(255) NOT NULL,
  `vraag_id` INT NOT NULL,
  PRIMARY KEY (`lijst`),
  CONSTRAINT `fk_vragenlijst_soort1`
    FOREIGN KEY (`soort`)
    REFERENCES `inschrijfmodule`.`soort` (`soort`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_vragenlijst_vragen1`
    FOREIGN KEY (`vraag_id`)
    REFERENCES `inschrijfmodule`.`vragen` (`vraag_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_vragenlijst_soort1_idx` ON `inschrijfmodule`.`vragenlijst` (`soort` ASC);

CREATE INDEX `fk_vragenlijst_vragen1_idx` ON `inschrijfmodule`.`vragenlijst` (`vraag_id` ASC);


-- -----------------------------------------------------
-- Table `inschrijfmodule`.`rechten`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `inschrijfmodule`.`rechten` ;

CREATE TABLE IF NOT EXISTS `inschrijfmodule`.`rechten` (
  `rolnaam` VARCHAR(255) NOT NULL,
  `aanmaken_evenement` TINYINT(1) NOT NULL,
  `wijzigen_evenement` TINYINT(1) NOT NULL,
  `bekijken_evenement` TINYINT(1) NOT NULL,
  `bekijken_persoonsgegevens` TINYINT(1) NOT NULL,
  `wijzigen_persoonsgegevens` TINYINT(1) NOT NULL,
  `verwijderen_persoonsgegevens` TINYINT(1) NOT NULL,
  `inschrijven_leerling` TINYINT(1) NOT NULL,
  `goedkeuren_aanmelding` TINYINT(1) NOT NULL,
  `whitelisten_leerling` TINYINT(1) NOT NULL,
  `rolid` INT NOT NULL,
  PRIMARY KEY (`rolnaam`),
  CONSTRAINT `fk_rechten_rolnaam1`
    FOREIGN KEY (`rolid`)
    REFERENCES `inschrijfmodule`.`rolnaam` (`rolid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_rechten_rolnaam1_idx` ON `inschrijfmodule`.`rechten` (`rolid` ASC);


-- -----------------------------------------------------
-- Table `inschrijfmodule`.`foto`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `inschrijfmodule`.`foto` ;

CREATE TABLE IF NOT EXISTS `inschrijfmodule`.`foto` (
  `foto_id` INT NOT NULL AUTO_INCREMENT,
  `fotonaam` VARCHAR(255) NOT NULL,
  `evenement_id` INT NOT NULL,
  `comment` VARCHAR(255) NULL,
  PRIMARY KEY (`foto_id`),
  CONSTRAINT `fk_foto_evenement1`
    FOREIGN KEY (`evenement_id`)
    REFERENCES `inschrijfmodule`.`evenement` (`evenement_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_foto_evenement1_idx` ON `inschrijfmodule`.`foto` (`evenement_id` ASC);


-- -----------------------------------------------------
-- Table `inschrijfmodule`.`beoordeling`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `inschrijfmodule`.`beoordeling` ;

CREATE TABLE IF NOT EXISTS `inschrijfmodule`.`beoordeling` (
  `beoordeling_id` INT NOT NULL AUTO_INCREMENT,
  `leerlingnummer` INT NOT NULL,
  `evenement_id` INT NOT NULL,
  `score` INT NOT NULL,
  PRIMARY KEY (`beoordeling_id`, `leerlingnummer`, `evenement_id`),
  CONSTRAINT `fk_leerling_has_evenement_leerling1`
    FOREIGN KEY (`leerlingnummer`)
    REFERENCES `inschrijfmodule`.`leerling` (`leerlingnummer`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_leerling_has_evenement_evenement1`
    FOREIGN KEY (`evenement_id`)
    REFERENCES `inschrijfmodule`.`evenement` (`evenement_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_leerling_has_evenement_evenement1_idx` ON `inschrijfmodule`.`beoordeling` (`evenement_id` ASC);

CREATE INDEX `fk_leerling_has_evenement_leerling1_idx` ON `inschrijfmodule`.`beoordeling` (`leerlingnummer` ASC);


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;


INSERT INTO `account` (`account_id`, `gebruikersnaam`, `wachtwoord`, `rol_id`) VALUES
(1, 'stijnkluiters', '$2y$15$HskhXrSNHg40cAXqGHf5deaNjQsl7w8HrRD5Ud0X/1j/aM7wqnf0m', 1);

INSERT INTO `evenement` (`evenement_id`, `account_id`, `titel`, `datum`, `begintijd`, `eindtijd`, `onderwerp`, `omschrijving`, `vervoer`, `min_leerlingen`, `max_leerlingen`, `comment`, `locatie`, `lokaalnummer`, `soort`, `contactnr`) VALUES
(NULL, '1', 'eventone', '2017-11-15', '2017-11-15 15:36:01', '', 'school', 'scho ho hool, merry schoolmas.', NULL, NULL, NULL, NULL, 'Amersfoort', NULL, 'hooi', NULL);