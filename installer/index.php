<?php
// Domain Manager - A web-based application written in PHP & MySQL used to manage a collection of domain names.
// Copyright (C) 2010 Greg Chetcuti
// 
// Domain Manager is free software; you can redistribute it and/or modify it under the terms of the GNU General
// Public License as published by the Free Software Foundation; either version 2 of the License, or (at your
// option) any later version.
// 
// Domain Manager is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the
// implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License
// for more details.
// 
// You should have received a copy of the GNU General Public License along with Domain Manager. If not, please 
// see http://www.gnu.org/licenses/
?>
<?php
session_start();

$_SESSION['session_installation_mode'] = 1;

include("../_includes/config.inc.php");
include("../_includes/database.inc.php");
include("../_includes/software.inc.php");
include("../_includes/timestamps/current-timestamp-basic.inc.php");

$sql = "
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) NOT NULL auto_increment,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `username` varchar(20) NOT NULL,
  `email_address` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `new_password` int(1) NOT NULL default '1',
  `admin` int(1) NOT NULL default '0',
  `active` int(1) NOT NULL default '1',
  `number_of_logins` int(10) NOT NULL default '0',
  `last_login` datetime NOT NULL,
  `insert_time` datetime NOT NULL,
  `update_time` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
";
$result = mysql_query($sql,$connection) or die(mysql_error());

$sql = "
INSERT INTO `users` (`first_name`, `last_name`, `username`, `email_address`, `password`, `admin`, `insert_time`) VALUES
('Domain', 'Administrator', 'admin', 'admin@aysmedia.com', '*4ACFE3202A5FF5CF467898FC58AAB1D615029441', '1', '$current_timestamp');
";
$result = mysql_query($sql,$connection) or die(mysql_error());

$sql = "
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(10) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `notes` longtext NOT NULL,
  `default_category` int(1) NOT NULL default '0',
  `active` int(1) NOT NULL default '1',
  `test_data` int(1) NOT NULL default '0',
  `insert_time` datetime NOT NULL,
  `update_time` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
";
$result = mysql_query($sql,$connection) or die(mysql_error());

