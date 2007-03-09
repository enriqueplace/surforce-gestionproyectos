-- phpMyAdmin SQL Dump
-- version 2.8.2.4
-- http://www.phpmyadmin.net
--
-- Servidor: mysql.linux.com.uy
-- Tiempo de generación: 21-02-2007 a las 09:36:17
-- Versión del servidor: 5.0.24
-- Versión de PHP: 4.4.4
--
-- Base de datos: `gestionproyectos`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `encuesta_conocimientos`
--

CREATE TABLE `encuesta_conocimientos` (
  `id_enc_con` int(11) NOT NULL auto_increment,
  `id_usuario` int(11) NOT NULL,
  `programacion` int(11) NOT NULL,
  `objetos` int(11) NOT NULL,
  `sql` int(11) NOT NULL,
  `uml` int(11) NOT NULL,
  `php` int(11) NOT NULL,
  `html` int(11) NOT NULL,
  `css` int(11) NOT NULL,
  `js` int(11) NOT NULL,
  `lenguajes` text collate utf8_spanish_ci NOT NULL,
  `patrones` text collate utf8_spanish_ci NOT NULL,
  `frameworks` text collate utf8_spanish_ci NOT NULL,
  `puesto` int(11) NOT NULL,
  `enteraron_por` text collate utf8_spanish_ci NOT NULL,
  PRIMARY KEY  (`id_enc_con`),
  UNIQUE KEY `id_usuario` (`id_usuario`),
  KEY `programacion` (`programacion`),
  KEY `puesto` (`puesto`)
) ENGINE=MyISAM AUTO_INCREMENT=95 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=95 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `encuesta_puesto`
--

CREATE TABLE `encuesta_puesto` (
  `id_puesto` int(11) NOT NULL,
  `desc_puesto` varchar(50) collate utf8_spanish_ci NOT NULL,
  PRIMARY KEY  (`id_puesto`),
  UNIQUE KEY `desc_puesto` (`desc_puesto`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `encuesta_valores`
--

CREATE TABLE `encuesta_valores` (
  `id_valor` int(11) NOT NULL,
  `desc_valor` varchar(50) collate utf8_spanish_ci NOT NULL,
  PRIMARY KEY  (`id_valor`),
  UNIQUE KEY `desc_valor` (`desc_valor`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL auto_increment,
  `usuario_usuario` varchar(50) collate utf8_spanish_ci NOT NULL,
  `clave_usuario` varchar(32) collate utf8_spanish_ci NOT NULL,
  `nombre_usuario` varchar(50) collate utf8_spanish_ci NOT NULL,
  `apellido_usuario` varchar(50) collate utf8_spanish_ci NOT NULL,
  `pais_usuario` varchar(50) collate utf8_spanish_ci NOT NULL,
  `ciudad_usuario` varchar(50) collate utf8_spanish_ci NOT NULL,
  `gmail_usuario` varchar(100) collate utf8_spanish_ci NOT NULL,
  `skype_usuario` varchar(100) collate utf8_spanish_ci NOT NULL,
  `profesion_usuario` varchar(100) collate utf8_spanish_ci NOT NULL,
  `empresa_usuario` varchar(100) collate utf8_spanish_ci NOT NULL,
  `expectativas_usuario` text collate utf8_spanish_ci NOT NULL,
  `foto_usuario` varchar(50) collate utf8_spanish_ci NOT NULL,
  `fecha_ult_mod_usuario` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `fecha_ingreso_usuario` timestamp NOT NULL default '0000-00-00 00:00:00',
  `fecha_aceptado_usuario` timestamp NOT NULL default '0000-00-00 00:00:00',
  `ip_usuario` varchar(32) collate utf8_spanish_ci NOT NULL,
  `ip_real_usuario` varchar(32) collate utf8_spanish_ci NOT NULL,
  `habilitado` tinyint(1) NOT NULL,
  `foro_asignado` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id_usuario`)
) ENGINE=MyISAM AUTO_INCREMENT=95 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=95 ;
