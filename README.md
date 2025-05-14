# 🎥 video-dl

**`video-dl`** — это простая CLI-утилита на PHP для скачивания и перекодировки видео с помощью `yt-dlp` и `ffmpeg`.

* 📥 Скачивает видео по URL
* 🎞️ Проверяет формат и кодеки
* 🔁 При необходимости перекодирует в `.mp4` с H.264
* 🧰 Работает как библиотека и как CLI-инструмент

---

## 📦 Установка

### 1. Установка через Composer (из GitHub)

Если ты хочешь подключить `video-dl` в другой проект:

```json
{
  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/vlavlamat/video-dl.git"
    }
  ],
  "require": {
    "vmatkovskii/video-dl": "dev-main"
  }
}
```

Затем:

```bash
composer update
```

CLI-обёртка будет доступна как:

```bash
./vendor/bin/video-dl
```

---

## 🔧 Зависимости

Убедись, что у тебя установлены:

* [`yt-dlp`](https://github.com/yt-dlp/yt-dlp)
* [`ffmpeg`](https://ffmpeg.org/)
* `php >= 8.0`

---

## 🚀 Использование

Просто запусти:

```bash
./bin/video-dl
```

Или если установлен как зависимость:

```bash
./vendor/bin/video-dl
```

Затем следуй инструкции в терминале:

```
Введите URL для скачивания:
```

---

## 📚 Пример кода как библиотеки

Если хочешь использовать в своём коде:

```php
use VMatkovskii\VideoDl\VideoDownloader;

$downloader = new VideoDownloader();
$downloader->run($argv);
```

---

## 📁 Структура проекта

```
video-dl/
├── bin/
│   └── video-dl          # CLI обёртка
├── src/
│   └── VideoDownloader.php
├── composer.json
├── README.md
├── LICENSE
└── .gitignore
```

---

## 📄 Лицензия

MIT © [Vladimir Matkovskii](mailto:vlavlamat@icloud.com)

---

🔗 GitHub: [https://github.com/vlavlamat/video-dl](https://github.com/vlavlamat/video-dl)
