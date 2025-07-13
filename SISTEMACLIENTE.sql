-- Creación de la base de datos
CREATE DATABASE IF NOT EXISTS sistema_rutas;
USE sistema_rutas;

-- Tabla de Monedas
CREATE TABLE moneda (
    id_moneda INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(10) NOT NULL,
    nombre VARCHAR(50) NOT NULL,
    simbolo VARCHAR(5) NOT NULL,
    activa BOOLEAN DEFAULT TRUE
);

-- Insertar datos iniciales de monedas
INSERT INTO moneda (codigo, nombre, simbolo) VALUES
('PEN', 'Soles Peruanos', 'S/.'),
('USD', 'Dólares Americanos', '$'),
('COP', 'Pesos Colombianos', '$'),
('PYG', 'Guaraníes Paraguayos', '₲');

-- Tabla de Tipos de Documento
CREATE TABLE tipo_documento (
    id_tipo_documento INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    descripcion VARCHAR(255),
    valido_para_credito BOOLEAN DEFAULT TRUE
);

-- Tabla de Tipos de Cobro
CREATE TABLE tipo_cobro (
    id_tipo_cobro INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    descripcion VARCHAR(255),
    dias_frecuencia INT NOT NULL COMMENT 'Número de días entre cobros'
);

-- Tabla de Seguros
CREATE TABLE seguro (
    id_seguro INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    activo BOOLEAN DEFAULT TRUE,
    costo DECIMAL(10,2) DEFAULT 0.00
);

-- Tabla de Oficinas
CREATE TABLE oficina (
    id_oficina INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    id_moneda INT,
    pais VARCHAR(50) NOT NULL,
    codigo VARCHAR(20),
    porcentajes_credito VARCHAR(255) COMMENT 'Porcentajes separados por comas',
    ver_caja_anterior BOOLEAN DEFAULT FALSE,
    ver_entradas_salidas BOOLEAN DEFAULT FALSE,
    consultar_cuadre_pasado BOOLEAN DEFAULT FALSE,
    cobrador_edita_clientes BOOLEAN DEFAULT FALSE,
    cobrador_ingresos_gastos BOOLEAN DEFAULT TRUE,
    pedir_base_al_ingresar BOOLEAN DEFAULT FALSE,
    liquidar_rutas BOOLEAN DEFAULT FALSE,
    foto_documento_obligatoria BOOLEAN DEFAULT FALSE,
    cambiar_claves_usuarios BOOLEAN DEFAULT FALSE,
    creditos_requieren_autorizacion BOOLEAN DEFAULT FALSE,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_moneda) REFERENCES moneda(id_moneda)
);

CREATE TABLE configuracion_abonos (
    id_configuracion INT AUTO_INCREMENT PRIMARY KEY,
    id_oficina INT NOT NULL,
    max_abonos_dia INT NOT NULL DEFAULT 1 COMMENT 'Valores posibles: 1-6 (como muestra la interfaz)',
    sin_limite BOOLEAN DEFAULT FALSE COMMENT 'Cuando es TRUE, muestra "Sin límite" como en la interfaz',
    aplica_desde TIME DEFAULT '00:00:00' COMMENT 'Hora de inicio de la restricción',
    aplica_hasta TIME DEFAULT '23:59:59' COMMENT 'Hora de fin de la restricción',
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_oficina) REFERENCES oficina(id_oficina) ON DELETE CASCADE,
    UNIQUE KEY (id_oficina) -- Garantiza una sola configuración por oficina
);
-- Tabla de relación entre Oficinas y Seguros
CREATE TABLE oficina_seguro (
    id_oficina_seguro INT AUTO_INCREMENT PRIMARY KEY,
    id_oficina INT NOT NULL,
    id_seguro INT NOT NULL,
    activo BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (id_oficina) REFERENCES oficina(id_oficina),
    FOREIGN KEY (id_seguro) REFERENCES seguro(id_seguro),
    UNIQUE KEY (id_oficina, id_seguro)
);

-- Tabla de Usuarios
CREATE TABLE usuario (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    contrasena VARCHAR(255) NOT NULL,
    id_oficina INT,
    es_administrador BOOLEAN DEFAULT FALSE,
    es_cobrador BOOLEAN DEFAULT FALSE,
    activo BOOLEAN DEFAULT TRUE,
    fecha_ultimo_login TIMESTAMP NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_oficina) REFERENCES oficina(id_oficina)
);

