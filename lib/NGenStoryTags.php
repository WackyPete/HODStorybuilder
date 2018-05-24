<?php

namespace Hod\StoryGen
{

    /**
     * NGen Story Generator.
     *
     * Story Tag
     *
     * @version 1.0
     * @author Megaweapon
     */
    class NGenStoryTags
    {
        public $Name;    
        public $Value = array();
        public $Required;    

        public function __construct($name, array $value, $required = false)    
        {
            $this->Name = $name;
            $this->Value = $value;
            $this->Required = $required == true ? 'True' : 'False';            
        }

        public function GetRandomValue()
        {
            $cnt = count($this->Value) - 1;
            return $this->Value[rand(0,$cnt)];
        }       
    }
}