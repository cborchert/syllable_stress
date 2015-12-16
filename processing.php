<?php
// This script will process the two dictionaries, creating a database
// ID | Word | Syllables | Stresses (e.g. 0,1,0) | txtout (e.g. dic'-tion-a-ry) | htmlout (e.g. <b>dic</b>tionary) 

// We're going to process the stresses file
// Each line is in the format WORD Stresses/Phonemes
// The stresses are 0 = no stress, 1 = primary, 2 = secondary
// e.g. AARDVARK  AA1 R D V AA2 R K
// The information we should extract the word (aardvark), and the stress structure (12)

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "syllablestress";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    $handle = fopen("stresses.txt", "r");
    if ($handle) {
        while (($line = fgets($handle)) !== false ) {
            $word = strtolower(substr($line, 0, strpos($line, " "))); //get the word 
            $word = str_replace( "'", "\'", preg_replace("/[^a-zA-Z'\.]+/", '', $word));//strip everything except letters, dashes, and apostrophes
            $stresses = substr($line, strpos($line, " "), -1); //get the stresses
            $stresses = preg_replace("/[^0-2]+/", '', $stresses);//strip down the stresses to just 0, 1, 2
            echo $word." | ".$stresses."<br>";
            
            $sql = "INSERT INTO words (word, stresses)
            VALUES ('$word','$stresses')";
            
            if ($conn->query($sql) === TRUE) {
                echo "New record created successfully";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
        fclose($handle);
    } else {
        echo "you f*cked up, chris";
        // error opening the file.
    }
}

$conn->close();

?>