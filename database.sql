-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jun 23, 2016 at 07:20 AM
-- Server version: 5.7.9
-- PHP Version: 5.6.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fundamentaldata`
--

-- --------------------------------------------------------

--
-- Table structure for table `fundamentals`
--

DROP TABLE IF EXISTS `fundamentals`;
CREATE TABLE IF NOT EXISTS `fundamentals` (
  `row_id` int(10) NOT NULL AUTO_INCREMENT,
  `stock_id` varchar(20) NOT NULL DEFAULT '',
  `Inventory` varchar(60) NOT NULL DEFAULT '',
  `Goodwill` varchar(60) DEFAULT NULL,
  `PeriodEnding` varchar(60) NOT NULL DEFAULT '',
  `CashAndCashEquivalents` varchar(60) DEFAULT NULL,
  `ShortTermInvestments` varchar(60) DEFAULT NULL,
  `NetReceivables` varchar(60) DEFAULT NULL,
  `OtherCurrentAssets` varchar(60) DEFAULT NULL,
  `TotalCurrentAssets` varchar(60) DEFAULT NULL,
  `LongTermInvestments` varchar(60) DEFAULT NULL,
  `PropertyPlantandEquipment` varchar(60) DEFAULT NULL,
  `IntangibleAssets` varchar(60) DEFAULT NULL,
  `AccumulatedAmortization` varchar(60) DEFAULT NULL,
  `OtherAssets` varchar(60) DEFAULT NULL,
  `DeferredLongTermAssetCharges` varchar(60) DEFAULT NULL,
  `TotalAssets` varchar(60) DEFAULT NULL,
  `AccountsPayable` varchar(60) DEFAULT NULL,
  `OtherCurrentLiabilities` varchar(60) DEFAULT NULL,
  `TotalCurrentLiabilities` varchar(60) DEFAULT NULL,
  `LongTermDebt` varchar(60) DEFAULT NULL,
  `OtherLiabilities` varchar(60) DEFAULT NULL,
  `DeferredLongTermLiabilityCharges` varchar(60) DEFAULT NULL,
  `MinorityInterest` varchar(60) DEFAULT NULL,
  `NegativeGoodwill` varchar(60) DEFAULT NULL,
  `TotalLiabilities` varchar(60) DEFAULT NULL,
  `MiscStocksOptionsWarrants` varchar(60) DEFAULT NULL,
  `RedeemablePreferredStock` varchar(60) DEFAULT NULL,
  `PreferredStock` varchar(60) DEFAULT NULL,
  `CommonStock` varchar(60) DEFAULT NULL,
  `RetainedEarnings` varchar(60) DEFAULT NULL,
  `TreasuryStock` varchar(60) DEFAULT NULL,
  `CapitalSurplus` varchar(60) DEFAULT NULL,
  `OtherStockholderEquity` varchar(60) DEFAULT NULL,
  `TotalStockholderEquity` varchar(60) DEFAULT NULL,
  `NetTangibleAssets` varchar(60) DEFAULT NULL,
  `NetIncome` varchar(60) DEFAULT NULL,
  `Depreciation` varchar(60) DEFAULT NULL,
  `AdjustmentsToNetIncome` varchar(60) DEFAULT NULL,
  `ChangesInAccountsReceivables` varchar(60) DEFAULT NULL,
  `ChangesInLiabilities` varchar(60) DEFAULT NULL,
  `ChangesInInventories` varchar(60) DEFAULT NULL,
  `ChangesInOtherOperatingActivities` varchar(60) DEFAULT NULL,
  `TotalCashFlowFromOperatingActivities` varchar(60) DEFAULT NULL,
  `CapitalExpenditures` varchar(60) DEFAULT NULL,
  `Investments` varchar(60) DEFAULT NULL,
  `OtherCashflowsfromInvestingActivities` varchar(60) DEFAULT NULL,
  `TotalCashFlowsFromInvestingActivities` varchar(60) DEFAULT NULL,
  `DividendsPaid` varchar(60) DEFAULT NULL,
  `SalePurchaseofStock` varchar(60) DEFAULT NULL,
  `NetBorrowings` varchar(60) DEFAULT NULL,
  `OtherCashFlowsfromFinancingActivities` varchar(60) DEFAULT NULL,
  `TotalCashFlowsFromFinancingActivities` varchar(60) DEFAULT NULL,
  `EffectOfExchangeRateChanges` varchar(60) DEFAULT NULL,
  `ChangeInCashandCashEquivalents` varchar(60) DEFAULT NULL,
  `TotalRevenue` varchar(60) DEFAULT NULL,
  `CostofRevenue` varchar(60) DEFAULT NULL,
  `GrossProfit` varchar(60) DEFAULT NULL,
  `ResearchDevelopment` varchar(60) DEFAULT NULL,
  `SellingGeneralandAdministrative` varchar(60) DEFAULT NULL,
  `NonRecurring` varchar(60) DEFAULT NULL,
  `Others` varchar(60) DEFAULT NULL,
  `TotalOperatingExpenses` varchar(60) DEFAULT NULL,
  `OperatingIncomeorLoss` varchar(60) DEFAULT NULL,
  `EarningsBeforeInterestAndTaxes` varchar(60) DEFAULT NULL,
  `InterestExpense` varchar(60) DEFAULT NULL,
  `IncomeBeforeTax` varchar(60) DEFAULT NULL,
  `IncomeTaxExpense` varchar(60) DEFAULT NULL,
  `NetIncomeFromContinuingOps` varchar(60) DEFAULT NULL,
  `DiscontinuedOperations` varchar(60) DEFAULT NULL,
  `ExtraordinaryItems` varchar(60) DEFAULT NULL,
  `EffectOfAccountingChanges` varchar(60) DEFAULT NULL,
  `OtherItems` varchar(60) DEFAULT NULL,
  `PreferredStockAndOtherAdjustments` varchar(60) DEFAULT NULL,
  `NetIncomeApplicableToCommonShares` varchar(60) DEFAULT NULL,
  `CapitalEmployed` decimal(50,0) DEFAULT NULL,
  `ReturnOnCapitalEmployed` decimal(50,2) DEFAULT NULL,
  `FinalEarningsPerShare` decimal(50,2) DEFAULT NULL,
  `IssuedShares` varchar(50) DEFAULT NULL,
  `DividendDateQuarter1` varchar(60) NOT NULL,
  `DividendDateQuarter2` varchar(60) NOT NULL,
  `DividendDateQuarter3` varchar(60) NOT NULL,
  `DividendDateQuarter4` varchar(60) NOT NULL,
  `DividendQuarter1` decimal(50,2) NOT NULL,
  `DividendQuarter2` decimal(50,2) NOT NULL,
  `DividendQuarter3` decimal(50,2) NOT NULL,
  `DividendQuarter4` decimal(50,2) NOT NULL,
  `ReturnOnShareholdersEquity` decimal(50,2) DEFAULT NULL,
  `InterimEarningsPerShare` decimal(50,2) DEFAULT NULL,
  `FinalDividend` decimal(50,2) DEFAULT NULL,
  `InterimDividend` decimal(50,2) DEFAULT NULL,
  `FinalDividendDeclareDate` varchar(30) DEFAULT NULL,
  `InterimDividendDeclareDate` varchar(30) NOT NULL,
  `completed` int(1) NOT NULL,
  UNIQUE KEY `stock_id` (`stock_id`,`PeriodEnding`),
  KEY `row_id` (`row_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2403 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stocks`
--

DROP TABLE IF EXISTS `stocks`;
CREATE TABLE IF NOT EXISTS `stocks` (
  `stock_id` varchar(60) NOT NULL,
  `completed` int(1) NOT NULL,
  UNIQUE KEY `stock_id` (`stock_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
