-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 20, 2011 at 06:26 AM
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
-- Table structure for table `sc_menus`
--

DROP TABLE IF EXISTS `sc_menus`;
CREATE TABLE IF NOT EXISTS `sc_menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module_id` int(11) NOT NULL,
  `parent` tinyint(4) NOT NULL DEFAULT '0',
  `index` tinyint(4) DEFAULT NULL,
  `uri` varchar(200) NOT NULL,
  `text` varchar(100) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `id_css` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `sc_menus`
--

INSERT INTO `sc_menus` (`id`, `module_id`, `parent`, `index`, `uri`, `text`, `title`, `id_css`) VALUES
(1, 1, 0, 0, 'home', 'Home', 'Home', 'menu_home'),
(2, 1, 0, 1, 'roles', 'Roles', 'Role Manager', 'menu_roles'),
(3, 1, 0, 2, 'users', 'Users', 'User Manager', 'menu_user'),
(10, 1, 0, 4, 'menus', 'Menus', 'Menu Management', 'menu_menus'),
(11, 1, 0, 5, 'screens', 'Screens', 'Screen Management', 'menu_screens'),
(12, 1, 0, 3, 'modules', 'Modules', 'Module Management', 'menu_modules');

-- --------------------------------------------------------

--
-- Table structure for table `sc_modules`
--

DROP TABLE IF EXISTS `sc_modules`;
CREATE TABLE IF NOT EXISTS `sc_modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `path` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `sc_modules`
--

INSERT INTO `sc_modules` (`id`, `path`, `name`, `status`) VALUES
(1, 'core', 'Core', 1);

-- --------------------------------------------------------

--
-- Table structure for table `sc_preferences`
--

DROP TABLE IF EXISTS `sc_preferences`;
CREATE TABLE IF NOT EXISTS `sc_preferences` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `email` varchar(200) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=23 ;

--
-- Dumping data for table `sc_preferences`
--

INSERT INTO `sc_preferences` (`id`, `email`, `first_name`, `last_name`) VALUES
(1, 'anton@itmn.co.id', 'Anton', 'Raharja'),
(2, 'kristy.d@gmail.com', 'Kristy', 'Damayanti'),
(19, '', 'nini', ''),
(18, '', 'odli', ''),
(17, '', 'anton', ''),
(16, '', 'kristy', ''),
(15, '', 'ajew', ''),
(20, '', 'aki', ''),
(21, '', 'teteh', ''),
(22, '', 'aki', '');

-- --------------------------------------------------------

--
-- Table structure for table `sc_roles`
--

DROP TABLE IF EXISTS `sc_roles`;
CREATE TABLE IF NOT EXISTS `sc_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `sc_roles`
--

INSERT INTO `sc_roles` (`id`, `name`) VALUES
(1, 'Administrators'),
(2, 'Managers'),
(10, 'Alpha Testers'),
(9, 'Beta Testers');

-- --------------------------------------------------------

--
-- Table structure for table `sc_roles_menus`
--

DROP TABLE IF EXISTS `sc_roles_menus`;
CREATE TABLE IF NOT EXISTS `sc_roles_menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `sc_roles_menus`
--

INSERT INTO `sc_roles_menus` (`id`, `role_id`, `menu_id`) VALUES
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
-- Table structure for table `sc_roles_screens`
--

DROP TABLE IF EXISTS `sc_roles_screens`;
CREATE TABLE IF NOT EXISTS `sc_roles_screens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `screen_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `sc_roles_screens`
--

INSERT INTO `sc_roles_screens` (`id`, `role_id`, `screen_id`) VALUES
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
-- Table structure for table `sc_screens`
--

DROP TABLE IF EXISTS `sc_screens`;
CREATE TABLE IF NOT EXISTS `sc_screens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `uri` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `sc_screens`
--

INSERT INTO `sc_screens` (`id`, `module_id`, `name`, `uri`) VALUES
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
-- Table structure for table `sc_sessions`
--

DROP TABLE IF EXISTS `sc_sessions`;
CREATE TABLE IF NOT EXISTS `sc_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(16) NOT NULL DEFAULT '0',
  `user_agent` varchar(50) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text NOT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sc_sessions`
--

INSERT INTO `sc_sessions` (`session_id`, `ip_address`, `user_agent`, `last_activity`, `user_data`) VALUES
('360f0eb3ba7e12cc30343e85c851e778', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; rv:2.0.1) Gecko/20100', 1305870913, 'a:2:{s:7:"user_id";s:1:"1";s:11:"login_state";b:1;}'),
('bda16e39bacbfa1884a52db8f4e35015', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; rv:2.0.1) Gecko/20100', 1305866840, 'a:2:{s:7:"user_id";s:1:"1";s:11:"login_state";b:1;}'),
('da776c360a35b58639442d4877b2b6ae', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; rv:2.0.1) Gecko/20100', 1305869380, 'a:2:{s:7:"user_id";s:1:"1";s:11:"login_state";b:1;}'),
('81239a661e589b5c89ad3a08315a1928', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; rv:2.0.1) Gecko/20100', 1305869046, 'a:2:{s:7:"user_id";s:1:"1";s:11:"login_state";b:1;}'),
('0849ab78d3d94cc311e2bf10ee42c16e', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; rv:2.0.1) Gecko/20100', 1305872593, 'a:2:{s:7:"user_id";s:1:"1";s:11:"login_state";b:1;}');

-- --------------------------------------------------------

--
-- Table structure for table `sc_users`
--

DROP TABLE IF EXISTS `sc_users`;
CREATE TABLE IF NOT EXISTS `sc_users` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `preference_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `salt` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

--
-- Dumping data for table `sc_users`
--

INSERT INTO `sc_users` (`id`, `role_id`, `preference_id`, `username`, `password`, `salt`) VALUES
(1, 1, 1, 'admin', 'password', ''),
(2, 2, 2, 'manager', 'password', ''),
(14, 10, 21, 'teteh', 'pwd', ''),
(16, 9, 0, 'ajew', 'ajew123', ''),
(17, 9, 0, 'odli', 'odli123', ''),
(18, 9, 0, 'dede', 'dede123', ''),
(19, 9, 22, 'aki', 'asdf1234', '');
