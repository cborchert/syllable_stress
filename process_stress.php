<?php
// We're going to process the stresses file
// Each line is in the format WORD Stresses/Phonemes
// The stresses are 0 = no stress, 1 = primary, 2 = secondary
// e.g. AARDVARK  AA1 R D V AA2 R K
// The information we should extract the word (aardvark), and the stress structure (12)

// The stresses.txt file can be useful for rhyming, too, btw. I'm basically throwing out half the info. 

$servername = "localhost";
$username = "";
$password = ""; //lol sorry
$dbname = "";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    //set up the input file
    $handle = fopen("stresses.txt", "r");
    if ($handle) {
        while (($line = fgets($handle)) !== false ) {
            $line = rtrim($line);//Knock off the carriage return from the line
            $word = strtolower(substr($line, 0, strpos($line, " "))); //get just the word, lower case 
            $word = rtrim(str_replace( "'", "\'", preg_replace("/[^a-zA-Z'\.]+/", '', $word)));//strip everything except letters, dashes, and apostrophes
            $stresses = substr($line, strpos($line, " ")); //get the stresses
            $stresses = preg_replace("/[^0-2]+/", '', $stresses);//strip down the stresses to just the numbers 0, 1, 2
            
            echo $word." | ".$stresses."<br>"; 
            
            //Set up the record
            $sql = "INSERT INTO word_stress (word, stresses)
            VALUES ('$word','$stresses')";
            //try to store it
            if ($conn->query($sql) === TRUE) {
                echo "New record created successfully <br>";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
        fclose($handle);
    } else {// error opening the file.
        echo "you f*cked up, chris";
    }
}
//close the connection
$conn->close();
?>