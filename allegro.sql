-- phpMyAdmin SQL Dump
-- version 3.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 15, 2014 at 09:29 PM
-- Server version: 5.5.25a
-- PHP Version: 5.4.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `allegro`
--

-- --------------------------------------------------------

--
-- Table structure for table `seller`
--

CREATE TABLE IF NOT EXISTS `seller` (
  `seller_id` int(11) NOT NULL,
  `date_added` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  `circuit` int(11) NOT NULL,
  `seller_profile` varchar(255) NOT NULL,
  `auction_page` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `region` varchar(255) NOT NULL,
  `zip-code` varchar(255) NOT NULL,
  `telephone` varchar(255) NOT NULL,
  `telephone_2` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `gg` varchar(255) NOT NULL,
  `shop` smallint(6) NOT NULL,
  PRIMARY KEY (`seller_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sellers`
--

CREATE TABLE IF NOT EXISTS `sellers` (
  `seller_id` int(11) NOT NULL AUTO_INCREMENT,
  `date_added` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  `circuit` int(11) NOT NULL,
  `seller_profile` varchar(255) NOT NULL,
  `auction_page` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `region` varchar(255) NOT NULL,
  `zip_code` varchar(255) NOT NULL,
  `telephone` varchar(255) NOT NULL,
  `telephone_2` varchar(255) NOT NULL,
  `allegro_id` int(255) NOT NULL,
  `gg` varchar(255) NOT NULL,
  `shop` smallint(6) NOT NULL,
  `address` varchar(255) NOT NULL,
  `regon` varchar(255) NOT NULL,
  `nip` varchar(255) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `condition` varchar(25) NOT NULL,
  `seller_name` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`seller_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=91 ;

--
-- Dumping data for table `sellers`
--

INSERT INTO `sellers` (`seller_id`, `date_added`, `date_updated`, `circuit`, `seller_profile`, `auction_page`, `email`, `city`, `region`, `zip_code`, `telephone`, `telephone_2`, `allegro_id`, `gg`, `shop`, `address`, `regon`, `nip`, `company_name`, `condition`, `seller_name`, `name`) VALUES
(82, '2014-02-15 09:26:28', '0000-00-00 00:00:00', 0, 'http://allegro.pl/my_page.php?uid=3201884', 'http://allegro.pl//maszyna-do-produkcji-alkomatow-jednorazowych-i3966924802.html', 'szymkowiak@domator24.com', 'Zielona', '', '65-126', '509 48 33 99', '>\r\n\r\n. ', 3201884, '', 0, 'Ã…ÂÃ„Â™Ã…Â¼ycka 14', '978094558', '973-055-18-69', 'DOMATOR24.COM PAWEÃ…Â NOWAK', 'used', '\n SprzedajÃ„Â…cy -AUDIO-  (2967)  \n ', 'PaweÃ…Â‚ Nowak'),
(83, '2014-02-15 09:26:29', '0000-00-00 00:00:00', 0, 'http://allegro.pl/my_page.php?uid=27238491', 'http://allegro.pl//pompa-wspomagania-opel-astra-f-1-4-1-6-1-8-2-0-i3895184049.html', '', 'SOBÃƒÂ“TKA', '', '55-050', '', '', 27238491, '', 0, 'Wojnarowice ul.GÃ…Â‚ÃƒÂ³wna 5', '932931553', '896-118-87-53', 'Auto Handel DANIO', 'used', '\n SprzedajÃ„Â…cy www_czesci_com  (112)  \n ', 'Daniel Tomaszewski'),
(84, '2014-02-15 09:26:30', '0000-00-00 00:00:00', 0, 'http://allegro.pl/my_page.php?uid=2614924', 'http://allegro.pl//alkomat-uszkodzony-ad6000ns-ad-6000-ns-i3955729722.html', '', '', '', '', '', 'px; text-align: left;">', 2614924, '', 0, 'ul {padding: 0', '', '', '', 'used', '\n SprzedajÃ„Â…cy merlinmag  (701)  \n ', ''),
(85, '2014-02-15 09:26:33', '0000-00-00 00:00:00', 0, 'http://allegro.pl/my_page.php?uid=7810164', 'http://allegro.pl//alkomat-ca-2000-i3964654481.html', '', '', '', '', ',658737674,', '._setAccount'', ''UA-2827377-', 7810164, '', 0, 'ul class="user-nav" id="user-nav" data-user-card-url="http://allegro.pl/show_user.php?uid=">wystaw przedmiotmoje allegrokupioneobserwowanelicytujeszsprzedajeszsprzedanewystaw komentarzkomentarze otrzymane', '', '', '', 'used', '\n SprzedajÃ„Â…cy szewczyktadeusz  (10)  \n ', ''),
(86, '2014-02-15 09:26:36', '0000-00-00 00:00:00', 0, 'http://allegro.pl/my_page.php?uid=9047651', 'http://allegro.pl//alcoscent-da-3000-alkomat-100-ustnikow-i3965744604.html', '', '', '', '', ',658737674,', '._setAccount'', ''UA-2827377-', 9047651, '', 0, 'ul class="user-nav" id="user-nav" data-user-card-url="http://allegro.pl/show_user.php?uid=">wystaw przedmiotmoje allegrokupioneobserwowanelicytujeszsprzedajeszsprzedanewystaw komentarzkomentarze otrzymane', '', '', '', 'used', '\n SprzedajÃ„Â…cy Fischka171  (100)  \n ', ''),
(87, '2014-02-15 09:26:38', '0000-00-00 00:00:00', 0, 'http://allegro.pl/my_page.php?uid=7851199', 'http://allegro.pl//alkomat-alcosafe-s4-aktualna-kalibracja-16-01-14-i3947131215.html', '', '', '', '', ',658737674,', '._setAccount'', ''UA-2827377-', 7851199, '', 0, 'ul class="user-nav" id="user-nav" data-user-card-url="http://allegro.pl/show_user.php?uid=">wystaw przedmiotmoje allegrokupioneobserwowanelicytujeszsprzedajeszsprzedanewystaw komentarzkomentarze otrzymane', '', '', '', 'used', '\n SprzedajÃ„Â…cy woj-p1  (83)  \n ', ''),
(88, '2014-02-15 09:26:39', '0000-00-00 00:00:00', 0, 'http://allegro.pl/my_page.php?uid=331472', 'http://allegro.pl//antena-pletwa-volvo-xc60-2010-i3900495583.html', 'orc3@o2.pl', 'Pleszew', '', '63-300', '', 'e1043018.', 331472, '', 0, 'REJA 5/2', '300611742', '617-187-95-51', 'GORC-POL', 'used', '\n SprzedajÃ„Â…cy MisiaczekX  (7743)  \n ', 'Grzegorz KociÃ…Â„ski'),
(89, '2014-02-15 09:26:41', '0000-00-00 00:00:00', 0, 'http://allegro.pl/my_page.php?uid=24416844', 'http://allegro.pl//alkomat-i3968010457.html', '', '', '', '', ' DNI I BRAKU WPÃ…ÂATY WYSTAWIAM NEGATYWNY KOMENTARZ A PRZEDMIOT WRACA NAÂ  LICYTACJÃ„Â˜.OSOBA KTÃ“RA WYLICYTOWAÃ…ÂA I NIE ZAPÃ…ÂACIÃ…ÂA ZOSTAJE WCIÃ„Â„GNIÃ„Â˜TA NA CZARNÃ„Â„ LISTÃ„Â˜.W PRZYPADKU NIEJASNOÃ…ÂšCI BÃ„Â„DÃ…Â» JAKICHKOLWIEK PTYAÃ…Âƒ PROSZÃ„', '', 24416844, '', 0, '', '', '', '', 'used', '\n SprzedajÃ„Â…cy kolor63  (445)  \n ', ''),
(90, '2014-02-15 09:26:43', '0000-00-00 00:00:00', 0, 'http://allegro.pl/my_page.php?uid=1601629', 'http://allegro.pl//alkomat-da-7100-2-lata-kalibracji-gratis-i3959007187.html', '', '', '', '', ',658737674,', '._setAccount'', ''UA-2827377-', 1601629, '', 0, 'ul class="user-nav" id="user-nav" data-user-card-url="http://allegro.pl/show_user.php?uid=">wystaw przedmiotmoje allegrokupioneobserwowanelicytujeszsprzedajeszsprzedanewystaw komentarzkomentarze otrzymane', '', '', '', 'used', '\n SprzedajÃ„Â…cy pakum  (562)  \n ', '');

-- --------------------------------------------------------

--
-- Table structure for table `seller_categories`
--

CREATE TABLE IF NOT EXISTS `seller_categories` (
  `allegro_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  KEY `allegro_id` (`allegro_id`,`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `seller_categories`
--

INSERT INTO `seller_categories` (`allegro_id`, `category_id`, `category_name`) VALUES
(3201884, 0, 'http://allegro.pl//akcesoria-alkomaty-18652?limit=180&buyUsed=1&string='),
(27238491, 0, 'http://allegro.pl//akcesoria-alkomaty-18652?limit=180&buyUsed=1&string='),
(2614924, 0, 'http://allegro.pl//akcesoria-alkomaty-18652?limit=180&buyUsed=1&string='),
(7810164, 0, 'http://allegro.pl//akcesoria-alkomaty-18652?limit=180&buyUsed=1&string='),
(9047651, 0, 'http://allegro.pl//akcesoria-alkomaty-18652?limit=180&buyUsed=1&string='),
(7851199, 0, 'http://allegro.pl//akcesoria-alkomaty-18652?limit=180&buyUsed=1&string='),
(331472, 0, 'http://allegro.pl//akcesoria-alkomaty-18652?limit=180&buyUsed=1&string='),
(24416844, 0, 'http://allegro.pl//akcesoria-alkomaty-18652?limit=180&buyUsed=1&string='),
(1601629, 0, 'http://allegro.pl//akcesoria-alkomaty-18652?limit=180&buyUsed=1&string='),
(535038, 0, 'http://allegro.pl//akcesoria-alkomaty-18652?limit=180&buyUsed=1&string=');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
