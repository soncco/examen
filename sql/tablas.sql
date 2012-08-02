DROP DATABASE IF EXISTS examen;
CREATE DATABASE IF NOT EXISTS examen;
USE examen;
DROP TABLE tAdministrador, tAlternativa, tAlumno, tCargaAcademica, tCurso, tDocente, tDocenteCurso, tExamen, tExamenPregunta, tExamenPrograma, tMatricula, tOpcion, tPregunta, tRespuesta, tSemestre, tTema, tWatchdog;
DROP TABLE tAlternativa, tAlumno, tCargaAcademica, tCurso, tDocente, tDocenteCurso, tExamen, tExamenPrograma, tPregunta, tSemestre, tTema;
DROP TABLE tCurso, tDocente, tDocenteCurso, tExamen;
DROP TABLE tCurso, tDocente;

-- -----------------------------------------------------
-- Table tAlumno
-- -----------------------------------------------------
CREATE  TABLE tAlumno (
  codAlumno CHAR(6) NOT NULL COMMENT 'Código del alumno.' ,
  password VARCHAR(32) NOT NULL COMMENT 'Contraseña encriptada con MD5.' ,
  apellidoP VARCHAR(40) NOT NULL COMMENT 'Apellido paterno del alumno.' ,
  apellidoM VARCHAR(40) NOT NULL COMMENT 'Apellido materno del alumno.' ,
  nombres VARCHAR(40) NOT NULL COMMENT 'Nombres del alumno.' ,
  email VARCHAR(100) NOT NULL COMMENT 'E-mail del alumno.' ,
  PRIMARY KEY (codAlumno) ,
  UNIQUE INDEX un_email (email ASC) )
ENGINE = InnoDB
COMMENT = 'Guarda información de alumnos.';


-- -----------------------------------------------------
-- Table tCurso
-- -----------------------------------------------------
CREATE  TABLE tCurso (
  codCurso CHAR(8) NOT NULL COMMENT 'Código del curso incluido el grupo y la carrera. Ejemplo: IF101AIN.' ,
  nombre VARCHAR(60) NOT NULL COMMENT 'Nombre del curso.' ,
  creditos INT UNSIGNED NOT NULL COMMENT 'Número de créditos.' ,
  categoria ENUM('OE','EE','OCG','ECG','SEM','AC') NOT NULL COMMENT 'Categoria del curso' ,
  activo ENUM('S','N') NOT NULL DEFAULT 'S' COMMENT 'Estado del curso. S: Activo, N: Inactivo.' ,
  PRIMARY KEY (codCurso) )
ENGINE = InnoDB
COMMENT = 'Datos de los cursos.';


-- -----------------------------------------------------
-- Table tDocente
-- -----------------------------------------------------
CREATE  TABLE tDocente (
  codDocente CHAR(5) NOT NULL COMMENT 'Identificador de un docente.' ,
  password VARCHAR(32) NOT NULL COMMENT 'Contraseña encriptada con MD5.' ,
  apellidoP VARCHAR(40) NOT NULL COMMENT 'Apellido paterno del docente.' ,
  apellidoM VARCHAR(40) NOT NULL COMMENT 'Apellido materno del docente.' ,
  nombres VARCHAR(40) NOT NULL COMMENT 'Nombres del docente.' ,
  email VARCHAR(100) NOT NULL COMMENT 'E-mail del docente.' ,
  PRIMARY KEY (codDocente) ,
  UNIQUE INDEX un_email (email ASC) )
ENGINE = InnoDB
COMMENT = 'Guarda información de docentes.';


