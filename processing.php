<?php
// This script will process the two dictionaries, creating a database
// ID | Word | Syllables | Stresses (e.g. 0,1,0) | txtout (e.g. dic'-tion-a-ry) | htmlout (e.g. <b>dic</b>tionary) 

// We're going to process the stresses file
// Each line is in the format WORD Stresses/Phonemes
// The stresses are 0 = no stress, 1 = primary, 2 = secondary
// e.g. AARDVARK  AA1 R D V AA2 R K
// The information we should extract the word (aardvark), and the stress structure (12)
$handle = fopen("stresses.txt", "r");
if ($handle) {
    $i = 0;
    while (($line = fgets($handle)) !== false && $i < 200) {
        $word = strtolower(substr($line, 0, strpos($line, " "))); //get the word 
        $word = preg_replace("/[^a-zA-Z'\.]+/", '', $word);//strip everything except letters, dashes, and apostrophes
        $syllables = substr($line, strpos($line, " "), -1); //get the syllables
        $syllables = preg_replace("/[^0-2]+/", '', $syllables);//strip down the syllables to just 0, 1, 2
        echo $word." | ".$syllables."<br>";
        $i++;
        
    }
    fclose($handle);
} else {
    echo "you f*cked up, chris";
    // error opening the file.
}
?>