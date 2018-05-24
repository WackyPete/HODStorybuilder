<?php
/**
 * NGen Story Generator. 
 *
 * @version 1.0
 * @author Megaweapon
 */

include('References.php');
$ngen = new NGen();
$outputStory = '';
$outputTags = array();

if(isset($_GET['submit']))
{    
    $json = filter_input(INPUT_POST,'output',FILTER_UNSAFE_RAW);
    $story_post = json_decode($json,true);
    $story_output = array_values($story_post);    

    $type = $_GET['submit'];    
    $group = $story_output[0]['value'];
    $story = $story_output[1]['value'];  

    if(strlen(trim($group)) > 30 || empty($group))
    {
        echo 'Your story will need a group id of 30 characters or less';
    }
    else
    {
        if(empty($story))
        {
            echo 'Please Add a Story w/ Tags and Try again';
        }
        elseif(strlen(trim($story)) <= 1500)
        {
            $outputValues = array();
            $index = 0;
            $name = '';        
            foreach($story_output as $k => $v)
            {  
                if($v['name'] === 'StoryOutput') continue;            
                $k = $v['name'];
                $v = $v['value'];
                if(strpos($k,'TagName') !== false)
                {
                    $name = $v;
                    $name = strtoupper($name);
                    if(array_key_exists($name,$outputTags) ||
                        in_array($name,\Hod\StoryGen\NGenRequirements::$REQUIRED_TAGS) ||
                        in_array($name,\Hod\StoryGen\NGenRequirements::$OPTIONAL_TAGS))
                    {
                        echo '!TagError (Duplicate Tag): ['.$name.']';
                        return;
                    }
                    if(empty($v))
                    {
                        echo '!TagError (Tag Name Missing): [Story Tag ('.substr($k,0,strpos($k,'_')).')]';
                        return;
                    }
                    $outputTags[$name] = $k;
                }
                elseif(strpos($k,'TagValue') !== false)
                {                
                    $tagvalue = substr($k,0,strpos($k,'_'));
                    if(empty($v))
                    {
                        echo '!TagError (Tag Value Missing): [Story Tag ('.$tagvalue.')]';
                        return;
                    }
                    $tag = $tagvalue.'_TagName';
                    if($tagvalue != $index)
                        $outputValues = [];               
                    $outputValues[] = $v;
                    $outputTags[$name] = $outputValues;
                    $index = $tagvalue;
                }
            }

            $ngen->LoadStory($group,$story,$outputTags);

            if($type === 'Preview')
            {
                $outputStory = $ngen->GenerateStory();
                echo $outputStory;
            }
            elseif($type === 'Export')
            {
                $ngen->GenerateXML();
            }
        }    
        else
        {
            echo 'Keep story under 1500 characters in length';
        }
    }
}