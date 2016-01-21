<?php

/* CouponGenerator
 * Author: Frank Reduth
 *
 * usage:
 *	      minimal:
 *		Coupon::get(int amount)
 *
 *	      long:
 *		Coupon::setAttributes(int length, str prefix)->setCharset(str|array charset)->get(int amount)
 *
 *	      save:
 *		Coupon::saveToFile(path/filename, str separator)
*/

class Coupon
{
    
    private static $length = 10;
    private static $prefix = 'MQ';
    private static $charset;
    private static $coupons;
    
    /**
     * @param  $charset
     * @return static
     */
    public static function setCharset($charset = false) {
        if (!$charset) {
            $chars = [range('A', 'Z'), range('a', 'z'), range(0, 9) ];
            self::$charset = array_merge($chars[0], $chars[1], $chars[2]);
        } 
        else {
            if (is_string($charset)) {
                self::$charset = str_split($charset, 1);
            } 
            else {
                self::$charset = $charset;
            }
            self::$charset = array_values(array_unique(self::$charset));
        }

        return new static ();
    }
    
    /**
     * @param $length
     * @param $prefix
     * @return static
     */
    public static function setAttributes($length, $prefix) {
        self::$length = $length;
        self::$prefix = $prefix;
        return new static ();
    }
    
    /**
     * @param $amount
     * @return array
     * @throws Exception
     */
    public static function get($amount) {
        if (empty(self::$charset)) {
            self::setCharset();
        }
        $cs = self::$charset;
        $length = self::$length - strlen(self::$prefix);
        
        //endless loop protection you can use only 50% of all possible permutations
        $combinations = round(pow(count($cs), $length) * 0.5);
        try {
            if ($combinations < $amount) {
                throw new Exception("Your attributes allow only $combinations codes.");
            }
        }
        catch(Exception $e) {
            throw $e;
        }
        $max = count($cs) - 1;
        while (count(self::$coupons) != $amount) {
            $string = '';
            for ($i = 0; $i < $length; ++$i) {
                $string.= $cs[mt_rand(0, $max) ];
            }
            
            self::$coupons[$string] = self::$prefix . $string;
        }
        return self::$coupons;
    }
    
    /**
     * @param $filename
     * @param string $separator
     */
    public static function saveToFile($filename, $separator = "\r\n") {
        $data = implode($separator, self::$coupons);
        file_put_contents($filename, $data);
    }
}

