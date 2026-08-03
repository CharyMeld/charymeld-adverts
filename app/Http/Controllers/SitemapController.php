<?php

namespace App\Http\Controllers;

use App\Models\Advert;
use App\Models\Blog;
use App\Models\Category;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapController extends Controller
{
    /**
     * Generate and return sitemap
     */
    public function index()
    {
        $sitemap = Sitemap::create();

        // Add homepage
        $sitemap->add(
            Url::create(route('home'))
                ->setLastModificationDate(now())
                ->setPriority(1.0)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
        );

        // Add static pages
        $sitemap->add(
            Url::create(route('about'))
                ->setLastModificationDate(now())
                ->setPriority(0.8)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
        );

        $sitemap->add(
            Url::create(route('contact'))
                ->setLastModificationDate(now())
                ->setPriority(0.8)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
        );

        $sitemap->add(
            Url::create(route('blogs.index'))
                ->setLastModificationDate(now())
                ->setPriority(0.9)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
        );

        // Add categories
        Category::active()->get()->each(function (Category $category) use ($sitemap) {
            $sitemap->add(
                Url::create(route('category.show', $category->slug))
                    ->setLastModificationDate($category->updated_at)
                    ->setPriority(0.8)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
            );
        });

        // Add active adverts
        Advert::with('category')
            ->active()
            ->approved()
            ->notExpired()
            ->get()
            ->each(function (Advert $advert) use ($sitemap) {
                $sitemap->add(
                    Url::create(route('advert.show', $advert->slug))
                        ->setLastModificationDate($advert->updated_at)
                        ->setPriority(0.7)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                );
            });

        // Add published blogs
        Blog::published()->get()->each(function (Blog $blog) use ($sitemap) {
            $sitemap->add(
                Url::create(route('blog.show', $blog->slug))
                    ->setLastModificationDate($blog->updated_at)
                    ->setPriority(0.7)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
            );
        });

        return $sitemap->toResponse(request());
    }

    /**
     * Generate sitemap and save to public folder
     */
    public function generate()
    {
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

        // Add categories
        Category::active()->get()->each(function (Category $category) use ($sitemap) {
            $sitemap->add(
                Url::create(route('category.show', $category->slug))
                    ->setLastModificationDate($category->updated_at)
                    ->setPriority(0.8)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
            );
        });

        // Add active adverts
        Advert::active()
            ->approved()
            ->notExpired()
            ->get()
            ->each(function (Advert $advert) use ($sitemap) {
                $sitemap->add(
                    Url::create(route('advert.show', $advert->slug))
                        ->setLastModificationDate($advert->updated_at)
                        ->setPriority(0.7)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                );
            });

        // Add published blogs
        Blog::published()->get()->each(function (Blog $blog) use ($sitemap) {
            $sitemap->add(
                Url::create(route('blog.show', $blog->slug))
                    ->setLastModificationDate($blog->updated_at)
                    ->setPriority(0.7)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
            );
        });

        // Save to public directory
        $sitemap->writeToFile(public_path('sitemap.xml'));

        return response()->json([
            'message' => 'Sitemap generated successfully!',
            'path' => public_path('sitemap.xml')
        ]);
    }
}
