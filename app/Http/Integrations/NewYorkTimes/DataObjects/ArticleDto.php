<?php

namespace App\Http\Integrations\NewYorkTimes\DataObjects;

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
        $author = $article['byline']['original'];
        $title = $article['headline']['main'];
        $description = $article['lead_paragraph'];
        $url = $article['web_url'];

        if(isset($article['multimedia'][0]['url'])){
            $urlToImage = "https://www.nytimes.com/{$article['multimedia'][0]['url']}";
        } else {
            $urlToImage = "";
        }

        if(data_get($article, 'pub_date')){
            $dateString = data_get($article, 'pub_date');
            $dateTime = new \DateTime($dateString);
            $publishedAt = $dateTime->format('Y-m-d H:i:s');
        } else {
            $dateTime = new \DateTime(0);
            $publishedAt = $dateTime->format('Y-m-d H:i:s');
        }

        $content = $article['lead_paragraph'];

        return new static(
            author: $author ?? '',
            title: $title  ?? '',
            description: $description  ?? '',
            url: $url  ?? '',
            urlToImage: $urlToImage  ?? '',
            publishedAt: $publishedAt  ?? '',
            content: $content  ?? '',
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
