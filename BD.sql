CREATE DATABASE bd_grupo;
USE bd_grupo;

-- Taules sense FK

-- Revisar si es pot aplicar un trigger per comprovar si l'email ja existeix conté un "@" y un ".".
CREATE TABLE PERSONA (
    nom VARCHAR(64) NOT NULL,
    cognom VARCHAR(64) NOT NULL,
    email VARCHAR(128) PRIMARY KEY,
    contrasenya VARCHAR(64) NOT NULL
);

CREATE TABLE PRIVILEGI (
    tipus ENUM('Visualizar', 'Borrar', 'Crear', 'Editar') PRIMARY KEY
);

CREATE TABLE ORGANITZACIO (
    nom VARCHAR(128) PRIMARY KEY,
    adreca VARCHAR(128) NOT NULL,
    telefon VARCHAR(16) NOT NULL
);

CREATE TABLE DURADA (
    mesos INT PRIMARY KEY
);

CREATE TABLE PRODUCTE (
    idConfig INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(128) NOT NULL,
    descripcio VARCHAR(512)
);

CREATE TABLE TEST (
    nom VARCHAR(128) PRIMARY KEY,
    descripcio TEXT NOT NULL,
    dataCreacio DATE NOT NULL
);

CREATE TABLE MODUL_CMS (
    tipus VARCHAR(16) PRIMARY KEY
);

CREATE TABLE CDN (
    tipus ENUM('Bàsic', 'Protegit', 'Avançat') PRIMARY KEY,
    preu DECIMAL(3, 2)
);

CREATE TABLE C_SSL (
    tipus ENUM('Bàsic', 'Professional', 'Avançat') PRIMARY KEY,
    preu DECIMAL(3, 2)
);

CREATE TABLE SIST_GESTIO_BD (
    tipus VARCHAR(64) PRIMARY KEY
);

CREATE TABLE RAM (
    tipus VARCHAR(8),
    GB INT,
    preu DECIMAL(4, 2) NOT NULL,
    PRIMARY KEY (tipus, GB)
);

CREATE TABLE DISC_DUR (
    tipus ENUM('HDD', 'SSD'),
    GB INT,
    preu DECIMAL(5, 2) NOT NULL,
    PRIMARY KEY (tipus, GB)
);

CREATE TABLE CPU (
    model ENUM('Intel', 'AMD'),
    nNuclis ENUM('2', '4', '6', '8', '10', '12', '16', '24', '32', '64'),
    preu DECIMAL(5, 2) NOT NULL,
    PRIMARY KEY (model, nNuclis)
);

CREATE TABLE SO (
    nom ENUM('Windows', 'Linux') PRIMARY KEY,
    preu DECIMAL(5, 2) NOT NULL
);

-- Taules amb FK
CREATE TABLE GRUP (
    nom VARCHAR(128),
    nomOrg VARCHAR(128),
    CONSTRAINT fk_grup_organitzacio FOREIGN KEY (nomOrg) REFERENCES ORGANITZACIO(nom) ON DELETE CASCADE,
    PRIMARY KEY (nom, nomOrg)
);

CREATE TABLE PERSONAL (
    email VARCHAR(128) PRIMARY KEY,
    dni VARCHAR(9) NOT NULL,
    CONSTRAINT fk_personal_persona FOREIGN KEY (email) REFERENCES PERSONA(email)
);

CREATE TABLE USUARI (
    email VARCHAR(128) PRIMARY KEY,
    nomOrg VARCHAR(128),
    grup VARCHAR(128),
    CONSTRAINT fk_usuari_persona FOREIGN KEY (email) REFERENCES PERSONA(email),
    CONSTRAINT fk_usuari_organitzacio FOREIGN KEY (nomOrg) REFERENCES ORGANITZACIO(nom) ON DELETE SET NULL,
    CONSTRAINT fk_usuari_grup FOREIGN KEY (grup) REFERENCES GRUP(nom) ON DELETE SET NULL
);

