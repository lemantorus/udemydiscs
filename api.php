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
        return $availableLanguages;
        }
        $offset = $_GET['offset'];
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
        $data =[];
        while($row = $result->fetch_assoc()){
            $data['data'][] = $row;
        }
        $data['languages']=getUniqValuesFromCol($conn,'language');
        $data['categories']=getUniqValuesFromCol($conn,'category');

        echo json_encode($data);}
        $conn->close();

?>
