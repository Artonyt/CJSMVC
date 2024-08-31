-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 01-09-2024 a las 01:13:12
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `colegio`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignaturas`
--

CREATE TABLE `asignaturas` (
  `ID_asignatura` int(11) NOT NULL,
  `Nombre_asignatura` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `asignaturas`
--

INSERT INTO `asignaturas` (`ID_asignatura`, `Nombre_asignatura`) VALUES
(1, 'Ética y Valores'),
(2, 'Humanidades'),
(3, 'Educación Física'),
(4, 'Tecnología'),
(5, 'Artes'),
(6, 'Idiomas'),
(7, 'Matemáticas'),
(8, 'Ciencias Naturales');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencia`
--

CREATE TABLE `asistencia` (
  `ID_asistencia` int(11) NOT NULL,
  `ID_estudiante` int(11) DEFAULT NULL,
  `ID_materia` int(11) DEFAULT NULL,
  `Fecha` date DEFAULT NULL,
  `Estado` enum('Justificada','Falla','Asistencia') DEFAULT NULL,
  `Excusa_imagen` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `asistencia`
--

INSERT INTO `asistencia` (`ID_asistencia`, `ID_estudiante`, `ID_materia`, `Fecha`, `Estado`, `Excusa_imagen`) VALUES
(36, 14, 19, '2024-08-29', '', NULL),
(37, 14, 19, '2024-08-16', '', NULL),
(38, 14, 19, '2024-08-15', '', NULL),
(39, 14, 19, '2024-08-08', '', NULL),
(40, 15, 19, '2024-08-08', '', NULL),
(41, 16, 19, '2024-08-08', '', NULL),
(42, 15, 19, '2024-08-15', '', NULL),
(43, 16, 19, '2024-08-15', '', NULL),
(44, 14, 19, '2024-08-07', '', NULL),
(45, 14, 19, '2024-08-11', '', NULL),
(46, 5, 2, '2024-08-21', '', NULL),
(47, 5, 2, '2024-08-29', '', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cursos`
--

CREATE TABLE `cursos` (
  `ID_curso` int(11) NOT NULL,
  `Nombre_curso` varchar(50) DEFAULT NULL,
  `ID_grado` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `cursos`
--

INSERT INTO `cursos` (`ID_curso`, `Nombre_curso`, `ID_grado`) VALUES
(1, '101', 1),
(2, '102', 1),
(3, '103', 1),
(4, '201', 2),
(5, '202', 2),
(6, '203', 2),
(7, '301', 3),
(8, '302', 3),
(9, '303', 3),
(10, '401', 4),
(11, '402', 4),
(12, '403', 4),
(13, '501', 5),
(14, '502', 5),
(15, '503', 5),
(16, '601', 6),
(17, '602', 6),
(18, '603', 6),
(19, '701', 7),
(20, '702', 7),
(21, '703', 7),
(22, '801', 8),
(23, '802', 8),
(24, '803', 8),
(25, '901', 9),
(26, '902', 9),
(27, '903', 9),
(28, '1001', 10),
(29, '1002', 10),
(30, '1003', 10),
(31, '1101', 11),
(32, '1102', 11),
(33, '1103', 11);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `docentes_materias`
--

CREATE TABLE `docentes_materias` (
  `ID_docente_materia` int(11) NOT NULL,
  `ID_docente` int(11) DEFAULT NULL,
  `ID_materia` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `docentes_materias`
--

INSERT INTO `docentes_materias` (`ID_docente_materia`, `ID_docente`, `ID_materia`) VALUES
(4, 1, 1),
(5, 1, 2),
(8, 30, 18);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiantes`
--

CREATE TABLE `estudiantes` (
  `ID_estudiante` int(11) NOT NULL,
  `Nombres` varchar(50) DEFAULT NULL,
  `Apellidos` varchar(50) DEFAULT NULL,
  `Identificacion` varchar(20) DEFAULT NULL,
  `Fecha_nacimiento` date DEFAULT NULL,
  `Genero` enum('Masculino','Femenino') DEFAULT NULL,
  `Direccion` varchar(100) DEFAULT NULL,
  `Telefono` varchar(15) DEFAULT NULL,
  `Correo_electronico` varchar(50) DEFAULT NULL,
  `ID_curso` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `estudiantes`
--

INSERT INTO `estudiantes` (`ID_estudiante`, `Nombres`, `Apellidos`, `Identificacion`, `Fecha_nacimiento`, `Genero`, `Direccion`, `Telefono`, `Correo_electronico`, `ID_curso`) VALUES
(1, 'Juan', 'Pérez', '1234567890', '2000-01-15', 'Masculino', 'Calle Falsa 123', '3001234567', 'juan.perez@example.com', 1),
(2, 'Ana', 'Gómez', '2345678901', '1999-02-20', 'Femenino', 'Avenida Siempre Viva 456', '3007654321', 'ana.gomez@example.com', 3),
(3, 'Carlos', 'Martínez', '3456789012', '2001-03-30', 'Masculino', 'Calle Luna 789', '3001122334', 'carlos.martinez@example.com', 3),
(4, 'Maria', 'Rodríguez', '4567890123', '2002-04-25', 'Femenino', 'Calle Sol 321', '3005566778', 'maria.rodriguez@example.com', 1),
(5, 'Luis', 'Hernández', '5678901234', '2000-05-12', 'Masculino', 'Carrera 7 654', '3003344556', 'luis.hernandez@example.com', 2),
(6, 'Sofia', 'García', '6789012345', '1999-06-22', 'Femenino', 'Calle 45 987', '3004455667', 'sofia.garcia@example.com', 3),
(7, 'Miguel', 'Lopez', '7890123456', '2001-07-10', 'Masculino', 'Calle 6 543', '3002233445', 'miguel.lopez@example.com', 1),
(8, 'Laura', 'Martínez', '8901234567', '2002-08-05', 'Femenino', 'Calle 2 876', '3003344555', 'laura.martinez@example.com', 2),
(9, 'Pedro', 'Vázquez', '9012345678', '2000-09-15', 'Masculino', 'Carrera 8 210', '3005566779', 'pedro.vazquez@example.com', 3),
(10, 'Claudia', 'Pérez', '0123456789', '1999-10-25', 'Femenino', 'Calle 9 321', '3006677889', 'claudia.perez@example.com', 1),
(11, 'Andrés', 'Jiménez', '1234567891', '2001-11-30', 'Masculino', 'Calle 10 654', '3007788990', 'andres.jimenez@example.com', 2),
(13, 'Felipe', 'Castro', '3456789013', '1999-01-10', 'Masculino', 'Calle 12 543', '3009900112', 'felipe.castro@example.com', 1),
(14, 'Isabella', 'Torres', '4567890124', '2001-02-20', 'Femenino', 'Calle 13 876', '3010011223', 'isabella.torres@example.com', 2),
(15, 'Sebastián', 'Núñez', '5678901235', '2000-03-30', 'Masculino', 'Calle 14 210', '3011122334', 'sebastian.nunez@example.com', 3),
(16, 'Camila', 'Ramos', '6789012346', '1999-04-25', 'Femenino', 'Calle 15 321', '3012233445', 'camila.ramos@example.com', 19),
(17, 'Santiago', 'Gómez', '7890123457', '2001-05-12', 'Masculino', 'Calle 16 654', '3013344556', 'santiago.gomez@example.com', 2),
(18, 'Gabriela', 'Hernández', '8901234568', '2000-06-22', 'Femenino', 'Calle 17 987', '3014455667', 'gabriela.hernandez@example.com', 3),
(19, 'Daniel', 'Ortiz', '9012345679', '1999-07-10', 'Masculino', 'Calle 18 543', '3015566778', 'daniel.ortiz@example.com', 1),
(20, 'Natalia', 'Jiménez', '0123456780', '2001-08-05', 'Femenino', 'Calle 19 876', '3016677889', 'natalia.jimenez@example.com', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grados`
--

CREATE TABLE `grados` (
  `ID_grado` int(11) NOT NULL,
  `Nombre_grado` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `grados`
--

INSERT INTO `grados` (`ID_grado`, `Nombre_grado`) VALUES
(1, 'Primero'),
(2, 'Segundo'),
(3, 'Tercero'),
(4, 'Cuarto'),
(5, 'Quinto'),
(6, 'Sexto'),
(7, 'Séptimo'),
(8, 'Octavo'),
(9, 'Noveno'),
(10, 'Décimo'),
(11, 'Undécimo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `materias`
--

CREATE TABLE `materias` (
  `ID_materia` int(11) NOT NULL,
  `Nombre_materia` varchar(50) DEFAULT NULL,
  `ID_asignatura` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `materias`
--

INSERT INTO `materias` (`ID_materia`, `Nombre_materia`, `ID_asignatura`) VALUES
(1, 'Ética', 1),
(2, 'Religión', 1),
(3, 'Español', 2),
(4, 'Educación Física', 3),
(5, 'Informática', 4),
(6, 'Artes', 5),
(7, 'Inglés', 6),
(8, 'Matemáticas', 7),
(9, 'Ciencias Naturales', 8),
(10, 'Estadística', 7),
(11, 'Geometría', 7),
(12, 'Química', 8),
(13, 'Física', 8),
(14, 'Democracia', 1),
(15, 'Geografía', 2),
(16, 'Historia', 2),
(17, 'Cátedra de la paz', 1),
(18, 'Trigonometría', 7),
(19, 'Filosofía', 2),
(20, 'Política', 2),
(21, 'Economía', 2),
(22, 'Álgebra', 7),
(23, 'Danzas', 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `materias_cursos`
--

CREATE TABLE `materias_cursos` (
  `ID_materia` int(11) NOT NULL,
  `ID_curso` int(11) NOT NULL,
  `ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `materias_cursos`
--

INSERT INTO `materias_cursos` (`ID_materia`, `ID_curso`, `ID`) VALUES
(2, 19, 7);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notas`
--

CREATE TABLE `notas` (
  `ID_nota` int(11) NOT NULL,
  `ID_estudiante` int(11) DEFAULT NULL,
  `ID_tarea` int(11) DEFAULT NULL,
  `Calificacion` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tareas`
--

CREATE TABLE `tareas` (
  `ID_tarea` int(11) NOT NULL,
  `Descripcion` text DEFAULT NULL,
  `Fecha_entrega` date DEFAULT NULL,
  `ID_materia` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `ID_usuario` int(11) NOT NULL,
  `Nombres` varchar(50) DEFAULT NULL,
  `Apellidos` varchar(50) DEFAULT NULL,
  `Identificacion` varchar(20) DEFAULT NULL,
  `contraseña` varchar(255) DEFAULT NULL,
  `Direccion` varchar(100) DEFAULT NULL,
  `Telefono` varchar(15) DEFAULT NULL,
  `Correo_electronico` varchar(50) DEFAULT NULL,
  `ID_rol` enum('Administrador','Docente') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`ID_usuario`, `Nombres`, `Apellidos`, `Identificacion`, `contraseña`, `Direccion`, `Telefono`, `Correo_electronico`, `ID_rol`) VALUES
(1, 'Juan', 'Pérez', '123456789', '$2y$10$TDz5Nj/HUsuXCbsV1eJY2e3hY88SjscxaCb7vrJVBbVoXbDZrhT3u', 'Calle 123', '1234567890', 'juan.perez@example.com', 'Administrador'),
(30, 'Carlos', 'Ortiz', '52800312', '$2y$10$uYqKHSVgb17CaWDqFXCbnOhjO58vPc3ls86IHneb33QcwBoQSMDxW', 'casa', '3003371492', 'vadas@gmail.com', 'Docente');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `asignaturas`
--
ALTER TABLE `asignaturas`
  ADD PRIMARY KEY (`ID_asignatura`);

--
-- Indices de la tabla `asistencia`
--
ALTER TABLE `asistencia`
  ADD PRIMARY KEY (`ID_asistencia`),
  ADD KEY `ID_estudiante` (`ID_estudiante`),
  ADD KEY `ID_materia` (`ID_materia`);

--
-- Indices de la tabla `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`ID_curso`),
  ADD KEY `ID_grado` (`ID_grado`);

--
-- Indices de la tabla `docentes_materias`
--
ALTER TABLE `docentes_materias`
  ADD PRIMARY KEY (`ID_docente_materia`),
  ADD KEY `ID_docente` (`ID_docente`),
  ADD KEY `ID_materia` (`ID_materia`);

--
-- Indices de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  ADD PRIMARY KEY (`ID_estudiante`),
  ADD UNIQUE KEY `Identificacion` (`Identificacion`),
  ADD UNIQUE KEY `Correo_electronico` (`Correo_electronico`),
  ADD KEY `ID_curso` (`ID_curso`);

--
-- Indices de la tabla `grados`
--
ALTER TABLE `grados`
  ADD PRIMARY KEY (`ID_grado`);

--
-- Indices de la tabla `materias`
--
ALTER TABLE `materias`
  ADD PRIMARY KEY (`ID_materia`),
  ADD KEY `ID_asignatura` (`ID_asignatura`);

--
-- Indices de la tabla `materias_cursos`
--
ALTER TABLE `materias_cursos`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ID_materia` (`ID_materia`,`ID_curso`);

--
-- Indices de la tabla `notas`
--
ALTER TABLE `notas`
  ADD PRIMARY KEY (`ID_nota`),
  ADD KEY `ID_estudiante` (`ID_estudiante`),
  ADD KEY `ID_tarea` (`ID_tarea`);

--
-- Indices de la tabla `tareas`
--
ALTER TABLE `tareas`
  ADD PRIMARY KEY (`ID_tarea`),
  ADD KEY `ID_materia` (`ID_materia`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`ID_usuario`),
  ADD UNIQUE KEY `Identificacion` (`Identificacion`),
  ADD UNIQUE KEY `Correo_electronico` (`Correo_electronico`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `asignaturas`
--
ALTER TABLE `asignaturas`
  MODIFY `ID_asignatura` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `asistencia`
--
ALTER TABLE `asistencia`
  MODIFY `ID_asistencia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT de la tabla `cursos`
--
ALTER TABLE `cursos`
  MODIFY `ID_curso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT de la tabla `docentes_materias`
--
ALTER TABLE `docentes_materias`
  MODIFY `ID_docente_materia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  MODIFY `ID_estudiante` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de la tabla `grados`
--
ALTER TABLE `grados`
  MODIFY `ID_grado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `materias`
--
ALTER TABLE `materias`
  MODIFY `ID_materia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT de la tabla `materias_cursos`
--
ALTER TABLE `materias_cursos`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `notas`
--
ALTER TABLE `notas`
  MODIFY `ID_nota` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tareas`
--
ALTER TABLE `tareas`
  MODIFY `ID_tarea` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `ID_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `asistencia`
--
ALTER TABLE `asistencia`
  ADD CONSTRAINT `asistencia_ibfk_1` FOREIGN KEY (`ID_estudiante`) REFERENCES `estudiantes` (`ID_estudiante`) ON DELETE CASCADE,
  ADD CONSTRAINT `asistencia_ibfk_2` FOREIGN KEY (`ID_materia`) REFERENCES `materias` (`ID_materia`) ON DELETE CASCADE;

--
-- Filtros para la tabla `cursos`
--
ALTER TABLE `cursos`
  ADD CONSTRAINT `cursos_ibfk_1` FOREIGN KEY (`ID_grado`) REFERENCES `grados` (`ID_grado`) ON DELETE SET NULL;

--
-- Filtros para la tabla `docentes_materias`
--
ALTER TABLE `docentes_materias`
  ADD CONSTRAINT `docentes_materias_ibfk_1` FOREIGN KEY (`ID_docente`) REFERENCES `usuarios` (`ID_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `docentes_materias_ibfk_2` FOREIGN KEY (`ID_materia`) REFERENCES `materias` (`ID_materia`) ON DELETE CASCADE;

--
-- Filtros para la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  ADD CONSTRAINT `estudiantes_ibfk_1` FOREIGN KEY (`ID_curso`) REFERENCES `cursos` (`ID_curso`);

--
-- Filtros para la tabla `materias`
--
ALTER TABLE `materias`
  ADD CONSTRAINT `materias_ibfk_1` FOREIGN KEY (`ID_asignatura`) REFERENCES `asignaturas` (`ID_asignatura`);

--
-- Filtros para la tabla `materias_cursos`
--
ALTER TABLE `materias_cursos`
  ADD CONSTRAINT `materias_cursos_ibfk_1` FOREIGN KEY (`ID_materia`) REFERENCES `materias` (`ID_materia`);

--
-- Filtros para la tabla `tareas`
--
ALTER TABLE `tareas`
  ADD CONSTRAINT `tareas_ibfk_1` FOREIGN KEY (`ID_tarea`) REFERENCES `notas` (`ID_tarea`),
  ADD CONSTRAINT `tareas_ibfk_2` FOREIGN KEY (`ID_materia`) REFERENCES `materias` (`ID_materia`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
