<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Series;
use App\Models\NovelChapter;
use App\Models\User;
use Database\Factories\CommentFactory;
use Faker\Factory;
use Laravelista\Comments\Comment;
use Carbon\Carbon;

class SeriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (config('app.env') == "production") return;

        $faker = Factory::create();
        $user = User::find(1);
        Series::factory(10)->create()->each(function ($series)use($faker, $user) {
            // add to reading list
            $in_reading_list = false;
            if (rand(1,100) > 70) {
                $user->reading_list()->attach($series->id);
                $in_reading_list = true;
            }

            // cover
            $cover_url = "https://picsum.photos/212/300";
            $series->addMediaFromUrl($cover_url)->toMediaCollection('covers');

            $ch_num = rand(3,15);
            for ($i=1; $i <= $ch_num; $i++) { 
                $ch_part = 0;
                if(rand(0,1) == 1) $ch_part = rand(1,3);
                $create = [
                    'chapter' => $i,
                    'series_id' => $series->id,
                    'poster_id' => $series->poster_id,
                    'published_at' => Carbon::now()->subDays(20)->subDays(rand(1, 10))->subMinutes(rand(1, 59)) // today - 30days, random 10 days
                ];
                $how_many = $ch_part == 0 ? 1 : $ch_part;
                $k = 1;
                NovelChapter::factory($how_many)->create($create)->each(function($c) use(&$k, $how_many) {
                    // chapter part but only if it's more than part 1
                    if ($how_many > 1) $c->update(['chapter_part'=>$k]);
                    $k++;
                    
                    // comments
                    CommentFactory::new()->count(rand(1,5))->create([
                        'commentable_type'  => NovelChapter::class,
                        'commentable_id'    => $c->id
                    ]);
                });
            }

            // comments
            CommentFactory::new()->count(rand(1,5))->create([
                'commentable_type'  => Series::class,
                'commentable_id'    => $series->id
            ]);
        });
    }
}
