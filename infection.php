<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Temp & oxy graphs</title>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
  <link rel="stylesheet" href="infection.css">
    <style type="text/css">
      #chart-container {
        width: 640px;
        height: auto;
      }
    </style>
</head>
<body>
<?php
$server = "localhost";
$user = "root";
$password = "";
$database = "login";

$conn = mysqli_connect($server, $user, $password, $database);
if(!$conn){
    die("Connection failed: " . mysqli_connect_error());
}
if(isset($_POST['submit'])){
$email = $_SESSION["email"];
$day = $_POST['day'];
$temp = $_POST['temp'];
$oxylevel = $_POST['oxylevel'];

$q = "INSERT INTO infection VALUES ('$email', '$day', '$temp', '$oxylevel')";
if(mysqli_query($conn, $q)){
    echo " ";
 }
 else{
     echo "Error:" . $q . "" . mysqli_error($conn);
 }
$query = "SELECT * FROM infection WHERE name = '$email'";
$result = mysqli_query($conn, $query);
$chart_data = '';
while($row = mysqli_fetch_array($result))
{
 $chart_data .= "{ day:'".$row["day"]."', temp:".$row["temp"].", oxylevel:".$row["oxylevel"]."}, ";
}
$chart_data = substr($chart_data, 0, -2);
$conn->close();
}
?>
     <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="infection.php" class="active">Infection</a></li>
        <li><a href="report.php">Reports</a></li>
        <li><a href="cowin2.html">Cowin</a></li>
        <li><a href="news.html">News</a></li>
        <li><a href="symptoms.html">Symptoms</a></li>
       <li class="covirise"><a href="mychart.html">Covid-Rise</a></li>
        <li style="float:right"><a href="logout.php">Logout</a></li>

    </ul>

    <div class="form">
        <form action="infection.php" method="POST">
            <table>
                <tr>
                    <th>
                        <p>
                            Day <input type="number" name="day" placeholder="1" id="day">
                        </p>
                    </th>
                </tr>
                <tr>
                    <th>
                        <p>
                            Body temperature <input type="text" name="temp" placeholder="Enter your body temperature" id="temp">
                        </p>
                    </th>
                </tr>            
                <tr>
                    <th>
                        <p>
                            Oxygen level <input type="text" name="oxylevel" placeholder="Enter your oxygen level" id="oxylevel">
                        </p>
                    </th>
                </tr>
                <tr>
                    <th>
                        <input type="submit" name="submit" id="submit">     
                    </th>
                </tr>
            </table>
        </form>
    </div>
    <div class="container" style="width:80%;">
        <div id="chart"></div>
    </div>
    <h3 class="heading">Body temperature graph</h3>
    <div class="container" style="width:80%;">
        <div id="chart2"></div>
    </div>
    <h3 class="heading">Oxygen level graph</h3>
</body>
</html>
<script>
Morris.Bar({
 element : 'chart',
 data:[<?php echo $chart_data; ?>],
 xkey:'day',
 ykeys:['temp'],
 ylabels:['temp'],
 hideHover:'auto',
 stacked:true
});
Morris.Bar({
 element : 'chart2',
 data:[<?php echo $chart_data; ?>],
 xkey:'day',
 ykeys:['oxylevel'],
 ylabels:['oxylevel'],
 hideHover:'auto',
 stacked:true
});
</script>
