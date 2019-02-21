********************************************************************************
Copyright Lloyd Lopes 2011-2018
>>THESE FILES ARE NOW DEFUNCT. THE CODE NO LONGER WORKS AND IS FOR REFERENCE ONLY<<
>>IN ADDITION , THIS FILE CONTAINS SOME NON OPTIMAL CODE. A FEW MISSING CODE COMMENTS<<
>>AND SOME STRUCTURE WHICH ISN'T OPTIMAL. <<
-------------------------------------------------------------------------------------

Instructions for downloading the fundamentals data from Yahoo Finance.

The scripts contained in this folder scrape data from Yahoo Finance - finance.yahoo.com
Specifically , items from the balance sheet , income statement and cash flow statement
for each stock are looked at. 

The scripts loop through each stock , attempting to parse the html pages that are presented and 
extract , clean and accuracy-check the data. The data is then stored in a MYSQL database.

The order here MUST be followed. Failure to do so will mean corrupt figures
********************************************************************************

1. Create a database in phpmyadmin and import the sql data structure file. Truncate stocks table and fill with stock ID's from eoddata.com and set completed =0
2. Open database.class.php and edit the database connection at the top of file
3. Open the index.php in a web browser (You'll need wamp with unlimited execution time if running locally). Run this first phase - yahoo fundamentals. Its designed to be redundant , so it carries on any failure.
4. Once complete , BACKUP THE RESULTS because dateconverter sucks.Set completed =0 in the fundamentals table,  then click the Phase 2- convert date button. This takes 5 minutes to complete.
5. Once complete ,  set completed=0 in the fundamentals table. Then run phase 3 - Dividends from morningstar. 
6. Once complete ,  run phase 4 - Quarterly Dividend from Yahoo. Make sure to set completed=0 in all rows in fundamental table BEFORE beginning.
7. Once complete , run the below SQL commands on the database.
8. Export in excel through phpmyadmin. Rename columns to these names and put them first in the order of columns :

Share,Dividend_Date,IssuedShares,Current Liabilities,Ordinary Shareholders Interest,Interim Dividends,
Interim  Earnings,Final Dividends,Final Earnings,Capital Employed,AfterTaxProfit

***********************************************************************************

UPDATE fundamentals SET Inventory=Inventory*1000;
UPDATE fundamentals SET Goodwill=Goodwill*1000;
UPDATE fundamentals SET CashAndCashEquivalents=CashAndCashEquivalents*1000;
UPDATE fundamentals SET ShortTermInvestments=ShortTermInvestments*1000;
UPDATE fundamentals SET NetReceivables=NetReceivables*1000;
UPDATE fundamentals SET OtherCurrentAssets=OtherCurrentAssets*1000;
UPDATE fundamentals SET TotalCurrentAssets=TotalCurrentAssets*1000;
UPDATE fundamentals SET LongTermInvestments=LongTermInvestments*1000;
UPDATE fundamentals SET PropertyPlantandEquipment=PropertyPlantandEquipment*1000;
UPDATE fundamentals SET IntangibleAssets=IntangibleAssets*1000;
UPDATE fundamentals SET AccumulatedAmortization=AccumulatedAmortization*1000;
UPDATE fundamentals SET OtherAssets=OtherAssets*1000;
UPDATE fundamentals SET DeferredLongTermAssetCharges=DeferredLongTermAssetCharges*1000;
UPDATE fundamentals SET TotalAssets=TotalAssets*1000;
UPDATE fundamentals SET AccountsPayable=AccountsPayable*1000;
UPDATE fundamentals SET OtherCurrentLiabilities=OtherCurrentLiabilities*1000;
UPDATE fundamentals SET TotalCurrentLiabilities=TotalCurrentLiabilities*1000;
UPDATE fundamentals SET LongTermDebt=LongTermDebt*1000;
UPDATE fundamentals SET OtherLiabilities=OtherLiabilities*1000;
UPDATE fundamentals SET DeferredLongTermLiabilityCharges=DeferredLongTermLiabilityCharges*1000;
UPDATE fundamentals SET MinorityInterest=MinorityInterest*1000;
UPDATE fundamentals SET NegativeGoodwill=NegativeGoodwill*1000;
UPDATE fundamentals SET TotalLiabilities=TotalLiabilities*1000;
UPDATE fundamentals SET MiscStocksOptionsWarrants=MiscStocksOptionsWarrants*1000;
UPDATE fundamentals SET RedeemablePreferredStock=RedeemablePreferredStock*1000;
UPDATE fundamentals SET PreferredStock=PreferredStock*1000;
UPDATE fundamentals SET CommonStock=CommonStock*1000;
UPDATE fundamentals SET RetainedEarnings=RetainedEarnings*1000;
UPDATE fundamentals SET TreasuryStock=TreasuryStock*1000;
UPDATE fundamentals SET CapitalSurplus=CapitalSurplus*1000;
UPDATE fundamentals SET OtherStockholderEquity=OtherStockholderEquity*1000;
UPDATE fundamentals SET TotalStockholderEquity=TotalStockholderEquity*1000;
UPDATE fundamentals SET NetTangibleAssets=NetTangibleAssets*1000;
UPDATE fundamentals SET NetIncome=NetIncome*1000;
UPDATE fundamentals SET Depreciation=Depreciation*1000;
UPDATE fundamentals SET AdjustmentsToNetIncome=AdjustmentsToNetIncome*1000;
UPDATE fundamentals SET ChangesInAccountsReceivables=ChangesInAccountsReceivables*1000;
UPDATE fundamentals SET ChangesInLiabilities=ChangesInLiabilities*1000;
UPDATE fundamentals SET ChangesInInventories=ChangesInInventories*1000;
UPDATE fundamentals SET ChangesInOtherOperatingActivities=ChangesInOtherOperatingActivities*1000;
UPDATE fundamentals SET TotalCashFlowFromOperatingActivities=TotalCashFlowFromOperatingActivities*1000;
UPDATE fundamentals SET CapitalExpenditures=CapitalExpenditures*1000;
UPDATE fundamentals SET OtherCashflowsfromInvestingActivities=OtherCashflowsfromInvestingActivities*1000;
UPDATE fundamentals SET TotalCashFlowsFromInvestingActivities=TotalCashFlowsFromInvestingActivities*1000;
UPDATE fundamentals SET DividendsPaid=DividendsPaid*1000;
UPDATE fundamentals SET SalePurchaseofStock=SalePurchaseofStock*1000;
UPDATE fundamentals SET NetBorrowings=NetBorrowings*1000;
UPDATE fundamentals SET OtherCashFlowsfromFinancingActivities=OtherCashFlowsfromFinancingActivities*1000;
UPDATE fundamentals SET TotalCashFlowsFromFinancingActivities=TotalCashFlowsFromFinancingActivities*1000;
UPDATE fundamentals SET EffectOfExchangeRateChanges=EffectOfExchangeRateChanges*1000;
UPDATE fundamentals SET ChangeInCashandCashEquivalents=ChangeInCashandCashEquivalents*1000;
UPDATE fundamentals SET TotalRevenue=TotalRevenue*1000;
UPDATE fundamentals SET CostofRevenue=CostofRevenue*1000;
UPDATE fundamentals SET GrossProfit=GrossProfit*1000;
UPDATE fundamentals SET ResearchDevelopment=ResearchDevelopment*1000;
UPDATE fundamentals SET SellingGeneralandAdministrative=SellingGeneralandAdministrative*1000;
UPDATE fundamentals SET NonRecurring=NonRecurring*1000;
UPDATE fundamentals SET Others=Others*1000;
UPDATE fundamentals SET TotalOperatingExpenses=TotalOperatingExpenses*1000;
UPDATE fundamentals SET OperatingIncomeorLoss=OperatingIncomeorLoss*1000;
UPDATE fundamentals SET EarningsBeforeInterestAndTaxes=EarningsBeforeInterestAndTaxes*1000;
UPDATE fundamentals SET InterestExpense=InterestExpense*1000;
UPDATE fundamentals SET IncomeBeforeTax=IncomeBeforeTax*1000;
UPDATE fundamentals SET IncomeTaxExpense=IncomeTaxExpense*1000;
UPDATE fundamentals SET NetIncomeFromContinuingOps=NetIncomeFromContinuingOps*1000;
UPDATE fundamentals SET DiscontinuedOperations=DiscontinuedOperations*1000;
UPDATE fundamentals SET ExtraordinaryItems=ExtraordinaryItems*1000;
UPDATE fundamentals SET EffectOfAccountingChanges=EffectOfAccountingChanges*1000;
UPDATE fundamentals SET OtherItems=OtherItems*1000;
UPDATE fundamentals SET PreferredStockAndOtherAdjustments=PreferredStockAndOtherAdjustments*1000;
UPDATE fundamentals SET NetIncomeApplicableToCommonShares=NetIncomeApplicableToCommonShares*1000;


DELETE FROM `fundamentals` WHERE `PeriodEnding` like "%1969";

update fundamentals set ReturnOnShareholdersEquity = NetIncome/TotalStockholderEquity;
update fundamentals set CapitalEmployed = TotalAssets - TotalCurrentLiabilities;
update fundamentals set ReturnOnCapitalEmployed = (TotalAssets - TotalCurrentLiabilities) / TotalStockholderEquity;
update fundamentals set InterimDividend = DividendQuarter1 + DividendQuarter2;
update fundamentals set FinalDividendDeclareDate= DividendDateQuarter4;
update fundamentals set InterimDividendDeclareDate= DividendDateQuarter2;
update fundamentals set InterimEarningsPerShare  = NetIncome / IssuedShares / 2; (for each quarter)

NOTE CapitalSurplus=OrdShareholdersInterest

------------------
Problem with AUS - dont assume these instructions are correct. Verify first.

Net income *1000 too many for only eps and div years
interim earnings per share *1000 too many
return on shareholder equity *1000 too many
