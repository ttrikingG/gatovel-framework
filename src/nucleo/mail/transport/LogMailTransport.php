<?php

namespace nucleo\mail\transport;

use nucleo\mail\Message;
use nucleo\mail\contracts\MailTransport;

class LogMailTransport implements MailTransport
{
    private string $path;

    public function __construct(
        string $path
    ) {
        $this->path = $path;
    }

    public function send(
        Message $message
    ): bool {
        $directory = dirname(
            $this->path
        );

        if (!is_dir($directory)) {
            mkdir(
                $directory,
                0775,
                true
            );
        }

        $content =
            "TO: "
            . $message->getTo()
            . PHP_EOL
            . "SUBJECT: "
            . $message->getSubject()
            . PHP_EOL
            . PHP_EOL
            . $message->getBody()
            . PHP_EOL
            . str_repeat(
                '-',
                60
            )
            . PHP_EOL;

        return file_put_contents(
            $this->path,
            $content,
            FILE_APPEND
        ) !== false;
    }
}