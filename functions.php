<?php
/** Detect Type of Config */
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

function parse_config($input)
{
    $type = detect_type($input);
    $parsed_config = [];
    switch ($type) {
        case "vmess":
            $parsed_config = decode_vmess($input);
            break;
        case "vless":
        case "trojan":
            $parsed_config = parseProxyUrl($input, $type);
            break;
        case "ss":
            $parsed_config = ParseShadowsocks($input);
            break;
    }
    return $parsed_config;
}

function build_config($input, $type)
{
    $build_config = "";
    switch ($type) {
        case "vmess":
            $build_config = encode_vmess($input);
            break;
        case "vless":
        case "trojan":
            $build_config = buildProxyUrl($input, $type);
            break;
        case "ss":
            $build_config = BuildShadowsocks($input);
            break;
    }
    return $build_config;
}

/** parse vmess configs */
function decode_vmess($vmess_config)
{
    $vmess_data = substr($vmess_config, 8); // remove "vmess://"
    $decoded_data = json_decode(base64_decode($vmess_data), true);
    return $decoded_data;
}

/** build vmess configs */
function encode_vmess($config)
{
    $encoded_data = base64_encode(json_encode($config));
    $vmess_config = "vmess://" . $encoded_data;
    return $vmess_config;
}

/** remove duplicate vmess configs */
function remove_duplicate_vmess($input)
{
    $array = explode("\n", $input);
    $result = [];
    foreach ($array as $item) {
        $parts = decode_vmess($item);
        if ($parts !== null) {
            $part_ps = $parts["ps"];
            unset($parts["ps"]);
            if (count($parts) >= 3) {
                ksort($parts);
                $part_serialize = serialize($parts);
                $result[$part_serialize][] = $part_ps ?? "";
            }
        }
    }
    $finalResult = [];
    foreach ($result as $serial => $ps) {
        $partAfterHash = $ps[0] ?? "";
        $part_serialize = unserialize($serial);
        $part_serialize["ps"] = $partAfterHash;
        $finalResult[] = encode_vmess($part_serialize);
    }
    $output = "";
    foreach ($finalResult as $config) {
        $output .= $output == "" ? $config : "\n" . $config;
    }
    return $output;
}

/** Parse vless and trojan config*/
function parseProxyUrl($url, $type = "trojan")
{
    // Parse the URL into components
    $parsedUrl = parse_url($url);

    // Extract the parameters from the query string
    $params = [];
    if (isset($parsedUrl["query"])) {
        parse_str($parsedUrl["query"], $params);
    }

    // Construct the output object
    $output = [
        "protocol" => $type,
        "username" => isset($parsedUrl["user"]) ? $parsedUrl["user"] : "",
        "hostname" => isset($parsedUrl["host"]) ? $parsedUrl["host"] : "",
        "port" => isset($parsedUrl["port"]) ? $parsedUrl["port"] : "",
        "params" => $params,
        "hash" => isset($parsedUrl["fragment"]) ? $parsedUrl["fragment"] : "",
    ];

    return $output;
}

/** Build vless and trojan config*/
function buildProxyUrl($obj, $type = "trojan")
{
    $url = $type . "://";
    $url .= addUsernameAndPassword($obj);
    $url .= $obj["hostname"];
    $url .= addPort($obj);
    $url .= addParams($obj);
    $url .= addHash($obj);
    return $url;
}

function addUsernameAndPassword($obj)
{
    $url = "";
    if ($obj["username"] !== "") {
        $url .= $obj["username"];
        if (isset($obj["pass"]) && $obj["pass"] !== "") {
            $url .= ":" . $obj["pass"];
        }
        $url .= "@";
    }
    return $url;
}

function addPort($obj)
{
    $url = "";
    if (isset($obj["port"]) && $obj["port"] !== "") {
        $url .= ":" . $obj["port"];
    }
    return $url;
}

function addParams($obj)
{
    $url = "";
    if (!empty($obj["params"])) {
        $url .= "?" . http_build_query($obj["params"]);
    }
    return $url;
}

function addHash($obj)
{
    $url = "";
    if (isset($obj["hash"]) && $obj["hash"] !== "") {
        $url .= "#" . $obj["hash"];
    }
    return $url;
}

/** remove duplicate vless and trojan config*/
function remove_duplicate_xray($input, $type)
{
    $array = explode("\n", $input);

    foreach ($array as $item) {
        $parts = parseProxyUrl($item, $type);
        $part_hash = $parts["hash"];
        unset($parts["hash"]);
        ksort($parts["params"]);
        $part_serialize = serialize($parts);
        $result[$part_serialize][] = $part_hash ?? "";
    }

    $finalResult = [];
    foreach ($result as $url => $parts) {
        $partAfterHash = $parts[0] ?? "";
        $part_serialize = unserialize($url);
        $part_serialize["hash"] = $partAfterHash;
        $finalResult[] = buildProxyUrl($part_serialize, $type);
    }

    $output = "";
    foreach ($finalResult as $config) {
        $output .= $output == "" ? $config : "\n" . $config;
    }
    return $output;
}

