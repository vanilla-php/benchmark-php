<?php

/**
 * PHP Script to benchmark PHP and MySQL-Server
 *
 * inspired by / thanks to:
 * - www.php-benchmark-script.com  (Alessandro Torrisi)
 * - www.webdesign-informatik.de
 *
 * @author odan
 * @version 2014.02.23
 * @license MIT
 *
 */
// -----------------------------------------------------------------------------
// Setup
// -----------------------------------------------------------------------------
set_time_limit(120); // 2 minutes

$arr_cfg = array();

// optional: mysql performance test
//$arr_cfg['db.host'] = 'localhost';
//$arr_cfg['db.user'] = 'root';
//$arr_cfg['db.pw'] = '';
//$arr_cfg['db.name'] = 'test';
// -----------------------------------------------------------------------------
// Main
// -----------------------------------------------------------------------------
// check performance
$arr_benchmark = test_benchmark($arr_cfg);

// html output
echo "<!DOCTYPE html>\n<html><head>\n";
echo "<style>table {
    font-family:Arial, Helvetica, sans-serif;
    font-size:12px;
    margin:0px;
    padding: 0px;
    border:#ccc 1px solid;
    border-collapse:collapse;
}
td, th {
    border:#ccc 1px solid;
    vertical-align: top;
}</style></head><body>";

echo array_to_html($arr_benchmark);

echo "\n</body></html>";
exit;

// -----------------------------------------------------------------------------
// Benchmark functions
// -----------------------------------------------------------------------------

function test_benchmark($arr_cfg)
{

    $time_start = microtime(true);

    $arr_return = array();
    $arr_return['version'] = '1.1';
    $arr_return['sysinfo']['time'] = date("Y-m-d H:i:s");
    $arr_return['sysinfo']['php_version'] = PHP_VERSION;
    $arr_return['sysinfo']['platform'] = PHP_OS;
    $arr_return['sysinfo']['server_name'] = $_SERVER['SERVER_NAME'];
    $arr_return['sysinfo']['server_addr'] = $_SERVER['SERVER_ADDR'];

    test_math($arr_return);

    test_string($arr_return);

    test_loops($arr_return);

    test_ifelse($arr_return);

    if (isset($arr_cfg['db.host'])) {
        test_mysql($arr_return, $arr_cfg);
    }

    $arr_return['total'] = timer_diff($time_start);

    return $arr_return;
}

function test_math(&$arr_return, $count = 99999)
{
    $time_start = microtime(true);

    $mathFunctions = array("abs", "acos", "asin", "atan", "bindec", "floor", "exp", "sin", "tan", "pi", "is_finite", "is_nan", "sqrt");
    for ($i = 0; $i < $count; $i++) {
        foreach ($mathFunctions as $function) {
            $r = call_user_func_array($function, array($i));
        }
    }

    $arr_return['benchmark']['math'] = timer_diff($time_start);
}

function test_string(&$arr_return, $count = 99999)
{
    $time_start = microtime(true);
    $stringFunctions = array("addslashes", "chunk_split", "metaphone", "strip_tags", "md5", "sha1", "strtoupper", "strtolower", "strrev", "strlen", "soundex", "ord");

    $string = 'the quick brown fox jumps over the lazy dog';
    for ($i = 0; $i < $count; $i++) {
        foreach ($stringFunctions as $function) {
            $r = call_user_func_array($function, array($string));
        }
    }
    $arr_return['benchmark']['string'] = timer_diff($time_start);
}

function test_loops(&$arr_return, $count = 999999)
{
    $time_start = microtime(true);
    for ($i = 0; $i < $count; ++$i)
        ;
    $i = 0;
    while ($i < $count) {
        ++$i;
    }

    $arr_return['benchmark']['loops'] = timer_diff($time_start);
}

function test_ifelse(&$arr_return, $count = 999999)
{
    $time_start = microtime(true);
    for ($i = 0; $i < $count; $i++) {
        if ($i == -1) {

        } elseif ($i == -2) {

        } else if ($i == -3) {

        }
    }
    $arr_return['benchmark']['ifelse'] = timer_diff($time_start);
}

function test_mysql(&$arr_return, $arr_cfg)
{

    $time_start = microtime(true);

    $link = mysqli_connect($arr_cfg['db.host'], $arr_cfg['db.user'], $arr_cfg['db.pw']);
    $arr_return['benchmark']['mysql']['connect'] = timer_diff($time_start);

    // //$arr_return['sysinfo']['mysql_version'] = '';

    mysqli_select_db($link, $arr_cfg['db.name']);
    $arr_return['benchmark']['mysql']['select_db'] = timer_diff($time_start);

    $result = mysqli_query($link, 'SELECT VERSION() as version;');
    $arr_row = mysqli_fetch_assoc($result);
    $arr_return['sysinfo']['mysql_version'] = $arr_row['version'];
    $arr_return['benchmark']['mysql']['query_version'] = timer_diff($time_start);

    $query = "SELECT BENCHMARK(1000000,ENCODE('hello','goodbye'));";
    $result = mysqli_query($link, $query);
    $arr_return['benchmark']['mysql']['query_benchmark'] = timer_diff($time_start);

    mysqli_close($link);

    $arr_return['benchmark']['mysql']['total'] = timer_diff($time_start);

    return $arr_return;
}

function timer_diff($time_start)
{
    return number_format(microtime(true) - $time_start, 3);
}

function array_to_html($my_array)
{
    $strReturn = '';
    if (is_array($my_array)) {
        $strReturn .= '<table>';
        foreach ($my_array as $k => $v) {
            $strReturn .= "\n<tr><td>";
            $strReturn .= '<strong>' . htmlentities($k) . "</strong></td><td>";
            $strReturn .= array_to_html($v);
            $strReturn .= "</td></tr>";
        }
        $strReturn .= "\n</table>";
    } else {
        $strReturn = htmlentities($my_array);
    }
    return $strReturn;
}
