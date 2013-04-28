#!/usr/bin/php
<?php
namespace PEAR2\Net\RouterOS;
require_once 'PEAR2/Autoload.php';

$client = new Client($argv[1],$argv[2],$argv[3]);
$idList = array();

$r = new Request('/ip/firewall/filter/print');
$r->setArgument('stats');

foreach ($client($r) as $entry){
	if ( @strlen($entry->getArgument('comment')) >0 && 
	     $entry->getArgument('action') != "jump" &&
	     $entry->getArgument('action') != "log" &&
             $entry->getArgument('chain') != "ppp" ) {
		$idList[$entry->getArgument('.id')] = array( 
			"id" => $entry->getArgument('.id'),
			"comment" => $entry->getArgument('comment'),
			"bytes" => $entry->getArgument('bytes'),
			"packets" => $entry->getArgument('packets'),
			"chain" => $entry->getArgument('chain'),
			"action" => $entry->getArgument('action')
		);
	};
}

switch ($argv[4]) {
	case 0:
		$d=0;
		print('{ "data":[');
		foreach ($idList as $i) {
			if ($d == 0) { 
				$pre = '';
			} else { 
				$pre = ','; 
			}
                        if (strlen($i['comment']) < 1) {
                                $i['name'] = $i['id'];
                        }
	        	if (strlen($i['id']) >0) {
				print $pre.'{ "{#FIREWALLID}":"'.$i['id'].'",'.
					'"{#FIREWALLCOMMENT}":"'.$i['comment'].'",'.
					'"{#WIRELESSCHAIN}":"'.$i['chain'].'"'.
					'"{#WIRELESSACTION}":"'.$i['action'].'"'.
			 	'}';
			};
			$d++;
		}
		print(']}');
		break;
	case 1:
		if (@strlen($idList[$argv[5]]['bytes']) >0) {
			print $idList[$argv[5]]['bytes'];
		};
		break;	
	case 2:
		if (@strlen($idList[$argv[5]]['packets']) >0) {
			print $idList[$argv[5]]['packets'];
		};
		break;	
}