CREATE TABLE CONTRACTE (
    idContracte INT AUTO_INCREMENT PRIMARY KEY,
    dataInici DATE NOT NULL,
    estat ENUM('Actiu', 'Finalitzat', 'Cancel·lat') NOT NULL,
    nom VARCHAR(128) NOT NULL,
    emailU VARCHAR(128) NOT NULL,
    idConfigProducte INT NOT NULL,
    mesos INT NOT NULL,
    CONSTRAINT fk_contracte_organitzacio FOREIGN KEY (nom) REFERENCES ORGANITZACIO(nom),
    CONSTRAINT fk_contracte_usuari FOREIGN KEY (emailU) REFERENCES USUARI(email),
    CONSTRAINT fk_contracte_producte FOREIGN KEY (idConfigProducte) REFERENCES PRODUCTE(idConfig),
    CONSTRAINT fk_contracte_durada FOREIGN KEY (mesos) REFERENCES DURADA(mesos)
);

CREATE TABLE SAAS (
    idConfig INT AUTO_INCREMENT PRIMARY KEY,
    domini VARCHAR(128) NOT NULL,
    dataCreacio DATE NOT NULL,
    tipusMCMS VARCHAR(16) NOT NULL,
    tipusCDN ENUM('Bàsic', 'Protegit', 'Avançat') NOT NULL,
    tipusSSL ENUM('Bàsic', 'Professional', 'Avançat') NOT NULL,
    tipusSGBD VARCHAR(64) NOT NULL,
    tipusRam VARCHAR(8) NOT NULL, 
    GBRam INT NOT NULL,
    tipusDD ENUM('HDD', 'SSD') NOT NULL,
    GBDD INT NOT NULL,
    CONSTRAINT fk_saas_producte FOREIGN KEY (idConfig) REFERENCES PRODUCTE(idConfig),
    CONSTRAINT fk_saas_modul_cms FOREIGN KEY (tipusMCMS) REFERENCES MODUL_CMS(tipus),
    CONSTRAINT fk_saas_cdn FOREIGN KEY (tipusCDN) REFERENCES CDN(tipus),
    CONSTRAINT fk_saas_c_ssl FOREIGN KEY (tipusSSL) REFERENCES C_SSL(tipus),
    CONSTRAINT fk_saas_sist_gestio_bd FOREIGN KEY (tipusSGBD) REFERENCES SIST_GESTIO_BD(tipus),
    CONSTRAINT fk_saas_ram FOREIGN KEY (tipusRam, GBRam) REFERENCES RAM(tipus, GB),
    CONSTRAINT fk_saas_disc_dur FOREIGN KEY (tipusDD, GBDD) REFERENCES DISC_DUR(tipus, GB)
);

CREATE TABLE PAAS (
    idConfig INT AUTO_INCREMENT PRIMARY KEY,
    iPv4 VARCHAR(16),
    iPv6 VARCHAR(35),
    tipusRAM VARCHAR(8) NOT NULL,
    GBRam INT NOT NULL,
    tipusDD ENUM('HDD', 'SSD') NOT NULL,
    GBDD INT NOT NULL,
    modelCPU ENUM('Intel', 'AMD') NOT NULL,
    nNuclis ENUM('2', '4', '6', '8', '10', '12', '16', '24', '32', '64') NOT NULL,
    nomSO ENUM('Windows', 'Linux') NOT NULL,
    CONSTRAINT fk_paas_producte FOREIGN KEY (idConfig) REFERENCES PRODUCTE(idConfig),
    CONSTRAINT fk_paas_ram FOREIGN KEY (tipusRAM, GBRam) REFERENCES RAM(tipus, GB),
    CONSTRAINT fk_paas_disc_dur FOREIGN KEY (tipusDD, GBDD) REFERENCES DISC_DUR(tipus, GB),
    CONSTRAINT fk_paas_cpu FOREIGN KEY (modelCPU, nNuclis) REFERENCES CPU(model, nNuclis),
    CONSTRAINT fk_paas_so FOREIGN KEY (nomSO) REFERENCES SO(nom)
);

CREATE TABLE PRIV_DE_GRUP (
    tipusPriv ENUM('Visualizar', 'Borrar', 'Crear', 'Editar'),
    nomG VARCHAR(128),
    nomOrg VARCHAR(128),
    PRIMARY KEY (tipusPriv, nomG),
    CONSTRAINT fk_priv_de_grup_privilegi FOREIGN KEY (tipusPriv) REFERENCES PRIVILEGI(tipus),
    CONSTRAINT fk_priv_de_grup_grup FOREIGN KEY (nomG, nomOrg) REFERENCES GRUP(nom, nomOrg) ON DELETE CASCADE
);


