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

tr{cursor: pointer; transition: all .25s ease-in-out}
.selected{background-color:grey; color: white;}

</style>
<body>

<!-- Links (sit on top) -->
<div class="w3-top">
  <div class="w3-row w3-padding w3-black">
        <div class="w3-col s3">
      <a href="index.php" class="w3-button w3-block w3-black">HOME</a>
    </div>
    <div class="w3-col s3">
      <a href="checkout1.php" class="w3-button w3-block w3-black">CHECK OUT</a>
    </div>
    <div class="w3-col s3">
      <a href="borrower1.php" class="w3-button w3-block w3-black">BORROWER</a>
    </div>
    <div class="w3-col s3">
      <a href="fine1.php" class="w3-button w3-block w3-black">FINES</a>
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
         <br>
      <p>Enter ISBN:<input class="w3-input w3-padding-16 w3-border" type="text" name="isbnName" id='ISBNID'  placeholder="ISBN"  style="max-width:200px"><br>  
        <p>Enter card ID:<input class="w3-input w3-padding-16 w3-border" type="text" name="cName" id="cID" placeholder="CARD ID"  style="max-width:200px"><br>
      <input class="w3-button w3-black" type="submit" name="checkin" value="Check In"><br><br>
      Need to search?
      <input class="w3-input w3-padding-16 w3-border" type="text" name="searchString"  id="searchID" placeholder="Enter card ID/ISBN/Borrower's first name"  ><br><input class="w3-button w3-black" type="submit" name="search" value="Search"><br><br>

      </p>
 

<!-- End page content -->




<?php

if(isset($_POST['search']))

{

$name=$_POST['searchString'];

 echo "<h2>List Of Book Loans:</h2><br>";

    $res=mysqli_query($db,"SELECT * FROM book_loans where isbn='$name' or card_no='$name'or card_no in(select card_no from borrower where Fname='$name') and date_in is null;");
    if(!$res) {echo "Could not perform search operation.";}

    echo "<table cellpadding=10 id='table1'>";
      echo "<tr bgcolor='w3-black' text='w3-text-white'>";
        //Table header
        
        echo "<th>"; echo "<b>LOAN ID</b>"; echo "</th>";
        echo "<th>"; echo "<b>ISBN</b>";  echo "</th>";
        echo "<th>"; echo "<b>CARD ID </b>";  echo "</th>";
        echo "<th>"; echo "<b>DATE OUT</b>";  echo "</th>";
        echo "<th>"; echo "<b>DATE IN</b>";  echo "</th>";
        echo "<th>"; echo "<b>DUE DATE</b>";  echo "</th>";
        echo "<th>"; echo "<b>TITLE</b>";  echo "</th>";
        
      echo "</tr>"; 

      while($row=mysqli_fetch_assoc($res))
      {
        
        echo "<tr>";
        echo "<td>"; echo $row['Loan_id']; echo "</td>";
        echo "<td>"; echo $row['isbn']; echo "</td>";
        echo "<td>"; echo $row['card_no']; echo "</td>";
        echo "<td>"; echo $row['Date_out']; echo "</td>";
        echo "<td>"; echo $row['Date_in']; echo "</td>";
        echo "<td>"; echo $row['Due_date']; echo "</td>";
        echo "<td>"; echo $row['name']; echo "</td>";
        
        
        echo "<td>"; 
        echo "</tr>";
      }
    echo "</table>";
  
}



if(isset($_POST['checkin']))

{$today=date("Y/m/d");
$card=$_POST['cName'];
$isbn=$_POST['isbnName'];
$flag=0;



if($_POST['cName']==0){$flag=1;}
if($_POST['isbnName']==""){$flag=1;}
if($flag==1){echo "Both card id and isbn need to be entered to check in.";}





if($flag==0)
 { $res1=mysqli_query($db,"update book_loans set Date_in= '$today' where isbn='$isbn';");
  $res2=mysqli_query($db,"update book set available=0 where Isbn='$isbn';");
   if(!$res2){echo "Error updating book availability.";}
  if($res1)
  {echo "Checked in successfully!";}
else {echo "Book has not been checked in. Please try again."; }
  }
}
?>


<script type="text/javascript">
    
                var table = document.getElementById('table1');
                for(var i = 1; i < table.rows.length; i++)
                {
                    table.rows[i].onclick = function()
                    {
                         if(typeof index !== "undefined")
                         {table.rows[index].classList.toggle("selected");}
                          index = this.rowIndex;
                        // add class selected to the row
                        this.classList.toggle("selected");
                         document.getElementById("ISBNID").value = this.cells[1].innerHTML;
                         document.getElementById("cID").value = this.cells[2].innerHTML;
                    }
                }
 </script>

</div>
</form>


<!-- Footer -->
<footer class="w3-center w3-light-grey w3-padding-48 w3-large">
  <p>* Invalid seach string returns an empty search list.</p>
</footer>



</body>
</html>
