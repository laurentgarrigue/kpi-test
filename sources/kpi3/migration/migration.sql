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




-- Hum...
ALTER TABLE gickp_Arbitre ADD FOREIGN KEY (Matric) REFERENCES gickp_Liste_Coureur (Matric);
ALTER TABLE gickp_Competitions_Equipes_Joueurs ADD FOREIGN KEY (Matric) REFERENCES gickp_Liste_Coureur (Matric);
ALTER TABLE gickp_Evenement_Journees ADD FOREIGN KEY (Id_journee) REFERENCES gickp_Journees (Id);
ALTER TABLE gickp_Evenement_Journees ADD FOREIGN KEY (Id_evenement) REFERENCES gickp_Evenement (Id);
ALTER TABLE gickp_Journees ADD FOREIGN KEY (Code_competition) REFERENCES gickp_Competitions (Code);
ALTER TABLE gickp_Matchs ADD FOREIGN KEY (Id_journee) REFERENCES gickp_Journees (Id);
ALTER TABLE gickp_Matchs_Detail ADD FOREIGN KEY (Id_match) REFERENCES gickp_Matchs (Id);
ALTER TABLE gickp_Matchs_Joueurs ADD FOREIGN KEY (Id_match) REFERENCES gickp_Matchs (Id);

