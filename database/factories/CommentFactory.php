<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Laravelista\Comments\Comment;

class CommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Comment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // $comment = [
        //     'comment'   => $this->faker->realText($this->faker->numberBetween(20,200)),
        //     'approved'  => $this->faker->boolean(80),
        // ];

        // $guest = $this->faker->boolean();
        // if ($guest) {
        //     $comment['guest_name'] = $this->faker->name;
        //     $comment['guest_email'] = $this->faker->freeEmail;
        // } else {
        //     $comment['commenter_type'] = User::class;
        //     $comment['commenter_id'] = rand(1,10);
        // }

        return [
            'comment'   => $this->faker->realText($this->faker->numberBetween(20,200)),
            'approved'  => $this->faker->boolean(80),
            'guest_name'=> $this->faker->name,
            'guest_email' => $this->faker->freeEmail
        ];
    }
}
