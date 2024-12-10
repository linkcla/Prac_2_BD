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
    domini VARCHAR(128) NOT NULL,
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
    idConfigProducte INT,
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

-- TAULES RELACIONADA AMB EL HISTORIAL INCREMENTAL
CREATE TABLE AUDITORIA (
    id INT AUTO_INCREMENT PRIMARY KEY,
    taula VARCHAR(128) NOT NULL,
    operacio ENUM('INSERT', 'UPDATE', 'DELETE') NOT NULL,
    dades_anteriors TEXT,
    dades_noves TEXT,
    dia_hora_minut DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE HISTORIAL (
    id INT AUTO_INCREMENT PRIMARY KEY,
    dia DATE NOT NULL,
    canvis_realitzats TEXT NOT NULL,
    numero_canvis INT NOT NULL
);

-- EVENT PER REALITZAR L'HISTORIAL DE CANVIS
DELIMITER $$

CREATE EVENT consolidar_a_historial
ON SCHEDULE EVERY 1 DAY
DO
BEGIN
    -- Consolidar los datos del día
    INSERT INTO HISTORIAL (dia, canvis_realitzats, numero_canvis)
    SELECT
        DATE(dia_hora_minut) AS dia,
        GROUP_CONCAT(CONCAT(
            'Taula: ', taula, ', Operació: ', operacio, 
            ', Dades anteriors: ', COALESCE(dades_anteriors, 'N/A'),
            ', Dades noves: ', COALESCE(dades_noves, 'N/A')
        ) SEPARATOR ' #-# ') AS canvis_realitzats,
        COUNT(*) AS numero_canvis
    FROM AUDITORIA
    GROUP BY DATE(dia_hora_minut);

    -- Limpiar la tabla AUDITORIA
    DELETE FROM AUDITORIA;
END$$

DELIMITER ;



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

-- Trigger para la tabla CONTRACTE
CREATE TRIGGER auditar_insert_contracte
AFTER INSERT ON CONTRACTE
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_noves)
    VALUES ('CONTRACTE', 'INSERT', CONCAT('idContracte=', NEW.idContracte, ', dataInici=', NEW.dataInici, ', estat=', NEW.estat, ', domini=', NEW.domini, ', nom=', NEW.nom, ', emailU=', NEW.emailU, ', idConfigProducte=', NEW.idConfigProducte, ', mesos=', NEW.mesos));
END$$

CREATE TRIGGER auditar_update_contracte
AFTER UPDATE ON CONTRACTE
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_anteriors, dades_noves)
    VALUES ('CONTRACTE', 'UPDATE', CONCAT('idContracte=', OLD.idContracte, ', dataInici=', OLD.dataInici, ', estat=', OLD.estat, ', domini=', OLD.domini, ', nom=', OLD.nom, ', emailU=', OLD.emailU, ', idConfigProducte=', OLD.idConfigProducte, ', mesos=', OLD.mesos),
            CONCAT('idContracte=', NEW.idContracte, ', dataInici=', NEW.dataInici, ', estat=', NEW.estat, ', domini=', NEW.domini, ', nom=', NEW.nom, ', emailU=', NEW.emailU, ', idConfigProducte=', NEW.idConfigProducte, ', mesos=', NEW.mesos));
END$$

CREATE TRIGGER auditar_delete_contracte
AFTER DELETE ON CONTRACTE
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_anteriors)
    VALUES ('CONTRACTE', 'DELETE', CONCAT('idContracte=', OLD.idContracte, ', dataInici=', OLD.dataInici, ', estat=', OLD.estat, ', domini=', OLD.domini, ', nom=', OLD.nom, ', emailU=', OLD.emailU, ', idConfigProducte=', OLD.idConfigProducte, ', mesos=', OLD.mesos));
END$$

-- Trigger la tabla DURADA. Solo tiene PK entonces solo se puede hacer INSERT y DELETE.
CREATE TRIGGER auditar_insert_durada
AFTER INSERT ON DURADA
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_noves)
    VALUES ('DURADA', 'INSERT', CONCAT('mesos=', NEW.mesos));
END$$

CREATE TRIGGER auditar_delete_durada
AFTER DELETE ON DURADA
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_anteriors)
    VALUES ('DURADA', 'DELETE', CONCAT('mesos=', OLD.mesos));
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

-- Trigger para la tabla TEST
CREATE TRIGGER auditar_insert_test
AFTER INSERT ON TEST
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_noves)
    VALUES ('TEST', 'INSERT', CONCAT('nom=', NEW.nom, ', descripcio=', NEW.descripcio, ', dataCreacio=', NEW.dataCreacio));
