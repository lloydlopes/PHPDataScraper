<?php

/*--------------------------------------------------------------------
This page parses HTML webpages from Yahoo Finance and spits out data 
we want in a neat format that we can store in a database. Specifically , 
we are extracting information from the income statements.

The script gets a list of stock symbols from the database , then loops
through them , one by one , gets the data in table form from Yahoos website
, cleans it , and then inserts it into the database.
----------------------------------------------------------------------*/

class Yahoo {

public function Yahoo(){
//Send in the html parser class from simplehtmldom.sourceforge.net. Great little free script.
require_once('simple_html_dom.php');
require_once('database.class.php');
require_once ('dataretrieval.class.php');
}

function ScrapeYahooData ($limit = 100) {

$dataretrieval = new DataRetrieval();
$con = $dataretrieval->DatabaseConnection();
   
$dataretrieval = new DataRetrieval();
$symbols = $dataretrieval->GetStockIDs($limit);

//If there are no stocks left to process - finish the whole job by setting all shares back to NOT DONE.
if (empty($symbols)) {
	
	$message =  "The entire stock market fundamental data has been downloaded!! Please check Database for confirmation!<br/>
	Run this command on the database before downloading another stock market : UPDATE stocks SET completed=0";
	
	//Clean up any duplicates in the fundamentals table once complete.
	$cleanUpDuplicates="create table tmp like fundamentals;
						alter table tmp add unique (stock_id, PeriodEnding);
						insert IGNORE into tmp select * from fundamentals;
						rename table fundamentals to deleteme, tmp to fundamentals;
						drop table deleteme;";
						
						Database::Run($con,$cleanUpDuplicates);
return $message;
}

    foreach ($symbols as $symbol) {
    
    //Yahoos not a fan of scrapers. A random delay of about 5 seconds to try and override flood control.	
    usleep(rand(200000,450000));	
    $message = $this->ProcessSymbol($symbol , $con);
    echo '<tr><td>'.$message.'</td></tr>';
    flush();
    ob_flush();
  
    }// Close off symbol foreach loop
}


//Functions to manipulate data 

//Function to convert dates from Sep232010 to 23/09/2010
function convertDate() {
$query = "Select PeriodEnding,stock_id FROM fundamentals";
$result = Database::Run($con ,$query) ;

while($row = Database::FetchAssoc($result)){
   
	
//Edited - added semicolon at the End of line.1st and 4th(prev) line
	$period=$row['PeriodEnding'];
	$symbol=$row['stock_id'];

	$day = substr($period, 3,-4);
	$month= substr($period, 0,3);
	$year = substr($period, -4);
	
        $date=$day." ".$month." ".$year." ";
	$date = date('d/m/Y', strtotime($date));
	
	
	$insertDate = "UPDATE fundamentals SET PeriodEnding = $date WHERE PeriodEnding = '$period' AND stock_id = '$symbol'";
	//mysqli_query($con ,$insertDate) ;
    echo $insertDate;
	
}

}

//Scrape the data for a single share on Yahoo Finance.
function ProcessSymbol ($symbol , $con) {

try{

$financialStatements=array(
'http://finance.yahoo.com/q/is?s='.$symbol.'+Income+Statement&annual',
'http://finance.yahoo.com/q/bs?s='.$symbol.'+Balance+Sheet&annual',
'http://finance.yahoo.com/q/cf?s='.$symbol.'+Cash+Flow&annual');

foreach ($financialStatements as $financialStatement) {

//Get the table from Yahoo Finance
$html = file_get_html($financialStatement);
if($html===false) {continue;}

// Yahoos balance sheet table is uniquiely identified by its colspan=2 attribute. WARNING - this may change
// This finds the rows , then sticks the content of the <td> elements in each row into a new array
	
	$theData = array();
	foreach($html->find('table[cellpadding=2] tr') as $row) {
    
	$rowData = array();
	foreach($row->find('td,th') as $cell) {
	 
	$unCleanedNumber= $cell->innertext;
	
	$cleanedNumber= str_replace(array("(",")"),array("-",""),$unCleanedNumber); // Convert (number) to -number
        $cleanedNumber = strip_tags(preg_replace('/[.,\s+]/', '', $cleanedNumber)); // Gets rid of commas and full stops in figures
	$cleanedNumber =  str_replace("&nbsp;", '', $cleanedNumber); //Remove annoying spaces from the html. The html is now clean as a whistle
	
	$rowData[] = $cleanedNumber;
	
	}
	
	 $theData[] = array_values(array_filter($rowData)); //Filter out incorrect keys and blank elements of the arrays. There might be a few
	
	}

    //Sometimes , Yahoo doesnt have info for the share we want. Check for this first.
    if (!empty($theData[1])) {
	$period = array_slice($theData[1] , 1);
	$firstColumn = $theData[1][0];
	
	//This stores the first column of data in the database. Typically , this is the financial year end date. We don't want to do this more
	//than once per year end date (because of the insert) , so it is only executed on the financial statement. Once rows have been inserted they
	//are then updated.
	if ($financialStatement=='http://finance.yahoo.com/q/is?s='.$symbol.'+Income+Statement&annual'){
		
		foreach ($period as $period) {
		$query = "INSERT IGNORE INTO fundamentals ($firstColumn , stock_id) VALUES ('$period','$symbol')";
		Database::Run($con,$query);
		}
	
	}
	

}


// Done putting the contents of rows into arrays.

foreach ($theData as $v1) {
	
	//Some rows contain header data. A simple condition - if the array with data has less than 3 elements its probably a header row
	$rowElements=count($v1);
	if ($rowElements > 2){
	
		//Internal loop for each element in the array that contains one table row
		for ($increment=0; $increment<=$rowElements-1 ; $increment++) {
        
		//This is to create the column names. To be run on its own.
		if ($increment==0) {
			//$query = "ALTER TABLE fundamentals ADD $v1[$increment] VARCHAR(60)";
			//echo $query;
			//mysqli_query($con,$query); 
		    //echo $v1[$increment]; echo "<br/>"; 
		
		}
		
		//print out the figures for that column.
		else { //echo  $v1[$increment]; echo "<br/>";
		$row=$v1[0];
		
		$dateColumn = $theData[1][0];
		$date = $theData[1][$increment];
		$query = "UPDATE fundamentals SET $row = $v1[$increment] WHERE $dateColumn = '$date' AND stock_id = '$symbol' ";
		//echo $v1[$increment]; echo "<br/>";
		Database::Run($con,$query); 
		
		}
		
		
		}
	
	
	
	}


}
	//We now need to clear the memory and unset the html dom class. Otherwise , the script keeps everything in memory and we run out of memory eventually.
	$html->clear();
	$html->__destruct();
	unset($html);
	} //Close off financialStatement foeach loop
	
	$message =  $symbol." is complete"."<br/>";
	
$query = "UPDATE stocks SET completed=1 WHERE stock_id = '$symbol'";
Database::Run($con,$query);
}
//If Yahoo fails us , include another go at this share while waiting another 45 seconds.
    catch(Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "Waiting and trying again...\n";
        flush();
        ob_flush();
        usleep(rand(150000000,250000000));	
        $message = $this->ProcessSymbol($symbol , $con);
        return $message;
    }
    
return $message;
//echo '<pre>';
//$vars = get_defined_vars();
//foreach($vars as $name=>$var)
//{
//    echo '<strong>' . $name . '</strong>: ' . strlen(serialize($var)) . '<br />';
//}


} 

}
//

?>