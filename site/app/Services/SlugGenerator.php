<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SlugGenerator
{
    public function generateFor(Model $model, string $source, string $column = 'slug'): string
    {
        $base = Str::slug($source);
        $slug = $base;
        $i = 1;
        while ($model->newQuery()->where($column, $slug)->exists()) {
            $slug = $base.'-'.$i++;
        }
        return $slug;
    }
}

