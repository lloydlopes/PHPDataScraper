<?php

/*-------------------------------------------------------------------------------
Step 3

Once you have got the fundamental data downloaded from yahooScraper.php , you 
need to modify the dates from 21Sep2012 to 21/09/2012. This script does just that.

Remember to modify database connection string to your database.
--------------------------------------------------------------------------------*/

//$dataClass = new DataManipulation();
//$dataClass->ConvertAllDates();

class DataManipulation {
    
    function DataManipulation(){
    require_once('database.class.php');
   
    }
    
function ConvertAllDates (){
 //Connect to the database
    $db = new Database();
    $con = $db->Connect();
    
//Send in the html parser class from simplehtmldom.sourceforge.net. Great little free script.
require_once('simple_html_dom.php');
require_once('dataretrieval.class.php');

$dataRetrievalClass = new DataRetrieval();

$query = "Select PeriodEnding,stock_id FROM fundamentals WHERE completed = 0";
$result = $dataRetrievalClass->GetDataIntoArray($query);

foreach($result as $row){
	
    //Edited - added semicolon at the End of line.1st and 4th(prev) line
    $period=strtolower(trim($row['PeriodEnding']));
    $date = $this->ConvertDateIfNecessary($period);
    $symbol=$row['stock_id'];

    //Check if the row already exists with this date. If it does , delete the original row.
    $checkDateRow = "SELECT * FROM fundamentals WHERE stock_id = '$symbol' AND (PeriodEnding = '$date' OR PeriodEnding = '$period')";
    $output = Database::Run($con,$checkDateRow);
    $numberOfRows = Database::GetNumberResults($output);


    $deleteRow = "DELETE FROM fundamentals WHERE stock_id = '$symbol' AND PeriodEnding = '$period'";
    if ($numberOfRows>1) {
            Database::Run($con ,$deleteRow) ;
    } 

    else  {
    $insertDate = "UPDATE fundamentals SET PeriodEnding = '$date' , completed = '1' WHERE PeriodEnding = '$period' AND stock_id = '$symbol'";
    Database::Run($con ,$insertDate) ;
    }
	
}
}
    function ConvertDateIfNecessary ($period){
            //If the date has letters in it , then it hasn't been converted yet. Convert the date.
            if (preg_match('/[A-Za-z]/', $period)) {

            $day = substr($period, 3,-4);
            $month= substr($period, 0,3);
            $year = substr($period, -4);

            $date=$day." ".$month." ".$year." ";
            $date = date('d/m/Y', strtotime($date));

            }

            //else the date has been converted. Leave it alone.
            else {$date = $period;}
            
            return $date;
           
    }
}//End Class

?>