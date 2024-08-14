-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-08-2024 a las 21:08:28
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
(5, 1, 2);

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
(1, 'Ana', 'Rodríguez', '654321987', '2005-03-15', 'Femenino', 'Calle 456', '9876543210', 'ana.rodriguez@example.com', 1),
(2, 'Carlos', 'López', '321654987', '2006-07-20', 'Masculino', 'Av. Secundaria', '6543210987', 'carlos.lopez@example.com', 2);

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
(23, 'Danzas', 5),
(25, 'Hatacuandoinono', 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `materias_cursos`
--

CREATE TABLE `materias_cursos` (
  `ID` int(11) NOT NULL,
  `ID_materia` int(11) NOT NULL,
  `ID_curso` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

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
  `contraseña` varchar(20) NOT NULL,
  `Direccion` varchar(100) DEFAULT NULL,
  `Telefono` varchar(15) DEFAULT NULL,
  `Correo_electronico` varchar(50) DEFAULT NULL,
  `ID_rol` enum('Administrador','Docente') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`ID_usuario`, `Nombres`, `Apellidos`, `Identificacion`, `contraseña`, `Direccion`, `Telefono`, `Correo_electronico`, `ID_rol`) VALUES
(1, 'Juan', 'Pérez', '123456789', '$2y$10$naPVTTO45MSjj', 'Calle 123', '1234567890', 'juan.perez@example.com', 'Administrador'),
(6, 'Luis', 'Aguilera', '52800312', '$2y$10$KI4TYUWgkZddb', 'cra 8 # 35 b 36 sur', '4214124', 'vaa@gmail.com', 'Docente'),
(7, 'Pedro', 'Pacheco', '31212', '$2y$10$VD0oVn399h3.t', '3123123', 'das', 'vadas@gmail.com', 'Docente'),
(8, 'Julian', 'Gomez', '10000687166', '$2y$10$51/1q/CTrWesu', 'das', '3003371492', '231312@gmail.com', 'Docente');

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
  ADD KEY `ID_materia` (`ID_materia`,`ID_curso`),
  ADD KEY `ID_curso` (`ID_curso`);

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
  MODIFY `ID_asignatura` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `asistencia`
--
ALTER TABLE `asistencia`
  MODIFY `ID_asistencia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `cursos`
--
ALTER TABLE `cursos`
  MODIFY `ID_curso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT de la tabla `docentes_materias`
--
ALTER TABLE `docentes_materias`
  MODIFY `ID_docente_materia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  MODIFY `ID_estudiante` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `grados`
--
ALTER TABLE `grados`
  MODIFY `ID_grado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `materias`
--
ALTER TABLE `materias`
  MODIFY `ID_materia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `materias_cursos`
--
ALTER TABLE `materias_cursos`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `ID_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
-- Filtros para la tabla `materias_cursos`
--
ALTER TABLE `materias_cursos`
  ADD CONSTRAINT `materias_cursos_ibfk_1` FOREIGN KEY (`ID_materia`) REFERENCES `materias` (`ID_materia`),
  ADD CONSTRAINT `materias_cursos_ibfk_2` FOREIGN KEY (`ID_curso`) REFERENCES `cursos` (`ID_curso`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
