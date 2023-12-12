<?php

namespace App\Http\Integrations\TheGuardian\DataObjects;

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
        if(data_get($article, 'webPublicationDate')){
            $dateString = data_get($article, 'webPublicationDate');
            $dateTime = new \DateTime($dateString);
            $publishedAt = $dateTime->format('Y-m-d H:i:s');
        }

        return new static(
            author: "Bilel Belghith",
            title: strval(data_get($article, 'webTitle')) ?? '',
            description: 'No description found in The guardian APIs !',
            url: strval(data_get($article, 'webUrl')) ?? '',
            urlToImage: "https://pioneer-technical.com/wp-content/uploads/2016/12/news-placeholder.png",
            publishedAt: $publishedAt ?? '',
            content: 'No content found in The guardian APIs :/ | Lorem ipsum dolor set',
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
