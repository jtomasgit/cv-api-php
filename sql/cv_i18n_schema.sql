-- Script completo con etiquetas UI para traducción de títulos de sección

-- (Asume que ya existe la base de datos cv_i18n y las tablas previas)
USE cv_i18n;

-- Tabla de etiquetas para la UI multilenguaje
DROP TABLE IF EXISTS etiquetas_ui;
CREATE TABLE etiquetas_ui (
  clave     VARCHAR(50)    NOT NULL,
  id_local  VARCHAR(5)     NOT NULL,
  texto     VARCHAR(100)   NOT NULL,
  PRIMARY KEY (clave, id_local),
  FOREIGN KEY (id_local) REFERENCES locales(id_local)
);

-- Inserción de títulos de sección en distintos idiomas
INSERT INTO etiquetas_ui (clave, id_local, texto) VALUES
  ('sobre_mi',     'es', 'Sobre mí'),
  ('sobre_mi',     'en', 'About me'),
  ('experiencia',  'es', 'Experiencia'),
  ('experiencia',  'en', 'Experience'),
  ('educacion',    'es', 'Educación'),
  ('educacion',    'en', 'Education'),
  ('habilidades',  'es', 'Habilidades'),
  ('habilidades',  'en', 'Skills'),
  ('idiomas',      'es', 'Idiomas'),
  ('idiomas',      'en', 'Languages');
