<?php

/*-------------------------------------------------------------------------------
This page downloads fundamental yearly fundamental data from Morning Star finance. It then takes
the output and transfers it into the right row in a MYSQL database. This is designed to add
to the existing Yahoo Finance data that should already be in the database.

Set completed = 0 in the stocks table before running this script.

DEV : 2010 in some cases is not processed - investigate.
--------------------------------------------------------------------------------*/
class Morningstar {
    
function Morningstar (){
//Send in the html parser class from simplehtmldom.sourceforge.net. Great little free script.
require_once('simple_html_dom.php');
require_once('database.class.php');
require_once('dataretrieval.class.php');

}

function DownloadMorningstarData ($limit = 10000) {
//Connect to the database
$db = new Database();
$con = $db->Connect();

$dataClass = new DataRetrieval();
$symbols =  $dataClass->GetUnfinishedFundamentalRows($limit);

foreach ($symbols as $symbol) {
	
    if (empty($symbols)) {

    echo "The entire stock market fundamental data has been downloaded!! Please check Database for confirmation!<br/>
    Run this command on the database before downloading another stock market : UPDATE stocks SET completed=0";

    exit;
    }

//Sleep for a bit so as not to arouse suspicion by leeching...
usleep(rand(200000,450000)); 

$marketID = Database::MARKET;
$total = $this->GetWebsiteData($symbol, $marketID);

if (count($total)>3) {

	foreach($total as $row) {

	//Put each cell into a variable - eg Dividend into variable
	if (empty($row[1])) $date='NODATE'; else {$date=$row[1]."-28";}
	if (empty($row[8])) $dividend=0; else {$dividend=$row[8];}
	if (empty($row[7])) $eps=0; else {$eps=$row[7];}
	
	//The oustanding shares are given in millions. Multiple by 1000000 to get actual figure.
	$issuedShares=$row[10] *1000000;
	$netIncome = $row[6] *1000;
	$totalRevenue = $row[2] *1000;

        //Format the date of the row containing EPS and Dividends
	$dateRowDate = trim(str_replace('-', '/', $date));
	$dateRowMonth=date('m', strtotime(($dateRowDate)));
	$dateRowYear=date('Y', strtotime(($dateRowDate)));
	$dateRowDate = date('d/m/Y', strtotime(($dateRowDate)));

	//echo $date." ".$eps." ".$dividend."<br/>";

	$getFundamentalDate = "Select stock_id,PeriodEnding FROM fundamentals WHERE stock_id='$symbol'";
	$getPeriodEnding = Database::Run($con ,$getFundamentalDate) ;

//Get the array cleaned out for new data
unset($avoidYears);
$avoidYears = array();

//Load up an array with year end values eg 2012 - this is important for using INSERT instead of UPDATE statements
while ($entry = Database::FetchAssoc($getPeriodEnding)) {
		$entry = str_replace('/', '-', $entry['PeriodEnding']); 
	   $avoidYears[] = date('Y', strtotime(($entry)));
}

//Add a year so that we dont go beyond the last financial year of any row
array_push($avoidYears, "2012");

$result1 = Database::Run($con ,$getFundamentalDate) ;

while ($entry = Database::FetchAssoc($result1)) {

$symbolFund = $entry['stock_id'];
$periodFund = $entry['PeriodEnding'];

$yearEndDate = str_replace('/', '-', $periodFund);
$yearEndYear=date('Y', strtotime(($yearEndDate)));



if ($symbol==$symbolFund && $yearEndYear==$dateRowYear &&  is_numeric($eps)) {
	
	$insertData = "UPDATE fundamentals SET FinalEarningsPerShare='$eps',FinalDividend='$dividend',IssuedShares='$issuedShares'   WHERE stock_id='$symbol' AND PeriodEnding='$periodFund' ";
	Database::Run($con,$insertData);

	//echo $insertData."<br/>";
}

else {
	
	if ($symbol==$symbolFund && $dateRowDate!='NODATE' && $dateRowDate!='TTM-28' && $dateRowDate!='01/01/1970' && !in_array($dateRowYear, $avoidYears) &&  is_numeric($eps)) {
		
			$insertRow = "INSERT IGNORE INTO fundamentals (stock_id,PeriodEnding,FinalEarningsPerShare , FinalDividend,IssuedShares, NetIncome,TotalRevenue,FinalDividendDeclareDate) VALUES ('$symbol','$dateRowDate','$eps','$dividend','$issuedShares','$netIncome','$totalRevenue','$dateRowDate')";
			//echo $insertRow."<br/>";
		Database::Run($con,$insertRow);

	}
}

}

	}

$finishShare = "UPDATE fundamentals SET completed=1 WHERE stock_id='$symbol'";
Database::Run($con,$finishShare);
  echo '<tr><td>'.$symbol.' completed!</td></tr>';
    flush();
    ob_flush();

	} 
  
}

}


//Functions

//Switches columns and rows in an array.
function transpose($array) {
$transposed_array = array();
if ($array) {
foreach ($array as $row_key => $row) {
if (is_array($row) && !empty($row)) { //check to see if there is a second dimension
foreach ($row as $column_key => $element) {
$transposed_array[$column_key][$row_key] = $element;
}
}
else {
$transposed_array[0][$row_key] = $row;
}
}
return $transposed_array;
}
}

//Returns an array filled with excel data. Data is company fundmenals from the morninstar site.
function GetWebsiteData ($symbol , $marketID) {
//TRICKY....BE CAREFUL WITH THIS....
//This is the new format URL which morningstar is introducing. I suspect it will ultimately replace the old URLS (above) , and do a better job of determining the location of a share.
//Right now , the above URLS sometimes have share names (for the LSE) which have a . (full stop) appended to to the end of the share name to differentiate them from shares of the same code on other markets. IE : RR. instead of RR
//The above URLS will get the wrong share , whereas the below URL will return no share at all. This allows us to check whether the share needs to have a . after it or not.
try {
if($marketID == 2){
$flag = true;
$checker = "http://financials.morningstar.com/ajax/ReportProcess4CSV.html?&t=XLON:".substr($symbol,0, -2)."&region=gbr&culture=en-US&ops=clear&cur=&reportType=is&period=12&dataType=A&order=asc&columnYear=5&curYearPart=1st5year&rounding=3&view=raw&r=50167&denominatorView=raw&number=3";
if(file_get_contents($checker) == false) {$flag = false; $symbol.=".";}
}

//For USD Currency Markets
if ($marketID == 3) $host = "http://financials.morningstar.com/ajax/exportKR2CSV.html?t=XNAS:".$symbol;
if ($marketID == 4) $host = "http://financials.morningstar.com/ajax/exportKR2CSV.html?t=XNYS:".$symbol;
//For the Australian Dollar Market
if ($marketID == 5) $host = "http://financials.morningstar.com/ajax/exportKR2CSV.html?t=XASX:".substr($symbol,0, -3)."&region=AUS&culture=en-US";
//For the UK Market
if ($marketID == 2) $host = "http://financials.morningstar.com/ajax/exportKR2CSV.html?t=XLON:".substr($symbol,0, -2)."&region=GBR&culture=en-US";

$requestUrl = $host;  

//echo $requestUrl."<br/>";
// Pull data (download CSV as file)

//Get the array cleaned out for new data
unset($total);
$total = array(); 

//Fill the array with excel data 
$maxRows=35;
$firstline = true;
if (($handle = fopen($requestUrl, "r")) !== FALSE) {
 for ($row=0; $row<$maxRows ; $row++) {
   if (($raw = fgetcsv($handle, 1000, ",")) !== FALSE) {
   
   if ($row!=0 && $row!=1 ) {
   $raw = array_values($raw);
   $total[] = $raw;
   }
   
   
   $row++;
  }
   
 }
  fclose($handle);
}

//Transpose the array - columns as rows and rows as columns to put the date in the row along with figures.
$total=$this->transpose($total);
}

//If Morninstar kicks us off , include another go at this share while waiting another 2-7 minutes in order to not arouse suspicion.
    catch(Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "Waiting and trying again...\n";
        flush();
        ob_flush();
        usleep(rand(150000000,250000000));	
        $total = $this->GetWebsiteData($symbol , $marketID);
        return $total;
    }
    
return $total;
}
} //End Morningstar Class

?>