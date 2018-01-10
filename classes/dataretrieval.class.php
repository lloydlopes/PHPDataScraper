<?php

class DataRetrieval {

    //Constructor to get all the required info for the class
    function DataRetrieval (){
        require_once('database.class.php');
     
    }
   
    public function DatabaseConnection (){
   //Connect to the database
   $db = new Database();
   $con = $db->Connect();
   return $con;
    }
    
    /*
     * Get data from a query into an array and return the array
     */
    function GetDataIntoArray ($query){
  
    $array = array();
    $con = $this->DatabaseConnection();
    $result = Database::Run($con ,$query) ;
    
    while($row = Database::FetchAssoc($result)){
    // add each row returned into an array
    $array[] = $row;
    }
    
    return $array;
    }
    
    //Get stock IDs out of the database.
    function GetStockIDs ($limit = 100) {
    $con = $this->DatabaseConnection();
   
    // Build the list of shares from the database. We are just selecting the ones that havent been processsed.
    $query = "Select stock_id FROM stocks WHERE completed = 0 LIMIT 0,".$limit;
    $result = Database::Run($con ,$query) ;

    while ($entry = Database::FetchAssoc($result)) {
       $symbols[] = $entry['stock_id'];
    }
    
    return $symbols;
    }
    
    //Get only the rows from the database that the calling function has not yet completed work on.
    public function GetUnfinishedFundamentalRows (){
    $con = $this->DatabaseConnection();
    $query = "Select stock_id FROM fundamentals WHERE completed = 0";
    $result = Database::Run($con ,$query) ;
    
    while ($entry = Database::FetchAssoc($result)) {
       $symbols[] = $entry['stock_id'];
    }
    
    return $symbols;
    }
}

?>