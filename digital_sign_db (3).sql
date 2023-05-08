-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 05, 2023 at 06:54 PM
-- Server version: 8.0.27
-- PHP Version: 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `digital_sign_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `e_sign_documents`
--

DROP TABLE IF EXISTS `e_sign_documents`;
CREATE TABLE IF NOT EXISTS `e_sign_documents` (
  `id` bigint UNSIGNED NOT NULL COMMENT 'Primary key',
  `uploadId` bigint NOT NULL COMMENT 'id of  e_sign_uploaded_files table',
  `documentId` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'unique alphanumeri long string id',
  `documentPath` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'Source path of document',
  `documentTitle` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'Document title',
  `senderId` bigint NOT NULL COMMENT 'owner of the document',
  `recieverId` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'Comma seprated userids, 0 in case of external user',
  `no_of_parties` int NOT NULL,
  `party_assets` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'Email, Name, Access code, data-feilds as Json.\r\n{"email":"dinesh@example.com","name":"dinesh kumar", "accesscode":"134545", "datafeilds":{"elmType":"....", "style":"....". ...., ...., ....}}',
  `accessCodeMedia` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT 'SMS or Email',
  `document_sent_status` tinyint NOT NULL DEFAULT '0',
  `email_order_sequence` text COLLATE utf8_unicode_ci NOT NULL,
  `document_status` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'Json in partywise and possible value is sent, viewed, signed',
  `isComplete` tinyint NOT NULL DEFAULT '0' COMMENT '1 if all the parties have signed the document',
  `complete_documents` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'as a json {DocId, DocPath, signed by UserEmail}',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `e_sign_documents`
--

INSERT INTO `e_sign_documents` (`id`, `uploadId`, `documentId`, `documentPath`, `documentTitle`, `senderId`, `recieverId`, `no_of_parties`, `party_assets`, `accessCodeMedia`, `document_sent_status`, `email_order_sequence`, `document_status`, `isComplete`, `complete_documents`, `created_at`, `updated_at`) VALUES
(1682422644632871, 1682422536360155, 'f95e8fd5c930c5380219d50310caeea1', '/userassets/mydocuments/1673874254153097/f95e8fd5c930c5380219d50310caeea1.pdf', 'Track Consignment EP700534772IN.pdf', 1673874254153097, '0', 1, '[{\"dataFeilds\":[{\"elmType\":\"signature\",\"page\":\"1\",\"pageTop\":\"315\",\"style\":\"height: 30px; width: 90px; left: 744px; top: 315px; z-index: 106; border-width: 1px 1px 1px 4px; border-style: solid; border-color: rgb(192, 192, 192); border-image: initial; background-color: rgba(192, 192, 192, 0.5);\",\"font_size\":\"13px\",\"font_family\":\"CourierPrime-Regular\",\"font_style\":\"normal\",\"font_weight\":\"normal\",\"text_decoration\":\"none\",\"default_value\":\"Signature of Rashika Sapru\",\"default_user\":\"Rashika Sapru#DK#upkit.rashikasapru@gmail.com#DK#Rashika Sapru#DK##C0C0C0\",\"is_required\":\"1\",\"is_readonly\":\"0\",\"placeholder_hint\":\"Sign Here\"},{\"elmType\":\"signature\",\"page\":\"1\",\"pageTop\":\"1040\",\"style\":\"height: 30px; width: 90px; left: 69px; top: 1040px; z-index: 104; border-width: 1px 1px 1px 4px; border-style: solid; border-color: rgb(192, 192, 192); border-image: initial; background-color: rgba(192, 192, 192, 0.5);\",\"font_size\":\"13px\",\"font_family\":\"CourierPrime-Regular\",\"font_style\":\"normal\",\"font_weight\":\"normal\",\"text_decoration\":\"none\",\"default_value\":\"Signature of Rashika Sapru\",\"default_user\":\"Rashika Sapru#DK#upkit.rashikasapru@gmail.com#DK#Rashika Sapru#DK##C0C0C0\",\"is_required\":\"1\",\"is_readonly\":\"0\",\"placeholder_hint\":\"Sign Here\"},{\"elmType\":\"signature\",\"page\":\"2\",\"pageTop\":\"735.0999999999999\",\"style\":\"height: 30px; width: 90px; left: 69px; top: 2012px; z-index: 109; border-width: 1px 1px 1px 4px; border-style: solid; border-color: rgb(192, 192, 192); border-image: initial; background-color: rgba(192, 192, 192, 0.5);\",\"font_size\":\"13px\",\"font_family\":\"CourierPrime-Regular\",\"font_style\":\"normal\",\"font_weight\":\"normal\",\"text_decoration\":\"none\",\"default_value\":\"Signature of Rashika Sapru\",\"default_user\":\"Rashika Sapru#DK#upkit.rashikasapru@gmail.com#DK#Rashika Sapru#DK##C0C0C0\",\"is_required\":\"1\",\"is_readonly\":\"0\",\"placeholder_hint\":\"Sign Here\"},{\"elmType\":\"signature\",\"page\":\"2\",\"pageTop\":\"132.0999999999999\",\"style\":\"height: 30px; width: 90px; left: 751px; top: 1409px; z-index: 106; border-width: 1px 1px 1px 4px; border-style: solid; border-color: rgb(192, 192, 192); border-image: initial; background-color: rgba(192, 192, 192, 0.5);\",\"font_size\":\"13px\",\"font_family\":\"CourierPrime-Regular\",\"font_style\":\"normal\",\"font_weight\":\"normal\",\"text_decoration\":\"none\",\"default_value\":\"Signature of Rashika Sapru\",\"default_user\":\"Rashika Sapru#DK#upkit.rashikasapru@gmail.com#DK#Rashika Sapru#DK##C0C0C0\",\"is_required\":\"1\",\"is_readonly\":\"0\",\"placeholder_hint\":\"Sign Here\"}],\"email\":\"upkit.rashikasapru@gmail.com\",\"name\":\"Rashika Sapru\",\"authType\":0,\"accesscode\":\"\",\"otp\":\"\"}]', 'email', 1, '', '[{\"email\":\"upkit.rashikasapru@gmail.com\",\"status\":\"signed\"}]', 1, '', '2023-04-25 17:07:24', '2023-04-25 17:07:24'),
(1682513423961134, 1682513163991942, '085dc7ff38220e7bcbc07cf6999cb729', '/userassets/mydocuments/1673874254153097/085dc7ff38220e7bcbc07cf6999cb729.pdf', 'gorise paypal last transaction1.pdf', 1673874254153097, '0,1673874254153097,0', 3, '[{\"dataFeilds\":[{\"elmType\":\"signature\",\"page\":\"1\",\"pageTop\":\"400\",\"style\":\"height: 30px; width: 90px; left: 448px; top: 400px; z-index: 106; border-width: 1px 1px 1px 4px; border-style: solid; border-color: rgb(192, 192, 192); border-image: initial; background-color: rgba(192, 192, 192, 0.5);\",\"font_size\":\"13px\",\"font_family\":\"CourierPrime-Regular\",\"font_style\":\"normal\",\"font_weight\":\"normal\",\"text_decoration\":\"none\",\"default_value\":\"Signature of Rashika Sapru\",\"default_user\":\"Rashika Sapru#DK#upkit.rashikasapru@gmail.com#DK#Rashika Sapru#DK##C0C0C0\",\"is_required\":\"1\",\"is_readonly\":\"0\",\"placeholder_hint\":\"Sign Here\"},{\"elmType\":\"name\",\"page\":\"1\",\"pageTop\":\"401\",\"style\":\"height: 31px; width: 130px; left: 253px; top: 401px; z-index: 107; border-width: 1px 1px 1px 4px; border-style: solid; border-color: rgb(192, 192, 192); border-image: initial; background-color: rgba(192, 192, 192, 0.5);\",\"font_size\":\"13px\",\"font_family\":\"CourierPrime-Regular\",\"font_style\":\"normal\",\"font_weight\":\"normal\",\"text_decoration\":\"none\",\"default_value\":\"Text\",\"default_user\":\"Rashika Sapru#DK#upkit.rashikasapru@gmail.com#DK#Rashika Sapru#DK##C0C0C0\",\"is_required\":\"1\",\"is_readonly\":\"0\",\"placeholder_hint\":\"Name\",\"data_textcolor_rgb\":\"0,0,0\",\"data_textcolor_hex\":\"#000\"},{\"elmType\":\"datepicker\",\"page\":\"1\",\"pageTop\":\"402\",\"style\":\"height: 30px; width: 90px; left: 713px; top: 402px; z-index: 104; border-width: 1px 1px 1px 4px; border-style: solid; border-color: rgb(192, 192, 192); border-image: initial; background-color: rgba(192, 192, 192, 0.5);\",\"font_size\":\"13px\",\"font_family\":\"CourierPrime-Regular\",\"font_style\":\"normal\",\"font_weight\":\"normal\",\"text_decoration\":\"none\",\"default_value\":\"26\\/04\\/2023\",\"default_user\":\"Rashika Sapru#DK#upkit.rashikasapru@gmail.com#DK#Rashika Sapru#DK##C0C0C0\",\"is_required\":\"1\",\"is_readonly\":\"0\",\"placeholder_hint\":\"DD\\/MM\\/YYYY\",\"data_textcolor_rgb\":\"0,0,0\",\"data_textcolor_hex\":\"#000\"}],\"email\":\"upkit.rashikasapru@gmail.com\",\"name\":\"Rashika Sapru\",\"authType\":2,\"accesscode\":\"123456\",\"otp\":\"\"},{\"dataFeilds\":[{\"elmType\":\"signature\",\"page\":\"1\",\"pageTop\":\"605\",\"style\":\"height: 30px; width: 90px; left: 452px; top: 605px; z-index: 108; border-width: 1px 1px 1px 4px; border-style: solid; border-color: rgb(201, 192, 187); border-image: initial; background-color: rgba(201, 192, 187, 0.5);\",\"font_size\":\"13px\",\"font_family\":\"CourierPrime-Regular\",\"font_style\":\"normal\",\"font_weight\":\"normal\",\"text_decoration\":\"none\",\"default_value\":\"Signature of Rashika Sapru\",\"default_user\":\"Dinesh Kumar#DK#upkit.dineshgiri@gmail.com#DK#Me#DK##C9C0BB\",\"is_required\":\"1\",\"is_readonly\":\"0\",\"placeholder_hint\":\"Sign Here\"},{\"elmType\":\"name\",\"page\":\"1\",\"pageTop\":\"605\",\"style\":\"height: 31px; width: 126px; left: 199px; top: 605px; z-index: 111; border-width: 1px 1px 1px 4px; border-style: solid; border-color: rgb(201, 192, 187); border-image: initial; background-color: rgba(201, 192, 187, 0.5);\",\"font_size\":\"13px\",\"font_family\":\"CourierPrime-Regular\",\"font_style\":\"normal\",\"font_weight\":\"normal\",\"text_decoration\":\"none\",\"default_value\":\"Text\",\"default_user\":\"Dinesh Kumar#DK#upkit.dineshgiri@gmail.com#DK#Me#DK##C9C0BB\",\"is_required\":\"1\",\"is_readonly\":\"0\",\"placeholder_hint\":\"Name\",\"data_textcolor_rgb\":\"0,0,0\",\"data_textcolor_hex\":\"#000\"},{\"elmType\":\"datepicker\",\"page\":\"1\",\"pageTop\":\"603\",\"style\":\"height: 30px; width: 90px; left: 746px; top: 603px; z-index: 104; border-width: 1px 1px 1px 4px; border-style: solid; border-color: rgb(201, 192, 187); border-image: initial; background-color: rgba(201, 192, 187, 0.5);\",\"font_size\":\"13px\",\"font_family\":\"CourierPrime-Regular\",\"font_style\":\"normal\",\"font_weight\":\"normal\",\"text_decoration\":\"none\",\"default_value\":\"26\\/04\\/2023\",\"default_user\":\"Dinesh Kumar#DK#upkit.dineshgiri@gmail.com#DK#Me#DK##C9C0BB\",\"is_required\":\"1\",\"is_readonly\":\"0\",\"placeholder_hint\":\"DD\\/MM\\/YYYY\",\"data_textcolor_rgb\":\"0,0,0\",\"data_textcolor_hex\":\"#000\"}],\"email\":\"upkit.dineshgiri@gmail.com\",\"name\":\"Dinesh Kumar\",\"authType\":1,\"accesscode\":\"\",\"otp\":275477},{\"dataFeilds\":[{\"elmType\":\"signature\",\"page\":\"1\",\"pageTop\":\"960\",\"style\":\"height: 30px; width: 90px; left: 461px; top: 960px; z-index: 106; border-width: 1px 1px 1px 4px; border-style: solid; border-color: rgb(229, 228, 226); border-image: initial; background-color: rgba(229, 228, 226, 0.5);\",\"font_size\":\"13px\",\"font_family\":\"CourierPrime-Regular\",\"font_style\":\"normal\",\"font_weight\":\"normal\",\"text_decoration\":\"none\",\"default_value\":\"Signature of Rashika Sapru\",\"default_user\":\"Kishan Rathor#DK#dinesh.g@kitlabs.in#DK#Kishan Rathor#DK##E5E4E2\",\"is_required\":\"1\",\"is_readonly\":\"0\",\"placeholder_hint\":\"Sign Here\"},{\"elmType\":\"name\",\"page\":\"1\",\"pageTop\":\"966\",\"style\":\"height: 29px; width: 123px; left: 201px; top: 966px; z-index: 111; border-width: 1px 1px 1px 4px; border-style: solid; border-color: rgb(229, 228, 226); border-image: initial; background-color: rgba(229, 228, 226, 0.5);\",\"font_size\":\"13px\",\"font_family\":\"CourierPrime-Regular\",\"font_style\":\"normal\",\"font_weight\":\"normal\",\"text_decoration\":\"none\",\"default_value\":\"Text\",\"default_user\":\"Kishan Rathor#DK#dinesh.g@kitlabs.in#DK#Kishan Rathor#DK##E5E4E2\",\"is_required\":\"1\",\"is_readonly\":\"0\",\"placeholder_hint\":\"Name\",\"data_textcolor_rgb\":\"0,0,0\",\"data_textcolor_hex\":\"#000\"},{\"elmType\":\"datepicker\",\"page\":\"1\",\"pageTop\":\"962\",\"style\":\"height: 30px; width: 90px; left: 745px; top: 962px; z-index: 112; border-width: 1px 1px 1px 4px; border-style: solid; border-color: rgb(229, 228, 226); border-image: initial; background-color: rgba(229, 228, 226, 0.5);\",\"font_size\":\"13px\",\"font_family\":\"CourierPrime-Regular\",\"font_style\":\"normal\",\"font_weight\":\"normal\",\"text_decoration\":\"none\",\"default_value\":\"26\\/04\\/2023\",\"default_user\":\"Kishan Rathor#DK#dinesh.g@kitlabs.in#DK#Kishan Rathor#DK##E5E4E2\",\"is_required\":\"1\",\"is_readonly\":\"0\",\"placeholder_hint\":\"DD\\/MM\\/YYYY\",\"data_textcolor_rgb\":\"0,0,0\",\"data_textcolor_hex\":\"#000\"}],\"email\":\"dinesh.g@kitlabs.in\",\"name\":\"Kishan Rathor\",\"authType\":1,\"accesscode\":\"\",\"otp\":381641}]', 'email', 1, '', '[{\"email\":\"upkit.rashikasapru@gmail.com\",\"status\":\"signed\"},{\"email\":\"upkit.dineshgiri@gmail.com\",\"status\":\"signed\"},{\"email\":\"dinesh.g@kitlabs.in\",\"status\":\"signed\"}]', 1, '', '2023-04-26 18:20:23', '2023-04-26 18:20:23'),
(1683009010876301, 1683008967990077, 'f70facb8dbb88db98288db81bc080144', '/userassets/mydocuments/1673874254153097/f70facb8dbb88db98288db81bc080144.pdf', 'JEcard.pdf', 1673874254153097, '0', 1, '[{\"dataFeilds\":[{\"elmType\":\"signature\",\"page\":\"1\",\"pageTop\":\"614px\",\"style\":\"height: 100px; width: 239px; left: 460px; top: 614px; z-index: 104; border-width: 1px 1px 1px 4px; border-style: solid; border-color: rgb(192, 192, 192); border-image: initial; background-color: rgba(192, 192, 192, 0.5);\",\"font_size\":\"13px\",\"font_family\":\"CourierPrime-Regular\",\"font_style\":\"normal\",\"font_weight\":\"normal\",\"text_decoration\":\"none\",\"default_value\":\"Signature of Rashika Sapru\",\"default_user\":\"Rashika Sapru#DK#upkit.rashikasapru@gmail.com#DK#Rashika Sapru#DK##C0C0C0\",\"is_required\":\"1\",\"is_readonly\":\"0\",\"placeholder_hint\":\"Sign Here\"},{\"elmType\":\"signature\",\"page\":\"1\",\"pageTop\":\"1005px\",\"style\":\"height: 75px; width: 209px; left: 99px; top: 1005px; z-index: 103; border-width: 1px 1px 1px 4px; border-style: solid; border-color: rgb(192, 192, 192); border-image: initial; background-color: rgba(192, 192, 192, 0.5);\",\"font_size\":\"13px\",\"font_family\":\"CourierPrime-Regular\",\"font_style\":\"normal\",\"font_weight\":\"normal\",\"text_decoration\":\"none\",\"default_value\":\"Signature of Rashika Sapru\",\"default_user\":\"Rashika Sapru#DK#upkit.rashikasapru@gmail.com#DK#Rashika Sapru#DK##C0C0C0\",\"is_required\":\"1\",\"is_readonly\":\"0\",\"placeholder_hint\":\"Sign Here\"}],\"email\":\"upkit.rashikasapru@gmail.com\",\"name\":\"Rashika Sapru\",\"authType\":0,\"accesscode\":\"\",\"otp\":\"\"}]', 'email', 1, '', '[{\"email\":\"upkit.rashikasapru@gmail.com\",\"status\":\"signed\"}]', 1, '', '2023-05-02 12:00:10', '2023-05-02 12:00:10'),
(1683010477170652, 1683010438299574, 'dae7916d91e273bf60acfdeb3b093c69', '/userassets/mydocuments/1673874254153097/dae7916d91e273bf60acfdeb3b093c69.pdf', 'ConsolidateCertificateOfComletion.pdf', 1673874254153097, '0', 1, '[{\"dataFeilds\":[{\"elmType\":\"signature\",\"page\":\"1\",\"pageTop\":\"636px\",\"style\":\"height: 30px; width: 90px; left: 481px; top: 636px; z-index: 102; border-width: 1px 1px 1px 4px; border-style: solid; border-color: rgb(192, 192, 192); border-image: initial; background-color: rgba(192, 192, 192, 0.5);\",\"font_size\":\"13px\",\"font_family\":\"CourierPrime-Regular\",\"font_style\":\"normal\",\"font_weight\":\"normal\",\"text_decoration\":\"none\",\"default_value\":\"Signature of Rashika Sapru\",\"default_user\":\"Rashika Sapru#DK#upkit.rashikasapru@gmail.com#DK#Rashika Sapru#DK##C0C0C0\",\"is_required\":\"1\",\"is_readonly\":\"0\",\"placeholder_hint\":\"Sign Here\"},{\"elmType\":\"signature\",\"page\":\"1\",\"pageTop\":\"631px\",\"style\":\"height: 30px; width: 90px; left: 66px; top: 631px; z-index: 102; border-width: 1px 1px 1px 4px; border-style: solid; border-color: rgb(192, 192, 192); border-image: initial; background-color: rgba(192, 192, 192, 0.5);\",\"font_size\":\"13px\",\"font_family\":\"CourierPrime-Regular\",\"font_style\":\"normal\",\"font_weight\":\"normal\",\"text_decoration\":\"none\",\"default_value\":\"Signature of Rashika Sapru\",\"default_user\":\"Rashika Sapru#DK#upkit.rashikasapru@gmail.com#DK#Rashika Sapru#DK##C0C0C0\",\"is_required\":\"1\",\"is_readonly\":\"0\",\"placeholder_hint\":\"Sign Here\"},{\"elmType\":\"signature\",\"page\":\"1\",\"pageTop\":\"266px\",\"style\":\"height: 30px; width: 90px; left: 538px; top: 266px; z-index: 102; border-width: 1px 1px 1px 4px; border-style: solid; border-color: rgb(192, 192, 192); border-image: initial; background-color: rgba(192, 192, 192, 0.5);\",\"font_size\":\"13px\",\"font_family\":\"CourierPrime-Regular\",\"font_style\":\"normal\",\"font_weight\":\"normal\",\"text_decoration\":\"none\",\"default_value\":\"Signature of Rashika Sapru\",\"default_user\":\"Rashika Sapru#DK#upkit.rashikasapru@gmail.com#DK#Rashika Sapru#DK##C0C0C0\",\"is_required\":\"1\",\"is_readonly\":\"0\",\"placeholder_hint\":\"Sign Here\"}],\"email\":\"upkit.rashikasapru@gmail.com\",\"name\":\"Rashika Sapru\",\"authType\":0,\"accesscode\":\"\",\"otp\":\"\"}]', 'email', 1, '', '[{\"email\":\"upkit.rashikasapru@gmail.com\",\"status\":\"signed\"}]', 1, '', '2023-05-02 12:24:37', '2023-05-02 12:24:37');

-- --------------------------------------------------------

--
-- Table structure for table `e_sign_document_signers`
--

DROP TABLE IF EXISTS `e_sign_document_signers`;
CREATE TABLE IF NOT EXISTS `e_sign_document_signers` (
  `id` bigint UNSIGNED NOT NULL,
  `parentDocument` bigint NOT NULL COMMENT 'Id feild of e_sign_documents',
  `documentId` varchar(191) COLLATE utf8_unicode_ci NOT NULL COMMENT 'unique alphanumeri long string id, will use this to create link also',
  `signerEmail` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'signer party email',
  `signerName` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'signer party name',
  `signerId` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'user id , 0 in case of external user',
  `internalUser` tinyint NOT NULL COMMENT '0 - external, 1 - internal',
  `document_status` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT 'possible value is sent, viewed, signed',
  `document_data` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'document json data-feilds',
  `userfilled_documentdata` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'data filled by user while signing the document',
  `authType` int NOT NULL COMMENT 'Document authentication type (0 is no auth required, 1 is otp required, 2 access code required)',
  `otp` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT 'otp to access the document',
  `otpDateTime` datetime NOT NULL COMMENT 'Otp Sent date time',
  `accessCode` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT 'document access code',
  `accessCodeMedia` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT 'SMS or Email',
  `documentExpiry` datetime NOT NULL COMMENT 'valid till date',
  `documentExpired` tinyint NOT NULL COMMENT '0, 1',
  `lastReminder` datetime NOT NULL COMMENT 'last reminder date',
  `reminderSent` int NOT NULL DEFAULT '0' COMMENT 'Reminder email is sent',
  `documentSentDate` datetime NOT NULL COMMENT 'Document sent on',
  `documentViewDate` datetime NOT NULL COMMENT 'date time when document is viewed by signer',
  `documentSignDate` datetime NOT NULL COMMENT 'date time when document is signed by signer',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `e_sign_document_signers`
--

INSERT INTO `e_sign_document_signers` (`id`, `parentDocument`, `documentId`, `signerEmail`, `signerName`, `signerId`, `internalUser`, `document_status`, `document_data`, `userfilled_documentdata`, `authType`, `otp`, `otpDateTime`, `accessCode`, `accessCodeMedia`, `documentExpiry`, `documentExpired`, `lastReminder`, `reminderSent`, `documentSentDate`, `documentViewDate`, `documentSignDate`, `created_at`, `updated_at`) VALUES
(168300901053230, 1683009010876301, 'e6d1e3f45fcf0bd1fb7212ca67d97a03', 'upkit.rashikasapru@gmail.com', 'Rashika Sapru', '0', 0, 'signed', '[{\"elmType\":\"signature\",\"page\":\"1\",\"pageTop\":\"614px\",\"style\":\"height: 100px; width: 239px; left: 460px; top: 614px; z-index: 104; border-width: 1px 1px 1px 4px; border-style: solid; border-color: rgb(192, 192, 192); border-image: initial; background-color: rgba(192, 192, 192, 0.5);\",\"font_size\":\"13px\",\"font_family\":\"CourierPrime-Regular\",\"font_style\":\"normal\",\"font_weight\":\"normal\",\"text_decoration\":\"none\",\"default_value\":\"Signature of Rashika Sapru\",\"default_user\":\"Rashika Sapru#DK#upkit.rashikasapru@gmail.com#DK#Rashika Sapru#DK##C0C0C0\",\"is_required\":\"1\",\"is_readonly\":\"0\",\"placeholder_hint\":\"Sign Here\"},{\"elmType\":\"signature\",\"page\":\"1\",\"pageTop\":\"1005px\",\"style\":\"height: 75px; width: 209px; left: 99px; top: 1005px; z-index: 103; border-width: 1px 1px 1px 4px; border-style: solid; border-color: rgb(192, 192, 192); border-image: initial; background-color: rgba(192, 192, 192, 0.5);\",\"font_size\":\"13px\",\"font_family\":\"CourierPrime-Regular\",\"font_style\":\"normal\",\"font_weight\":\"normal\",\"text_decoration\":\"none\",\"default_value\":\"Signature of Rashika Sapru\",\"default_user\":\"Rashika Sapru#DK#upkit.rashikasapru@gmail.com#DK#Rashika Sapru#DK##C0C0C0\",\"is_required\":\"1\",\"is_readonly\":\"0\",\"placeholder_hint\":\"Sign Here\"}]', '{\"Rashika Sapru#DK#upkit.rashikasapru@gmail.com#DK#Rashika Sapru#DK##C0C0C0\":[{\"elmType\":\"signature\",\"elmId\":\"signature_51s1m5k86hifu6fkbbv4\",\"page\":\"1\",\"pageTop\":\"614px\",\"style\":\"height: 100px; width: 239px; left: 460px; top: 614px; z-index: 104; border-width: 1px 1px 1px 4px; border-style: solid; border-color: rgb(192, 192, 192); border-image: initial; background-color: rgba(192, 192, 192, 0.5);\",\"font_size\":\"13px\",\"font_family\":\"CourierPrime-Regular\",\"font_style\":\"normal\",\"font_weight\":\"normal\",\"text_decoration\":\"none\",\"default_value\":\"Signature of Rashika Sapru\",\"default_user\":\"Rashika Sapru#DK#upkit.rashikasapru@gmail.com#DK#Rashika Sapru#DK##C0C0C0\"},{\"elmType\":\"signature\",\"elmId\":\"signature_gkr5vk7a9fkkZbhxdd48\",\"page\":\"1\",\"pageTop\":\"1005px\",\"style\":\"height: 75px; width: 209px; left: 99px; top: 1005px; z-index: 103; border-width: 1px 1px 1px 4px; border-style: solid; border-color: rgb(192, 192, 192); border-image: initial; background-color: rgba(192, 192, 192, 0.5);\",\"font_size\":\"13px\",\"font_family\":\"CourierPrime-Regular\",\"font_style\":\"normal\",\"font_weight\":\"normal\",\"text_decoration\":\"none\",\"default_value\":\"Signature of Rashika Sapru\",\"default_user\":\"Rashika Sapru#DK#upkit.rashikasapru@gmail.com#DK#Rashika Sapru#DK##C0C0C0\"}]}', 0, '', '0000-00-00 00:00:00', '', 'email', '2023-05-12 23:59:59', 0, '2023-05-02 06:30:10', 0, '2023-05-02 06:30:22', '2023-05-02 06:31:07', '2023-05-02 06:50:51', '2023-05-02 12:00:10', '2023-05-02 12:00:10'),
(1682422644616854, 1682422644632871, 'c84b3c8d1a55dfd4fc89092de4d8d273', 'upkit.rashikasapru@gmail.com', 'Rashika Sapru', '0', 0, 'signed', '[{\"elmType\":\"signature\",\"page\":\"1\",\"pageTop\":\"315\",\"style\":\"height: 30px; width: 90px; left: 744px; top: 315px; z-index: 106; border-width: 1px 1px 1px 4px; border-style: solid; border-color: rgb(192, 192, 192); border-image: initial; background-color: rgba(192, 192, 192, 0.5);\",\"font_size\":\"13px\",\"font_family\":\"CourierPrime-Regular\",\"font_style\":\"normal\",\"font_weight\":\"normal\",\"text_decoration\":\"none\",\"default_value\":\"Signature of Rashika Sapru\",\"default_user\":\"Rashika Sapru#DK#upkit.rashikasapru@gmail.com#DK#Rashika Sapru#DK##C0C0C0\",\"is_required\":\"1\",\"is_readonly\":\"0\",\"placeholder_hint\":\"Sign Here\"},{\"elmType\":\"signature\",\"page\":\"1\",\"pageTop\":\"1040\",\"style\":\"height: 30px; width: 90px; left: 69px; top: 1040px; z-index: 104; border-width: 1px 1px 1px 4px; border-style: solid; border-color: rgb(192, 192, 192); border-image: initial; background-color: rgba(192, 192, 192, 0.5);\",\"font_size\":\"13px\",\"font_family\":\"CourierPrime-Regular\",\"font_style\":\"normal\",\"font_weight\":\"normal\",\"text_decoration\":\"none\",\"default_value\":\"Signature of Rashika Sapru\",\"default_user\":\"Rashika Sapru#DK#upkit.rashikasapru@gmail.com#DK#Rashika Sapru#DK##C0C0C0\",\"is_required\":\"1\",\"is_readonly\":\"0\",\"placeholder_hint\":\"Sign Here\"},{\"elmType\":\"signature\",\"page\":\"2\",\"pageTop\":\"735.0999999999999\",\"style\":\"height: 30px; width: 90px; left: 69px; top: 2012px; z-index: 109; border-width: 1px 1px 1px 4px; border-style: solid; border-color: rgb(192, 192, 192); border-image: initial; background-color: rgba(192, 192, 192, 0.5);\",\"font_size\":\"13px\",\"font_family\":\"CourierPrime-Regular\",\"font_style\":\"normal\",\"font_weight\":\"normal\",\"text_decoration\":\"none\",\"default_value\":\"Signature of Rashika Sapru\",\"default_user\":\"Rashika Sapru#DK#upkit.rashikasapru@gmail.com#DK#Rashika Sapru#DK##C0C0C0\",\"is_required\":\"1\",\"is_readonly\":\"0\",\"placeholder_hint\":\"Sign Here\"},{\"elmType\":\"signature\",\"page\":\"2\",\"pageTop\":\"132.0999999999999\",\"style\":\"height: 30px; width: 90px; left: 751px; top: 1409px; z-index: 106; border-width: 1px 1px 1px 4px; border-style: solid; border-color: rgb(192, 192, 192); border-image: initial; background-color: rgba(192, 192, 192, 0.5);\",\"font_size\":\"13px\",\"font_family\":\"CourierPrime-Regular\",\"font_style\":\"normal\",\"font_weight\":\"normal\",\"text_decoration\":\"none\",\"default_value\":\"Signature of Rashika Sapru\",\"default_user\":\"Rashika Sapru#DK#upkit.rashikasapru@gmail.com#DK#Rashika Sapru#DK##C0C0C0\",\"is_required\":\"1\",\"is_readonly\":\"0\",\"placeholder_hint\":\"Sign Here\"}]', '{\"Rashika Sapru#DK#upkit.rashikasapru@gmail.com#DK#Rashika Sapru#DK##C0C0C0\":[{\"elmType\":\"signature\",\"elmId\":\"signature_k1dcccfchcsfhc15faif\",\"page\":\"1\",\"pageTop\":\"315\",\"style\":\"height: 30px; width: 90px; left: 744px; top: 315px; z-index: 106; border-width: 1px 1px 1px 4px; border-style: solid; border-color: rgb(192, 192, 192); border-image: initial; background-color: rgba(192, 192, 192, 0.5);\",\"font_size\":\"13px\",\"font_family\":\"CourierPrime-Regular\",\"font_style\":\"normal\",\"font_weight\":\"normal\",\"text_decoration\":\"none\",\"default_value\":\"Signature of Rashika Sapru\",\"default_user\":\"Rashika Sapru#DK#upkit.rashikasapru@gmail.com#DK#Rashika Sapru#DK##C0C0C0\"},{\"elmType\":\"signature\",\"elmId\":\"signature_c5tkfdtd51cddhg5k4Zh\",\"page\":\"1\",\"pageTop\":\"1040\",\"style\":\"height: 30px; width: 90px; left: 69px; top: 1040px; z-index: 104; border-width: 1px 1px 1px 4px; border-style: solid; border-color: rgb(192, 192, 192); border-image: initial; background-color: rgba(192, 192, 192, 0.5);\",\"font_size\":\"13px\",\"font_family\":\"CourierPrime-Regular\",\"font_style\":\"normal\",\"font_weight\":\"normal\",\"text_decoration\":\"none\",\"default_value\":\"Signature of Rashika Sapru\",\"default_user\":\"Rashika Sapru#DK#upkit.rashikasapru@gmail.com#DK#Rashika Sapru#DK##C0C0C0\"},{\"elmType\":\"signature\",\"elmId\":\"signature_b2h6hd5m5vb5v4edthcr\",\"page\":\"2\",\"pageTop\":\"735.0999999999999\",\"style\":\"height: 30px; width: 90px; left: 69px; top: 2012px; z-index: 109; border-width: 1px 1px 1px 4px; border-style: solid; border-color: rgb(192, 192, 192); border-image: initial; background-color: rgba(192, 192, 192, 0.5);\",\"font_size\":\"13px\",\"font_family\":\"CourierPrime-Regular\",\"font_style\":\"normal\",\"font_weight\":\"normal\",\"text_decoration\":\"none\",\"default_value\":\"Signature of Rashika Sapru\",\"default_user\":\"Rashika Sapru#DK#upkit.rashikasapru@gmail.com#DK#Rashika Sapru#DK##C0C0C0\"},{\"elmType\":\"signature\",\"elmId\":\"signature_md1b379buZ8mkkhidxs7\",\"page\":\"2\",\"pageTop\":\"132.0999999999999\",\"style\":\"height: 30px; width: 90px; left: 751px; top: 1409px; z-index: 106; border-width: 1px 1px 1px 4px; border-style: solid; border-color: rgb(192, 192, 192); border-image: initial; background-color: rgba(192, 192, 192, 0.5);\",\"font_size\":\"13px\",\"font_family\":\"CourierPrime-Regular\",\"font_style\":\"normal\",\"font_weight\":\"normal\",\"text_decoration\":\"none\",\"default_value\":\"Signature of Rashika Sapru\",\"default_user\":\"Rashika Sapru#DK#upkit.rashikasapru@gmail.com#DK#Rashika Sapru#DK##C0C0C0\"}]}', 0, '', '0000-00-00 00:00:00', '', 'email', '2023-05-05 23:59:59', 0, '2023-04-25 11:37:24', 0, '2023-04-25 11:37:34', '0000-00-00 00:00:00', '2023-04-26 07:21:56', '2023-04-25 17:07:24', '2023-04-25 17:07:24'),
(1682513423336272, 1682513423961134, '1a171bde8a66e9793d8a2489a6619e3d', 'upkit.dineshgiri@gmail.com', 'Dinesh Kumar', '1673874254153097', 1, 'signed', '[{\"elmType\":\"signature\",\"page\":\"1\",\"pageTop\":\"605\",\"style\":\"height: 30px; width: 90px; left: 452px; top: 605px; z-index: 108; border-width: 1px 1px 1px 4px; border-style: solid; border-color: rgb(201, 192, 187); border-image: initial; background-color: rgba(201, 192, 187, 0.5);\",\"font_size\":\"13px\",\"font_family\":\"CourierPrime-Regular\",\"font_style\":\"normal\",\"font_weight\":\"normal\",\"text_decoration\":\"none\",\"default_value\":\"Signature of Rashika Sapru\",\"default_user\":\"Dinesh Kumar#DK#upkit.dineshgiri@gmail.com#DK#Me#DK##C9C0BB\",\"is_required\":\"1\",\"is_readonly\":\"0\",\"placeholder_hint\":\"Sign Here\"},{\"elmType\":\"name\",\"page\":\"1\",\"pageTop\":\"605\",\"style\":\"height: 31px; width: 126px; left: 199px; top: 605px; z-index: 111; border-width: 1px 1px 1px 4px; border-style: solid; border-color: rgb(201, 192, 187); border-image: initial; background-color: rgba(201, 192, 187, 0.5);\",\"font_size\":\"13px\",\"font_family\":\"CourierPrime-Regular\",\"font_style\":\"normal\",\"font_weight\":\"normal\",\"text_decoration\":\"none\",\"default_value\":\"Text\",\"default_user\":\"Dinesh Kumar#DK#upkit.dineshgiri@gmail.com#DK#Me#DK##C9C0BB\",\"is_required\":\"1\",\"is_readonly\":\"0\",\"placeholder_hint\":\"Name\",\"data_textcolor_rgb\":\"0,0,0\",\"data_textcolor_hex\":\"#000\"},{\"elmType\":\"datepicker\",\"page\":\"1\",\"pageTop\":\"603\",\"style\":\"height: 30px; width: 90px; left: 746px; top: 603px; z-index: 104; border-width: 1px 1px 1px 4px; border-style: solid; border-color: rgb(201, 192, 187); border-image: initial; background-color: rgba(201, 192, 187, 0.5);\",\"font_size\":\"13px\",\"font_family\":\"CourierPrime-Regular\",\"font_style\":\"normal\",\"font_weight\":\"normal\",\"text_decoration\":\"none\",\"default_value\":\"26\\/04\\/2023\",\"default_user\":\"Dinesh Kumar#DK#upkit.dineshgiri@gmail.com#DK#Me#DK##C9C0BB\",\"is_required\":\"1\",\"is_readonly\":\"0\",\"placeholder_hint\":\"DD\\/MM\\/YYYY\",\"data_textcolor_rgb\":\"0,0,0\",\"data_textcolor_hex\":\"#000\"}]', '{\"Dinesh Kumar#DK#upkit.dineshgiri@gmail.com#DK#Me#DK##C9C0BB\":[{\"elmType\":\"signature\",\"elmId\":\"signature_40kh7hkc5sv4vtm8xhhd\",\"page\":\"1\",\"pageTop\":\"605\",\"style\":\"height: 30px; width: 90px; left: 452px; top: 605px; z-index: 108; border-width: 1px 1px 1px 4px; border-style: solid; border-color: rgb(201, 192, 187); border-image: initial; background-color: rgba(201, 192, 187, 0.5);\",\"font_size\":\"13px\",\"font_family\":\"CourierPrime-Regular\",\"font_style\":\"normal\",\"font_weight\":\"normal\",\"text_decoration\":\"none\",\"default_value\":\"Signature of Rashika Sapru\",\"default_user\":\"Dinesh Kumar#DK#upkit.dineshgiri@gmail.com#DK#Me#DK##C9C0BB\"},{\"elmType\":\"name\",\"elmId\":\"name_ttfdhr45r8ckdhgkhk5Z\",\"page\":\"1\",\"pageTop\":\"605\",\"style\":\"height: 31px; width: 126px; left: 199px; top: 605px; z-index: 111; border-width: 1px 1px 1px 4px; border-style: solid; border-color: rgb(201, 192, 187); border-image: initial; background-color: rgba(201, 192, 187, 0.5);\",\"font_size\":\"13px\",\"font_family\":\"CourierPrime-Regular\",\"font_style\":\"normal\",\"font_weight\":\"normal\",\"text_decoration\":\"none\",\"default_value\":\"Text\",\"default_user\":\"Dinesh Kumar#DK#upkit.dineshgiri@gmail.com#DK#Me#DK##C9C0BB\"},{\"elmType\":\"datepicker\",\"elmId\":\"datepicker_hfkrg85kggv7ke55k7k7\",\"page\":\"1\",\"pageTop\":\"603\",\"style\":\"height: 30px; width: 90px; left: 746px; top: 603px; z-index: 104; border-width: 1px 1px 1px 4px; border-style: solid; border-color: rgb(201, 192, 187); border-image: initial; background-color: rgba(201, 192, 187, 0.5);\",\"font_size\":\"13px\",\"font_family\":\"CourierPrime-Regular\",\"font_style\":\"normal\",\"font_weight\":\"normal\",\"text_decoration\":\"none\",\"default_value\":\"Apr 27, 2023\",\"default_user\":\"Dinesh Kumar#DK#upkit.dineshgiri@gmail.com#DK#Me#DK##C9C0BB\"}]}', 1, '925353', '2023-04-27 12:20:10', '', 'email', '2023-05-06 23:59:59', 0, '2023-05-05 12:41:37', 1, '2023-04-26 12:50:36', '2023-04-27 12:20:44', '2023-04-27 12:21:05', '2023-04-26 18:20:23', '2023-04-26 18:20:23'),
(1682513423352977, 1682513423961134, '710ad1f1cec0ab91d239b1486c9a5e2b', 'upkit.rashikasapru@gmail.com', 'Rashika Sapru', '0', 0, 'signed', '[{\"elmType\":\"signature\",\"page\":\"1\",\"pageTop\":\"400\",\"style\":\"height: 30px; width: 90px; left: 448px; top: 400px; z-index: 106; border-width: 1px 1px 1px 4px; border-style: solid; border-color: rgb(192, 192, 192); border-image: initial; background-color: rgba(192, 192, 192, 0.5);\",\"font_size\":\"13px\",\"font_family\":\"CourierPrime-Regular\",\"font_style\":\"normal\",\"font_weight\":\"normal\",\"text_decoration\":\"none\",\"default_value\":\"Signature of Rashika Sapru\",\"default_user\":\"Rashika Sapru#DK#upkit.rashikasapru@gmail.com#DK#Rashika Sapru#DK##C0C0C0\",\"is_required\":\"1\",\"is_readonly\":\"0\",\"placeholder_hint\":\"Sign Here\"},{\"elmType\":\"name\",\"page\":\"1\",\"pageTop\":\"401\",\"style\":\"height: 31px; width: 130px; left: 253px; top: 401px; z-index: 107; border-width: 1px 1px 1px 4px; border-style: solid; border-color: rgb(192, 192, 192); border-image: initial; background-color: rgba(192, 192, 192, 0.5);\",\"font_size\":\"13px\",\"font_family\":\"CourierPrime-Regular\",\"font_style\":\"normal\",\"font_weight\":\"normal\",\"text_decoration\":\"none\",\"default_value\":\"Text\",\"default_user\":\"Rashika Sapru#DK#upkit.rashikasapru@gmail.com#DK#Rashika Sapru#DK##C0C0C0\",\"is_required\":\"1\",\"is_readonly\":\"0\",\"placeholder_hint\":\"Name\",\"data_textcolor_rgb\":\"0,0,0\",\"data_textcolor_hex\":\"#000\"},{\"elmType\":\"datepicker\",\"page\":\"1\",\"pageTop\":\"402\",\"style\":\"height: 30px; width: 90px; left: 713px; top: 402px; z-index: 104; border-width: 1px 1px 1px 4px; border-style: solid; border-color: rgb(192, 192, 192); border-image: initial; background-color: rgba(192, 192, 192, 0.5);\",\"font_size\":\"13px\",\"font_family\":\"CourierPrime-Regular\",\"font_style\":\"normal\",\"font_weight\":\"normal\",\"text_decoration\":\"none\",\"default_value\":\"26\\/04\\/2023\",\"default_user\":\"Rashika Sapru#DK#upkit.rashikasapru@gmail.com#DK#Rashika Sapru#DK##C0C0C0\",\"is_required\":\"1\",\"is_readonly\":\"0\",\"placeholder_hint\":\"DD\\/MM\\/YYYY\",\"data_textcolor_rgb\":\"0,0,0\",\"data_textcolor_hex\":\"#000\"}]', '{\"Rashika Sapru#DK#upkit.rashikasapru@gmail.com#DK#Rashika Sapru#DK##C0C0C0\":[{\"elmType\":\"signature\",\"elmId\":\"signature_tZg34fsf7gcih5tddf75\",\"page\":\"1\",\"pageTop\":\"400\",\"style\":\"height: 30px; width: 90px; left: 448px; top: 400px; z-index: 106; border-width: 1px 1px 1px 4px; border-style: solid; border-color: rgb(192, 192, 192); border-image: initial; background-color: rgba(192, 192, 192, 0.5);\",\"font_size\":\"13px\",\"font_family\":\"CourierPrime-Regular\",\"font_style\":\"normal\",\"font_weight\":\"normal\",\"text_decoration\":\"none\",\"default_value\":\"Signature of Rashika Sapru\",\"default_user\":\"Rashika Sapru#DK#upkit.rashikasapru@gmail.com#DK#Rashika Sapru#DK##C0C0C0\"},{\"elmType\":\"name\",\"elmId\":\"name_7s7Zdhdfdh446749cc99\",\"page\":\"1\",\"pageTop\":\"401\",\"style\":\"height: 31px; width: 130px; left: 253px; top: 401px; z-index: 107; border-width: 1px 1px 1px 4px; border-style: solid; border-color: rgb(192, 192, 192); border-image: initial; background-color: rgba(192, 192, 192, 0.5);\",\"font_size\":\"13px\",\"font_family\":\"CourierPrime-Regular\",\"font_style\":\"normal\",\"font_weight\":\"normal\",\"text_decoration\":\"none\",\"default_value\":\"Text\",\"default_user\":\"Rashika Sapru#DK#upkit.rashikasapru@gmail.com#DK#Rashika Sapru#DK##C0C0C0\"},{\"elmType\":\"datepicker\",\"elmId\":\"datepicker_hv8a0kx5fk5bhZ45dZst\",\"page\":\"1\",\"pageTop\":\"402\",\"style\":\"height: 30px; width: 90px; left: 713px; top: 402px; z-index: 104; border-width: 1px 1px 1px 4px; border-style: solid; border-color: rgb(192, 192, 192); border-image: initial; background-color: rgba(192, 192, 192, 0.5);\",\"font_size\":\"13px\",\"font_family\":\"CourierPrime-Regular\",\"font_style\":\"normal\",\"font_weight\":\"normal\",\"text_decoration\":\"none\",\"default_value\":\"27-Apr-2023\",\"default_user\":\"Rashika Sapru#DK#upkit.rashikasapru@gmail.com#DK#Rashika Sapru#DK##C0C0C0\"}]}', 2, '', '0000-00-00 00:00:00', '123456', 'email', '2023-05-06 23:59:59', 0, '2023-05-05 12:41:42', 1, '2023-04-26 12:50:30', '2023-04-27 11:50:24', '2023-04-27 11:53:16', '2023-04-26 18:20:23', '2023-04-26 18:20:23'),
(1682513423782787, 1682513423961134, 'cf1ae4a35d01d158f5dc28320a69c157', 'dinesh.g@kitlabs.in', 'Kishan Rathor', '0', 0, 'signed', '[{\"elmType\":\"signature\",\"page\":\"1\",\"pageTop\":\"960\",\"style\":\"height: 30px; width: 90px; left: 461px; top: 960px; z-index: 106; border-width: 1px 1px 1px 4px; border-style: solid; border-color: rgb(229, 228, 226); border-image: initial; background-color: rgba(229, 228, 226, 0.5);\",\"font_size\":\"13px\",\"font_family\":\"CourierPrime-Regular\",\"font_style\":\"normal\",\"font_weight\":\"normal\",\"text_decoration\":\"none\",\"default_value\":\"Signature of Rashika Sapru\",\"default_user\":\"Kishan Rathor#DK#dinesh.g@kitlabs.in#DK#Kishan Rathor#DK##E5E4E2\",\"is_required\":\"1\",\"is_readonly\":\"0\",\"placeholder_hint\":\"Sign Here\"},{\"elmType\":\"name\",\"page\":\"1\",\"pageTop\":\"966\",\"style\":\"height: 29px; width: 123px; left: 201px; top: 966px; z-index: 111; border-width: 1px 1px 1px 4px; border-style: solid; border-color: rgb(229, 228, 226); border-image: initial; background-color: rgba(229, 228, 226, 0.5);\",\"font_size\":\"13px\",\"font_family\":\"CourierPrime-Regular\",\"font_style\":\"normal\",\"font_weight\":\"normal\",\"text_decoration\":\"none\",\"default_value\":\"Text\",\"default_user\":\"Kishan Rathor#DK#dinesh.g@kitlabs.in#DK#Kishan Rathor#DK##E5E4E2\",\"is_required\":\"1\",\"is_readonly\":\"0\",\"placeholder_hint\":\"Name\",\"data_textcolor_rgb\":\"0,0,0\",\"data_textcolor_hex\":\"#000\"},{\"elmType\":\"datepicker\",\"page\":\"1\",\"pageTop\":\"962\",\"style\":\"height: 30px; width: 90px; left: 745px; top: 962px; z-index: 112; border-width: 1px 1px 1px 4px; border-style: solid; border-color: rgb(229, 228, 226); border-image: initial; background-color: rgba(229, 228, 226, 0.5);\",\"font_size\":\"13px\",\"font_family\":\"CourierPrime-Regular\",\"font_style\":\"normal\",\"font_weight\":\"normal\",\"text_decoration\":\"none\",\"default_value\":\"26\\/04\\/2023\",\"default_user\":\"Kishan Rathor#DK#dinesh.g@kitlabs.in#DK#Kishan Rathor#DK##E5E4E2\",\"is_required\":\"1\",\"is_readonly\":\"0\",\"placeholder_hint\":\"DD\\/MM\\/YYYY\",\"data_textcolor_rgb\":\"0,0,0\",\"data_textcolor_hex\":\"#000\"}]', '{\"Kishan Rathor#DK#dinesh.g@kitlabs.in#DK#Kishan Rathor#DK##E5E4E2\":[{\"elmType\":\"signature\",\"elmId\":\"signature_1v7scbgkcs4vrbdakhts\",\"page\":\"1\",\"pageTop\":\"960\",\"style\":\"height: 30px; width: 90px; left: 461px; top: 960px; z-index: 106; border-width: 1px 1px 1px 4px; border-style: solid; border-color: rgb(229, 228, 226); border-image: initial; background-color: rgba(229, 228, 226, 0.5);\",\"font_size\":\"13px\",\"font_family\":\"CourierPrime-Regular\",\"font_style\":\"normal\",\"font_weight\":\"normal\",\"text_decoration\":\"none\",\"default_value\":\"Signature of Rashika Sapru\",\"default_user\":\"Kishan Rathor#DK#dinesh.g@kitlabs.in#DK#Kishan Rathor#DK##E5E4E2\"},{\"elmType\":\"name\",\"elmId\":\"name_hddt6u0dd341d52m38dg\",\"page\":\"1\",\"pageTop\":\"966\",\"style\":\"height: 29px; width: 123px; left: 201px; top: 966px; z-index: 111; border-width: 1px 1px 1px 4px; border-style: solid; border-color: rgb(229, 228, 226); border-image: initial; background-color: rgba(229, 228, 226, 0.5);\",\"font_size\":\"13px\",\"font_family\":\"CourierPrime-Regular\",\"font_style\":\"normal\",\"font_weight\":\"normal\",\"text_decoration\":\"none\",\"default_value\":\"Text\",\"default_user\":\"Kishan Rathor#DK#dinesh.g@kitlabs.in#DK#Kishan Rathor#DK##E5E4E2\"},{\"elmType\":\"datepicker\",\"elmId\":\"datepicker_h8hffvf19gbskv97f5c1\",\"page\":\"1\",\"pageTop\":\"962\",\"style\":\"height: 30px; width: 90px; left: 745px; top: 962px; z-index: 112; border-width: 1px 1px 1px 4px; border-style: solid; border-color: rgb(229, 228, 226); border-image: initial; background-color: rgba(229, 228, 226, 0.5);\",\"font_size\":\"13px\",\"font_family\":\"CourierPrime-Regular\",\"font_style\":\"normal\",\"font_weight\":\"normal\",\"text_decoration\":\"none\",\"default_value\":\"27 Apr, 2023\",\"default_user\":\"Kishan Rathor#DK#dinesh.g@kitlabs.in#DK#Kishan Rathor#DK##E5E4E2\"}]}', 1, '250569', '2023-04-27 12:21:55', '', 'email', '2023-05-06 23:59:59', 0, '2023-05-05 12:41:47', 1, '2023-04-26 12:50:49', '2023-04-27 12:22:49', '2023-04-27 12:23:14', '2023-04-26 18:20:23', '2023-04-26 18:20:23'),
(1683010477142270, 1683010477170652, 'a240ff73862cfb70b60e418891628b91', 'upkit.rashikasapru@gmail.com', 'Rashika Sapru', '0', 0, 'viewed', '[{\"elmType\":\"signature\",\"page\":\"1\",\"pageTop\":\"636px\",\"style\":\"height: 30px; width: 90px; left: 481px; top: 636px; z-index: 102; border-width: 1px 1px 1px 4px; border-style: solid; border-color: rgb(192, 192, 192); border-image: initial; background-color: rgba(192, 192, 192, 0.5);\",\"font_size\":\"13px\",\"font_family\":\"CourierPrime-Regular\",\"font_style\":\"normal\",\"font_weight\":\"normal\",\"text_decoration\":\"none\",\"default_value\":\"Signature of Rashika Sapru\",\"default_user\":\"Rashika Sapru#DK#upkit.rashikasapru@gmail.com#DK#Rashika Sapru#DK##C0C0C0\",\"is_required\":\"1\",\"is_readonly\":\"0\",\"placeholder_hint\":\"Sign Here\"},{\"elmType\":\"signature\",\"page\":\"1\",\"pageTop\":\"631px\",\"style\":\"height: 30px; width: 90px; left: 66px; top: 631px; z-index: 102; border-width: 1px 1px 1px 4px; border-style: solid; border-color: rgb(192, 192, 192); border-image: initial; background-color: rgba(192, 192, 192, 0.5);\",\"font_size\":\"13px\",\"font_family\":\"CourierPrime-Regular\",\"font_style\":\"normal\",\"font_weight\":\"normal\",\"text_decoration\":\"none\",\"default_value\":\"Signature of Rashika Sapru\",\"default_user\":\"Rashika Sapru#DK#upkit.rashikasapru@gmail.com#DK#Rashika Sapru#DK##C0C0C0\",\"is_required\":\"1\",\"is_readonly\":\"0\",\"placeholder_hint\":\"Sign Here\"},{\"elmType\":\"signature\",\"page\":\"1\",\"pageTop\":\"266px\",\"style\":\"height: 30px; width: 90px; left: 538px; top: 266px; z-index: 102; border-width: 1px 1px 1px 4px; border-style: solid; border-color: rgb(192, 192, 192); border-image: initial; background-color: rgba(192, 192, 192, 0.5);\",\"font_size\":\"13px\",\"font_family\":\"CourierPrime-Regular\",\"font_style\":\"normal\",\"font_weight\":\"normal\",\"text_decoration\":\"none\",\"default_value\":\"Signature of Rashika Sapru\",\"default_user\":\"Rashika Sapru#DK#upkit.rashikasapru@gmail.com#DK#Rashika Sapru#DK##C0C0C0\",\"is_required\":\"1\",\"is_readonly\":\"0\",\"placeholder_hint\":\"Sign Here\"}]', '{\"Rashika Sapru#DK#upkit.rashikasapru@gmail.com#DK#Rashika Sapru#DK##C0C0C0\":[{\"elmType\":\"signature\",\"elmId\":\"signature_5dktZvhgkfdxhhbi15x5\",\"page\":\"1\",\"pageTop\":\"636px\",\"style\":\"height: 30px; width: 90px; left: 481px; top: 636px; z-index: 102; border-width: 1px 1px 1px 4px; border-style: solid; border-color: rgb(192, 192, 192); border-image: initial; background-color: rgba(192, 192, 192, 0.5);\",\"font_size\":\"13px\",\"font_family\":\"CourierPrime-Regular\",\"font_style\":\"normal\",\"font_weight\":\"normal\",\"text_decoration\":\"none\",\"default_value\":\"Signature of Rashika Sapru\",\"default_user\":\"Rashika Sapru#DK#upkit.rashikasapru@gmail.com#DK#Rashika Sapru#DK##C0C0C0\"},{\"elmType\":\"signature\",\"elmId\":\"signature_4df7hd5r5fvghbxdg76c\",\"page\":\"1\",\"pageTop\":\"631px\",\"style\":\"height: 30px; width: 90px; left: 66px; top: 631px; z-index: 102; border-width: 1px 1px 1px 4px; border-style: solid; border-color: rgb(192, 192, 192); border-image: initial; background-color: rgba(192, 192, 192, 0.5);\",\"font_size\":\"13px\",\"font_family\":\"CourierPrime-Regular\",\"font_style\":\"normal\",\"font_weight\":\"normal\",\"text_decoration\":\"none\",\"default_value\":\"Signature of Rashika Sapru\",\"default_user\":\"Rashika Sapru#DK#upkit.rashikasapru@gmail.com#DK#Rashika Sapru#DK##C0C0C0\"},{\"elmType\":\"signature\",\"elmId\":\"signature_decf7ck6bhe4figs0m8k\",\"page\":\"1\",\"pageTop\":\"266px\",\"style\":\"height: 30px; width: 90px; left: 538px; top: 266px; z-index: 102; border-width: 1px 1px 1px 4px; border-style: solid; border-color: rgb(192, 192, 192); border-image: initial; background-color: rgba(192, 192, 192, 0.5);\",\"font_size\":\"13px\",\"font_family\":\"CourierPrime-Regular\",\"font_style\":\"normal\",\"font_weight\":\"normal\",\"text_decoration\":\"none\",\"default_value\":\"Signature of Rashika Sapru\",\"default_user\":\"Rashika Sapru#DK#upkit.rashikasapru@gmail.com#DK#Rashika Sapru#DK##C0C0C0\"}]}', 0, '', '0000-00-00 00:00:00', '', 'email', '2023-05-03 23:59:59', 0, '2023-05-02 06:54:37', 0, '2023-05-02 06:54:43', '2023-05-03 07:41:03', '2023-05-02 06:56:00', '2023-05-02 12:24:37', '2023-05-02 12:24:37');

-- --------------------------------------------------------

--
-- Table structure for table `e_sign_electronic_signatures`
--

DROP TABLE IF EXISTS `e_sign_electronic_signatures`;
CREATE TABLE IF NOT EXISTS `e_sign_electronic_signatures` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `signatureHash` text COLLATE utf8_unicode_ci NOT NULL,
  `signType` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'draw, type, upload',
  `CCHash` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'Hash of completion certificate',
  `documentId` bigint NOT NULL COMMENT 'Id of e_sign_document_signers',
  `parentDocumentId` bigint NOT NULL COMMENT 'Id of e_sign_documents',
  `signerId` bigint NOT NULL COMMENT '0 if external user, 1 if internal user',
  `signerEmail` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Signer party email',
  `senderId` bigint NOT NULL,
  `documentStatus` tinyint NOT NULL DEFAULT '0' COMMENT '0, 1(Completed or signed)',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1683010560909175 DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `e_sign_electronic_signatures`
--

INSERT INTO `e_sign_electronic_signatures` (`id`, `signatureHash`, `signType`, `CCHash`, `documentId`, `parentDocumentId`, `signerId`, `signerEmail`, `senderId`, `documentStatus`, `created_at`, `updated_at`) VALUES
(168249177690864, '30158442460e4d33f4eb6988601c917b64d18f0af7eaad44e94f1b6c4c3e48a4a0abb1ac41f1d265b8172c8cbafde6b675dbb6bd15b24afca60d58ebec9f03be/qjU6p8EID/giUH48/SOtPJEQuf1LaKMh/xsyI5yIZgBBCryU+LNSeesd0OyBohJdeWPEu8Vr/OiW92SzcS9A0UG8kde3rxg+tx4VyeBO5kPu0oYmNnypPVmJSxZXsexBw8Aov/HHwXGNhHGtflHGgcql8R9H6r1XWF0QFNbMP2TaFHH3UAB64SpOaJmyeaa5Zd5uxD+SE0aZrR0C6ySZVR9US82NwKuisVEro2l7U6OaUrlSkpHZXk+YRbAoGw8Sckk14sYuNwsNYcO6Q==', 'type', '', 1682422644616854, 1682422644632871, 0, 'upkit.rashikasapru@gmail.com', 1673874254153097, 1, '2023-04-26 06:49:36', '2023-04-26 06:49:36'),
(168249371650698, 'fbeed16a85dcde35977c5f887c508c3f9d812b2932fd3cdb912907403a4d070fdde76beae82786560b6f2a07f89fa0014776c6372c0a1f7ffa61663b818402edl5xGhRv353kSUYzbqWagYDQRCr/GFYQ0DMLA+JfhFrlfCIPm2vWKMIR8OyYVlyHaurwces75KjzAcalw8/OsECZs5YnSqdFdTYDsf9jP34+pNPYYDm77j9e2BGW9fa/1UIxU+320+A52Uc1d5b6jsIsxOPpvVZCTHKI2Ppvq2QvJaaqsDk+XhaYl8IvCZcykyhwYE7o1+hUGq3Mw0kcuDYnbt5UmbzSpHH99LB0Y3pYNFh8EkYg97xYYLqC8XHr8RXau1gWUU4LKakrdBQ==', 'type', '', 1682422644616854, 1682422644632871, 0, 'upkit.rashikasapru@gmail.com', 1673874254153097, 1, '2023-04-26 07:21:56', '2023-04-26 07:21:56'),
(1682488443854739, 'd1445995d8e5798e9f6edea534020e723b367b4ded39f71f119fd065fec0f871f2a471142779260c0a7cbf899b1ba92b7c128d636477f5a3bb55a547d5f0885bQ2gHSXUYLHrE8cDXjU/56RUKuRoTmnuzHMlYh16anSfZ/2vSd1/IPmwUZsX+IFlN40EG8PrUPT+S8gBkrJJzMoA8hOVvmVrp4mAKPeUN+XXGoA+nZLoek6M+a9404AAymprnTMh6GBa/S75XdwO8zL/nFgrpXeoDkUodvSJyDP24xvbSV/PPfv9TgK7ujHr2yyMWwQG6XgfQVSTgA3X4M/5HfWiGRnvOAKzZC7B9XDoRkJ8ppNPTwXCDNe6yEtnO19hPhw6PLXnmKGeoaw==', 'type', '', 1682422644616854, 1682422644632871, 0, 'upkit.rashikasapru@gmail.com', 1673874254153097, 1, '2023-04-26 05:54:03', '2023-04-26 05:54:03'),
(1682488499651928, '44f9b3bfd1bdf8972f1ae9a8bc3639d198d8287c6bcbfe7fe6debd459059c5d51843e49a516541ca11dcb3e26cf6c1ac5202c80efd3f6845a0470ba22120d8e71XKziAeAScpP4ic9NEP2tT7CNNdFojth5NHCHQJyExF+BNyg4LOqHpHNl6wWkuYL1dsLqwlVZhkK/DSANmhtlGQ6MZnjc2CtxDiWLtwebS3paC+pen8jE+psw88sRHAKUOXj3UtnPW1JFtHlptRJ0eJfP0fPgU6zCTbYSici0kYAX+O0gU54dN8lmN7hrZeOZwclZRdlyB4sg/dV4VTzX/+OlT2WQjfAzBXSqcvJRJnqIMb0eZ5byMn1FPxN4/Acmauq/tSH0jshy+falw==', 'type', '', 1682422644616854, 1682422644632871, 0, 'upkit.rashikasapru@gmail.com', 1673874254153097, 1, '2023-04-26 05:55:00', '2023-04-26 05:55:00'),
(1682488987311934, '226e47081ccc8905988f5ada9216197c816c97b031a9e00922a9f19cba499d977b2c5363ce47db4d62f4beab3816e42abc60559b5fa5ab07d6915d54cd3f2976EhoHageo5lw3/UE7BzZZnxaUW0kw4HcNICbV2fseAYWZ+i7oBK4DCe3MhMSFV9PKvyMUiPSRIC9NWbbFuispky3yU+WAEeCDR2s6bwfTlSzKydbO1Iffr10NJRmJCCDC1hBBgCAiX+vUXxNwWbOW7BEfnYjjzTMMvUdnwaYxCnaBT4IykMuwaXVW/G/JM6yJPzQw3DDhVbfVABeV7ueCh6Ou6h/cGGjm0PVGxM/Ud7xbA368I67qF/AihwttpBT76Nq52BRceUJv9L7clA==', 'type', '', 1682422644616854, 1682422644632871, 0, 'upkit.rashikasapru@gmail.com', 1673874254153097, 1, '2023-04-26 06:03:07', '2023-04-26 06:03:07'),
(1682489078633215, 'e095b511286fe41ada390d4c2de3d783ebade29dee4ef3ad1457e3ff85957c3647fa433171e08edbdb95cbf8e293238d5fb7a78f8cd4ceef613930b104ba00e7vwNRHrORlmqJL4vImDoZQfPw3Lp2HWDyq1qeNV4M08v1GOuPPqyDueNcUolqRoAmH8Q54boXtQjwViGG/4Zo18IFFjKki+RUhgZaMYc+HHD7FB/oHYQavHXfeYiIDzkBiFTPomdlKDO904dzKHGCMiRApS7FxDm3fOBiNAMjdYyUqz4WWHX4MXpGCfNxB2MSEeKCYw274e1zUni+lWCldS1HWyX+p4f+HI0pXa/S7x1JHJdfleln2aEuQ0Coh+2Pe4POH/1RMqEaX4B/jQ==', 'draw', '', 1682422644616854, 1682422644632871, 0, 'upkit.rashikasapru@gmail.com', 1673874254153097, 1, '2023-04-26 06:04:38', '2023-04-26 06:04:38'),
(1682489215806010, '3807d41dcd0a087cd9b3270bf37467d4205fa121df6586f973f8ccfb29c61aee746e3e2995bf29b63b8e98c7885ea94202def111e25efcef8bc236b2dc9166d9PpTDNRGeq0U8CKLZW5RlCyqueuJCzFVOoaPHiFSOct1c1caKKMMk1DNt1jyJwVvj62REFosTOlh6+YH2fy5BGWj1nYRcp383lnc3eYMUG/sdCAc9yH63qv5pKLalvKsCHY49VeGp3PvMmcRshNv4azNrd1+gcQygX3sJclLgth1ozAlG8Q8qCi2JWaM9z65U4FVzfY//oVWtp3rnMcfHGZtomoeo21vt190qQco08D4mihd8K7oaX7hKIq5Fh6Ztm2YB+xheiOe47M2Vtw==', 'draw', '', 1682422644616854, 1682422644632871, 0, 'upkit.rashikasapru@gmail.com', 1673874254153097, 1, '2023-04-26 06:06:55', '2023-04-26 06:06:55'),
(1682489309170098, '2bb87866bd67ea132302afa1435a0a6098b44f8dbea7be8cf90ab4679591a9d5511632993d9cc29761324cac439eb7e9be36790470ffa8fefbc42caa8792c840alB/idRQtJwel6aZljyI/0qVu5vJXfqGFHzlsPqUV4W6+TOrmMImj35R7zsvGLpWoE8LJSjM2mHmw0tl5cSvAPsl9WLMI1cdeNrDP+0lqeAJqqQJ3SMxcNPwcklUbVk4EvdW7cfvK/Yi+COXPxH9zMJ9L/3riOYU43Vy0Sb4g3fePmBWquFXNKq4G9+bi/i5oKcHBZgCr97b6LCsKDn7AerdQoQucfupxyJPcCkKURdvxPfysTR2F3BA9uSIpvXpFKia93+Ca/eEfg9iHA==', 'draw', '', 1682422644616854, 1682422644632871, 0, 'upkit.rashikasapru@gmail.com', 1673874254153097, 1, '2023-04-26 06:08:29', '2023-04-26 06:08:29'),
(1682490701506987, '6fa473f5d2e575e9ee717bed9871c41d82837257ef72bb20f8058bf142f9cda8fb4a6cb6805820012f32f8acf944fdf454263ec7d2b4696189605f4e8096205dTzjxmlp0dVT3xyUUxGwQTk9lShgQ9VCcFdNByN32xozo5+L5NIucWjhWhL0q17c6kGj7Sr/QImgf4/Xbhg4jXAtP4/RMsNa+fdSXnItryHEzb7VJBEuQg+C8Einlmg1fWEPlr7AzHAEeBBExBmCDvb+jmtA1nnMUvCs4Ldb9qmD+ASMdswyrLmqBQBb3FUumO6X5S+VdkorxNu2Q48pxYNzDJElN6SpdLExM7hUu2l3DEYsOnai3HyBPyq14uSZWW1M6ShwsYM0IDaT91A==', 'type', '', 1682422644616854, 1682422644632871, 0, 'upkit.rashikasapru@gmail.com', 1673874254153097, 1, '2023-04-26 06:31:41', '2023-04-26 06:31:41'),
(1682490764957646, '96dd1f03c36f15e0877d516d6eef27ed718a7f1de1b24464e6ae0b261a5b8d6146c2688eb28e97a987840db0b7ee15a1d2fdf8eb16062c6cdb7e2ac71cb74d9bCAHs1EochT/oDe1qV9YSUZ4FXeC33lFoQFkA+b+w7uot51gaS/l7vctJ8dd8xSdbHgJa0Ot1MHouEioRUKgO0WcSKM8foaRduZu1760IMxXbMcQwFnaWxHXbNos7xkEGqVXmVrrU6x24jn2iz/hnqRsObfbwMWuqc6nRJKGgiIrBVGQe53WGbvh3G9edLbTvYIfC96YYGARgDzA3cw/W2uYxs9e6Tic1xbMdgKbGptSFTUQkvu/HJwk1TZHFLwIPeplCGCZstrrwiWItOw==', 'upload', '', 1682422644616854, 1682422644632871, 0, 'upkit.rashikasapru@gmail.com', 1673874254153097, 1, '2023-04-26 06:32:44', '2023-04-26 06:32:44'),
(1682491405575006, '9c18c0f02da4e716b8d122c4bed53505cc260140599ac0d8a788469d551e990a0a5c8a8b79b63d426b340d6ba9087f4fdca8e297dfceec2da6f98caee80ddc496WOp4A8XmLVDTGxPRR4UWpy3oPwayIMk6VkJ/iP9lWfPpS/B59AVWDc5ZUyXwcta+GgVI2ZLeEqF1MDSxVnNVrwdH/Y3IziQxpnguteif1PC4Lj8h8gJ8m7ElsWbAXlr/bbb6PqHaNFIy0fouY9hBKgcCRSjNVGZVFk1W6cFX1EcWV4hwibVIBJ3kSqddXNP+MFzDK98i5jxYb2mmv5mMhxbfa+dbGEYfpv0WENt0uNr9cbCKiKEsDYCmAXo2PlVTN0UlXWhj55rQzrchQ==', 'type', '', 1682422644616854, 1682422644632871, 0, 'upkit.rashikasapru@gmail.com', 1673874254153097, 1, '2023-04-26 06:43:25', '2023-04-26 06:43:25'),
(1682491846620007, '9996c4b44f86c8a703e6d4a7ab7d6fd77d9bc3c1a9c510538b847bf3292557b26dcab03078d3ee972b7b9c4b95bcf4fbc9c8393a45494910900e520afeb9ec9eQmlG0xyg80okRi9j9jxmLt9szCtaNVsmnhDRz7TBl9CJIJIYJpRGFzkkEfgVxonRvmIxw8CX15nG7bnVYpYUKjL8yh3b04MDoE0evv7QXf4IlfrENb1qTt38TNiv0I+wFl5TsFSIFw5bygr+xQTieOEGAorMTnH0i3/NkgxqJKAqhyR1UynuS97mfZoFTwzMfnxCFFVGoDxSr/QUMKxmM43z6cLUc/p3IV6PnqkNBoArA/l4YDaVE78yCAdVyIwQz9Ew1Eg47JScVIRk7Q==', 'type', '', 1682422644616854, 1682422644632871, 0, 'upkit.rashikasapru@gmail.com', 1673874254153097, 1, '2023-04-26 06:50:46', '2023-04-26 06:50:46'),
(1682491986279106, 'bb187f4ef9d42ae113cc3c43dd6597d967d3eeb69272fdd538d4e10c7c89dac51c3c0a7c380f9ec2abd6be0e8a824a94e70213e4994ed6cbcf339e4f64aff5daHTW1rb05bh6WqbVg9YEgnTelYTmyfry8HvGh4ji23KODLiuYI4EGgCb7x6ayEiu3Ig+F+NptJTOTfsK1vIVftAF/zAEtOlhsFwVeYaniq1nZ6/KdSXHul3qmmPlL/iho5BGJEdp05v9WSWRiB1/eMFt53DexGYncz6JP3It3NTKgHAroO9tI0cdsxMxP4LWT5QzM+xgSjaWK9A9AuyLjWKsme8XWllRHbCPtdlo7ZekKZRVUDpQC1Y5ERCiARi9fNqAv6OtclBLOYChjPQ==', 'type', '', 1682422644616854, 1682422644632871, 0, 'upkit.rashikasapru@gmail.com', 1673874254153097, 1, '2023-04-26 06:53:06', '2023-04-26 06:53:06'),
(1682514710744076, '74b5f14d2b09088699c2e14db4dcc9db0391347a735c6b1344a442b5a456e01fd584afa7e9467c0368ef6664cf2d1441abae96cf89a86906c432c840a7c7aa46BJk4tXJe3kP4c+TdWeIsr7+sXAE+PPI8moWHXSq6JCkBZkj6PiS1ohV65WXxzUDeZyqxgKY9zoctRI1DJwvEdcfvhHJFWFF7xT8D817DsZeVFUgCGLacP2AsaW9EgAdz7nnhc68y/uvnmbSXiVHfKK8LNrfA7e2BSuJHmZCXW4m2gTt2uKswyL5ePXu8CbQgZKuz9a4RL8y88nmufiXZvnx6mXMcup+TrRIR5yvrIP6v/Pj/dpl2uQ+qdeyInEJ+5VLZgDSBe7CY0IuE', 'type', '', 1682513423352977, 1682513423961134, 0, 'upkit.rashikasapru@gmail.com', 1673874254153097, 1, '2023-04-26 13:11:50', '2023-04-26 13:11:50'),
(1682515155909220, '77712ac17d479b1d022ea1c710764951f77865884cd45429f2d2c78b3dd7ab828c58191807243b39f5806c1834a7e5b1cd639481640a74eeb09aa829481aefeeDyFGyGtXAeQWAbOH6qVFB87BaQumGkNR/mpf24rVuy8vLbTLRU6RJy/Or/1zcirzuxRMCpok/Odou98nii4C4+8DfY1BJdkSGz2r7BaY+7AOKECxApM8ZZdmFEFDJ2GwHZ7zKVN2e9VZ9jHYaoJKQwJJ+q+XUJykp1yXCBN4y9x5xeK79rG4VR/nkzJnRCfjt8rc99RHNqEj+vQopTi33n+WD8BmtHVruWUFfCNLr1HAVWlAqjkSBX1l80z8PvFFcnuO', 'type', '', 1682513423782787, 1682513423961134, 0, 'dinesh.g@kitlabs.in', 1673874254153097, 1, '2023-04-26 13:19:15', '2023-04-26 13:19:15'),
(1682515268814436, '4082bb779e494fe1c69b4027c0ff6d9e1123d9bb22b871be900466add418626c4a2453a5853aa2fdbe25305cb444aa6d6efd9f16ebd7480924db33c0d9811a70BC6bpb+x4V64i5ZdVgyLhZUnkwYwlJoqFJGHDDbniV+79CwAWkvG/9m/lCTVWEPkbkPiFHFGXaF4Bb+oE2NhlEhZtQW2/dRtDzDPwiLpM3anlmQRrQEmivK1UXhE0Q4W5LPB7qUtkYDBtrtBwCFSHnzOEfxuJL5Liz7t+nyTI4kIzqZBhEBkmxMch3qDRGi76FSpaF70fmmy7PPCTsSQqt+pY3UgvLvrumS+1hWaWfBdGpa0c4EuZSDvzXSbbC4jhQD6r/eoTzYCSg==', 'type', '', 1682513423336272, 1682513423961134, 1673874254153097, 'upkit.dineshgiri@gmail.com', 1673874254153097, 1, '2023-04-26 13:21:08', '2023-04-26 13:21:08'),
(1682596397561506, '8e309f60ef1b74d79257f55336d79fd2e8311c25413e6fbe417dccb46b0c34b33d85b7379535cc3ae94375ecf165324a8ab7f18b7cb330608c9e937da91f226d2ZvQQ87sxaShl1OJjzMOfEE+yea0/WjZyLWCPVxA0tJibT6vKIOdNPtzoZz24RLIfaAQAq3osYsUCO6MO+xwQeA9ki7SoqxDjnEUbScUWz+csgXSYAULJuy540FTXNSiLT9aGLtm7Ry93zFtYR7JR7c4oZPOA1TGZg5S+Kx4g9/Sl/Rus7z3SKbvJIb1KHQTc6WP+b8//0YUtqD1jMCD69B4T2lNBWbX3Cfb+l+plN9iSzDnHqQ+kWN54BHp5iXfXq9TILAwBFngFs13', 'type', '', 1682513423352977, 1682513423961134, 0, 'upkit.rashikasapru@gmail.com', 1673874254153097, 1, '2023-04-27 11:53:17', '2023-04-27 11:53:17'),
(1682598065200827, 'f8ff2f6e3d499076c41db32925bcc91358c31c38aaa62d48baf25ad5bfa25ff61952adbd8f3a72e63fbf66af39ea40f6debbc6c2bffa01524e4df32bccf4f2e2YqQhXQWixl/ZBTOsfuSnpueujehMdhn7ykgjzN4eK4JoFlkwZqevZFycAXih5aiCaQebfGRJ/gBpU22FO3vFijBoBUwHZG+xwnzX33m/HdrklWH5KDz5aSEnr76Pn3SMrG0/HEm1WRDkIcd0K7cyDLo35kbQr9qDlUbZ3AqtTFJ28+8WhMFwjHKs1yceAFsF+DV3+RzBFz7LNA75Tm1IVtarpWPtv2slGTufBZ0lvFZQvydicWuZu4nTFsEUWlr+24rGywXAFmZ+kQ==', 'type', '', 1682513423336272, 1682513423961134, 1673874254153097, 'upkit.dineshgiri@gmail.com', 1673874254153097, 1, '2023-04-27 12:21:05', '2023-04-27 12:21:05'),
(1682598194305283, '945a6b3d99504e91bf31e7568f6446792c9dfec56ace003531ea089dfaf7512d2e8abaaaf34e9528e375b2350cbf965c2d30d0168686783084634ed85abe4574zHSJaQGr6NLWti/TB8g0Tnv9nT8+S8Wy2Ckz1khAAaI2a9VYL0Tyh1HH60EcpaXzxipwBEtuutV0GG7zy4iKmehjZb7Qj6YbIlBiqoLIVGz1ziuaYpGebx8tflVj+AL0ixypBWIF5QKMyaYARqoGj0alHQrCEb7XsMKMpe1BOSobmV782Zuuxdzj4JNj2eSryYaPZt0XuY7RZ3eFrR3EYRZJ5wvvqC/w1Zka5JCT9a701ugiXKixs7kF/QHcteQwRhF4', 'type', '', 1682513423782787, 1682513423961134, 0, 'dinesh.g@kitlabs.in', 1673874254153097, 1, '2023-04-27 12:23:14', '2023-04-27 12:23:14'),
(1683010251275158, 'c9e3780a175db9c9170b1a719c3b1b7d4891969308af552bec260534244253abc5ba152f312e47ae15d4150c7ec8d2015c23dd75abf420f11fe721b07c142dd6iHJTi0jL0Rh7pZH4e0//sxyODGIureZDZQ4QH8F1sSA7VukRrPoGrLRBPt7TN8bnH7AL40y1J3pcFkqwyflJf4FYHJ6DUWBfxQ6iqHIQnG6MBOtzwKfVz/YIG1iCMUK1jh4o1/ZKH5loRe2Aiwd5VT1sdEqgAMIETdqQ187sIcnFLhVHLyWk+ZAbRY5PLGAJVGuNMNfo/uycg/x54QV28VraOYewZHgi479mLhTARr2BOWoJJeSlV/vvi/1+ZD0IvyJIaHUDf/Q7RJWa', 'draw', '', 168300901053230, 1683009010876301, 0, 'upkit.rashikasapru@gmail.com', 1673874254153097, 1, '2023-05-02 06:50:51', '2023-05-02 06:50:51'),
(1683010560909174, '1bff4a1acf00e8152d3d02be47cbdffb30bc4805ec86b17bde5a048280d9939b5a8adba22026d9214f08039df22147d0bb317bbf8b9977bcd1917b30c15741f8YVR4Ro+T3NUB326KaAbQQw/ti4QotGZf+lopsS+IN+rdDvMUv842y03Eteg9KIyS4vzuoHXA89f4CS69RFWAiaZtR/vi2BHCru33G/J5aL9KU3KJ3QmemGGW6wiJtFP9sOlGeqJTij/hmXZrGDKNuvMkNYDY/OCuMYbTmYtMrpgMNJnLAhbahayDoeoYfxF0167FskW2KxjBMrgSnStD4RWtAp5wCaRX6BltdRkAsd4YMb4YTJQdC/dwXp5CxLZd3E8ehzjfS0gtec91', 'draw', '', 1683010477142270, 1683010477170652, 0, 'upkit.rashikasapru@gmail.com', 1673874254153097, 1, '2023-05-02 06:56:00', '2023-05-02 06:56:00');

-- --------------------------------------------------------

--
-- Table structure for table `e_sign_uploaded_files`
--

DROP TABLE IF EXISTS `e_sign_uploaded_files`;
CREATE TABLE IF NOT EXISTS `e_sign_uploaded_files` (
  `id` bigint NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `system_file_name` varchar(255) NOT NULL COMMENT 'File name generated by our system',
  `file_type` varchar(255) NOT NULL,
  `recipients` longtext NOT NULL COMMENT 'recipients json and their access type',
  `documentTitle` text NOT NULL COMMENT 'Title of the document',
  `recipientMessage` text NOT NULL COMMENT 'Any message for recipients',
  `expiresInDays` int NOT NULL COMMENT 'expires in no of days',
  `expiryDate` datetime NOT NULL COMMENT 'user''s document expiry date time',
  `expired` int NOT NULL DEFAULT '0' COMMENT '0 valid, 1 expired',
  `alertOneDyBfrExp` int NOT NULL DEFAULT '0' COMMENT 'Alert one day before expiry',
  `user_id` bigint NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `expiryNotify` int NOT NULL DEFAULT '0' COMMENT '0 (Not sent),1 (Sent document expired notification to owner)',
  `expiryNotifyDate` datetime NOT NULL COMMENT 'expiry notification sent date',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `e_sign_uploaded_files`
--

INSERT INTO `e_sign_uploaded_files` (`id`, `file_name`, `system_file_name`, `file_type`, `recipients`, `documentTitle`, `recipientMessage`, `expiresInDays`, `expiryDate`, `expired`, `alertOneDyBfrExp`, `user_id`, `created_at`, `updated_at`, `expiryNotify`, `expiryNotifyDate`) VALUES
(1682422536360155, 'Track Consignment EP700534772IN.pdf', '1682422536360155.pdf', 'application/pdf', '{\"upkit.rashikasapru@gmail.com\":{\"email\":\"upkit.rashikasapru@gmail.com\",\"name\":\"Rashika Sapru\",\"authType\":0,\"accessCode\":\"\",\"otp\":\"\"}}', 'SSHA VRPL', '', 10, '2023-05-05 23:59:59', 1, 1, 1673874254153097, '2023-04-25 11:35:36', '2023-05-04 12:16:29', 1, '2023-05-04 12:16:29'),
(1682319491787228, 'CertificateOfCompletion.pdf', '1682319491787228.pdf', 'application/pdf', '{\"upkit.rashikasapru@gmail.com\":{\"email\":\"upkit.rashikasapru@gmail.com\",\"name\":\"Rashika Sapru\",\"authType\":0,\"accessCode\":\"\",\"otp\":\"\"}}', 'demo', '', 10, '2023-05-04 23:59:59', 0, 0, 1673874254153097, '2023-04-24 06:58:11', '2023-04-24 06:58:11', 0, '0000-00-00 00:00:00'),
(1682085233579292, 'sample.pdf', '1682085233579292.pdf', 'application/pdf', '{\"upkit.rashikasapru@gmail.com\":{\"email\":\"upkit.rashikasapru@gmail.com\",\"name\":\"Rashika Sapru\",\"authType\":0,\"accessCode\":\"\",\"otp\":\"\"}}', 'Demo Document', 'Please review and sign', 10, '2023-05-01 23:59:59', 0, 0, 1673874254153097, '2023-04-21 13:53:53', '2023-04-21 13:53:53', 0, '0000-00-00 00:00:00'),
(1682084264678521, 'sample.pdf', '1682084264678521.pdf', 'application/pdf', '{\"upkit.rashikasapru@gmail.com\":{\"email\":\"upkit.rashikasapru@gmail.com\",\"name\":\"Rashika Sapru\",\"authType\":0,\"accessCode\":\"\",\"otp\":\"\"}}', 'Demo Document', 'Please review and sign the document', 10, '2023-05-01 23:59:59', 0, 0, 1673874254153097, '2023-04-21 13:37:44', '2023-04-21 13:37:44', 0, '0000-00-00 00:00:00'),
(1681966643464579, 'doc.pdf', '1681966643464579.pdf', 'application/pdf', '{\"upkit.rashikasapru@gmail.com\":{\"email\":\"upkit.rashikasapru@gmail.com\",\"name\":\"Rashika Sapru\",\"authType\":0,\"accessCode\":\"\",\"otp\":\"\"}}', 'SSHA VRPL', '', 10, '2023-04-30 23:59:59', 0, 0, 1673874254153097, '2023-04-20 04:57:23', '2023-04-20 04:57:23', 0, '0000-00-00 00:00:00'),
(1681905912981991, 'sample.pdf', '1681905912981991.pdf', 'application/pdf', '{\"upkit.rashikasapru@gmail.com\":{\"email\":\"upkit.rashikasapru@gmail.com\",\"name\":\"Rashika Sapru\",\"authType\":0,\"accessCode\":\"\",\"otp\":\"\"}}', 'SSHA VRPL', '', 10, '2023-04-29 23:59:59', 0, 0, 1673874254153097, '2023-04-19 12:05:12', '2023-04-19 12:05:12', 0, '0000-00-00 00:00:00'),
(1681372788233271, 'SSHA_VRPL.pdf', '1681372788233271.pdf', 'application/pdf', '{\"upkit.rashikasapru@gmail.com\":{\"email\":\"upkit.rashikasapru@gmail.com\",\"name\":\"Rashika Sapru\",\"authType\":0,\"accessCode\":\"\",\"otp\":\"\"}}', '', '', 10, '2023-04-23 23:59:59', 0, 0, 1673874254153097, '2023-04-13 07:59:48', '2023-04-13 07:59:48', 0, '0000-00-00 00:00:00'),
(1681383149860299, 'index.pdf', '1681383149860299.pdf', 'application/pdf', '{\"upkit.rashikasapru@gmail.com\":{\"email\":\"upkit.rashikasapru@gmail.com\",\"name\":\"Rashika Sapru\",\"authType\":0,\"accessCode\":\"\",\"otp\":\"\"}}', 'SSHA VRPL', '', 10, '2023-04-23 23:59:59', 0, 0, 1673874254153097, '2023-04-13 10:52:29', '2023-04-13 10:52:29', 0, '0000-00-00 00:00:00'),
(1681708934283592, 'index.pdf', '1681708934283592.pdf', 'application/pdf', '{\"upkit.rashikasapru@gmail.com\":{\"email\":\"upkit.rashikasapru@gmail.com\",\"name\":\"Rashika Sapru\",\"authType\":0,\"accessCode\":\"\",\"otp\":\"\"}}', 'SSHA VRPL', '', 10, '2023-04-27 23:59:59', 0, 0, 1673874254153097, '2023-04-17 05:22:14', '2023-04-17 05:22:14', 0, '0000-00-00 00:00:00'),
(1681796212263758, 'index.pdf', '1681796212263758.pdf', 'application/pdf', '{\"upkit.rashikasapru@gmail.com\":{\"email\":\"upkit.rashikasapru@gmail.com\",\"name\":\"Rashika Sapru\",\"authType\":1,\"accessCode\":\"\",\"otp\":886392},\"dinesh.g@kitlabs.in\":{\"email\":\"dinesh.g@kitlabs.in\",\"name\":\"kishan\",\"authType\":0,\"accessCode\":\"\",\"otp\":\"\"}}', 'SSHA VRPL', 'Additional message', 10, '2023-04-28 23:59:59', 0, 0, 1673874254153097, '2023-04-18 05:36:52', '2023-04-18 05:36:52', 0, '0000-00-00 00:00:00'),
(1682513163991942, 'gorise paypal last transaction1.pdf', '1682513163991942.pdf', 'application/pdf', '{\"upkit.rashikasapru@gmail.com\":{\"email\":\"upkit.rashikasapru@gmail.com\",\"name\":\"Rashika Sapru\",\"authType\":2,\"accessCode\":\"123456\",\"otp\":\"\"},\"upkit.dineshgiri@gmail.com\":{\"email\":\"upkit.dineshgiri@gmail.com\",\"name\":\"Dinesh Kumar\",\"authType\":1,\"accessCode\":\"\",\"otp\":275477},\"dinesh.g@kitlabs.in\":{\"email\":\"dinesh.g@kitlabs.in\",\"name\":\"Kishan Rathor\",\"authType\":1,\"accessCode\":\"\",\"otp\":381641}}', 'Test Sign Document', '', 10, '2023-05-06 23:59:59', 0, 1, 1673874254153097, '2023-04-26 12:46:03', '2023-04-26 12:46:03', 0, '0000-00-00 00:00:00'),
(1683008967990077, 'JEcard.pdf', '1683008967990077.pdf', 'application/pdf', '{\"upkit.rashikasapru@gmail.com\":{\"email\":\"upkit.rashikasapru@gmail.com\",\"name\":\"Rashika Sapru\",\"authType\":0,\"accessCode\":\"\",\"otp\":\"\"}}', 'Demo Document', 'Please review and sign the document.', 10, '2023-05-12 23:59:59', 0, 0, 1673874254153097, '2023-05-02 06:29:27', '2023-05-02 06:29:27', 0, '0000-00-00 00:00:00'),
(1683010438299574, 'ConsolidateCertificateOfComletion.pdf', '1683010438299574.pdf', 'application/pdf', '{\"upkit.rashikasapru@gmail.com\":{\"email\":\"upkit.rashikasapru@gmail.com\",\"name\":\"Rashika Sapru\",\"authType\":0,\"accessCode\":\"\",\"otp\":\"\"}}', 'Demo Document', 'PLease review and sign this document.', 10, '2023-05-12 23:59:59', 0, 0, 1673874254153097, '2023-05-02 06:53:58', '2023-05-02 06:53:58', 0, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `connection` text COLLATE utf8_unicode_ci NOT NULL,
  `queue` text COLLATE utf8_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2023_01_11_070617_create_permission_tables', 2),
(9, '2023_01_11_115510_create_e_sign_documents_table', 3),
(10, '2023_01_11_115552_create_e_sign_electronic_signatures_table', 3),
(11, '2023_01_11_115808_create_e_sign_document_signers_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
CREATE TABLE IF NOT EXISTS `model_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
CREATE TABLE IF NOT EXISTS `model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(2, 'App\\Models\\User', 2);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `guard_name` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `guard_name` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(2, 'Admin', 'web', '2023-01-11 01:53:23', '2023-01-11 01:53:23');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
CREATE TABLE IF NOT EXISTS `role_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint UNSIGNED NOT NULL,
  `first_name` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `last_name` text COLLATE utf8_unicode_ci NOT NULL,
  `phone` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'mobile number with country code',
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email_verified` tinyint NOT NULL COMMENT '0-not verified, 1-verified',
  `email_verified_at` datetime DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `phone`, `email`, `email_verified`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1673874254153097, 'Dinesh', 'Kumar', '', 'upkit.dineshgiri@gmail.com', 1, '2023-01-17 05:34:20', '7c4a8d09ca3762af61e59520943dc26494f8941b', NULL, '2023-01-16 13:04:14', '2023-01-16 13:04:14');

-- --------------------------------------------------------

--
-- Table structure for table `users_smtp`
--

DROP TABLE IF EXISTS `users_smtp`;
CREATE TABLE IF NOT EXISTS `users_smtp` (
  `id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `smtp_details` text NOT NULL,
  `last_update` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='contains smtp info of users';

--
-- Constraints for dumped tables
--

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
