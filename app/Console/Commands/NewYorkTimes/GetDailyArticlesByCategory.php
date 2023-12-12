<?php

namespace App\Console\Commands\NewYorkTimes;

use App\Http\Integrations\NewYorkTimes\DataObjects\ArticleDto;
use App\Models\Article;
use Illuminate\Console\Command;
use App\Http\Integrations\NewYorkTimes\Requests\GetDailyArticlesByCategoryRequest;
use App\Http\Integrations\NewYorkTimes\NewYorkTimesConnector;
use App\Services\CategoryService;
use App\Services\SourceService;


class GetDailyArticlesByCategory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'newyorktimes:daily-articles {category : The articles category.}';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get all New York Times articles from last day';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $connector = new NewYorkTimesConnector();
        $category = (string) $this->argument('category');

        $request = new GetDailyArticlesByCategoryRequest(
            category: $category,
        );

        $this->info(
            string: "Fetching articles for category {$category}",
        );

        $response = $connector->send($request);

        if ($response->failed()) {
            throw $response->toException();
        }

        // Get or create category
        $categoryService = new CategoryService();
        $categoryId = $categoryService->getOrCreateCategoryIdByName($category);

        // Get or create source
        $sourceService = new SourceService();
        $sourceId = $sourceService->getOrCreateSourceIdByName("News York Times");

        // Get DTO response
        $articles = $response->dtoOrFail();

        // Prepare data to get inserted in DB
        $rows = $articles
        ->map(fn (ArticleDto $article) =>
              $article->toArray($categoryId, $sourceId)
        )->toArray();

        // Bulk insert articles
        Article::insert($rows);

        // Shows console Table for user
        $this->table(
            // headers: ['Author', 'Title', 'Description', 'Url', 'UrlToImage', 'PublishedAt', 'Content'],
            headers: ['Author', 'Title', 'Url', 'UrlToImage', 'PublishedAt'],
            rows: $rows,
        );

        return Command::SUCCESS;
    }
}
