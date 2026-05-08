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
    tiempo TIME NOT NULL,
    fechaAccion TIMESTAMP NOT NULL,
    visible INT (1) NOT NULL,
    FOREIGN KEY (idIncidencia) REFERENCES INCIDENCIA (idIncidencia)
);

-- Script

CREATE OR REPLACE VIEW vista_informe_tecnics AS
SELECT
    t.idTecnico,
    t.nombre AS nomTecnic,
    i.prioritat,
    i.idIncidencia,
    i.descripcion AS descripcioIncidencia,
    i.fechaInicio AS dataInici,
    IFNULL(SUM(TIME_TO_SEC(a.tiempo)), 0) AS tempsTotalDedicat
FROM TECNICO t
INNER JOIN INCIDENCIA i ON t.idTecnico = i.idTecnico
LEFT JOIN ACCION a ON i.idIncidencia = a.idIncidencia
WHERE i.fechaFin IS NULL
GROUP BY
    t.idTecnico,
    t.nombre,
    i.prioritat,
    i.idIncidencia,
    i.descripcion,
    i.fechaInicio;

CREATE OR REPLACE VIEW vista_consum_departaments AS
SELECT
    d.idDepartamento,
    d.nombre AS nomDepartament,
    COUNT(i.idIncidencia) AS nombreIncidencies,
    IFNULL(SUM(temps_per_incidencia.tempsTotal), 0) AS tempsTotalDedicat
FROM DEPARTAMENTO d
LEFT JOIN INCIDENCIA i ON d.idDepartamento = i.idDepartamento
LEFT JOIN (
    SELECT
        idIncidencia,
        SUM(TIME_TO_SEC(tiempo)) AS tempsTotal
    FROM ACCION
    GROUP BY idIncidencia
) AS temps_per_incidencia ON i.idIncidencia = temps_per_incidencia.idIncidencia
GROUP BY
    d.idDepartamento,
    d.nombre;