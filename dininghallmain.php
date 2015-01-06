<html>
    <body>

    <h2>Dining Hall @Columbia</h2>
        
    <?php
        require_once "common.php";
        
        $did = "'".$_POST["name"]."'";
        $nameQuery = "SELECT * FROM dining_hall WHERE did=".$did;
        $nameres = mysqli_query($conn,$nameQuery);
        while($row = mysqli_fetch_array($nameres, MYSQLI_NUM)){
            echo "Welcome to ".$row[2].".<br>";
            echo "Location: ".$row[1]."<br>";
            echo "Operation Hours: ".$row[3]."<br>";
        }
        
        session_start();
        $_SESSION['did'] = $did;
        
        mysqli_close($conn);
    ?>


    <a href="http://localhost/mostpopmeal.php"><br>Click here to see the most popular meal<br></a>

    <a href="http://localhost/busytime.php"><br>Click here to see the busiest time of a day<br></a>

    <a href="http://localhost/creviews.php"><br>Click here to see customer reviews<br></a>
    
    <a href="http://localhost/cinfo.php"><br>Click here to see customer information<br></a>

    </body>
</html>