<?php

namespace VMatkovskii\VideoDl;

class VideoDownloader
{
    public function run(array $argv): void
    {
        $downloadsDir = $this->getWritableDownloadsDir();

        $url = $this->prompt("Введите URL для скачивания: ");
        if (empty($url)) {
            $this->log("URL не может быть пустым. Выход.", '31');
            exit(1);
        }

        $escapedUrl = escapeshellarg($url);
        $outputTemplate = escapeshellarg($downloadsDir . '/%(title).200B.%(ext)s');
        $downloadedFile = $this->runWithOutput("yt-dlp --print after_move:filepath -o $outputTemplate $escapedUrl");

        if (empty($downloadedFile) || !file_exists($downloadedFile)) {
            $this->log("Ошибка: yt-dlp не вернул путь к файлу или файл не существует.", '31');
            exit(1);
        }

        $this->log("Скачанный файл: " . basename($downloadedFile));

        $mime = $this->detectMimeType($downloadedFile);
        $ext = strtolower(pathinfo($downloadedFile, PATHINFO_EXTENSION));
        [$vCodec, $aCodec] = $this->getCodecs($downloadedFile);
        $this->log("MIME: $mime | Video codec: $vCodec | Audio codec: $aCodec", '36');

        if (!$this->needsReencoding($mime, $ext, $vCodec)) {
            $this->log("Файл уже в нужном формате ($ext) и с нужным кодеком ($vCodec). Перекодировка не требуется.");
            exit(0);
        }

        $newFile = $this->getSafeNewFilename($downloadedFile, 'mp4');
        $this->log("Перекодируем видео в mp4 (H.264)...", '34');
        $this->runWithOutput("ffmpeg -i " . escapeshellarg($downloadedFile) . " -c:v libx264 -c:a aac -strict experimental " . escapeshellarg($newFile));

        if (file_exists($newFile)) {
            unlink($downloadedFile);
            $this->log("Удалён оригинальный файл: " . basename($downloadedFile), '33');
            $this->log("🎬 Перекодировано: " . basename($newFile));
        } else {
            $this->log("Ошибка при перекодировании. Новый файл не создан.", '31');
            exit(1);
        }
    }

    private function prompt(string $text): string
    {
        echo $this->color($text, '36');
        return trim(fgets(STDIN));
    }

    private function runWithOutput(string $command): string
    {
        echo $this->color("\n> $command\n", '33');
        $output = shell_exec($command);
        return is_string($output) ? trim($output) : '';
    }

    private function log(string $message, string $color = '32'): void
    {
        echo $this->color("[" . date('Y-m-d H:i:s') . "] $message\n", $color);
    }

    private function color(string $text, string $colorCode): string
    {
        return stream_isatty(STDOUT) ? "\033[1;{$colorCode}m$text\033[0m" : $text;
    }

    private function detectMimeType(string $file): string
    {
        return mime_content_type($file) ?: '';
    }

    private function getCodecs(string $file): array
    {
        $videoCodec = trim(shell_exec("ffprobe -v error -select_streams v:0 -show_entries stream=codec_name -of default=nokey=1:noprint_wrappers=1 " . escapeshellarg($file)) ?? '');
        $audioCodec = trim(shell_exec("ffprobe -v error -select_streams a:0 -show_entries stream=codec_name -of default=nokey=1:noprint_wrappers=1 " . escapeshellarg($file)) ?? '');
        return [$videoCodec, $audioCodec];
    }

    private function getSafeNewFilename(string $original, string $ext): string
    {
        $path = pathinfo($original);
        return $path['dirname'] . '/' . $path['filename'] . '_converted.' . $ext;
    }

    private function getWritableDownloadsDir(): string
    {
        $targetDir = getenv("HOME") . "/Downloads/Videos";
        if (!is_dir($targetDir) && !mkdir($targetDir, 0777, true) && !is_dir($targetDir)) {
            $this->log("Ошибка: не удалось создать директорию: $targetDir", '31');
            exit(1);
        }
        return $targetDir;
    }

    private function needsReencoding(string $mime, string $ext, string $vCodec): bool
    {
        if (!str_starts_with($mime, 'video')) {
            $this->log("Неизвестный тип файла ($mime). Перекодировка невозможна.", '31');
            exit(1);
        }

        return !($ext === 'mp4' && $vCodec === 'h264');
    }
}
