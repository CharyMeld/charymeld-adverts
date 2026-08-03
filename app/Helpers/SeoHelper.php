<?php

namespace App\Helpers;

class SeoHelper
{
    /**
     * Generate SEO meta tags for adverts
     */
    public static function generateAdvertMeta($advert): array
    {
        $title = $advert->title . ' - CharyMeld Adverts';
        $description = self::truncate($advert->description, 160);
        $image = $advert->primaryImage?->image_path ?? asset('images/default-ad.jpg');
        $url = route('advert.show', $advert->slug);

        return [
            'title' => $title,
            'description' => $description,
            'keywords' => self::generateKeywords($advert),
            'canonical' => $url,
            'og' => [
                'title' => $advert->title,
                'description' => $description,
                'image' => $image,
                'url' => $url,
                'type' => 'product',
                'site_name' => config('app.name'),
            ],
            'twitter' => [
                'card' => 'summary_large_image',
                'title' => $advert->title,
                'description' => $description,
                'image' => $image,
            ],
            'schema' => self::generateAdvertSchema($advert),
        ];
    }

    /**
     * Generate SEO meta tags for blog posts
     */
    public static function generateBlogMeta($blog): array
    {
        // Use meta fields if available, otherwise fallback to defaults
        $title = $blog->meta_title ?? ($blog->title . ' - CharyMeld Blog');
        $description = $blog->meta_description ??
                      $blog->excerpt ??
                      self::truncate(strip_tags($blog->content), 160);
        $keywords = $blog->meta_keywords ??
                   (is_array($blog->tags) ? implode(', ', $blog->tags) : '') ??
                   self::extractKeywordsFromContent($blog->content);
        $image = $blog->featured_image ? asset('storage/' . $blog->featured_image) : asset('images/default-blog.jpg');
        $url = route('blog.show', $blog->slug);

        return [
            'title' => $title,
            'description' => $description,
            'keywords' => $keywords,
            'canonical' => $url,
            'og' => [
                'title' => $blog->meta_title ?? $blog->title,
                'description' => $description,
                'image' => $image,
                'url' => $url,
                'type' => 'article',
                'site_name' => config('app.name'),
                'published_time' => $blog->published_at?->toIso8601String(),
                'author' => $blog->user?->name,
            ],
            'twitter' => [
                'card' => 'summary_large_image',
                'title' => $blog->meta_title ?? $blog->title,
                'description' => $description,
                'image' => $image,
            ],
            'schema' => self::generateBlogSchema($blog),
        ];
    }

    /**
     * Generate SEO meta tags for categories
     */
    public static function generateCategoryMeta($category): array
    {
        $title = $category->name . ' - CharyMeld Adverts';
        $description = $category->description ?? "Browse {$category->name} listings on CharyMeld Adverts";
        $url = route('category.show', $category->slug);

        return [
            'title' => $title,
            'description' => $description,
            'keywords' => $category->name . ', adverts, listings, marketplace',
            'canonical' => $url,
            'og' => [
                'title' => $category->name,
                'description' => $description,
                'url' => $url,
                'type' => 'website',
                'site_name' => config('app.name'),
            ],
            'twitter' => [
                'card' => 'summary',
                'title' => $category->name,
                'description' => $description,
            ],
            'schema' => self::generateCategorySchema($category),
        ];
    }

    /**
     * Generate Schema.org markup for advert
     */
    private static function generateAdvertSchema($advert): string
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $advert->title,
            'description' => $advert->description,
            'image' => $advert->primaryImage?->image_path,
            'url' => route('advert.show', $advert->slug),
        ];

        if ($advert->price) {
            $schema['offers'] = [
                '@type' => 'Offer',
                'price' => $advert->price,
                'priceCurrency' => 'NGN', // Update based on your currency
                'availability' => 'https://schema.org/InStock',
            ];
        }

        if ($advert->category) {
            $schema['category'] = $advert->category->name;
        }

        return json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    /**
     * Generate Schema.org markup for blog
     */
    private static function generateBlogSchema($blog): string
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'BlogPosting',
            'headline' => $blog->title,
            'description' => self::truncate(strip_tags($blog->content), 200),
            'image' => $blog->featured_image,
            'datePublished' => $blog->published_at?->toIso8601String(),
            'dateModified' => $blog->updated_at->toIso8601String(),
            'author' => [
                '@type' => 'Person',
                'name' => $blog->user?->name ?? 'CharyMeld Team',
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => config('app.name'),
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => asset('images/logo.png'),
                ],
            ],
        ];

        return json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    /**
     * Generate Schema.org markup for category
     */
    private static function generateCategorySchema($category): string
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'CollectionPage',
            'name' => $category->name,
            'description' => $category->description,
            'url' => route('category.show', $category->slug),
        ];

        return json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    /**
     * Generate keywords from advert
     */
    private static function generateKeywords($advert): string
    {
        $keywords = [];

        $keywords[] = $advert->title;
        if ($advert->category) {
            $keywords[] = $advert->category->name;
        }
        if ($advert->location) {
            $keywords[] = $advert->location;
        }

        return implode(', ', array_unique($keywords));
    }

    /**
     * Extract keywords from blog content
     */
    private static function extractKeywordsFromContent($content): string
    {
        // Simple keyword extraction - could be enhanced with NLP
        $text = strip_tags($content);
        $words = str_word_count(strtolower($text), 1);
        $stopWords = ['the', 'a', 'an', 'and', 'or', 'but', 'in', 'on', 'at', 'to', 'for', 'of', 'with', 'is', 'are', 'was', 'were'];

        $words = array_diff($words, $stopWords);
        $wordCounts = array_count_values($words);
        arsort($wordCounts);

        $keywords = array_slice(array_keys($wordCounts), 0, 10);

        return implode(', ', $keywords);
    }

    /**
     * Truncate text to specified length
     */
    private static function truncate($text, $length = 160): string
    {
        if (strlen($text) <= $length) {
            return $text;
        }

        return substr($text, 0, $length - 3) . '...';
    }

    /**
     * Generate breadcrumb schema
     */
    public static function generateBreadcrumbSchema(array $breadcrumbs): string
    {
        $itemListElement = [];
        $position = 1;

        foreach ($breadcrumbs as $breadcrumb) {
            $itemListElement[] = [
                '@type' => 'ListItem',
                'position' => $position++,
                'name' => $breadcrumb['name'],
                'item' => $breadcrumb['url'],
            ];
        }

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $itemListElement,
        ];

        return json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }
}
