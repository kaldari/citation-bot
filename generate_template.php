<?php
declare(strict_types=1);
// outputs a Wikipedia reference from a DOI 
// usage: https://citations.toolforge.org/generate_template.php?doi=<DOI>
@session_start();
@header("Access-Control-Allow-Origin: *"); //This is ok because the API is not authenticated
?>

<!DOCTYPE html><html lang="en" dir="ltr"><head><title>Make a Template</title></head><body><pre>

<?php
require_once('setup.php');

$t = new Template();
$t->parse_text('{{Cite web}}');
if (count($_GET) > 10) exit('Excessive number of parameters passed');
if (count($_GET) === 0) exit('No parameters passed');
foreach ($_GET as $param=>$value) {
  if (strlen($param . $value) > 256) exit('Excessively long parameter passed');
  $t->set($param, $value);
}

$page = new Page();
$page->parse_text($t->parsed_text());
$page->expand_text();

echo("\n\n" . htmlspecialchars('<ref>' . $page->parsed_text() . '</ref>'));
?>

</pre></body></html>
