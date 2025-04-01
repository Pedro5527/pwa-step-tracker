<?php
header('Content-Type: application/json');

if (!isset($_GET['product'])) {
    echo json_encode(["error" => "Kein Produkt angegeben"]);
    exit;
}

$product = urlencode($_GET['product']);
$kauflandUrl = "https://www.kaufland.de/suche/?search_value=$product";
$reweUrl = "https://www.rewe.de/suche/?search=$product";

function scrapePrice($url, $store) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0");
    $html = curl_exec($ch);
    curl_close($ch);

    preg_match('/€\s?([0-9,]+)/', $html, $matches);
    return $matches ? ["store" => $store, "price" => $matches[1] . " €"] : ["store" => $store, "price" => "Nicht gefunden"];
}

$kauflandPrice = scrapePrice($kauflandUrl, "Kaufland");
$rewePrice = scrapePrice($reweUrl, "Rewe");

echo json_encode([$kauflandPrice, $rewePrice]);
?>