CREATE TABLE PERSONAL_ADMINISTRA_CONTR (
    emailP VARCHAR(128),
    idContracte INT AUTO_INCREMENT,
    PRIMARY KEY (emailP, idContracte),
    CONSTRAINT fk_personal_administra_contr_personal FOREIGN KEY (emailP) REFERENCES PERSONAL(email),
    CONSTRAINT fk_personal_administra_contr_contracte FOREIGN KEY (idContracte) REFERENCES CONTRACTE(idContracte)
);

CREATE TABLE PERSONAL_CREA_PRODUCTE (
    emailP VARCHAR(128),
    idConfigProducte INT AUTO_INCREMENT,
    PRIMARY KEY (emailP, idConfigProducte),
    CONSTRAINT fk_personal_crea_producte_personal FOREIGN KEY (emailP) REFERENCES PERSONAL(email),
    CONSTRAINT fk_personal_crea_producte_producte FOREIGN KEY (idConfigProducte) REFERENCES PRODUCTE(idConfig)
);

CREATE TABLE PERSONAL_REALITZA_TEST (
    emailP VARCHAR(128),
    nomT VARCHAR(128),
    PRIMARY KEY (emailP, nomT),
    CONSTRAINT fk_personal_realitza_test_personal FOREIGN KEY (emailP) REFERENCES PERSONAL(email),
    CONSTRAINT fk_personal_realitza_test_test FOREIGN KEY (nomT) REFERENCES TEST(nom)
);

CREATE TABLE ESTAT (
    estat ENUM('Pendent', 'Aprovat', 'Fallat'),
    nomT VARCHAR(128),
    idConfigProducte INT AUTO_INCREMENT,
    PRIMARY KEY (nomT, idConfigProducte),
    CONSTRAINT fk_estat_test FOREIGN KEY (nomT) REFERENCES TEST(nom),
    CONSTRAINT fk_estat_producte FOREIGN KEY (idConfigProducte) REFERENCES PRODUCTE(idConfig)
);

-- TAULA RELACIONADA AMB EL HISTORIAL INCREMENTAL
CREATE TABLE AUDITORIA (
    id INT AUTO_INCREMENT PRIMARY KEY,
    taula VARCHAR(128) NOT NULL,
    operacio ENUM('INSERT', 'UPDATE', 'DELETE') NOT NULL,
    dades_anteriors TEXT,
    dades_noves TEXT,
    data DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- TRIGGERS PARA REALIZAR LA EL HISTORIAL DE CAMBIOS

DELIMITER $$
-- Trigger para la tabla PERSONA
CREATE TRIGGER auditar_insert_persona
AFTER INSERT ON PERSONA
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_noves)
    VALUES ('PERSONA', 'INSERT', CONCAT('nom=', NEW.nom, ', cognom=', NEW.cognom, ', email=', NEW.email, ', contrasenya=', NEW.contrasenya));
END$$

CREATE TRIGGER auditar_update_persona
AFTER UPDATE ON PERSONA
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_anteriors, dades_noves)
    VALUES ('PERSONA', 'UPDATE', CONCAT('nom=', OLD.nom, ', cognom=', OLD.cognom, ', email=', OLD.email, ', contrasenya=', OLD.contrasenya),
            CONCAT('nom=', NEW.nom, ', cognom=', NEW.cognom, ', email=', NEW.email, ', contrasenya=', NEW.contrasenya));
END$$

CREATE TRIGGER auditar_delete_persona
AFTER DELETE ON PERSONA
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_anteriors)
    VALUES ('PERSONA', 'DELETE', CONCAT('nom=', OLD.nom, ', cognom=', OLD.cognom, ', email=', OLD.email, ', contrasenya=', OLD.contrasenya));
END$$

-- Trigger para la tabla PERSONAL
CREATE TRIGGER auditar_insert_personal
AFTER INSERT ON PERSONAL
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_noves)
    VALUES ('PERSONAL', 'INSERT', CONCAT('email=', NEW.email, ', dni=', NEW.dni));
END$$

CREATE TRIGGER auditar_update_personal
AFTER UPDATE ON PERSONAL
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_anteriors, dades_noves)
    VALUES ('PERSONAL', 'UPDATE', CONCAT('email=', OLD.email, ', dni=', OLD.dni),
            CONCAT('email=', NEW.email, ', dni=', NEW.dni));
END$$

CREATE TRIGGER auditar_delete_personal
AFTER DELETE ON PERSONAL
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_anteriors)
    VALUES ('PERSONAL', 'DELETE', CONCAT('email=', OLD.email, ', dni=', OLD.dni));
END$$

