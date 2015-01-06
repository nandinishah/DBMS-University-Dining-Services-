<html>
<body>

<h2>Student @Columbia</h2>

<?php
    require_once "common2.php";
   
    session_start();
    $pid = $_SESSION['pid'];
    
    // 1a/3a - Target calories and sodium
    $myQuery1a = "SELECT S.gender, S.height, S.weight, S.birth_year FROM student S WHERE S.pid = '".$pid."'";
    $res1a = mysqli_query($conn, $myQuery1a);
    while ($row = mysqli_fetch_array($res1a, MYSQLI_NUM)) {
        echo "<br>";
        printf("Gender: %s<br>Height: %s<br>Weight: %s<br>Birth year :%s\n",$row[0], $row[1], $row[2], $row[3]);
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
    
    
    
    // Student - Query 3: Whether user's dietary target has been overshot/undershot.
    // Target calories/sodium per day as calculated earlier - 3a.
    // Input: pid as entered earlier.
    // Working: cids ate this week from "eats", cross-reference against tables in query 1b to figure sum of cals, sod etc. for past week and do average consumption per meal. Hence overshot/undershot on average.
    // Output: print all the working steps and conclusion.
    
    echo "<br>This week: ";
    $i = 0;
    $todaydate = date("Y/m/d");
    $startdate = date('y')."/".date('m')."/".(date('d')-7);
    echo "20".$startdate." to ";
    echo $todaydate."<br>";
    $myQuery3b = "SELECT E.mdid, E.edate, E.cid, E.mtid, E.did FROM eats E WHERE E.pid = '".$pid."' and E.edate >= '".$startdate."'";
    $res3b = mysqli_query($conn, $myQuery3b);
    echo "<br>Meals eaten this week:<br>";
    echo "Day...........DATE........Combo.....Time...Location<br>";
    while ($row = mysqli_fetch_array($res3b, MYSQLI_NUM)) {
        printf("%s......%s........%s.............%s...........%s<br>",$row[0], $row[1], $row[2], $row[3], $row[4]);
        $comboeaten[$i] = $row[2];
        $i = $i + 1;
    }
    
    //sort($comboeaten);
    
    $calconsumed = 0;
    $cholconsumed = 0;
    $fatconsumed = 0;
    $sodconsumed = 0;
    
    for ($j = 0;$j < $i; $j = $j+1) {
        $k = 0;
        while($k >= 0) {
            if ($comboeaten[$j] == $combo[$k]) {
                $calconsumed = $calconsumed + $c[$k];
                $sodconsumed = $sodconsumed + $s[$k];
                $cholconsumed = $cholconsumed + $ch[$k];
                $fatconsumed = $fatconsumed + $f[$k];
                break;}
            $k = $k+1;
        }
    }
    //echo "<br>Total Nutritional Consumption this week:<br>Calories = ".$calconsumed."<br>Sodium = ".$sodconsumed."<br>Cholestrol = ".$cholconsumed."<br>Fat = ".$fatconsumed."<br>";
    
    echo "<br>Average Nutritional Consumption this week:<br>Calories = ".round($calconsumed/$i,2)."<br>Sodium = ".round($sodconsumed/$i,2)."<br>Cholestrol = ".round($cholconsumed/$i,2)."<br>Fat = ".round($fatconsumed/$i,2)."<br>";
    
    if ($calconsumed/$i < $tcal/3) { echo "<br><b>You UNDERSHOT your target this week by: ".round(($tcal/3 - $calconsumed/$i),2)." cals<br></b>"; }
    elseif ($calconsumed/$i > $tcal/3) { echo "<br><b>You OVERSHOT your target this week by: ".round(($calconsumed/$i - $tcal/3),2)." cals<br></b>"; }
    elseif ($calconsumed/$i == $tcal/3) { echo "<br><b>You MET your target this week! <br></b>"; }
    
    
    
    
    
    
    mysqli_close($conn);
    ?>


</body>
</html>