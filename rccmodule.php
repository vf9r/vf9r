<?php
session_start();
ini_set('display_errors',1);

$sesid = rand(1, 1000000);
$jobexpiration = 100;
$baseurl = "http://roblox.com/";

$id = $_SESSION['id'];

$apicolors = json_decode(file_get_contents("https://www.example.com/apis/GetBodyColors.php?id=" . urlencode($id)));
$apihats = json_decode(file_get_contents("https://www.example.com/apis/GetHats.php?id=" . urlencode($id)));

$leftarmColor = $apicolors->laColor;
$rightarmColor = $apicolors->raColor;
$leftlegColor = $apicolors->llColor;
$rightlegColor = $apicolors->rlColor;
$headColor = $apicolors->headColor;
$torsoColor = $apicolors->torsoColor;
$hat1 = null;
$hat2 = null;
if ($apihats->hat1) {
    $hat1 = $apihats->hat1;
}
if ($apihats->hat2) {
    $hat2 = $apihats->hat2;
}


# lua
$script;

if ($hat1 and $hat2) {
    $script = 'local plr = game.Players:CreateLocalPlayer(0) plr:LoadCharacter() 
plr.Character.Head.BrickColor = BrickColor.new('. $headColor .')
plr.Character.Torso.BrickColor = BrickColor.new('. $torsoColor .')
plr.Character["Left Leg"].BrickColor = BrickColor.new('. $leftlegColor .')
plr.Character["Right Leg"].BrickColor = BrickColor.new('. $rightlegColor .')
plr.Character["Left Arm"].BrickColor = BrickColor.new('. $leftarmColor .')
plr.Character["Right Arm"].BrickColor = BrickColor.new('. $rightarmColor .')
local hat = game:GetObjects("rbxasset://'. $hat1 .'.rbxmx")[1] 
hat.Parent = plr.Character
local hat2 = game:GetObjects("rbxasset://'. $hat2 .'.rbxmx")[1] 
hat2.Parent = plr.Character
        local result = game:GetService("ThumbnailGenerator"):Click("PNG", 540, 660, true)
        return result';
} elseif ($hat2) {
    $script = 'local plr = game.Players:CreateLocalPlayer(0) plr:LoadCharacter() 
plr.Character.Head.BrickColor = BrickColor.new('. $headColor .')
plr.Character.Torso.BrickColor = BrickColor.new('. $torsoColor .')
plr.Character["Left Leg"].BrickColor = BrickColor.new('. $leftlegColor .')
plr.Character["Right Leg"].BrickColor = BrickColor.new('. $rightlegColor .')
plr.Character["Left Arm"].BrickColor = BrickColor.new('. $leftarmColor .')
plr.Character["Right Arm"].BrickColor = BrickColor.new('. $rightarmColor .')
local hat = game:GetObjects("rbxasset://'. $hat2 .'.rbxmx")[1] 
hat.Parent = plr.Character
        local result = game:GetService("ThumbnailGenerator"):Click("PNG", 540, 660, true)
        return result';
} elseif ($hat1) {
    $script = 'local plr = game.Players:CreateLocalPlayer(0) plr:LoadCharacter() 
plr.Character.Head.BrickColor = BrickColor.new('. $headColor .')
plr.Character.Torso.BrickColor = BrickColor.new('. $torsoColor .')
plr.Character["Left Leg"].BrickColor = BrickColor.new('. $leftlegColor .')
plr.Character["Right Leg"].BrickColor = BrickColor.new('. $rightlegColor .')
plr.Character["Left Arm"].BrickColor = BrickColor.new('. $leftarmColor .')
plr.Character["Right Arm"].BrickColor = BrickColor.new('. $rightarmColor .')
local hat = game:GetObjects("rbxasset://'. $hat1 .'.rbxmx")[1] 
hat.Parent = plr.Character
        local result = game:GetService("ThumbnailGenerator"):Click("PNG", 540, 660, true)
        return result';
        } else {
    $script = 'local plr = game.Players:CreateLocalPlayer(0) plr:LoadCharacter() 
plr.Character.Head.BrickColor = BrickColor.new('. $headColor .')
plr.Character.Torso.BrickColor = BrickColor.new('. $torsoColor .')
plr.Character["Left Leg"].BrickColor = BrickColor.new('. $leftlegColor .')
plr.Character["Right Leg"].BrickColor = BrickColor.new('. $rightlegColor .')
plr.Character["Left Arm"].BrickColor = BrickColor.new('. $leftarmColor .')
plr.Character["Right Arm"].BrickColor = BrickColor.new('. $rightarmColor .')
        local result = game:GetService("ThumbnailGenerator"):Click("PNG", 540, 660, true)
        return result';
}

$xml = '<?xml version="1.0" encoding="UTF-8"?>
<SOAP-ENV:Envelope 
    xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" 
    xmlns:ns1="'.$baseurl.'">
    <SOAP-ENV:Body>
        <ns1:OpenJob>
            <ns1:job>
                <ns1:id>'.$sesid.'</ns1:id>
                <ns1:expirationInSeconds>'.$jobexpiration.'</ns1:expirationInSeconds>
                <ns1:category>1</ns1:category>
                <ns1:cores>321</ns1:cores>
            </ns1:job>
            <ns1:script>
                <ns1:name>Script</ns1:name>
                <ns1:script><![CDATA[
                    '.$script.'
                ]]></ns1:script>
            </ns1:script>
        </ns1:OpenJob>
    </SOAP-ENV:Body>
</SOAP-ENV:Envelope>';

$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => "localhost", # change this to your rcc vps and port
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $xml, 
    CURLOPT_HTTPHEADER => array(
        'Content-Type: text/xml; charset=utf-8',
        'Content-Length: ' . strlen($xml),
    ),
));
$response = curl_exec($curl);
if ($response === false) {
    echo "Error drawing your character 1...\n";
    echo $response;
    sleep(1);
} else {
    $pos_start = strpos($response, '<ns1:value>') + strlen('<ns1:value>');
    $pos_end = strpos($response, '</ns1:value>', $pos_start);
    
    if ($pos_start !== false && $pos_end !== false) {
        $base64 = substr($response, $pos_start, $pos_end - $pos_start);
        $base64 = trim($base64);
        file_put_contents("../images/renders/".$id.".png",base64_decode($base64));
        echo "<script>
        window.location = '/my/Character.php';
        </script>";
    } else {
        echo "Error drawing your character 2...";
        echo "VARIABLE POS_START AND POS_END ARE EQUAL TO FALSE";
        sleep(1);
        echo "\nRedirecting..";
    }
}

curl_close($curl);
?>
