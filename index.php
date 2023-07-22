<?php

include "config.php";
include "functions.php";

$merged_data = merge_subscription($subscription_urls);
$splited_by_protocol = split_by_protocol($merged_data);

file_put_contents("merged", $merged_data);
file_put_contents("merged_base64", base64_encode($merged_data));

file_put_contents("vmess", array_to_subscription($splited_by_protocol['vmess']));
file_put_contents("vmess_base64", array_to_subscription(base64_encode($splited_by_protocol['vmess'])));
file_put_contents("vless", array_to_subscription($splited_by_protocol['vless']));
file_put_contents("vless_base64", array_to_subscription(base64_encode($splited_by_protocol['vless'])));
file_put_contents("trojan", array_to_subscription($splited_by_protocol['trojan']));
file_put_contents("trojan_base64", array_to_subscription(base64_encode($splited_by_protocol['trojan'])));
file_put_contents("shadowsocks", array_to_subscription($splited_by_protocol['ss']));
file_put_contents("shadowsocks_base64", array_to_subscription(base64_encode($splited_by_protocol['ss'])));
