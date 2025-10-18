<?php

namespace App\Console\Commands;

use App\Models\Advert;
use App\Models\Blog;
use App\Models\Category;
use App\Models\User;
use Illuminate\Console\Command;

class IndexSearchData extends Command
{
    protected $signature = 'search:index {model?}';
    protected $description = 'Index data for search';

    public function handle()
    {
        $model = $this->argument('model');

        if (!$model || $model === 'adverts') {
            $this->info('Indexing Adverts...');
            Advert::makeAllSearchable();
            $count = Advert::count();
            $this->info("✓ Indexed {$count} adverts");
        }

        if (!$model || $model === 'blogs') {
            $this->info('Indexing Blogs...');
            Blog::makeAllSearchable();
            $count = Blog::count();
            $this->info("✓ Indexed {$count} blogs");
        }

        if (!$model || $model === 'categories') {
            $this->info('Indexing Categories...');
            Category::makeAllSearchable();
            $count = Category::count();
            $this->info("✓ Indexed {$count} categories");
        }

        if (!$model || $model === 'users') {
            $this->info('Indexing Users...');
            User::makeAllSearchable();
            $count = User::count();
            $this->info("✓ Indexed {$count} users");
        }

        $this->info('✓ All data indexed successfully!');

        return Command::SUCCESS;
    }
}
