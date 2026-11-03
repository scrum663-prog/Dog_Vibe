-- Base de datos DOG_NEXT para MySQL / MariaDB (phpMyAdmin)
-- Importar este archivo desde la pestaña Importar de phpMyAdmin.
-- Nota: si ya existe una base de datos con este nombre, se reutilizará.

CREATE DATABASE IF NOT EXISTS DOG_NEXT
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE DOG_NEXT;

-- 1. USUARIO
CREATE TABLE IF NOT EXISTS usuario (
    id_usuario INT NOT NULL AUTO_INCREMENT,
    Nombre VARCHAR(100) NULL,
    Apellido VARCHAR(100) NULL,
    Correo VARCHAR(100) NULL,
    Telefono VARCHAR(20) NULL,
    `Contraseña` VARCHAR(255) NULL,
    Direccion VARCHAR(150) NULL,
    Tipo_usuario VARCHAR(100) NULL,
    Especialidad VARCHAR(100) NULL,
    Experiencia INT NULL,
    Registro_profesional VARCHAR(100) NULL,
    CONSTRAINT PK_usuario PRIMARY KEY (id_usuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. SERVICIO
CREATE TABLE IF NOT EXISTS servicio (
    id_servicio INT NOT NULL AUTO_INCREMENT,
    Nombre VARCHAR(100) NULL,
    Descripcion TEXT NULL,
    Precio DECIMAL(10,2) NULL,
    Duracion INT NULL COMMENT 'Duración en minutos',
    CONSTRAINT PK_servicio PRIMARY KEY (id_servicio)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. RESERVA
CREATE TABLE IF NOT EXISTS reserva (
    id_reserva INT NOT NULL AUTO_INCREMENT,
    Fecha DATE NULL,
    Hora TIME NULL,
    Estado VARCHAR(30) NULL,
    id_usuario INT NULL,
    id_servicio INT NULL,
    CONSTRAINT PK_reserva PRIMARY KEY (id_reserva),
    CONSTRAINT FK_reserva_usuario FOREIGN KEY (id_usuario)
        REFERENCES usuario(id_usuario),
    CONSTRAINT FK_reserva_servicio FOREIGN KEY (id_servicio)
        REFERENCES servicio(id_servicio)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. CERTIFICACION
CREATE TABLE IF NOT EXISTS certificacion (
    id_certificacion INT NOT NULL AUTO_INCREMENT,
    Nombre VARCHAR(100) NULL,
    Entidad_emisora VARCHAR(120) NULL,
    Numero VARCHAR(80) NULL,
    Fecha_emision DATE NULL,
    Fecha_vencimiento DATE NULL,
    id_usuario INT NULL,
    CONSTRAINT PK_certificacion PRIMARY KEY (id_certificacion),
    CONSTRAINT FK_certificacion_usuario FOREIGN KEY (id_usuario)
        REFERENCES usuario(id_usuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. DISPONIBILIDAD
CREATE TABLE IF NOT EXISTS disponibilidad (
    id_disponibilidad INT NOT NULL AUTO_INCREMENT,
    Dia VARCHAR(20) NULL,
    Hora_inicio TIME NULL,
    Hora_fin TIME NULL,
    Estado VARCHAR(30) NULL,
    id_usuario INT NULL,
    CONSTRAINT PK_disponibilidad PRIMARY KEY (id_disponibilidad),
    CONSTRAINT FK_disponibilidad_usuario FOREIGN KEY (id_usuario)
        REFERENCES usuario(id_usuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. ENTRENAMIENTO
CREATE TABLE IF NOT EXISTS entrenamiento (
    id_entrenamiento INT NOT NULL AUTO_INCREMENT,
    Objetivo VARCHAR(150) NULL,
    Progreso VARCHAR(100) NULL,
    Observaciones TEXT NULL,
    Fecha DATE NULL,
    id_reserva INT NULL,
    CONSTRAINT PK_entrenamiento PRIMARY KEY (id_entrenamiento),
    CONSTRAINT FK_entrenamiento_reserva FOREIGN KEY (id_reserva)
        REFERENCES reserva(id_reserva)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. MASCOTA
CREATE TABLE IF NOT EXISTS mascota (
    id_mascota INT NOT NULL AUTO_INCREMENT,
    id_usuario INT NULL,
    Nombre VARCHAR(80) NULL,
    Especie VARCHAR(50) NULL,
    Raza VARCHAR(80) NULL,
    Edad INT NULL,
    Sexo VARCHAR(20) NULL,
    Peso DECIMAL(6,2) NULL,
    CONSTRAINT PK_mascota PRIMARY KEY (id_mascota),
    CONSTRAINT FK_mascota_usuario FOREIGN KEY (id_usuario)
        REFERENCES usuario(id_usuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. MENSAJE
CREATE TABLE IF NOT EXISTS mensaje (
    id_chatprivado INT NOT NULL AUTO_INCREMENT,
    Contenido TEXT NULL,
    Fecha DATE NULL,
    Hora TIME NULL,
    id_usuario INT NULL,
    CONSTRAINT PK_mensaje PRIMARY KEY (id_chatprivado),
    CONSTRAINT FK_mensaje_usuario FOREIGN KEY (id_usuario)
        REFERENCES usuario(id_usuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. NOTIFICACION
CREATE TABLE IF NOT EXISTS notificacion (
    id_notificacion INT NOT NULL AUTO_INCREMENT,
    Tipo VARCHAR(50) NULL,
    Contenido TEXT NULL,
    Fecha DATE NULL,
    Hora TIME NULL,
    Estado VARCHAR(30) NULL,
    id_usuario INT NULL,
    CONSTRAINT PK_notificacion PRIMARY KEY (id_notificacion),
    CONSTRAINT FK_notificacion_usuario FOREIGN KEY (id_usuario)
        REFERENCES usuario(id_usuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10. PAGO
CREATE TABLE IF NOT EXISTS pago (
    id_pago INT NOT NULL AUTO_INCREMENT,
    Valor DECIMAL(10,2) NULL,
    Metodo VARCHAR(30) NULL,
    Fecha DATE NULL,
    Estado VARCHAR(30) NULL,
    id_reserva INT NULL,
    CONSTRAINT PK_pago PRIMARY KEY (id_pago),
    CONSTRAINT FK_pago_reserva FOREIGN KEY (id_reserva)
        REFERENCES reserva(id_reserva)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 11. REPORTE
CREATE TABLE IF NOT EXISTS reporte (
    id_reporte INT NOT NULL AUTO_INCREMENT,
    Tipo VARCHAR(50) NULL,
    Descripcion TEXT NULL,
    Fecha DATE NULL,
    Estado VARCHAR(30) NULL,
    id_usuario INT NULL,
    CONSTRAINT PK_reporte PRIMARY KEY (id_reporte),
    CONSTRAINT FK_reporte_usuario FOREIGN KEY (id_usuario)
        REFERENCES usuario(id_usuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 12. RESENA
CREATE TABLE IF NOT EXISTS resena (
    id_resena INT NOT NULL AUTO_INCREMENT,
    Calificacion INT NULL,
    Comentario TEXT NULL,
    Fecha DATE NULL,
    id_usuario INT NULL,
    id_reserva INT NULL,
    CONSTRAINT PK_resena PRIMARY KEY (id_resena),
    CONSTRAINT FK_resena_usuario FOREIGN KEY (id_usuario)
        REFERENCES usuario(id_usuario),
    CONSTRAINT FK_resena_reserva FOREIGN KEY (id_reserva)
        REFERENCES reserva(id_reserva)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- DATOS DE PRUEBA
-- Las inserciones se ejecutan solo si se vuelve a importar en una base vacía.
INSERT INTO usuario
(Nombre, Apellido, Correo, Telefono, `Contraseña`, Direccion, Tipo_usuario, Especialidad, Experiencia, Registro_profesional)
VALUES
('Marlon','Rodriguez','marlon@gmail.com','3001112233','12345','Bogota','Dueño',NULL,NULL,NULL),
('Carlos','Gomez','carlos@gmail.com','3012223344','12345','Bogota','Entrenador','Adiestramiento canino',5,'RP001'),
('Laura','Martinez','laura@gmail.com','3023334455','12345','Medellin','Entrenador','Educacion canina',3,'RP002'),
('Andres','Lopez','andres@gmail.com','3034445566','12345','Cali','Dueño',NULL,NULL,NULL);

INSERT INTO servicio (Nombre, Descripcion, Precio, Duracion)
VALUES
('Adiestramiento Básico','Clases de obediencia para cachorros',50000.00,60),
('Corrección de conducta','Modificación de conductas agresivas',70000.00,60),
('Socialización avanzada','Sesiones grupales de juego guiado',60000.00,90);

INSERT INTO reserva (Fecha, Hora, Estado, id_usuario, id_servicio)
VALUES
('2026-10-10','08:00:00','Confirmada',1,1),
('2026-10-12','14:00:00','Pendiente',1,2),
('2026-10-15','09:00:00','Confirmada',4,3);

INSERT INTO certificacion
(Nombre, Entidad_emisora, Numero, Fecha_emision, Fecha_vencimiento, id_usuario)
VALUES
('Certificacion en Adiestramiento Canino','Instituto Canino Colombia','CERT001','2024-02-15','2027-02-15',2),
('Certificacion en Educacion Canina','Academia Canina Nacional','CERT002','2025-03-10','2028-03-10',3);

INSERT INTO disponibilidad (Dia, Hora_inicio, Hora_fin, Estado, id_usuario)
VALUES
('Lunes','08:00:00','12:00:00','Disponible',2),
('Miercoles','14:00:00','18:00:00','Disponible',2),
('Martes','09:00:00','13:00:00','Disponible',3),
('Jueves','15:00:00','19:00:00','Disponible',3);

INSERT INTO entrenamiento (Objetivo, Progreso, Observaciones, Fecha, id_reserva)
VALUES
('Mejorar obediencia basica','80%','La mascota responde correctamente a los comandos.','2026-10-10',1),
('Corregir comportamiento','60%','Se recomienda continuar con las sesiones.','2026-10-12',2),
('Mejorar socializacion','70%','Presenta avances positivos con otros perros.','2026-10-15',3);

INSERT INTO mascota (id_usuario, Nombre, Especie, Raza, Edad, Sexo, Peso)
VALUES
(1,'Blacky','Perro','Criollo',3,'Macho',15.50),
(1,'Max','Perro','Labrador',2,'Macho',20.30),
(4,'Luna','Perro','Beagle',4,'Hembra',12.80);

INSERT INTO mensaje (Contenido, Fecha, Hora, id_usuario)
VALUES
('Hola, quisiera conocer los horarios disponibles.','2026-10-08','09:30:00',1),
('Claro, tengo disponibilidad el dia lunes.','2026-10-08','10:00:00',2),
('Puedo reservar una sesion para mi mascota.','2026-10-08','11:15:00',4);

INSERT INTO notificacion (Tipo, Contenido, Fecha, Hora, Estado, id_usuario)
VALUES
('Reserva','Tu reserva ha sido confirmada.','2026-10-08','10:30:00','Leida',1),
('Mensaje','Tienes un nuevo mensaje.','2026-10-08','11:00:00','Pendiente',2),
('Reserva','Tienes una nueva solicitud de reserva.','2026-10-08','11:30:00','Pendiente',3);

INSERT INTO pago (Valor, Metodo, Fecha, Estado, id_reserva)
VALUES
(50000,'Efectivo','2026-10-10','Pagado',1),
(70000,'Transferencia','2026-10-12','Pendiente',2),
(60000,'Tarjeta','2026-10-15','Pagado',3);

INSERT INTO reporte (Tipo, Descripcion, Fecha, Estado, id_usuario)
VALUES
('Servicio','Reporte sobre inconveniente con un servicio.','2026-10-08','Pendiente',1),
('Usuario','Reporte relacionado con un usuario.','2026-10-08','En revision',4);

INSERT INTO resena (Calificacion, Comentario, Fecha, id_usuario, id_reserva)
VALUES
(5,'Excelente servicio y muy buen trato.','2026-10-10',1,1),
(4,'Buen entrenamiento, recomendado.','2026-10-15',4,3);

-- CONSULTAS DE EJEMPLO

-- Mascotas y sus dueños
SELECT
    u.Nombre,
    u.Apellido,
    m.Nombre AS Mascota,
    m.Especie,
    m.Raza
FROM usuario u
INNER JOIN mascota m ON u.id_usuario = m.id_usuario;

-- Reservas con dueño, mascota y servicio.
-- Nota: como reserva no tiene id_mascota, esta consulta puede mostrar varias
-- filas por reserva si un usuario tiene más de una mascota.
SELECT
    r.id_reserva,
    u.Nombre,
    u.Apellido,
    m.Nombre AS Mascota,
    s.Nombre AS Servicio,
    r.Fecha,
    r.Hora,
    r.Estado
FROM reserva r
INNER JOIN usuario u ON r.id_usuario = u.id_usuario
INNER JOIN mascota m ON u.id_usuario = m.id_usuario
INNER JOIN servicio s ON r.id_servicio = s.id_servicio;

-- Reservas y precio del servicio
SELECT
    r.id_reserva,
    u.Nombre,
    u.Apellido,
    s.Nombre AS Servicio,
    s.Precio AS Total
FROM reserva r
INNER JOIN usuario u ON r.id_usuario = u.id_usuario
INNER JOIN servicio s ON r.id_servicio = s.id_servicio;

-- Usuarios que no tienen mascotas
SELECT
    u.id_usuario,
    u.Nombre,
    u.Apellido,
    u.Correo
FROM usuario u
LEFT JOIN mascota m ON u.id_usuario = m.id_usuario
WHERE m.id_mascota IS NULL;
