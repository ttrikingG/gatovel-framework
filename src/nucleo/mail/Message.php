<?php

namespace nucleo\mail;

class Message
{
    private string $to = '';

    private string $subject = '';

    private string $body = '';

    public function to(
        string $email
    ): self {
        $this->to = $email;

        return $this;
    }

    public function subject(
        string $subject
    ): self {
        $this->subject = $subject;

        return $this;
    }

    public function body(
        string $body
    ): self {
        $this->body = $body;

        return $this;
    }

    public function getTo(): string
    {
        return $this->to;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function getBody(): string
    {
        return $this->body;
    }
}