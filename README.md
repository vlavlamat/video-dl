# 🎥 video-dl

**`video-dl`** — это PHP-библиотека для скачивания и перекодировки видео с помощью `yt-dlp` и `ffmpeg`.

* 📥 Скачивает видео по URL
* 🎞️ Проверяет формат и кодеки
* 🔁 При необходимости перекодирует в `.mp4` с H.264
* 📚 Используется как библиотека в других проектах

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

---

## 🔧 Зависимости

Убедись, что у тебя установлены:

* [`yt-dlp`](https://github.com/yt-dlp/yt-dlp)
* [`ffmpeg`](https://ffmpeg.org/)
* `php >= 8.0`
* `ext-fileinfo` (PHP-расширение для определения MIME-типов)

---

## 📚 Использование в коде

Пример использования библиотеки в проекте:

```php
use VMatkovskii\VideoDl\VideoDownloader;

$downloader = new VideoDownloader();
$downloader->run([$argv[0], $url]);
```

Аргументом передаётся массив, где первый элемент — имя скрипта (для совместимости с CLI-сценариями), второй элемент — URL для скачивания. Если URL не указан, будет запрошен ручной ввод.

---

## 📁 Структура пакета

```
video-dl/
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
