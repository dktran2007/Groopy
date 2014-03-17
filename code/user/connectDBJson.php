<?php
    $data = array();
    // Query that retrieves events
    $query = "SELECT * FROM Tasks ORDER BY id";

    // connection to the database
    $connection = mysqli_connect("localhost", "groopyuser", "groopyuser", "Groopy_Schema");
    if (mysqli_connect_errno($connection))
    {
        echo "Fail to connect";
    }
    // Execute the query
    $result = mysqli_query($connection, $query);
    if(!$result){
        die('2 error: ' . mysqli_error($connection));
    }
 
    //looping through result and write to json
    file_put_contents('tasksJson.json', '');
    $fp = fopen('tasksJson.json', 'w');
    while($row = mysqli_fetch_assoc($result)){
        $data[] = array(
            'id' => $row['id'],
            'title' => $row['assignedTo'],
            'start' => $row['deadline'],
            'url' => $row['task'],
            'allday' => false
        );
        $json_data = json_encode($data);
        fwrite($fp, $json_data);
    }
    fclose($fp);
    
    $json_data = file_get_contents('tasksJson.json');
    echo json_encode($data);
?>