-- Trigger para la tabla USUARI
CREATE TRIGGER auditar_insert_usuari
AFTER INSERT ON USUARI
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_noves)
    VALUES ('USUARI', 'INSERT', CONCAT('email=', NEW.email, ', nomOrg=', NEW.nomOrg, ', grup=', NEW.grup));
END$$

CREATE TRIGGER auditar_update_usuari
AFTER UPDATE ON USUARI
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_anteriors, dades_noves)
    VALUES ('USUARI', 'UPDATE', CONCAT('email=', OLD.email, ', nomOrg=', OLD.nomOrg, ', grup=', OLD.grup),
            CONCAT('email=', NEW.email, ', nomOrg=', NEW.nomOrg, ', grup=', NEW.grup));
END$$

CREATE TRIGGER auditar_delete_usuari
AFTER DELETE ON USUARI
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_anteriors)
    VALUES ('USUARI', 'DELETE', CONCAT('email=', OLD.email, ', nomOrg=', OLD.nomOrg, ', grup=', OLD.grup));
END$$

-- Trigger para la tabla GRUP
CREATE TRIGGER auditar_insert_grup
AFTER INSERT ON GRUP
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_noves)
    VALUES ('GRUP', 'INSERT', CONCAT('nom=', NEW.nom, ', nomOrg=', NEW.nomOrg));
END$$

CREATE TRIGGER auditar_update_grup
AFTER UPDATE ON GRUP
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_anteriors, dades_noves)
    VALUES ('GRUP', 'UPDATE', CONCAT('nom=', OLD.nom, ', nomOrg=', OLD.nomOrg),
            CONCAT('nom=', NEW.nom, ', nomOrg=', NEW.nomOrg));
END$$

CREATE TRIGGER auditar_delete_grup
AFTER DELETE ON GRUP
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_anteriors)
    VALUES ('GRUP', 'DELETE', CONCAT('nom=', OLD.nom, ', nomOrg=', OLD.nomOrg));
END$$

-- Trigger para la tabla PRIVILEGI. Con el ENUM no se puede hacer un UPDATE. no hace falta hacer su trigger.
CREATE TRIGGER auditar_insert_privilegi
AFTER INSERT ON PRIVILEGI
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_noves)
    VALUES ('PRIVILEGI', 'INSERT', CONCAT('tipus=', NEW.tipus));
END$$

CREATE TRIGGER auditar_delete_privilegi
AFTER DELETE ON PRIVILEGI
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_anteriors)
    VALUES ('PRIVILEGI', 'DELETE', CONCAT('tipus=', OLD.tipus));
END$$

-- Trigger para la tabla ORGANITZACIO
CREATE TRIGGER auditar_insert_organitzacio
AFTER INSERT ON ORGANITZACIO
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_noves)
    VALUES ('ORGANITZACIO', 'INSERT', CONCAT('nom=', NEW.nom, ', adreca=', NEW.adreca, ', telefon=', NEW.telefon));
END$$

CREATE TRIGGER auditar_update_organitzacio
AFTER UPDATE ON ORGANITZACIO
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_anteriors, dades_noves)
    VALUES ('ORGANITZACIO', 'UPDATE', CONCAT('nom=', OLD.nom, ', adreca=', OLD.adreca, ', telefon=', OLD.telefon),
            CONCAT('nom=', NEW.nom, ', adreca=', NEW.adreca, ', telefon=', NEW.telefon));
END$$

CREATE TRIGGER auditar_delete_organitzacio
AFTER DELETE ON ORGANITZACIO
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_anteriors)
    VALUES ('ORGANITZACIO', 'DELETE', CONCAT('nom=', OLD.nom, ', adreca=', OLD.adreca, ', telefon=', OLD.telefon));
END$$

---------------------------------------------------------------------------------------------------------------------------- HASTA AQUÍ REVISADO

-- Trigger para INSERT en la tabla CONTRACTE
CREATE TRIGGER auditar_insert_contracte
AFTER INSERT ON CONTRACTE
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_noves)
    VALUES ('CONTRACTE', 'INSERT', CONCAT('idContracte=', NEW.idContracte, ', dataInici=', NEW.dataInici, ', estat=', NEW.estat, ', nom=', NEW.nom, ', emailU=', NEW.emailU, ', idConfigProducte=', NEW.idConfigProducte, ', mesos=', NEW.mesos));
END$$

