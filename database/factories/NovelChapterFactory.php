<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\NovelChapter;
use Illuminate\Database\Eloquent\Factories\Factory;

class NovelChapterFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = NovelChapter::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $content = $this->faker->paragraphs($this->faker->numberBetween(10,20));
        $content = implode("\n", $content);
        return [
            'title' => $this->faker->unique()->realText($this->faker->numberBetween(14,100)),
            'content' => $content,
            'nsfw'  => $this->faker->boolean(50),
        ];
    }
}
