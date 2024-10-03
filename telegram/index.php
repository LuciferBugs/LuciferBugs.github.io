<?php
require_once("./Telebot.php");
require_once("../db.php");

// Ambil data dari tabel config, tapi sepertinya hanya butuh 1 baris (bukan fetchAll)
$stmt = $pdo->prepare("SELECT * FROM config ORDER BY id ASC LIMIT :limit OFFSET :offset ");
$limit = 1;
$offset = 0;
$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$task = $stmt->fetch(PDO::FETCH_ASSOC); // Hanya ambil satu baris, karena sepertinya config hanya satu data

$bot = new Telebot($task['apiTelegram']);

// Command bot dimulai di sini
$bot->command('start', function ($ctx) use ($pdo, $task) {
    $caption = "<b>". $task['caption1'] ."</b>\n \n" .
        "👇 ". $task['caption2'] ." 👇";

    $replyMarkup = [
        'inline_keyboard' => [
            [
                ['text' => $task['titleLaunch'], 'url' => $task['linkTMA'].'app'],
            ],
            [
                ['text' => $task['titleChannel'], 'url' => $task['linkChannel']],
                ['text' => $task['titleGroup'], 'url' => $task['linkGroup']],
            ]
        ]
    ];

    try {
        $username = $ctx->from->username ?? null;
        $firstname = $ctx->from->first_name ?? " ";
        $lastname = $ctx->from->last_name ?? " ";

        if (is_null($username)) {
            $username = str_replace(' ', '', $firstname . $lastname);
        }

        $telegram_id = $ctx->from->id;
        $referral_id = substr($ctx->text, 7); // Ambil ID referral dari parameter teks

        // Cek apakah user sudah terdaftar di database
        $stmt = $pdo->prepare("SELECT telegram_id FROM user WHERE telegram_id = :telegram_id");
        $stmt->bindParam(':telegram_id', $telegram_id, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Jika user sudah terdaftar, kirim pesan
            $ctx->sendPhoto($task['photoUrl'], $caption, [
                "parse_mode" => "HTML",
                "reply_markup" => $replyMarkup
            ]);
        } else {
            // User baru, cek apakah ada referral ID
            if (!empty($referral_id)) {
                // Update hours referral karena user baru mendaftar melalui referral
                $stmt = $pdo->prepare("UPDATE user SET hours = hours + :hours WHERE telegram_id = :referral_id");
                $stmt->execute([':hours' => 1, ':referral_id' => $referral_id]);

                // Tambahkan ke tabel referral
                $stmt = $pdo->prepare("INSERT INTO referral (telegram_id, referral_id) VALUES (:telegram_id, :referral_id)");
                $stmt->execute([
                    ':telegram_id' => $telegram_id,
                    ':referral_id' => $referral_id
                ]);

                $stmt = $pdo->prepare("INSERT INTO history (telegram_id, type, amount, status) VALUES (:referral_id, 'Invite', 1, 'Completed')");
                $stmt->execute([':referral_id' => $referral_id]);

                // Cek total referral untuk referral ID ini
                $stmt = $pdo->prepare("SELECT COUNT(*) as total_referrals FROM referral WHERE referral_id = :referral_id");
                $stmt->bindParam(':referral_id', $referral_id, PDO::PARAM_INT);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $total_referrals = (int) $result['total_referrals'];

                // Jika baru 1 referral, tambahkan task referral pertama
                if ($total_referrals === 1) {
                    $stmt = $pdo->prepare("INSERT INTO user_task (telegram_id, task_id, status) VALUES (:referral_id, 3, 'complete')");
                    $stmt->execute([':referral_id' => $referral_id]);

                    // Tambahkan ke history
                    $stmt = $pdo->prepare("INSERT INTO history (telegram_id, type, amount, status) VALUES (:referral_id, 'Task', 1, 'Completed')");
                    $stmt->execute([':referral_id' => $referral_id]);
                }

                // Jika mencapai 5 referral, tambahkan task kedua
                if ($total_referrals === 5) {
                    $stmt = $pdo->prepare("INSERT INTO user_task (telegram_id, task_id, status) VALUES (:referral_id, 4, 'complete')");
                    $stmt->execute([':referral_id' => $referral_id]);

                    // Tambahkan ke history
                    $stmt = $pdo->prepare("INSERT INTO history (telegram_id, type, amount, status) VALUES (:referral_id, 'Task', 2, 'Completed')");
                    $stmt->execute([':referral_id' => $referral_id]);
                }
            }

            // Tambahkan user baru ke tabel user
            $stmt = $pdo->prepare("INSERT INTO user (telegram_id, username, firstname, lastname) VALUES (:telegram_id, :username, :firstname, :lastname)");
            $stmt->execute([
                ':telegram_id' => $telegram_id,
                ':username' => $username,
                ':firstname' => $firstname,
                ':lastname' => $lastname
            ]);

            // Kirim pesan selamat datang
            $ctx->sendPhoto($task['photoUrl'], $caption, [
                "parse_mode" => "HTML",
                "reply_markup" => $replyMarkup
            ]);
        }

    } catch (PDOException $e) {
        $ctx->replyWithText("An error occurred: " . $e->getMessage());
    }
});

$bot->run();