-- Trigger para UPDATE en la tabla CONTRACTE
CREATE TRIGGER auditar_update_contracte
AFTER UPDATE ON CONTRACTE
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_anteriors, dades_noves)
    VALUES ('CONTRACTE', 'UPDATE', CONCAT('idContracte=', OLD.idContracte, ', dataInici=', OLD.dataInici, ', estat=', OLD.estat, ', nom=', OLD.nom, ', emailU=', OLD.emailU, ', idConfigProducte=', OLD.idConfigProducte, ', mesos=', OLD.mesos),
            CONCAT('idContracte=', NEW.idContracte, ', dataInici=', NEW.dataInici, ', estat=', NEW.estat, ', nom=', NEW.nom, ', emailU=', NEW.emailU, ', idConfigProducte=', NEW.idConfigProducte, ', mesos=', NEW.mesos));
END$$

-- Trigger para DELETE en la tabla CONTRACTE
CREATE TRIGGER auditar_delete_contracte
AFTER DELETE ON CONTRACTE
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_anteriors)
    VALUES ('CONTRACTE', 'DELETE', CONCAT('idContracte=', OLD.idContracte, ', dataInici=', OLD.dataInici, ', estat=', OLD.estat, ', nom=', OLD.nom, ', emailU=', OLD.emailU, ', idConfigProducte=', OLD.idConfigProducte, ', mesos=', OLD.mesos));
END$$






















-- Trigger para la tabla CONTRACTE
CREATE TRIGGER auditar_insert_contracte
AFTER INSERT ON CONTRACTE
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_noves)
    VALUES ('CONTRACTE', 'INSERT', CONCAT('idConfigProducte=', NEW.idConfigProducte, ', nom=', NEW.nom, ', dataInici=', NEW.dataInici, ', mesos=', NEW.mesos, ', estat=', NEW.estat));
END$$

CREATE TRIGGER auditar_update_contracte
AFTER UPDATE ON CONTRACTE
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_anteriors, dades_noves)
    VALUES ('CONTRACTE', 'UPDATE', CONCAT('idConfigProducte=', OLD.idConfigProducte, ', nom=', OLD.nom, ', dataInici=', OLD.dataInici, ', mesos=', OLD.mesos, ', estat=', OLD.estat),
            CONCAT('idConfigProducte=', NEW.idConfigProducte, ', nom=', NEW.nom, ', dataInici=', NEW.dataInici, ', mesos=', NEW.mesos, ', estat=', NEW.estat));
END$$

CREATE TRIGGER auditar_delete_contracte
AFTER DELETE ON CONTRACTE
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_anteriors)
    VALUES ('CONTRACTE', 'DELETE', CONCAT('idConfigProducte=', OLD.idConfigProducte, ', nom=', OLD.nom, ', dataInici=', OLD.dataInici, ', mesos=', OLD.mesos, ', estat=', OLD.estat));
END$$

-- Trigger para la tabla PRODUCTE
CREATE TRIGGER auditar_insert_producte
AFTER INSERT ON PRODUCTE
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_noves)
    VALUES ('PRODUCTE', 'INSERT', CONCAT('idConfig=', NEW.idConfig, ', nom=', NEW.nom, ', descripcio=', NEW.descripcio));
END$$

CREATE TRIGGER auditar_update_producte
AFTER UPDATE ON PRODUCTE
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_anteriors, dades_noves)
    VALUES ('PRODUCTE', 'UPDATE', CONCAT('idConfig=', OLD.idConfig, ', nom=', OLD.nom, ', descripcio=', OLD.descripcio),
            CONCAT('idConfig=', NEW.idConfig, ', nom=', NEW.nom, ', descripcio=', NEW.descripcio));
END$$

CREATE TRIGGER auditar_delete_producte
AFTER DELETE ON PRODUCTE
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_anteriors)
    VALUES ('PRODUCTE', 'DELETE', CONCAT('idConfig=', OLD.idConfig, ', nom=', OLD.nom, ', descripcio=', OLD.descripcio));
END$$

-- Trigger para la tabla CONTRACTE
CREATE TRIGGER auditar_insert_contracte
AFTER INSERT ON CONTRACTE
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_noves)
    VALUES ('CONTRACTE', 'INSERT', CONCAT('idConfigProducte=', NEW.idConfigProducte, ', nom=', NEW.nom, ', dataInici=', NEW.dataInici, ', mesos=', NEW.mesos, ', estat=', NEW.estat));
END$$

