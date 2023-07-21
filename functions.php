<?php

function detect_type($input)
{
    $type = "";
    if (substr($input, 0, 8) === "vmess://") {
        $type = "vmess";
    } elseif (substr($input, 0, 8) === "vless://") {
        $type = "vless";
    } elseif (substr($input, 0, 9) === "trojan://") {
        $type = "trojan";
    } elseif (substr($input, 0, 5) === "ss://") {
        $type = "ss";
    }

    return $type;
}

function is_base64_encoded ($input_string) {
    $decoded_string = base64_decode($input_string, true);
    return ($decoded_string !== false);
}

function process_subscriptions_helper ($input) {
    $output = [];
    $data_array = explode("\n", $input);
    foreach ($data_array as $config){
      $type = detect_type($config);
      switch ($type) {
        case "vmess":
            $output['vmess'][] = $config;
            break;
        case "vless":
            $output['vless'][] = $config;
            break;
        case "trojan":
            $output['trojan'][] = $config;
            break;
        case "ss":
            $output['ss'][] = $config;
            break;
      }
    }
  return $output;
}

function process_subscriptions ($input) {
  $output = [];
  if (is_base64_encoded($input) === true){
    $data = base64_decode($input);
    $output = process_subscriptions_helper($data);
  } else {
    $output = process_subscriptions_helper($input);
  }
  return $output;
}

function merge_subscription ($input){
  $vmess = "";
  $vless = "";
  $trojan = "";
  $shadowsocks = "";
  foreach ($input as $subscription_url){
    $subscription_data = file_get_contents($subscription_url);
    $processed_array = process_subscriptions($subscription_data);
    $vmess .= isset($processed_array['vmess']) ? implode("\n", $processed_array['vmess']) . "\n" : null;
    $vless .= isset($processed_array['vless']) ? implode("\n", $processed_array['vless']) . "\n" : null;
    $trojan .= isset($processed_array['trojan']) ? implode("\n", $processed_array['trojan']) . "\n" : null;
    $shadowsocks .= isset($processed_array['ss']) ? implode("\n", $processed_array['ss']) . "\n": null;
  }
  $output = $vmess . $vless . $trojan . $shadowsocks;
}