$sql = "
CREATE TABLE IF NOT EXISTS `companies` (
  `id` int(5) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `notes` longtext NOT NULL,
  `active` int(1) NOT NULL default '1',
  `test_data` int(1) NOT NULL default '0',
  `insert_time` datetime NOT NULL,
  `update_time` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
";
$result = mysql_query($sql,$connection) or die(mysql_error());

$sql = "
CREATE TABLE IF NOT EXISTS `currencies` (
  `id` int(10) NOT NULL auto_increment,
  `currency` varchar(3) NOT NULL,
  `name` varchar(75) NOT NULL,
  `conversion` float NOT NULL,
  `notes` longtext NOT NULL,
  `default_currency` int(1) NOT NULL default '0',
  `active` int(1) NOT NULL default '1',
  `test_data` int(1) NOT NULL default '0',
  `insert_time` datetime NOT NULL,
  `update_time` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
";
$result = mysql_query($sql,$connection) or die(mysql_error());

$sql = "
INSERT INTO `currencies` (`id`, `currency`, `name`, `conversion`, `default_currency`, `insert_time`) VALUES
(1, 'CAD', 'Canadian Dollars', 1.00059, 0, '$current_timestamp'),
(2, 'EUR', 'Euros', 1.33693, 0, '$current_timestamp'),
(3, 'AUD', 'Australian Dollars', 0.926664, 0, '$current_timestamp'),
(4, 'CHF', 'Switzerland Francs', 0.932068, 0, '$current_timestamp'),
(5, 'CNY', 'China Yuan Renminbi', 0.14637, 0, '$current_timestamp'),
(6, 'DKK', 'Denmark Kroner', 0.179651, 0, '$current_timestamp'),
(7, 'GBP', 'United Kingdom Pounds', 1.53832, 0, '$current_timestamp'),
(8, 'HKD', 'Hong Kong Dollars', 0.128822, 0, '$current_timestamp'),
(9, 'HUF', 'Hungary Forint', 0.00508413, 0, '$current_timestamp'),
(10, 'INR', 'India Rupees', 0.0225657, 0, '$current_timestamp'),
(11, 'JPY', 'Japan Yen', 0.010645, 0, '$current_timestamp'),
(12, 'MXN', 'Mexico Pesos', 0.0819931, 0, '$current_timestamp'),
(13, 'MYR', 'Malaysia Ringgits', 0.313411, 0, '$current_timestamp'),
(14, 'NOK', 'Norway Kroner', 0.169632, 0, '$current_timestamp'),
(15, 'NZD', 'New Zealand Dollars', 0.717594, 0, '$current_timestamp'),
(16, 'RUB', 'Russia Rubles', 0.0342797, 0, '$current_timestamp'),
(17, 'SEK', 'Sweden Kronor', 0.139414, 0, '$current_timestamp'),
(18, 'SGD', 'Singapore Dollars', 0.729672, 0, '$current_timestamp'),
(19, 'THB', 'Thailand Baht', 0.0310316, 0, '$current_timestamp'),
(20, 'USD', 'United States Dollars', 1, 1, '$current_timestamp'),
(21, 'ZAR', 'South Africa Rand', 0.134658, 0, '$current_timestamp'),
(22, 'AED', 'United Arab Emirates Dirhams', 0.272297, 0, '$current_timestamp');
";
$result = mysql_query($sql,$connection) or die(mysql_error());

$sql = "
CREATE TABLE IF NOT EXISTS `fees` (
  `id` int(10) NOT NULL auto_increment,
  `registrar_id` int(5) NOT NULL,
  `tld` varchar(10) NOT NULL,
  `initial_fee` float NOT NULL,
  `renewal_fee` float NOT NULL,
  `currency_id` int(10) NOT NULL,
  `test_data` int(1) NOT NULL default '0',
  `fee_fixed` int(1) NOT NULL,
  `insert_time` datetime NOT NULL,
  `update_time` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
";
$result = mysql_query($sql,$connection) or die(mysql_error());

$sql = "
CREATE TABLE IF NOT EXISTS `ssl_fees` (
  `id` int(10) NOT NULL auto_increment,
  `ssl_provider_id` int(5) NOT NULL,
  `type` varchar(255) NOT NULL,
  `initial_fee` float NOT NULL,
  `renewal_fee` float NOT NULL,
  `currency_id` int(10) NOT NULL,
  `test_data` int(1) NOT NULL default '0',
  `fee_fixed` int(1) NOT NULL,
  `insert_time` datetime NOT NULL,
  `update_time` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
";
$result = mysql_query($sql,$connection) or die(mysql_error());

$sql = "
CREATE TABLE IF NOT EXISTS `domains` (
  `id` int(10) NOT NULL auto_increment,
  `company_id` int(5) NOT NULL,
  `registrar_id` int(5) NOT NULL,
  `account_id` int(5) NOT NULL,
  `domain` varchar(255) NOT NULL,
  `tld` varchar(50) NOT NULL,
  `expiry_date` date NOT NULL,
  `cat_id` int(10) NOT NULL default '1',
  `fee_id` int(10) NOT NULL default '0',
  `dns_id` int(10) NOT NULL default '0',
  `function` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `status_notes` longtext NOT NULL,
  `notes` longtext NOT NULL,
  `privacy` int(1) NOT NULL default '0',
  `active` int(1) NOT NULL default '1',
  `test_data` int(1) NOT NULL default '0',
  `fee_fixed` int(1) NOT NULL,
  `insert_time` datetime NOT NULL,
  `update_time` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `domain` (`domain`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
";
$result = mysql_query($sql,$connection) or die(mysql_error());

$sql = "
CREATE TABLE IF NOT EXISTS `ssl_certs` (
  `id` int(10) NOT NULL auto_increment,
  `company_id` int(5) NOT NULL,
  `ssl_provider_id` int(5) NOT NULL,
  `account_id` int(5) NOT NULL,
  `domain_id` int(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `expiry_date` date NOT NULL,
  `fee_id` int(10) NOT NULL,
  `notes` longtext NOT NULL,
  `active` int(1) NOT NULL default '1',
  `test_data` int(1) NOT NULL default '0',
  `fee_fixed` int(1) NOT NULL,
  `insert_time` datetime NOT NULL,
  `update_time` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
";
$result = mysql_query($sql,$connection) or die(mysql_error());

$sql = "
CREATE TABLE IF NOT EXISTS `dns` (
  `id` int(10) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `dns1` varchar(255) NOT NULL,
  `dns2` varchar(255) NOT NULL,
  `dns3` varchar(255) NOT NULL,
  `dns4` varchar(255) NOT NULL,
  `dns5` varchar(255) NOT NULL,
  `dns6` varchar(255) NOT NULL,
  `dns7` varchar(255) NOT NULL,
  `dns8` varchar(255) NOT NULL,
  `dns9` varchar(255) NOT NULL,
  `dns10` varchar(255) NOT NULL,
  `notes` longtext NOT NULL,
  `number_of_servers` int(2) NOT NULL default '0',
  `active` int(1) NOT NULL default '1',
  `test_data` int(1) NOT NULL default '0',
  `insert_time` datetime NOT NULL,
  `update_time` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
";
$result = mysql_query($sql,$connection) or die(mysql_error());

$sql = "
CREATE TABLE IF NOT EXISTS `registrars` (
  `id` int(5) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `notes` longtext NOT NULL,
  `active` int(1) NOT NULL default '1',
  `test_data` int(1) NOT NULL default '0',
  `insert_time` datetime NOT NULL,
  `update_time` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
";
$result = mysql_query($sql,$connection) or die(mysql_error());

$sql = "
CREATE TABLE IF NOT EXISTS `ssl_providers` (
  `id` int(5) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `notes` longtext NOT NULL,
  `active` int(1) NOT NULL default '1',
  `test_data` int(1) NOT NULL default '0',
  `insert_time` datetime NOT NULL,
  `update_time` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
";
$result = mysql_query($sql,$connection) or die(mysql_error());

$sql = "
CREATE TABLE IF NOT EXISTS `registrar_accounts` (
  `id` int(10) NOT NULL auto_increment,
  `company_id` int(5) NOT NULL,
  `registrar_id` int(10) NOT NULL,
  `username` varchar(255) NOT NULL,
  `notes` longtext NOT NULL,
  `reseller` int(1) NOT NULL default '0',
  `active` int(1) NOT NULL default '1',
  `test_data` int(1) NOT NULL default '0',
  `insert_time` datetime NOT NULL,
  `update_time` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `registrar_id` (`registrar_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
";
$result = mysql_query($sql,$connection) or die(mysql_error());

$sql = "
CREATE TABLE IF NOT EXISTS `ssl_accounts` (
  `id` int(10) NOT NULL auto_increment,
  `company_id` int(5) NOT NULL,
  `ssl_provider_id` int(10) NOT NULL,
  `username` varchar(255) NOT NULL,
  `notes` longtext NOT NULL,
  `reseller` int(1) NOT NULL default '0',
  `active` int(1) NOT NULL default '1',
  `test_data` int(1) NOT NULL default '0',
  `insert_time` datetime NOT NULL,
  `update_time` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `ssl_provider_id` (`ssl_provider_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
";
$result = mysql_query($sql,$connection) or die(mysql_error());

$sql = "
CREATE TABLE IF NOT EXISTS `segments` (
  `id` int(10) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  `segment` longtext NOT NULL,
  `number_of_domains` int(6) NOT NULL,
  `notes` longtext NOT NULL,
  `active` int(1) NOT NULL default '1',
  `test_data` int(1) NOT NULL default '0',
  `insert_time` datetime NOT NULL,
  `update_time` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
";
$result = mysql_query($sql,$connection) or die(mysql_error());

header("Location: ../_includes/system/test-data-generate.php");
exit;
?>