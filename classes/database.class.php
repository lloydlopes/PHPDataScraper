<?php

class Database{
    
    //Change these variables to their correct settings for the market you are trying to download.
    const MARKET = 4; // 1=JSE , 2=LSE , 3=NASDAQ , 4=NYSE , 5=ASX
    const DATABASE = 'fundamentaldata'; // jse , lse , nyse , nasdaq , asx
    
    const HOSTNAME = 'localhost';
    const USERNAME = 'root';
    const PASSWORD = '';
    
    //Make a connection to the MYSQL database of choice
    function Connect(){
        $connection = mysqli_connect(self::HOSTNAME,self::USERNAME,self::PASSWORD);
        mysqli_select_db($connection , self::DATABASE);
        
        return $connection;
    }
    
     //Run a standard MYSQL command and process through database
     static function Run($query, $connection){
    return mysqli_query($query, $connection);
    }
    
    //Get the total number of rows returned from a MYSQL result set.
    static function GetNumberResults($result){
        return mysqli_num_rows($result);
    }
    
    //Return an array from the MYSQL object filled with data.
    static function FetchAssoc($result){
        return mysqli_fetch_assoc($result);
    }
    
    
    //Prints error information to screen for debugging from just about any source , array , string etc.
    static function out($var){
	echo "<pre>" . print_r($var, true) . "</pre>";
    }
    
}

?>