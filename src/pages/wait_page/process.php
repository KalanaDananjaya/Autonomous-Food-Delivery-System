<?php
$connect = mysqli_connect('localhost','root','','inputdata');
$conn = mysql_connect('localhost','root','');
if(mysqli_connect_errno($connect)){
    echo 'Failed to connect';
}

$name = $_POST['name'];
$email = $_POST['email'];
$tp = $_POST['tp'];
$tablenum=$_POST['tableno'];


session_start();
$_SESSION['name'] = $name;
$food = $_SESSION['fooditem'];

$prices = array('1'=>120, '2'=>150, '3'=>80, '4'=>100);
$mealList = array('1'=>'Rice & Curry','2'=>'Fried Rice', '3'=>'String Hoppers', '4'=>'Hoppers');
$_SESSION['price'] = $prices[$food];
$prices = array('Rice & Curry'=>120, 'Fried Rice'=>150, 'String Hoppers'=>80, 'Hoppers'=>100);
$_SESSION['meal'] = $mealList[$food];


mysqli_query($connect, "INSERT INTO user_info(name,email,phone,tableno,food) VALUES ('$name','$email','$tp','$tablenum','$food')");
//echo "data input succesfull";

if(mysqli_affected_rows($connect)>0){
    //echo "<p>User added</p>";
}
else{
    //echo "User not added<br/>";
    echo mysqli_error($connect);
}

$sql = 'SELECT * FROM user_info WHERE id=(SELECT max(id) FROM user_info)';
mysql_select_db('inputdata');
$retval = mysql_query($sql,$conn);
//echo $retval;

if(! $retval){
    die('could not get data : '.mysql_error());
}
else{
    while($row= mysql_fetch_assoc($retval)){
        //echo "NAME : {$row['name']}<br>";
    }
    //echo "fetched data successfully";
    mysql_close($conn);
}

?>



<!DOCTYPE html>
<html>
<script type="text/javascript">
    var customer= <?php echo json_encode($name); ?>;
    var fooditem= <?php echo json_encode($food); ?>;

</script>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>order countdown</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/user.css">
</head>

<body>
    <nav class="navbar navbar-default hidden-xs hidden-sm hidden-md hidden-lg">
        <div class="container">
            <div class="navbar-header"><a class="navbar-brand navbar-link" href="#"><i class="glyphicon glyphicon-phone"></i>Mobile App</a>
                <button class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>
            </div>
            <div class="collapse navbar-collapse" id="navcol-1">
                <ul class="nav navbar-nav navbar-right">
                    <li class="active" role="presentation"><a href="#">First Item</a></li>
                    <li role="presentation"><a href="#">Second Item</a></li>
                    <li role="presentation"><a href="#">Third Item</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="jumbotron hero">
        <div class="container">
            <div class="row">
                <h1>Greetings Mr. <span id=customer>test</span> !<h1>
                <div class="col-md-4 col-md-push-7 phone-preview">
                    <div class="iphone-mockup"><img id="image" class="device">
                        <script>
                            var meal = <?php echo json_encode($food); ?>;
                            if (meal == 1) {
                                document.getElementById("image").src = "assets/img/Riceandcurry.png";
                            }
                            if (meal == 2) {
                                document.getElementById("image").src = "assets/img/fried.png";
                            }
                            if (meal == 3) {
                                document.getElementById("image").src = "assets/img/fried.png";
                            }
                            if (meal == 4) {
                                document.getElementById("image").src = "assets/img/fried.png";
                            }
                            //document.getElementById("image").innerHTML = meal;
                        </script>

                    </div>
                   


                </div>
                <div class="col-md-6 col-md-pull-3 get-it">
                    <h1>Your food will arrive shortly </h1>
                    <p>
                        
                        
                        <a id="btn2" class="btn btn-success btn-lg" role="button" onclick="mediaplay()">Play / Pause music</a>
                        <!--<a id="btn3" class="btn btn-primary btn-lg" role="button" href="#">Go to Home </a> -->
                        <a id="btn4" class="btn btn-success btn-lg" role="button" href="../payment-page/index.php">Pay now</a></p>
                        
                </div>
                <div class="col-md-12">
                    <div>
    <audio autoplay id="song"> 
        <source src="http://localhost\Food Delivery System Web\src\pages\wait_page\assets\img\Inthemirror.mp3" type="audio/mpeg" >
        </audio>
</div>
                </div>
            </div>
        </div>
    </div>
  
    <footer class="site-footer">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <h5>Team GHOST © 2018</h5></div>
                <div class="col-sm-6 social-icons"><a href="#"><i class="fa fa-facebook"></i></a><a href="#"><i class="fa fa-twitter"></i></a><a href="#"><i class="fa fa-instagram"></i></a></div>
            </div>
        </div>
    </footer>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/countdown.js"></script>

</body>

</html>