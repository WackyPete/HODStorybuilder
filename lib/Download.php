<?php
/**
 * NGen Story Generator.
 *
 * @version 1.0
 * @author Megaweapon
 */

include('Definitions.php');

header('Content-type: text/plain');
header("Content-Description: File Transfer");
header('Content-Disposition: attachment; filename="'.STORY_FILE.'"');
readfile(STORY_PATH);