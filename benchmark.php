<?php

/**
 * PHP Script to benchmark PHP and MySQL-Server
 *
 * inspired by / thanks to:
 * - www.php-benchmark-script.com  (Alessandro Torrisi)
 * - www.webdesign-informatik.de
 *
 * @author odan
 * @license MIT
 */
// -----------------------------------------------------------------------------
// Setup
// -----------------------------------------------------------------------------
set_time_limit(120); // 2 minutes

$options = array();

// Optional: mysql performance test
//$options['db.host'] = '127.0.0.1';
//$options['db.user'] = 'root';
//$options['db.pw'] = '';
//$options['db.name'] = 'test';
// -----------------------------------------------------------------------------
// Main
// -----------------------------------------------------------------------------
// check performance
$benchmarkResult = test_benchmark($options);

// html output
echo "<!DOCTYPE html>\n<html><head>\n";
echo "<style>
    table {
        color: #333; /* Lighten up font color */
        font-family: Helvetica, Arial, sans-serif; /* Nicer font */
        width: 640px;
        border-collapse:
        collapse; border-spacing: 0;
    }

    td, th {
        border: 1px solid #CCC; height: 30px;
    } /* Make cells a bit taller */

    th {
        background: #F3F3F3; /* Light grey background */
        font-weight: bold; /* Make sure they're bold */
    }

    td {
        background: #FAFAFA; /* Lighter grey background */
    }
    </style>
    </head>
    <body>";

echo array_to_html($benchmarkResult);

echo "\n</body></html>";
exit;

// -----------------------------------------------------------------------------
// Benchmark functions
// -----------------------------------------------------------------------------

function test_benchmark($settings)
{
    $timeStart = microtime(true);

    $result = array();
    $result['version'] = '1.1';
    $result['sysinfo']['time'] = date("Y-m-d H:i:s");
    $result['sysinfo']['php_version'] = PHP_VERSION;
    $result['sysinfo']['platform'] = PHP_OS;
    $result['sysinfo']['server_name'] = $_SERVER['SERVER_NAME'];
    $result['sysinfo']['server_addr'] = $_SERVER['SERVER_ADDR'];

    test_math($result);
    test_string($result);
    test_loops($result);
    test_ifelse($result);
    if (isset($settings['db.host'])) {
        test_mysql($result, $settings);
    }

    $result['total'] = timer_diff($timeStart);
    return $result;
}

function test_math(&$result, $count = 99999)
{
    $timeStart = microtime(true);

    $mathFunctions = array("abs", "acos", "asin", "atan", "bindec", "floor", "exp", "sin", "tan", "pi", "is_finite", "is_nan", "sqrt");
    for ($i = 0; $i < $count; $i++) {
        foreach ($mathFunctions as $function) {
            call_user_func_array($function, array($i));
        }
    }
    $result['benchmark']['math'] = timer_diff($timeStart);
}

function test_string(&$result, $count = 99999)
{
    $timeStart = microtime(true);
    $stringFunctions = array("addslashes", "chunk_split", "metaphone", "strip_tags", "md5", "sha1", "strtoupper", "strtolower", "strrev", "strlen", "soundex", "ord");

    $string = 'the quick brown fox jumps over the lazy dog';
    for ($i = 0; $i < $count; $i++) {
        foreach ($stringFunctions as $function) {
            call_user_func_array($function, array($string));
        }
    }
    $result['benchmark']['string'] = timer_diff($timeStart);
}

function test_loops(&$result, $count = 999999)
{
    $timeStart = microtime(true);
    for ($i = 0; $i < $count; ++$i) {

    }
    $i = 0;
    while ($i < $count) {
        ++$i;
    }
    $result['benchmark']['loops'] = timer_diff($timeStart);
}

function test_ifelse(&$result, $count = 999999)
{
    $timeStart = microtime(true);
    for ($i = 0; $i < $count; $i++) {
        if ($i == -1) {

        } elseif ($i == -2) {

        } else if ($i == -3) {

        }
    }
    $result['benchmark']['ifelse'] = timer_diff($timeStart);
}

function test_mysql(&$result, $settings)
{
    $timeStart = microtime(true);

    $link = mysqli_connect($settings['db.host'], $settings['db.user'], $settings['db.pw']);
    $result['benchmark']['mysql']['connect'] = timer_diff($timeStart);

    //$arr_return['sysinfo']['mysql_version'] = '';

    mysqli_select_db($link, $settings['db.name']);
    $result['benchmark']['mysql']['select_db'] = timer_diff($timeStart);

    $dbResult = mysqli_query($link, 'SELECT VERSION() as version;');
    $arr_row = mysqli_fetch_array($dbResult);
    $result['sysinfo']['mysql_version'] = $arr_row['version'];
    $result['benchmark']['mysql']['query_version'] = timer_diff($timeStart);

    $query = "SELECT BENCHMARK(1000000,ENCODE('hello',RAND()));";
    $dbResult = mysqli_query($link, $query);
    $result['benchmark']['mysql']['query_benchmark'] = timer_diff($timeStart);

    mysqli_close($link);

    $result['benchmark']['mysql']['total'] = timer_diff($timeStart);
    return $result;
}

function timer_diff($timeStart)
{
    return number_format(microtime(true) - $timeStart, 3);
}

function array_to_html($array)
{
    $result = '';
    if (is_array($array)) {
        $result .= '<table>';
        foreach ($array as $k => $v) {
            $result .= "\n<tr><td>";
            $result .= '<strong>' . htmlentities($k) . "</strong></td><td>";
            $result .= array_to_html($v);
            $result .= "</td></tr>";
        }
        $result .= "\n</table>";
    } else {
        $result = htmlentities($array);
    }
    return $result;
}
