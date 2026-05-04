SET NAMES utf8mb4;

CREATE DATABASE IF NOT EXISTS indidenciesP
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

GRANT ALL PRIVILEGES ON indidenciesP.* TO 'User'@'%';
FLUSH PRIVILEGES;

USE indidenciesP;

-- 1. DEPARTAMENTO
CREATE TABLE DEPARTAMENTO (
    idDepartamento INT (11) AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR (200) NOT NULL
);
-- 2. TECNICO
CREATE TABLE TECNICO (
    idTecnico INT (11) AUTO_INCREMENT PRIMARY KEY, 
    nombre VARCHAR (200) NOT NULL
);

-- 3. TIPO
CREATE TABLE TIPO (
    idTipo INT (7) AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR (25) NOT NULL
);

-- 4. INCIDENCIA
CREATE TABLE INCIDENCIA (
    idIncidencia INT (11) AUTO_INCREMENT PRIMARY KEY,
    idTecnico INT (11),
    idDepartamento INT (11),
    idTipo INT (7),
    fechaInicio TIMESTAMP NOT NULL,
    fechaFin DATE,
    descripcion VARCHAR (2000),
    prioritat ENUM('Alta', 'Mitja', 'Baixa') NOT NULL DEFAULT 'Baixa',
    FOREIGN KEY (idTecnico) REFERENCES TECNICO(idTecnico),
    FOREIGN KEY (idTipo) REFERENCES TIPO(idTipo),
    FOREIGN KEY (idDepartamento) REFERENCES DEPARTAMENTO (idDepartamento)
);
-- 5. ACCION
CREATE TABLE ACCION (
    idAccion INT (11) AUTO_INCREMENT PRIMARY KEY,
    idIncidencia INT (11),
    comentario VARCHAR (2000),
    tiempo INT (3) NOT NULL,
    fechaAccion TIMESTAMP NOT NULL,
    visible INT (1) NOT NULL,
    FOREIGN KEY (idIncidencia) REFERENCES INCIDENCIA (idIncidencia)
);