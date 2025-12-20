<?php

namespace Database\Factories;

use App\Models\BucketListImage;
use App\Models\BucketList;
use Illuminate\Database\Eloquent\Factories\Factory;

class BucketListImageFactory extends Factory
{
    protected $model = BucketListImage::class;

    public function definition()
    {
        return [
            'bucket_list_id' => BucketList::factory(),
            'image_path' => 'bucket_list_images/' . $this->faker->uuid . '.jpg', // Placeholder path
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}