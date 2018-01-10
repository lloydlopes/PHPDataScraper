<?php

/* LLOYD LOPES
 * This is a test class which tests the data retrieval functions of our code.
 * This is comprised of two areas - the data retrieved from the database and
 * the data retrieved from external websites or APIs.
 * 
 * If these tests fail at any point , we are notified that the foreign APIs have 
 * changed or that the data or data structure in our database is no longer compatible
 * with the code.
 */
use phpunit\framework\TestCase;

class RetrievalTest extends TestCase
{
    function  RetrievalTest () {
        require_once('/../classes/datamanipulation.class.php');
        require_once('/../classes/dataretrieval.class.php');
        require_once('/../classes/yahoo.class.php');
        require_once('/../classes/morningstar.class.php');
    }
    // ...
    public function testDateConversion()
    {
        // Arrange
        $a = new DataManipulation();
        $date = 'Oct312015';
        // Act
        $b = $a->ConvertDateIfNecessary($date);

        // Assert
        $this->assertEquals('31/10/2015', $b);
    }
    
    
    public function testDataRetrieval ()
    {
        $class = new DataRetrieval();
        $query = 'SELECT * FROM `Fundamentals` LIMIT 5';
        $result = $class->GetDataIntoArray($query);
        $this->assertEquals(5, count($result));
    }
    
    public function testGetStockIDs (){
    $dataretrieval = new DataRetrieval();
    $limit=1;
    $symbols = $dataretrieval->GetStockIDs($limit);
    $this->assertEquals($limit, count($symbols));
    }
    
    //Results of this test will appear in the database.
    public function testScrapeYahooData (){
    $yahoo = new Yahoo();
    $limit=1;
    $yahoo->ScrapeYahooData($limit);
    $this->assertEquals(1, 1);
    }
    
    public function testMorninstarData (){
    $morningStar = new Morningstar();
    $symbol = 'RR.L';
    $marketID = 2;
    $csv = $morningStar->GetWebsiteData($symbol, $marketID);
    $this->assertEquals(1, 1);
    }
    
    
    public function testGetUnfinishedFundamentalRows (){
        $dataClass = new DataRetrieval();
        $rows = $dataClass->GetUnfinishedFundamentalRows();
        $this->assertGreaterThan(1, count($rows));
    }
    
    
    
    

    // ...
}


?>


