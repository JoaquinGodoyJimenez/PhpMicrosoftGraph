<?php
function apiRequest($accessToken, $url) {
    $ch = curl_init($url);
    $headers = [
        'Authorization: Bearer ' . $accessToken,
    ];

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}

function getToken($code) {
    $config = include('../config/config.php');
    $clientId = $config['clientId'];
    $clientSecret = $config['clientSecret'];
    $redirectUri = $config['redirectUri'];

    $tokenUrl = "https://login.microsoftonline.com/common/oauth2/v2.0/token";
    $postData = [
        'client_id' => $clientId,
        'scope' => 'openid profile email',
        'code' => $code,
        'redirect_uri' => $redirectUri,
        'grant_type' => 'authorization_code',
        'client_secret' => $clientSecret,
    ];

    $ch = curl_init($tokenUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);
    return $data['access_token'];
}

function getUsername($accessToken) {
    $graphApiUrl = 'https://graph.microsoft.com/v1.0/me';
    $response = apiRequest($accessToken, $graphApiUrl);

    $userData = json_decode($response, true);
    return isset($userData['displayName']) ? $userData['displayName'] : "No se pudo obtener el nombre de usuario.";
}

function getEmail($accessToken) {
    $graphApiUrl = 'https://graph.microsoft.com/v1.0/me?$select=userPrincipalName';
    $response = apiRequest($accessToken, $graphApiUrl);

    $userData = json_decode($response, true);
    return isset($userData['userPrincipalName']) ? $userData['userPrincipalName'] : "No se pudo obtener el correo del usuario.";
}

function getPhoto($accessToken) {
    $graphApiUrl = 'https://graph.microsoft.com/v1.0/me/photo/$value';
    $response = apiRequest($accessToken, $graphApiUrl);

    return $response ? $response : "No se pudo obtener la foto del usuario.";
}

function getReceivedEmails($accessToken) {
    $graphApiUrl = 'https://graph.microsoft.com/v1.0/me/messages?$select=id,subject,from,receivedDateTime,webLink';
    $response = apiRequest($accessToken, $graphApiUrl);

    $emailData = json_decode($response, true);

    if (isset($emailData['value']) && is_array($emailData['value'])) {
        $emails = [];
        foreach ($emailData['value'] as $email) {
            $emailSubject = $email['subject'];
            $emailFrom = $email['from']['emailAddress']['name'];
            $emailReceivedDateTime = $email['receivedDateTime'];
            $emailLink = $email['webLink'];

            $emails[] = [
                'subject' => $emailSubject,
                'from' => $emailFrom,
                'receivedDateTime' => $emailReceivedDateTime,
                'link' => $emailLink,
            ];
        }
        return $emails;
    } else {
        return "No se pudieron obtener los correos recibidos del usuario.";
    }
}
?>