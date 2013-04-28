#!/usr/bin/php
<?php
#namespace PEAR2\Net\RouterOS;
#require_once 'PEAR2/Net/RouterOS/Autoload.php';


namespace PEAR2\Net\RouterOS;
require_once 'PEAR2/Autoload.php';

$client = new Client($argv[1],$argv[2],$argv[3]);
$idList = array();
foreach ($client(new Request('/interface wireless registration-table getall')) as $entry) {
	$bytes_rx = @explode(',',$entry->getArgument('bytes'))[1];
	$bytes_tx = @explode(',',$entry->getArgument('bytes'))[0];
        $idList[$entry->getArgument('mac-address')] = array("id" => $entry->getArgument('.id'), "name" => $entry->getArgument('comment'), "mac" => $entry->getArgument('mac-address') 
		, "bytestx" => $bytes_tx, "bytesrx" => $bytes_rx, "tx-rate" => $entry->getArgument('tx-rate'), "rx-rate" => $entry->getArgument('rx-rate'), 
		"signal-to-noise" => $entry->getArgument('signal-to-noise'), 
		"signal-strength-ch0" => $entry->getArgument('signal-strength-ch0'),
		"signal-strength-ch1" => $entry->getArgument('signal-strength-ch1'),
		"signal-strength-ch2" => $entry->getArgument('signal-strength-ch2')
	);
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
                        if (strlen($i['name']) < 1) {
                                $i['name'] = $i['mac'];
                        }
			if (strlen($i['id']) >0) {
				print $pre.'{ "{#WIRELESSID}":"'.$i['id'].'",'.
					'"{#WIRELESSNAME}":"'.$i['name'].'",'.
					'"{#WIRELESSMAC}":"'.$i['mac'].'"'.
			 	'}';
			};
			$d++;
		}
		print(']}');
		break;
	case 1:
		if (@strlen($idList[$argv[5]]['bytesrx']) >0) {
			print $idList[$argv[5]]['bytesrx'];
		};
		break;
        case 2:
                if (@strlen($idList[$argv[5]]['bytestx']) >0) {
                        print $idList[$argv[5]]['bytestx'];
                };
                break;
	case 3:
		if (@strlen($idList[$argv[5]]['tx-rate']) >0) {
			print explode('.',$idList[$argv[5]]['tx-rate'])[0];
		};
                break;
        case 4:
                if (@strlen($idList[$argv[5]]['rx-rate']) >0) {
			print explode('.',$idList[$argv[5]]['rx-rate'])[0];
                };
                break;
        case 5:
                if (@strlen($idList[$argv[5]]['signal-to-noise']) >0) {
			print $idList[$argv[5]]["signal-to-noise"];
                };
                break;
        case 6:
                if (@strlen($idList[$argv[5]]['signal-strength-ch0']) >0) {
                        print $idList[$argv[5]]["signal-strength-ch0"];
                };
                break;
        case 7:
                if (@strlen($idList[$argv[5]]['signal-strength-ch1']) >0) {
                        print $idList[$argv[5]]["signal-strength-ch1"];
                } else { print 0; };
                break;
        case 8:
                if (@strlen($idList[$argv[5]]['signal-strength-ch2']) >0) {
                        print $idList[$argv[5]]["signal-strength-ch2"];
                } else { print 0; };
                break;


};
