# SEO & Search Engine Indexing Setup

This document explains how to set up and configure SEO features for CharyMeld Adverts.

## Features Implemented

### 1. XML Sitemap
- **Location**: `/public/sitemap.xml`
- **Auto-generation**: Sitemap is automatically generated when content changes
- **Manual generation**: Run `php artisan sitemap:generate`
- **Scheduled generation**: Runs daily at 2:00 AM
- **Includes**:
  - Homepage
  - Static pages (About, Contact, Terms, Blog Index)
  - Categories (26 categories)
  - Active adverts
  - Published blog posts
  - Blog categories
  - RSS feeds

### 2. Robots.txt
- **Location**: `/public/robots.txt`
- **Features**:
  - Allows all major search engines
  - Blocks admin areas, dashboards, API endpoints
  - Rate limits aggressive crawlers (Ahrefs, Semrush, MJ12)
  - Blocks malicious bots
  - Includes sitemap references

### 3. Search Engine Verification
Meta tags for verification are automatically added to all pages.

#### Google Search Console Setup:
1. Go to [Google Search Console](https://search.google.com/search-console)
2. Add your property (https://charymeld-adverts.com)
3. Choose "HTML tag" verification method
4. Copy the content value from the meta tag
5. Add to `.env`: `GOOGLE_SITE_VERIFICATION=your_verification_code`
6. Verify in Google Search Console

#### Bing Webmaster Tools Setup:
1. Go to [Bing Webmaster Tools](https://www.bing.com/webmasters)
2. Add your site
3. Choose "HTML Meta Tag" verification
4. Copy the verification code
5. Add to `.env`: `BING_SITE_VERIFICATION=your_verification_code`
6. Verify in Bing Webmaster Tools

## Configuration

### Environment Variables (.env)
```env
# SEO & Search Engine Verification
GOOGLE_SITE_VERIFICATION=your_google_verification_code
BING_SITE_VERIFICATION=your_bing_verification_code
SEO_ALLOW_INDEXING=true

# Social Media
TWITTER_HANDLE=@charymeld
FACEBOOK_APP_ID=your_facebook_app_id
```

### SEO Config (config/seo.php)
```php
'defaults' => [
    'title' => 'CharyMeld Adverts - Your Trusted Advertising Platform',
    'description' => 'Leading advertising platform...',
    'keywords' => 'advertising, classifieds, marketplace...',
],

'social' => [
    'twitter_handle' => '@charymeld',
    'facebook_app_id' => '',
],

'index' => [
    'allow_indexing' => true,
    'noindex_routes' => ['login', 'register', 'admin.*'],
],
```

## Automatic Sitemap Updates

The sitemap is automatically regenerated when:
1. **Content Changes**: When adverts, blogs, categories, or blog categories are created, updated, or deleted
2. **Daily Schedule**: Every day at 2:00 AM
3. **Manual Trigger**: By running `php artisan sitemap:generate`

## Search Engine Submission

### Google
1. Submit sitemap in [Google Search Console](https://search.google.com/search-console)
2. Go to Sitemaps → Add new sitemap
3. Enter: `https://charymeld-adverts.com/sitemap.xml`
4. Click Submit

### Bing
1. Submit sitemap in [Bing Webmaster Tools](https://www.bing.com/webmasters)
2. Go to Sitemaps → Submit Sitemap
3. Enter: `https://charymeld-adverts.com/sitemap.xml`
4. Click Submit

### Yahoo
Yahoo uses Bing's index, so submitting to Bing covers Yahoo as well.

## Monitoring & Analytics

### Check Indexing Status
- **Google**: Use `site:charymeld-adverts.com` in Google search
- **Bing**: Use `site:charymeld-adverts.com` in Bing search

### Monitor Performance
1. **Google Search Console**:
   - Check Coverage report for indexing status
   - Monitor Performance report for clicks and impressions
   - Review Enhancements for structured data

2. **Bing Webmaster Tools**:
   - Check Site Explorer for indexed pages
   - Monitor Search Performance
   - Review SEO Reports

## Structured Data (Schema.org)

Structured data is automatically generated for:
- **Adverts**: Product schema with offers
- **Blog Posts**: BlogPosting schema with author and publisher
- **Categories**: CollectionPage schema

Implemented in `app/Helpers/SeoHelper.php`

## Best Practices

1. **Regular Content Updates**: Keep publishing new blog posts and adverts
2. **Monitor Sitemap**: Check `/sitemap.xml` regularly to ensure it's up to date
3. **Fix Crawl Errors**: Review and fix any errors in Search Console
4. **Optimize Meta Tags**: Use SEO-friendly titles and descriptions
5. **Internal Linking**: Link related content (implemented automatically)
6. **Mobile-Friendly**: Ensure site is responsive (already implemented)
7. **Page Speed**: Monitor and optimize loading times

## Troubleshooting

### Sitemap not updating
- Check if observers are working: `php artisan tinker` → Test creating content
- Check logs: `storage/logs/laravel.log`
- Manually regenerate: `php artisan sitemap:generate`

### Verification meta tag not showing
- Clear config cache: `php artisan config:clear`
- Check .env file has the verification codes
- View page source to verify meta tags are present

### Pages not being indexed
- Check robots.txt isn't blocking: Visit `/robots.txt`
- Verify sitemap is accessible: Visit `/sitemap.xml`
- Check Search Console for crawl errors
- Ensure pages are published and active

## Cron Job Setup

To enable scheduled sitemap generation, add this to your crontab:

```bash
* * * * * cd /path/to/charymeld-adverts && php artisan schedule:run >> /dev/null 2>&1
```

Or use Laravel Forge/Envoyer which handles this automatically.

## Additional Resources

- [Google Search Console Help](https://support.google.com/webmasters)
- [Bing Webmaster Guidelines](https://www.bing.com/webmasters/help/webmaster-guidelines-30fba23a)
- [Schema.org Documentation](https://schema.org/)
- [Spatie Sitemap Package](https://github.com/spatie/laravel-sitemap)
