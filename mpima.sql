-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jul 26, 2018 at 10:07 AM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `mpima`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE IF NOT EXISTS `cart` (
  `item_id` bigint(255) NOT NULL,
  `user_id` bigint(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `cart_id` bigint(255) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`cart_id`),
  KEY `user_id` (`user_id`),
  KEY `item_id` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `parent_id` bigint(255) NOT NULL,
  `id` bigint(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `rank` enum('1','2','3','5','6','7','8','9','10') NOT NULL DEFAULT '1',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`parent_id`, `id`, `name`, `rank`, `date`) VALUES
(1, 2, 'Houses', '6', '2016-04-05 17:22:37'),
(1, 3, 'Plot', '5', '2016-04-05 17:52:14'),
(1, 4, 'Land', '3', '2016-04-05 17:53:44');

-- --------------------------------------------------------

--
-- Table structure for table `change_email_confirm`
--

CREATE TABLE IF NOT EXISTS `change_email_confirm` (
  `confirm_code` varchar(200) NOT NULL,
  `user_id` bigint(255) NOT NULL,
  `email` varchar(50) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('pending','confirmed') NOT NULL DEFAULT 'pending',
  UNIQUE KEY `user_id_2` (`user_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `change_email_confirm`
--

INSERT INTO `change_email_confirm` (`confirm_code`, `user_id`, `email`, `date`, `status`) VALUES
('9d0428f970e64525a951', 1, 'tiya@gmail.com', '2016-03-13 12:39:35', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `contact_email`
--

CREATE TABLE IF NOT EXISTS `contact_email` (
  `email` varchar(2000) NOT NULL,
  `user_id` bigint(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `user_id_2` (`user_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `contact_email`
--

INSERT INTO `contact_email` (`email`, `user_id`, `date`) VALUES
('0|#$(deli@m@iter-2)$#|tiyachamdimba@gmail.com', 1, '2016-02-25 15:10:16');

-- --------------------------------------------------------

--
-- Table structure for table `contact_map`
--

CREATE TABLE IF NOT EXISTS `contact_map` (
  `map_description` varchar(2000) DEFAULT NULL,
  `user_id` bigint(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `location` varchar(50) DEFAULT NULL,
  `map_pic` varchar(5000) NOT NULL,
  UNIQUE KEY `user_id_2` (`user_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `contact_map`
--

INSERT INTO `contact_map` (`map_description`, `user_id`, `date`, `location`, `map_pic`) VALUES
('malawi,lilongwe area 2', 1, '2016-02-25 16:57:39', NULL, '');

-- --------------------------------------------------------

--
-- Table structure for table `contact_phone`
--

CREATE TABLE IF NOT EXISTS `contact_phone` (
  `phone` varchar(2000) DEFAULT NULL,
  `user_id` bigint(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `user_id_2` (`user_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `contact_phone`
--

INSERT INTO `contact_phone` (`phone`, `user_id`, `date`) VALUES
('0|#$(delimiter-2)$#|0888484921', 1, '2016-02-25 14:56:55');

-- --------------------------------------------------------

--
-- Table structure for table `contact_post`
--

CREATE TABLE IF NOT EXISTS `contact_post` (
  `post_box` varchar(50) NOT NULL,
  `user_id` bigint(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `user_id_2` (`user_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `contact_post`
--

INSERT INTO `contact_post` (`post_box`, `user_id`, `date`) VALUES
('tiya Chamdimba\r\np.o. box 2577 \r\nLilongwe \r\nMalawi', 1, '2016-02-25 16:24:09');

-- --------------------------------------------------------

--
-- Table structure for table `followed_page`
--

CREATE TABLE IF NOT EXISTS `followed_page` (
  `followed_id` bigint(255) NOT NULL,
  `user_id` bigint(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `user_id` (`user_id`),
  KEY `followed_id` (`followed_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE IF NOT EXISTS `item` (
  `item_id` bigint(255) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(255) NOT NULL,
  `name` varchar(50) NOT NULL,
  `category_id` bigint(255) NOT NULL,
  `price` varchar(100) NOT NULL,
  `condition_id` bigint(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `on_display` enum('0','1') NOT NULL DEFAULT '1',
  `description` varchar(2200) NOT NULL,
  `item_pic` varchar(5000) NOT NULL,
  `deleted_by_seller` enum('0','1') NOT NULL DEFAULT '0',
  `location_id` bigint(255) NOT NULL,
  `type_id` bigint(255) NOT NULL,
  `summary` varchar(700) NOT NULL,
  PRIMARY KEY (`item_id`),
  KEY `user_id` (`user_id`),
  KEY `category_id` (`category_id`),
  KEY `condition_id` (`condition_id`),
  KEY `type_id` (`type_id`,`location_id`),
  KEY `location_id` (`location_id`),
  KEY `category_id_2` (`category_id`),
  FULLTEXT KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`item_id`, `user_id`, `name`, `category_id`, `price`, `condition_id`, `date`, `on_display`, `description`, `item_pic`, `deleted_by_seller`, `location_id`, `type_id`, `summary`) VALUES
(3, 1, 'Nkolokosa', 2, '300000', 1, '2018-06-06 07:59:44', '1', '2 bedroom house best condtion', '0|#$(delimiter-2)$#|media/user/1/image/item/2018_22/dc3f8e1d2ca73ce0c1db|#$(delimiter-2)$#|0|#$(delimiter-1)$#|1|#$(delimiter-2)$#|media/user/1/image/item/2018_22/9cfc8c65f18cd066dae9|#$(delimiter-2)$#|0', '0', 1, 1, '2 bedroom house best condtion'),
(5, 1, 'Bangwe township', 4, '708880', 1, '2018-06-06 07:59:44', '1', 'Bangwe township', '0|#$(delimiter-2)$#|media/user/1/image/item/2018_22/dd98133c29b2e005a1cf|#$(delimiter-2)$#|0', '0', 1, 2, 'Bangwe township morden'),
(8, 1, 'Ndirande', 2, '200000', 1, '2018-07-06 22:17:52', '1', 'nice', '0|#$(delimiter-2)$#|media/user/1/image/item/2018_27/f9d1f9796819ba818abf|#$(delimiter-2)$#|0|#$(delimiter-1)$#|1|#$(delimiter-2)$#|media/user/1/image/item/2018_27/b92f6f66c0fa7839b8f2|#$(delimiter-2)$#|0|#$(delimiter-1)$#|2|#$(delimiter-2)$#|media/user/1/image/item/2018_27/a34d31478bf47e2a7e7f|#$(delimiter-2)$#|0', '0', 17, 1, 'goose House'),
(9, 1, 'chiromoni', 2, '30000', 1, '2018-07-06 22:48:53', '0', 'nice', '0|#$(delimiter-2)$#|media/user/1/image/item/2018_27/d52328b829382fdb1a36|#$(delimiter-2)$#|0', '0', 29, 2, 'cool indeed'),
(10, 1, 'Vumbwe', 2, '50000', 1, '2018-07-06 22:50:05', '1', 'really nice', '0|#$(delimiter-2)$#|media/user/1/image/item/2018_27/38c63ce5bb6256c7c69d|#$(delimiter-2)$#|0|#$(delimiter-1)$#|1|#$(delimiter-2)$#|media/user/1/image/item/2018_27/7827d58fdea2434dd14d|#$(delimiter-2)$#|0', '0', 29, 2, 'right at the peek');

-- --------------------------------------------------------

--
-- Table structure for table `item_condition`
--

CREATE TABLE IF NOT EXISTS `item_condition` (
  `condition_name` varchar(15) NOT NULL,
  `condition_id` bigint(255) NOT NULL AUTO_INCREMENT,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `rank` enum('1','2','3','5','6','7','8','9','10') NOT NULL DEFAULT '1',
  PRIMARY KEY (`condition_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `item_condition`
--

INSERT INTO `item_condition` (`condition_name`, `condition_id`, `date`, `rank`) VALUES
('Good', 1, '2016-04-10 08:28:18', '1'),
('Perfect', 2, '2016-04-10 08:28:18', '2'),
('Brand New', 3, '2016-04-10 08:28:37', '5'),
('Excellent', 4, '2016-03-08 00:26:37', '3'),
('Fairly used', 7, '2016-04-10 09:10:33', '1'),
('Used', 9, '2016-04-10 09:10:33', '1');

-- --------------------------------------------------------

--
-- Table structure for table `item_request`
--

CREATE TABLE IF NOT EXISTS `item_request` (
  `item_id` bigint(255) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(255) NOT NULL,
  `name` varchar(50) NOT NULL,
  `category_id` bigint(255) NOT NULL,
  `price` varchar(100) NOT NULL,
  `condition_id` bigint(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `on_display` enum('0','1') NOT NULL DEFAULT '1',
  `description` varchar(2200) NOT NULL,
  `item_pic` varchar(5000) NOT NULL,
  `deleted_by_seller` enum('0','1') NOT NULL DEFAULT '0',
  `location_id` bigint(255) NOT NULL,
  `type_id` bigint(255) NOT NULL,
  `summary` varchar(700) NOT NULL,
  `phone` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  PRIMARY KEY (`item_id`),
  KEY `user_id` (`user_id`),
  KEY `category_id` (`category_id`),
  KEY `condition_id` (`condition_id`),
  KEY `type_id` (`type_id`,`location_id`),
  KEY `location_id` (`location_id`),
  KEY `category_id_2` (`category_id`),
  FULLTEXT KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

--
-- Dumping data for table `item_request`
--

INSERT INTO `item_request` (`item_id`, `user_id`, `name`, `category_id`, `price`, `condition_id`, `date`, `on_display`, `description`, `item_pic`, `deleted_by_seller`, `location_id`, `type_id`, `summary`, `phone`, `email`) VALUES
(11, 1, 'Tyer marks', 2, '', 1, '2018-07-09 23:32:50', '1', 'i love houses', '', '0', 29, 2, '', '0888 8789898', 'tiyachamdimba@gkmail.com'),
(12, 1, 'Tyer marks', 2, '', 1, '2018-07-09 23:33:38', '1', 'i love houses', '', '0', 29, 2, '', '0888 8789898', 'tiyachamdimba@gkmail.com'),
(13, 1, 'tyeer', 2, '', 1, '2018-07-09 23:44:47', '1', 'tyeer yutr', '', '0', 29, 2, '', '099876556', ''),
(15, 1, 'tyeer', 2, '', 1, '2018-07-13 23:43:43', '1', 'My Love. My Fate Season 5 - 2016 Latest Nigerian', '', '0', 29, 2, '', '987987687', ''),
(16, 1, 'tyuyu', 2, '', 1, '2018-07-13 23:44:14', '1', 'My Love. My Fate Season 5 - 2016 Latest Nigerian', '', '0', 29, 2, '', '769869879', ''),
(17, 1, 'tyeer', 2, '', 1, '2018-07-13 23:47:31', '1', 'My Love. My Fate Season 5 - 2016 Latest Nigerian', '', '0', 29, 2, '', '97698769768', ''),
(18, 1, 'tyeer', 2, '', 1, '2018-07-13 23:57:19', '1', 'position: relative;\r\n ', '0|#$(delimiter-2)$#|media/user/1/image/item/2018_28/3046a2da8b04a8024361|#$(delimiter-2)$#|0', '0', 29, 2, '', '0900090090', ''),
(20, 1, 'tyeer', 2, '', 1, '2018-07-14 00:03:49', '1', 'I want these thing please', '0|#$(delimiter-2)$#|media/user/1/image/item/2018_28/744b62bf708354e82b9c|#$(delimiter-2)$#|0', '0', 29, 2, '', '0999 876 767', '');

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

CREATE TABLE IF NOT EXISTS `location` (
  `id` bigint(255) NOT NULL AUTO_INCREMENT,
  `region` varchar(15) CHARACTER SET utf8 DEFAULT NULL,
  `name` varchar(10) CHARACTER SET utf8 DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `description` varchar(500) NOT NULL,
  `rank` enum('1','2','3','4','5','6','7','8','9','10') NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=30 ;

--
-- Dumping data for table `location`
--

INSERT INTO `location` (`id`, `region`, `name`, `date`, `description`, `rank`) VALUES
(1, 'Central Region', 'Dedza', '2018-07-03 16:24:58', '', '1'),
(2, 'Central Region', 'Dowa', '2018-07-03 16:24:58', '', '1'),
(3, 'Central Region', 'Kasungu', '2018-07-03 16:24:58', '', '1'),
(4, 'Central Region', 'Lilongwe', '2018-07-03 16:24:58', '', '5'),
(5, 'Central Region', 'Mchinji', '2018-07-03 16:24:58', '', '1'),
(6, 'Central Region', 'Nkhotakota', '2018-07-03 16:24:58', '', '1'),
(7, 'Central Region', 'Ntcheu', '2018-07-03 16:24:58', '', '1'),
(8, 'Central Region', 'Ntchisi', '2018-07-03 16:24:58', '', '1'),
(9, 'Central Region', 'Salima', '2018-07-03 16:24:58', '', '1'),
(10, 'Northern Region', 'Chitipa', '2018-07-03 16:24:58', '', '1'),
(11, 'Northern Region', 'Karonga', '2018-07-03 16:24:58', '', '1'),
(12, 'Northern Region', 'Likoma', '2018-07-03 16:24:58', '', '1'),
(13, 'Northern Region', 'Mzimba', '2018-07-03 16:24:58', '', '1'),
(14, 'Northern Region', 'Nkhata Bay', '2018-07-03 16:24:58', '', '1'),
(15, 'Northern Region', 'Rumphi', '2018-07-03 16:24:58', '', '1'),
(16, 'Southern Region', 'Balaka', '2018-07-03 16:24:58', '', '1'),
(17, 'Southern Region', 'Blantyre', '2018-07-03 16:24:58', '', '5'),
(18, 'Southern Region', 'Chikwawa', '2018-07-03 16:24:58', '', '1'),
(19, 'Southern Region', 'Chiradzulu', '2018-07-03 16:24:58', '', '1'),
(20, 'Southern Region', 'Machinga', '2018-07-03 16:24:58', '', '1'),
(21, 'Southern Region', 'Mangochi', '2018-07-03 16:24:58', '', '1'),
(22, 'Southern Region', 'Mulanje', '2018-07-03 16:24:58', '', '1'),
(23, 'Southern Region', 'Mwanza', '2018-07-03 16:24:58', '', '1'),
(24, 'Southern Region', 'Nsanje', '2018-07-03 16:24:58', '', '1'),
(25, 'Southern Region', 'Thyolo', '2018-07-03 16:24:58', '', '1'),
(26, 'Southern Region', 'Phalombe', '2018-07-03 16:24:58', '', '1'),
(27, 'Southern Region', 'Zomba', '2018-07-03 16:24:58', '', '5'),
(28, 'Southern Region', 'Neno', '2018-07-03 16:24:58', '', '1'),
(29, 'Northern Region', 'Mzuzu', '2018-07-03 16:31:11', '', '5');

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE IF NOT EXISTS `message` (
  `message_id` bigint(255) NOT NULL AUTO_INCREMENT,
  `sender_id` bigint(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `message` longtext NOT NULL,
  `subject_id` bigint(255) NOT NULL DEFAULT '1',
  PRIMARY KEY (`message_id`),
  KEY `sender_id` (`sender_id`),
  KEY `subject_id` (`subject_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=56 ;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`message_id`, `sender_id`, `date`, `message`, `subject_id`) VALUES
(12, 1, '2016-08-25 00:08:20', 'tyer', 6),
(15, 1, '2016-08-25 08:38:59', 'hey man terror', 9),
(18, 1, '2016-08-25 08:51:33', 'no it aint', 9),
(19, 10, '2016-08-25 22:58:27', 'hey hhahah terror', 10),
(20, 1, '2016-08-27 12:46:47', 'test 1&lt;br&gt;', 10),
(21, 1, '2016-08-27 14:06:40', '&lt;p&gt;test2&lt;br&gt;&lt;/p&gt;', 10),
(22, 1, '2016-08-27 14:07:59', '&lt;p&gt;test3 .1&lt;br&gt;&lt;/p&gt;', 10),
(23, 1, '2016-08-28 22:20:21', '&lt;p&gt;goo do&lt;br&gt;&lt;/p&gt;', 10),
(24, 1, '2016-08-28 22:24:01', '&lt;p&gt;yeap it there&lt;br&gt;&lt;/p&gt;', 10),
(25, 1, '2016-08-28 22:25:09', '&lt;p&gt;yeas&lt;br&gt;&lt;/p&gt;', 10),
(26, 1, '2016-08-28 22:37:32', '&lt;p&gt;still ki&lt;br&gt;&lt;/p&gt;', 9),
(27, 1, '2016-08-28 23:09:21', '&lt;p&gt;&nbsp;&nbsp; This is an important tutorial about stripping HTML tags in PHP <br />\nwith sample code. Sometimes you encountered a situation when you are <br />\noutputting a text into a document say RSS feeds or PDF. And the content <br />\n(text) that is being outputted contains html entities for example: &amp;<br />\n nbsp ;<br />\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Common practice of removing html tags in PHP is using the <br />\nfunction: strip_tags, however this function will not remove html <br />\nentities; as a result html entities in being outputted to html resulting<br />\n in gibberish text.&lt;/p&gt;', 9),
(28, 10, '2016-08-28 23:17:51', '&lt;p&gt;ok&nbsp;&lt;/p&gt;&lt;p&gt;More precisely, this function decodes all the entities (including all numeric<br />\n   entities) that a) are necessarily valid for the chosen document type &mdash; i.e.,<br />\n   for XML, this function does not decode named entities that might be defined<br />\n   in some DTD &mdash; and b) whose character or characters are in the coded character<br />\n   set associated with the chosen encoding and are permitted in the chosen<br />\n   document type. All other entities are left as is.&lt;br&gt;&lt;/p&gt;', 10),
(29, 1, '2016-08-28 23:23:30', '&lt;p&gt;&lt;font color=&quot;#6495ed&quot;&gt;&lt;a xss=removed href=&quot;http://www.php-developer.org/simple-and-easiest-wysiwyg-free-php5-templating-engine-raintpl-review/&quot;&gt;&lt;div xss=removed&gt;&lt;div xss=removed&gt;Simple and Easiest WYSIWYG Free PHP5 Templating Engine-RainTPL Review&lt;/div&gt;&lt;/div&gt;&lt;/a&gt;&lt;/font&gt;&lt;font color=&quot;#00f&quot;&gt;&lt;a xss=removed href=&quot;http://www.php-developer.org/blocking-denial-of-service-attacks-using-php-script-possible/&quot;&gt;&lt;div xss=removed&gt;&lt;div xss=removed&gt;Blocking Denial of Service Attacks using PHP script, Possible?&lt;/div&gt;&lt;/div&gt;&lt;/a&gt;&lt;/font&gt;&lt;/p&gt;&lt;h6&gt;&lt;a xss=removed href=&quot;http://www.php-developer.org/best-practices-of-php-error-handling-illustrated-using-a-video-streaming-script/&quot;&gt;&lt;div xss=removed&gt;&lt;div xss=removed&gt;Best practices of PHP &lt;font color=&quot;#d2691e&quot; face=&quot;courier new&quot;&gt;&lt;b&gt;&lt;i&gt;Error Handling: Illustrated using a Video Streaming Script&lt;/i&gt;&lt;/b&gt;&lt;/font&gt;&lt;/div&gt;&lt;/div&gt;&lt;/a&gt;&lt;/h6&gt;', 10),
(30, 1, '2016-09-04 10:59:21', '<p><br></p>', 11),
(31, 1, '2016-09-04 11:02:57', '<p><br></p>', 12),
(32, 1, '2016-09-04 11:23:51', '', 13),
(33, 1, '2016-09-04 11:40:06', '<p>hi man<br></p>', 14),
(34, 1, '2016-09-04 12:56:38', '<p>hello am home<br></p>', 15),
(35, 1, '2016-09-04 12:57:40', '<p>hello man <br></p>', 16),
(36, 1, '2016-09-06 13:03:14', '<p>wwwwwwwwwwwwwwwwwwwwwwwwwwww<br></p>', 17),
(37, 1, '2016-09-07 15:58:20', '&lt;p&gt;still moving forward kid&lt;br&gt;&lt;/p&gt;', 10),
(38, 10, '2016-10-16 15:27:59', ' hey man', 18),
(39, 10, '2016-10-16 19:30:54', ' tyeer is the deal', 19),
(40, 10, '2016-10-17 00:31:17', ' thanks man for evrything', 10),
(41, 10, '2016-10-17 00:32:03', ' alright all cool', 10),
(42, 10, '2016-10-17 00:34:13', ' alright all cool', 10),
(43, 10, '2016-10-17 00:34:30', ' alright all cool', 10),
(44, 10, '2016-10-17 00:54:20', ' alright all cool', 10),
(45, 10, '2016-10-17 00:57:07', ' alright all cool', 10),
(46, 10, '2016-10-17 01:02:59', ' thanks man for evrything ', 10),
(47, 10, '2016-10-17 01:04:31', ' thanks man for evrything ', 10),
(48, 10, '2016-10-17 01:06:18', ' thanks man for evrything ', 10),
(49, 10, '2016-10-17 01:06:44', ' thanks man for evrything ', 10),
(50, 10, '2016-10-17 01:06:56', ' thanks man for evrything ', 10),
(51, 10, '2016-10-17 01:11:53', ' $route[&#039;(?i)message/inbox/(:num)/f&#039;] = &#039;message/reply_form_static_connector&#039;;', 10),
(52, 10, '2016-10-17 01:13:54', '$route[&#039;(?i)message/inbox/(:num)/f&#039;] = &#039;message/reply_form_static_connector&#039;; ', 10),
(53, 10, '2016-10-17 01:14:16', '$route[&#039;(?i)message/inbox/(:num)/f&#039;] = &#039;message/reply_form_static_connector&#039;; ', 10),
(54, 1, '2017-08-06 04:12:39', '&lt;p&gt;&lt;br&gt;&lt;/p&gt;', 10),
(55, 1, '2017-08-06 04:12:49', '&lt;p&gt;&lt;br&gt;&lt;/p&gt;', 10);

-- --------------------------------------------------------

--
-- Table structure for table `message_deletion`
--

CREATE TABLE IF NOT EXISTS `message_deletion` (
  `id` bigint(255) NOT NULL AUTO_INCREMENT,
  `subject_id` bigint(255) NOT NULL,
  `deleter_id` bigint(255) NOT NULL COMMENT 'one who has deleted the message',
  `deletion_level` enum('1','2') NOT NULL COMMENT '1 = its in trash, 2= its completely deleted',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `subject_id` (`subject_id`),
  KEY `user_id` (`deleter_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `message_deletion`
--

INSERT INTO `message_deletion` (`id`, `subject_id`, `deleter_id`, `deletion_level`, `date`) VALUES
(1, 16, 1, '1', '2016-09-04 13:03:31'),
(2, 15, 1, '1', '2016-09-04 13:03:31'),
(3, 14, 1, '1', '2016-09-04 13:03:31'),
(4, 13, 1, '1', '2016-09-04 13:03:31'),
(5, 12, 1, '1', '2016-09-04 13:03:31'),
(7, 9, 1, '1', '2016-10-17 09:32:18');

-- --------------------------------------------------------

--
-- Table structure for table `message_receiver`
--

CREATE TABLE IF NOT EXISTS `message_receiver` (
  `id` bigint(255) NOT NULL AUTO_INCREMENT,
  `message_id` bigint(255) NOT NULL,
  `receiver_id` bigint(255) NOT NULL COMMENT 'one who has received the message',
  `reception_type` enum('cc','bcc') NOT NULL DEFAULT 'cc',
  `message_read` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0=has not been read, 1=read',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `subject_id` (`message_id`),
  KEY `user_id` (`receiver_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=41 ;

--
-- Dumping data for table `message_receiver`
--

INSERT INTO `message_receiver` (`id`, `message_id`, `receiver_id`, `reception_type`, `message_read`, `date`) VALUES
(1, 12, 10, 'cc', '1', '2016-08-25 00:08:46'),
(2, 15, 1, 'cc', '1', '2016-08-25 08:38:59'),
(3, 18, 1, 'cc', '1', '2016-08-25 08:51:33'),
(4, 19, 1, 'cc', '1', '2016-08-25 22:58:27'),
(5, 20, 10, 'cc', '1', '2016-08-27 12:46:47'),
(6, 21, 10, 'cc', '1', '2016-08-27 14:06:40'),
(7, 22, 10, 'cc', '1', '2016-08-27 14:07:59'),
(8, 23, 10, 'cc', '1', '2016-08-28 22:20:21'),
(9, 24, 10, 'cc', '1', '2016-08-28 22:24:01'),
(10, 25, 10, 'cc', '1', '2016-08-28 22:25:09'),
(11, 26, 1, 'cc', '1', '2016-08-28 22:37:32'),
(12, 27, 1, 'cc', '1', '2016-08-28 23:09:21'),
(13, 28, 1, 'cc', '1', '2016-08-28 23:17:51'),
(14, 29, 10, 'cc', '1', '2016-08-28 23:23:30'),
(15, 30, 10, 'cc', '1', '2016-09-04 10:59:21'),
(16, 31, 10, 'cc', '1', '2016-09-04 11:02:57'),
(17, 32, 10, 'cc', '1', '2016-09-04 11:23:51'),
(18, 33, 10, 'cc', '1', '2016-09-04 11:40:06'),
(19, 34, 10, 'cc', '1', '2016-09-04 12:56:38'),
(20, 35, 10, 'cc', '1', '2016-09-04 12:57:40'),
(21, 36, 10, 'cc', '1', '2016-09-04 13:03:14'),
(22, 37, 10, 'cc', '1', '2016-09-07 15:58:20'),
(23, 38, 1, 'cc', '1', '2016-10-16 15:27:59'),
(24, 39, 1, 'cc', '1', '2016-10-16 19:30:54'),
(25, 40, 1, 'cc', '1', '2016-10-17 00:31:17'),
(26, 41, 1, 'cc', '1', '2016-10-17 00:32:03'),
(27, 42, 1, 'cc', '1', '2016-10-17 00:34:13'),
(28, 43, 1, 'cc', '1', '2016-10-17 00:34:30'),
(29, 44, 1, 'cc', '1', '2016-10-17 00:54:20'),
(30, 45, 1, 'cc', '1', '2016-10-17 00:57:07'),
(31, 46, 1, 'cc', '1', '2016-10-17 01:02:59'),
(32, 47, 1, 'cc', '1', '2016-10-17 01:04:31'),
(33, 48, 1, 'cc', '1', '2016-10-17 01:06:18'),
(34, 49, 1, 'cc', '1', '2016-10-17 01:06:44'),
(35, 50, 1, 'cc', '1', '2016-10-17 01:06:56'),
(36, 51, 1, 'cc', '1', '2016-10-17 01:11:53'),
(37, 52, 1, 'cc', '1', '2016-10-17 01:13:54'),
(38, 53, 1, 'cc', '1', '2016-10-17 01:14:16'),
(39, 54, 10, 'cc', '1', '2017-08-06 04:12:39'),
(40, 55, 10, 'cc', '1', '2017-08-06 04:12:49');

-- --------------------------------------------------------

--
-- Table structure for table `message_subject`
--

CREATE TABLE IF NOT EXISTS `message_subject` (
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `subject_id` bigint(255) NOT NULL AUTO_INCREMENT,
  `subject` varchar(300) NOT NULL DEFAULT '---',
  PRIMARY KEY (`subject_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=20 ;

--
-- Dumping data for table `message_subject`
--

INSERT INTO `message_subject` (`date`, `subject_id`, `subject`) VALUES
('2016-08-14 10:03:02', 1, 'WELCOME'),
('2016-08-14 15:57:24', 2, 'Deep in 1'),
('2016-08-23 11:26:56', 6, 'XXX1234NULLVALUE1234XXX'),
('2016-08-25 08:38:59', 9, '666'),
('2016-08-25 22:58:27', 10, 'trial'),
('2016-09-04 10:59:21', 11, 'XXX1234NULLVALUE1234XXX'),
('2016-09-04 11:02:57', 12, ''),
('2016-09-04 11:23:51', 13, ''),
('2016-09-04 11:40:06', 14, ''),
('2016-09-04 12:56:38', 15, ''),
('2016-09-04 12:57:40', 16, ''),
('2016-09-04 13:03:14', 17, 'XXX1234NULLVALUE1234XXX'),
('2016-10-16 15:27:59', 18, 'trial77'),
('2016-10-16 19:30:54', 19, '');

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE IF NOT EXISTS `notification` (
  `notification_id` bigint(255) NOT NULL AUTO_INCREMENT,
  `sender_id` bigint(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `message` varchar(500) NOT NULL,
  `privacy` varchar(30) NOT NULL DEFAULT 'public',
  `notification_fullstory_id` bigint(255) NOT NULL,
  `notification_type_id` bigint(255) NOT NULL,
  PRIMARY KEY (`notification_id`),
  KEY `user_id` (`sender_id`),
  KEY `notification_type` (`notification_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `notification_type`
--

CREATE TABLE IF NOT EXISTS `notification_type` (
  `type` varchar(100) NOT NULL,
  `type_id` bigint(255) NOT NULL AUTO_INCREMENT,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `notification_viewed`
--

CREATE TABLE IF NOT EXISTS `notification_viewed` (
  `notification_id` bigint(255) NOT NULL,
  `user_id` bigint(255) NOT NULL,
  `story_read` enum('1','0') NOT NULL DEFAULT '0',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`notification_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ordered_items`
--

CREATE TABLE IF NOT EXISTS `ordered_items` (
  `item_id` bigint(20) NOT NULL,
  `order_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `phone` int(20) NOT NULL,
  `status` enum('waiting for call','waiting for schedule','waiting for delivery','done_finished') NOT NULL DEFAULT 'waiting for call',
  `user_id` bigint(20) NOT NULL,
  `order_id_shown_to_user` varchar(20) NOT NULL,
  `payment` varchar(15) NOT NULL,
  PRIMARY KEY (`order_id`),
  KEY `item_id` (`item_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `profile_cover_pic`
--

CREATE TABLE IF NOT EXISTS `profile_cover_pic` (
  `user_id` bigint(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user_pic` varchar(5000) NOT NULL,
  UNIQUE KEY `user_id_2` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `profile_cover_pic`
--

INSERT INTO `profile_cover_pic` (`user_id`, `date`, `user_pic`) VALUES
(1, '2017-05-31 13:25:06', '0|#$(delimiter-2)$#|media/user/1/image/profile/2017_22/1fc7645931e4aecd4005');

-- --------------------------------------------------------

--
-- Table structure for table `profile_pic`
--

CREATE TABLE IF NOT EXISTS `profile_pic` (
  `user_id` bigint(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user_pic` varchar(5000) NOT NULL,
  UNIQUE KEY `user_id_2` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `profile_pic`
--

INSERT INTO `profile_pic` (`user_id`, `date`, `user_pic`) VALUES
(1, '2017-08-24 20:34:08', '0|#$(delimiter-2)$#|media/user/1/image/profile/2017_34/dbfbbc8369d6036d4ba7');

-- --------------------------------------------------------

--
-- Table structure for table `sign_up_by_confirmcode`
--

CREATE TABLE IF NOT EXISTS `sign_up_by_confirmcode` (
  `confirm_code` varchar(200) NOT NULL,
  `user_type` varchar(10) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` longtext NOT NULL,
  `name` varchar(50) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('pending','confirmed') NOT NULL DEFAULT 'pending',
  `id` bigint(255) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_pass` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;

--
-- Dumping data for table `sign_up_by_confirmcode`
--

INSERT INTO `sign_up_by_confirmcode` (`confirm_code`, `user_type`, `email`, `password`, `name`, `date`, `status`, `id`) VALUES
('ef5377e54994c260ba29', 'shop', 'tiyachamdimba@gmail.com', '$2y$10$PO4zLgxQdS0VuVh/1OSWceXwCrlrkTPe/37H.b17QClJzyNMvZiJS', 'tyeer2', '2017-09-03 13:52:36', 'confirmed', 21),
('c6b906c110c37c1f28f2', 'shop', 'shoppinallyear@gmail.com', '$2y$10$9cIcrBMcVbFto745HXg/TO3SfLiP5ApVXF4ZEIEqs3E9UN0UJIq2q', 'tyeer2', '2017-09-07 10:55:59', 'pending', 22);

-- --------------------------------------------------------

--
-- Table structure for table `sign_up_by_otp`
--

CREATE TABLE IF NOT EXISTS `sign_up_by_otp` (
  `user_type` varchar(10) NOT NULL,
  `password` longtext NOT NULL,
  `name` varchar(50) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('pending','confirmed') NOT NULL DEFAULT 'pending',
  `phone` varchar(20) DEFAULT NULL,
  `id` bigint(255) NOT NULL AUTO_INCREMENT,
  `otp` varchar(6) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `phone` (`phone`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=97 ;

--
-- Dumping data for table `sign_up_by_otp`
--

INSERT INTO `sign_up_by_otp` (`user_type`, `password`, `name`, `date`, `status`, `phone`, `id`, `otp`) VALUES
('shop', '$2y$10$0frm4HaMUNQeXm3XLzh7Tuoh4pJSh0IcFC3BXt2t41hkS.K873/a.', 'jkhgo', '2017-08-28 08:26:15', 'confirmed', '265888484567', 49, '546294'),
('shop', '$2y$10$4qY3QxVhdVAOQoMswyBVfOxESB5KXcqdKE6kVAr0i7Lyenvztwhg2', 'BB3920', '2017-09-01 22:27:35', 'confirmed', '265992626077', 73, '650386'),
('shop', '$2y$10$Qw6dbiwU0Cg39MF2Ik.TGueXxWl3SDoftqSeVjRuvg4AybogMt6iG', 'hjooh', '2017-09-01 23:17:28', 'confirmed', '265999876768', 85, '656335'),
('shop', '$2y$10$Q6MGhL8gVtnTEGiP4/.uJeezJtpqonOtpSYhv91/wenidvC9gt8le', 'hjooh', '2017-09-01 23:20:26', 'confirmed', '265888777987', 86, '878039'),
('shop', '$2y$10$q7RGNv2DhLk3ZZBUUAIQ7OYvS7MM8P7eMQk9SLptCr6EwnSCVnaqi', 'hjooh', '2017-09-01 23:55:42', 'pending', '265888776998', 87, '201709'),
('shop', '$2y$10$vDvsBX5W1OGUkjCFFhppW.6.kQ5b3wDoxWgC4mHO7a.Zvs0yLraC6', 'Masautso', '2017-09-02 19:22:16', 'pending', '999888777898', 92, '425635'),
('buyer', '$2y$10$xX0arWzDdcMnjE4rktvq8uYpQ2x7P8ofxBRZuDUduGTyJBORwZ2ly', 'tyeer2', '2017-09-02 19:22:58', 'pending', '234543654765', 93, '259124'),
('shop', '$2y$10$YLfKD53E7Z0.oRTJrbLgRO80gE7XGSQaQArSXgzWmZt0f0tudUOiK', 'tyeer2', '2017-09-03 11:35:21', 'confirmed', '265888484921', 96, '560591');

-- --------------------------------------------------------

--
-- Table structure for table `type`
--

CREATE TABLE IF NOT EXISTS `type` (
  `name` varchar(15) NOT NULL,
  `id` bigint(255) NOT NULL AUTO_INCREMENT,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `rank` enum('1','2','3','5','6','7','8','9','10') NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `type`
--

INSERT INTO `type` (`name`, `id`, `date`, `rank`) VALUES
('For Sale', 1, '2016-04-10 08:28:18', '1'),
('For Rent', 2, '2016-04-10 08:28:18', '2');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` bigint(255) NOT NULL AUTO_INCREMENT,
  `password` longtext NOT NULL,
  `email` varchar(100) NOT NULL,
  `user_type` enum('shop','buyer') NOT NULL DEFAULT 'buyer',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `registration_status` enum('pending','confirmed') NOT NULL DEFAULT 'pending',
  `phone` varchar(36) DEFAULT NULL,
  `profile_pic` varchar(1000) NOT NULL,
  `display_name` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `rights` varchar(255) NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `phone` (`phone`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=37 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `password`, `email`, `user_type`, `date`, `registration_status`, `phone`, `profile_pic`, `display_name`, `first_name`, `last_name`, `username`, `rights`) VALUES
(1, '$2y$10$duEdaGbKBlwh.oQ.n56yOe7ZQRjP4YN7zeQeSORwEIia/DbaqRRrS', 'tiyachamdimba@gkmail.com', 'shop', '2015-12-27 21:30:25', 'confirmed', 'akjhsdasdy87q68', '', '', '', '', '', ''),
(10, '$2y$10$j0RG7cGiiOjPIk/w6THLKO7yfPS4nSov6iwYaiCnEreOjXNj9cE9q', 'tiyachamdimba@egmail.com', 'buyer', '2016-02-10 23:40:47', 'confirmed', 'ajhdakjh67', '', '', '', '', '', ''),
(18, '$2y$10$4qY3QxVhdVAOQoMswyBVfOxESB5KXcqdKE6kVAr0i7Lyenvztwhg2', '7004d5eef33e247bcbe5', 'buyer', '2017-09-01 22:49:49', 'confirmed', '265992626077', '', '', '', '', '', ''),
(32, '$2y$10$Qw6dbiwU0Cg39MF2Ik.TGueXxWl3SDoftqSeVjRuvg4AybogMt6iG', '95a701fb7208ba7e9af0', 'buyer', '2017-09-01 23:17:54', 'confirmed', '265999876768', '', '', '', '', '', ''),
(34, '$2y$10$duEdaGbKBlwh.oQ.n56yOe7ZQRjP4YN7zeQeSORwEIia/DbaqRRrS', 'NULLVALUE**4bb532e09c776e9152e6', 'buyer', '2017-09-01 23:21:50', 'confirmed', '265888777987', '', '', '', '', '', ''),
(35, '$2y$10$duEdaGbKBlwh.oQ.n56yOe7ZQRjP4YN7zeQeSORwEIia/DbaqRRrS', 'UniqDummyValue:993808f34ae6d87f82ff', 'buyer', '2017-09-03 11:49:18', 'confirmed', '265888484921', '', '', '', '', '', ''),
(36, '$2y$10$PO4zLgxQdS0VuVh/1OSWceXwCrlrkTPe/37H.b17QClJzyNMvZiJS', 'tiyachamdimba@gmail.com', 'buyer', '2017-09-03 13:59:51', 'confirmed', NULL, '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `user_info`
--

CREATE TABLE IF NOT EXISTS `user_info` (
  `user_id` bigint(255) NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'date joined',
  `about` varchar(2000) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  FULLTEXT KEY `user_name` (`user_name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_info`
--

INSERT INTO `user_info` (`user_id`, `user_name`, `date`, `about`) VALUES
(1, 'GlobalShoppers', '2016-02-10 23:40:47', 'the best and coolest shop in town'),
(10, 'Tyeer', '2016-02-10 23:40:47', 'CodeIgniter is an Application Development Framework - a toolkit - for people who build web sites using PHP. Its goal is to enable you to develop projects                           '),
(18, 'BB3920', '2017-09-01 22:49:49', NULL),
(32, 'hjooh', '2017-09-01 23:17:55', NULL),
(34, 'hjooh', '2017-09-01 23:21:50', NULL),
(35, 'tyeer2', '2017-09-03 11:49:18', NULL),
(36, 'tyeer2', '2017-09-03 13:59:51', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_license`
--

CREATE TABLE IF NOT EXISTS `user_license` (
  `license_description` varchar(2000) DEFAULT NULL,
  `user_id` bigint(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `license_pic` varchar(5000) DEFAULT NULL,
  UNIQUE KEY `user_id_2` (`user_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_license`
--

INSERT INTO `user_license` (`license_description`, `user_id`, `date`, `license_pic`) VALUES
(NULL, 1, '2016-03-05 00:15:45', '1|#$(delimiter-2)$#|media/user/1/image/profile/2017_34/ed524baef96f54fe3cac');

-- --------------------------------------------------------

--
-- Table structure for table `verification`
--

CREATE TABLE IF NOT EXISTS `verification` (
  `user_id` bigint(255) NOT NULL,
  `verification` varchar(30) NOT NULL DEFAULT 'normal',
  `date` timestamp NOT NULL,
  `rank` enum('1','2','3','5','6','7','8','9','10') NOT NULL DEFAULT '1',
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `verification`
--

INSERT INTO `verification` (`user_id`, `verification`, `date`, `rank`) VALUES
(1, 'verified', '2017-10-19 22:16:23', '1');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `item` (`item_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `change_email_confirm`
--
ALTER TABLE `change_email_confirm`
  ADD CONSTRAINT `change_email_confirm_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `contact_email`
--
ALTER TABLE `contact_email`
  ADD CONSTRAINT `contact_email_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `contact_map`
--
ALTER TABLE `contact_map`
  ADD CONSTRAINT `contact_map_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `contact_phone`
--
ALTER TABLE `contact_phone`
  ADD CONSTRAINT `contact_phone_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `contact_post`
--
ALTER TABLE `contact_post`
  ADD CONSTRAINT `contact_post_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `followed_page`
--
ALTER TABLE `followed_page`
  ADD CONSTRAINT `followed_page_ibfk_1` FOREIGN KEY (`followed_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `followed_page_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `item`
--
ALTER TABLE `item`
  ADD CONSTRAINT `item_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `item_ibfk_2` FOREIGN KEY (`condition_id`) REFERENCES `item_condition` (`condition_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `item_ibfk_4` FOREIGN KEY (`type_id`) REFERENCES `type` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `item_ibfk_5` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `item_ibfk_6` FOREIGN KEY (`location_id`) REFERENCES `location` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `message_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`user_id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `message_ibfk_3` FOREIGN KEY (`subject_id`) REFERENCES `message_subject` (`subject_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `message_deletion`
--
ALTER TABLE `message_deletion`
  ADD CONSTRAINT `message_deletion_ibfk_1` FOREIGN KEY (`subject_id`) REFERENCES `message_subject` (`subject_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `message_deletion_ibfk_2` FOREIGN KEY (`deleter_id`) REFERENCES `users` (`user_id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `message_receiver`
--
ALTER TABLE `message_receiver`
  ADD CONSTRAINT `message_receiver_ibfk_1` FOREIGN KEY (`message_id`) REFERENCES `message` (`message_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `message_receiver_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`user_id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `notification`
--
ALTER TABLE `notification`
  ADD CONSTRAINT `notification_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `notification_ibfk_2` FOREIGN KEY (`notification_type_id`) REFERENCES `notification_type` (`type_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `notification_viewed`
--
ALTER TABLE `notification_viewed`
  ADD CONSTRAINT `notification_viewed_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `notification_viewed_ibfk_3` FOREIGN KEY (`notification_id`) REFERENCES `notification` (`notification_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ordered_items`
--
ALTER TABLE `ordered_items`
  ADD CONSTRAINT `ordered_items_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `item` (`item_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ordered_items_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `profile_pic`
--
ALTER TABLE `profile_pic`
  ADD CONSTRAINT `profile_pic_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_info`
--
ALTER TABLE `user_info`
  ADD CONSTRAINT `user_info_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_license`
--
ALTER TABLE `user_license`
  ADD CONSTRAINT `user_license_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `verification`
--
ALTER TABLE `verification`
  ADD CONSTRAINT `verification_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
