<?php

namespace App\Utils\Slack;

class Attachment
{
    private $color;
    private $title;
    private $pretext;
    private $text;
    private $titleLink;
    private $fields;

    /**
     * @return mixed
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param mixed $color
     */
    public function setColor($color): void
    {
        $this->color = $color;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getPretext()
    {
        return $this->pretext;
    }

    /**
     * @param mixed $pretext
     */
    public function setPretext($pretext): void
    {
        $this->pretext = $pretext;
    }

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
     * @return mixed
     */
    public function getTitleLink()
    {
        return $this->titleLink;
    }

    /**
     * @param mixed $titleLink
     */
    public function setTitleLink($titleLink): void
    {
        $this->titleLink = $titleLink;
    }

    /**
     * @return AttachmentField[]
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @param mixed $fields
     */
    public function setFields($fields): void
    {
        $this->fields = $fields;
    }

    public function newField()
    {
        return new AttachmentField();
    }

    public function addField(AttachmentField $field)
    {
        $this->fields[] = $field;
    }
}
