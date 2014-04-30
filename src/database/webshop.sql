-- phpMyAdmin SQL Dump
-- version 4.1.6
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 10, 2014 at 12:20 PM
-- Server version: 5.5.36
-- PHP Version: 5.4.25

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

DROP TABLE IF EXISTS `product`;
CREATE TABLE `product` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `product_code` varchar(20) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_description` text NOT NULL,
  `product_support_content` TEXT NOT NULL,
  `meta_title` varchar(255) NOT NULL,
  `meta_keyword` varchar(255) NOT NULL,
  `meta_description` varchar(255) NOT NULL,
  `product_highlight_text` varchar(500) NOT NULL,
  `product_slogan` varchar(255) NOT NULL,
  `product_price` decimal(15,2) NOT NULL,
  `product_price_usd` decimal(20,2) NOT NULL,
  `product_price_currency` varchar(10) NOT NULL,
  `product_user_id` bigint(20) NOT NULL COMMENT 'user id',
  `product_sold` bigint(20) NOT NULL,
  `product_added_date` date NOT NULL,
  `url_slug` varchar(150) NOT NULL,
  `demo_url` varchar(255) NOT NULL,
  `demo_details` TEXT NOT NULL,
  `product_category_id` bigint(20) NOT NULL,
  `product_tags` varchar(200) NOT NULL,
  `total_views` bigint(20) NOT NULL,
  `is_featured_product` enum('Yes','No') NOT NULL DEFAULT 'No',
  `is_user_featured_product` enum('Yes','No') NOT NULL DEFAULT 'No',
  `date_activated` datetime NOT NULL,
  `product_discount_price` decimal(15,2) NOT NULL DEFAULT '0.00',
  `product_discount_price_usd` decimal(20,2) NOT NULL,
  `product_discount_fromdate` date NOT NULL,
  `product_discount_todate` date NOT NULL,
  `product_preview_type` enum('image','audio','video') NOT NULL DEFAULT 'image',
  `is_free_product` enum('Yes','No') NOT NULL DEFAULT 'No' COMMENT 'is free product or not.',
  `last_updated_date` datetime NOT NULL,
  `total_downloads` bigint(20) NOT NULL DEFAULT '0',
  `product_moreinfo_url` varchar(255) NOT NULL,
  `global_transaction_fee_used` enum('Yes','No') NOT NULL DEFAULT 'No',
  `site_transaction_fee_type` enum('Flat','Percentage','Mix') NOT NULL DEFAULT 'Flat',
  `site_transaction_fee` double(10,2) NOT NULL,
  `site_transaction_fee_percent` double(10,2) NOT NULL,
  `is_downloadable_product` enum('No','Yes') NOT NULL DEFAULT 'Yes',
  `user_section_id` bigint(20) NOT NULL,
  `delivery_days` BIGINT( 20 ) NOT NULL DEFAULT '0' COMMENT 'Delivery time in days.',
  `date_expires` datetime NOT NULL COMMENT 'Product expiry date.',
  `default_orig_img_width` int(11) NOT NULL COMMENT 'Original default image width',
  `default_orig_img_height` int(11) NOT NULL COMMENT 'Original default image height',
  `product_status` enum('Draft','Ok','Deleted','ToActivate','NotApproved') NOT NULL DEFAULT 'ToActivate' COMMENT 'approval status',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `product_category`
--

DROP TABLE IF EXISTS `product_category`;
CREATE TABLE `product_category` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `seo_category_name` varchar(255) NOT NULL,
  `category_name` varchar(250) NOT NULL,
  `category_description` varchar(250) NOT NULL,
  `category_meta_title` text NOT NULL,
  `category_meta_description` text,
  `category_meta_keyword` text NOT NULL,
  `category_level` tinyint(4) NOT NULL COMMENT 'To store the level, 1 stands for the top level category ',
  `parent_category_id` bigint(20) NOT NULL COMMENT 'category_id of the category which is the parent for this category, 0 for level 1 category',
  `category_left` bigint(20) NOT NULL COMMENT 'cgories with lft and rgt values in between this ones are descendants',
  `category_right` bigint(20) NOT NULL COMMENT 'cgories with lft and rgt values in between this ones are descendants',
  `date_added` date NOT NULL,
  `display_order` bigint(20) NOT NULL COMMENT 'Order in which the categories must be displayed in the front end in the listing',
  `available_sort_options` varchar(250) NOT NULL DEFAULT 'all' COMMENT 'Available sorting options saved as a string from array separated by '','' ',
  `image_name` varchar(200) NOT NULL,
  `image_ext` varchar(10) NOT NULL,
  `image_width` int(11) NOT NULL,
  `image_height` int(11) NOT NULL,
  `is_featured_category` enum('Yes','No') NOT NULL DEFAULT 'No',
  `use_parent_meta_detail` enum('Yes','No') NOT NULL DEFAULT 'No' COMMENT 'Use parent category meta details or not',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`),
  UNIQUE KEY `SEO_PARENT` (`seo_category_name`,`parent_category_id`),
  KEY `parent_category_index` (`parent_category_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `shop_details`
--

DROP TABLE IF EXISTS `shop_details`;
CREATE TABLE `shop_details` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `shop_name` varchar(150) NOT NULL,
  `url_slug` varchar(150) NOT NULL,
  `shop_slogan` varchar(200) DEFAULT NULL,
  `shop_desc` text,
  `shop_address1` varchar(150) DEFAULT NULL,
  `shop_address2` varchar(150) DEFAULT NULL,
  `shop_city` varchar(50) DEFAULT NULL,
  `shop_state` varchar(50) DEFAULT NULL,
  `shop_zipcode` varchar(15) DEFAULT NULL,
  `shop_country` varchar(10) DEFAULT NULL,
  `shop_message` text NOT NULL,
  `shop_contactinfo` text NOT NULL,
  `image_name` VARCHAR( 100 ) NOT NULL,
  `image_ext` VARCHAR( 10 ) NOT NULL,
  `image_server_url` VARCHAR( 255 ) NOT NULL,
  `t_height` VARCHAR( 15 ) NOT NULL,
  `t_width` VARCHAR( 15 ) NOT NULL,
  `is_featured_shop` ENUM('Yes','No') DEFAULT 'No',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_product_section`
--

DROP TABLE IF EXISTS `user_product_section`;
CREATE TABLE `user_product_section` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `section_name` varchar(150) NOT NULL,
  `date_added` datetime NOT NULL,
  `status` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `product_category_attributes`
--

DROP TABLE IF EXISTS `product_category_attributes`;
CREATE TABLE `product_category_attributes` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `attribute_id` bigint(20) NOT NULL,
  `category_id` bigint(20) NOT NULL,
  `date_added` date NOT NULL,
  `display_order` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `product_image`
--

DROP TABLE IF EXISTS `product_image`;
CREATE TABLE `product_image` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) NOT NULL,
  `thumbnail_title` varchar(250) NOT NULL,
  `thumbnail_img` varchar(150) NOT NULL,
  `thumbnail_ext` varchar(4) NOT NULL,
  `thumbnail_width` int(11) NOT NULL,
  `thumbnail_height` int(11) NOT NULL,
  `default_title` varchar(250) NOT NULL,
  `default_img` varchar(150) NOT NULL,
  `default_ext` varchar(4) NOT NULL,
  `default_width` int(11) NOT NULL,
  `default_height` int(11) NOT NULL,
  `default_orig_img_width` int(11) NOT NULL,
  `default_orig_img_height` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `product_attributes_values`
