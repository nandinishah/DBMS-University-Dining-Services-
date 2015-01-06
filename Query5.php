<html>
<body>

<h2>Student @Columbia</h2>

<?php
    require_once "common2.php";
    
    session_start();
    $did = $_SESSION["did"];
    $mtid = $_SESSION["mtid"];
    $mdid = $_SESSION["mdid"];
    $pid = $_SESSION["pid"];
    
    
    echo "<br><b>Status of subscription</b>";
    $myQuery4a = "SELECT Count(P.pid) AS Freq FROM pays_by P WHERE P.pid = '".$pid."' and P.ptype = 'Dining Plan' GROUP BY P.pid";
    $res4a = mysqli_query($conn, $myQuery4a);
    while ($row = mysqli_fetch_array($res4a, MYSQLI_NUM)){
        //printf("%s \n", $row[0]);
        $totalmeals = $row[0];
    }
    
    $totaldd = 0;
    $myQuery4b = "SELECT SUM(S.mprice) FROM serves S, pays_by PB WHERE PB.pid = '".$pid."' and PB.ptype = 'Dining Dollar' and PB.mtid = S.mtid and PB.did = S.did GROUP BY PB.pid";
    $res4b = mysqli_query($conn, $myQuery4b);
    while ($row = mysqli_fetch_array($res4b, MYSQLI_NUM)){
        //printf("Dining Dollars spent till now: %s \n", $row[0]);
        $totaldd = $row[0];
    }
    
    $myQuery4c = "SELECT E.until, D.meals, D.bonus, D.pname, D.price, D.dining_dollar FROM enrolled_in E, dining_plan D WHERE E.pid = '".$pid."' and E.pname = D.pname";
    $res4c = mysqli_query($conn, $myQuery4c);
    $allocateddd = 0; $allocatedmeals = 0; $avgprice = 0;
    while ($row = mysqli_fetch_array($res4c, MYSQLI_NUM)){
        //printf("%s %s \n", $row[0], $row[1]);
        $allocateddd = $row[5];
        $allocatedmeals = $row[1]+$row[2];
        echo "<br>Dining Plan Name: ".$row[3];
        echo "<br>Valid Until: ".$row[0];
        echo "<br>Total Meals Allocated: ".$allocatedmeals;
        echo "<br>Total Dining Dollars Allocated: ".$row[5];
        //printf("Dining Dollars spent till now: %s \n", $totaldd);
        $avgprice = ($row[4]-$row[5])/$allocatedmeals;
        //$until = $row[0];
        echo"<br>"; }
    
    $pendingmeals = $allocatedmeals-$totalmeals;
    echo "<br>Pending meals = ".$pendingmeals;
    //echo "<br>Avg price = ".$avgprice;
    echo "<br>Pending Dining Dollars = ";
    $pendingdd = $allocateddd - $totaldd;
    echo $pendingdd;

    
    
    $myQuery5b = "SELECT S.mprice FROM serves S WHERE S.did = '".$did."' and S.mtid = '".$mtid."' and S.mdid = '".$mdid."'";
    $res5b = mysqli_query($conn, $myQuery5b);
    while ($row = mysqli_fetch_array($res5b, MYSQLI_NUM)){
        echo "<br>";
        printf("Meal Price: %s \n", $row[0]);
        $regprice = $row[0];
        $regpriceTax = $regprice * 8.875/100;
    }
    
    
    if ($regprice > $avgprice and $pendingmeals > 0) { echo "<br><br><b>Recommended method of payment is Dining Plan.<br></b>"; }
    elseif ($regprice < $avgprice and $pendingdd > 0) { echo "<br><br><b>Recommended method of payment is Dining Dollars.<br></b>"; }
    else { echo "<br><br><br><b>Recommended method of payment is Cash/Credit.<br></b>"; }
    
    

    
    
    
    
    mysqli_close($conn);
    ?>


</body>
</html>