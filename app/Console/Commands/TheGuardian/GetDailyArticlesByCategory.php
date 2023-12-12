<?php

namespace App\Console\Commands\TheGuardian;

use App\Http\Integrations\TheGuardian\DataObjects\ArticleDto;
use App\Models\Article;
use Illuminate\Console\Command;
use App\Http\Integrations\TheGuardian\Requests\GetDailyArticlesByCategoryRequest;
use App\Http\Integrations\TheGuardian\TheGuardianConnector;
use App\Services\CategoryService;
use App\Services\SourceService;


class GetDailyArticlesByCategory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'guardian:daily-articles {category : The articles category.}';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get all The guardian articles from last day';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $connector = new TheGuardianConnector();
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
        $sourceId = $sourceService->getOrCreateSourceIdByName("The Guardian");

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
