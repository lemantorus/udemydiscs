<?php   
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
        $sql = "SELECT * FROM coupons LIMIT 10 OFFSET $offset";
        $result = $conn->query($sql);
        $data = [];
        while($row = $result->fetch_assoc()){
            $data[] = $row;
        }
        echo json_encode($data);}
        $conn->close();

?>
