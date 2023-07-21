<?php

include "config.php";
include "functions.php";

$merged_data = merge_subscription($subscription_urls);

file_put_contents("merged", $merged_data);
file_put_contents("merged_base64", base64_encode($merged_data));
