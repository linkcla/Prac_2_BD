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

CREATE TABLE GRUP (
    nom VARCHAR(128) PRIMARY KEY
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
    descripció TEXT NOT NULL,
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
CREATE TABLE PERSONAL (
    email VARCHAR(128) PRIMARY KEY,
    dni VARCHAR(9) NOT NULL,
    CONSTRAINT fk_personal_persona FOREIGN KEY (email) REFERENCES PERSONA(email)
);

CREATE TABLE USUARI (
    email VARCHAR(128) PRIMARY KEY,
    nomOrg VARCHAR(128),
    CONSTRAINT fk_usuari_persona FOREIGN KEY (email) REFERENCES PERSONA(email),
    CONSTRAINT fk_usuari_organitzacio FOREIGN KEY (nomOrg) REFERENCES ORGANITZACIO(nom)
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
    tipusMCMS ENUM('Sense modul', 'WordPress', 'Drupal', 'Joomla') NOT NULL,
    tipusCDN ENUM('Bàsic', 'Protegit', 'Avançat') NOT NULL,
    tipusSSL ENUM('Bàsic', 'Professional', 'Avançat') NOT NULL,
    tipusSGBD VARCHAR(64) NOT NULL,
    tipusRam ENUM('DDR3', 'DDR4', 'DDR5') NOT NULL, 
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
    tipusRAM ENUM('DDR3', 'DDR4', 'DDR5') NOT NULL,
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
    PRIMARY KEY (tipusPriv, nomG),
    CONSTRAINT fk_priv_de_grup_privilegi FOREIGN KEY (tipusPriv) REFERENCES PRIVILEGI(tipus),
    CONSTRAINT fk_priv_de_grup_grup FOREIGN KEY (nomG) REFERENCES GRUP(nom)
);

CREATE TABLE US_PERTANY_GRU (
    emailU VARCHAR(128),
    nomG VARCHAR(128),
    PRIMARY KEY (emailU, nomG),
    CONSTRAINT fk_us_pertany_gru_usuari FOREIGN KEY (emailU) REFERENCES USUARI(email),
    CONSTRAINT fk_us_pertany_gru_grup FOREIGN KEY (nomG) REFERENCES GRUP(nom)
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
