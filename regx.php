<?php
$str="baag";
$pattern="/a/i";
// preg_match check whether the pattern much the string return 1 $0 if no match
echo (preg_match($pattern,$str)."<br>");
// Returns the number of times the pattern was found in the string, which may also be 0

echo preg_match_all($pattern,$str)."<br>";
$str = "Visit Microsoft!";
$pattern = "/microsoft/i";
// The preg_replace() function will replace all of the matches of the pattern in a string with another string.


echo preg_replace($pattern, "W3schools", $str)."<br>";
$newstr="hrieabof";
$newpattern="/[^abc]/i";
echo (preg_match_all($newpattern,$newstr)."<br>");
$nepattern="/[abc]/i";
echo (preg_match_all($nepattern,$newstr)."<br>");
// search for values in range
$mypattern="/[a-z]/i";
echo (preg_match_all($mypattern,$newstr)."<br>");
$digitpattern="/d/";
echo (preg_match_all($digitpattern,$newstr)."<br>");
$begofstring="helloword";
$stringpattern="/^h/";
echo (preg_match_all($stringpattern,$begofstring)."<br>");
echo ("end of string"."<br>");
$endofstrpattern="/h$/";
echo (preg_match_all($endofstrpattern,$begofstring)."<br>");
// s-white spaces,S-non white spaces,D-nondigit,w-values btn a-z and o-9, W-NOT A-Z AND 0_9
// . -ANY CHARACTER,b -character at the begining of a word,|- find characters seperated byit
echo ("atleast one n"."<br>");

$nchars="/z+/";
echo (preg_match($nchars,$begofstring)."<br>");
// Matches any string that contains zero or more occurrences of n
echo ("zero or more occurances of n"."<br>");

$manychars="/h*/";
echo (preg_match($manychars,$begofstring)."<br>");
// Matches any string that contains zero or one occurrences of n
echo ("zero or one occurances of n"."<br>");
$onechars="/e?/";
echo (preg_match($onechars,$begofstring)."<br>");
// shows the number of n's
$n3chars="/l{1}/";
echo (preg_match_all($n3chars,$begofstring)."<br>");
// 	Matches any string that contains a sequence of at least 2, but not more that 4 n's
$n3chars="/h{2,4}/";
echo (preg_match_all($n3chars,$begofstring)."<br>");
// 	Matches any string that contains a sequence of at least 2,
$n3chars="/l{2,}/";
echo (preg_match_all($n3chars,$begofstring)."<br>");
// grouping
$str = "Apples and bananas.";
$pattern = "/ba(na){2}/i";
echo preg_match($pattern, $str)."<br>";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REGX</title>
</head>
<body>
    <h2>Regular expressions</h2>
    <p>A regular expression is a sequence of characters that forms a search pattern. When you search for data in a text, 
        you can use this search pattern to describe what you are searching for.</p>
<p>In the example above, / is the delimiter, w3schools is the pattern that is being searched for, and i is a modifier that makes the search case-insensitive.

The delimiter can be any character that is not a letter, number, backslash or space. The most common delimiter is the forward slash (/), but when your pattern contains forward slashes it is convenient
 to choose other delimiters such as # or ~.</p>
    </body>
</html>