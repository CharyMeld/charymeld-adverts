<?php

namespace App\Console\Commands;

use App\Models\Advert;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Category;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate XML sitemap for the website';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Generating sitemap...');

        $sitemap = Sitemap::create();

        // Add homepage
        $sitemap->add(
            Url::create(route('home'))
                ->setLastModificationDate(now())
                ->setPriority(1.0)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
        );

        // Add static pages
        $staticPages = [
            ['route' => 'about', 'priority' => 0.8, 'frequency' => Url::CHANGE_FREQUENCY_MONTHLY],
            ['route' => 'contact', 'priority' => 0.8, 'frequency' => Url::CHANGE_FREQUENCY_MONTHLY],
            ['route' => 'terms', 'priority' => 0.5, 'frequency' => Url::CHANGE_FREQUENCY_YEARLY],
            ['route' => 'blogs.index', 'priority' => 0.9, 'frequency' => Url::CHANGE_FREQUENCY_DAILY],
        ];

        foreach ($staticPages as $page) {
            $sitemap->add(
                Url::create(route($page['route']))
                    ->setLastModificationDate(now())
                    ->setPriority($page['priority'])
                    ->setChangeFrequency($page['frequency'])
            );
        }

        $this->info('Added static pages');

        // Add categories
        $categoriesCount = 0;
        Category::active()->chunk(100, function ($categories) use ($sitemap, &$categoriesCount) {
            foreach ($categories as $category) {
                $sitemap->add(
                    Url::create(route('category.show', $category->slug))
                        ->setLastModificationDate($category->updated_at)
                        ->setPriority(0.8)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                );
                $categoriesCount++;
            }
        });

        $this->info("Added {$categoriesCount} categories");

        // Add active adverts
        $advertsCount = 0;
        Advert::active()->approved()->notExpired()->chunk(100, function ($adverts) use ($sitemap, &$advertsCount) {
            foreach ($adverts as $advert) {
                $sitemap->add(
                    Url::create(route('advert.show', $advert->slug))
                        ->setLastModificationDate($advert->updated_at)
                        ->setPriority(0.7)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                );
                $advertsCount++;
            }
        });

        $this->info("Added {$advertsCount} adverts");

        // Add published blogs
        $blogsCount = 0;
        Blog::published()->chunk(100, function ($blogs) use ($sitemap, &$blogsCount) {
            foreach ($blogs as $blog) {
                $sitemap->add(
                    Url::create(route('blog.show', $blog->slug))
                        ->setLastModificationDate($blog->updated_at)
                        ->setPriority(0.7)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                );
                $blogsCount++;
            }
        });

        $this->info("Added {$blogsCount} blogs");

        // Add blog categories
        $blogCategoriesCount = 0;
        BlogCategory::active()->chunk(100, function ($categories) use ($sitemap, &$blogCategoriesCount) {
            foreach ($categories as $category) {
                $sitemap->add(
                    Url::create(route('blog.category', $category->slug))
                        ->setLastModificationDate($category->updated_at)
                        ->setPriority(0.6)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                );
                $blogCategoriesCount++;
            }
        });

        $this->info("Added {$blogCategoriesCount} blog categories");

        // Add RSS feeds
        $sitemap->add(
            Url::create(route('rss.blog'))
                ->setLastModificationDate(now())
                ->setPriority(0.5)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
        );

        $this->info("Added RSS feeds");

        // Save to public directory
        $path = public_path('sitemap.xml');
        $sitemap->writeToFile($path);

        $this->info("Sitemap generated successfully at: {$path}");

        return Command::SUCCESS;
    }
}
