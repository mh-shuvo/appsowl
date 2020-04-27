SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pos-final`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts_chart`
--

CREATE TABLE `accounts_chart` (
  `id` int(11) NOT NULL,
  `chart_no` varchar(100) DEFAULT NULL,
  `chart_name` varchar(100) NOT NULL,
  `chart_name_value` varchar(250) DEFAULT NULL,
  `chart_category_name` varchar(100) DEFAULT NULL,
  `chart_sub_category_name` varchar(100) DEFAULT NULL,
  `account_group` varchar(100) DEFAULT NULL,
  `chart_type` enum('debit','credit') DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `update_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `user_id` int(20) DEFAULT NULL,
  `is_delete` enum('true','false') NOT NULL DEFAULT 'false',
  `chart_status` enum('active','inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `accounts_chart`
--

INSERT INTO `accounts_chart` (`id`, `chart_no`, `chart_name`, `chart_name_value`, `chart_category_name`, `chart_sub_category_name`, `account_group`, `chart_type`, `created_at`, `update_at`, `user_id`, `is_delete`, `chart_status`) VALUES
(1, '1', 'account_total_purchase', 'account_total_purchase', 'account_cost_of_sold_goods', '', NULL, 'debit', '2019-09-30 13:35:02', '2019-10-13 12:57:24', 1, 'false', 'active'),
(2, '2', 'account_total_sale', 'account_total_sale', 'account_income_of_sold_goods', '', NULL, 'credit', '2019-09-30 13:35:02', '2019-10-13 12:57:26', 1, 'false', 'active'),
(3, '3', 'account_vat', 'account_vat', 'account_current_liabilities', '', 'account_expense', 'credit', '2019-09-30 13:35:02', '2019-10-13 13:27:06', 1, 'false', 'active'),
(5, '5', 'account_purchase_return', 'account_purchase_return', 'account_cost_of_sold_goods', '', NULL, 'credit', '2019-09-30 13:35:02', '2019-10-10 16:07:31', 1, 'false', 'active'),
(6, '6', 'account_sale_return', 'account_sale_return', 'account_income_of_sold_goods', '', NULL, 'debit', '2019-09-30 13:35:02', '2019-10-10 16:12:55', 1, 'false', 'active'),
(7, '7', 'account_purchase_discount', 'account_purchase_discount', 'account_cost_of_sold_goods', '', NULL, 'credit', '2019-09-30 13:35:02', '2019-10-10 16:07:35', 1, 'false', 'active'),
(8, '8', 'account_sale_discount', 'account_sale_discount', 'account_income_of_sold_goods', '', NULL, 'debit', '2019-09-30 13:35:02', '2019-10-10 16:13:01', 1, 'false', 'active'),
(9, '9', 'account_salary', 'account_salary', 'account_administrative_cost', 'account_expense', 'account_expense', 'debit', '2019-09-30 13:35:02', '2019-10-13 13:06:16', 1, 'false', 'active'),
(12, '12', 'account_carriage_inward', 'account_carriage_inward', 'account_cost_of_sold_goods', 'account_expense', 'account_expense', 'debit', '2019-09-30 13:35:02', '2019-10-13 13:06:19', 1, 'false', 'active'),
(13, '13', 'account_carriage_outward', 'account_carriage_outward', 'account_operating_cost', 'account_expense', 'account_expense', 'debit', '2019-09-30 13:35:02', '2019-10-13 13:22:46', 1, 'false', 'active'),
(14, '14', 'account_wages', 'account_wages', 'account_cost_of_sold_goods', 'account_expense', 'account_expense', 'debit', '2019-09-30 13:35:02', '2019-10-19 12:48:15', 1, 'false', 'active'),
(17, '17', 'account_repairing_cost', 'account_repairing_cost', 'account_operating_cost', 'account_expense', 'account_expense', 'debit', '2019-09-30 13:35:02', '2019-10-13 13:22:48', 1, 'false', 'active'),
(18, '18', 'account_duty_fee', 'account_duty_fee', 'account_cost_of_sold_goods', 'account_expense', 'account_expense', 'debit', '2019-09-30 13:35:02', '2019-10-19 12:44:25', 1, 'false', 'active'),
(19, '19', 'account_law_cost', 'account_law_cost', 'account_administrative_cost', 'account_expense', 'account_expense', 'debit', '2019-09-30 13:35:02', '2019-10-13 13:22:52', 1, 'false', 'active'),
(20, '20', 'account_audit_cost', 'account_audit_cost', 'account_administrative_cost', 'account_expense', 'account_expense', 'debit', '2019-09-30 13:35:02', '2019-10-13 13:22:53', 1, 'false', 'active'),
(21, '21', 'account_insurance_cost', 'account_insurance_cost', 'account_administrative_cost', 'account_expense', 'account_expense', 'debit', '2019-09-30 13:35:02', '2019-10-13 13:22:55', 1, 'false', 'active'),
(22, '22', 'account_office_rent', 'account_office_rent', 'account_administrative_cost', 'account_expense', 'account_expense', 'debit', '2019-09-30 13:35:02', '2019-10-13 13:23:53', 1, 'false', 'active'),
(23, '23', 'account_office_cost', 'account_office_cost', 'account_administrative_cost', 'account_expense', 'account_expense', 'debit', '2019-09-30 13:35:02', '2019-10-13 13:22:59', 1, 'false', 'active'),
(24, '24', 'account_electricity_bill', 'account_electricity_bill', 'account_administrative_cost', 'account_expense', 'account_expense', 'debit', '2019-09-30 13:35:02', '2019-10-13 13:23:13', 1, 'false', 'active'),
(25, '25', 'account_gas_bill', 'account_gas_bill', 'account_administrative_cost', 'account_expense', 'account_expense', 'debit', '2019-09-30 13:35:02', '2019-10-13 13:23:14', 1, 'false', 'active'),
(26, '26', 'account_water_bill', 'account_water_bill', 'account_administrative_cost', 'account_expense', 'account_expense', 'debit', '2019-09-30 13:35:02', '2019-10-13 13:24:33', 1, 'false', 'active'),
(27, '27', 'account_telephone_bill', 'account_telephone_bill', 'account_administrative_cost', 'account_expense', 'account_expense', 'debit', '2019-09-30 13:35:02', '2019-10-13 13:23:43', 1, 'false', 'active'),
(29, '29', 'account_advertisement_cost', 'account_advertisement_cost', 'account_administrative_cost', 'account_expense', 'account_expense', 'debit', '2019-09-30 13:35:02', '2019-10-13 13:24:36', 1, 'false', 'active'),
(30, '30', 'account_marketing_cost', 'account_marketing_cost', 'account_operating_cost', 'account_expense', 'account_expense', 'debit', '2019-09-30 13:35:02', '2019-10-13 13:28:01', 1, 'false', 'active'),
(31, '31', 'account_packaging_cost', 'account_packaging_cost', 'account_operating_cost', 'account_expense', 'account_expense', 'debit', '2019-09-30 13:35:02', '2019-10-13 13:28:03', 1, 'false', 'active'),
(32, '32', 'account_income_tax_cost', 'account_income_tax_cost', 'account_administrative_cost', 'account_expense', 'account_expense', 'debit', '2019-09-30 13:35:02', '2019-10-13 13:30:31', 1, 'false', 'active'),
(33, '33', 'account_transport_cost', 'account_transport_cost', 'account_administrative_cost', 'account_expense', 'account_expense', 'debit', '2019-09-30 13:35:02', '2019-10-13 13:30:33', 1, 'false', 'active'),
(34, '34', 'account_reception_or_entertainment_cost', 'account_reception_or_entertainment_cost', 'account_administrative_cost', 'account_expense', 'account_expense', 'debit', '2019-09-30 13:35:02', '2019-10-13 13:30:34', 1, 'false', 'active'),
(35, '35', 'account_tour_cost', 'account_tour_cost', 'account_administrative_cost', 'account_expense', 'account_expense', 'debit', '2019-09-30 13:35:02', '2019-10-13 13:30:35', 1, 'false', 'active'),
(36, '36', 'account_cash_theft_or_lost', 'account_cash_theft_or_lost', 'account_non_operating_cost', 'account_expense', 'account_expense', 'debit', '2019-09-30 13:35:02', '2019-10-13 13:30:37', 1, 'false', 'active'),
(37, '37', 'account_product_theft_or_lost', 'account_product_theft_or_lost', 'account_cost_of_sold_goods', 'account_expense', 'account_expense', 'debit', '2019-09-30 13:35:02', '2019-10-13 13:30:38', 1, 'false', 'active'),
(38, '38', 'account_accidental_cost', 'account_accidental_cost', 'account_non_operating_cost', 'account_expense', 'account_expense', 'debit', '2019-09-30 13:35:02', '2019-10-13 13:30:39', 1, 'false', 'active'),
(39, '39', 'account_product_wastage_cost', 'account_product_wastage_cost', 'account_cost_of_sold_goods', 'account_expense', 'account_expense', 'debit', '2019-09-30 13:35:02', '2019-10-13 13:30:41', 1, 'false', 'active'),
(40, '40', 'account_product_expired_cost', 'account_product_expired_cost', 'account_cost_of_sold_goods', 'account_expense', 'account_expense', 'debit', '2019-09-30 13:35:02', '2019-10-13 13:30:42', 1, 'false', 'active'),
(41, '41', 'account_commission_cost', 'account_commission_cost', 'account_operating_cost', 'account_expense', 'account_expense', 'debit', '2019-09-30 13:35:02', '2019-10-13 13:30:44', 1, 'false', 'active'),
(42, '42', 'account_commission_income', 'account_commission_income', 'account_non_operating_income', 'account_income', 'account_income', 'credit', '2019-09-30 13:35:02', '2019-10-13 14:08:20', 1, 'false', 'active'),
(43, '43', 'account_revenue_cost', 'account_revenue_cost', 'account_administrative_cost', 'account_expense', 'account_expense', 'debit', '2019-09-30 13:35:02', '2019-10-13 13:30:46', 1, 'false', 'active'),
(44, '44', 'account_other_cost', 'account_other_cost', 'account_administrative_cost', 'account_expense', 'account_expense', 'debit', '2019-09-30 13:35:02', '2019-10-13 13:30:48', 1, 'false', 'active'),
(45, '45', 'account_cash', 'account_cash', 'account_current_assets', '', NULL, 'debit', '2019-09-30 13:35:02', '2019-10-13 13:28:48', 1, 'false', 'active'),
(46, '46', 'account_bank', 'account_bank', 'account_current_assets', '', NULL, 'debit', '2019-09-30 13:35:02', '2019-10-13 13:28:51', 1, 'false', 'active'),
(47, '47', 'account_bank_charge', 'account_bank_charge', 'account_non_operating_cost', 'account_expense', 'account_expense', 'debit', '2019-09-30 13:35:02', '2019-10-13 13:30:50', 1, 'false', 'active'),
(48, '48', 'account_bank_interest_income', 'account_bank_interest_income', 'account_non_operating_income', 'account_income', 'account_income', 'credit', '2019-09-30 13:35:02', '2019-10-13 14:08:17', 1, 'false', 'active'),
(49, '49', 'account_capital', 'account_capital', 'account_capital', NULL, NULL, 'credit', '2019-09-30 13:35:02', '2019-10-24 19:07:52', 1, 'false', 'active'),
(50, '50', 'account_withdraw', 'account_withdraw', 'account_capital', NULL, NULL, 'debit', '2019-09-30 13:35:02', '2019-10-24 19:07:56', 1, 'false', 'active'),
(51, '51', 'account_product_withdraw_by_owner', 'account_product_withdraw_by_owner', 'account_cost_of_sold_goods', '', 'account_expense', 'debit', '2019-09-30 13:35:02', '2019-10-15 14:22:33', 1, 'false', 'active'),
(52, '52', 'account_product_use_in_marketing', 'account_product_use_in_marketing', 'account_cost_of_sold_goods', '', 'account_expense', 'debit', '2019-09-30 13:35:02', '2019-10-15 14:22:29', 1, 'false', 'active'),
(53, '53', 'account_land', 'account_land', 'account_fixed_assets', 'account_capital_assets', 'account_expense,capital_assets,capital_lease,account_income', 'debit', '2019-09-30 13:35:02', '2019-10-13 14:08:29', 1, 'false', 'active'),
(54, '54', 'account_machinery', 'account_machinery', 'account_fixed_assets', 'account_capital_assets', 'account_expense,capital_assets,capital_lease,account_income', 'debit', '2019-09-30 13:35:02', '2019-10-13 14:08:33', 1, 'false', 'active'),
(55, '55', 'account_furnisher', 'account_furnisher', 'account_fixed_assets', 'account_capital_assets', 'account_expense,capital_assets,capital_lease,account_income', 'debit', '2019-09-30 13:35:02', '2019-10-13 14:08:36', 1, 'false', 'active'),
(56, '56', 'account_delivery_truck_or_car', 'account_delivery_truck_or_car', 'account_fixed_assets', 'account_capital_assets', 'account_expense,capital_assets,capital_lease,account_income', 'debit', '2019-09-30 13:35:02', '2019-10-13 14:08:38', 1, 'false', 'active'),
(63, '63', 'account_stationary_cost', 'account_stationary_cost', 'account_non_operating_cost', 'account_expense', 'account_expense', 'debit', '2019-09-30 13:35:02', '2019-10-13 14:09:27', 1, 'false', 'active'),
(64, '64', 'account_asset_sale_cost', 'account_asset_sale_cost', 'account_non_operating_cost', 'account_expense', 'account_expense', 'debit', '2019-09-30 13:35:02', '2019-10-13 14:09:11', 1, 'false', 'active'),
(65, '65', 'account_asset_sale_income', 'account_asset_sale_income', 'account_non_operating_income', 'account_income', 'account_income', 'credit', '2019-09-30 13:35:02', '2019-10-13 14:09:32', 1, 'false', 'active'),
(66, '66', 'account_debitors', 'account_debitors', 'account_current_assets', '', NULL, 'debit', '2019-09-30 13:35:02', '2019-10-13 14:10:18', 1, 'false', 'active'),
(67, '67', 'account_creditors', 'account_creditors', 'account_current_liabilities', '', NULL, 'credit', '2019-09-30 13:35:02', '2019-10-13 14:10:54', 1, 'false', 'active'),
(68, '68', 'account_loan', 'account_loan', 'account_long_term_liabilities', 'account_capital_loans', NULL, 'credit', '2019-09-30 13:35:02', '2019-10-16 18:05:24', 1, 'false', 'active'),
(69, '69', 'account_loan_interest_cost', 'account_loan_interest_cost', 'account_non_operating_cost', 'account_expense', 'account_expense', 'debit', '2019-09-30 13:35:02', '2019-10-13 14:11:56', 1, 'false', 'active'),
(70, '70', 'account_opening_stock', 'account_opening_stock', 'account_cost_of_sold_goods', NULL, NULL, 'debit', '2019-09-30 13:35:02', '2019-10-13 14:11:48', 1, 'false', 'active'),
(71, '71', 'account_closing_stock', 'account_closing_stock', 'account_cost_of_sold_goods', NULL, NULL, 'debit', '2019-09-30 13:35:02', '2019-10-13 14:11:45', 1, 'false', 'active'),
(72, '72', 'account_depreciate_cost', 'account_depreciate_cost', 'account_non_operating_cost', 'account_expense', NULL, 'debit', '2019-09-30 13:35:02', '2019-10-15 14:22:48', 1, 'false', 'active'),
(74, '74', 'account_net_purchase', 'account_net_purchase', 'account_cost_of_sold_goods', 'account_expense', NULL, 'debit', '2019-09-30 13:35:02', '2019-10-15 14:23:00', 1, 'false', 'active'),
(75, '75', 'account_import_duty_fee', 'account_import_duty_fee', 'account_cost_of_sold_goods', 'account_expense', 'account_expense', 'debit', '2019-09-30 13:35:02', '2019-10-15 14:24:31', 1, 'false', 'active'),
(76, '76', 'account_lease', 'account_lease', 'account_fixed_assets', NULL, NULL, NULL, '2019-10-16 18:03:40', '2019-10-19 19:50:15', 1, 'false', 'active'),
(77, '77', 'account_owners_equity', 'account_owners_equity', 'account_long_term_liabilities', 'account_capital_loans', NULL, 'credit', '2019-09-30 13:35:02', '2019-10-16 18:05:24', 1, 'false', 'active'),
(78, '78', 'account_sale_return_discount', 'account_sale_return_discount', 'account_income_of_sold_goods', '', NULL, 'debit', '2019-09-30 13:35:02', '2019-10-17 16:18:52', 1, 'false', 'active'),
(79, '5', 'account_purchase_return_discount', 'account_purchase_return_discount', 'account_cost_of_sold_goods', '', NULL, 'credit', '2019-09-30 13:35:02', '2019-10-10 16:07:31', 1, 'false', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `accounts_chart_category`
--

CREATE TABLE `accounts_chart_category` (
  `id` int(10) NOT NULL,
  `chart_category_name` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `chart_category_type` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `chart_type` enum('debit','credit') COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_delete` enum('true','false') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'false',
  `chart_category_status` enum('active','inactive') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `accounts_chart_category`
--

INSERT INTO `accounts_chart_category` (`id`, `chart_category_name`, `chart_category_type`, `chart_type`, `is_delete`, `chart_category_status`) VALUES
(1, 'account_cost_of_sold_goods', 'income_statement', NULL, 'false', 'active'),
(2, 'account_operating_cost', 'income_statement', NULL, 'false', 'active'),
(3, 'account_administrative_cost', 'income_statement', NULL, 'false', 'active'),
(4, 'account_non_operating_cost', 'income_statement', NULL, 'false', 'active'),
(5, 'account_non_operating_income', 'income_statement', NULL, 'false', 'active'),
(6, 'account_net_income', 'net_income_statement', NULL, 'false', 'active'),
(7, 'account_capital', 'owner_equity', NULL, 'false', 'active'),
(8, 'account_current_assets', 'financial_statement', NULL, 'false', 'active'),
(9, 'account_fixed_assets', 'financial_statement', NULL, 'false', 'active'),
(10, 'account_current_liabilities', 'financial_statement', NULL, 'false', 'active'),
(11, 'account_long_term_liabilities', 'financial_statement', NULL, 'false', 'active'),
(12, 'account_owners_equity', 'financial_statement', NULL, 'false', 'active'),
(13, 'account_income_of_sold_goods', 'income_statement', NULL, 'false', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `accounts_chart_sub_category`
--

CREATE TABLE `accounts_chart_sub_category` (
  `id` int(10) NOT NULL,
  `chart_sub_category_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_delete` enum('true','false') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'false',
  `chart_sub_category_status` enum('active','inactive') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `accounts_chart_sub_category`
--

INSERT INTO `accounts_chart_sub_category` (`id`, `chart_sub_category_name`, `is_delete`, `chart_sub_category_status`) VALUES
(13, 'account_chart', 'false', 'active'),
(14, 'account_income', 'false', 'active'),
(15, 'account_expense', 'false', 'active'),
(16, 'account_capital_cash', 'false', 'active'),
(17, 'account_withdraw', 'false', 'active'),
(18, 'account_transfer', 'false', 'active'),
(21, 'account_income_statement', 'false', 'active'),
(22, 'account_financial_statement', 'false', 'active'),
(23, 'account_capital_bank', 'false', 'active'),
(24, 'account_capital_assets', 'false', 'active'),
(25, 'account_capital_loans', 'false', 'active'),
(26, 'account_capital_lease', 'false', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `accounts_transactions`
--

CREATE TABLE `accounts_transactions` (
  `id` int(100) NOT NULL,
  `store_id` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payer_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dr_account` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dr_account_status` enum('paid','due','advance') COLLATE utf8_unicode_ci DEFAULT NULL,
  `cr_account` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cr_account_status` enum('paid','due','due_paid','advance','advance_paid') COLLATE utf8_unicode_ci DEFAULT NULL,
  `amount` float(10,2) DEFAULT NULL,
  `payment_method` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `from_payment_method` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `account_type` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_due_purpose` enum('true','false') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'false',
  `is_advance_purpose` enum('true','false') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'false',
  `account_status` enum('paid','due','advance') COLLATE utf8_unicode_ci DEFAULT NULL,
  `loan_installment` decimal(20,2) DEFAULT NULL,
  `leasting_date` date DEFAULT NULL,
  `note` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `date` date DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `update_at` datetime DEFAULT NULL,
  `is_delete` enum('true','false') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'false',
  `status` enum('active','inactive') COLLATE utf8_unicode_ci DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pos_brands`
--

CREATE TABLE `pos_brands` (
  `brand_id` int(11) NOT NULL,
  `brand_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `brand_status` enum('active','inactive') COLLATE utf8_unicode_ci DEFAULT 'active',
  `is_delete` enum('true','false') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'false',
  `created_at` datetime DEFAULT NULL,
  `update_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pos_category`
--

CREATE TABLE `pos_category` (
  `category_id` int(10) UNSIGNED NOT NULL,
  `user_id` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `is_delete` enum('true','false') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'false',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pos_contact`
--

CREATE TABLE `pos_contact` (
  `id` int(10) UNSIGNED NOT NULL,
  `contact_id` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_type` enum('customer','supplier','both','account') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `business_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `store_id` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '1',
  `user_id` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp(),
  `contact_status` enum('active','inactive') COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `is_delete` enum('true','false') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'false'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pos_invoice`
--

CREATE TABLE `pos_invoice` (
  `id` int(11) NOT NULL,
  `invoice_id` varchar(100) NOT NULL,
  `invoice_title` varchar(255) DEFAULT NULL,
  `top` varchar(100) DEFAULT NULL,
  `bottom` varchar(100) DEFAULT NULL,
  `header` varchar(100) DEFAULT NULL,
  `footer` varchar(100) DEFAULT NULL,
  `logo` varchar(100) DEFAULT NULL,
  `footer_note` mediumtext DEFAULT NULL,
  `status` enum('active','deactive') DEFAULT NULL,
  `is_delete` enum('true','false') NOT NULL DEFAULT 'false'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pos_payment_method`
--

CREATE TABLE `pos_payment_method` (
  `payment_method_id` int(11) NOT NULL,
  `payment_method_name` varchar(100) DEFAULT NULL,
  `payment_method_value` varchar(100) DEFAULT NULL,
  `payment_method_type` varchar(100) DEFAULT NULL,
  `account_number` varchar(100) DEFAULT NULL,
  `minimum_amount` float NOT NULL,
  `payment_method_details` mediumtext DEFAULT NULL,
  `payment_method_status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `user_id` varchar(100) DEFAULT NULL,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_at` datetime DEFAULT NULL,
  `is_delete` enum('true','false') NOT NULL DEFAULT 'false'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pos_product`
--

CREATE TABLE `pos_product` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_id` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_vat` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `product_vat_type` enum('fixed','percent') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit_id` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `brand_id` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `supplier_id` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alert_quantity` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_stock` enum('enable','disable') COLLATE utf8mb4_unicode_ci DEFAULT 'enable',
  `product_image` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_type` enum('single','variable') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `variation_category_id` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_serial` enum('enable','disable') COLLATE utf8mb4_unicode_ci DEFAULT 'disable',
  `product_featured` enum('true','false') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'false',
  `product_status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `is_delete` enum('true','false') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'false',
  `created_at` datetime DEFAULT NULL,
  `update_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pos_product_serial`
--

CREATE TABLE `pos_product_serial` (
  `id` int(100) NOT NULL,
  `product_id` varchar(100) DEFAULT NULL,
  `sub_product_id` varchar(100) DEFAULT NULL,
  `purchase_id` varchar(100) DEFAULT NULL,
  `stock_id` varchar(100) DEFAULT '1',
  `sell_stock_id` varchar(100) DEFAULT NULL,
  `sales_id` varchar(100) DEFAULT NULL,
  `return_id` varchar(100) DEFAULT NULL,
  `product_serial_no` varchar(250) DEFAULT NULL,
  `product_serial_category` enum('purchase','transfered','sell_return','purchase_return','adjustment') DEFAULT NULL,
  `product_serial_status` enum('received','sell','transfer','damage') NOT NULL,
  `product_serial_stock_type` enum('in','out') DEFAULT NULL,
  `customer_id` varchar(100) DEFAULT NULL,
  `supplier_id` varchar(100) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `sold_at` datetime DEFAULT NULL,
  `product_serial_note` mediumtext NOT NULL,
  `is_delete` enum('true','false') NOT NULL DEFAULT 'false'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pos_purchase`
--

CREATE TABLE `pos_purchase` (
  `id` int(100) NOT NULL,
  `purchase_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `supplier_id` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `purchase_reference_no` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(100) NOT NULL,
  `purchase_subtotal` float(10,2) NOT NULL DEFAULT 0.00,
  `purchase_total` float(10,2) DEFAULT 0.00,
  `purchase_vat` float(10,2) NOT NULL DEFAULT 0.00,
  `purchase_tax` float(10,2) NOT NULL DEFAULT 0.00,
  `store_id` varchar(100) COLLATE utf8_unicode_ci DEFAULT '1',
  `purchase_note` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `purchase_discount` float(10,2) NOT NULL DEFAULT 0.00,
  `purchase_shipping_charge` decimal(20,2) DEFAULT NULL,
  `purchase_shipping_note` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `purchase_document` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `update_at` datetime DEFAULT NULL,
  `purchase_date` date DEFAULT NULL,
  `purchase_payment_status` enum('paid','due','cancel') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'due',
  `purchase_status` enum('pending','received','ordered','cancel') COLLATE utf8_unicode_ci DEFAULT 'pending',
  `is_delete` enum('true','false') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'false'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pos_register_report`
--

CREATE TABLE `pos_register_report` (
  `id` int(11) NOT NULL,
  `register_id` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `store_id` varchar(100) COLLATE utf8_unicode_ci DEFAULT '1',
  `warehouse_id` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `register_open` datetime DEFAULT NULL,
  `register_close` datetime DEFAULT NULL,
  `register_open_balance` float(10,2) NOT NULL DEFAULT 0.00,
  `register_close_balance` float(10,2) NOT NULL DEFAULT 0.00,
  `register_status` enum('open','close') COLLATE utf8_unicode_ci DEFAULT 'open',
  `is_delete` enum('true','false') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'false',
  `closing_note` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `opening_note` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pos_return`
--

CREATE TABLE `pos_return` (
  `id` int(100) NOT NULL,
  `return_id` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sales_id` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `purchase_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `customer_id` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `supplier_id` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `store_id` varchar(100) COLLATE utf8_unicode_ci DEFAULT '1',
  `user_id` int(100) NOT NULL,
  `return_subtotal` float(10,2) NOT NULL DEFAULT 0.00,
  `return_vat` decimal(20,2) DEFAULT 0.00,
  `return_discount` decimal(20,2) DEFAULT 0.00,
  `return_total` float(10,2) NOT NULL DEFAULT 0.00,
  `return_sales_purchase_total` float(20,2) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `update_at` datetime DEFAULT NULL,
  `return_note` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `return_type` enum('sales','purchase') COLLATE utf8_unicode_ci DEFAULT NULL,
  `document` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `return_payment_status` enum('paid','due','cancel') COLLATE utf8_unicode_ci DEFAULT NULL,
  `return_status` enum('returned','cancel') COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_delete` enum('true','false') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'false'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pos_sales`
--

CREATE TABLE `pos_sales` (
  `id` int(100) NOT NULL,
  `sales_id` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `customer_id` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `store_id` varchar(100) COLLATE utf8_unicode_ci DEFAULT '1',
  `sales_type` enum('pos','invoice') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'pos',
  `user_id` int(100) DEFAULT NULL,
  `return_id` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sales_subtotal` float(10,2) NOT NULL DEFAULT 0.00,
  `sales_total` float(10,2) NOT NULL DEFAULT 0.00,
  `sales_vat` float(10,2) NOT NULL DEFAULT 0.00,
  `sales_discount` float(10,2) NOT NULL DEFAULT 0.00,
  `sales_discount_type` enum('percent','fixed') COLLATE utf8_unicode_ci DEFAULT NULL,
  `sales_discount_value` decimal(20,2) DEFAULT 0.00,
  `sales_pay_cash` decimal(20,2) DEFAULT NULL,
  `sales_pay_change` decimal(20,2) DEFAULT NULL,
  `shipping_charge` decimal(20,2) DEFAULT NULL,
  `sales_purchase_total` float(20,2) DEFAULT NULL,
  `shipping_details` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `sales_shipping_status` enum('delivered','pending','cancel','hold') COLLATE utf8_unicode_ci DEFAULT NULL,
  `sales_note` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `return_total` float(10,2) NOT NULL DEFAULT 0.00,
  `sales_payment_status` enum('paid','due','cancel') COLLATE utf8_unicode_ci DEFAULT NULL,
  `sales_status` enum('quote','complete','ordered','cancel','draft') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'complete',
  `is_delete` enum('true','false') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'false',
  `created_at` datetime DEFAULT NULL,
  `update_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pos_setting`
--

CREATE TABLE `pos_setting` (
  `id` int(100) NOT NULL,
  `company_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `currency` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `address` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `website` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `receipt_footer` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `vat` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `vat_type` enum('global','single') COLLATE utf8_unicode_ci NOT NULL,
  `company_logo` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `invoice_logo` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `invoice_margin_top` int(100) NOT NULL DEFAULT 100,
  `invoice_margin_bottom` int(10) NOT NULL DEFAULT 10,
  `invoice_margin_left` int(10) DEFAULT 10,
  `invoice_margin_right` int(10) NOT NULL DEFAULT 10,
  `nbr_no` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `nbr_unit` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `pos_active` enum('active','inactive','cancel','expire') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'active',
  `pos_renew_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pos_stock`
--

CREATE TABLE `pos_stock` (
  `id` int(100) NOT NULL,
  `stock_id` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `adjustment_type` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `product_id` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sub_product_id` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `variation_id` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `customer_id` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sales_id` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `supplier_id` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `store_id` varchar(100) COLLATE utf8_unicode_ci DEFAULT '1',
  `warehouse_id` varchar(100) COLLATE utf8_unicode_ci DEFAULT '1',
  `purchase_id` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `return_id` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `transfer_id` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `adjustment_id` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `product_quantity` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `product_price` float(10,2) NOT NULL DEFAULT 0.00,
  `sales_purchase_price` decimal(20,2) DEFAULT NULL,
  `product_discount` decimal(20,2) DEFAULT 0.00,
  `product_vat` decimal(20,2) DEFAULT 0.00,
  `product_vat_value` decimal(20,2) DEFAULT 0.00,
  `product_vat_type` enum('percent','fixed') COLLATE utf8_unicode_ci DEFAULT NULL,
  `product_vat_total` decimal(20,2) DEFAULT 0.00,
  `stock_category` enum('sales','purchase','damage','return','replace','transfer','opening_stock') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'sales',
  `stock_type` enum('in','out') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'in',
  `expire_date` date DEFAULT NULL,
  `manufac_date` date DEFAULT NULL,
  `product_subtotal` float(10,2) NOT NULL DEFAULT 0.00,
  `stock_date` date DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `update_at` datetime DEFAULT NULL,
  `stock_status` enum('active','inactive','cancel') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'active',
  `stock_note` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `product_serial` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_delete` enum('true','false') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'false'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pos_stock_adjustment`
--

CREATE TABLE `pos_stock_adjustment` (
  `id` int(100) NOT NULL,
  `stock_adjustment_id` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `from_store_id` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `reference_no` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `store_id` varchar(100) COLLATE utf8_unicode_ci DEFAULT '1',
  `user_id` int(100) NOT NULL,
  `shipping_charge` float(10,2) NOT NULL DEFAULT 0.00,
  `created_at` datetime DEFAULT NULL,
  `stock_adjustment_note` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `stock_adjustment_status` enum('active','inactive') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'active',
  `is_delete` enum('true','false') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'false',
  `type` enum('normal','abnormal') COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pos_stock_transfer`
--

CREATE TABLE `pos_stock_transfer` (
  `id` int(100) NOT NULL,
  `stock_transfer_id` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `from_store_id` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `to_store_id` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `reference_no` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `store_id` varchar(100) COLLATE utf8_unicode_ci DEFAULT '1',
  `user_id` int(100) NOT NULL,
  `shipping_charge` float(10,2) NOT NULL DEFAULT 0.00,
  `created_at` datetime DEFAULT NULL,
  `stock_transfer_note` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `stock_transfer_status` enum('send','received','cancel') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'send',
  `is_delete` enum('true','false') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'false'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pos_store`
--

CREATE TABLE `pos_store` (
  `store_id` int(10) NOT NULL,
  `store_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `store_location` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `warehouse_id` int(100) DEFAULT NULL,
  `user_id` int(20) DEFAULT NULL,
  `is_default` enum('true','false') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'false',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `store_status` enum('active','deactive') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'active',
  `is_delete` enum('true','false') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'false'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pos_transactions`
--

CREATE TABLE `pos_transactions` (
  `id` int(100) NOT NULL,
  `transaction_id` varchar(100) DEFAULT NULL,
  `transaction_type` enum('sales','purchase','return','deposit','withdrawal','opening','credit','debit','transfer','vat','adjustment') DEFAULT NULL,
  `transaction_charge` decimal(20,2) DEFAULT NULL,
  `return_id` varchar(100) DEFAULT NULL,
  `purchase_id` varchar(100) DEFAULT NULL,
  `sales_id` varchar(100) DEFAULT NULL,
  `account_id` varchar(100) DEFAULT NULL,
  `matching_id` varchar(100) DEFAULT NULL,
  `adjustment_id` varchar(20) DEFAULT NULL,
  `transaction_amount` decimal(20,2) NOT NULL,
  `transaction_flow_type` enum('credit','debit') DEFAULT NULL,
  `is_return` enum('true','false') NOT NULL DEFAULT 'false',
  `payment_method_value` varchar(100) DEFAULT NULL,
  `transaction_no` varchar(100) DEFAULT NULL,
  `transaction_note` mediumtext NOT NULL,
  `paid_date` datetime DEFAULT NULL,
  `payment_for` varchar(100) DEFAULT NULL,
  `user_id` varchar(100) DEFAULT NULL,
  `store_id` varchar(100) DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `update_at` datetime NOT NULL DEFAULT current_timestamp(),
  `transaction_status` enum('paid','due','hold','cancel') NOT NULL DEFAULT 'paid',
  `is_delete` enum('true','false') NOT NULL DEFAULT 'false'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pos_unit`
--

CREATE TABLE `pos_unit` (
  `unit_id` int(11) UNSIGNED NOT NULL,
  `unit_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit_status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `is_delete` enum('true','false') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'false',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pos_user_permission`
--

CREATE TABLE `pos_user_permission` (
  `permission_id` int(100) NOT NULL,
  `permission_section` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `permission_view` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `permission_edit` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `permission_delete` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `permission_off` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `permission_status` enum('active','inactive') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `pos_user_permission`
--

INSERT INTO `pos_user_permission` (`permission_id`, `permission_section`, `permission_view`, `permission_edit`, `permission_delete`, `permission_off`, `permission_status`) VALUES
(1, 'sale_pos', ',1,93,95,97,94,98', ',1,94', ',1,94', '', 'active'),
(2, 'sale_pos_sale_list', ',1,94', ',1,93,95,94', ',1,94', '', 'active'),
(3, 'purchase_add_purchase', ',1,95,97,94', ',1,94', ',1,93,94', '', 'active'),
(4, 'purchase_purchase_list', ',1,94', ',1,95,94', ',1,94', ',93', 'active'),
(5, 'report', ',1,95,97,94', ',1,94', ',1,94', '', 'active'),
(6, 'products_product_list', '1,,95,94', ',1,94', ',1,94', '', 'active'),
(8, 'products_add_product', ',1,97,94', ',1,95,94', ',1,94', '', 'active'),
(9, 'contacts_customer', ',1,95,94', ',1,93,95,94', ',1,93,95,94', '', 'active'),
(10, 'contacts_supplier', ',1,95,94', ',1,93,95,94', ',1,95,94', '', 'active'),
(11, 'manage_user', ',1,93,94', ',1,94', ',1,94', ',95', 'active'),
(12, 'multiple_store_warehouse', ',1,95,94', ',1,95,94', ',1,94', '', 'active'),
(13, 'accounts', ',1,94', ',1,94', ',1,94', '', 'active'),
(14, 'purchase_return', ',1,94', ',1,94', ',1,94', '', 'active'),
(15, 'sale_return', ',1,94', ',1,94', ',1,94', '', 'active'),
(16, 'stock_transfer', ',1,94', ',1,94', ',1,94', '', 'active'),
(17, 'stock_adjustment', ',1,94', ',1,94', ',1,94', '', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `pos_variations`
--

CREATE TABLE `pos_variations` (
  `variation_id` int(100) NOT NULL,
  `variation_name` varchar(250) DEFAULT NULL,
  `product_id` varchar(100) DEFAULT NULL,
  `sub_product_id` varchar(100) DEFAULT NULL,
  `variation_category_id` varchar(100) DEFAULT NULL,
  `purchase_price` decimal(20,2) DEFAULT NULL,
  `profit_percent` decimal(20,2) DEFAULT NULL,
  `sell_price` decimal(20,2) DEFAULT NULL,
  `store_id` varchar(100) DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `update_at` datetime NOT NULL DEFAULT current_timestamp(),
  `variations_status` enum('active','inactive','cancel') NOT NULL DEFAULT 'active',
  `is_delete` enum('true','false') NOT NULL DEFAULT 'false'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pos_variations_category`
--

CREATE TABLE `pos_variations_category` (
  `variation_category_id` int(100) NOT NULL,
  `variation_category_name` varchar(250) DEFAULT NULL,
  `variation_category_value` varchar(250) DEFAULT NULL,
  `user_id` varchar(100) DEFAULT NULL,
  `store_id` varchar(100) DEFAULT '1',
  `variation_category_status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `is_delete` enum('true','false') NOT NULL DEFAULT 'false',
  `created_at` datetime DEFAULT NULL,
  `update_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pos_warehouse`
--

CREATE TABLE `pos_warehouse` (
  `warehouse_id` int(100) NOT NULL,
  `user_id` int(20) DEFAULT NULL,
  `warehouse_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `warehouse_location` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `warehouse_status` enum('active','deactive') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'active',
  `is_delete` enum('true','false') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'false',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts_chart`
--
ALTER TABLE `accounts_chart`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `chart_name_value` (`chart_name_value`);

--
-- Indexes for table `accounts_chart_category`
--
ALTER TABLE `accounts_chart_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `accounts_chart_sub_category`
--
ALTER TABLE `accounts_chart_sub_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `accounts_transactions`
--
ALTER TABLE `accounts_transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pos_brands`
--
ALTER TABLE `pos_brands`
  ADD PRIMARY KEY (`brand_id`);

--
-- Indexes for table `pos_category`
--
ALTER TABLE `pos_category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `pos_contact`
--
ALTER TABLE `pos_contact`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customer_id` (`contact_id`);

--
-- Indexes for table `pos_invoice`
--
ALTER TABLE `pos_invoice`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pos_payment_method`
--
ALTER TABLE `pos_payment_method`
  ADD PRIMARY KEY (`payment_method_id`),
  ADD UNIQUE KEY `payment_method_value` (`payment_method_value`);

--
-- Indexes for table `pos_product`
--
ALTER TABLE `pos_product`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_id` (`product_id`),
  ADD KEY `brand_id` (`brand_id`),
  ADD KEY `unit_id` (`unit_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `supplier_id` (`supplier_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `pos_product_serial`
--
ALTER TABLE `pos_product_serial`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pos_purchase`
--
ALTER TABLE `pos_purchase`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `purchase_id` (`purchase_id`);

--
-- Indexes for table `pos_register_report`
--
ALTER TABLE `pos_register_report`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pos_return`
--
ALTER TABLE `pos_return`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `return_id` (`return_id`);

--
-- Indexes for table `pos_sales`
--
ALTER TABLE `pos_sales`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pos_setting`
--
ALTER TABLE `pos_setting`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pos_stock`
--
ALTER TABLE `pos_stock`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `stock_id` (`stock_id`);

--
-- Indexes for table `pos_stock_adjustment`
--
ALTER TABLE `pos_stock_adjustment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pos_stock_transfer`
--
ALTER TABLE `pos_stock_transfer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pos_store`
--
ALTER TABLE `pos_store`
  ADD PRIMARY KEY (`store_id`);

--
-- Indexes for table `pos_transactions`
--
ALTER TABLE `pos_transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pos_unit`
--
ALTER TABLE `pos_unit`
  ADD PRIMARY KEY (`unit_id`);

--
-- Indexes for table `pos_user_permission`
--
ALTER TABLE `pos_user_permission`
  ADD PRIMARY KEY (`permission_id`);

--
-- Indexes for table `pos_variations`
--
ALTER TABLE `pos_variations`
  ADD PRIMARY KEY (`variation_id`);

--
-- Indexes for table `pos_variations_category`
--
ALTER TABLE `pos_variations_category`
  ADD PRIMARY KEY (`variation_category_id`);

--
-- Indexes for table `pos_warehouse`
--
ALTER TABLE `pos_warehouse`
  ADD PRIMARY KEY (`warehouse_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts_chart`
--
ALTER TABLE `accounts_chart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `accounts_chart_category`
--
ALTER TABLE `accounts_chart_category`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `accounts_chart_sub_category`
--
ALTER TABLE `accounts_chart_sub_category`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `accounts_transactions`
--
ALTER TABLE `accounts_transactions`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pos_brands`
--
ALTER TABLE `pos_brands`
  MODIFY `brand_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pos_category`
--
ALTER TABLE `pos_category`
  MODIFY `category_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pos_contact`
--
ALTER TABLE `pos_contact`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pos_invoice`
--
ALTER TABLE `pos_invoice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pos_payment_method`
--
ALTER TABLE `pos_payment_method`
  MODIFY `payment_method_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pos_product`
--
ALTER TABLE `pos_product`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pos_product_serial`
--
ALTER TABLE `pos_product_serial`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pos_purchase`
--
ALTER TABLE `pos_purchase`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pos_register_report`
--
ALTER TABLE `pos_register_report`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pos_return`
--
ALTER TABLE `pos_return`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pos_sales`
--
ALTER TABLE `pos_sales`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pos_setting`
--
ALTER TABLE `pos_setting`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pos_stock`
--
ALTER TABLE `pos_stock`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pos_stock_adjustment`
--
ALTER TABLE `pos_stock_adjustment`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pos_stock_transfer`
--
ALTER TABLE `pos_stock_transfer`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pos_store`
--
ALTER TABLE `pos_store`
  MODIFY `store_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pos_transactions`
--
ALTER TABLE `pos_transactions`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pos_unit`
--
ALTER TABLE `pos_unit`
  MODIFY `unit_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pos_user_permission`
--
ALTER TABLE `pos_user_permission`
  MODIFY `permission_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `pos_variations`
--
ALTER TABLE `pos_variations`
  MODIFY `variation_id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pos_variations_category`
--
ALTER TABLE `pos_variations_category`
  MODIFY `variation_category_id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pos_warehouse`
--
ALTER TABLE `pos_warehouse`
  MODIFY `warehouse_id` int(100) NOT NULL AUTO_INCREMENT;