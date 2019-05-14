<?php
include "conn.php"
?>
<!DOCTYPE html>
<html>
<title>Library Management System</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inconsolata">
<style>
body, html {
  height: 100%;
  font-family: "Inconsolata", sans-serif;
}

.bgimg {
  background-position: center;
  background-size: cover;
  background-image: url("/images/library1.jpg");
  min-height: 50%;
}

.menu {
  display: none;
}
</style>
<body>

<!-- Links (sit on top) -->
<div class="w3-top">
  <div class="w3-row w3-padding w3-black">
        <div class="w3-col s3">
      <a href="checkin.php" class="w3-button w3-block w3-black">CHECK IN</a>
    </div>
    <div class="w3-col s3">
      <a href="checkout1.php" class="w3-button w3-block w3-black">CHECK OUT</a>
    </div>
    <div class="w3-col s3">
      <a href="index.php" class="w3-button w3-block w3-black">HOME</a>
    </div>
    <div class="w3-col s3">
      <a href="fine1.php" class="w3-button w3-block w3-black">FINE</a>
    </div>
  </div>
</div>

<!-- Header with image -->
<header class="bgimg w3-display-container w3-grayscale-min" id="home">
  
  <div class="w3-display-middle w3-center">
    <span class="w3-text-white" style="font-size:90px">the<br>Library</span>
  </div>
  <div class="w3-display-bottomright w3-center w3-padding-large">
    <span class="w3-text-white">Open from 8 am to 6 pm</span>
  </div>
</header>

<!-- Add a background color and large text to the whole page -->
<div class="w3-sand w3-grayscale w3-large">



<!-- Contact/Area Container -->
<form method="POST">
<div class="w3-container" id="where" style="padding-bottom:32px;">
  <div class="w3-content" style="max-width:600px">
      <p>SSN:(required)<input class="w3-input w3-padding-16 w3-border" type="text" name="ssnName"  placeholder="Enter your ssn here" required name="Message"><br>  
      <p> First name:(required)<input class="w3-input w3-padding-16 w3-border" type="text" name="firstName"  placeholder="Enter your first name" required name="Message"><br> 
      <p> Last name:<input class="w3-input w3-padding-16 w3-border" type="text" name="lastName"  placeholder="Enter your last name" ><br> 
      <p>Phone Number:<input class="w3-input w3-padding-16 w3-border" type="text" name="phoneName"  placeholder="Enter your phone number" ><br>     
      <p>Address :(required)<input class="w3-input w3-padding-16 w3-border" type="text" name="addressName"  placeholder="Enter apartment,street" required name="Message"><br> 
      City :(required)<input class="w3-input w3-padding-16 w3-border" type="text" name="cityName"  placeholder="Enter your city" required name="Message" style="max-width:200px"><br>
       State :(required)<input class="w3-input w3-padding-16 w3-border" type="text" name="stateName"  placeholder="Enter your state" required name="Message" style="max-width:200px"><br> 
       
      <input class="w3-button w3-black" type="submit" name="submit" value="register"></p><br>
 

<!-- End page content -->



<?php
  
  if(isset($_POST['submit']))
 {
  $ssn=$_POST['ssnName'];
$flag=0;
$val='';

//check for duplicate ssn
  $res=mysqli_query($db,"select Ssn from borrower ;");
   while($row=mysqli_fetch_assoc($res))
      {
        if ($row['Ssn']==$_POST['ssnName'])
          {$flag=1;
            echo " The SSN provided already exists.Every user can have only one card Id. ";}
      }

      if($flag==0)

  {$res1=mysqli_query($db,"INSERT INTO `BORROWER`(Ssn,Fname,Lname,Address,City,State,Phone) VALUES('$_POST[ssnName]','$_POST[firstName]','$_POST[lastName]','$_POST[addressName]','$_POST[cityName]','$_POST[stateName]','$_POST[phoneName]');");

$res=mysqli_query($db,"select card_no from borrower where Ssn='$ssn';");
   while($row=mysqli_fetch_assoc($res))
   { $val= $row['card_no'];}
 
 if($res1)
  {echo"<center>User registered successfully!Your Card Id is '$val'";}
  
 }
 }


 
?>
</div>
</form>


<!-- Footer -->
<footer class="w3-center w3-light-grey w3-padding-48 w3-large">
  <p>-</p>
</footer>



</body>
</html>
