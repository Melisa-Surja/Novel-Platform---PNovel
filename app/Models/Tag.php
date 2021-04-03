<?php

namespace App\Models;

use Spatie\Tags\Tag as TagParent;

class Tag extends TagParent
{
    // just for the count!
    public function series() {
        return $this->morphedByMany(Series::class, 'taggable');
    }
}
