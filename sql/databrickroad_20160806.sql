-- phpMyAdmin SQL Dump
-- version 4.0.10.7
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Aug 07, 2016 at 01:02 AM
-- Server version: 10.1.13-MariaDB-cll-lve
-- PHP Version: 5.4.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `datanbya_dbr`
--

-- --------------------------------------------------------

--
-- Table structure for table `pageload`
--

CREATE TABLE IF NOT EXISTS `pageload` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `created` datetime DEFAULT NULL,
  `user_guid` varchar(50) DEFAULT NULL,
  `domain` varchar(100) DEFAULT NULL,
  `page_name` varchar(250) DEFAULT NULL,
  `page_url` varchar(350) DEFAULT NULL,
  `referrer` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1100401 ;

-- --------------------------------------------------------

--
-- Table structure for table `pagescroll`
--

CREATE TABLE IF NOT EXISTS `pagescroll` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `created` datetime DEFAULT NULL,
  `user_guid` varchar(50) DEFAULT NULL,
  `domain` varchar(100) DEFAULT NULL,
  `page_name` varchar(250) DEFAULT NULL,
  `page_url` varchar(350) DEFAULT NULL,
  `referrer` varchar(250) DEFAULT NULL,
  `page_position_code` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=548083 ;

-- --------------------------------------------------------

--
-- Table structure for table `pagescroll_positions`
--

CREATE TABLE IF NOT EXISTS `pagescroll_positions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `position_code` varchar(10) DEFAULT NULL,
  `position_description` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `userbehavior`
--

CREATE TABLE IF NOT EXISTS `userbehavior` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `created` datetime DEFAULT NULL,
  `user_guid` varchar(50) DEFAULT NULL,
  `start` datetime DEFAULT NULL,
  `end` datetime DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `pageloadcount` int(11) DEFAULT NULL,
  `uniquepagecount` int(11) DEFAULT NULL,
  `lastvisitedpage` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=836380 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
