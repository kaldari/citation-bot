<?
// $Id$

error_reporting(E_ALL^E_NOTICE);
$slowMode=false;
$fastMode=false;
$accountSuffix='_1'; // Keep this before including expandFns
include("expandFns.php");
$htmlOutput = false;
$editInitiator = '[Pu' . revisionID() . ']';
$ON = true; // Override later if necessary

print "OK";
die( write("User:DOI_bot/Zandbox", "Edit while blocked", "I should be blocked?"));

function updateQueue() {
  print "** Updating backlog...\nSeeing what links to 'Cite Journal'...";
  $cite_journal = whatTranscludes2("Cite_journal", 0);
  print "\nand 'Citation'... ";
  $citation =  whatTranscludes2("Citation", 0);
  $pages = array_merge($cite_journal["title"], $citation["title"]);
  $ids = array_merge($cite_journal["id"], $citation["id"]);
  print "and writing to file...";
  $count = count($pages);
  for ($i=0; $i<$count; $i++){
    $result = mysql_query("SELECT page FROM citation WHERE id = {$ids[$i]}") or die (mysql_error());
    if (!mysql_fetch_row($result)) {
      mysql_query("INSERT INTO citation (id, page) VALUES ('{$ids[$i]}', '". addslashes($pages[$i]) ."')" )or die(mysql_error());
      print "<br>{$pages[$i]} @ {$ids[$i]}";
    } else print ".";
  }
  print "\ndone.";
}

function nextPage(){
  #return "User:DOI bot/Zandbox";
  return "microRNA";
  global $ON, $STOP;
	if (!$ON || $STOP) die ("\n** EXIT: Bot switched off.\n");
	global $db;
	$result = mysql_query ("SELECT page FROM citation ORDER BY fast ASC") or die(mysql_error());
	$result = mysql_query("SELECT page FROM citation ORDER BY fast ASC") or die (mysql_error());
	$result = mysql_fetch_row($result);
	return $result[0];
}
#$STOP = true;
#$ON = false;
$page = "User:DOI bot/Zandbox";  // Leave this line as is.  It'll be over-written when the bot is turned on.
if ($ON) $page = nextPage();
#$page = " Template:Cite doi/10.1002.2F.28SICI.291097-0290.2819980420.2958:2.2F3.3C121::AID-BIT2.3E3.0.CO.3B2-N";
//$ON = true; // Uncomment this line to test edits in the Zandbox; but remember to break the bot after it touches the page or it'll keep on going!
#$page = "";
include("expand.php"); // i.e. GO!