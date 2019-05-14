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
      <a href="borrower1.php" class="w3-button w3-block w3-black">BORROWER</a>
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
  <div class="w3-content" style="max-width:1000px">
      <p><input class="w3-input w3-padding-16 w3-border" type="text" name="searchName"  placeholder="Search for any book by book title/isbn " required name="Message"><br>    
      <input class="w3-button w3-black" type="submit" name="submit" value="search"></p>
 

<!-- End page content -->



<?php
  
  
  if(isset($_POST['submit']))


  {
    $name="%";
    $name.=$_POST['searchName']; 
    $name2=$_POST['searchName'];
    $name.="%"; $name2.="%";

    //two search patterns for the search bar so the result returns 'begins with' search first followed by a substring match- for aesthetics.

    echo "<h2>List Of Books</h2><br>";

    $res=mysqli_query($db,"SELECT * FROM `book` where Title like '$name2' or Isbn like '$name2' union SELECT * FROM `book` where Title like '$name' or Isbn like '$name' or Author like '$name' ;");
      
              if(!$res) {echo "Could not perform search.";}

    //Result tablr

    echo "<table cellpadding=10 >";
      echo "<tr bgcolor='w3-black' text='w3-text-white'>";
        //Table header
        
        echo "<th>"; echo "<b>ISBN</b>"; echo "</th>";
        echo "<th>"; echo "<b>TITLE</b>";  echo "</th>";
        echo "<th>"; echo "<b>AUTHOR</b>";  echo "</th>";
        echo "<th>"; echo "<b>AVAILABILITY</b>";  echo "</th>";
        
      echo "</tr>"; 

      while($row=mysqli_fetch_assoc($res))
      {
        if ($row['available']==0)
          {$val="yes";}
        else
          {$val="no";}
        echo "<tr>";
        echo "<td>"; echo $row['Isbn']; echo "</td>";
        echo "<td>"; echo $row['Title']; echo "</td>";
        echo "<td>"; echo $row['Author']; echo "</td>";
        echo "<td>"; echo $val; echo "</td>";
        
        echo "<td>"; 
        echo "</tr>";
      }
    echo "</table>";
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
