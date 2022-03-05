<?php

echo "[+] Bot Heker Makanan - By: GidhanB.A\n";
echo "[+] Input File Cookie: ";
$file = trim(fgets(STDIN));

$data = file_get_contents($file);
$pisah = explode("\n{", $data);
$body = str_replace("\r", "", "{".$pisah[1]);

$a = explode("\n", $pisah[0]);
array_shift($a);
array_shift($a);
array_pop($a);
for ($i=0; $i < count($a); $i++) { 
    if (strpos($a[$i], 't-length') || strpos($a[$i], 't-encoding')) {
        unset($a[$i]);
    }
}
$a = array_values($a);

$headers = array();
for ($i=0; $i < count($a); $i++) { 
    $headers[] = str_replace("\r", "", $a[$i]);
}

echo "\n";
$a = true;
while ($a) {
    $gas = curl('https://foody.shopee.co.id/api/buyer/orders', $body, $headers);
    $date = "[".date("H:i:s")."]";
    if (strpos($gas[1], 'Gagal Checkout (K06)')) {
        echo color('red', $date)." ".json_decode($gas[1])->msg."\n";
    } else {
        echo color('green', $date)." ".json_decode($gas[1])->msg."\n";
        $a = false;
    }
}

function curl($url, $post, $headers, $follow = false, $method = null)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		if ($follow == true) curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		if ($method !== null) curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
		if ($headers !== null) curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		if ($post !== null) curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		$result = curl_exec($ch);
		$header = substr($result, 0, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
		$body = substr($result, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
		preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $result, $matches);
		$cookies = array();
		foreach ($matches[1] as $item) {
			parse_str($item, $cookie);
			$cookies = array_merge($cookies, $cookie);
		}
		return array(
			$header,
			$body,
			$cookies
		);
	}

function color($color, $text)
    {
        $arrayColor = array(
            'grey'      => '1;30',
            'red'       => '1;31',
            'green'     => '1;32',
            'yellow'    => '1;33',
            'blue'      => '1;34',
            'purple'    => '1;35',
            'nevy'      => '1;36',
            'white'     => '1;0',
        );  
        return "\033[".$arrayColor[$color]."m".$text."\033[0m";
    }