/** parse shadowsocks configs */
function ParseShadowsocks($config_str)
{
    // Parse the config string as a URL
    $url = parse_url($config_str);

    // Extract the encryption method and password from the user info
    list($encryption_method, $password) = explode(
        ":",
        base64_decode($url["user"])
    );

    // Extract the server address and port from the host and path
    $server_address = $url["host"];
    $server_port = $url["port"];

    // Extract the name from the fragment (if present)
    $name = isset($url["fragment"]) ? urldecode($url["fragment"]) : null;

    // Create an array to hold the server configuration
    $server = [
        "encryption_method" => $encryption_method,
        "password" => $password,
        "server_address" => $server_address,
        "server_port" => $server_port,
        "name" => $name,
    ];

    // Return the server configuration as a JSON string
    return $server;
}

/** build shadowsocks configs */
function BuildShadowsocks($server)
{
    // Encode the encryption method and password as a Base64-encoded string
    $user = base64_encode(
        $server["encryption_method"] . ":" . $server["password"]
    );

    // Construct the URL from the server address, port, and user info
    $url = "ss://$user@{$server["server_address"]}:{$server["server_port"]}";

    // If the name is present, add it as a fragment to the URL
    if (!empty($server["name"])) {
        $url .= "#" . urlencode($server["name"]);
    }

    // Return the URL as a string
    return $url;
}

/** remove duplicate shadowsocks configs */
function remove_duplicate_ss($input)
{
    $array = explode("\n", $input);

    foreach ($array as $item) {
        $parts = ParseShadowsocks($item);
        $part_hash = $parts["name"];
        unset($parts["name"]);
        ksort($parts);
        $part_serialize = serialize($parts);
        $result[$part_serialize][] = $part_hash ?? "";
    }

    $finalResult = [];
    foreach ($result as $url => $parts) {
        $partAfterHash = $parts[0] ?? "";
        $part_serialize = unserialize($url);
        $part_serialize["name"] = $partAfterHash;
        $finalResult[] = BuildShadowsocks($part_serialize);
    }

    $output = "";
    foreach ($finalResult as $config) {
        $output .= $output == "" ? $config : "\n" . $config;
    }
    return $output;
}

function is_ip($string)
{
    $ipv4_pattern = '/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/';
    $ipv6_pattern = '/^[0-9a-fA-F:]+$/'; // matches any valid IPv6 address

    if (preg_match($ipv4_pattern, $string) || preg_match($ipv6_pattern, $string)) {
        return true;
    } else {
        return false;
    }
}

function ip_info($ip)
{
    if (is_ip($ip) === false) {
        $ip_address_array = dns_get_record($ip, DNS_A);
        if (is_array($ip_address_array)) {
            $randomKey = array_rand($ip_address_array);
            $ip = $ip_address_array[$randomKey]["ip"];
        }
    }
    $ipinfo = json_decode(
        file_get_contents("https://api.country.is/" . $ip),
        true
    );
    return $ipinfo;
}

function get_flag($ip)
{
    $flag = "";
    $ip_info = ip_info($ip);
    if (isset($ip_info["country"])) {
        $location = $ip_info["country"];
        $flag = $location . getFlags($location);
    } else {
        $flag = "RELAY🚩";
    }
    return $flag;
}

function getFlags($country_code)
{
    $flag = mb_convert_encoding(
        "&#" . (127397 + ord($country_code[0])) . ";",
        "UTF-8",
        "HTML-ENTITIES"
    );
    $flag .= mb_convert_encoding(
        "&#" . (127397 + ord($country_code[1])) . ";",
        "UTF-8",
        "HTML-ENTITIES"
    );
    return $flag;
}


function get_ip($config, $type, $is_reality)
{
    switch ($type) {
        case "vmess":
            return get_vmess_ip($config);
        case "vless":
            return get_vless_ip($config, $is_reality);
        case "trojan":
            return get_trojan_ip($config);
        case "ss":
            return get_ss_ip($config);
    }
}

function get_vmess_ip($input)
{
    return !empty($input["sni"])
        ? $input["sni"]
        : (!empty($input["host"])
            ? $input["host"]
            : $input["add"]);
}

function get_vless_ip($input, $is_reality)
{
    return $is_reality
        ? $input["hostname"]
        : (!empty($input["params"]["sni"])
            ? $input["params"]["sni"]
            : (!empty($input["params"]["host"])
                ? $input["params"]["host"]
                : $input["hostname"]));
}

