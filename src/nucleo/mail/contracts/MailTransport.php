<?php

namespace nucleo\mail\contracts;

use nucleo\mail\Message;

interface MailTransport
{
    public function send(
        Message $message
    ): bool;
}
