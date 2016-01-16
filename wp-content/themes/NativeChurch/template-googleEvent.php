<?php
/*
  Template Name: Google Events
 */
get_header();?>
<?php 
$dateformat="Y-m-d";
						$timeformat="G:i";
						$GroupByDate=true;
						$items_to_show=10; ///999 = unlimited
						$use_cache=false;
						$debug_mode=false;
						$new_event_data = array();
 $url = "https://www.google.com/calendar/feeds/nitinprakash8%40gmail.com/private-832de7f03643b06656da38e164056ec7/basic?singleevents=true&futureevents=true&max-results".$items_to_show."&orderby=starttime&sortorder=a";
$xml = file_get_contents($url);
$feed = simplexml_load_string($xml);
$ns=$feed->getNameSpaces(true);
$items_shown=0;
$old_date="";
$check = 0;
foreach ($feed->entry as $entry) {
//    echo 'link is:'.$entry->link['href'];
//    $when=$entry->children($ns["gd"]);
//    $when_atr=$when->attributes();
//    $title=$entry->title;
//    echo "<div class='eventTitle'>".$title . "</div>";
//    echo '</br>';
    echo 'startTime:'.$when_atr['startTime'];
    echo "</br>";
    print_r($when_atr);
    $start = new DateTime($when_atr['startTime']);
//    $gCalDate = date($dateformat, strtotime($ns_gd->when->attributes()->startTime));
//    echo "<div class='eventTime'>".$start->format('D F jS, g:ia') . " to ";    
    $end = new DateTime($when_atr['endTime']);
//    echo $end->format('g:ia')."</div>" . '<br />' ;    
}
 ?>
<?php get_footer(); ?>