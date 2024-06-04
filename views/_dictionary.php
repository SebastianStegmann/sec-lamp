<?php

$dictionary = file_get_contents('../dictionary.json');
$dictionary = json_decode($dictionary, true); // convert text to object

?>