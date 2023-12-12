<?php

namespace App\Http\Integrations\TheGuardian\Requests;

use App\Http\Integrations\TheGuardian\DataObjects\ArticleDto;
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
        return "/search?q={$this->category}&from_date={$oneDayAgo}";
    }

    public function createDtoFromResponse(Response $response): Collection
    {
        $responseData = $response->json();
        $articles = $responseData['response']['results'];

        return (new Collection(
            items: $articles,
        ))->map(function ($article) : ArticleDto {
            return ArticleDto::fromSaloon(
                article: $article,
            );
        });
    }
}
