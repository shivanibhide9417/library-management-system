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
      <a href="checkin.php" class="w3-button w3-block w3-black">CHECK IN</a>
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
      <br>
      
     PAY using card Id:<br>
      <p><input class="w3-input w3-padding-16 w3-border" type="text" name="searchName" id="payId"  placeholder="Enter card ID or select from the list below" ><br> 
      <input class="w3-button w3-black" type="submit" name="pay" value="pay" "><br><br>
      The following list will display the total fine amount associated with every user.<br> Fines which have already been paid will be displayed in the bottom half of the list.The top half of the list are due fines.<br><br>Payment will not be accepted even if a single book issued by the card owner is not returned.<br><br>
      <input class="w3-button w3-black" type="submit" name="reset" value="Fines by cardID(refresh list)"></p><br></div>
      
 

<!-- End page content -->




<?php

if(isset($_POST['reset']))
{ 
  $today=date("Y/m/d");

  
//check for new entries in the book loans table
 $val=mysqli_query($db,"insert into fines(Loan_id)(select Loan_id from book_loans where Due_date<Date_in or Date_in is NULL );");
 if(!$val){echo " Refresh failed. ";}

//calculate fine for books that are already checked in
 $val2=mysqli_query($db,"update fines,book_loans set fine_amt=datediff(book_loans.Date_in,book_loans.Due_date)*0.25 where fines.Loan_id=book_loans.Loan_id and book_loans.date_in is not null and paid=0;");
 if(!$val2){echo " Refresh failed. ";}

//calculate fine for book that are not checked in yet
$val3=mysqli_query($db,"update fines,book_loans set fine_amt=datediff('$today',book_loans.Due_date)*0.25 where fines.Loan_id=book_loans.Loan_id and book_loans.Date_in is null and paid=0;");
if(!$val3){echo " Refresh failed. ";}

//no fine for books returned before the due date
$val3=mysqli_query($db,"update fines set fine_amt=0 where fine_amt<0;");
//if(!$val3){echo " five";}

 $val4=mysqli_query($db,"update fines,book_loans set fines.card_no=book_loans.card_no where fines.Loan_id=book_loans.Loan_id;");
 //if(!$val4){echo " Refresh failed. ";}


      $res=mysqli_query($db,"select card_no,sum(fine_amt),paid from fines where paid=0 group by card_no union select card_no,fine_amt,paid from fines where paid=1;");
     if(!$res){echo "Error while displaying the list. Please try again.";}

      if($res)
    echo "<center><table cellpadding=20 id='table1'>";
      echo "<tr bgcolor='w3-black' text='w3-text-white'>";
        //Table header
        
        echo "<th>"; echo "Card id";  echo "</th>";
        echo "<th>"; echo "Amount($)";  echo "</th>";
        echo "<th>"; echo "Paid ";  echo "</th>";
        
      echo "</tr>"; 

      while($row=mysqli_fetch_assoc($res))
      {
        if ($row['paid']==0)
          {$val="no";}
        else
          {$val="yes";}
        echo "<tr>";
        echo "<td>"; echo $row['card_no']; echo "</td>";
        echo "<td>"; echo $row['sum(fine_amt)']; echo "</td>";
        echo "<td>"; echo $val; echo "</td>";
        
        echo "</tr>";
      }
    echo "</table></center>";
  

}






if(isset($_POST['pay']))
{
  $flag=0;
  $id=$_POST['searchName'];

  $res2=mysqli_query($db,"select sum(fine_amt) from fines where card_no='$id';");
   while($row=mysqli_fetch_assoc($res2))
      {

       // check for due amount
        if ($row['sum(fine_amt)']==0)
          {$flag=2;
            echo "This card ID does not have any balance due."; echo $row['sum(fine_amt)'];break;}
      }

if($flag==0)

{$res1=mysqli_query($db,"select Date_in from book_loans where card_no='$_POST[searchName]';");
while($row=mysqli_fetch_assoc($res1))
  
    // check if all borrowed books are returned
  {if(is_null($row['Date_in']))
      {$flag=1;
      echo "All books borrowed by this card ID have not yet been returned. Payment will not be accepted.";break;}
  }
}

if($flag==0)
    {$res=mysqli_query($db,"update fines set paid=1 where card_no='$_POST[searchName]';");
    if($res){echo "<center>Payment recorded!<center>";} else {echo "<center>Error while recording payment.<center>";}
    }
}

?>

<!-- highlight selected rows in the table and move card id from the selected row into the corresponding textbox -->
<script type="text/javascript">
    
                var table = document.getElementById("table1");
                for(var i = 1; i < table.rows.length; i++)
                {
                    table.rows[i].onclick = function()
                    {
                         if(typeof index !== "undefined")
                         {table.rows[index].classList.toggle("selected");}
                          index = this.rowIndex;
                        // add class selected to the row
                        this.classList.toggle("selected");
                         document.getElementById("payId").value = this.cells[0].innerHTML;
                         
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
