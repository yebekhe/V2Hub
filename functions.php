<?

function openLink($url)
{
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_USERAGENT => "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36",
        CURLOPT_FOLLOWLOCATION => true,
    ]);
    return curl_exec($ch);
}

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
  if (is_base64_encoded($input)){
    $data = base64_decode($input);
    $output = process_subscriptions_helper($data);
  } else {
    $output = process_subscriptions_helper($input);
  }
  return $output;
}

function merge_subscription ($subscription_array){
  $vmess = "";
  $vless = "";
  $trojan = "";
  $shadowsocks = "";
  foreach ($subscription_array as $subscription_url){
    $subscription_data = openLink($subscription_url);
    $processed_array = process_subscriptions($subscription_data);
    $vmess .= implode("\n", $processed_array['vmess']) . "\n";
    $vless .= implode("\n", $processed_array['vless']) . "\n";
    $trojan .= implode("\n", $processed_array['trojan']) . "\n";
    $shadowsocks .= implode("\n", $processed_array['ss']) . "\n";
  }
  $output = $vmess . $vless . $trojan . $shadowsocks;
}
