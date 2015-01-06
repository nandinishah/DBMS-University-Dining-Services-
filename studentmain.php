<html>
    <body>

    <h2>Student @Columbia</h2>
        
    <?php
        
        require_once "common.php";
        
        $name = $_POST["name"];
        echo "Welcome ".$name."! ";
        $userid = $_POST["userid"];
        
        
        session_start();
        $_SESSION['pid'] = $userid;
        
 /*       // Student - Query on main page printed at all times - TRY MAKING THIS LOOK PRETTY IN A TABLE!
        $arrayMain = array(array(50)); $i = 0;
        $myQueryMain = "SELECT mdid, mtid, did FROM available_at GROUP BY mdid, mtid, did";
        $resMain = mysqli_query($conn, $myQueryMain);
        echo "Meal Options this week are: </b><br><br>";
        printf("<b>Day...mtid...mdid \n</b>");
        while ($row = mysqli_fetch_array($resMain, MYSQLI_NUM)) {
            echo "<br>";
            //printf("%s %s %s", $row[0], $row[1], $row[2]);
            $arrayMain[$i][0] = $row[0];
            $arrayMain[$i][1] = $row[1];
            $arrayMain[$i][2] = $row[2];
            echo $arrayMain[$i][0]."......".$arrayMain[$i][1]."......".$arrayMain[$i][2];
            $i = $i+1;
        }
        
        echo "<br><br><b>Options for Breakfast (B) :<br></b>";
        for ($j = 0; $j < $i; $j = $j+1) {
            switch ($arrayMain[$j][1]) {
                case "B":
                    echo $arrayMain[$j][0]."\t".$arrayMain[$j][1]."\t".$arrayMain[$j][2]."<br>";
                    break;
            }} */
        echo "<br><br>";
        echo "<b>Enter information to find out nutritional content of a meal:</b><br>";
        ?>

    <form action="Query2.php" method="post">
    Meal day: <input type="text" name="mdid"><br>
    Meal time: <input type="text" name="mtid"><br>
    Dining Hall: <input type="text" name="did"><br><br>
    <input type="submit">
    </form>

    <a href="http://localhost/Query4.php"><br>Click here for status of subscription plan<br></a>

    <a href="http://localhost/Query3.php"><br>Click here for this week's health tracker<br></a>

    <a href="http://localhost/Query1.php"><br>Click here for recommended meals<br></a>


    <?php   mysqli_close($conn);    ?>
    
    

    </body>
</html>