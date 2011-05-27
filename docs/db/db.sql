-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 27, 2011 at 06:23 PM
-- Server version: 5.5.8
-- PHP Version: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `oborci`
--

-- --------------------------------------------------------

--
-- Table structure for table `oci_menus`
--

DROP TABLE IF EXISTS `oci_menus`;
CREATE TABLE IF NOT EXISTS `oci_menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module_id` int(11) NOT NULL,
  `parent` tinyint(4) NOT NULL DEFAULT '0',
  `index` tinyint(4) DEFAULT NULL,
  `uri` varchar(200) NOT NULL,
  `text` varchar(100) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `id_css` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `oci_menus`
--

INSERT INTO `oci_menus` (`id`, `module_id`, `parent`, `index`, `uri`, `text`, `title`, `id_css`) VALUES
(1, 1, 0, 0, 'home', 'Home', 'Home', 'menu_home'),
(2, 1, 0, 1, 'roles', 'Roles', 'Role Manager', 'menu_roles'),
(3, 1, 0, 2, 'users', 'Users', 'User Manager', 'menu_user'),
(10, 1, 0, 4, 'menus', 'Menus', 'Menu Management', 'menu_menus'),
(11, 1, 0, 5, 'screens', 'Screens', 'Screen Management', 'menu_screens');

-- --------------------------------------------------------

--
-- Table structure for table `oci_modules`
--

DROP TABLE IF EXISTS `oci_modules`;
CREATE TABLE IF NOT EXISTS `oci_modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `path` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `oci_modules`
--

INSERT INTO `oci_modules` (`id`, `path`, `name`, `status`) VALUES
(1, 'core', 'Core', 1);

-- --------------------------------------------------------

--
-- Table structure for table `oci_preferences`
--

DROP TABLE IF EXISTS `oci_preferences`;
CREATE TABLE IF NOT EXISTS `oci_preferences` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `email` varchar(200) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=29 ;

--
-- Dumping data for table `oci_preferences`
--

INSERT INTO `oci_preferences` (`id`, `email`, `first_name`, `last_name`) VALUES
(1, 'anton@itmn.co.id', 'Anton', 'Raharja'),
(2, 'kristy.d@gmail.com', 'Kristy', 'Damayanti'),
(19, '', 'nini', ''),
(18, '', 'odli', ''),
(17, '', 'anton', ''),
(16, '', 'kristy', ''),
(15, '', 'ajew', ''),
(20, '', 'aki', ''),
(21, '', 'teteh', ''),
(22, '', 'aki', ''),
(23, 'ajew@yahoooo.com', 'Azelia Aisya', 'Raharja'),
(24, '', 'odli', ''),
(25, '', 'dede', ''),
(26, '', 'huha', ''),
(27, '', 'huhu', ''),
(28, 'teteh@yayayaa.com', 'tester1', 'testeran');

-- --------------------------------------------------------

--
-- Table structure for table `oci_roles`
--

DROP TABLE IF EXISTS `oci_roles`;
CREATE TABLE IF NOT EXISTS `oci_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `oci_roles`
--

INSERT INTO `oci_roles` (`id`, `name`) VALUES
(1, 'Administrators'),
(2, 'Managers'),
(10, 'Alpha Testers'),
(9, 'Beta Testers'),
(13, 'Gamma Testers'),
(14, 'Theta Testers');

-- --------------------------------------------------------

--
-- Table structure for table `oci_roles_menus`
--

DROP TABLE IF EXISTS `oci_roles_menus`;
CREATE TABLE IF NOT EXISTS `oci_roles_menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `oci_roles_menus`
--

INSERT INTO `oci_roles_menus` (`id`, `role_id`, `menu_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 2, 1),
(5, 2, 2),
(6, 1, 10),
(7, 1, 11),
(8, 1, 12);

-- --------------------------------------------------------

--
-- Table structure for table `oci_roles_screens`
--

DROP TABLE IF EXISTS `oci_roles_screens`;
CREATE TABLE IF NOT EXISTS `oci_roles_screens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `screen_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `oci_roles_screens`
--

INSERT INTO `oci_roles_screens` (`id`, `role_id`, `screen_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 2, 1),
(5, 2, 2),
(6, 1, 4),
(7, 1, 5),
(8, 1, 6),
(9, 1, 7),
(10, 1, 8);

-- --------------------------------------------------------

--
-- Table structure for table `oci_screens`
--

DROP TABLE IF EXISTS `oci_screens`;
CREATE TABLE IF NOT EXISTS `oci_screens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `uri` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `oci_screens`
--

INSERT INTO `oci_screens` (`id`, `module_id`, `name`, `uri`) VALUES
(1, 1, 'Home', 'home'),
(2, 1, 'Role Management', 'roles'),
(3, 1, 'User Managemenet', 'users'),
(4, 1, 'Role Member List', 'roles/members'),
(5, 1, 'User preferences', 'preference/show'),
(6, 1, 'Menu Management', 'menus'),
(7, 1, 'Screen Management', 'screens'),
(8, 1, 'Module Management', 'modules');

-- --------------------------------------------------------

--
-- Table structure for table `oci_sessions`
--

DROP TABLE IF EXISTS `oci_sessions`;
CREATE TABLE IF NOT EXISTS `oci_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(16) NOT NULL DEFAULT '0',
  `user_agent` varchar(50) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text NOT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `oci_sessions`
--

INSERT INTO `oci_sessions` (`session_id`, `ip_address`, `user_agent`, `last_activity`, `user_data`) VALUES
('2a65182bd4cb5a923e52c2dfb662db24', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; rv:2.0.1) Gecko/20100', 1306520457, 'a:2:{s:7:"user_id";s:1:"1";s:11:"login_state";b:1;}');

-- --------------------------------------------------------

--
-- Table structure for table `oci_users`
--

DROP TABLE IF EXISTS `oci_users`;
CREATE TABLE IF NOT EXISTS `oci_users` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `preference_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `salt` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=28 ;

--
-- Dumping data for table `oci_users`
--

INSERT INTO `oci_users` (`id`, `role_id`, `preference_id`, `username`, `password`, `salt`) VALUES
(1, 1, 1, 'admin', 'password', ''),
(2, 2, 2, 'manager', 'password', ''),
(14, 9, 21, 'teteh', 'pwd', ''),
(16, 9, 23, 'ajew', 'ajew123', ''),
(17, 9, 24, 'odli', 'odli123', ''),
(18, 9, 25, 'dede', 'dede123', ''),
(19, 9, 22, 'aki', 'asdf1234', ''),
(20, 9, 26, 'huha', '11111', ''),
(21, 9, 27, 'huhu', '11111', ''),
(22, 9, 0, 'hihi', '123', ''),
(23, 9, 0, 'hehe', 'asd', ''),
(24, 9, 0, 'hoho', 'asd', ''),
(25, 10, 28, 'tester1', 'test', ''),
(26, 2, 0, 'Momotaro', 'ooo', ''),
(27, 1, 0, 'rumbada', 'asdf1234', '');
