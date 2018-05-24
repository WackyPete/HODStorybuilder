<?php
namespace Hod;

/**
 * Upload short summary.
 *
 * Upload description.
 *
 * @version 1.0
 * @author Nathan
 */
class UploadStory
{   
    public static function Upload()
    {
        $return = '';        
        if($_FILES['file']['error'] > 0)
        {
            $return  = 'Error: ' . $_FILES['file']['error'] . '<br>';
        }
        else
        {

        }

        return $return;
    }

    public static function Download()
    {
        return null;
    }

}