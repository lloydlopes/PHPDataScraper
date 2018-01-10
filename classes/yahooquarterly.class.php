<?php

/*-------------------------------------------------------------------------------
Step 5

This page downloads quarterly dividend data from Yahoo finance. It then takes
the output and transfers it into the correct rows in a MYSQL database.

yahooScraper.php and convertDate.php MUST be run before this script is run.
--------------------------------------------------------------------------------*/
class YahooQuarterly {
    
function YahooQuarterly (){
//Send in the html parser class from simplehtmldom.sourceforge.net. Great little free script.
require_once('simple_html_dom.php');
require_once('database.class.php');


}

function ScrapeQuarterlyData(){
//Connect to the database
$db = new Database();
$con = $db->Connect();
    
// Build the list of shares from the database. We are just selecting the ones that havent been processsed.
$query = "Select stock_id,PeriodEnding FROM fundamentals WHERE completed=0";
$result = Database::Run($con ,$query) ;


while ($entry = Database::FetchAssoc($result)) {

$message = $this->ScrapeWebsiteData($entry);
echo '<tr><td>'.$message.'</td></tr>';
}
}


function ScrapeWebsiteData($entry){   
//Connect to the database
$db = new Database();
$con = $db->Connect();

try{

$symbol = $entry['stock_id'];
$period = $entry['PeriodEnding'];

//Format date of financial year end , and get it a year back , three months back etc
$yearEndDate = str_replace('/', '-', $period);
$yearEnd=strtotime($yearEndDate);

$yearAgo= strtotime('-13 months', strtotime($yearEndDate));
$threeQuartersAgo= strtotime('-10 months', strtotime($yearEndDate));
$twoQuartersAgo= strtotime('-7 months', strtotime($yearEndDate));
$quarterAgo= strtotime('-4 months', strtotime($yearEndDate));


//Split it up 
$yearEndDay= date('d', strtotime(($yearEndDate)));
$yearEndMonth=date('m', strtotime(($yearEndDate)));
$yearEndYear=date('Y', strtotime(($yearEndDate)));


$host = "http://ichart.finance.yahoo.com/x";
$requestUrl = $host."?s=".$symbol."&d=".$yearEndMonth."&e=".$yearEndDay."&f=".$yearEndYear."&g=v&y=0&z=3000";
//ichart.finance.yahoo.com/x?s=AAPL&a=00&b=2&c=1962&d=04&e=25&f=2013&g=v&y=0&z=4 FOR DIVIDENDS
//echo $requestUrl;

// Pull data (download CSV as file)
$filesize=100000;
$handle = @fopen($requestUrl, "r");
if($handle===FALSE)  {$message = $symbol.' FAILED'; return $message;}
$raw = fread($handle, $filesize);
fclose($handle);

// Split results, trim way the extra line break at the end
$quotes = explode("\n",trim($raw));


//Dont insert more than 4 dividend quarters
$count=4;
 
foreach($quotes as $quoteraw) {
$quoteraw = str_replace(", I", " I", $quoteraw);
$quote = explode(",", $quoteraw);


if (isset($quote[1])){
$dividendDate=$quote[1];
}

if(empty($quote[2]) || !is_numeric($quote[2])) {
	$dividend=0;
}
else 
	$dividend=$quote[2];

//Get the financial quarter date
$dividendDay= date('d', strtotime(($dividendDate)));
$dividendMonth=date('m', strtotime(($dividendDate)));
$dividendYear=date('Y', strtotime(($dividendDate)));


$isThisaDividend=trim($quote[0]);

    
	//Divide the year up into quarters
	if ($count>=1 && strtotime($dividendDate) >= $quarterAgo && strtotime($dividendDate) <= $yearEnd && $isThisaDividend=='DIVIDEND') {
	
		$dividendDateColumn="DividendDateQuarter4";
		$dividendColumn="DividendQuarter4";
		$dividendDate = date('d/m/Y', strtotime($dividendDate));
		$insertDividends = "UPDATE fundamentals SET $dividendDateColumn='$dividendDate' , $dividendColumn='$dividend'  WHERE stock_id='$symbol' AND PeriodEnding='$period'";
		
			//echo $insertDividends."<br/>";
			Database::Run($con ,$insertDividends) ;
			$count--;
			}
			
	if ($count>=1 && strtotime($dividendDate) >= $twoQuartersAgo && strtotime($dividendDate) <= $quarterAgo && $isThisaDividend=='DIVIDEND') {
	
		$dividendDateColumn="DividendDateQuarter3";
		$dividendColumn="DividendQuarter3";
		$dividendDate = date('d/m/Y', strtotime($dividendDate));
		$insertDividends = "UPDATE fundamentals SET $dividendDateColumn='$dividendDate' , $dividendColumn='$dividend'  WHERE stock_id='$symbol' AND PeriodEnding='$period'";
		
			//echo $insertDividends."<br/>";
			Database::Run($con ,$insertDividends) ;
			$count--;
			}
			
	if ($count>=1 && strtotime($dividendDate) >= $threeQuartersAgo && strtotime($dividendDate) <= $twoQuartersAgo && $isThisaDividend=='DIVIDEND') {
	
		$dividendDateColumn="DividendDateQuarter2";
		$dividendColumn="DividendQuarter2";
		$dividendDate = date('d/m/Y', strtotime($dividendDate));
		$insertDividends = "UPDATE fundamentals SET $dividendDateColumn='$dividendDate' , $dividendColumn='$dividend'  WHERE stock_id='$symbol' AND PeriodEnding='$period'";
		
			//echo $insertDividends."<br/>";
			Database::Run($con ,$insertDividends) ;
			$count--;
			}
			
	if ($count>=1 && strtotime($dividendDate) >= $yearAgo && strtotime($dividendDate) <= $threeQuartersAgo && $isThisaDividend=='DIVIDEND') {
	
	$dividendDateColumn="DividendDateQuarter1";
		$dividendColumn="DividendQuarter1";
		$dividendDate = date('d/m/Y', strtotime($dividendDate));
		$insertDividends = "UPDATE fundamentals SET $dividendDateColumn='$dividendDate' , $dividendColumn='$dividend'  WHERE stock_id='$symbol' AND PeriodEnding='$period'";
		
			//echo $insertDividends."<br/>";
			Database::Run($con ,$insertDividends) ;
			$count--;
			}


}

	$finishShare = "UPDATE fundamentals SET completed=1  WHERE stock_id='$symbol' AND PeriodEnding='$period'";
	Database::Run($con ,$finishShare) ;
        $message = $symbol.' is complete';
        return $message;
} 
catch(Exception $e){
     
    //Yahoos not a fan of scrapers. A random delay of about 5 seconds to try and override flood control.	
    usleep(rand(200000,450000));	
    $message = $this->ScrapeWebsiteData($data);
    return $message;
    flush();
    ob_flush();
}



}

}
?>