-- -----------------------------------------------------
-- Table tDocenteCurso
-- -----------------------------------------------------
CREATE  TABLE tDocenteCurso (
  codDocente CHAR(5) NOT NULL ,
  codCurso CHAR(8) NOT NULL ,
  INDEX fk_tDocenteCurso_tDocente2 (codDocente ASC) ,
  INDEX fk_tDocenteCurso_tCurso2 (codCurso ASC) ,
  PRIMARY KEY (codDocente, codCurso) ,
  CONSTRAINT fk_tDocenteCurso_tDocente2
    FOREIGN KEY (codDocente )
    REFERENCES tDocente (codDocente )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_tDocenteCurso_tCurso2
    FOREIGN KEY (codCurso )
    REFERENCES tCurso (codCurso )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table tTema
-- -----------------------------------------------------
CREATE  TABLE tTema (
  codTema INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador del tema.' ,
  codDocente CHAR(5) NOT NULL ,
  codCurso CHAR(8) NOT NULL ,
  nombre VARCHAR(255) NOT NULL COMMENT 'Nombre del tema.' ,
  PRIMARY KEY (codTema) ,
  INDEX fk_tTema_tDocenteCurso1 (codDocente ASC, codCurso ASC) ,
  CONSTRAINT fk_tTema_tDocenteCurso1
    FOREIGN KEY (codDocente , codCurso )
    REFERENCES tDocenteCurso (codDocente , codCurso )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Guarda información de Temas.';


-- -----------------------------------------------------
-- Table tPregunta
-- -----------------------------------------------------
CREATE  TABLE tPregunta (
  codPregunta INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la pregunta.' ,
  codTema INT UNSIGNED NOT NULL COMMENT 'Código del tema al cual pertenece la pregunta.' ,
  enunciado VARCHAR(500) NOT NULL COMMENT 'Enunciado de la pregunta.' ,
  nivel ENUM('F','N','D') NOT NULL DEFAULT 'F' COMMENT 'Nivel de la pregunta. F: Fácil, N: Normal y D: Difícil.' ,
  imagen VARCHAR(100) NULL COMMENT 'Nombre de la imagen relativa a la pregunta.' ,
  PRIMARY KEY (codPregunta) ,
  INDEX fk_tPregunta_tTema1 (codTema ASC) ,
  CONSTRAINT fk_tPregunta_tTema1
    FOREIGN KEY (codTema )
    REFERENCES tTema (codTema )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Guarda información de preguntas.';


-- -----------------------------------------------------
-- Table tAlternativa
-- -----------------------------------------------------
CREATE  TABLE tAlternativa (
  codAlternativa INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la alternativa.' ,
  codPregunta INT UNSIGNED NOT NULL COMMENT 'Código de la pregunta al que pertenece la alternativa.' ,
  correcta ENUM('S','N') NOT NULL DEFAULT 'N' COMMENT 'Muestra si la alternativa es o no correcta.' ,
  detalle VARCHAR(255) NOT NULL COMMENT 'La alternativa.' ,
  PRIMARY KEY (codAlternativa) ,
  INDEX fk_tAlternativa_tPregunta1 (codPregunta ASC) ,
  CONSTRAINT fk_tAlternativa_tPregunta1
    FOREIGN KEY (codPregunta )
    REFERENCES tPregunta (codPregunta )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Guarda información de las alternativas.';


-- -----------------------------------------------------
-- Table tExamen
-- -----------------------------------------------------
CREATE  TABLE tExamen (
  codExamen INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador del ' ,
  nombre VARCHAR(20) NOT NULL COMMENT 'Nombre del  Ejemplo: Primera parcial de ...' ,
  PRIMARY KEY (codExamen) )
ENGINE = InnoDB
COMMENT = 'Guarda información de exámenes.';


-- -----------------------------------------------------
-- Table tExamenPregunta
-- -----------------------------------------------------
CREATE  TABLE tExamenPregunta (
  codExamen INT UNSIGNED NOT NULL COMMENT 'Código del ' ,
  codPregunta INT UNSIGNED NOT NULL COMMENT 'Código de la pregunta.' ,
  puntaje INT(2) UNSIGNED NOT NULL COMMENT 'Puntaje de la pregunta en el ' ,
  INDEX fk_tExamenPregunta_tExamen1 (codExamen ASC) ,
  PRIMARY KEY (codExamen, codPregunta) ,
  INDEX fk_tExamenPregunta_tPregunta1 (codPregunta ASC) ,
  CONSTRAINT fk_tExamenPregunta_tExamen1
    FOREIGN KEY (codExamen )
    REFERENCES tExamen (codExamen )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_tExamenPregunta_tPregunta1
    FOREIGN KEY (codPregunta )
    REFERENCES tPregunta (codPregunta )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Guarda información de preguntas relacionadas a un ';


-- -----------------------------------------------------
-- Table tExamenPrograma
-- -----------------------------------------------------
CREATE  TABLE tExamenPrograma (
  codExamen INT UNSIGNED NOT NULL COMMENT 'Identificador.' ,
  fecha TIMESTAMP NOT NULL COMMENT 'Fecha y hora de la programación.' ,
  duracion INT NOT NULL DEFAULT 3600 ,
  rendido ENUM('S','N') NOT NULL DEFAULT 'N' COMMENT 'Información sobre si se ha rendido o no el ' ,
  PRIMARY KEY (codExamen, fecha) ,
  CONSTRAINT fk_tExamenPrograma_tExamen1
    FOREIGN KEY (codExamen )
    REFERENCES tExamen (codExamen )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Guarda información de programación de exámenes.';


-- -----------------------------------------------------
-- Table tRespuesta
-- -----------------------------------------------------
CREATE  TABLE tRespuesta (
  codAlumno CHAR(6) NOT NULL COMMENT 'El código del alumno.' ,
  codExamen INT UNSIGNED NOT NULL COMMENT 'El código del ' ,
  fecha TIMESTAMP NOT NULL COMMENT 'La fecha del ' ,
  codAlternativa INT UNSIGNED NOT NULL COMMENT 'Alternativa marcada por el alumno.' ,
  PRIMARY KEY (codAlumno, codExamen, fecha, codAlternativa) ,
  INDEX fk_tRespuesta_tExamenPrograma1 (codExamen ASC, fecha ASC) ,
  INDEX fk_tRespuesta_tAlternativa1 (codAlternativa ASC) ,
  CONSTRAINT fk_tRespuesta_tAlumno1
    FOREIGN KEY (codAlumno )
    REFERENCES tAlumno (codAlumno )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_tRespuesta_tExamenPrograma1
    FOREIGN KEY (codExamen , fecha )
    REFERENCES tExamenPrograma (codExamen , fecha )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_tRespuesta_tAlternativa1
    FOREIGN KEY (codAlternativa )
    REFERENCES tAlternativa (codAlternativa )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Guarda información de respuestas de un alumno en un ';


-- -----------------------------------------------------
-- Table tSemestre
-- -----------------------------------------------------
CREATE  TABLE tSemestre (
  codSemestre VARCHAR(7) NOT NULL COMMENT 'Identificador del semestre, por ejemplo: 2012-I.' ,
  fechaInicio TIMESTAMP NOT NULL COMMENT 'Inicio del semestre.' ,
  fechaFin TIMESTAMP NOT NULL COMMENT 'Fin del semestre.' ,
  PRIMARY KEY (codSemestre) )
ENGINE = InnoDB
COMMENT = 'Guarda información de semestres.';


-- -----------------------------------------------------
-- Table tCargaAcademica
-- -----------------------------------------------------
CREATE  TABLE tCargaAcademica (
  codDocente CHAR(5) NOT NULL ,
  codCurso CHAR(8) NOT NULL ,
  codSemestre VARCHAR(7) NOT NULL ,
  PRIMARY KEY (codSemestre, codDocente, codCurso) ,
  INDEX fk_tDocenteCurso_tSemestre1 (codSemestre ASC) ,
  INDEX fk_tCargaAcademica_tDocenteCurso1 (codDocente ASC, codCurso ASC) ,
  CONSTRAINT fk_tDocenteCurso_tSemestre1
    FOREIGN KEY (codSemestre )
    REFERENCES tSemestre (codSemestre )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_tCargaAcademica_tDocenteCurso1
    FOREIGN KEY (codDocente , codCurso )
    REFERENCES tDocenteCurso (codDocente , codCurso )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Guarda información de docentes asignados a cursos.';


-- -----------------------------------------------------
-- Table tMatricula
-- -----------------------------------------------------
CREATE  TABLE tMatricula (
  codDocente CHAR(5) NOT NULL ,
  codCurso CHAR(8) NOT NULL ,
  codSemestre VARCHAR(7) NOT NULL ,
  codAlumno CHAR(6) NOT NULL COMMENT 'El código del alumno.' ,
  PRIMARY KEY (codAlumno, codDocente, codCurso, codSemestre) ,
  INDEX fk_tMatricula_tAlumno1 (codAlumno ASC) ,
  INDEX fk_tMatricula_tCargaAcademica1 (codSemestre ASC, codDocente ASC, codCurso ASC) ,
  CONSTRAINT fk_tMatricula_tAlumno1
    FOREIGN KEY (codAlumno )
    REFERENCES tAlumno (codAlumno )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_tMatricula_tCargaAcademica1
    FOREIGN KEY (codSemestre , codDocente , codCurso )
    REFERENCES tCargaAcademica (codSemestre , codDocente , codCurso )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Guarda información de matrículas de alumnos.';


-- -----------------------------------------------------
-- Table tAdministrador
-- -----------------------------------------------------
CREATE  TABLE tAdministrador (
  codAdmin INT NOT NULL AUTO_INCREMENT ,
  usuario VARCHAR(12) NOT NULL ,
  password VARCHAR(32) NOT NULL ,
  apellidoP VARCHAR(40) NOT NULL ,
  apellidoM VARCHAR(40) NOT NULL ,
  nombres VARCHAR(40) NOT NULL ,
  email VARCHAR(100) NOT NULL ,
  PRIMARY KEY (codAdmin) ,
  UNIQUE INDEX usuario_UNIQUE (usuario ASC) ,
  UNIQUE INDEX un_email (email ASC) )
ENGINE = InnoDB
COMMENT = 'Guarda datos de administradores.';


-- -----------------------------------------------------
-- Table tWatchdog
-- -----------------------------------------------------
CREATE  TABLE tWatchdog (
  codWatch INT NOT NULL AUTO_INCREMENT COMMENT 'Identificador del evento.' ,
  codUsuario VARCHAR(6) NOT NULL COMMENT 'Código del usuario que generó el evento.' ,
  tipoUsuario ENUM('A', 'D', 'S') NOT NULL DEFAULT 'S' COMMENT 'Tipo de usuario que generó el evento.' ,
  tipo VARCHAR(45) NOT NULL COMMENT 'Tipo de evento.' ,
  fecha TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha y hora del evento.' ,
  PRIMARY KEY (codWatch) )
ENGINE = InnoDB
COMMENT = 'Registra eventos del sistema.';


-- -----------------------------------------------------
-- Table tOpcion
-- -----------------------------------------------------
CREATE  TABLE tOpcion (
  codOpcion INT NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la opción.' ,
  nombre VARCHAR(45) NOT NULL COMMENT 'La opción.' ,
  descripcion TEXT NOT NULL COMMENT 'Valor de la opción.' ,
  PRIMARY KEY (codOpcion) ,
  UNIQUE INDEX nombre_UNIQUE (nombre ASC) )
ENGINE = InnoDB
COMMENT = 'Opciones del Sistema.';
