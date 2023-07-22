<?php

include "config.php";
include "functions.php";

$merged_data = merge_subscription($subscription_urls);
$splited_by_protocol = split_by_protocol($merged_data);

file_put_contents("merged", $merged_data);
file_put_contents("merged_base64", base64_encode($merged_data));

file_put_contents("Split/Normal/vmess", array_to_subscription($splited_by_protocol['vmess']));
file_put_contents("Split/Base64/vmess", array_to_subscription(base64_encode($splited_by_protocol['vmess'])));
file_put_contents("Split/Normal/vless", array_to_subscription($splited_by_protocol['vless']));
file_put_contents("Split/Base64/vless", array_to_subscription(base64_encode($splited_by_protocol['vless'])));
file_put_contents("Split/Normal/reality", get_reality(array_to_subscription($splited_by_protocol['vless'])));
file_put_contents("Split/Base64/reality", get_reality(array_to_subscription(base64_encode($splited_by_protocol['vless']))));
file_put_contents("Split/Normal/trojan", array_to_subscription($splited_by_protocol['trojan']));
file_put_contents("Split/Base64/trojan", array_to_subscription(base64_encode($splited_by_protocol['trojan'])));
file_put_contents("Split/Normal/shadowsocks", array_to_subscription($splited_by_protocol['ss']));
file_put_contents("Split/Base64/shadowsocks", array_to_subscription(base64_encode($splited_by_protocol['ss'])));