CREATE TRIGGER auditar_update_contracte
AFTER UPDATE ON CONTRACTE
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_anteriors, dades_noves)
    VALUES ('CONTRACTE', 'UPDATE', CONCAT('idConfigProducte=', OLD.idConfigProducte, ', nom=', OLD.nom, ', dataInici=', OLD.dataInici, ', mesos=', OLD.mesos, ', estat=', OLD.estat),
            CONCAT('idConfigProducte=', NEW.idConfigProducte, ', nom=', NEW.nom, ', dataInici=', NEW.dataInici, ', mesos=', NEW.mesos, ', estat=', NEW.estat));
END$$

CREATE TRIGGER auditar_delete_contracte
AFTER DELETE ON CONTRACTE
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_anteriors)
    VALUES ('CONTRACTE', 'DELETE', CONCAT('idConfigProducte=', OLD.idConfigProducte, ', nom=', OLD.nom, ', dataInici=', OLD.dataInici, ', mesos=', OLD.mesos, ', estat=', OLD.estat));
END$$

-- Trigger para la tabla PRODUCTE
CREATE TRIGGER auditar_insert_producte
AFTER INSERT ON PRODUCTE
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_noves)
    VALUES ('PRODUCTE', 'INSERT', CONCAT('idConfig=', NEW.idConfig, ', nom=', NEW.nom, ', descripcio=', NEW.descripcio));
END$$

CREATE TRIGGER auditar_update_producte
AFTER UPDATE ON PRODUCTE
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_anteriors, dades_noves)
    VALUES ('PRODUCTE', 'UPDATE', CONCAT('idConfig=', OLD.idConfig, ', nom=', OLD.nom, ', descripcio=', OLD.descripcio),
            CONCAT('idConfig=', NEW.idConfig, ', nom=', NEW.nom, ', descripcio=', NEW.descripcio));
END$$

CREATE TRIGGER auditar_delete_producte
AFTER DELETE ON PRODUCTE
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_anteriors)
    VALUES ('PRODUCTE', 'DELETE', CONCAT('idConfig=', OLD.idConfig, ', nom=', OLD.nom, ', descripcio=', OLD.descripcio));
END$$

-- Trigger para la tabla USUARI
CREATE TRIGGER auditar_insert_usuari
AFTER INSERT ON USUARI
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_noves)
    VALUES ('USUARI', 'INSERT', CONCAT('id=', NEW.id, ', nom=', NEW.nom, ', cognom=', NEW.cognom, ', email=', NEW.email));
END$$

CREATE TRIGGER auditar_update_usuari
AFTER UPDATE ON USUARI
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_anteriors, dades_noves)
    VALUES ('USUARI', 'UPDATE', CONCAT('id=', OLD.id, ', nom=', OLD.nom, ', cognom=', OLD.cognom, ', email=', OLD.email),
            CONCAT('id=', NEW.id, ', nom=', NEW.nom, ', cognom=', NEW.cognom, ', email=', NEW.email));
END$$

CREATE TRIGGER auditar_delete_usuari
AFTER DELETE ON USUARI
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_anteriors)
    VALUES ('USUARI', 'DELETE', CONCAT('id=', OLD.id, ', nom=', OLD.nom, ', cognom=', OLD.cognom, ', email=', OLD.email));
END$$

-- Trigger para la tabla ORGANITZACIO
CREATE TRIGGER auditar_insert_organitzacio
AFTER INSERT ON ORGANITZACIO
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_noves)
    VALUES ('ORGANITZACIO', 'INSERT', CONCAT('id=', NEW.id, ', nom=', NEW.nom, ', descripcio=', NEW.descripcio));
END$$

CREATE TRIGGER auditar_update_organitzacio
AFTER UPDATE ON ORGANITZACIO
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_anteriors, dades_noves)
    VALUES ('ORGANITZACIO', 'UPDATE', CONCAT('id=', OLD.id, ', nom=', OLD.nom, ', descripcio=', OLD.descripcio),
            CONCAT('id=', NEW.id, ', nom=', NEW.nom, ', descripcio=', NEW.descripcio));
END$$

CREATE TRIGGER auditar_delete_organitzacio
AFTER DELETE ON ORGANITZACIO
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_anteriors)
    VALUES ('ORGANITZACIO', 'DELETE', CONCAT('id=', OLD.id, ', nom=', OLD.nom, ', descripcio=', OLD.descripcio));
END$$


DELIMITER ;