<?php

namespace App\Library;

/**
 * Class Console
 * @package App\Library
 * @method static string bold(string $text, string $_=null)
 * @method static string black(string $text, string $_=null)
 * @method static string blue(string $text, string $_=null)
 * @method static string green(string $text, string $_=null)
 * @method static string cyan(string $text, string $_=null)
 * @method static string red(string $text, string $_=null)
 * @method static string purple(string $text, string $_=null)
 * @method static string brown(string $text, string $_=null)
 * @method static string light_gray(string $text, string $_=null)
 * @method static string normal(string $text, string $_=null)
 * @method static string dim(string $text, string $_=null)
 * @method static string dark_gray(string $text, string $_=null)
 * @method static string light_blue(string $text, string $_=null)
 * @method static string light_green(string $text, string $_=null)
 * @method static string light_cyan(string $text, string $_=null)
 * @method static string light_red(string $text, string $_=null)
 * @method static string light_purple(string $text, string $_=null)
 * @method static string yellow(string $text, string $_=null)
 * @method static string white(string $text, string $_=null)
 */
class Console {

    /**
     * @var array list of text colors
     */
    static $foreground_colors = array(
        'bold'         => '1',    'dim'          => '2',
        'black'        => '0;30', 'dark_gray'    => '1;30',
        'blue'         => '0;34', 'light_blue'   => '1;34',
        'green'        => '0;32', 'light_green'  => '1;32',
        'cyan'         => '0;36', 'light_cyan'   => '1;36',
        'red'          => '0;31', 'light_red'    => '1;31',
        'purple'       => '0;35', 'light_purple' => '1;35',
        'brown'        => '0;33', 'yellow'       => '1;33',
        'light_gray'   => '0;37', 'white'        => '1;37',
        'normal'       => '0;39',
    );

    /**
     * @var array list of background colors
     */
    static $background_colors = array(
        'black'        => '40',   'red'          => '41','bold'         => '1',
        'green'        => '42',   'yellow'       => '43',
        'blue'         => '44',   'magenta'      => '45',
        'cyan'         => '46',   'light_gray'   => '47',
    );

    /**
     * @var array liust of options
     */
    static $options = array(
        'underline'    => '4',    'blink'         => '5', 
        'reverse'      => '7',    'hidden'        => '8',
    );

    /**
     * @var string end of line
     */
    static $EOF = "\n";

    /**
     * Logs a string to console.
     * @param  string $str Input String
     * @param  string $color Text Color
     * @param  boolean $newline Append EOF?
     * @param  [type]  $background Background Color
     * @return string Formatted output
     */
    public static function log($str = '', $color = 'normal', $newline = true, $background_color = null)
    {
        if( is_bool($color) )
        {
            $newline = $color;
            $color   = 'normal';
        }
        elseif( is_string($color) && is_string($newline) )
        {
            $background_color = $newline;
            $newline          = true;
        }
        $str = $newline ? $str . self::$EOF : $str;

        return self::$color($str, $background_color);
    }
    
    /**
     * Catches static calls (Wildcard)
     * @param  string $foreground_color Text Color
     * @param  array  $args             Options
     * @return string                   Colored string
     */
    public static function __callStatic($foreground_color, $args)
    {
        $string         = $args[0];
        $colored_string = "";
 
        // Check if given foreground color found
        if( isset(self::$foreground_colors[$foreground_color]) ) {
            $colored_string .= "\033[" . self::$foreground_colors[$foreground_color] . "m";
        }
        else{
            die( $foreground_color . ' not a valid color');
        }
        
        array_shift($args);

        foreach( $args as $option ){
            // Check if given background color found
            if(isset(self::$background_colors[$option])) {
                $colored_string .= "\033[" . self::$background_colors[$option] . "m";
            }
            elseif(isset(self::$options[$option])) {
                $colored_string .= "\033[" . self::$options[$option] . "m";
            }
        }
        
        // Add string and end coloring
        $colored_string .= $string . "\033[0m";
        
        return $colored_string;
        
    }
 
    /**
     * Plays a bell sound in console (if available)
     * @param  integer $count Bell play count
     */
    public static function bell($count = 1) {
        echo str_repeat("\007", $count);
    }
}
