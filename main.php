<?php
    include 'connection.php';
    if(isset($_FILES['csv'])){
        $csv_mimetypes = array(
        'text/csv',
        'text/plain',
        'application/csv',
        'text/comma-separated-values',
        'application/excel',
        'application/vnd.ms-excel',
        'application/vnd.msexcel',
        'text/anytext',
        'application/octet-stream',
        'application/txt',
        );

        if(in_array($_FILES['csv']['type'],$csv_mimetypes)){
            $query = "SELECT * FROM format";
            $result = mysqli_query($conn,$query);
            $count  = mysqli_num_rows($result);
            if($count==0) {
                $uploaded_file = $_FILES['csv']['tmp_name'];
                $command = escapeshellcmd("python first_line.py $uploaded_file");
                $output = shell_exec($command);
                $output = substr($output, 1, -1);
                $output = substr($output, 0, -1);
                $str_arr = explode (",", $output); 
                $count_database = count($str_arr);

                $string_table = "CREATE Table Dat(";
                foreach($str_arr as $str_arr){
                    $query = "INSERT INTO format(format) VALUES ($str_arr)";
                    $result = mysqli_query($conn,$query);
                    $str_arr = str_replace("'", '', $str_arr);
                    $string_table = $string_table."$str_arr varchar(200),";
                    
                }

                $string_table = rtrim($string_table,",");
                $string_table =  $string_table.")";
                $result = mysqli_query($conn,$string_table);
                
                $sql = "INSERT into dat values";

                $i=0;
                $j=0;
                if($_FILES["csv"]["size"] > 0)
		        {
                $file = fopen($uploaded_file, "r");
                    while (($getData = fgetcsv($file, 10000, ",")) !== FALSE)
                    {
                        if($j>0){
                            for($i=0;$i<$count_database;$i++){
                                if($j>1){
                                    $sql = $sql."'".$getData[$i]."'".',';
                                }
                                
                        }
                            $sql = rtrim($sql,",");    
                            if($j>1){
                                $sql = $sql.'),';
                            }
                            
                            $sql = $sql.'(';
                        }
                        if($j==0){
                                $sql = rtrim($sql,"),");
                            }
                        $j++;
                    }
                }
                $sql = rtrim($sql,"(");
                $sql = rtrim($sql,",");
                $result = mysqli_query($conn,$sql);
                echo "<script>
                    alert('First File Uploaded Sucesfully');
                    window.location = 'http://dmbi.com';
                </script>";
            }
            else{
                $value = array();
                $final = array();
                $query = "SELECT * FROM format";
                $result = mysqli_query($conn,$query);
                while($row = mysqli_fetch_array($result)){
                    array_push($value,"$row[0]");
                }
                $count_uploaded = count($value);


                $uploaded_file = $_FILES['csv']['tmp_name'];
                $command = escapeshellcmd("python first_line.py $uploaded_file");
                $output = shell_exec($command);
                $output = substr($output, 1, -1);
                $output = substr($output, 0, -1);
                $str_arr = explode (",", $output); 
                $count_uploaded = count($str_arr);
                

                $databasefinal = array_map(function($piece){
                    $piece = str_replace("'","",$piece);
                    $piece = str_replace(" ","",$piece);
                    return (string) $piece;
                }, $value);


                $fileuploaded = array_map(function($piece){
                    $piece = str_replace("'","",$piece);
                    $piece = str_replace(" ","",$piece);
                    return (string) $piece;
                }, $str_arr);

               
                $x=0;
                foreach($fileuploaded as $databasevalue){

                    if((in_array($databasevalue,$value))){
                        
                    }
                    else{
                        array_push($final,$databasevalue);
                    }
                    
                }
                $alt = "";
                foreach($final as $str_arr){
                    $str_arr = (string)$str_arr;
                    $query = "INSERT INTO format(format) VALUES ('$str_arr')";
                    $alt = "ALTER TABLE dat ADD COLUMN $str_arr VARCHAR(1000)";
                    $result = mysqli_query($conn,$alt);
                    $result = mysqli_query($conn,$query);
                }
                


                $count_database = count($fileuploaded);
                $sql = "INSERT into dat (";
                $i=0;
                $j=0;
                if($_FILES["csv"]["size"] > 0)
		        {
                $file = fopen($uploaded_file, "r");
                    while (($getData = fgetcsv($file, 10000, ",")) !== FALSE)
                    {
                        if($j>0){
                            $sql = $sql.'(';
                        }

                            for($i=0;$i<$count_database;$i++){
                                if($j==0){
                                    $getData[$i] = str_replace("'","",$getData[$i]);
                                    $lol = str_replace(" ","",$getData[$i]);
                                    $sql = $sql.$lol.',';
                                }
                                elseif($j>0){
                                    $sql = $sql."'".$getData[$i]."'".',';
                                }
                                
                                    

                        }
                        if($j>0){
                            $sql = rtrim($sql,",");
                            $sql = $sql.'),';
                        }
                        if($j==0){
                            $sql = rtrim($sql,",");
                                        $sql = $sql.') values ';
                        }
                        
                        $j++;
                    }
                }
                $sql = rtrim($sql,"(");
                $sql = rtrim($sql,",");
                
                $result = mysqli_query($conn,$sql);
                echo "<script>
        alert('Data uploaded');
        window.location = 'http://dmbi.com';
        </script>";


            }

        } 
        else 
        {
        echo "<script>
        alert('Please Upload CSV Only');
        window.location = 'http://dmbi.com';
        </script>";

        }
    }
?>