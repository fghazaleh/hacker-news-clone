<?php namespace FGhazaleh\Support\Helper;

class Helper
{
    /**
     * Get host name from url.
     *
     * @param string $url ;
     * @return string
     * */
    public static function getHost(string $url):string
    {
        $host = parse_url($url, PHP_URL_HOST);
        if (is_null($host)) {
            return '';
        }
        return str_replace('www.', '', $host);
    }
}
