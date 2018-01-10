<html>
    
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

<div class="col-md-8">  
    
    <form action ="" id="phase1"> <input type="hidden" name="phase" value="1"> </form>
    <form action ="" id="phase2"> <input type="hidden" name="phase" value="2"> </form>
    <form action ="" id="phase3"> <input type="hidden" name="phase" value="3"> </form>
    <form action ="" id="phase4"> <input type="hidden" name="phase" value="4"> </form>
    
    <button type="submit" form="phase1"  class="btn btn-primary">Phase 1 - Fundamentals From Yahoo</button>
    <button type="submit" form="phase2" class="btn btn-primary">Phase 2 - Process Dates </button>
    <button type="submit" form="phase3" class="btn btn-primary">Phase 3 - Dividends From Morningstar </button>
    <button type="submit" form="phase4" class="btn btn-primary">Phase 4 - Quarterly Dividends From Yahoo</button>
    
<?php

if (isset($_GET['phase'])) {
    $phase = $_GET['phase'];

    if ($phase ==1){
    require_once('classes/yahoo.class.php');
    echo "<table class='table'><th>Status</th>";
    $yahoo = new Yahoo();
    $yahoo->ScrapeYahooData();
    echo "</table>";
    }
    
    if ($phase ==2){
    require_once('classes/datamanipulation.class.php');
    $dataClass = new DataManipulation();
    $dataClass->ConvertAllDates();
    echo '<h4>Date Conversion Complete. Please Check Database</h4>';
    }
    
    if ($phase ==3){
    require_once('classes/morningstar.class.php');
    $morningstar = new Morningstar();
    echo "<table class='table'><th>Status</th>";
    $morningstar->DownloadMorningstarData(100000);
    echo "</table>";
    }
    
    if ($phase ==4){
    require_once('classes/yahooquarterly.class.php');
    $yahooQuarterly = new YahooQuarterly();
    echo "<table class='table'><th>Status</th>";
    $yahooQuarterly->ScrapeQuarterlyData();
    echo "</table>";
    }
    
    
    
}


?>
</div>
</html>
