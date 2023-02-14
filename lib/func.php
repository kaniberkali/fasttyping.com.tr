<?php
function p2a($query, $connection, $debug=0){
    if($debug==1) echo "<div>$query</div>";
    $result=$connection->query($query);
    (int)$count=$result->rowCount();
    if($count>0){
        $decod = $result->fetchAll(PDO::FETCH_ASSOC);
        return $decod;
    }else{
        return array();
    }
}

function a2s_u($table, $data, $id_field, $id_value) {
    foreach ($data as $field => $value) {

        $fields[] = sprintf("`%s` = '%s'", $field, addslashes($value));
    }
    $field_list = join(',', $fields);
    $query = sprintf("UPDATE `%s` SET %s WHERE `%s` = %s", $table, $field_list, $id_field, intval($id_value));
    return $query;
}

function a2s_i($table, $data) {
    foreach ($data as $field => $value) {
        $fields[] = sprintf("`%s` = '%s'", $field, addslashes($value));
    }
    $field_list = join(',', $fields);
    $query = sprintf("INSERT INTO `%s` SET %s", $table, $field_list);
    return $query;
}

function a2s_i_($table, $data) {
    foreach ($data as $field => $value) {
        $fields[] = sprintf("`%s` = '%s'", $field, $value);
    }
    $field_list = join(',', $fields);
    $query = sprintf("INSERT INTO `%s` SET %s", $table, $field_list);
    return $query;
}

function encrypt($plainText, $key){
    $secretKey = md5($key);
    $iv = substr(hash('sha256', "aaaabbbbcccccddddeweee"), 0, 16);
    $encryptedText = openssl_encrypt(
        $plainText,
        'AES-128-CBC',
        $secretKey,
        OPENSSL_RAW_DATA,
        $iv
    );
    return base64_encode($encryptedText);
}

function decrypt($encryptedText, $key){
    $key = md5($key);
    $iv = substr(hash('sha256', "aaaabbbbcccccddddeweee"), 0, 16);
    $decryptedText = openssl_decrypt(
		base64_decode($encryptedText),
        'AES-128-CBC',
        $key,
        OPENSSL_RAW_DATA,
        $iv
    );
    return $decryptedText;
}

function randomGenerator($length = 25) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function randomUserKey()
{
    return rand(1, 2147483647);
}

function startsWith($haystack, $needle)
{
     $length = strlen($needle);
     return (substr($haystack, 0, $length) === $needle);
}

function isUserName($text)
{
    return (bool)(strlen($text) >= 5 && strlen($text) <= 100 &&
    preg_match("/^[a-z]+$/", $text));
}

function isPassword($text)
{
    return (bool)(strlen($text) >= 5 && strlen($text) <= 100);
}

function isEmail($text)
{
    return (bool)(strlen($text) >= 5 && strlen($text) <= 100) &&
    preg_match("/^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/", $text);
}

function sendEmail($email, $username, $title, $message, $theme)
{
	$mail = new PHPMailer();
	$mail->IsSMTP();
	$mail->SMTPAuth = true;
	$mail->Host = "{mail host}";
	$mail->Port = 465;
	$mail->SMTPSecure = "ssl";
	$mail->Username = "{mail}";
	$mail->Password = "{password}";
	$mail->SetFrom($mail->Username, $title);
	$mail->AddAddress($email, $username);
	$mail->CharSet = "UTF-8";
	$mail->Subject = $title;
	$content = str_replace("{title}", $title, file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/inc/mail/notification.html'));
	$content = str_replace("{theme}", $theme, $content);
	$content = str_replace("{username}", $username, $content);
	$content = str_replace("{message}", $message, $content);
    $mail->MsgHTML($content);
    return $mail->Send();
}

function allUserMailSender($title, $message, $db)
{
    $result = [];
    $users = p2a("SELECT mail, username, theme FROM users", $db);
    foreach($users as $user)
        array_push($result, ["user"=>$user["username"], "result"=>sendEmail($user["mail"],$user["username"], $title, $message,$user["theme"])]);
    return $result;
}

?>