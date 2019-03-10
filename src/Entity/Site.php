<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SiteRepository")
 */
class Site
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cms;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $admin_url;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $response_time_latest;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCms(): ?string
    {
        if ($this->cms === 'none') {
            return null;
        }

        return $this->cms;
    }

    public function setCms(string $cms): self
    {
        $this->cms = $cms;

        return $this;
    }

    public function getAdminUrl(): ?string
    {
        return $this->admin_url;
    }

    public function setAdminUrl(string $admin_url): self
    {
        $this->admin_url = $admin_url;

        return $this;
    }

    public function getResponseTimeLatest(): ?string
    {
        return $this->response_time_latest;
    }

    public function setResponseTimeLatest(?string $response_time_latest): self
    {
        $this->response_time_latest = $response_time_latest;

        return $this;
    }
}