END$$

CREATE TRIGGER auditar_update_test
AFTER UPDATE ON TEST
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_anteriors, dades_noves)
    VALUES ('TEST', 'UPDATE', CONCAT('nom=', OLD.nom, ', descripcio=', OLD.descripcio, ', dataCreacio=', OLD.dataCreacio),
            CONCAT('nom=', NEW.nom, ', descripcio=', NEW.descripcio, ', dataCreacio=', NEW.dataCreacio));
END$$

CREATE TRIGGER auditar_delete_test
AFTER DELETE ON TEST
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_anteriors)
    VALUES ('TEST', 'DELETE', CONCAT('nom=', OLD.nom, ', descripcio=', OLD.descripcio, ', dataCreacio=', OLD.dataCreacio));
END$$

-- Trigger para la tabla ESTAT
CREATE TRIGGER auditar_insert_estat
AFTER INSERT ON ESTAT
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_noves)
    VALUES ('ESTAT', 'INSERT', CONCAT('estat=', NEW.estat, ', nomT=', NEW.nomT, ', idConfigProducte=', NEW.idConfigProducte));
END$$

CREATE TRIGGER auditar_update_estat
AFTER UPDATE ON ESTAT
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_anteriors, dades_noves)
    VALUES ('ESTAT', 'UPDATE', CONCAT('estat=', OLD.estat, ', nomT=', OLD.nomT, ', idConfigProducte=', OLD.idConfigProducte),
            CONCAT('estat=', NEW.estat, ', nomT=', NEW.nomT, ', idConfigProducte=', NEW.idConfigProducte));
END$$

CREATE TRIGGER auditar_delete_estat
AFTER DELETE ON ESTAT
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_anteriors)
    VALUES ('ESTAT', 'DELETE', CONCAT('estat=', OLD.estat, ', nomT=', OLD.nomT, ', idConfigProducte=', OLD.idConfigProducte));
END$$

-- Trigger para la tabla SAAS
CREATE TRIGGER auditar_insert_saas
AFTER INSERT ON SAAS
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_noves)
    VALUES ('SAAS', 'INSERT', CONCAT('idConfig=', NEW.idConfig, ', dataCreacio=', NEW.dataCreacio, ', tipusMCMS=', NEW.tipusMCMS, ', tipusCDN=', NEW.tipusCDN, ', tipusSSL=', NEW.tipusSSL, ', tipusSGBD=', NEW.tipusSGBD, ', tipusRam=', NEW.tipusRam, ', GBRam=', NEW.GBRam, ', tipusDD=', NEW.tipusDD, ', GBDD=', NEW.GBDD));
END$$

CREATE TRIGGER auditar_update_saas
AFTER UPDATE ON SAAS
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_anteriors, dades_noves)
    VALUES ('SAAS', 'UPDATE', CONCAT('idConfig=', OLD.idConfig, ', dataCreacio=', OLD.dataCreacio, ', tipusMCMS=', OLD.tipusMCMS, ', tipusCDN=', OLD.tipusCDN, ', tipusSSL=', OLD.tipusSSL, ', tipusSGBD=', OLD.tipusSGBD, ', tipusRam=', OLD.tipusRam, ', GBRam=', OLD.GBRam, ', tipusDD=', OLD.tipusDD, ', GBDD=', OLD.GBDD),
            CONCAT('idConfig=', NEW.idConfig, ', dataCreacio=', NEW.dataCreacio, ', tipusMCMS=', NEW.tipusMCMS, ', tipusCDN=', NEW.tipusCDN, ', tipusSSL=', NEW.tipusSSL, ', tipusSGBD=', NEW.tipusSGBD, ', tipusRam=', NEW.tipusRam, ', GBRam=', NEW.GBRam, ', tipusDD=', NEW.tipusDD, ', GBDD=', NEW.GBDD));
END$$

CREATE TRIGGER auditar_delete_saas
AFTER DELETE ON SAAS
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_anteriors)
    VALUES ('SAAS', 'DELETE', CONCAT('idConfig=', OLD.idConfig, ', dataCreacio=', OLD.dataCreacio, ', tipusMCMS=', OLD.tipusMCMS, ', tipusCDN=', OLD.tipusCDN, ', tipusSSL=', OLD.tipusSSL, ', tipusSGBD=', OLD.tipusSGBD, ', tipusRam=', OLD.tipusRam, ', GBRam=', OLD.GBRam, ', tipusDD=', OLD.tipusDD, ', GBDD=', OLD.GBDD));
