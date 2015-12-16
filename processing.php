<?php
$handle = fopen("stresses.txt", "r");
if ($handle) {
    $i == 0;
    while (($line = fgets($handle)) !== false && $i < 10) {
        echo $line."<br>";
        $i++;
    }
    fclose($handle);
} else {
    echo "you f*cked up, chris"
    // error opening the file.
}
?>