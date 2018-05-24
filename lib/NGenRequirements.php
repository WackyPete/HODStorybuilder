<?php

namespace Hod\StoryGen
{
    /**
     * NGen Story Generator.
     *
     * NGenRequirements. All Hardcoded tag values
     *
     * @version 1.0
     * @author Megaweapon
     */
    class NGenRequirements
    {
        
        public static $REQUIRED_TAGS = ['BOSSNAME','BOSSTYPE','REWARDNAME','MAPNAME'];

        public static $OPTIONAL_TAGS = ['BOSSRACE','MINIONRACE','BOSSHISHER','BOSSHIMHER','BOSSHESHE','MINIONRACES'];

        public static $BossNames = ['Jotaz the Algid' => 'F',
                                    'Kampesnar the Coarse' => 'M',
                                    'Reusbrudd the Wind' => 'F',
                                    'Shane the Viper' => 'M',
                                    'Sara the Wicked' => 'F',
                                    'The Hunter of Dreams' => 'F',
                                    'Vincent the Sellsword' => 'M'];

        public static $RewardNames = ['Cloth armor of legendary quality',
                                      'Plate armor of heroic quality',
                                      'Leather armor of common quality',
                                      'Cloth armor of rare quality',
                                      'A collar of heroic quality',
                                      'An anklet of uncommon quality',
                                      'A longsword of legendary quality',
                                      'A battleaxe of uncommon quality',
                                      'An arbalest of rare quality'];

        public static $MapNames = ['The Icy Caves',
                                   'The Consecrated Chapel',
                                   'The Stormy Cliffs',
                                   'The Decayed Tomb',
                                   'The Rotting Lighthouse',
                                   'The Lifeless Plateau',
                                   'The Derelict Chapel',
                                   'The Frigid Volcano',
                                   'The Wailing Temple',
                                   'The Hellish Catacomb',
                                   'The Glaciated Precipice'];

        public static $BossTypes = ['Mage',
                                    'Archer',
                                    'Rogue',
                                    'Warrior',
                                    'Paladin',
                                    'Healer'];

        public static $SingleRace = ['Orc',
                                'Human',
                                'Demon',
                                'Zombie',
                                'Ghoul',
                                'Lich',
                                'Frost Giant',
                                'Fire Giant',
                                'Cultist',                                
                                'Dark Elf',
                                'Wood Elf',
                                'Goblin',
                                'Kobold',
                                'Fire Elemental',
                                'Ice Elemental',
                                'Storm Troll',
                                'Cave Troll'];

        public static $Races = ['Orcs',
                                'Humans',
                                'Demons',
                                'Zombies',
                                'Ghouls',
                                'Liches',
                                'Frost Giants',
                                'Fire Giants',
                                'Cultists',                                
                                'Dark Elves',
                                'Wood Elves',
                                'Goblins',
                                'Kobolds',
                                'Fire Elementals',
                                'Ice Elementals',
                                'Storm Trolls',
                                'Cave Trolls'];

        public static $Gender;


        public static function ProcessGenericTags($story)//:string
        {
            $base_story = $story;
            self::GetRandom($base_story, self::$REQUIRED_TAGS);
            self::GetRandom($base_story, self::$OPTIONAL_TAGS);
            return $base_story;
        }

        private static function GetRandomValue(array $list)//:string
        {
            $final_cnt = count($list) -1;
            return $list[rand(0,$final_cnt)];            
        }

        private static function ReplaceTag(&$base_story,$tag,$val)
        {            
            $name_upper = '['.$tag.']';
            $base_story = str_replace($name_upper,$val,$base_story);
            $name_lower = '{'.$tag.'}';
            
            if($tag == 'BOSSTYPE' || $tag == 'REWARDNAME' || in_array($tag,self::$OPTIONAL_TAGS,false))
            {                
                $base_story = str_replace($name_lower,strtolower($val),$base_story);
            }
            else
            {
                $base_story = str_replace($name_lower,$val,$base_story);
            }
        }
        
        private static function GetRandom(&$base_story,array $list)
        {   
            foreach($list as $item)
            {
                $rand_value = '';
                switch($item)
                {
                    //Required Tags
                    case 'BOSSNAME':                                            
                        $rand_value = self::GetRandomValue(array_keys(self::$BossNames));
                        self::$Gender = self::$BossNames[$rand_value];
                        break;                    
                    case 'BOSSTYPE':
                        $rand_value = self::GetRandomValue(self::$BossTypes);
                        break;
                    case 'REWARDNAME':
                        $rand_value = self::GetRandomValue(self::$RewardNames);
                        break;
                    case 'MAPNAME':
                        $rand_value = self::GetRandomValue(self::$MapNames);
                        break;         
                    //Optional Tags
                    case 'BOSSHISHER':
                        $rand_value = self::$Gender == 'M' ? 'His' : 'Her';
                        break;
                    case 'BOSSHIMHER':
                        $rand_value = self::$Gender == 'M' ? 'him' : 'her';
                        break;
                    case 'BOSSHESHE':
                        $rand_value = self::$Gender == 'M' ? 'He' : 'She';
                        break;
                    case 'BOSSRACE':
                        $rand_value = self::GetRandomValue(self::$SingleRace);
                        break;
                    case 'MINIONRACE':
                        $rand_value = self::GetRandomValue(self::$SingleRace);
                        break;
                    case 'MINIONRACES':
                        $rand_value = self::GetRandomValue(self::$Races);
                        break;                   
                    default:
                        $rand_value = '';
                        break;
                }

                if(!empty($rand_value))
                    self::ReplaceTag($base_story,$item,$rand_value);
            }           
        }
    }   
}