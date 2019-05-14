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
      <a href="checkin.php" class="w3-button w3-block w3-black">CHECK IN</a>
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
      <br>Enter card ID:<input class="w3-input w3-padding-16 w3-border" type="text" name="cardIdName" id="cardID" placeholder="CARD ID"style="max-width:200px" ><br>   
       ISBN:<input class="w3-input w3-padding-16 w3-border" type="text" name="isbnName"  placeholder=" Enter ISBN or select from the list below" id="ISBNID"style="max-width:600px"><br>  
      Enter Title:<input class="w3-input w3-padding-16 w3-border" type="text" name="titleName" id="titleID" placeholder="Book Title or select from the list below" ><br>
      <input class="w3-button w3-black" type="submit" name="checkout" value="Check Out"/><br><br>
      

      Need to search from all books?
      <input class="w3-input w3-padding-16 w3-border" type="text" name="searchName"  placeholder="Seach by book name/ISBN/author"><br>
         <input class="w3-button w3-black" type="submit" name="search" value="search"><br>


<!-- End page content -->




<?php

//calculating dates
$today=date("Y/m/d");
$dueDay = strtotime("+14 day");
$dueDay=date("Y/m/d",$dueDay);


//FUNCTION FOR CHECKOUT
if(isset($_POST['checkout']))

{
  $name=$_POST['cardIdName'];$flag=0;
  if($_POST['cardIdName']==0){$flag=1;}
  if($_POST['isbnName']==""){$flag=1;}
  if($flag==1){echo "Both card id and isbn need to be entered";}

//check if user already has checked out 3 books
  $res2=mysqli_query($db,"select count(*) as res from book_loans where Card_no='$_POST[cardIdName]';");
  while($row=mysqli_fetch_assoc($res2))
        {
          if ($row['res']>2){$flag=2; echo "You cannot checkout more than three books at a time";}
        }

//check if the book is available
  $res3=mysqli_query($db,"select available from book where Isbn='$_POST[isbnName]';");
  while($row=mysqli_fetch_assoc($res3))
        {
          if ($row['available']==1){$flag=3; echo "The selected book is not available at this time.Please select another one.";
        }
  }


//If all conditions are satisfied, create an entry          
if($flag==0)
{
    $isbn=$_POST['isbnName'];
    $res=mysqli_query($db,"insert into book_loans(isbn,card_no,Date_out,Due_date,name) values('$_POST[isbnName]','$_POST[cardIdName]','$today','$dueDay','$_POST[titleName]');");

    $res2=mysqli_query($db,"update book set available=1 where Isbn='$isbn';");
  
          if($res)
          {echo "Checked out successfully" ; 
          }
          else
          {echo "insert failed. ";}

  }

}


//FUNCTION FOR SEARCH
 if(isset($_POST['search']))
   {
      $name="%";
      $name.=$_POST['searchName']; 
      $name2=$_POST['searchName'];
      $name.="%"; $name2.="%";

      //two search patterns for the search bar so the result returns 'begins with' search first followed by a substring match- for aesthetics.

    echo "<h2>List Of Books</h2><br>";

    $res=mysqli_query($db,"SELECT * FROM `book` where Title like '$name2' or Isbn like '$name2' union SELECT * FROM `book` where Title like '$name' or Isbn like '$name' or Author like '$name';");
    if(!$res) {echo "Could not perform search operation."; }
   

    echo "<table cellpadding=10 id='table1'>";
      echo "<tr bgcolor='w3-black' text='w3-text-white'>";
        //Table header
        
        echo "<th>"; echo "<b>ISBN</b>"; echo "</th>";
        echo "<th>"; echo "<b>TITLE</b>";  echo "</th>";
        echo "<th>"; echo "<b>AUTHOR</b>";  echo "</th>";
        echo "<th>"; echo "<b>AVAILABLE</b>";  echo "</th>";
        
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

<!-- Function to highlight selected row in the list and copy the selected values into the textbox fields -->
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
                         document.getElementById("ISBNID").value = this.cells[0].innerHTML;
                         document.getElementById("titleID").value = this.cells[1].innerHTML;
                    }
                }
 </script>
</div>
</form>
<!-- Footer -->
<footer class="w3-center w3-light-grey w3-padding-48 w3-large">
  <p>-</p>
</footer>
</body>
</html>
