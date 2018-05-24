<?php
/**
 * NGen Story Generator.
 *
 * @version 1.0
 * @author Megaweapon
 */

require('References.php');

$story_break = ['story' => '','tags' => []];

if ($_FILES[0]['error'] > 0) 
{
    echo "false";
}
else
{
    $tmp_path = $_FILES[0]['tmp_name'];
    $copy_path = UPLOAD_PATH.STORY_FILE;
    $isMoved = move_uploaded_file($tmp_path,$copy_path);
    echo $isMoved === true ? "true" : "false";
}