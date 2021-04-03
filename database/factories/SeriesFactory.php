<?php

namespace Database\Factories;

use App\Models\Series;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SeriesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Series::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $title = $this->faker->unique()->realText($this->faker->numberBetween(14,100));

        $summary = $this->faker->paragraphs($this->faker->numberBetween(3,10));
        $summary = implode("\n", $summary);
        $excerpt = substr($summary, 0, 300);
        $excerpt = explode(" ", $excerpt);
        array_pop($excerpt);
        $excerpt = implode(" ", $excerpt);


        $staffs = [];
        if ($this->faker->boolean) {
            $staffs = [
                'translator' => $this->faker->unique()->name,
                'editor'    => $this->faker->unique()->name
            ];
        }

        // $arcs_num = $this->faker->numberBetween(0,3);
        // $arcs = [
        //     ['title' => $this->faker->realText($this->faker->numberBetween(3,7)), 'chapter' => 1]
        // ];

        return [
            'title'     => $title,
            'slug'      => Str::slug($title),
            'summary'   => $summary,
            'excerpt'   => $excerpt,
            'author'    => $this->faker->unique()->name,
            'staffs'    => $staffs,
            'arcs'      => [],
            'poster_id' => User::permission('create series')->inRandomOrder()->first('id'),
            'published_at' => Carbon::now()->subDays(20)->subDays(rand(1, 10))->subMinutes(rand(1, 59)) // today - 30days, random 10 days
        ];
    }
}
