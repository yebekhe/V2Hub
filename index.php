<?php

include "config.php";
include "functions.php";

$merged_data = merge_subscription($subscription_urls);
$merged_vmess = array_to_subscription($merged_data['vmess']);
$merged_vless = array_to_subscription($merged_data['vless']);
$merged_reality = get_reality($merged_vless);
$merged_trojan = array_to_subscription($merged_data['trojan']);
$merged_shadowsocks = array_to_subscription($merged_data['ss']);
$merged_mix = $merged_vmess . $merged_vless . $merged_trojan . $merged_shadowsocks ;

file_put_contents("merged", $merged_mix);
file_put_contents("merged_base64", base64_encode($merged_mix));

file_put_contents("Split/Normal/vmess", $merged_vmess);
file_put_contents("Split/Base64/vmess", base64_encode($merged_vmess));
file_put_contents("Split/Normal/vless", $merged_vless);
file_put_contents("Split/Base64/vless", base64_encode($merged_vless));
file_put_contents("Split/Normal/reality", $merged_reality);
file_put_contents("Split/Base64/reality", base64_encode($merged_reality));
file_put_contents("Split/Normal/trojan", $merged_trojan);
file_put_contents("Split/Base64/trojan", base64_encode($merged_trojan));
file_put_contents("Split/Normal/shadowsocks", $merged_shadowsocks);
file_put_contents("Split/Base64/shadowsocks", base64_encode($merged_shadowsocks));
