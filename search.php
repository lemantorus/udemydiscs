<?php

        $servername = "localhost";
        $username = "coder";
        $password = "09050165andreipro21";
        $dbname = "udemydiscs";
        $conn = new mysqli($servername,$username,$password,$dbname);
        if ($conn->connect_error){
            die("error");
        }

        else{
            function getUniqValuesFromCol($conn,string $col){
                $query = "SELECT DISTINCT $col FROM coupons";
                $stmt = $conn->prepare($query);
                $stmt->execute();
                $result = $stmt->get_result();
                $uniqItems = [];
                while ($row = $result->fetch_assoc()) {
                    $uniqItems[] = $row[$col];
                }
                $stmt->close();
                return $uniqItems;
}
            function conditionsGet(){
                $result = json_decode(file_get_contents("php://input"),true);
                $categorie = $result['category'];
                $query = $result['query'];
                $languages = $result['languages']??[];
                $rating_from =$result['rating']['from'];
                $rating_to = $result['rating']['to'];
                return [
                    'query'=>$query??"",
                    'categorie'=>$categorie??"",
                    'languages'=>$languages??[],
                    'rating_from'=>$rating_from??0,
                    'rating_to'=>$rating_to??5,
            ];
            }
            $rd = conditionsGet();
            $langlist = $rd['languages'];
            if(count($langlist)>0) {
                $offset = $_GET['offset']??0;
                $placeholders = implode(',',array_fill(0,count($langlist),"?"));
                $query = "SELECT * FROM coupons WHERE category LIKE ? AND rating BETWEEN ? AND ? AND name LIKE ? AND language IN ($placeholders) LIMIT 10 OFFSET ? ";

            }
            else{
                $query = "SELECT * FROM coupons WHERE category LIKE ? AND rating BETWEEN ? AND ? AND name LIKE ? LIMIT 10 OFFSET ? ";
            };

            $stmt = $conn->prepare($query);
            $params = [
                $rd['categorie'],
                $rd['rating_from'],
                $rd["rating_to"],
                '%'.$rd['query'].'%'
            ];
            $params = array_merge($params,$langlist);
            $params[]=$offset;
            $types = "sdds" . implode('', array_fill(0, count($langlist), "s"))."i";

            $stmt->bind_param($types,...$params);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            echo json_encode($data);
            $conn->close();

        }
?>