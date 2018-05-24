<?php
require('References.php');
    
use Hod\StoryGen\NGenStoryTags;
use Hod\StoryGen\NGenRequirements;

/**
    * NGen Story Generator.
    *
    * NGen Story Builder Tool
    *
    * @version 1.0
    * @author Megaweapon
    */
class NGen
{       
    private $_group;
    private $_story;
    private $_taglist = array();
    private $_isStoryLoaded = false;

    public function CreateTag($name,array $values,$isRequired)
    {
        return new NGenStoryTags($name,$values,$isRequired);
    }

    private function ValidateRequiredTags($story)
    {
        $count = count(NGenRequirements::$REQUIRED_TAGS);
        $t_count = 0;
        $missing = '';
        foreach(NGenRequirements::$REQUIRED_TAGS as $tag)
        {
            if(strpos($story,$tag) !== false)            
                $t_count++;                    
            else            
                $missing .= ' '.$tag;
        }

        if(!empty($missing))
            echo 'MISSING REQUIRED TAGS: '.$missing;
        
        return $t_count === $count;
    } 

    public function LoadStory($group,$story,array $tags)
    {
        $this->_isStoryLoaded = false;
        if(empty($story) && empty($group)) return;
        if(!$this->ValidateRequiredTags($story)) return;        
        $this->_story = $story;
        $this->_group = $group;
        if(count($tags) > 0)
        {                    
            $this->_taglist = [];
            foreach($tags as $name => $desc)
            {
                $tval = new NGenStoryTags($name,$desc,false);
                $this->_taglist[] = $tval;                
            }
        }

        $this->_isStoryLoaded = true;        
    }        

    public function GenerateXML()
    {
        if(!$this->_isStoryLoaded) return;
        $xmlstory = $this->_story;
        $xmlgroup = $this->_group;
        $xml = new DOMDocument();
        $root = $xml->appendChild($xml->createElement('Story_Generator'));
        $root->appendChild($xml->createElement('Group',trim($xmlgroup)));
        $root->appendChild($xml->createElement('Story',trim($xmlstory)));        
        $story_root = $root->appendChild($xml->createElement('Story_Fields'));

        foreach($this->_taglist as $tag)
        {
            $t = $story_root->appendChild($xml->createElement('Field'));
            $t->appendChild($xml->createAttribute('Name'))->appendChild($xml->createTextNode($tag->Name));            
            foreach($tag->Value as $value)
            {
                $t->appendChild($xml->createElement('Value',$value));
            }
        }

        $xml->formatOutput = true;
        $this->PushFile($xml->saveXML());
    }
    
    public function GenerateStory()
    {
        if(!$this->_isStoryLoaded) return '';        
        $story = NGenRequirements::ProcessGenericTags($this->_story);        
        foreach($this->_taglist as $NStory)
        {
            $rand_value = $NStory->GetRandomValue();
            $name_upper = '['.$NStory->Name.']';
            $name_lower = '{'.$NStory->Name.'}';
            $uc_first = '*'.$NStory->Name.'*';

            $story = str_replace(strtoupper($name_upper),$rand_value,$story);
            $story = str_replace(strtoupper($name_lower),strtolower($rand_value),$story);
            $story = str_replace(strtoupper($uc_first),ucfirst($rand_value),$story);
        }

        return $story;        
    }

    private function PushFile($xml)    
    {   
        file_put_contents(STORY_PATH,$xml);
    }

    private function ReturnTags($story)
    {        
        $names = array();
        $pattern_tag_val = '/\[([^\]]*)\]/';        
        preg_match_all($pattern_tag_val, $story, $matches);               
        
        foreach($matches[0] as $n)
            $names[] = $n;        

        return count($names) === 0 ? null : $names;        
    }

}