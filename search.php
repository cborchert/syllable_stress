<?php
//Set up GET vars
$search = strtolower($_GET["word"]);
$output = strtolower($_GET["output"]);

//Set up return value
$word = array('term'=>$search, 'stresses'=>array(), 'syllables'=>'' );

//Set up connection
$servername = "localhost";
$username = "cborcher_syllabl";
$password = "";//lol sorry
$dbname = "cborcher_syllablestress";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else { //Do your thing!
    
    //Get the results for stresses
    $sql = "SELECT * FROM word_stress WHERE word='$search'";
    if ($result = $conn->query($sql)) { //multiple ways
        while($row = $result->fetch_assoc()){
            array_push($word['stresses'], $row['stresses']);
        }
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    
    //Get the results for syllables
    $sql = "SELECT * FROM word_syl WHERE word='$search'";
    if ($result = $conn->query($sql)) {
        if($row = $result->fetch_assoc()){ //only one way, right?
            $word['syllables'] = $row['syllables'];
        }
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    
}
//Close the connections
$conn->close();

//Prepare the output
//set up syllables as an array
$syllables = explode("-", $word['syllables']);
$styled = array();
foreach($word['stresses'] as $stresses) {
    $stress_out = "";
    for($i = 0; $i < count($syllables); $i++) {
        if($stresses[$i] == '1') {
            $stress_out .= "<span class='stress_1'>".$syllables[$i]."</span> ";
        } elseif($stresses[$i] == '2') {
            $stress_out .= "<span class='stress_2'>".$syllables[$i]."</span> ";
        } else {
            $stress_out .= $syllables[$i]." ";
        }
    }
    array_push($styled, $stress_out);
}

if($output == "vardump") {
    var_dump($word);
} else if($output == "json") {
    echo json_encode($word);
} else {
    echo "<style>
            .stress_1, .stress_2 { font-weight: bold; }
            .stress_1 {text-decoration: underline; }
            body {padding: 20px; color: #223; font-family: monospace;}
            h1, p {margin: 5px 0;}
            p { margin-left: 10px; padding-left: 7.5px; border-left: 3px solid #ccd; font-size: 16px; }
          </style>";
    echo "<h1>".$word['term']."</h1>";
    foreach($styled as $style) {
        echo "<p>".$style."</p>";
    }
}

?>