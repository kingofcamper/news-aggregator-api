<?php

namespace App\Services;

use App\Models\Category;

class CategoryService
{
    public function getOrCreateCategoryIdByName(string $categoryName): int
    {
        $category = Category::where('name', $categoryName)->first();

        if ($category) {
            return $category->id;
        } else {
            $newCategory = Category::create([
                'name' => $categoryName,
            ]);

            return $newCategory->id;
        }
    }
}
