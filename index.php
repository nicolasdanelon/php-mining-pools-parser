<?php

require_once(__DIR__ . '/vendor/autoload.php');

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

$btc = getenv('BTC_WALLET');
$mphKey = getenv('MPH_API_KEY');

use Goutte\Client;
$client = new Client();
$crawlerZ = $client->request('GET', 'http://www.zpool.ca/site/wallet_results?address=' . $btc);
$crawlerH = $client->request('GET', 'http://pool.hashrefinery.com/site/wallet_results?address=' . $btc);
$crawlerA = $client->request('GET', 'https://www.ahashpool.com/wallet_wallet_results.php?showdetails=1&wallet='.$btc);

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mining</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>body, html { font-family: sans-serif }</style>
</head>
<body>
<?php

function showTable($url, $params)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $server_output = curl_exec($ch);
    curl_close($ch);

    return json_decode($server_output, true);
}

$coins = [
    [
        'server' => "https://miningpoolhub.com/index.php",
        'params' => "page=api&api_key=" . $mphKey . "&action=getuserallbalances"
    ],
];

$total = [];

foreach ($coins as $coin) {
    $data = showTable($coin['server'], $coin['params']);
    foreach ($data as $key => $value) {
        foreach ($value['data'] as $k => $v) {
            if ($v['coin'] === 'bitcoin') {
                $total['mph'] = $v['confirmed'];
            }
            echo '<div style="float: left; width: 300px">' . PHP_EOL;
                echo '
                <h3>'.ucfirst($v['coin']).'</h3>
                <table>
                    <tr>
                        <td style="width: 160px">Confirmed</td>
                        <td>' . number_format($v['confirmed'], 8) . '</td>
                    </tr>
                    <tr>
                        <td>Unconfirmed</td>
                        <td>' . number_format($v['unconfirmed'], 8) . '</td>
                    </tr>
                    <tr>
                        <td>AE Confirmed</td>
                        <td>' . number_format($v['ae_confirmed'], 8) . '</td>
                    </tr>
                    <tr>
                        <td>AE Unconfirmed</td>
                        <td>' . number_format($v['ae_unconfirmed'], 8) . '</td>
                    </tr>
                    <tr>
                        <td>Exchange</td>
                        <td>' . number_format($v['exchange'], 8) . '</td>
                    </tr>
                </table>' . PHP_EOL;
            echo '</div>' . PHP_EOL;
        }
    }
}

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://www.zpool.ca/api/wallet?address=' . $btc );
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$server_output = curl_exec($ch);
curl_close($ch);

$data = json_decode($server_output, true);

$total['zp'] = $data['total'];

echo '<div style="">' . PHP_EOL;
echo '<table>' . PHP_EOL;
echo '<tr><td colspan="2"><h3>' . $data['currency'] . ' (zpool)</h3></td></tr>' . PHP_EOL;
echo '<tr><td style="width: 160px">Unsold</td><td>' . number_format($data['unsold'], 8) . '</td></tr>' . PHP_EOL;
echo '<tr><td>Balance</td><td>' . number_format($data['balance'], 8) . '</td></tr>' . PHP_EOL;
echo '<tr><td>Unpaid</td><td>' . number_format($data['unpaid'], 8) . '</td></tr>' . PHP_EOL;
echo '<tr><td>Paid24h</td><td>' . number_format($data['paid24h'], 8) . '</td></tr>' . PHP_EOL;
echo '<tr><td>Total</td><td>' .  number_format($data['total'], 8) . '</td></tr>' . PHP_EOL;
echo '</table>' . PHP_EOL;
echo '</div>' . PHP_EOL;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://pool.hashrefinery.com/api/wallet?address=' . $btc);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$server_output = curl_exec($ch);
curl_close($ch);

$data = json_decode($server_output, true);

$total['hr'] = $data['total'];

echo '<div style="">' . PHP_EOL;
echo '<table>' . PHP_EOL;
echo '<tr><td colspan="2"><h3>' . $data['currency'] . ' (hashrefinery)</h3></td></tr>' . PHP_EOL;
echo '<tr><td style="width: 160px">Unsold</td><td>' . number_format($data['unsold'], 8) . '</td></tr>' . PHP_EOL;
echo '<tr><td>Balance</td><td>' . number_format($data['balance'], 8) . '</td></tr>' . PHP_EOL;
echo '<tr><td>Unpaid</td><td>' . number_format($data['unpaid'], 8) . '</td></tr>' . PHP_EOL;
echo '<tr><td>Paid24h</td><td>' . number_format($data['paid24h'], 8) . '</td></tr>' . PHP_EOL;
echo '<tr><td>Total</td><td>' .  number_format($data['total'], 8) . '</td></tr>' . PHP_EOL;
echo '</table>' . PHP_EOL;
echo '</div>' . PHP_EOL;


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://www.ahashpool.com/api/wallet/?address=' . $btc);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$server_output = curl_exec($ch);
curl_close($ch);

$data = json_decode($server_output, true);

$total['ad'] = $data['total'];

echo '<div style="">' . PHP_EOL;
echo '<table>' . PHP_EOL;
echo '<tr><td colspan="2"><h3>' . $data['currency'] . ' (ahashpool)</h3></td></tr>' . PHP_EOL;
echo '<tr><td style="width: 160px">Unsold</td><td>' . number_format($data['unsold'], 8) . '</td></tr>' . PHP_EOL;
echo '<tr><td>Balance</td><td>' . number_format($data['balance'], 8) . '</td></tr>' . PHP_EOL;
echo '<tr><td>Unpaid</td><td>' . number_format($data['unpaid'], 8) . '</td></tr>' . PHP_EOL;
echo '<tr><td>Paid24h</td><td>' . number_format($data['paid24h'], 8) . '</td></tr>' . PHP_EOL;
echo '<tr><td>Total</td><td>' .  number_format($data['total'], 8) . '</td></tr>' . PHP_EOL;
echo '</table>' . PHP_EOL;
echo '</div>' . PHP_EOL;

$kurl = curl_init();
curl_setopt($kurl, CURLOPT_URL, 'https://api.coinmarketcap.com/v1/ticker/?convert=USD&limit=1');
curl_setopt($kurl, CURLOPT_RETURNTRANSFER, true);
$output = curl_exec($kurl);
curl_close($kurl);

$d = json_decode($output, true);

$usd = $d[0]['price_usd'];
$btc = $total['hr'] + $total['zp'] + $total['mph'] + $total['ad'];


echo '<h2>1 BTC = ' . $usd . ' USD</h2>';
echo '<h2>Total BTC = ' . $btc . '</h2>';
echo '<h2>Total USD = ' . $usd * $btc . '</h2>';

echo '<br><br>';

echo '<h3>zpool</h3>';
$crawlerZ->filter('table td')->each(function ($node, $key) {
	if ($key == 20 || $key == 22) 
	echo $node->text() . '<br>';
});

echo '<h3>hashrefinery</h3>';
$crawlerH->filter('table td')->each(function ($node, $key) {
	if ($key == 25 || $key == 27) 
        echo $node->text() . '<br>';
});

echo '<h3>ahashpool</h3>';
$crawlerA->filter('table td')->each(function ($node, $key) {
if ($key == 23 || $key == 21) 
        echo $node->text() . '<br>';
});

?>
</body>
</html>

