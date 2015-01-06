<html>
<body>

<h2>Student @Columbia</h2>

<?php
    require_once "common2.php";
    
    session_start();
    $pid = $_SESSION["pid"];
    
    //Student - Query 1: Recommend meal based on demographics
    //student has already entered cid
    //Output = Target Calories/day and Sodium/day and hence meal combination student should opt for
    //Note: To output contents of combination including nutritional content split - we can leverage Query 2.
    //On webpage, design the two queries to be side by side so once query 1 results are checked, you can follow up with query 2 results.
    
    echo "<br><b>Recommendations based on Demographics</b>";
    
    // 1a/3a - Target calories and sodium
    $myQuery1a = "SELECT S.gender, S.height, S.weight, S.birth_year FROM student S WHERE S.pid = '".$pid."'";
    $res1a = mysqli_query($conn, $myQuery1a);
    while ($row = mysqli_fetch_array($res1a, MYSQLI_NUM)) {
        echo "<br>";
        printf("Gender: %s<br>Height: %s<br>Weight: %s<br>Birth year: %s\n",$row[0], $row[1], $row[2], $row[3]);
        $g = $row[0];
        $h = $row[1];
        $w = $row[2];
        $y = $row[3];
    }
    
    $currentyear = date('Y');
    //"<br><br>".$currentyear."<br><br>";
    switch($g) {
        case "M":
            if ($h >= 5.8 and $w < 200 /* and ($currentyear-$y) < 31*/) { $tcal = 2500; $tsod = 3000;}
            elseif($h >= 5.8 and $w > 200) { $tcal = 1800; $tsod = 2500;}
            elseif($h >= 5.3 and $w <=180) { $tcal = 1800; $tsod = 2500;}
            elseif($h >= 5.3 and $w > 180) { $tcal = 1600; $tsod = 2000;}
            elseif($h <= 5 and $w <= 150) { $tcal = 1600; $tsod = 2000;}
            elseif($h <= 5 and $w > 150) { $tcal = 1400; $tsod = 2000;}
            break;
        case "F":
            if ($h >= 5.5 and $w < 150 /*and ($currentyear-$y) < 31*/) { $tcal = 1800; $tsod = 1700;}
            elseif($h >= 5.5 and $w > 150) { $tcal = 1600; $tsod = 1500;}
            elseif($h >= 5 and $w <=125) { $tcal = 1600; $tsod = 1700;}
            elseif($h >= 5 and $w > 125) { $tcal = 1400; $tsod = 1500;}
            elseif($h < 5 and $w <= 120) { $tcal = 1500; $tsod = 1500;}
            elseif($h < 5 and $w > 120) { $tcal = 1200; $tsod = 1500;}
            break;
    }
    echo "<br><br>Target Calories per day: ".$tcal." and Target Sodium per day: ".$tsod."<br>";
    
    // 1b - Combination student should opt for
    $combo = array(15); $c = array(15); $s = array(15); $ch = array(15); $f = array(15); $did = array(15); $mdid = array(15); $mtid = array(15);
    $i = 0;
    //echo "<br>C.cid, SUM(F.calories), SUM(F.sodium), SUM(F.chol), SUM(F.fat), A.did, A.mdid, A.mtid<br>";
    $myQuery1b = "SELECT C.cid, SUM(F.calories), SUM(F.sodium), SUM(F.chol), SUM(F.fat), A.did, A.mdid, A.mtid FROM contains C, food F, available_at A WHERE C.fname = F.fname and A.cid = C.cid GROUP BY C.cid, A.did, A.mdid, A.mtid";
    $res1b = mysqli_query($conn, $myQuery1b);
    while ($row = mysqli_fetch_array($res1b, MYSQLI_NUM)) {
        //printf("%s.........%s.........%s......%s...%s...%s...%s...%s<br>",$row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7]);
        $combo[$i] = $row[0];
        $c[$i] = $row[1];
        $s[$i] = $row[2];
        $ch[$i] = $row[3];
        $f[$i] = $row[4];
        $did[$i] = $row[5];
        switch($row[6]) {
            case ("Mon"): $mdid[$i] = '0';break; case ("Tue"): $mdid[$i] = '1';break; case ("Wed"): $mdid[$i] = '2';break; case ("Thu"): $mdid[$i] = '3';break; case ("Fri"): $mdid[$i] = '4';break; case ("Sat"): $mdid[$i] = '5';break; case ("Sun"): $mdid[$i] = '6';break;}
        $mtid[$i] = $row[7];
        //printf("%s.........%s.........%s......%s...%s...%s...%s...%s<br>",$combo[$i],$c[$i],$s[$i],$ch[$i],$f[$i],$did[$i],$mdid[$i],$mtid[$i]);
        $i = $i + 1;
    }
    
    
    $todayday = date('l');
    echo "<br>It is ".$todayday." today.<br>";
    echo "<b><br>Your food options today are:<br></b>";
    
    switch($todayday) {
            
        case("Monday"):
            for ($j=0; $j<=$i-1;$j=$j+1) {
                if ($mdid[$j] == "0" and (($mtid[$j]=="B" and $tcal*1/3 >= $c[$j]) or ($mtid[$j]=="L" and $tcal*1/3 >= $c[$j]) or ($mtid[$j]=="D" and $tcal*1/3 >= $c[$j])) and $tsod >= $s[$j]) { echo $mtid[$j]." at ".$did[$j]." combination: ".$combo[$j].": Calories = ".$c[$j]." and Sodium = ".$s[$j]." mdid = ".$mdid[$j]."<br>";}
            }
            break;
        case("Tuesday"):
            for ($j=0; $j<=$i-1;$j=$j+1) {
                if ($mdid[$j] == "1" and (($mtid[$j]=="B" and $tcal*1/3 >= $c[$j]) or ($mtid[$j]=="L" and $tcal*1/3 >= $c[$j]) or ($mtid[$j]=="D" and $tcal*1/3 >= $c[$j])) and $tsod >= $s[$j]) { echo $mtid[$j]." at ".$did[$j]." combination: ".$combo[$j].": Calories = ".$c[$j]." and Sodium = ".$s[$j]." mdid = ".$mdid[$j]."<br>";}
            }
            break;
        case("Wednesday"):
            for ($j=0; $j<=$i-1;$j=$j+1) {
                if ($mdid[$j] == "2" and (($mtid[$j]=="B" and $tcal*1/3 >= $c[$j]) or ($mtid[$j]=="L" and $tcal*1/3 >= $c[$j]) or ($mtid[$j]=="D" and $tcal*1/3 >= $c[$j])) and $tsod >= $s[$j]) { echo $mtid[$j]." at ".$did[$j]." combination: ".$combo[$j].": Calories = ".$c[$j]." and Sodium = ".$s[$j]." mdid = ".$mdid[$j]."<br>";}
            }
            break;
        case("Thursday"):
            for ($j=0; $j<=$i-1;$j=$j+1) {
                if ($mdid[$j] == "3" and (($mtid[$j]=="B" and $tcal*1/3 >= $c[$j]) or ($mtid[$j]=="L" and $tcal*1/3 >= $c[$j]) or ($mtid[$j]=="D" and $tcal*1/3 >= $c[$j])) and $tsod >= $s[$j]) { echo $mtid[$j]." at ".$did[$j]." combination: ".$combo[$j].": Calories = ".$c[$j]." and Sodium = ".$s[$j]." mdid = ".$mdid[$j]."<br>";}
            }
            break;
        case("Friday"):
            for ($j=0; $j<=$i-1;$j=$j+1) {
                if ($mdid[$j] == "4" and (($mtid[$j]=="B" and $tcal*1/3 >= $c[$j]) or ($mtid[$j]=="L" and $tcal*1/3 >= $c[$j]) or ($mtid[$j]=="D" and $tcal*1/3 >= $c[$j])) and $tsod >= $s[$j]) { echo $mtid[$j]." at ".$did[$j]." combination: ".$combo[$j].": Calories = ".$c[$j]." and Sodium = ".$s[$j]." mdid = ".$mdid[$j]."<br>";}
            }
            break;
        case("Saturday"):
            for ($j=0; $j<=$i-1;$j=$j+1) {
                if ($mdid[$j] == "5" and (($mtid[$j]=="B" and $tcal*1/3 >= $c[$j]) or ($mtid[$j]=="L" and $tcal*1/3 >= $c[$j]) or ($mtid[$j]=="D" and $tcal*1/3 >= $c[$j])) and $tsod >= $s[$j]) { echo $mtid[$j]." at ".$did[$j]." combination: ".$combo[$j].": Calories = ".$c[$j]." and Sodium = ".$s[$j]." mdid = ".$mdid[$j]."<br>";}
            }
            break;
        case("Sunday"):
            for ($j=0; $j<=$i-1;$j=$j+1) {
                if ($mdid[$j] == "6" and (($mtid[$j]=="B" and $tcal*1/3 >= $c[$j]) or ($mtid[$j]=="L" and $tcal*1/3 >= $c[$j]) or ($mtid[$j]=="D" and $tcal*1/3 >= $c[$j])) and $tsod >= $s[$j]) { echo $mtid[$j]." at ".$did[$j]." combination: ".$combo[$j].": Calories = ".$c[$j]." and Sodium = ".$s[$j]." mdid = ".$mdid[$j]."<br>";}
            }
            break;
    }
    
    mysqli_close($conn);
    
?>


</body>
</html>