END$$

-- Trigger para la tabla MODUL_CMS. Solo tiene PK entonces solo se puede hacer INSERT y DELETE.
CREATE TRIGGER auditar_insert_modul_cms
AFTER INSERT ON MODUL_CMS
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_noves)
    VALUES ('MODUL_CMS', 'INSERT', CONCAT('tipus=', NEW.tipus));
END$$

CREATE TRIGGER auditar_delete_modul_cms
AFTER DELETE ON MODUL_CMS
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_anteriors)
    VALUES ('MODUL_CMS', 'DELETE', CONCAT('tipus=', OLD.tipus));
END$$

-- Trigger para la tabla CDN
CREATE TRIGGER auditar_insert_cdn
AFTER INSERT ON CDN
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_noves)
    VALUES ('CDN', 'INSERT', CONCAT('tipus=', NEW.tipus, ', preu=', NEW.preu));
END$$

CREATE TRIGGER auditar_update_cdn
AFTER UPDATE ON CDN
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_anteriors, dades_noves)
    VALUES ('CDN', 'UPDATE', CONCAT('tipus=', OLD.tipus, ', preu=', OLD.preu),
            CONCAT('tipus=', NEW.tipus, ', preu=', NEW.preu));
END$$

CREATE TRIGGER auditar_delete_cdn
AFTER DELETE ON CDN
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_anteriors)
    VALUES ('CDN', 'DELETE', CONCAT('tipus=', OLD.tipus, ', preu=', OLD.preu));
END$$

-- Trigger para la tabla C_SSL
CREATE TRIGGER auditar_insert_c_ssl
AFTER INSERT ON C_SSL
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_noves)
    VALUES ('C_SSL', 'INSERT', CONCAT('tipus=', NEW.tipus, ', preu=', NEW.preu));
END$$

CREATE TRIGGER auditar_update_c_ssl
AFTER UPDATE ON C_SSL
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_anteriors, dades_noves)
    VALUES ('C_SSL', 'UPDATE', CONCAT('tipus=', OLD.tipus, ', preu=', OLD.preu),
            CONCAT('tipus=', NEW.tipus, ', preu=', NEW.preu));
END$$

CREATE TRIGGER auditar_delete_c_ssl
AFTER DELETE ON C_SSL
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_anteriors)
    VALUES ('C_SSL', 'DELETE', CONCAT('tipus=', OLD.tipus, ', preu=', OLD.preu));
END$$

-- Trigger para la tabla SIST_GESTIO_BD. Solo tiene PK entonces solo se puede hacer INSERT y DELETE.
CREATE TRIGGER auditar_insert_sist_gestio_bd
AFTER INSERT ON SIST_GESTIO_BD
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_noves)
    VALUES ('SIST_GESTIO_BD', 'INSERT', CONCAT('tipus=', NEW.tipus));
END$$

CREATE TRIGGER auditar_delete_sist_gestio_bd
AFTER DELETE ON SIST_GESTIO_BD
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_anteriors)
    VALUES ('SIST_GESTIO_BD', 'DELETE', CONCAT('tipus=', OLD.tipus));
END$$

-- Trigger para la tabla PAAS
CREATE TRIGGER auditar_insert_paas
AFTER INSERT ON PAAS
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_noves)
    VALUES ('PAAS', 'INSERT', CONCAT('idConfig=', NEW.idConfig, ', iPv4=', NEW.iPv4, ', iPv6=', NEW.iPv6, ', tipusRAM=', NEW.tipusRAM, ', GBRam=', NEW.GBRam, ', tipusDD=', NEW.tipusDD, ', GBDD=', NEW.GBDD, ', modelCPU=', NEW.modelCPU, ', nNuclis=', NEW.nNuclis, ', nomSO=', NEW.nomSO));
END$$

CREATE TRIGGER auditar_update_paas
AFTER UPDATE ON PAAS
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_anteriors, dades_noves)
    VALUES ('PAAS', 'UPDATE', CONCAT('idConfig=', OLD.idConfig, ', iPv4=', OLD.iPv4, ', iPv6=', OLD.iPv6, ', tipusRAM=', OLD.tipusRAM, ', GBRam=', OLD.GBRam, ', tipusDD=', OLD.tipusDD, ', GBDD=', OLD.GBDD, ', modelCPU=', OLD.modelCPU, ', nNuclis=', OLD.nNuclis, ', nomSO=', OLD.nomSO),
            CONCAT('idConfig=', NEW.idConfig, ', iPv4=', NEW.iPv4, ', iPv6=', NEW.iPv6, ', tipusRAM=', NEW.tipusRAM, ', GBRam=', NEW.GBRam, ', tipusDD=', NEW.tipusDD, ', GBDD=', NEW.GBDD, ', modelCPU=', NEW.modelCPU, ', nNuclis=', NEW.nNuclis, ', nomSO=', NEW.nomSO));
