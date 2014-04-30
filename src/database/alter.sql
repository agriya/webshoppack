-- Date added: 25/04/2014

ALTER TABLE `product` ADD `is_featured_product` ENUM( 'Yes', 'No' ) NOT NULL DEFAULT 'No' AFTER `total_views` ,
ADD `is_user_featured_product` ENUM( 'Yes', 'No' ) NOT NULL DEFAULT 'No' AFTER `is_featured_product`;

-- Date added: 26/04/2014

-- --------------------------------------------------------

--
-- Table structure for table `product_log`
--

DROP TABLE IF EXISTS `product_log`;
CREATE TABLE `product_log` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) NOT NULL,
  `date_added` datetime NOT NULL,
  `added_by` enum('User','Admin','Staff') NOT NULL DEFAULT 'User',
  `user_id` bigint(20) NOT NULL,
  `notes` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `product_attributes_option_values`
--

DROP TABLE IF EXISTS `product_attributes_option_values`;
CREATE TABLE `product_attributes_option_values` (
  `product_id` bigint(20) NOT NULL,
  `attribute_id` bigint(20) NOT NULL,
  `attribute_options_id` bigint(20) NOT NULL,
  KEY `product_id` (`product_id`,`attribute_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `product_attributes`
--

DROP TABLE IF EXISTS `product_attributes`;
CREATE TABLE `product_attributes` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `attribute_label` varchar(250) NOT NULL,
  `attribute_help_tip` varchar(250) NOT NULL,
  `attribute_question_type` enum('text','textarea','select','check','option','multiselectlist') NOT NULL DEFAULT 'text',
  `default_value` text NOT NULL,
  `validation_rules` varchar(250) NOT NULL,
  `date_added` date NOT NULL,
  `is_searchable` enum('yes','no') NOT NULL DEFAULT 'no',
  `show_in_list` enum('yes','no') NOT NULL DEFAULT 'yes',
  `description` varchar(250) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `product_attribute_options`
--

DROP TABLE IF EXISTS `product_attribute_options`;
CREATE TABLE `product_attribute_options` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `attribute_id` bigint(20) NOT NULL,
  `option_label` varchar(150) NOT NULL,
  `option_value` varchar(150) NOT NULL,
  `is_default_option` enum('yes','no') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `currency_exchange_rate`
--

DROP TABLE IF EXISTS `currency_exchange_rate`;
CREATE TABLE `currency_exchange_rate` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `country` varchar(250) NOT NULL,
  `country_code` varchar(10) NOT NULL,
  `currency_code` varchar(10) NOT NULL,
  `currency_symbol` varchar(25) NOT NULL,
  `currency_name` varchar(250) NOT NULL,
  `exchange_rate` varchar(20) NOT NULL,
  `status` enum('Active','InActive') NOT NULL DEFAULT 'Active',
  `paypal_supported` enum('Yes','No') NOT NULL DEFAULT 'No',
  `display_currency` enum('Yes','No') NOT NULL DEFAULT 'No',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `currency_exchange_rate`
--

INSERT INTO `currency_exchange_rate` (`id`, `country`, `country_code`, `currency_code`, `currency_symbol`, `currency_name`, `exchange_rate`, `status`, `paypal_supported`, `display_currency`) VALUES
(1, 'United Arab Emirates', 'ARE', 'AED', '', 'United Arab Emirates Dirham', '3.672688', 'Active', 'No', 'No'),
(2, 'Afghanistan', 'AFG', 'AFN', '', 'Afghan Afghani', '48.181299', 'Active', 'No', 'No'),
(3, 'Albania', 'ALB', 'ALL', '', 'Albanian Lek', '109.734999', 'Active', 'No', 'No'),
(4, 'Armenia', 'ARM', 'AMD', '', 'Armenian Dram', '410.544995', 'Active', 'No', 'No'),
(5, 'Netherlands Antilles', 'ANT', 'ANG', '', 'Netherlands Antillean Guilder', '1.7738', 'Active', 'No', 'No'),
(6, 'Angola', 'AGO', 'AOA', '', 'Angolan Kwanza', '95.489998', 'Active', 'No', 'No'),
(7, 'Argentina', 'ARG', 'ARS', '', 'Argentine Peso', '4.615274', 'Active', 'No', 'No'),
(8, 'Australia', 'AUS', 'AUD', '$', 'Australian Dollar', '0.953657', 'Active', 'No', 'Yes'),
(9, 'Christmas Island', 'CXR', 'AUD', '', 'Australian Dollar', '0.953657', 'Active', 'No', 'No'),
(10, 'Cocos (Keeling) Islands', 'CCK', 'AUD', '', 'Australian Dollar', '0.953657', 'Active', 'No', 'No'),
(11, 'Heard Island and McDonald Islands', 'HMD', 'AUD', '', 'Australian Dollar', '0.953657', 'Active', 'No', 'No'),
(12, 'Kiribati', 'KIR', 'AUD', '', 'Australian Dollar', '0.953657', 'Active', 'No', 'No'),
(13, 'Nauru', 'NRU', 'AUD', '', 'Australian Dollar', '0.953657', 'Active', 'No', 'No'),
(14, 'Norfolk Island', 'NFK', 'AUD', '', 'Australian Dollar', '0.953657', 'Active', 'No', 'No'),
(15, 'Tuvalu', 'TUV', 'AUD', '', 'Australian Dollar', '0.953657', 'Active', 'No', 'No'),
(16, 'Aruba', 'ABW', 'AWG', '', 'Aruban Florin', '1.7901', 'Active', 'No', 'No'),
(17, 'Azerbaijan', 'AZE', 'AZN', '', 'Azerbaijani Manat', '0.7854', 'Active', 'No', 'No'),
(18, 'Bosnia-Herzegovina', 'BIH', 'BAM', '', 'Bosnia-Herzegovina Convertible Mark', '1.559625', 'Active', 'No', 'No'),
(19, 'Barbados', 'BRB', 'BBD', '', 'Barbadian Dollar', '2', 'Active', 'No', 'No'),
(20, 'Bangladesh', 'BGD', 'BDT', '', 'Bangladeshi Taka', '81.209433', 'Active', 'No', 'No'),
(21, 'Bulgaria', 'BGR', 'BGN', '', 'Bulgarian Lev', '1.558388', 'Active', 'No', 'No'),
(22, 'Bahrain', 'BHR', 'BHD', '', 'Bahraini Dina', '0.376694', 'Active', 'No', 'No'),
(23, 'Burundi', 'BDI', 'BIF', '', 'Burundian Franc', '1434.126667', 'Active', 'No', 'No'),
(24, 'Bermuda', 'BMU', 'BMD', '', 'Bermudan Dollar', '1', 'Active', 'No', 'No'),
(25, 'Brunei Darussalam', 'BRN', 'BND', '', 'Brunei Dollar', '1.244213', 'Active', 'No', 'No'),
(26, 'Bolivia', 'BOL', 'BOB', '', 'Bolivian Boliviano', '6.960804', 'Active', 'No', 'No'),
(27, 'Brazil', 'BRA', 'BRL', 'R$', 'Brazilian Real', '2.0166', 'Active', 'No', 'Yes'),
(28, 'Bahamas', 'BHS', 'BSD', '', 'Bahamian Dollar', '1', 'Active', 'No', 'No'),
(29, 'Bhutan', 'BTN', 'BTN', '', 'Bhutanese Ngultrum', '51.295', 'Active', 'No', 'No'),
(30, 'Botswana', 'BWA', 'BWP', '', 'Botswanan Pula', '7.694578', 'Active', 'No', 'No'),
(31, 'Belarus', 'BLR', 'BYR', '', 'Belarusian Ruble', '8359.9', 'Active', 'No', 'No'),
(32, 'Belize', 'BLZ', 'BZD', '', 'Belize Dollar', '1.88145', 'Active', 'No', 'No'),
(33, 'Canada', 'CAN', 'CAD', '$', 'Canadian Dollar', '0.99037', 'Active', 'No', 'Yes'),
(34, 'Congo, Dem. Republic', 'COD', 'CDF', '', 'Congolese Franc', '923.415161', 'Active', 'No', 'No'),
(35, 'Liechtenstein', 'LIE', 'CHF', '', 'Swiss Franc', '0.957061', 'Active', 'No', 'No'),
(36, 'Switzerland', 'CHE', 'CHF', 'CHF', 'Swiss Franc', '0.957061', 'Active', 'No', 'Yes'),
(37, 'Chile', 'CHL', 'CLP', '', 'Chilean Peso', '481.812127', 'Active', 'No', 'No'),
(38, 'China', 'CHN', 'CNY', '¥', 'Chinese Yuan Renminbi', '6.354264', 'Active', 'No', 'Yes'),
(39, 'Colombia', 'COL', 'COP', '', 'Colombian Peso', '1812.72105', 'Active', 'No', 'No'),
(40, 'Costa Rica', 'CRI', 'CRC', '', 'Costa Rican Colon', '497.835031', 'Active', 'No', 'No'),
(41, 'Cuba', 'CUB', 'CUP', '', 'Cuban Peso', '1', 'Active', 'No', 'No'),
(42, 'Cape Verde', 'CPV', 'CVE', '', 'Cape Verdean Escudo', '88.679566', 'Active', 'No', 'No'),
(43, 'Czech Rep.', 'CZE', 'CZK', 'Kč', 'Czech Republic Koruna', '19.833468', 'Active', 'No', 'Yes'),
(44, 'Djibouti', 'DJI', 'DJF', '', 'Djiboutian Franc', '177.753332', 'Active', 'No', 'No'),
(45, 'Denmark', 'DNK', 'DKK', 'kr', 'Danish Krone', '5.934224', 'Active', 'No', 'Yes'),
(46, 'Faroe Islands', 'FRO', 'DKK', '', 'Danish Krone', '5.934224', 'Active', 'No', 'No'),
(47, 'Greenland', 'GRL', 'DKK', '', 'Danish Krone', '5.934224', 'Active', 'No', 'No'),
(48, 'Dominican Republic', 'DOM', 'DOP', '', 'Dominican Peso', '38.891081', 'Active', 'No', 'No'),
(49, 'Algeria', 'DZA', 'DZD', '', 'Algerian Dinar', '80.414547', 'Active', 'No', 'No'),
(50, 'Egypt', 'EGY', 'EGP', '', 'Egyptian Pound', '6.088136', 'Active', 'No', 'No'),
(51, 'Ethiopia', 'ETH', 'ETB', '', 'Ethiopian Birr', '17.894417', 'Active', 'No', 'No'),
(52, 'Andorra', 'AND', 'EUR', '', 'Euro', '0.796577', 'Active', 'No', 'No'),
(53, 'Austria', 'AUT', 'EUR', '', 'Euro', '0.796577', 'Active', 'No', 'No'),
(54, 'Belgium', 'BEL', 'EUR', '', 'Euro', '0.796577', 'Active', 'No', 'No'),
(55, 'European Union', '', 'EUR', '', 'Euro', '0.796577', 'Active', 'No', 'No'),
(56, 'Finland', 'FIN', 'EUR', '', 'Euro', '0.796577', 'Active', 'No', 'No'),
(57, 'France', 'FRA', 'EUR', '', 'Euro', '0.796577', 'Active', 'No', 'No'),
(58, 'French Guiana', 'GUF', 'EUR', '', 'Euro', '0.796577', 'Active', 'No', 'No'),
(59, 'French Southern Territories', 'ATF', 'EUR', '', 'Euro', '0.796577', 'Active', 'No', 'No'),
(60, 'Germany', 'DEU', 'EUR', '&euro;', 'Euro', '0.796577', 'Active', 'No', 'Yes'),
(61, 'Greece', 'GRC', 'EUR', '', 'Euro', '0.796577', 'Active', 'No', 'No'),
(62, 'Guadeloupe (French)', 'GLP', 'EUR', '', 'Euro', '0.796577', 'Active', 'No', 'No'),
(63, 'Ireland', 'IRL', 'EUR', '', 'Euro', '0.796577', 'Active', 'No', 'No'),
(64, 'Italy', 'ITA', 'EUR', '', 'Euro', '0.796577', 'Active', 'No', 'No'),
(65, 'Luxembourg', 'LUX', 'EUR', '', 'Euro', '0.796577', 'Active', 'No', 'No'),
(66, 'Martinique (French)', 'MTQ', 'EUR', '', 'Euro', '0.796577', 'Active', 'No', 'No'),
(67, 'Mayotte', 'MYT', 'EUR', '', 'Euro', '0.796577', 'Active', 'No', 'No'),
(68, 'Monaco', 'MCO', 'EUR', '', 'Euro', '0.796577', 'Active', 'No', 'No'),
(69, 'Montenegro', 'MNE', 'EUR', '', 'Euro', '0.796577', 'Active', 'No', 'No'),
(70, 'Netherlands', 'NLD', 'EUR', '', 'Euro', '0.796577', 'Active', 'No', 'No'),
(71, 'Portugal', 'PRT', 'EUR', '', 'Euro', '0.796577', 'Active', 'No', 'No'),
(72, 'Reunion (French)', 'REU', 'EUR', '', 'Euro', '0.796577', 'Active', 'No', 'No'),
(73, 'Saint Pierre and Miquelon', 'SPM', 'EUR', '', 'Euro', '0.796577', 'Active', 'No', 'No'),
(74, 'Spain', 'ESP', 'EUR', '', 'Euro', '0.796577', 'Active', 'No', 'No'),
(75, 'Vatican', 'VAT', 'EUR', '', 'Euro', '0.796577', 'Active', 'No', 'No'),
(76, 'Fiji', 'FJI', 'FJD', '', 'Fijian Dollar', '1.77025', 'Active', 'No', 'No'),
(77, 'Falkland Islands', 'FLK', 'FKP', '', 'Falkland Islands Pound', '0.629755', 'Active', 'No', 'No'),
(78, 'Great Britain', 'GBR', 'GBP', '£', 'British Pound Sterling', '0.629755', 'Active', 'No', 'Yes'),
(79, 'Guernsey', 'GGY', 'GBP', '', 'British Pound Sterling', '0.629755', 'Active', 'No', 'No'),
(80, 'Isle of Man', 'IMN', 'GBP', '', 'British Pound Sterling', '0.629755', 'Active', 'No', 'No'),
(81, 'Jersey', 'JEY', 'GBP', '', 'British Pound Sterling', '0.629755', 'Active', 'No', 'No'),
(82, 'South Georgia & South Sandwich Islands', 'SGS', 'GBP', '', 'British Pound Sterling', '0.629755', 'Active', 'No', 'No'),
(83, 'U.K.', 'GBR', 'GBP', '', 'British Pound Sterling', '0.629755', 'Active', 'No', 'No'),
(84, 'Georgia', 'GEO', 'GEL', '', 'Georgian Lari', '1.726683', 'Active', 'No', 'No'),
(85, 'Ghana', 'GHA', 'GHS', '', 'Ghanaian Cedi', '1.931433', 'Active', 'No', 'No'),
(86, 'Gibraltar', 'GIB', 'GIP', '', 'Gibraltar Pound', '0.62767', 'Active', 'No', 'No'),
(87, 'Gambia', 'GMB', 'GMD', '', 'Gambian Dalasi', '31.881101', 'Active', 'No', 'No'),
(88, 'Guinea', 'GIN', 'GNF', '', 'Guinean Franc', '7156.966667', 'Active', 'No', 'No'),
(89, 'Guatemala', 'GTM', 'GTQ', '', 'Guatemalan Quetzal', '7.829517', 'Active', 'No', 'No'),
(90, 'Guyana', 'GUY', 'GYD', '', 'Guyanaese Dollar', '202.949998', 'Active', 'No', 'No'),
(91, 'Hong Kong', 'HKG', 'HKD', '$', 'Hong Kong Dollar', '7.756244', 'Active', 'No', 'Yes'),
(92, 'Honduras', 'HND', 'HNL', '', 'Honduran Lempira', '19.46019', 'Active', 'No', 'No'),
(93, 'Croatia', 'HRV', 'HRK', '', 'Croatian Kuna', '5.969697', 'Active', 'No', 'No'),
(94, 'Haiti', 'HTI', 'HTG', '', 'Haitian Gourde', '41.8571', 'Active', 'No', 'No'),
(95, 'Hungary', 'HUN', 'HUF', 'Ft', 'Hungarian Forint', '220.054208', 'Active', 'No', 'Yes'),
(96, 'Indonesia', 'IDN', 'IDR', '', 'Indonesian Rupiah', '9500.42738', 'Active', 'No', 'No'),
(97, 'Israel', 'ISR', 'ILS', '₪', 'Israeli New Sheqel', '4.001313', 'Active', 'No', 'Yes'),
(98, 'India', 'IND', 'INR', 'Rs', 'Indian Rupee', '54', 'Active', 'No', 'Yes'),
(99, 'Iraq', 'IRQ', 'IQD', '', 'Iraqi Dinar', '1158.33', 'Active', 'No', 'No'),
(100, 'Iran', 'IRN', 'IRR', '', 'Iranian Rial', '12223.726667', 'Active', 'No', 'No'),
(101, 'Iceland', 'ISL', 'ISK', '', 'Icelandic Krona', '118.62', 'Active', 'No', 'No'),
(102, 'Jamaica', 'JAM', 'JMD', '', 'Jamaican Dollar', '88.8657', 'Active', 'No', 'No'),
(103, 'Jordan', 'JOR', 'JOD', '', 'Jordanian Dinar', '0.70849', 'Active', 'No', 'No'),
(104, 'Japan', 'JPN', 'JPY', '¥', 'Japanese Yen', '78.599817', 'Active', 'No', 'Yes'),
(105, 'Kenya', 'KEN', 'KES', '', 'Kenyan Shilling', '83.983915', 'Active', 'No', 'No'),
(106, 'Kyrgyzstan', 'KGZ', 'KGS', '', 'Kyrgystani Som', '44.952151', 'Active', 'No', 'No'),
(107, 'Cambodia', 'KHM', 'KHR', '', 'Cambodian Riel', '4052.19', 'Active', 'No', 'No'),
(108, 'Comoros', 'COM', 'KMF', '', 'Comorian Franc', '396.545429', 'Active', 'No', 'No'),
(109, 'Korea-North', 'PRK', 'KPW', '', 'North Korean Won', '900', 'Active', 'No', 'No'),
(110, 'Korea-South', 'KOR', 'KRW', '', 'South Korean Won', '1130.495862', 'Active', 'No', 'No'),
(111, 'Kuwait', 'KWT', 'KWD', '', 'Kuwaiti Dinar', '0.281952', 'Active', 'No', 'No'),
(112, 'Kazakhstan', 'KAZ', 'KZT', '', 'Kazakhstani Tenge', '148.486876', 'Active', 'No', 'No'),
(113, 'Laos', 'LAO', 'LAK', '', 'Laotian Kip', '7982.166667', 'Active', 'No', 'No'),
(114, 'Lebanon', 'LBN', 'LBP', '', 'Lebanese Pound', '1500.418525', 'Active', 'No', 'No'),
(115, 'Sri Lanka', 'LKA', 'LKR', '', 'Sri Lankan Rupee', '132.321911', 'Active', 'No', 'No'),
(116, 'Liberia', 'LBR', 'LRD', '', 'Liberian Dollar', '74.1125', 'Active', 'No', 'No'),
(117, 'Lesotho', 'LSO', 'LSL', '', 'Lesotho Loti', '8.259983', 'Active', 'No', 'No'),
(118, 'Lithuania', 'LTU', 'LTL', '', 'Lithuanian Litas', '2.750449', 'Active', 'No', 'No'),
(119, 'Latvia', 'LVA', 'LVL', '', 'Latvian Lats', '0.554573', 'Active', 'No', 'No'),
(120, 'Libya', 'LBY', 'LYD', '', 'Libyan Dinar', '1.256817', 'Active', 'No', 'No'),
(121, 'Morocco', 'MAR', 'MAD', '', 'Moroccan Dirham', '8.801646', 'Active', 'No', 'No'),
(122, 'Western Sahara', 'ESH', 'MAD', '', 'Moroccan Dirham', '8.801646', 'Active', 'No', 'No'),
(123, 'Moldova', 'MDA', 'MDL', '', 'Moldovan Leu', '12.436819', 'Active', 'No', 'No'),
(124, 'Madagascar', 'MDG', 'MGA', '', 'Malagasy Ariary', '2228.966667', 'Active', 'No', 'No'),
(125, 'Macedonia', 'MKD', 'MKD', '', 'Macedonian Denar', '49.998625', 'Active', 'No', 'No'),
(126, 'Myanmar', 'MMR', 'MMK', '', 'Myanma Kyat', '871.705', 'Active', 'No', 'No'),
(127, 'Mongolia', 'MNG', 'MNT', '', 'Mongolian Tugrik', '1397.929993', 'Active', 'No', 'No'),
(128, 'Macau', 'MAC', 'MOP', '', 'Macanese Pataca', '7.9522', 'Active', 'No', 'No'),
(129, 'Mauritania', 'MRT', 'MRO', '', 'Mauritanian Ouguiya', '300.681667', 'Active', 'No', 'No'),
(130, 'Mauritius', 'MUS', 'MUR', '', 'Mauritian Rupee', '30.411164', 'Active', 'No', 'No'),
(131, 'Maldives', 'MDV', 'MVR', '', 'Maldivian Rufiyaa', '15.334333', 'Active', 'No', 'No'),
(132, 'Malawi', 'MWI', 'MWK', '', 'Malawian Kwacha', '273.352508', 'Active', 'No', 'No'),
(133, 'Mexico', 'MEX', 'MXN', '$', 'Mexican Peso', '13.103786', 'Active', 'No', 'Yes'),
(134, 'Malaysia', 'MYS', 'MYR', 'RM', 'Malaysian Ringgit', '3.095787', 'Active', 'No', 'Yes'),
(135, 'Mozambique', 'MOZ', 'MZN', '', 'Mozambican Metical', '28.50875', 'Active', 'No', 'No'),
(136, 'Namibia', 'NAM', 'NAD', '', 'Namibian Dollar', '8.255996', 'Active', 'No', 'No'),
(137, 'Nigeria', 'NGA', 'NGN', '', 'Nigerian Naira', '157.304594', 'Active', 'No', 'No'),
(138, 'Nicaragua', 'NIC', 'NIO', '', 'Nicaraguan Cordoba', '23.626742', 'Active', 'No', 'No'),
(139, 'Bouvet Island', 'BVT', 'NOK', '', 'Norwegian Krone', '5.854828', 'Active', 'No', 'No'),
(140, 'Norway', 'NOR', 'NOK', 'kr', 'Norwegian Krone', '5.854828', 'Active', 'No', 'Yes'),
(141, 'Svalbard and Jan Mayen Islands', 'SJM', 'NOK', '', 'Norwegian Krone', '5.854828', 'Active', 'No', 'No'),
(142, 'Nepal', 'NPL', 'NPR', '', 'Nepalese Rupee', '88.11028', 'Active', 'No', 'No'),
(143, 'Cook Islands', 'COK', 'NZD', '', 'New Zealand Dollar', '1.224302', 'Active', 'No', 'No'),
(144, 'New Zealand', 'NZL', 'NZD', '$', 'New Zealand Dollar', '1.224302', 'Active', 'No', 'Yes'),
(145, 'Niue', 'NIU', 'NZD', '', 'New Zealand Dollar', '1.224302', 'Active', 'No', 'No'),
(146, 'Pitcairn Island', 'PCN', 'NZD', '', 'New Zealand Dollar', '1.224302', 'Active', 'No', 'No'),
(147, 'Tokelau', 'TKL', 'NZD', '', 'New Zealand Dollar', '1.224302', 'Active', 'No', 'No'),
(148, 'Oman', 'OMN', 'OMR', '', 'Omani Rial', '0.384812', 'Active', 'No', 'No'),
(149, 'Panama', 'PAN', 'PAB', '', 'Panamanian Balboa', '1', 'Active', 'No', 'No'),
(150, 'Peru', 'PER', 'PEN', '', 'Peruvian Nuevo Sol', '2.603013', 'Active', 'No', 'No'),
(151, 'Papua New Guinea', 'PNG', 'PGK', '', 'Papua New Guinean Kina', '2.054549', 'Active', 'No', 'No'),
(152, 'Philippines', 'PHL', 'PHP', '₱', 'Philippine Peso', '42.135849', 'Active', 'No', 'Yes'),
(153, 'Pakistan', 'PAK', 'PKR', '', 'Pakistani Rupee', '94.023294', 'Active', 'No', 'No'),
(154, 'Poland', 'POL', 'PLN', 'zł', 'Polish Zloty', '3.247987', 'Active', 'No', 'Yes'),
(155, 'Paraguay', 'PRY', 'PYG', '', 'Paraguayan Guarani', '4410.84861', 'Active', 'No', 'No'),
(156, 'Qatar', 'QAT', 'QAR', '', 'Qatari Rial', '3.640912', 'Active', 'No', 'No'),
(157, 'Romania', 'ROU', 'RON', '', 'Romanian Leu', '3.577474', 'Active', 'No', 'No'),
(158, 'Serbia', 'SRB', 'RSD', '', 'Serbian Dinar', '93.726442', 'Active', 'No', 'No'),
(159, 'Russia', 'RUS', 'RUB', '', 'Russian Ruble', '31.697677', 'Active', 'No', 'No'),
(160, 'Rwanda', 'RWA', 'RWF', '', 'Rwandan Franc', '609.539388', 'Active', 'No', 'No'),
(161, 'Saudi Arabia', 'SAU', 'SAR', '', 'Saudi Riyal', '3.750176', 'Active', 'No', 'No'),
(162, 'Solomon Islands', 'SLB', 'SBD', '', 'Solomon Islands Dollar', '7.065961', 'Active', 'No', 'No'),
(163, 'Seychelles', 'SYC', 'SCR', '', 'Seychellois Rupee', '13.040057', 'Active', 'No', 'No'),
(164, 'Sudan', 'SDN', 'SDG', '', 'Sudanese Pound', '4.4152', 'Active', 'No', 'No'),
(165, 'Sweden', 'SWE', 'SEK', 'kr', 'Swedish Krona', '6.635852', 'Active', 'No', 'Yes'),
(166, 'Singapore', 'SGP', 'SGD', '$', 'Singapore Dollar', '1.245888', 'Active', 'No', 'Yes'),
(167, 'Saint Helena', 'SHN', 'SHP', '', 'Saint Helena Pound', '0.629755', 'Active', 'No', 'No'),
(168, 'Sierra Leone', 'SLE', 'SLL', '', 'Sierra Leonean Leone', '4361.775363', 'Active', 'No', 'No'),
(169, 'Somalia', 'SOM', 'SOS', '', 'Somali Shilling', '1617.456667', 'Active', 'No', 'No'),
(170, 'Suriname', 'SUR', 'SRD', '', 'Surinamese Dollar', '3.275', 'Active', 'No', 'No'),
(171, 'Sao Tome and Principe', 'STP', 'STD', '', 'Sao Tomé and Príncipe Dobra', '20072.246667', 'Active', 'No', 'No'),
(172, 'El Salvador', 'SLV', 'SVC', '', 'Salvadoran Colon', '8.71802', 'Active', 'No', 'No'),
(173, 'Syria', 'SYR', 'SYP', '', 'Syrian Pound', '65.246168', 'Active', 'No', 'No'),
(174, 'Swaziland', 'SWZ', 'SZL', '', 'Swazi Lilangeni', '8.261117', 'Active', 'No', 'No'),
(175, 'Thailand', 'THA', 'THB', '฿', 'Thai Baht', '31.214507', 'Active', 'No', 'Yes'),
(176, 'Tajikistan', 'TJK', 'TJS', '', 'Tajikistani Somoni', '4.5819', 'Active', 'No', 'No'),
(177, 'Turkmenistan', 'TKM', 'TMT', '', 'Turkmenistani Manat', '2.8503', 'Active', 'No', 'No'),
(178, 'Tunisia', 'TUN', 'TND', '', 'Tunisian Dinar', '1.596925', 'Active', 'No', 'No'),
(179, 'Tonga', 'TON', 'TOP', '', 'Tongan Pa''anga', '1.751448', 'Active', 'No', 'No'),
(180, 'Turkey', 'TUR', 'TRY', '', 'Turkish Lira', '1.795137', 'Active', 'No', 'No'),
(181, 'Trinidad and Tobago', 'TTO', 'TTD', '', 'Trinidad and Tobago Dollar', '6.376663', 'Active', 'No', 'No'),
(182, 'Taiwan', 'TWN', 'TWD', 'NT$', 'New Taiwan Dollar', '29.878845', 'Active', 'No', 'Yes'),
(183, 'Tanzania', 'TZA', 'TZS', '', 'Tanzanian Shilling', '1567.94676', 'Active', 'No', 'No'),
(184, 'Ukraine', 'UKR', 'UAH', '', 'Ukrainian Hryvnia', '8.074449', 'Active', 'No', 'No'),
(185, 'Uganda', 'UGA', 'UGX', '', 'Ugandan Shilling', '2499.07562', 'Active', 'No', 'No'),
(186, 'American Samoa', 'ASM', 'USD', '', 'United States Dollar', '1', 'Active', 'No', 'No'),
(187, 'British Indian Ocean Territory', 'IOT', 'USD', '', 'United States Dollar', '1', 'Active', 'No', 'No'),
(188, 'Guam (USA)', 'GUM', 'USD', '', 'United States Dollar', '1', 'Active', 'No', 'No'),
(189, 'Marshall Islands', 'MHL', 'USD', '', 'United States Dollar', '1', 'Active', 'No', 'No'),
(190, 'Micronesia', 'FSM', 'USD', '', 'United States Dollar', '1', 'Active', 'No', 'No'),
(191, 'Northern Mariana Islands', 'MNP', 'USD', '', 'United States Dollar', '1', 'Active', 'No', 'No'),
(192, 'Palau', 'PLW', 'USD', '', 'United States Dollar', '1', 'Active', 'No', 'No'),
(193, 'Puerto Rico', 'PRI', 'USD', '', 'United States Dollar', '1', 'Active', 'No', 'No'),
(194, 'Turks and Caicos Islands', 'TCA', 'USD', '', 'United States Dollar', '1', 'Active', 'No', 'No'),
(195, 'United States', 'USA', 'USD', '$', 'United States Dollar', '1', 'Active', 'No', 'Yes'),
(196, 'USA Minor Outlying Islands', 'UMI', 'USD', '', 'United States Dollar', '1', 'Active', 'No', 'No'),
(197, 'Virgin Islands (British)', 'VGB', 'USD', '', 'United States Dollar', '1', 'Active', 'No', 'No'),
(198, 'Virgin Islands (USA)', 'VIR', 'USD', '', 'United States Dollar', '1', 'Active', 'No', 'No'),
(199, 'Uruguay', 'URY', 'UYU', '', 'Uruguayan Peso', '21.227465', 'Active', 'No', 'No'),
(200, 'Uzbekistan', 'UZB', 'UZS', '', 'Uzbekistan Som', '1909.388954', 'Active', 'No', 'No'),
(201, 'Venezuela', 'VEN', 'VEF', '', 'Venezuelan Bolívar', '4.294323', 'Active', 'No', 'No'),
(202, 'Vietnam', 'VNM', 'VND', '', 'Vietnamese Dong', '20808.845825', 'Active', 'No', 'No'),
(203, 'Vanuatu', 'VUT', 'VUV', '', 'Vanuatu Vatu', '91.25', 'Active', 'No', 'No'),
(204, 'Samoa', 'WSM', 'WST', '', 'Samoan Tala', '2.309777', 'Active', 'No', 'No'),
(205, 'Cameroon', 'CMR', 'XAF', '', 'CFA Franc BEAC', '522.932787', 'Active', 'No', 'No'),
(206, 'Central African Republic', 'CAF', 'XAF', '', 'CFA Franc BEAC', '522.932787', 'Active', 'No', 'No'),
(207, 'Chad', 'TCD', 'XAF', '', 'CFA Franc BEAC', '522.932787', 'Active', 'No', 'No'),
(208, 'Congo', 'COG', 'XAF', '', 'CFA Franc BEAC', '522.932787', 'Active', 'No', 'No'),
(209, 'Equatorial Guinea', 'GNQ', 'XAF', '', 'CFA Franc BEAC', '522.932787', 'Active', 'No', 'No'),
(210, 'Gabon', 'GAB', 'XAF', '', 'CFA Franc BEAC', '522.932787', 'Active', 'No', 'No'),
(211, 'Anguilla', 'AIA', 'XCD', '', 'East Caribbean Dollar', '2.70175', 'Active', 'No', 'No'),
(212, 'Antarctica', 'ATA', 'XCD', '', 'East Caribbean Dollar', '2.70175', 'Active', 'No', 'No'),
(213, 'Antigua and Barbuda', 'ATG', 'XCD', '', 'East Caribbean Dollar', '2.70175', 'Active', 'No', 'No'),
(214, 'Dominica', 'DMA', 'XCD', '', 'East Caribbean Dollar', '2.70175', 'Active', 'No', 'No'),
(215, 'Grenada', 'GRD', 'XCD', '', 'East Caribbean Dollar', '2.70175', 'Active', 'No', 'No'),
(216, 'Montserrat', 'MSR', 'XCD', '', 'East Caribbean Dollar', '2.70175', 'Active', 'No', 'No'),
(217, 'Saint Kitts & Nevis Anguilla', 'KNA', 'XCD', '', 'East Caribbean Dollar', '2.70175', 'Active', 'No', 'No'),
(218, 'Saint Lucia', 'LCA', 'XCD', '', 'East Caribbean Dollar', '2.70175', 'Active', 'No', 'No'),
(219, 'Saint Vincent & Grenadines', 'VCT', 'XCD', '', 'East Caribbean Dollar', '2.70175', 'Active', 'No', 'No'),
(220, 'International Monetary Fund (IMF)', '', 'XDR', '', 'Special Drawing Rights', '0.660259', 'Active', 'No', 'No'),
(221, 'Benin', 'BEN', 'XOF', '', 'CFA Franc BCEAO', '524.663337', 'Active', 'No', 'No'),
(222, 'Burkina Faso', 'BFA', 'XOF', '', 'CFA Franc BCEAO', '524.663337', 'Active', 'No', 'No'),
(223, 'Ivory Coast', 'CIV', 'XOF', '', 'CFA Franc BCEAO', '524.663337', 'Active', 'No', 'No'),
(224, 'Mali', 'MLI', 'XOF', '', 'CFA Franc BCEAO', '524.663337', 'Active', 'No', 'No'),
(225, 'Niger', 'NER', 'XOF', '', 'CFA Franc BCEAO', '524.663337', 'Active', 'No', 'No'),
(226, 'Senegal', 'SEN', 'XOF', '', 'CFA Franc BCEAO', '524.663337', 'Active', 'No', 'No'),
(227, 'Togo', 'TGO', 'XOF', '', 'CFA Franc BCEAO', '524.663337', 'Active', 'No', 'No'),
(228, 'New Caledonia (French)', 'NCL', 'XPF', '', 'CFP Franc', '95.342501', 'Active', 'No', 'No'),
(229, 'Polynesia (French)', 'PYF', 'XPF', '', 'CFP Franc', '95.342501', 'Active', 'No', 'No'),
(230, 'Wallis and Futuna Islands', 'WLF', 'XPF', '', 'CFP Franc', '95.342501', 'Active', 'No', 'No'),
(231, 'Yemen', 'YEM', 'YER', '', 'Yemeni Rial', '215.040559', 'Active', 'No', 'No'),
(232, 'South Africa', 'ZAF', 'ZAR', 'R', 'South African Rand', '8.273592', 'Active', 'No', 'Yes'),
(233, 'Zambia', 'ZMB', 'ZMK', '', 'Zambian Kwacha', '4856.902232', 'Active', 'No', 'No'),
(234, 'Zimbabwe', 'ZWE', 'ZWL', '', 'Zimbabwean Dollar', '322.355011', 'Active', 'No', 'No');

ALTER TABLE `product` ADD `product_price_usd` decimal(20,2) NOT NULL AFTER `product_price` ;
ALTER TABLE `product` ADD `product_discount_price_usd` decimal(20,2) NOT NULL AFTER `product_discount_price` ;

DROP TABLE IF EXISTS `users_shop_details`;
CREATE TABLE `users_shop_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `is_shop_owner` enum('Yes','No') NOT NULL DEFAULT 'No',
  `shop_status` tinyint(1) NOT NULL DEFAULT '1',
  `total_products` bigint(20) NOT NULL,
  `paypal_id` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Date added: 30/04/2014

DROP TABLE IF EXISTS `message`;
CREATE TABLE `message` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `date_added` datetime NOT NULL,
  `from_user_id` bigint(20) NOT NULL,
  `to_user_id` bigint(20) NOT NULL,
  `last_replied_by` bigint(20) NOT NULL,
  `last_replied_date` datetime NOT NULL,
  `subject` varchar(200) NOT NULL,
  `reply_count` int(16) NOT NULL,
  `message_text` text NOT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `is_replied` tinyint(1) NOT NULL DEFAULT '0',
  `rel_type` varchar(100) NOT NULL,
  `rel_id` int(16) NOT NULL,
  `rel_table` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;