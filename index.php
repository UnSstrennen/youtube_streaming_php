<?php
include('cfg.php');
// form handler
if (isset($_POST['insert_yt_broadcast'])) {
    $ch = curl_init();

    // set api params for youtube broadcast below
    // you can discover params there: https://developers.google.com/youtube/v3/live/docs/liveBroadcasts?hl=ru#properties
    $params = array(
        "snippet" => array(
            "scheduledStartTime" => $_POST['yt_start_time'] . '+03',
            "title" => $_POST['yt_title']
        ),
        "status" => array(
            "privacyStatus" => "unlisted",
            "selfDeclaredMadeForKids" => false
        ),
        "kind" => "youtube#liveBroadcast"
    );

    // @ATTENTION: look at link below: don't forget to specify part argument if you added sth from another parts
    curl_setopt($ch, CURLOPT_URL, 'https://youtube.googleapis.com/youtube/v3/liveBroadcasts?part=status&part=snippet&key=' . $_COOKIE['access_token']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

    $headers = array();
    $headers[] = 'Authorization: Bearer ' . $_COOKIE['access_token'];
    $headers[] = 'Accept: application/json';
    $headers[] = 'Content-Type: application/json';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = json_decode(curl_exec($ch), true);
    if (curl_errno($ch)) {
         echo 'Error:' . curl_error($ch);
    }
    else {
        echo 'Success!  ';
        // if it dowsn't echo any ID, then cookie has been removed or broadcast was planned for a past time
        echo 'https://www.youtube.com/watch?v=' . $result['id'];
    }
    curl_close($ch);
}

// auth handler. There's nothing to change because auth process is strictly standardised
else if (!isset($_COOKIE['access_token'])) {
    // auth, nothing interesting
    // step 2
    $headers = array();
    if (isset($_GET['code'])) {
        
        $headers['Content-Type'] = "application/x-www-form-urlencoded";
        $code = $_GET['code'];
        $scope = $_GET['scope'];
        $url = "https://accounts.google.com/o/oauth2/token";
        $params = array(
            "code" => $code,
            "redirect_uri" => $redirect_uri,
            "client_id" => $client_id,
            "client_secret" => $client_secret,
            "scope" => $scope,
            "grant_type" => "authorization_code"
            );
        $params = http_build_query($params);
    }
    else {
        // auth, nothing interesting
        // step 1
        $url = "https://accounts.google.com/o/oauth2/auth";
        $params = array(
        "redirect_uri" => $redirect_uri,
        "prompt" => "consent",
        "client_id" => $client_id,
        "response_type" => "code",
        "scope" => "https://www.googleapis.com/auth/youtube.force-ssl",
        "access_type" => "offline"
        );
    }
    // sending request to googleapis
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $json_response = curl_exec($curl);
    curl_close($curl);

    // if we're in step when we don't have token
    if (strpos($json_response, 'access_token') === false) {
        echo $json_response;
        exit(0);
    }

    // if we got token in response
    $json_response = json_decode($json_response, true);
    setcookie("access_token", $json_response['access_token'],
    time() + $json_response['expires_in']);
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Создание трансляции</title>
</head>
<body>
    <form action="" method="post", enctype="multipart/form-data">
        <input type="hidden" name="insert_yt_broadcast", value="true">
        <label for="name">Название</label>
        <input type="text" id="name" name="yt_title" placeholder="Название">
        <br>
        <label for="time">Время начала</label>
        <input type="datetime-local" name="yt_start_time" id="time">
        <input type="submit">
    </form>
</body>
</html>