<?php
include  $_SERVER['DOCUMENT_ROOT'] . "/lib/config.php";
header('Content-Type: application/json; charset=utf-8');
$act = $_GET["action"];

$isSecurty = 1;
foreach ($_REQUEST as $key => $value)
{
    if ($_REQUEST[$key] != preg_replace('/\b(SELECT|INSERT|UPDATE|DELETE)\b/', '', $value) || $_REQUEST[$key] != preg_replace('/[><]/', '_', $value))
        $isSecurty = 0;
}
if ($isSecurty)
{
    if ($user && $user["approval"] == 1)
    {
		if ($act == "get-invites")
		{
			$data = p2a("SELECT rooms_invite.date, rooms_invite.room_id, rooms_invite.user_id, users.username, rooms.code, rooms.password
						FROM rooms_invite
						JOIN users ON rooms_invite.user_id = users.id
						JOIN rooms ON rooms_invite.room_id = rooms.id
						WHERE rooms_invite.invite_id = ". $user["id"], $db);
			if ($data)
				echo json_encode($data, JSON_UNESCAPED_UNICODE);
			else
				echo "false";
		}

		if ($act == "set-invite")
		{
			if ((int)$_POST["invite_id"] > 0)
			{
				$data = p2a("SELECT * FROM rooms_invite WHERE room_id=".$user["game_id"]. " AND invite_id=". (int)$_POST["invite_id"],$db);
				if (!$data)
				{
					p2a("INSERT INTO rooms_invite (room_id, user_id, invite_id, date) VALUES(".$user["game_id"].", ".$user["id"].", ".(int)$_POST["invite_id"].", '".date("Y-m-d H:i:s")."')", $db);
					echo "true";
				}
				else
					echo "false";
			}
			else
				echo "false";
		}

		if ($act == "accept-game")
		{
			if ((int)$_POST["room_id"] > 0)
            {
                p2a("DELETE FROM rooms_invite WHERE invite_id=".$user["id"]. " AND room_id=".$_POST["room_id"], $db);
                echo "true";
            }
            else
                echo "false";
		}

		if ($act == "deny-game")
		{
			if ((int)$_POST["room_id"] > 0)
			{
				p2a("DELETE FROM rooms_invite WHERE room_id=".(int)$_POST["room_id"] . " AND invite_id=".$user["id"], $db);
				echo "true";
			}
			else
				echo "false";
		}

		if ($act == "send-change-password-verify")
		{
			if (strtotime(date("Y-m-d H:i:s")) - strtotime($user["last_send_mail"]. "+ 5 minute") > 0)
			{
				$code = rand(100000, 1000000);
				$eposta = $user['mail'];
				$ad_soyad = $user['username'];
				$mail = new PHPMailer();
				$mail->IsSMTP();
				$mail->SMTPAuth = true;
				$mail->Host = "{mail host}";
				$mail->Port = 465;
				$mail->SMTPSecure = "ssl";
				$mail->Username = "{mail}";
				$mail->Password = "{password}";
				$mail->SetFrom($mail->Username, "Fast Typing - Şifre Değiştirme");
				$mail->AddAddress($eposta, $ad_soyad);
				$mail->CharSet = "UTF-8";
				$mail->Subject = "Doğrulama Kodu :". $code;
				$content = str_replace("{code}", $code, file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/inc/mail/change-password.html'));
				$content = str_replace("{theme}",$user["theme"], $content);
				$mail->MsgHTML($content);
				if ($mail->Send())
				{
					p2a("UPDATE users SET change_password=".$code.", last_send_mail='".date("Y-m-d H:i:s")."' WHERE id=".$user["id"], $db);
					echo "true";
				}
				else
					echo "false";
			}
			else
				echo strtotime($user["last_send_mail"]. "+ 5 minute")- strtotime(date("Y-m-d H:i:s"));
		}
		if ($act == "check-change-verify")
		{
			if ((int)$user["change_password"] == (int)$_POST["code"] && (int)$user["change_password"] > 0)
				echo "true";
			else
				echo "false";
		}
		if ($act == "change-password")
		{
			if ((int)$user["change_password"] == (int)$_POST["code"] && (int)$user["change_password"] > 0  && isPassword($_POST["password"]) &&isPassword($_POST["passwordAgain"]) && $_POST["password"] == $_POST["passwordAgain"])
			{
				p2a("UPDATE users SET change_password=0, `password`='".$_POST["password"]."' WHERE id=".$user["id"], $db);
				echo "true";
			}
			else
				echo "false";
		}
		if ($act == "change-password-cancel")
		{
			p2a("UPDATE users SET change_password=0 WHERE id=".$user["id"], $db);
			echo "true";
		}
		if ($act == "check-ban")
		{
			echo p2a("SELECT * FROM rooms_bans WHERE room_id=".$user["game_id"]. " AND user_id=".$user["id"], $db) ? "true" : "false";
		}
		if ($act == "ban-player")
		{
			$id = (int)$_POST["id"];
			if ($id > 0)
			{
				$data = p2a("SELECT admin_id, id FROM rooms WHERE admin_id=".$user["id"]. " AND id=".$user["game_id"], $db);
				if ($data)
				{
                	p2a("INSERT INTO rooms_bans (room_id, user_id) VALUES(".$user["game_id"].", ".$id.")", $db);
					echo "true";
				}
				else
					echo "false";
			}
			else
				echo "false";
		}
		if ($act == "unban-player")
		{
			$id = (int)$_POST["id"];
			if ($id > 0)
			{
				$data = p2a("SELECT admin_id, id FROM rooms WHERE admin_id=".$user["id"]. " AND id=".$user["game_id"], $db);
				if ($data)
				{
					p2a("DELETE FROM rooms_bans WHERE room_id=". $user["game_id"]. " AND user_id=" . $id, $db);
					echo "true";
				}
				else
					echo "false";
			}
			else
				echo "false";
		}
        if ($act == "room-send-message")
        {
            if (isset($_POST["message"]))
            {
                p2a("INSERT INTO rooms_chat (room_id, user_id, message) VALUES(".$user["game_id"].", ".$user["id"].", '".$_POST["message"]."')", $db);
                echo "true";
            }
            else
                echo "false";
        }
        if ($act == "room-get-messages")
        {
            if ($user["game_type"] == 1)
            {
                $messages = p2a("SELECT r.*, u.username, CASE WHEN user_id = ".$user["id"]." THEN 1 ELSE 0 END as is_you
                FROM rooms_chat r
                JOIN users u ON r.user_id = u.id
                WHERE r.room_id = ".$user["game_id"]. " ORDER BY r.id ASC", $db);
                echo json_encode($messages, JSON_UNESCAPED_UNICODE);
            }
            else
                echo "false";
        }
		if ($act == "room-lock-control")
		{
			$id = (int)$_POST["id"];
			if ($id > 0)
			{
				$data = p2a("SELECT * FROM rooms WHERE code=".$id, $db)[0];
                if ($data)
                    echo $data["size"] > p2a("SELECT COUNT(*) as count FROM `rooms_users` WHERE room_id=".$data["id"]. " AND user_id !=".$user["id"], $db)[0]["count"] ? "true" : "false";
                else
                    echo json_encode("refresh", JSON_UNESCAPED_UNICODE);
			}
			else
				echo json_encode("refresh", JSON_UNESCAPED_UNICODE);
		}

		if ($act == "rooms-match")
		{
			$data = p2a("SELECT * FROM rooms_match WHERE user_id=".$user["id"], $db)[0];
			if (!$data)
				p2a("INSERT INTO rooms_match (user_id, date) VALUES(".$user["id"].", '".date("Y-m-d H:i:s")."')", $db);
			$match = p2a("SELECT * FROM rooms_match ORDER BY date ASC", $db);
			if (count($match) >= 2)
			{
				if ($match[0]["user_id"] == $user["id"])
				{
					$key = randomUserKey();
					p2a("INSERT INTO rooms (admin_id, name, code, size, create_time) VALUES(".$user["id"].", '".$user["username"]." room', ".$key.",2 , '".date("Y-m-d H:i:s")."')", $db);
					$last = $db->lastInsertId();
					p2a("INSERT INTO rooms_users (room_id, user_id) VALUES(".$last.", ".$user["id"].")", $db);
					$words = p2a("SELECT * FROM words ORDER BY RAND() LIMIT 0,150", $db);
					p2a("UPDATE users SET game_id=". $last . ",game_type=1, game_words='" . json_encode(array_column($words, 'word'), JSON_UNESCAPED_UNICODE) . "' WHERE id=".$user["id"], $db);
					echo json_encode(["key" => $key], JSON_UNESCAPED_UNICODE);
				}
				else if ($match[1]["user_id"] == $user["id"])
				{
					$data = p2a("SELECT admin_id, id, code FROM rooms WHERE admin_id=".$match[0]["user_id"], $db)[0];
					if ($data)
					{
						p2a("INSERT INTO rooms_users (room_id, user_id) VALUES(".$data["id"].", ".$user["id"].")", $db);
						p2a("DELETE FROM rooms_match WHERE user_id=".$user["id"], $db);
						p2a("DELETE FROM rooms_match WHERE user_id=".$match[0]["user_id"], $db);
						$words = p2a("SELECT users.game_words 
						FROM rooms 
						JOIN users ON rooms.admin_id = users.id 
						WHERE rooms.id = ". $data["id"], $db)[0]["game_words"];
						p2a("UPDATE users SET game_id=". $data["id"] . ",game_type=1, game_words='" . $words . "' WHERE id=".$user["id"], $db);
						echo json_encode(["key" => $data["code"]], JSON_UNESCAPED_UNICODE);
					}
					else
						echo count($match);
				}
				else
					echo count($match);
			}
			else
				echo count($match);
		}

		if ($act == "room-password-checker")
		{
			$id = (int)$_POST["id"];
			if ($id > 0)
			{
				$data = p2a("SELECT * FROM rooms WHERE code=".$id, $db)[0];
				if ($data["size"] > p2a("SELECT COUNT(*) as count FROM `rooms_users` WHERE room_id=".$data["id"], $db)[0]["count"] && ($data["password"] == $_POST["password"] || strlen($data["password"]) == 0 || $data["password"] == "undefined"))
				{
                    $is_exist = p2a("SELECT * FROM rooms_users WHERE user_id=".$user["id"]. " AND room_id=".$user["game_id"], $db);
                    if (!$is_exist)
                    {
                        p2a("INSERT INTO `rooms_users`(`room_id`, `user_id`) VALUES (".$data["id"].",".$user["id"].")", $db);
						$words = p2a("SELECT users.game_words 
						FROM rooms 
						JOIN users ON rooms.admin_id = users.id 
						WHERE rooms.id = ". $data["id"], $db)[0]["game_words"];
                        p2a("UPDATE users SET game_id=". $data["id"] . ",game_type=1, game_words='" . $words . "' WHERE id=".$user["id"], $db);
                    }
                    echo "true";
                }
				else
					echo "false";
			}
			else
				echo "false";
		}
		if ($act == "room-get-users")
		{
			$users = p2a("SELECT u.id, u.username, u.level,
			CASE
			WHEN r.admin_id = u.id THEN 1
			ELSE 0
			END AS is_admin,
			CASE
			WHEN u.id = ".$user["id"]." THEN 1
			ELSE 0
			END AS is_you,
			(SELECT  IFNULL(MAX(score),0) FROM scores WHERE user_id = u.id) AS max_score
			FROM users u
			JOIN rooms_users ru ON u.id = ru.user_id
			JOIN rooms r ON r.id = ru.room_id
			WHERE r.id = ".$user["game_id"], $db);
			echo json_encode($users, JSON_UNESCAPED_UNICODE); 
		}

		if ($act == "room-get-ban-users")
		{
			$users = p2a("SELECT u.id, 
			u.username, 
			u.level,
			(SELECT  IFNULL(MAX(score),0) FROM scores WHERE user_id = u.id) AS max_score
			FROM users u
			JOIN rooms_bans r ON r.user_id = u.id
			WHERE r.room_id = ".$user["game_id"], $db);
			echo json_encode($users, JSON_UNESCAPED_UNICODE); 
		}
		if ($act == "create-room")
		{
			if (isset($_POST["name"]) && isset($_POST["size"]) && isset($_POST["password"]))
			{
				$key = randomUserKey();
				p2a("INSERT INTO `rooms`(`admin_id`, `name`, `code`, `password`, `size`, `create_time`) VALUES (".$user["id"].",'".$_POST["name"]."',".$key.",'".$_POST["password"]."',".$_POST["size"].",'".date("Y-m-d H:i:s")."')", $db);
				$last = $db->lastInsertId();
				p2a("INSERT INTO `rooms_users`(`room_id`, `user_id`) VALUES (".$last.",".$user["id"].")", $db);
				$words = p2a("SELECT * FROM words ORDER BY RAND() LIMIT 0,150". $limit, $db);
                p2a("UPDATE users SET game_id=". $last . ",game_type=1, game_words='" . json_encode(array_column($words, 'word'), JSON_UNESCAPED_UNICODE) . "' WHERE id=".$user["id"], $db);
				echo $key;
			}
			else
				echo "false"; 
		}
		if ($act == "room-next-start")
		{
			$data = p2a("SELECT * FROM rooms WHERE admin_id=".$user["id"], $db);
			if ($data)
			{
				p2a("UPDATE `rooms` SET `end_time`='' WHERE id=".$user["game_id"], $db);
				p2a("UPDATE rooms_users SET true_word=0, false_word=0, true_letter=0, false_letter=0, score=0 WHERE room_id=".$user["game_id"], $db);
				$words = p2a("SELECT * FROM words ORDER BY RAND() LIMIT 0,150". $limit, $db);
				p2a("UPDATE users SET game_words='".json_encode(array_column($words, 'word'), JSON_UNESCAPED_UNICODE)."' WHERE game_id=".$user["game_id"], $db);
				echo "true";
			}
			else
				echo "false";
		}
		if ($act == "get-rooms")
		{
			$rooms = p2a("SELECT r.id, r.name, r.code, r.size, r.create_time, u.username AS username,
			(SELECT COUNT(*) FROM rooms_users ru WHERE ru.room_id = r.id) AS current_size,
			CASE WHEN LENGTH(r.password) > 0 THEN 1 ELSE 0 END AS is_lock
			FROM rooms r
			LEFT JOIN users u ON u.id = r.admin_id
			WHERE (SELECT COUNT(*) FROM rooms_users ru WHERE ru.room_id = r.id) < size", $db);
			echo json_encode($rooms, JSON_UNESCAPED_UNICODE);
		}
		if ($act == "offline-stop")
		{
			if ($user["game_id"] > 0)
			{
				p2a("DELETE FROM scores WHERE id=". $user["game_id"], $db);
				echo "true";
			}
			else
				echo "false";
		}
        if ($act == "offline-start")
        {
            $limit = "";
            $count = (int)$_POST["count"];
            if ($count > 0)
            {
                $limit = "LIMIT 0, ". $count;
                if ($user["game_id"] != 0)
                {
                    $data = p2a("SELECT * FROM scores WHERE id=" . $user["game_id"], $db)[0];
                    if ($data["score"] <= 0 || ($data["end_time"] < date("Y-m-d H:i:s")))
                        p2a("DELETE FROM scores WHERE id=" . $user["game_id"], $db);
                }
				$words = p2a("SELECT * FROM words ORDER BY RAND() ". $limit, $db);
                p2a("INSERT INTO scores (user_id, end_time) VALUES (" . $user["id"] . ", '" . date("Y-m-d H:i:s", strtotime( date("Y-m-d H:i:s") . "+ 66 second")) . "')", $db);
                $game_id = $db->lastInsertId();
                p2a("UPDATE users SET game_id=". $game_id . ",game_type=0, game_words='" . json_encode(array_column($words, 'word'), JSON_UNESCAPED_UNICODE) . "', last_time='".date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s") . "+ 66 second"))."' WHERE id=".$user["id"], $db);
                $user["game_id"] = $game_id;
                echo json_encode($words, JSON_UNESCAPED_UNICODE);
            }
            else
                echo "false";
        }
        if ($act == "time")
        {
            $current_time = date("Y-m-d H:i:s");
            $time = p2a("SELECT end_time FROM scores WHERE id=". $user["game_id"], $db)[0]["end_time"];
			if ($time)
			{
				$result = strtotime($time) - strtotime($current_time);
				echo $result;
			}
			else
				echo "false";
        }

		if ($act == "room-time")
		{
			$current_time = date("Y-m-d H:i:s");
            $time = p2a("SELECT end_time FROM rooms WHERE id=". $user["game_id"], $db)[0]["end_time"];
			if ($time)
			{
				$result = strtotime($time) - strtotime($current_time);
				echo $result;
			}
			else
				echo "false";
		}
        if ($act == "score")
        {
			if ($user["game_type"] == 1)
            	$data = p2a("SELECT JSON_UNQUOTE(u.game_words->'$[0]') as word, ru.score, ru.true_word, ru.false_word, ru.true_letter, ru.false_letter, r.end_time
				FROM users u
				JOIN rooms_users ru on u.id = ru.user_id
				JOIN rooms r on ru.room_id = r.id
				WHERE u.id = ".$user["id"]." AND r.id = ".$user["game_id"], $db)[0];
			else
				$data = p2a("SELECT JSON_UNQUOTE(u.game_words->'$[0]') AS word, r.* FROM scores r JOIN users u ON r.user_id = u.id WHERE r.id=". $user["game_id"], $db)[0];
			if (strtotime($data["end_time"]) - strtotime(date("Y-m-d H:i:s")) < 0)
			{
				if ($user["game_type"] == 0)
				{
					p2a("UPDATE users SET game_id=0, game_words=null WHERE id=".$user["id"], $db);
					$user["game_id"] = 0;
				}
				echo "false";
			}
			else
			{
				if (strtotime($data["end_time"]) - strtotime(date("Y-m-d H:i:s")) > 60)
					$data["score"] -= 50;
				else
				{
					if ($data["word"] == $_POST["input"])
					{
						$data["score"] += 10;
						$data["true_word"]++;
						$data["false_word"] = (int)$data["false_word"];
					}
					else
					{
						$data["score"] -= 10;
						$data["false_word"]++;
						$data["true_word"] = (int)$data["true_word"];
					}
					if (startsWith($data["word"], $_POST["input"]))
					{
						$data["true_letter"] += (int)(strlen($_POST["input"]));
						$data["false_letter"] += (int)(strlen($data["word"]) - strlen($_POST["input"]));
						$data["score"] += (int)(strlen($_POST["input"])) - (int)(strlen($data["word"]) - strlen($_POST["input"]));
					}
					else
					{
						$data["false_letter"] += (int)(strlen($data["word"]));
						$data["score"] -= (int)(strlen($data["word"]));
					}
				}
				p2a("UPDATE users SET game_words=JSON_REMOVE(game_words, '$[0]') WHERE id=".$user["id"], $db);
				if ($user["game_type"] == 1)
					p2a("UPDATE rooms_users SET true_word=".$data["true_word"].", false_word=".$data["false_word"].", true_letter=".$data["true_letter"].", false_letter=".$data["false_letter"].", score=".$data["score"]." WHERE room_id=". $user["game_id"]. " AND user_id=".$user["id"], $db);
				else
					p2a("UPDATE scores SET true_word=".$data["true_word"].", false_word=".$data["false_word"].", true_letter=".$data["true_letter"].", false_letter=".$data["false_letter"].", score=".$data["score"]." WHERE id=". $user["game_id"], $db);
				echo json_encode(array("true_word"=>$data["true_word"], "false_word"=> $data["false_word"]), JSON_UNESCAPED_UNICODE);
			}
		}
		if ($act == "room-get-users-scores")
		{
			$data = p2a("SELECT r.room_id, r.user_id as id, r.score, u.username, u.level, r.true_word, r.false_word, r.true_letter, r.false_letter FROM rooms_users r LEFT JOIN users u ON u.id = r.user_id WHERE r.room_id=".$user["game_id"], $db);
			if ($data)
				echo json_encode($data, JSON_UNESCAPED_UNICODE);
			else
				echo "false";
		}
		if ($act == "room-start")
		{
			$data = p2a("SELECT * FROM rooms WHERE admin_id=".$user["id"]. " AND id=".$user["game_id"], $db);
			if ($data)
			{
				p2a("UPDATE rooms_users SET true_word=0, false_word=0, true_letter=0, false_letter=0, score=0 WHERE room_id=".$user["game_id"], $db);
				p2a("UPDATE rooms SET end_time='".date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s") . "+ 71 second"))."' WHERE id=". $user["game_id"], $db);
				echo "true";
			}
			else
				echo "false";
		}
		if ($act == "room-is-start")
		{
			$time = p2a("SELECT end_time FROM rooms WHERE id=".$user["game_id"], $db)[0]["end_time"];
			if ($time)
				echo strtotime($time) - strtotime(date("Y-m-d H:i:s"));
			else
				echo "false";
		}
		if ($act == "room-is-admin")
		{
			$admin = p2a("SELECT * FROM rooms WHERE id=".$user["game_id"], $db)[0];
			if ($admin["admin_id"] == $user["id"])
				echo "true";
			else
				echo json_encode(p2a("SELECT username, id FROM users WHERE id=".$admin["admin_id"], $db)[0], JSON_UNESCAPED_UNICODE);
		}
		if ($act == "current-game-words")
		{
			$time = p2a("SELECT end_time FROM rooms WHERE id=".$user["game_id"], $db)[0]["end_time"];
			if (strtotime($time) - strtotime(date("Y-m-d H:i:s")) >= 60)
				echo $user["game_words"];
			else
				echo "false";
		}
        if ($act == "score-result")
        {
			if ($user["game_type"] == 0)
			{
				$data = p2a("SELECT * FROM scores WHERE id=". $user["game_id"], $db)[0];
				if ($data["score"] <= 0)
					p2a("DELETE FROM scores WHERE id=" . $user["game_id"], $db);
				p2a("UPDATE users SET game_id=0, game_words=null WHERE id=".$user["id"], $db);
				$user["game_id"] = 0;
				echo json_encode(array("score"=>$data["score"], "true_word"=>$data["true_word"], "false_word"=> $data["false_word"], "true_letter"=>$data["true_letter"], "false_letter"=> $data["false_letter"]), JSON_UNESCAPED_UNICODE);
			}
			else
			{
				$data = p2a("SELECT JSON_UNQUOTE(u.game_words->'$[0]') as word, ru.score, ru.true_word, ru.false_word, ru.true_letter, ru.false_letter, r.end_time
				FROM users u
				JOIN rooms_users ru on u.id = ru.user_id
				JOIN rooms r on ru.room_id = r.id
				WHERE u.id = ".$user["id"]." AND r.id = ".$user["game_id"], $db)[0];
				if ($data)
				{
					p2a("INSERT INTO scores (user_id, true_word, false_word, true_letter, false_letter, score, game_type, end_time) VALUES(".$user["id"].", ".$data["true_word"].", ".$data["false_word"].", ".$data["true_letter"].", ".$data["false_letter"].", ".$data["score"].", 1, '".date("Y-m-d H:i:s")."')", $db);
					echo "true";
				}
				else
					echo "false";
			}
        }
        if ($act == "set-level")
        {
            $scoreResult = p2a("SELECT SUM(score) as total_score FROM scores WHERE user_id=".$user["id"], $db)[0]["total_score"];
            $required_xp = 100;
            $reduction_rate = 1.15;
            $level = 0;
            while ($scoreResult >= $required_xp) {
                $scoreResult -= $required_xp;
                $required_xp *= $reduction_rate;
                $level++;
            }
            p2a("UPDATE `users` SET `level`=".$level." WHERE id=" . $user["id"], $db);
            echo "true";
        }
        if ($act == "get-level")
        {
            $scoreResult = p2a("SELECT SUM(score) as total_score FROM scores WHERE user_id=".$user["id"], $db)[0]["total_score"];
            $required_xp = 100;
            $reduction_rate = 1.15;
            $level = 0;
            while ($scoreResult >= $required_xp) {
                $scoreResult -= $required_xp;
                $required_xp *= $reduction_rate;
                $level++;
            }
            echo json_encode(["level"=>$level, "percent"=>(int)(100 * ($scoreResult / $required_xp))],JSON_UNESCAPED_UNICODE);
        }
		if ($act == "get-current-time")
		{
			echo json_encode(date("Y-m-d H:i:s"), JSON_UNESCAPED_UNICODE);
		}

        if ($act == "get-user-details")
        {
            $username = $_POST["username"];
			$querys = "SELECT u.id, u.last_time, u.level,
			(SELECT COUNT(DISTINCT level) + 1 FROM users WHERE level > u.level) as level_rank,
			s.max_score,
			(SELECT COUNT(DISTINCT max_score) + 1 FROM (SELECT user_id, MAX(score) as max_score FROM scores GROUP BY user_id) s2 WHERE s2.max_score > s.max_score) as score_rank,
			(SELECT SUM(score) FROM scores WHERE user_id = u.id) as total_score
			FROM users u
			JOIN (SELECT user_id, MAX(score) as max_score FROM scores GROUP BY user_id) s ON u.id = s.user_id
			WHERE ";
            if (isUserName($username))
				$querys .= ("u.username ='" . $username. "'");
			else
				$querys .= ("u.id =" .$user["id"]);
			$data = p2a($querys, $db);
			$scoreResult = (int)$data[0]["total_score"];
            $required_xp = 100;
            $reduction_rate = 1.15;
            $level = 0;
            while ($scoreResult >= $required_xp) {
                $scoreResult -= $required_xp;
                $required_xp *= $reduction_rate;
                $level++;
            }
			$data[0]["level"] = $level;
			$data[0]["percent"] = (int)(100 * ($scoreResult / $required_xp));
            echo json_encode($data[0], JSON_UNESCAPED_UNICODE);
        }
        if ($act == "remove-temp")
        {
            $current_time = date("Y-m-d H:i:s");
			p2a("DELETE FROM rooms_match WHERE user_id=".$user["id"], $db);
            p2a("DELETE FROM rooms_invite WHERE user_id=".$user["id"], $db);
			if ($user["game_id"] > 0)
			{
				$data = p2a("SELECT id, admin_id FROM rooms WHERE id=". $user["game_id"], $db)[0];
				if ($data["id"] > 0)
				{
					$querys = "";
                    if ($data["admin_id"] == $user["id"])
						$querys = "DELETE FROM rooms WHERE id=". $data["id"]. ";DELETE FROM rooms_users WHERE room_id=". $user["game_id"].";DELETE FROM rooms_bans WHERE room_id=". $user["game_id"].";DELETE FROM rooms_chat WHERE room_id=". $user["game_id"]."; UPDATE users SET game_id=0,game_type=0,game_words=null WHERE game_id=".$user["game_id"];
                    else
						$querys = "DELETE FROM rooms_users WHERE user_id=". $user["id"].";DELETE FROM rooms_chat WHERE room_id=". $user["game_id"]. " AND user_id=".$user["id"].";";
				}
				$querys .= "UPDATE users SET game_id=0, game_type=0, game_words=null WHERE id=".$user["id"].";";
				$time = p2a("SELECT end_time, id FROM scores WHERE id=". $user["game_id"], $db)[0]["end_time"];
				if ($time)
				{
					$result = strtotime($time) - strtotime($current_time);
					if ($result > 0)
						$querys .= "DELETE FROM scores WHERE id=". $user["game_id"]. ";";
					$user["game_id"] = 0;
				}
				p2a($querys, $db);
				echo "true";
			}
			else
				echo "false";
        }
        if ($act == "get-friends")
        {
            $friends = p2a("
       		SELECT u.id, u.level, u.username, IFNULL(r.score, 0) as score,
            CASE
                WHEN u.last_time > DATE_SUB(NOW(), INTERVAL 1 MINUTE) THEN 1
                ELSE 0
            END AS is_online
			FROM users u
			INNER JOIN friends f
			ON (f.user_id = ".$user["id"]." AND f.friend_id = u.id) OR (f.user_id = u.id AND f.friend_id = ".$user["id"].")
			LEFT JOIN (
			SELECT user_id,  IFNULL(MAX(score),0) AS score
			FROM scores
			GROUP BY user_id
			) r ON u.id = r.user_id
			WHERE f.approval = 1
			ORDER BY is_online DESC, score DESC", $db);
            echo json_encode($friends, JSON_UNESCAPED_UNICODE);
        }
        
        if ($act == "get-friends-onlines")
        {
            $friends = p2a("SELECT u.id, u.level, u.username, IFNULL(r.score, 0) as score,
            CASE
                WHEN u.last_time > DATE_SUB(NOW(), INTERVAL 1 MINUTE) THEN 1
                ELSE 0
            END AS is_online
            FROM users u
            INNER JOIN friends f
            ON (f.user_id = ".$user["id"]." AND f.friend_id = u.id) OR (f.user_id = u.id AND f.friend_id = ".$user["id"].")
            LEFT JOIN (
            SELECT user_id,  IFNULL(MAX(score),0) AS score
            FROM scores
            GROUP BY user_id
            ) r ON u.id = r.user_id
            WHERE f.approval = 1
            AND NOT EXISTS (SELECT 1 FROM rooms_invite WHERE invite_id = u.id AND room_id = ".$user["game_id"].")
            AND NOT EXISTS (SELECT 1 FROM rooms_users WHERE user_id = u.id AND room_id = ".$user["game_id"].")
            AND (CASE WHEN u.last_time > DATE_SUB(NOW(), INTERVAL 1 MINUTE) THEN 1 ELSE 0 END) = 1
            ORDER BY score DESC", $db);
            echo json_encode($friends, JSON_UNESCAPED_UNICODE);
        }
        
        if ($act == "get-friends-approval")
        {
            $friends = p2a("SELECT u.id, u.username
            FROM users u
            INNER JOIN friends f
            ON (f.user_id = u.id AND f.friend_id = ".$user["id"].")
            LEFT JOIN (
                SELECT user_id,  IFNULL(MAX(score),0) AS score
                FROM scores
                GROUP BY user_id
            ) r
            ON u.id = r.user_id
            WHERE f.approval = 0
            ORDER BY r.score DESC", $db);
            echo json_encode($friends, JSON_UNESCAPED_UNICODE);
        }
        if ($act == "get-users")
        {
            $users = p2a("SELECT u.id, u.level, u.username, IFNULL(r.score, 0) as maximum_score, u.approval as mail_approval, 
        CASE WHEN u.id = ". $user["id"]. " THEN -1 ELSE 
            IF((f.user_id =". $user["id"]. " AND f.friend_id = u.id) OR (f.user_id = u.id AND f.friend_id = ". $user["id"]. "), 1, 0) 
        END AS is_friend, IFNULL(f.approval, 0) as approval, u.level,
        COALESCE((SELECT SUM(score) FROM scores WHERE user_id = u.id), 0) AS total_score
        FROM users u
        LEFT JOIN (
            SELECT user_id,  IFNULL(MAX(score),0) AS score
            FROM scores
            GROUP BY user_id
        ) r
        ON u.id = r.user_id
        LEFT JOIN friends f
        ON (f.user_id =". $user["id"]. " AND f.friend_id = u.id) OR (f.user_id = u.id AND f.friend_id =". $user["id"]. ") WHERE u.approval = 1
        ORDER BY r.score DESC", $db);
            echo json_encode($users, JSON_UNESCAPED_UNICODE);
        }
        if ($act == "add-friend")
        { 
            $friend_id = (int)$_POST["friend_id"];
            if ($friend_id > 0)
            {
                $data = p2a("SELECT * FROM friends WHERE `user_id`='".$user["id"]."' AND friend_id='".$friend_id."' OR `friend_id`='".$user["id"]."' AND user_id='".$friend_id."'", $db)[0];
                if (!$data && $friend_id != $user["id"])
                {
                    p2a("INSERT INTO friends (`user_id`, `friend_id`) VALUES ('". $user["id"] ."','". $friend_id ."')", $db);
                    echo "true";
                }
                else
                    echo "false";
            }
            else
                echo "false";
        }
        if ($act == "accept-friend")
        {
            $friend_id = (int)$_POST["friend_id"];
            if ($friend_id > 0)
            {
                $data = p2a("SELECT * FROM friends WHERE `friend_id`='".$user["id"]."' AND user_id='".$friend_id."'", $db)[0];
                if ($data && $friend_id != $user["id"])
                {
                    p2a("UPDATE friends SET approval=1 WHERE `friend_id`='".$user["id"]."' AND user_id='".$friend_id."'", $db);
                    echo "true";
                }
                else
                    echo "false";
            }
            else
                echo "false";
        }
        if ($act == "remove-friend")
        {
            $friend_id = (int)$_POST["friend_id"];
            if ($friend_id > 0)
            {
                p2a("DELETE FROM friends WHERE `user_id`='".$user["id"]."' AND friend_id='".$friend_id."' OR `friend_id`='".$user["id"]."' AND user_id='".$friend_id."'", $db);
                echo "true";
            }
            else
                echo "false";
        }
        if ($act == "logout")
        {
            $key = randomUserKey();
            p2a("UPDATE users SET user_key=" . $key . " WHERE id=".$user["id"], $db);
            $user["user_key"] = $key;
            setcookie("key", null, -1, "/", "fasttyping.com.tr");
            echo "true";
        }
        if ($act == "get-user-statistics")
        {
            $username = $_POST["username"];
            if (isUserName($username))
                $statistics = p2a("SELECT true_word, false_word, true_letter, false_letter, score, end_time FROM scores
                LEFT JOIN (SELECT username, id FROM users)
                u ON u.username = '". $username ."'
                WHERE user_id=u.id ORDER BY end_time ASC", $db);
            else
                $statistics = p2a("SELECT true_word, false_word, true_letter, false_letter, score, end_time FROM scores WHERE user_id=". $user["id"] ." ORDER BY end_time ASC", $db);
            echo json_encode($statistics, JSON_UNESCAPED_UNICODE);
        }
        if ($act == "set-last-time")
        {
            p2a("UPDATE users SET last_time='".date("Y-m-d H:i:s")."' WHERE id=" . $user["id"], $db);
            echo "true";
        }
        if ($act == "set-theme")
        {
            $id = (int)$_POST["id"];
            if ($id >= 1 && $id <= 5)
            {
                $theme = "hufflepuff";
                if ($id == 1)
                    $theme = "gryffindor";
                if ($id == 2)
                    $theme = "ravenclaw";
                if ($id == 3)
                    $theme = "slytherin";
                if ($id == 5)
                    $theme = "dementor";
                p2a("UPDATE users SET theme='".$theme."' WHERE id=" . $user["id"], $db);
                $user["theme"] = $theme;
                echo "true";
            }
            else
                echo "false";
        }
		if ($act == "get-user")
			echo json_encode(["username"=>$user["username"],"approval"=> $user["approval"],"mail"=> $user["mail"]], JSON_UNESCAPED_UNICODE);
    }
	else if ($user["approval"] != 1 && $user)
	{
		if ($act == "get-user")
			echo json_encode([$user["username"], $user["approval"], $user["mail"]], JSON_UNESCAPED_UNICODE);
		if ($act == "verify-account")
		{
			$code = (int)$_POST["code"];
			if ($code > 0 && $code == $user["approval"])
			{
				$key = randomUserKey();
				p2a("UPDATE users SET user_key=" . $key . ", approval=1 WHERE id=".$user["id"], $db);
				$user["user_key"] = $key;
				$user["approval"] = 1;
				setcookie("key", $key, time()+365*24*60*60, "/", "fasttyping.com.tr");
				echo "true";
			}
			else
				echo "false";
		}
		if ($act == "resend-email")
		{
			if (strtotime(date("Y-m-d H:i:s")) - strtotime($user["last_send_mail"]. "+ 5 minute") > 0)
			{
				$user["approval"] = rand(100000, 1000000);
				$user["user_key"] = randomUserKey();
				$eposta = $user['mail'];
				$ad_soyad = $user['username'];
				$mail = new PHPMailer();
				$mail->IsSMTP();
				$mail->SMTPAuth = true;
				$mail->Host = "{mail host}";
				$mail->Port = 465;
				$mail->SMTPSecure = "ssl";
				$mail->Username = "{mail}";
				$mail->Password = "{password}";
				$mail->SetFrom($mail->Username, "Fast Typing - Hesap Doğrulama");
				$mail->AddAddress($eposta, $ad_soyad);
				$mail->CharSet = "UTF-8";
				$mail->Subject = "Doğrulama Kodu :".$user["approval"];
				$content = str_replace('{code}', $user["approval"], file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/inc/mail/verify.html'));
				$content = str_replace('{theme}', 'hufflepuff', $content);
				$mail->MsgHTML($content);
				if ($mail->Send()) 
				{
                    p2a("UPDATE users SET user_key=" . $user["user_key"] . ", approval=".$user["approval"]." WHERE id=".$user["id"], $db);
					setcookie("key", $user["user_key"], time()+365*24*60*60, "/", "fasttyping.com.tr");
					echo 'true';
				}
				else
					echo 'false';
			}
			else
				echo strtotime($user["last_send_mail"]. "+ 5 minute")-strtotime(date("Y-m-d H:i:s"));
		}
		if ($act == "delete-account")
		{
			p2a("DELETE FROM users WHERE id=" . $user["id"], $db);
			echo "true";
		}
	}
    else if ($act != "register" && $act != "login" && $act != "words" && $act != "top-list")
        echo "false";
    if ($act == "register")
    {
        if (isUserName($_POST["username"]) && isPassword($_POST["password"]) &&
            isPassword($_POST["passwordAgain"]) && isEmail($_POST["mail"]) &&
            $_POST["passwordAgain"] == $_POST["password"])
            unset($_POST['passwordAgain']);
        $data = p2a("SELECT * FROM users WHERE mail='" . $_POST["mail"] . "' OR username='" . $_POST["username"] . "'", $db)[0];
        if (!$data) 
		{
            $_POST["approval"] = rand(100000, 1000000);
            $_POST["user_key"] = randomUserKey();
            p2a(a2s_i("users", $_POST), $db);
            $eposta = $_POST['mail'];
            $ad_soyad = $_POST['username'];
            $mail = new PHPMailer();
            $mail->IsSMTP();
            $mail->SMTPAuth = true;
            $mail->Host = "{mail host}";
            $mail->Port = 465;
            $mail->SMTPSecure = "ssl";
            $mail->Username = "{mail}";
            $mail->Password = "{password}";
            $mail->SetFrom($mail->Username, "Fast Typing - Hesap Doğrulama");
            $mail->AddAddress($eposta, $ad_soyad);
            $mail->CharSet = "UTF-8";
            $mail->Subject = "Doğrulama Kodu :".$_POST["approval"];
            $content = str_replace('{code}', $_POST["approval"], file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/inc/mail/verify.html'));
			$content = str_replace('{theme}', 'hufflepuff', $content);
            $mail->MsgHTML($content);
            if ($mail->Send()) 
            {
                setcookie("key", $_POST["user_key"], time()+365*24*60*60, "/", "fasttyping.com.tr");
                echo 'true';
            }
            else
                echo 'false';
        } 
		else if (!isUserName($_POST["username"]))
			echo "Kullanıcı Adı 5 karakterden büyük, 100 karakterden küçük ve küçük harflerden oluşmalı.";
		else if (!isPassword($_POST["password"]))
			echo "Şifre 5 karakterden büyük 100 karakterden küçük olmalı.";
		else if (!isEmail($_POST["mail"]))
			echo "Eposta adresi veya kullanıcı adı daha önceden alınmış";
		else if ($_POST["password"] != $_POST["passwordAgain"])
			echo "Şifreler birbiriyle uyuşmuyor.";
		else
			echo "Eposta adresi veya kullanıcı adı daha önceden alınmış";
    }
    if ($act == "login")
    {
        if (isUserName($_POST["username"]) && isPassword($_POST["password"]))
        {
            $user_id = p2a("SELECT id FROM users WHERE username='".$_POST["username"]."' AND password ='".$_POST["password"]."'", $db)[0]["id"];
            if ($user_id > 0)
            {
                $key = randomUserKey();
                p2a("UPDATE users SET user_key=" . $key . " WHERE id=".$user_id, $db);
                setcookie("key", $key, time()+365*24*60*60, "/", "fasttyping.com.tr");
                echo "true";
            }
            else
                echo "false";
        }
        else
            echo "false";
    }
    if ($act == "words")
    {
        $limit = "";
        $count = (int)$_POST["count"];
        if ($count > 0)
        {
            $limit = "LIMIT 0, ". $count;
            if ($_POST["difficulity"] == "Kolay")
                echo json_encode(p2a("SELECT * FROM words ORDER BY LENGTH(word), RAND() ". $limit, $db), JSON_UNESCAPED_UNICODE);
            else if ($_POST["difficulity"] == "Zor")
                echo json_encode(p2a("SELECT * FROM words ORDER BY LENGTH(word) DESC, RAND() ". $limit, $db), JSON_UNESCAPED_UNICODE);
            else
                echo json_encode(p2a("SELECT * FROM words ORDER BY RAND() ". $limit, $db), JSON_UNESCAPED_UNICODE);
        }
        else
            echo "false";
    }
    if ($act == "top-list")
    {
        $users = p2a("SELECT u.level, u.username, r.score,
        CASE
        WHEN u.last_time > DATE_SUB(NOW(), INTERVAL 1 MINUTE) THEN 1
        ELSE 0
        END AS is_online
        FROM users u
        JOIN (
        SELECT user_id,  IFNULL(MAX(score),0) AS score
        FROM scores
        WHERE DATE_ADD(end_time, INTERVAL 1 DAY) > CURDATE()
        GROUP BY user_id
        ) r ON u.id = r.user_id
        ORDER BY r.score DESC", $db);
        echo json_encode($users, JSON_UNESCAPED_UNICODE);
    }
}
else
    echo "false";
?>