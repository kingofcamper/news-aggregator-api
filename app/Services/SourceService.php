<?php

namespace App\Services;

use App\Models\Source;

class SourceService
{
    public function getOrCreateSourceIdByName(string $sourceName): int
    {
        $source = Source::where('name', $sourceName)->first();

        if ($source) {
            return $source->id;
        } else {
            $newSource = Source::create([
                'name' => $sourceName,
            ]);

            return $newSource->id;
        }
    }
}
