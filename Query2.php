<html>
<body>

<h2>Student @Columbia</h2>

<?php
    require_once "common2.php";

    // Student - Query 2: Information about meal
    
    //student has already entered cid
    //Input = $mealtime, $mealday, $did
    //Output = Combinations available with their nutritional content
    
    $mdid = $_POST["mdid"];
    $mtid = $_POST["mtid"];
    $did = $_POST["did"];
    
    echo "<br><b>Nutritional Content of Selected Meal</b><br><br>";
    printf("<b>Time  Combo   Type......Food..................Calories...Fat...Chol...Sodium</b>");
    $myQuery2 = "SELECT A.mtid, A.cid, F.ftype, F.fname, F.calories, F.fat, F.chol, F.sodium FROM contains C, food F, available_at A WHERE A.cid = C.cid and F.fname = C.fname and A.mdid = '".$mdid."' and A.mtid = '".$mtid."' and A.did = '".$did."' GROUP BY A.cid, A.mtid, F.ftype, F.fname, F.calories, F.fat, F.chol, F.sodium";
    $res2 = mysqli_query($conn, $myQuery2);
    while($row = mysqli_fetch_array($res2, MYSQLI_NUM)) {
        echo "<br>";
        printf("%s.........%s.........%s......%s......%s......%s.........%s......%s",$row[0], $row[1], $row[2],$row[3], $row[4], $row[5],$row[6],$row[7]);
    }
    echo "<br><br>";
    
    session_start();
    $_SESSION["did"] = $did;
    $_SESSION["mtid"] = $mtid;
    $_SESSION["mdid"] = $mdid;

?>
    <a href="http://localhost/Query5.php"><br>Click here to see recommended payment method for chosen meal<br></a>


  <?php mysqli_close($conn); ?>



</body>
</html>