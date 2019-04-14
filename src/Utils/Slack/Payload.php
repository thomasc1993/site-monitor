<?php

namespace App\Utils\Slack;

class Payload
{
    private $text;
    private $attachments;

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text): void
    {
        $this->text = $text;
    }

    /**
     * @return Attachment[]
     */
    public function getAttachments(): array
    {
        return $this->attachments;
    }

    /**
     * @param array $attachments
     */
    public function setAttachments(array $attachments): void
    {
        $this->attachments = $attachments;
    }

    public function newAttachment()
    {
        return new Attachment();
    }

    public function addAttachment(Attachment $attachment)
    {
        $this->attachments[] = $attachment;
    }
}
