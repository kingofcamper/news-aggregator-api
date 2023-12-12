<?php

namespace App\Http\Integrations\NewYorkTimes\Requests;

use App\Http\Integrations\NewYorkTimes\DataObjects\ArticleDto;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Illuminate\Support\Collection;

class GetDailyArticlesByCategoryRequest extends Request
{
    // add Category in constructor
    public function __construct(
        public string $category,
    ) {}

    /**
     * The HTTP method of the request
     */
    protected Method $method = Method::GET;

    /**
     * The endpoint for the request
     */
    public function resolveEndpoint(): string
    {
        $currentDate = new \DateTime();
        $oneDayAgo = $currentDate->modify('-1 day')->format('Ymd');
        return "/articlesearch.json?q={$this->category}&begin_date={$oneDayAgo}";
    }

    public function createDtoFromResponse(Response $response): Collection
    {
        $responseData = $response->json();
        $articles = $responseData['response']['docs'];

        return (new Collection(
            items: $articles,
        ))->map(function ($article) : ArticleDto {
            return ArticleDto::fromSaloon(
                article: $article,
            );
        });
    }
}
