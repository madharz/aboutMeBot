<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);


define('TG_TOKEN', '7669211500:AAFLGm3Q6aqYDfS63nVR-Mk0zAvGf-a_b3w');
define('TG_API', 'https://api.telegram.org/bot'. TG_TOKEN .'/');
define('TG_USER_ID', 304670036);

$offset = 0;
$maxIterations = 1000;
$iterations = 0;

while ($iterations < $maxIterations) {
    $updates = json_decode(file_get_contents(TG_API . 'getUpdates?offset=' . $offset), true);

    if (isset($updates['result'])) {
        foreach ($updates['result'] as $update) {
            $offset = $update['update_id'] + 1;

            if (isset($update['message'])) {
                $chatId = $update['message']['chat']['id'];
                $text = $update['message']['text'];

                switch ($text) {
                    case '/start':
                        sendMenu($chatId);
                        break;
                    case 'About':
                        sendMessage($chatId, 'Я PHP-розробник із досвідом роботи у веб-розробці.');
                        break;
                    case 'Help':
                        $message = "<b>Help:</b>\n" .
                            "/about - Інформація про мене\n" .
                            '/links - Посилання на мої соцмережі';
                        sendMessage($chatId, $message, 'HTML');
                        break;
                    case 'Links':
                        sendMessage($chatId, "LinkedIn: https://www.linkedin.com/in/danylo-prymostka-299891235/\n Facebook: https://www.facebook.com/daniel.primostka/?locale=ua_UA");
                        break;
                }
            }
            if (isset($update['callback_query'])) {
                $callbackData = $update['callback_query']['data'];
                $chatId = $update['callback_query']['message']['chat']['id'];

                switch ($callbackData) {
                    case 'about':
                        sendMessage($chatId, 'Я PHP-розробник із досвідом роботи у веб-розробці.');
                        break;
                    case 'help':
                        $keyboard = [
                            'inline_keyboard' => [
                                [
                                    ['text' => '/about', 'callback_data' => 'about'],
                                    ['text' => '/links', 'callback_data' => 'links'],
                                ],
                            ],
                        ];
                        sendMessage($chatId, "<b>Help:</b>\n", 'HTML', $keyboard);
                        break;
                    case 'links':
                        sendMessage($chatId, "🔗 LinkedIn: https://www.linkedin.com/in/danylo-prymostka-299891235/\n🔗 Facebook: https://www.facebook.com/daniel.primostka/?locale=ua_UA");
                        break;
                }
            }
        }
    }
    if (empty($updates['result'])) {
        break;
    }
    sleep(1);
    $iterations++;
}

function sendMenu($chatId)
{
    $keyboard = [
        'inline_keyboard' => [
            [
                ['text' => '/about', 'callback_data' => 'about'],
                ['text' => '/help', 'callback_data' => 'help'],
            ],
            [
                ['text' => '/links', 'callback_data' => 'links'],
            ],
        ],
    ];

    sendMessage($chatId, 'Виберіть опцію:', 'HTML', $keyboard);
}

function sendMessage($chatId, $message, $parseMode = 'HTML', $keyboard = null)
{
    $data = [
        'chat_id'  => $chatId,
        'text' => $message,
        'parse_mode' => $parseMode,
    ];

    if ($keyboard !== null) {
        $data['reply_markup'] = json_encode($keyboard);
    }

    $response = file_get_contents(TG_API . 'sendMessage?' . http_build_query($data));

    $result = json_decode($response, true);
    if (!is_array($result)) {
        die('Exception: Telegram API повернуло не JSON');
    }
    return $result;
}