END$$

CREATE TRIGGER auditar_delete_paas
AFTER DELETE ON PAAS
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_anteriors)
    VALUES ('PAAS', 'DELETE', CONCAT('idConfig=', OLD.idConfig, ', iPv4=', OLD.iPv4, ', iPv6=', OLD.iPv6, ', tipusRAM=', OLD.tipusRAM, ', GBRam=', OLD.GBRam, ', tipusDD=', OLD.tipusDD, ', GBDD=', OLD.GBDD, ', modelCPU=', OLD.modelCPU, ', nNuclis=', OLD.nNuclis, ', nomSO=', OLD.nomSO));
END$$

-- Trigger para la tabla RAM
CREATE TRIGGER auditar_insert_ram
AFTER INSERT ON RAM
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_noves)
    VALUES ('RAM', 'INSERT', CONCAT('tipus=', NEW.tipus, ', GB=', NEW.GB, ', preu=', NEW.preu));
END$$

CREATE TRIGGER auditar_update_ram
AFTER UPDATE ON RAM
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_anteriors, dades_noves)
    VALUES ('RAM', 'UPDATE', CONCAT('tipus=', OLD.tipus, ', GB=', OLD.GB, ', preu=', OLD.preu),
            CONCAT('tipus=', NEW.tipus, ', GB=', NEW.GB, ', preu=', NEW.preu));
END$$

CREATE TRIGGER auditar_delete_ram
AFTER DELETE ON RAM
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_anteriors)
    VALUES ('RAM', 'DELETE', CONCAT('tipus=', OLD.tipus, ', GB=', OLD.GB, ', preu=', OLD.preu));
END$$

-- Trigger para la tabla DISC_DUR
CREATE TRIGGER auditar_insert_disc_dur
AFTER INSERT ON DISC_DUR
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_noves)
    VALUES ('DISC_DUR', 'INSERT', CONCAT('tipus=', NEW.tipus, ', GB=', NEW.GB, ', preu=', NEW.preu));
END$$

CREATE TRIGGER auditar_update_disc_dur
AFTER UPDATE ON DISC_DUR
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_anteriors, dades_noves)
    VALUES ('DISC_DUR', 'UPDATE', CONCAT('tipus=', OLD.tipus, ', GB=', OLD.GB, ', preu=', OLD.preu),
            CONCAT('tipus=', NEW.tipus, ', GB=', NEW.GB, ', preu=', NEW.preu));
END$$

CREATE TRIGGER auditar_delete_disc_dur
AFTER DELETE ON DISC_DUR
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_anteriors)
    VALUES ('DISC_DUR', 'DELETE', CONCAT('tipus=', OLD.tipus, ', GB=', OLD.GB, ', preu=', OLD.preu));
END$$

-- Trigger para la tabla CPU
CREATE TRIGGER auditar_insert_cpu
AFTER INSERT ON CPU
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_noves)
    VALUES ('CPU', 'INSERT', CONCAT('model=', NEW.model, ', nNuclis=', NEW.nNuclis, ', preu=', NEW.preu));
END$$

CREATE TRIGGER auditar_update_cpu
AFTER UPDATE ON CPU
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_anteriors, dades_noves)
    VALUES ('CPU', 'UPDATE', CONCAT('model=', OLD.model, ', nNuclis=', OLD.nNuclis, ', preu=', OLD.preu),
            CONCAT('model=', NEW.model, ', nNuclis=', NEW.nNuclis, ', preu=', NEW.preu));
END$$

CREATE TRIGGER auditar_delete_cpu
AFTER DELETE ON CPU
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_anteriors)
    VALUES ('CPU', 'DELETE', CONCAT('model=', OLD.model, ', nNuclis=', OLD.nNuclis, ', preu=', OLD.preu));
END$$

-- Trigger para la tabla SO
CREATE TRIGGER auditar_insert_so
AFTER INSERT ON SO
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_noves)
    VALUES ('SO', 'INSERT', CONCAT('nom=', NEW.nom, ', preu=', NEW.preu));
END$$

