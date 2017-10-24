<?php include 'header.php';?>

<div class="container">

<h2>Furnizori</h2>

<table>
  <tr>
    <th>Nume Furnizor</th>
    <th>Prdus furnizat</th>
    <th>Adresa</th>
    <th>Telefon </th>
  </tr>
  

<?php

$username = "root";
$password = "";
$servername = "127.0.0.1"; 
$dbname   = "Supermarket";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "SELECT FR.Nume AS Furn, P.Denumire AS Prod, FR.Adresa AS Adr, Fr.Telefon AS Tel
        FROM Furnizor AS FR , Factura AS F, Intrari_produse AS IP, Produs AS P
        WHERE FR.IDFurnizor = F.IDFurnizor AND IP.IDFactura = F.IDFactura AND P.IDProdus = IP.IDProdus
        ORDER BY FR.Nume";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    
    
    while($row = $result->fetch_assoc()) {
       echo '<tr>';  
       echo '<td>' . $row["Furn"] . '</td>';
       echo '<td>' . $row["Prod"] . '</td>';
       echo '<td>' . $row["Adr"] . '</td>';
       echo '<td>' . $row["Tel"] . '</td>';
       echo '</tr>';
    }
   echo '</table>';
   
} 



?>

</br>
<div class="col-sm-12 wowload fadeInUp">
    <div class="rooms">
      <form method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>"> 
      <input type="submit" value="Cantitate/prod intrate" id="stoc" name="stoc"  class="btn btn-default">
      </br> </br>
      <input type="submit" value="Cel mai vandut produs" id="best" name="best"  class="btn btn-default">
      </br> </br>
      <input type="submit" value="Anul cu cele mai multe achizitii" id="an" name="an"  class="btn btn-default">
      </form>

      </div>
    </div>


<?php 
       if (isset($_POST['stoc'])) : 
            echo '<table>
            <tr>
              <th>Denumire produs</th>
              <th>Cantitate intrata in Supermarket</th>
            </tr>';

            $sql = " SELECT R.Total_prod AS Total, P.Denumire AS Nume
                    FROM Produs P INNER JOIN  (SELECT SUM(Cantitate_produs) AS Total_prod, IDProdus
                                               FROM Intrari_produse
                                               GROUP BY IDProdus) AS R ON P.IDProdus = R.IDProdus
                    ORDER BY R.Total_prod";


              $result = $conn->query($sql);


        if ($result->num_rows > 0) {
          while($row = $result->fetch_assoc()) {
             echo '<tr>';  
             echo '<td>' . $row["Nume"] . '</td>';
             echo '<td>' . $row["Total"] . ' buc </td>';
             echo '</tr>';
          }
          echo '</table> </br>';
              
              } else {
                  echo "Error: " . $sql . "<br>" . $conn->error;
              }
        endif; 

        if (isset($_POST['best'])) : 

            $sql = "SELECT P.Denumire AS Prod, R.Total_prod  AS Cant
                    FROM Produs AS P INNER JOIN  (SELECT SUM(Cantitate_produs) AS Total_prod, IDProdus
                                                FROM Intrari_produse
                                                GROUP BY IDProdus) AS R ON P.IDProdus = R.IDProdus          
                    ORDER BY R.Total_prod DESC LIMIT 1";


              $result = $conn->query($sql);


        if ($result->num_rows > 0) {
           $row = $result->fetch_assoc();
           
           echo '<p> </br><img src="images/photos/answer.png" style= "width:80px; heigth:80px;""> Cel mai vandut produs este: <b>'. $row["Prod"]. ": " . $row["Cant"] . " bucati.</b> </br>" . "</p>";
              
              } else {
                  echo "Error: " . $sql . "<br>" . $conn->error;
              }
        endif; 

        if (isset($_POST['an'])) : 

            $sql = "SELECT YEAR(R.Data) AS year, SUM(R.Cantitate_produs) AS Cant
                    FROM (SELECT F.Data, P.Denumire, P.IDProdus, IP.Cantitate_produs 
                        FROM Factura F, Intrari_produse IP, Produs P
                        WHERE F.IDFactura = IP.IDFactura AND IP.IDProdus = P.IDProdus) AS R
                    GROUP BY R.Data
                    ORDER BY Cant DESC LIMIT 1";


              $result = $conn->query($sql);


        if ($result->num_rows > 0) {
           $row = $result->fetch_assoc();
           
           echo '<p> </br><img src="images/photos/answer.png" style= "width:80px; heigth:80px;"">Anul cu cele mai multe investitii: <b>'. $row["year"]. ": " . $row["Cant"] . " bucati.</b> </br>" . "</p>";
              
              } else {
                  echo "Error: " . $sql . "<br>" . $conn->error;
              }
        endif;




        echo ' </div> </br>';

?>


<!--   <div class="col-sm-6 wowload fadeInUp"><div class="rooms"><img src="images/photos/10.jpg" class="img-responsive"><div class="info"><h3>Luxirious Suites</h3><p> Missed lovers way one vanity wishes nay but. Use shy seemed within twenty wished old few regret passed. Absolute one hastened mrs any sensible</p><a href="room-details.php" class="btn btn-default">Check Details</a></div></div></div>  --> 


<?php include 'footer.php';?>