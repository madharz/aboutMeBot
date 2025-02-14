<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);


define('TG_TOKEN', '7669211500:AAFLGm3Q6aqYDfS63nVR-Mk0zAvGf-a_b3w');
define('TG_API', 'https://api.telegram.org/bot'. TG_TOKEN .'/');
define("TG_USER_ID", 304670036);

$input = file_get_contents('php://input');
$update = json_decode($input, true);

if (isset($update['message'])) {
    $chatId = $update['message']['chat']['id'];
    $text = $update['message']['text'];

    switch ($text) {
        case "/start":
            sendMenu($chatId);
            break;
        case "/about":
            sendMessage($chatId, "Я PHP-розробник із досвідом роботи у веб-розробці.");
            break;
        case "/help":
            $message = "<b>Допомога:</b>\n" .
                "/about - Інформація про мене\n" .
                "/links - Посилання на мої соцмережі";
            sendMessage($chatId, $message, "HTML");
            break;
        case "/links":
            sendMessage($chatId, "LinkedIn: https://www.linkedin.com/in/danylo-prymostka-299891235/\n Facebook: https://www.facebook.com/daniel.primostka/?locale=ua_UA");
            break;
    }
}

if (isset($update['callback_query'])) {
    $callbackData = $update['callback_query']['data'];
    $chatId = $update['callback_query']['message']['chat']['id'];

    switch ($callbackData) {
        case "about":
            sendMessage($chatId, "Я PHP-розробник із досвідом роботи у веб-розробці AУЕ.");
            break;
        case "help":
            $message = "<b>Допомога:</b>\n" .
                "<a href='tg://resolve?domain=myAboutMeBot&start=about'>/about</a> - Інформація про мене\n" .
                "<a href='tg://resolve?domain=myAboutMeBot&start=links'>/links</a> - Посилання на мої соцмережі";
            sendMessage($chatId, $message, "HTML");
            break;
        case "links":
            sendMessage($chatId, "LinkedIn: https://www.linkedin.com/in/danylo-prymostka-299891235/\n Facebook: https://www.facebook.com/daniel.primostka/?locale=ua_UA");
            break;
    }
}

function sendMenu($chatId) {
    $keyboard = [
        'keyboard' => [
            [['text' => '/about'], ['text' => '/help']],
            [['text' => '/links']]
        ],
        'resize_keyboard' => true,
        'one_time_keyboard' => false
    ];

    $data = [
        'chat_id' => $chatId,
        'text' => "Виберіть опцію:",
        'reply_markup' => json_encode($keyboard)
    ];

    file_get_contents(TG_API . "sendMessage?" . http_build_query($data));
}

function sendMessage($chatId, $message, $parseMode = "HTML") {
    $data = [
        'chat_id' => $chatId,
        'text' => $message,
        'parse_mode' => $parseMode
    ];

    file_get_contents(TG_API . "sendMessage?" . http_build_query($data));
}