-- Tabla de Rutas
CREATE TABLE ruta (
    id_ruta INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    id_oficina INT NOT NULL,
    creada_en DATE NOT NULL,
    activa BOOLEAN DEFAULT FALSE,
    id_tipo_documento INT,
    id_tipo_cobro INT,
    agregar_ceros_cantidades BOOLEAN DEFAULT FALSE COMMENT 'Agregar 3 ceros a las cantidades',
    editar_interes_credito BOOLEAN DEFAULT FALSE,
    considerar_domingos_pago BOOLEAN DEFAULT FALSE,
    enrutamiento_automatico BOOLEAN DEFAULT FALSE,
    porcentajes_credito VARCHAR(255),
    cobradores_agregan_gastos BOOLEAN DEFAULT TRUE,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_oficina) REFERENCES oficina(id_oficina),
    FOREIGN KEY (id_tipo_documento) REFERENCES tipo_documento(id_tipo_documento),
    FOREIGN KEY (id_tipo_cobro) REFERENCES tipo_cobro(id_tipo_cobro)
);

-- Tabla de relación entre Rutas y Usuarios
CREATE TABLE ruta_usuario (
    id_ruta_usuario INT AUTO_INCREMENT PRIMARY KEY,
    id_ruta INT NOT NULL,
    id_usuario INT NOT NULL,
    es_responsable BOOLEAN DEFAULT FALSE,
    asignado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_ruta) REFERENCES ruta(id_ruta),
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario),
    UNIQUE KEY (id_ruta, id_usuario)
);

-- Tabla de Socios
CREATE TABLE socio (
    id_socio INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    documento VARCHAR(50) NOT NULL,
    id_tipo_documento INT,
    telefono VARCHAR(20),
    email VARCHAR(100),
    direccion TEXT,
    activo BOOLEAN DEFAULT TRUE,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_tipo_documento) REFERENCES tipo_documento(id_tipo_documento)
);

-- Tabla de relación entre Rutas y Socios
CREATE TABLE ruta_socio (
    id_ruta_socio INT AUTO_INCREMENT PRIMARY KEY,
    id_ruta INT NOT NULL,
    id_socio INT NOT NULL,
    asignado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    activo BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (id_ruta) REFERENCES ruta(id_ruta),
    FOREIGN KEY (id_socio) REFERENCES socio(id_socio),
    UNIQUE KEY (id_ruta, id_socio)
);

-- Tabla de Historial de Cambios
CREATE TABLE historial_cambios (
    id_historial INT AUTO_INCREMENT PRIMARY KEY,
    tabla_afectada VARCHAR(50) NOT NULL,
    id_registro INT NOT NULL,
    campo_modificado VARCHAR(50) NOT NULL,
    valor_anterior TEXT,
    valor_nuevo TEXT,
    id_usuario INT,
    fecha_cambio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ip_address VARCHAR(45),
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario)
);

-- Tabla de Sesiones
CREATE TABLE sesion (
    id_sesion INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    token VARCHAR(255) NOT NULL,
    fecha_inicio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_ultima_actividad TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    ip_address VARCHAR(45),
    user_agent TEXT,
    activa BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario)
);

-- Insertar datos iniciales para tipos de documento
INSERT INTO tipo_documento (nombre, valido_para_credito) VALUES
('Cédula de Ciudadanía', TRUE),
('DNI', TRUE),
('Pasaporte', FALSE),
('RUC', TRUE);

-- Insertar tipos de cobro
INSERT INTO tipo_cobro (nombre, dias_frecuencia) VALUES
('Diario', 1),
('Semanal', 7),
('Quincenal', 15),
('Mensual', 30);

-- Insertar datos iniciales para seguros
INSERT INTO seguro (nombre, descripcion, costo) VALUES
('Seguro de Vida', 'Protección en caso de fallecimiento', 15.00),
('Seguro de Desgravamen', 'Cobertura para saldo deudor', 10.50),
('Seguro contra Accidentes', 'Protección por accidentes', 8.75);

-- Insertar datos iniciales para oficinas
INSERT INTO oficina (
    nombre,
    id_moneda,
    pais,
    porcentajes_credito,
    cobrador_edita_clientes,
    foto_documento_obligatoria
) VALUES
('Perú', 1, 'Perú', '20%,25%,30%', TRUE, TRUE),
('Colombia', 3, 'Colombia', '15%,20%,25%', FALSE, FALSE),
('Paraguay', 4, 'Paraguay', '10%,15%,20%', TRUE, TRUE);

