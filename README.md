Перше, що треба зробити це стартанути локально сервер php -S localhost:8080 -t.
Потім ми запускаєм ngrok http 8080 для того, щоб сервер був доступний для Telegram і тут ми отримуємо наш ngrok_url
Потім привязуємо вебхук curl -X POST "https://api.telegram.org/botтвій_токен/setWebhook?url=твій_ngrok_url"
І після цього можна запускати бота з допомогою /start
