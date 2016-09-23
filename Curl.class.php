<?php
/* CurlClass
 * Author: Frank Reduth
 *
 * usage:
 *    minimal:
 *      Curl::get(string url)
 *      Curl::post(string url,string|array parameters)
 *
 *    long:
 *      Curl::proxie(str proxy)->timeOut(int seconds)...->get(str url)
 *
 */
class Curl
{
    private static $__cookieFile = 'cookie';
    private static $__proxy;
    private static $__userAgent = 'Mozilla/5.0 (Windows; U; Windows NT 6.1; de; rv:1.9.2.24) Gecko/20111103 Firefox/3.6.24';
    private static $__compression = 1;
    private static $__timeOut = 180;
    private static $__followLocation = 1;
    private static $__returnTransfer = 1;

    /**
     * Set proxie
     * @param  string $proxy IP:PORT
     * @return new static
     */
    public static function proxie($proxie)
    {
        self::$__proxie = $proxie;
        return new static ();
    }

    /**
     * Set Compression
     * @param  string $compression
     * @return new static
     */
    public static function compression($compression = 'gzip')
    {
        self::$__compression = $compression;
        return new static ();
    }


    /**
     * Get CoockieFile
     * @return string filename
     */
    public static function getCookieFile()
    {
        return self::$__cookieFile;
    }

    /**
     * Set Timeout
     * @param  integer $timeOut
     * @return new static
     */
    public static function timeOut($timeOut)
    {
        self::$__timeOut = $timeOut;
        return new static ();
    }

    /**
     * Set ReturnTransfer
     * @param  integer $returnTransfer
     * @return new static
     */
    public static function returnTransfer($returnTransfer)
    {
        self::$__returnTransfer = $returnTransfer;
        return new static ();
    }

    /**
     * Set FollowLocation
     * @param  integer $followLocation
     * @return new static
     */
    public static function followLocation($followLocation)
    {
        self::$__followLocation = $followLocation;
        return new static ();
    }

    /**
     * Set UserAgent
     * @param  string $userAgent
     * @return new static
     */
    public static function userAgent($userAgent = 'rnd')
    {
        $ua[] = 'Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/30.0.1599.17 Safari/537.36';
        $ua[] = 'Mozilla/5.0 (Windows NT 6.2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/28.0.1467.0 Safari/537.36';
        $ua[] = 'Mozilla/5.0 (Windows NT 6.2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/28.0.1467.0 Safari/537.36';
        $ua[] = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_2) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.6 Safari/537.11';
        $ua[] = 'Mozilla/5.0 (Windows NT 6.2) AppleWebKit/536.3 (KHTML, like Gecko) Chrome/19.0.1062.0 Safari/536.3';

        if (is_numeric($userAgent) && $userAgent < count($ua) - 1) {
            self::$__userAgent = $ua[$userAgent];
        } elseif ($userAgent == 'rnd') {
            self::$__userAgent = $ua[rand(0, count($ua) - 1)];
        } else {
            self::$__userAgent = $userAgent;
        }
        return new static ();
    }

    /**
     * GetRequest Content of Url('s)
     * @param  string|array $url
     * @return string|array Page Content
     */
    public static function get($url)
    {
        if (is_array($url)) {
            foreach ($url as $v) {
                $process = curl_init($v);
                curl_setopt($process, CURLOPT_HTTPHEADER, array('Accept-Encoding: gzip,deflate'));
                curl_setopt($process, CURLOPT_RETURNTRANSFER, self::$__returnTransfer);
                curl_setopt($process, CURLOPT_FOLLOWLOCATION, self::$__followLocation);
                curl_setopt($process, CURLOPT_USERAGENT, self::$__userAgent);
                curl_setopt($process, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($process, CURLOPT_COOKIEFILE, self::$__cookieFile);
                curl_setopt($process, CURLOPT_COOKIEJAR, self::$__cookieFile);
                curl_setopt($process, CURLOPT_ENCODING, self::$__compression);
                curl_setopt($process, CURLOPT_CONNECTTIMEOUT, self::$__timeOut);
                curl_setopt($process, CURLOPT_TIMEOUT, self::$__timeOut);
                if (self::$__proxy) {
                    curl_setopt($process, CURLOPT_proxie, self::$__proxy);
                }
                $content[] = curl_exec($process);
            }
        } else {

            $process = curl_init($url);
            curl_setopt($process, CURLOPT_HTTPHEADER, array('Accept-Encoding: gzip,deflate'));
            curl_setopt($process, CURLOPT_RETURNTRANSFER, self::$__returnTransfer);
            curl_setopt($process, CURLOPT_FOLLOWLOCATION, self::$__followLocation);
            curl_setopt($process, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($process, CURLOPT_USERAGENT, self::$__userAgent);
            curl_setopt($process, CURLOPT_COOKIEFILE, self::$__cookieFile);
            curl_setopt($process, CURLOPT_COOKIEJAR, self::$__cookieFile);
            curl_setopt($process, CURLOPT_ENCODING, self::$__compression);
            curl_setopt($process, CURLOPT_CONNECTTIMEOUT, self::$__timeOut);
            curl_setopt($process, CURLOPT_TIMEOUT, self::$__timeOut);
            if (self::$__proxy) {
                curl_setopt($process, CURLOPT_proxie, self::$__proxy);
            }
            $content = curl_exec($process);
        }
        curl_close($process);
        return $content;
    }

    /**
     * PostRequest
     * @param  string $url
     * @param  string|array $data PostData
     * @return string Content
     */
    public static function post($url, $data)
    {
        $process = curl_init($url);
        curl_setopt($process, CURLOPT_HTTPHEADER, array('Accept-Encoding: gzip,deflate'));
        curl_setopt($process, CURLOPT_USERAGENT, self::$__userAgent);
        curl_setopt($process, CURLOPT_COOKIEFILE, self::$__cookieFile);
        curl_setopt($process, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($process, CURLOPT_COOKIEJAR, self::$__cookieFile);
        curl_setopt($process, CURLOPT_ENCODING, self::$__compression);
        curl_setopt($process, CURLOPT_TIMEOUT, self::$__timeOut);
        if (self::$__proxy) {
            curl_setopt($process, CURLOPT_proxie, self::$__proxy);
        }
        curl_setopt($process, CURLOPT_POSTFIELDS, $data);
        curl_setopt($process, CURLOPT_RETURNTRANSFER, self::$__returnTransfer);
        curl_setopt($process, CURLOPT_FOLLOWLOCATION, self::$__followLocation);
        curl_setopt($process, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($process, CURLOPT_POST, 1);
        $content = curl_exec($process);
        curl_close($process);
        return $content;
    }

}