CREATE TRIGGER auditar_update_so
AFTER UPDATE ON SO
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_anteriors, dades_noves)
    VALUES ('SO', 'UPDATE', CONCAT('nom=', OLD.nom, ', preu=', OLD.preu),
            CONCAT('nom=', NEW.nom, ', preu=', NEW.preu));
END$$

CREATE TRIGGER auditar_delete_so
AFTER DELETE ON SO
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_anteriors)
    VALUES ('SO', 'DELETE', CONCAT('nom=', OLD.nom, ', preu=', OLD.preu));
END$$

-- Trigger para la tabla PRIV_DE_GRUP
CREATE TRIGGER auditar_insert_priv_de_grup
AFTER INSERT ON PRIV_DE_GRUP
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_noves)
    VALUES ('PRIV_DE_GRUP', 'INSERT', CONCAT('tipusPriv=', NEW.tipusPriv, ', nomG=', NEW.nomG, ', nomOrg=', NEW.nomOrg));
END$$

CREATE TRIGGER auditar_update_priv_de_grup
AFTER UPDATE ON PRIV_DE_GRUP
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_anteriors, dades_noves)
    VALUES ('PRIV_DE_GRUP', 'UPDATE', CONCAT('tipusPriv=', OLD.tipusPriv, ', nomG=', OLD.nomG, ', nomOrg=', OLD.nomOrg),
            CONCAT('tipusPriv=', NEW.tipusPriv, ', nomG=', NEW.nomG, ', nomOrg=', NEW.nomOrg));
END$$

CREATE TRIGGER auditar_delete_priv_de_grup
AFTER DELETE ON PRIV_DE_GRUP
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_anteriors)
    VALUES ('PRIV_DE_GRUP', 'DELETE', CONCAT('tipusPriv=', OLD.tipusPriv, ', nomG=', OLD.nomG, ', nomOrg=', OLD.nomOrg));
END$$

-- Trigger para la tabla PERSONAL_ADMINISTRA_CONTR
CREATE TRIGGER auditar_insert_personal_administra_contr
AFTER INSERT ON PERSONAL_ADMINISTRA_CONTR
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_noves)
    VALUES ('PERSONAL_ADMINISTRA_CONTR', 'INSERT', CONCAT('emailP=', NEW.emailP, ', idContracte=', NEW.idContracte));
END$$

CREATE TRIGGER auditar_update_personal_administra_contr
AFTER UPDATE ON PERSONAL_ADMINISTRA_CONTR
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_anteriors, dades_noves)
    VALUES ('PERSONAL_ADMINISTRA_CONTR', 'UPDATE', CONCAT('emailP=', OLD.emailP, ', idContracte=', OLD.idContracte),
            CONCAT('emailP=', NEW.emailP, ', idContracte=', NEW.idContracte));
END$$

CREATE TRIGGER auditar_delete_personal_administra_contr
AFTER DELETE ON PERSONAL_ADMINISTRA_CONTR
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_anteriors)
    VALUES ('PERSONAL_ADMINISTRA_CONTR', 'DELETE', CONCAT('emailP=', OLD.emailP, ', idContracte=', OLD.idContracte));
END$$

-- Trigger para la tabla PERSONAL_CREA_PRODUCTE. Solo tiene PK entonces solo se puede hacer INSERT y DELETE.
CREATE TRIGGER auditar_insert_personal_crea_producte
AFTER INSERT ON PERSONAL_CREA_PRODUCTE
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_noves)
    VALUES ('PERSONAL_CREA_PRODUCTE', 'INSERT', CONCAT('emailP=', NEW.emailP, ', idConfigProducte=', NEW.idConfigProducte));
END$$

CREATE TRIGGER auditar_delete_personal_crea_producte
AFTER DELETE ON PERSONAL_CREA_PRODUCTE
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_anteriors)
    VALUES ('PERSONAL_CREA_PRODUCTE', 'DELETE', CONCAT('emailP=', OLD.emailP, ', idConfigProducte=', OLD.idConfigProducte));
END$$

-- Trigger para la tabla PERSONAL_REALITZA_TEST
CREATE TRIGGER auditar_insert_personal_realitza_test
AFTER INSERT ON PERSONAL_REALITZA_TEST
FOR EACH ROW
BEGIN
    INSERT INTO AUDITORIA (taula, operacio, dades_noves)
    VALUES ('PERSONAL_REALITZA_TEST', 'INSERT', CONCAT('emailP=', NEW.emailP, ', nomT=', NEW.nomT));
END$$

DELIMITER ;