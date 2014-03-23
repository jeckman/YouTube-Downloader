<?php 

$f1 = 'function fE(a){a=a.split("");a=a.slice(2);a=a.reverse();a=gE(a,39);a=gE(a,43);return a.join("")}';
$f2 = 'function gE(a,b){var c=a[0];a[0]=a[b%a.length];a[b]=c;return a}';
echo js2php($f1);
echo js2php($f2); 





function js2php($f) {
  $f = preg_replace('/\$/', '_', $f);
  $f = preg_replace('/\}/', ';}', $f);
  $f = preg_replace('/var\s+/', '', $f);
  $f = preg_replace('/(\w+).join\(""\)/', 'implode(${1})', $f);
  $f = preg_replace('/(\w+).length/', 'count(${1})', $f);
  $f = preg_replace('/(\w+).reverse\(\)/', 'array_reverse(${1})', $f);
  $f = preg_replace('/(\w+).slice\((\d+)\)/', 'array_slice(\$${1},${2})', $f);
  $f = preg_replace('/(\w+).split\(""\)/', 'str_split(${1})', $f);
  $f = preg_replace('/\((\w+)\)/', '(\$${1})', $f);
  $f = preg_replace('/\[(\w+)/', '[\$${1}', $f);
  $f = preg_replace('/\((\w+,\s*\d+)\)/', '(\$${1})', $f);
  $f = preg_replace('/\((\w+),\s*(\w+)\)/', '(\$${1},\$${2})', $f);
  $f = preg_replace('/(\w+)([=\[;])/', '\$${1}${2}', $f);
  $f = preg_replace('/\$(\d+)/', '${1}', $f);
  #echo $f . "\n";
  return $f;
}

?>