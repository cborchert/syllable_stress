<?php

// We're going to process the syllables file
// Each line is just the syllables separated by spaces and or dashes
// aard-vark
// The information we should extract the word (aardvark), and the syllables(aard-vark)

$servername = "localhost";
$username = "cborcher_syllabl";
$password = "";//lol sorry
$dbname = "cborcher_syllablestress";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    //set up the input file
    $handle = fopen("syllables.txt", "r");
    if ($handle) {
        //go through each line
        while (($line = fgets($handle)) !== false ) {
            $line = rtrim($line);//knock off the carriage return
            $line = strtolower(str_replace( "'", "\'", preg_replace("/[^-a-zA-Z'\.\s]+/", '', $line))); //lowercase line with just dashes a-z . and space
            $word = str_replace( "-", "", $line); //strip out the dashes
            $syllables = $line; //why not?
            
            echo $word." | ".$syllables."<br>";
            
            //Set up the record
            $sql = "INSERT INTO word_syl (word, syllables) VALUES ('$word','$syllables')";
            //try to store it
            if ($result = $conn->query($sql)) {
                var_dump($result);
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
//close the connection
$conn->close();

?>