--

DROP TABLE IF EXISTS `product_attributes_values`;
CREATE TABLE `product_attributes_values` (
  `product_id` bigint(20) NOT NULL,
  `attribute_id` bigint(20) NOT NULL,
  `attribute_value` text NOT NULL,
  PRIMARY KEY (`product_id`,`attribute_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `product_resource`
--

DROP TABLE IF EXISTS `product_resource`;
CREATE TABLE `product_resource` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) NOT NULL,
  `resource_type` enum('Archive','Audio','Video','Image','Other') NOT NULL DEFAULT 'Other',
  `is_downloadable` enum('Yes','No') NOT NULL DEFAULT 'No',
  `filename` varchar(150) NOT NULL,
  `ext` varchar(10) NOT NULL,
  `title` varchar(150) NOT NULL,
  `default_flag` enum('Yes','No') NOT NULL DEFAULT 'No',
  `server_url` varchar(255) NOT NULL,
  `display_order` int(11) NOT NULL,
  `width` int(11) NOT NULL,
  `height` int(11) NOT NULL,
  `l_width` int(11) NOT NULL,
  `l_height` int(11) NOT NULL,
  `t_width` int(11) NOT NULL,
  `t_height` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


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

-- --------------------------------------------------------

--
-- Table structure for table `users_shop_details`
--

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

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

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