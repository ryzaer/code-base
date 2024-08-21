<?php
namespace parse;
/**
* UUID class
*
* The following class generates VALID RFC 4122 COMPLIANT
* Universally Unique IDentifiers (UUID) version 3, 4 and 5.
*
* UUIDs generated validates using OSSP UUID Tool, and output
* for named-based UUIDs are exactly the same. This is a pure
* PHP implementation.
*
* @author Andrew Moore
* @link http://www.php.net/manual/en/function.uniqid.php#94959
*/
class uuid
{
    /**
    *
    * Generate v2 UUID
    *
    * Version 2 UUIDs are pseudo-random.
    */
    public static function v2()
    {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        
        // 32 bits for "time_low"
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        
        // 16 bits for "time_mid"
        mt_rand(0, 0xffff),
        
        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand(0, 0x0fff) | 0x4000,
        
        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand(0, 0x3fff) | 0x8000,
        
        // 48 bits for "node"
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

    /**
    * Generate v4 UUID
    *
    * Version 4 UUIDs are named based. They require a namespace (another
    * valid UUID) and a value (the name). Given the same namespace and
    * name, the output is always the same.
    *
    * @param uuid $namespace
    * @param string $name
    */
    public static function v4($stat_uuid, $salt)
    {
        if(!self::is_valid($stat_uuid)) return false;
        
        // Get hexadecimal components of stat_uuid
        $nhex = preg_replace('/[^a-z0-9]/','', $stat_uuid);
        
        // Binary Value
        $nstr = '';
        
        // Convert stat_uuid UUID to bits
        for($i = 0; $i < strlen($nhex); $i+=2)
        {
            $nstr .= chr(hexdec($nhex[$i].$nhex[$i+1]));
        }
        
        // Calculate hash value
        $hash = hash('md4',$nstr . $salt);
        
        return sprintf('%08s-%04s-%04x-%04x-%12s',
        
        // 32 bits for "time_low"
        substr($hash, 0, 8),
        
        // 16 bits for "time_mid"
        substr($hash, 8, 4),
        
        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 3
        (hexdec(substr($hash, 12, 4)) & 0x0fff) | 0x3000,
        
        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        (hexdec(substr($hash, 16, 4)) & 0x3fff) | 0x8000,
        
        // 48 bits for "node"
        substr($hash, 20, 12)
        );
    }
    
    
    
    /**
    * Generate v5 UUID
    *
    * Version 5 UUIDs are named based. They require a namespace (another
    * valid UUID) and a value (the name). Given the same namespace and
    * name, the output is always the same.
    *
    * @param uuid $namespace
    * @param string $name
    */
    public static function v5($name=null,$algo='md5')
    {
        $name = $name?$name:self::v2();
        // Calculate hash value
        $hash = hash($algo,$name);
        
        return sprintf('%08s-%04s-%04x-%04x-%12s',
        
        // 32 bits for "time_low"
        substr($hash, 0, 8),
        
        // 16 bits for "time_mid"
        substr($hash, 8, 4),
        
        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 5
        (hexdec(substr($hash, 12, 4)) & 0x0fff) | 0x5000,
        
        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        (hexdec(substr($hash, 16, 4)) & 0x3fff) | 0x8000,
        
        // 48 bits for "node"
        substr($hash, 20, 12)
        );
    }
    public function split_into($string, $num) {
        for ($i = 0; $i < strlen($string)-$num+1; $i++) {
            $result[] = substr($string, $i, $num);
        }
        return $result;
    }
    public static function v6($front=null,$end=null){
        $func = new uuid();
        $rand = $func->v2();
        $char = $func->split_into(preg_replace('/[^a-z]/','',$rand),2);
        $nums = $func->split_into(preg_replace('/[^0-9]/','',$rand),4);
        return $front.strtoupper($char[array_rand($char,1)]).$nums[array_rand($nums,1)].$end;
    } 
    public static function is_valid($uuid) {
        return preg_match('/^\{?[0-9a-f]{8}\-?[0-9a-f]{4}\-?[0-9a-f]{4}\-?'.
        '[0-9a-f]{4}\-?[0-9a-f]{12}\}?$/i', $uuid) === 1;
    }
}

?>
