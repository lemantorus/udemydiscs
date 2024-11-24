<?php
    function getUniqValuesFromCol($conn,string $col){
                $query = "SELECT DISTINCT $col FROM coupons";
                $stmt = $conn->prepare($query);
                if(!$stmt){
                    die("Prepared failed".$conn->error);
                }
                if(!$stmt->execute()){
                    die("Execute failed".$conn->erorr);
                }
                $stmt->execute();
                $result = $stmt->get_result();
                $availableLanguages = [];
                while ($row = $result->fetch_assoc()) {
                    $availableLanguages[] = $row[$col];
                }
                $stmt->close();
                return $availableLanguages;
}
        $offset = $_POST['offset'];
        $servername = "localhost";
        $username = "coder";
        $password = "09050165andreipro21";
        $dbname = "udemydiscs";
        $conn = new mysqli($servername,$username,$password,$dbname);
        if ($conn->connect_error){
            die("error");
        }

        else{
            $data = getUniqValuesFromCol($conn,'language');
            foreach ($data as $key) {
                echo $key;
            }
        }
?>