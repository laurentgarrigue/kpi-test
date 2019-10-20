/**
 * Author:  laurent
 * Created: 27 sept. 2019
 */
-- InnoDB
ALTER TABLE my_table ENGINE = InnoDB;
-- utf8_general_ci
ALTER TABLE my_table CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;

-- Foreign keys
ALTER TABLE gickp_Equipe ADD FOREIGN KEY (Code_club) REFERENCES gickp_Club (Code);
ALTER TABLE gickp_Club ADD FOREIGN KEY (Code_comite_dep) REFERENCES gickp_Comite_dep (Code);
ALTER TABLE gickp_Comite_dep ADD FOREIGN KEY (Code_comite_reg) REFERENCES gickp_Comite_reg (Code);
ALTER TABLE gickp_Competitions_Equipes ADD FOREIGN KEY (Code_club) REFERENCES gickp_Club (Code);
ALTER TABLE gickp_Surclassements ADD FOREIGN KEY (Matric) REFERENCES gickp_Liste_Coureur (Matric);


-- Hum... !
ALTER TABLE gickp_Arbitre ADD FOREIGN KEY (Matric) REFERENCES gickp_Liste_Coureur (Matric);
ALTER TABLE gickp_Competitions_Equipes_Joueurs ADD FOREIGN KEY (Matric) REFERENCES gickp_Liste_Coureur (Matric);
ALTER TABLE gickp_Evenement_Journees ADD FOREIGN KEY (Id_journee) REFERENCES gickp_Journees (Id);
ALTER TABLE gickp_Evenement_Journees ADD FOREIGN KEY (Id_evenement) REFERENCES gickp_Evenement (Id);
ALTER TABLE gickp_Journees ADD FOREIGN KEY (Code_competition) REFERENCES gickp_Competitions (Code);
ALTER TABLE gickp_Matchs ADD FOREIGN KEY (Id_journee) REFERENCES gickp_Journees (Id);
ALTER TABLE gickp_Matchs_Detail ADD FOREIGN KEY (Id_match) REFERENCES gickp_Matchs (Id);
ALTER TABLE gickp_Matchs_Joueurs ADD FOREIGN KEY (Id_match) REFERENCES gickp_Matchs (Id);
-- ---------



-- ion_auth
-- executer /third_party/ion_auth/sql/ion_auth.sql

-- sessions

CREATE TABLE IF NOT EXISTS `ci_sessions` (
        `id` varchar(128) NOT NULL,
        `ip_address` varchar(45) NOT NULL,
        `timestamp` int(10) unsigned DEFAULT 0 NOT NULL,
        `data` blob NOT NULL,
        KEY `ci_sessions_timestamp` (`timestamp`)
);

-- users
ALTER TABLE `users` 
        ADD UNIQUE(`username`);
ALTER TABLE `users` 
        CHANGE `username` `username` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE `users` 
        ADD `seasons` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `phone`, 
        ADD `compets` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `seasons`, 
        ADD `phases` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `compets`, 
        ADD `clubs` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `phases`;

-- groups
TRUNCATE TABLE `groups`;
INSERT INTO `groups` (`id`, `name`, `description`) VALUES
(1, 'admin', 'Administrateur'),
(20, 'bureau', 'Bureau CNKP'),
(30, 'rc', 'Responsable compétition'),
(32, 'rc_int', 'Responsable compétition internationale'),
(34, 'rc_nat', 'Responsable compétition nationale'),
(36, 'rc_reg', 'Responsable compétition régionale'),
(38, 'rc_open', 'Responsable compétition open'),
(40, 'rp', 'Responsable poule'),
(50, 'delegue', 'Délégué fédéral'),
(60, 'r1', 'Responsable organisation'),
(70, 'coach', 'Responsable équipe'),
(80, 'consultation', 'Consultation'),
(90, 'table', 'Table de marque'),
(100, 'members', 'Utilisateurs');

-- sections
CREATE TABLE `gickp_sections` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `gickp_sections` (`id`, `name`) VALUES
(1, 'International'),
(2, 'National'),
(3, 'Regional'),
(4, 'Open'),
(5, 'Continental'),
(100, 'Divers');

ALTER TABLE `gickp_sections` ADD PRIMARY KEY(`id`)
ALTER TABLE `gickp_Competitions_Groupes` ADD FOREIGN KEY (section) REFERENCES `gickp_sections` (id);
