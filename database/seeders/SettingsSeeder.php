<?php

namespace Database\Seeders;

use App\Models\Settings;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $default = [
            'comment_blacklist' => ['infoforwomen','miki7.site'], 
            'comment_moderated' => ['http','php','html','.se','.ru','.be','erectile','.com','www'], 
            'featured' => [1]
        ];
        $create = [];
        foreach ($default as $key => $value) {
            $create[] = ['key'=>$key, 'value'=>json_encode($value)];
        }
        Settings::insert($create);
    }
}
