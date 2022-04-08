<?php

namespace RSSReader\Entity;

class Resource
{

    public function __construct(
        protected int $id,
        protected string $name,
        protected string $url,
        protected ?string $createdAt = null
    )
    {
    }

    public static function toObject(array $data): Resource
    {
        return new Resource(
            $data['id'],
            $data['name'],
            $data['url'],
            $data['created_at']
        );
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return string|null
     */
    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
}