-- Crear índices para mejorar rendimiento
CREATE INDEX idx_ruta_activa ON ruta(activa);
CREATE INDEX idx_ruta_oficina ON ruta(id_oficina);
CREATE INDEX idx_usuario_oficina ON usuario(id_oficina);
CREATE INDEX idx_socio_documento ON socio(documento);
CREATE INDEX idx_sesion_token ON sesion(token);
CREATE INDEX idx_sesion_usuario ON sesion(id_usuario);
CREATE INDEX idx_historial_tabla ON historial_cambios(tabla_afectada);
CREATE INDEX idx_historial_usuario ON historial_cambios(id_usuario);




HASTA ACA VA SIN EL USUARIO











CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    descripcion VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Tabla de permisos
CREATE TABLE permisos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    descripcion VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Tabla de usuarios
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    apellidos VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    celular VARCHAR(20) NOT NULL UNIQUE COMMENT 'Usuario para iniciar sesión',
    password VARCHAR(255) NOT NULL,
    rol_id INT NOT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    ultimo_acceso DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (rol_id) REFERENCES roles(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- Tabla de información laboral
CREATE TABLE informacion_laboral (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    cargo VARCHAR(100) NOT NULL,
    pais VARCHAR(50),
    ciudad VARCHAR(50),
    fecha_ingreso DATE,
    fecha_salida DATE,
    ruta_asignada VARCHAR(100),
    comentarios TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Tabla de relación entre roles y permisos
CREATE TABLE rol_permiso (
    rol_id INT NOT NULL,
    permiso_id INT NOT NULL,
    PRIMARY KEY (rol_id, permiso_id),
    FOREIGN KEY (rol_id) REFERENCES roles(id) ON DELETE CASCADE,
    FOREIGN KEY (permiso_id) REFERENCES permisos(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Tabla de permisos personalizados por usuario
CREATE TABLE usuario_permiso (
    usuario_id INT NOT NULL,
    permiso_id INT NOT NULL,
    concedido BOOLEAN DEFAULT FALSE,
    PRIMARY KEY (usuario_id, permiso_id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (permiso_id) REFERENCES permisos(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Tabla de historial de accesos (opcional)
CREATE TABLE historial_accesos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    fecha_hora DATETIME NOT NULL,
    ip VARCHAR(45),
    dispositivo VARCHAR(255),
    accion VARCHAR(100),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB;


-- Insertar roles básicos
INSERT INTO roles (nombre, descripcion) VALUES
('Administrador', 'Acceso completo al sistema'),
('Cobrador', 'Realiza cobros y gestiona pagos'),
('Encargado de oficina', 'Gestiona operaciones de oficina'),
('Revisador', 'Revisa y valida documentos'),
('Super Admin', 'Superusuario con todos los privilegios');

-- Insertar permisos disponibles
INSERT INTO permisos (nombre, descripcion) VALUES
('Sistema Web', 'Acceso al sistema web'),
('Movimientos', 'Gestionar movimientos financieros'),
('Créditos', 'Administrar créditos y préstamos'),
('Reportes', 'Generar y ver reportes'),
('Configuración', 'Configurar parámetros del sistema'),
('CrossBox', 'Acceso al módulo CrossBox'),
('Acciones del Sistema', 'Realizar acciones administrativas del sistema');

-- Asignar permisos a roles
-- Administrador
INSERT INTO rol_permiso (rol_id, permiso_id) VALUES
(1, 1), (1, 2), (1, 3), (1, 4), (1, 5), (1, 6), (1, 7);

-- Cobrador
INSERT INTO rol_permiso (rol_id, permiso_id) VALUES
(2, 1), (2, 2);

-- Encargado de oficina
INSERT INTO rol_permiso (rol_id, permiso_id) VALUES
(3, 1), (3, 2), (3, 3), (3, 4);

-- Revisador
INSERT INTO rol_permiso (rol_id, permiso_id) VALUES
(4, 1), (4, 3), (4, 4);

-- Super Admin (todos los permisos)
INSERT INTO rol_permiso (rol_id, permiso_id) VALUES
(5, 1), (5, 2), (5, 3), (5, 4), (5, 5), (5, 6), (5, 7);

-- Insertar usuario de ejemplo
INSERT INTO usuarios (nombre, apellidos, email, celular, password, rol_id, status) VALUES
('Fiorella', 'Familia', 'fiorella@example.com', '921522040', SHA2('password123', 256), 2, 'active');

-- Insertar información laboral del usuario
INSERT INTO informacion_laboral (usuario_id, cargo, pais, ciudad, fecha_ingreso, ruta_asignada) VALUES
(1, 'Cobrador', 'Perú', 'Lima', '2025-07-10', 'Ruta Norte');
