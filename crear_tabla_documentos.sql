CREATE TABLE documentos (
    id SERIAL PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    contenido TEXT,
    usuario_id INTEGER REFERENCES usuario(id),
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
