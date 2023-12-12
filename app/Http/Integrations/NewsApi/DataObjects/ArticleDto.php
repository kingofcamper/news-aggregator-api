<?php

namespace App\Http\Integrations\NewsApi\DataObjects;

use Saloon\Traits\Responses\HasResponse;
use Saloon\Contracts\DataObjects\WithResponse;

class ArticleDto implements WithResponse
{
    use HasResponse;

    public function __construct(
        public string $author,
        public string $title,
        public string $description,
        public string $url,
        public string $urlToImage,
        public string $publishedAt,
        public string $content,
    ) {}

    public static function fromSaloon(array $article): static
    {
        if(data_get($article, 'publishedAt')){
            $dateString = data_get($article, 'publishedAt');
            $dateTime = new \DateTime($dateString);
            $formattedDate = $dateTime->format('Y-m-d H:i:s');
        }
        return new static(
            author: strval(data_get($article, 'author')) ?? '',
            title: strval(data_get($article, 'title')) ?? '',
            description: strval(data_get($article, 'description')) ?? '',
            url: strval(data_get($article, 'url')) ?? '',
            urlToImage: strval(data_get($article, 'urlToImage')) ?? '',
            publishedAt: $formattedDate ?? '',
            content: strval(data_get($article, 'content')) ?? '',
        );
    }

    public function toArray($categoryId, $sourceId): array
    {
        return [
            'category_id' => $categoryId,
            'source_id' => $sourceId,
            'author' => $this->author,
            'title' => $this->title,
            'description' => $this->description,
            'url' => $this->url,
            'urlToImage' => $this->urlToImage,
            'publishedAt' => $this->publishedAt,
            'content' => $this->content,
        ];
    }
}