function get_trojan_ip($input)
{
    return !empty($input["params"]["sni"])
        ? $input["params"]["sni"]
        : (!empty($input["params"]["host"])
            ? $input["params"]["host"]
            : $input["hostname"]);
}

function get_ss_ip($input)
{
    return $input["server_address"];
}

function get_port($input, $type)
{
    $port = "";
    switch ($type) {
        case "vmess":
            $port = $input["port"];
            break;
        case "vless":
            $port = $input["port"];
            break;
        case "trojan":
            $port = $input["port"];
            break;
        case "ss":
            $port = $input["server_port"];
            break;
    }
    return $port;
}

function ping($ip, $port)
{
    $it = microtime(true);
    $check = @fsockopen($ip, $port, $errno, $errstr, 0.5);
    $ft = microtime(true);
    $militime = round(($ft - $it) * 1e3, 2);
    if ($check) {
        fclose($check);
        return $militime;
    } else {
        return "unavailable";
    }
}

function generate_name($flag, $ip, $port, $ping, $is_reality)
{
    $name = "";
    switch ($is_reality) {
        case true:
            $name =
                "REALITY|" .
                $flag .
                " | " .
                $ip .
                "-" .
                $port .
                " | " .
                $ping .
                "ms";
            break;
        case false:
            $name =
                $flag .
                " | " .
                $ip .
                "-" .
                $port .
                " | " .
                $ping .
                "ms";
            break;
    }
    return $name;
}

function process_config($config)
{
    $name_array = [
        "vmess" => "ps",
        "vless" => "hash",
        "trojan" => "hash",
        "ss" => "name",
    ];
    $type = detect_type($config);
    $is_reality = stripos($config, "reality") !== false ? true : false;
    $parsed_config = parse_config($config);
    $ip = get_ip($parsed_config, $type, $is_reality);
    $port = get_port($parsed_config, $type);
    $ping_data = ping($ip, $port);
    if ($ping_data !== "unavailable") {
        $flag = get_flag($ip);
        $name_key = $name_array[$type];
        $parsed_config[$name_key] = generate_name(
            $flag,
            $ip,
            $port,
            $ping_data,
            $is_reality
        );
        $final_config = build_config($parsed_config, $type);
        return $final_config;
    }
    return false;
}

/** Extract reality configs */
function get_reality($input)
{
    $array = explode("\n", $input);
    $output = "";
    foreach ($array as $item) {
        if (stripos($item, "reality")) {
            $output .= $output === "" ? $item : "\n$item";
        }
    }
    return $output;
}

/** Check if subscription is base64 encoded or not */
function is_base64_encoded($string)
{
    if (base64_encode(base64_decode($string, true)) === $string) {
        return "true";
    } else {
        return "false";
    }
}

function process_subscriptions($input)
{
    $output = [];
    if (is_base64_encoded($input) === "true") {
        $data = base64_decode($input);
        $output = process_subscriptions_helper($data);
    } else {
        $output = process_subscriptions_helper($input);
    }
    return $output;
}

function process_subscriptions_helper($input)
{
    $output = [];
    $data_array = explode("\n", $input);
    foreach ($data_array as $config) {
        $processed_config = process_config($config);
        if ($processed_config !== false) {
            $type = detect_type($processed_config);
            switch ($type) {
                case "vmess":
                    $output["vmess"][] = $processed_config;
                    break;
                case "vless":
                    $output["vless"][] = $processed_config;
                    break;
                case "trojan":
                    $output["trojan"][] = $processed_config;
                    break;
                case "ss":
                    $output["ss"][] = $processed_config;
                    break;
            }
        }
    }
    return $output;
}

function merge_subscription($input)
{
    $output = [];
    $vmess = "";
    $vless = "";
    $trojan = "";
    $shadowsocks = "";
    foreach ($input as $subscription_url) {
        $subscription_data = file_get_contents($subscription_url);
        $processed_array = process_subscriptions($subscription_data);
        $vmess .= isset($processed_array["vmess"])
            ? implode("\n", $processed_array["vmess"]) . "\n"
            : null;
        $vless .= isset($processed_array["vless"])
            ? implode("\n", $processed_array["vless"]) . "\n"
            : null;
        $trojan .= isset($processed_array["trojan"])
            ? implode("\n", $processed_array["trojan"]) . "\n"
            : null;
        $shadowsocks .= isset($processed_array["ss"])
            ? implode("\n", $processed_array["ss"]) . "\n"
            : null;
    }
    $output['vmess'] = explode("\n", $vmess);
    $output['vless'] = explode("\n", $vless);
    $output['trojan'] = explode("\n", $trojan);
    $output['ss'] = explode("\n", $shadowsocks);
    return $output;
}

function array_to_subscription($input) {
    return implode("\n", $input);
}
