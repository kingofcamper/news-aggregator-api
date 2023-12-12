<?php

namespace App\Http\Integrations\NewsApi\Requests;

use App\Http\Integrations\NewsApi\DataObjects\ArticleDto;
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
        $oneDayAgo = $currentDate->modify('-1 day')->format('Y-m-d');
        return "/everything?q={$this->category}&from={$oneDayAgo}";
    }

    public function createDtoFromResponse(Response $response): Collection
    {
        return (new Collection(
            items: $response->json('articles'),
        ))->map(function ($article) : ArticleDto {
            return ArticleDto::fromSaloon(
                article: $article,
            );
        });
    }
}
