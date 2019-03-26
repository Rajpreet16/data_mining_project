<?php
include 'connection.php';
$query = "SELECT * FROM dat";
$result = mysqli_query($conn,$query);

if ($result->num_rows > 0) 
   {
      echo "<table id='tbl'><tr>";
      $field=$result->fetch_fields();
// output column names  
     foreach ($field as $col)
     {
        echo "<th>".$col->name."</th>";
     }
     echo "</tr>";

// output data of each row
     while($row = $result->fetch_row()) 
     {
        echo "<tr>";

        for ($i=0;$i<$result->field_count;$i++)
        {
           echo "<td>".$row[$i]."</td>";
        }

        echo "</tr>";
      }
     echo "</table>";

  }

else  
{
 echo "No data found";
}



?>