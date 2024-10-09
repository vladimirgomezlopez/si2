CREATE TABLE comentarios (
    id SERIAL PRIMARY KEY,
    documento_id INTEGER REFERENCES documentos(id),
    usuario_id INTEGER REFERENCES usuarios(id),
    contenido TEXT NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
