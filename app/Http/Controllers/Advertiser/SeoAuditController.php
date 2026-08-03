<?php

namespace App\Http\Controllers\Advertiser;

use App\Http\Controllers\Controller;
use App\Models\Advert;
use Illuminate\Http\Request;

class SeoAuditController extends Controller
{
    /**
     * Show SEO audit for an advert
     */
    public function show(Advert $advert)
    {
        // Check ownership
        if ($advert->user_id !== auth()->id()) {
            abort(403);
        }

        $audit = $this->performAudit($advert);

        return view('advertiser.seo-audit', compact('advert', 'audit'));
    }

    /**
     * Perform SEO audit on advert
     */
    private function performAudit(Advert $advert)
    {
        $score = 0;
        $maxScore = 100;
        $issues = [];
        $recommendations = [];

        // Title checks (20 points)
        if (strlen($advert->title) < 30) {
            $issues[] = '⚠️ Title too short (< 30 characters)';
            $recommendations[] = 'Expand title to 50-60 characters for better SEO';
        } elseif (strlen($advert->title) > 70) {
            $issues[] = '⚠️ Title too long (> 70 characters)';
            $recommendations[] = 'Shorten title to 50-60 characters';
        } else {
            $score += 20;
        }

        // Description checks (20 points)
        if (strlen($advert->description) < 100) {
            $issues[] = '⚠️ Description too short (< 100 characters)';
            $recommendations[] = 'Add detailed description (minimum 150 characters)';
        } elseif (strlen($advert->description) > 500) {
            $score += 20;
        } else {
            $score += 15;
        }

        // Images check (15 points)
        $imageCount = $advert->images->count();
        if ($imageCount == 0) {
            $issues[] = '❌ No images uploaded';
            $recommendations[] = 'Add at least 3 high-quality images';
        } elseif ($imageCount < 3) {
            $score += 7;
            $issues[] = '⚠️ Only ' . $imageCount . ' image(s)';
            $recommendations[] = 'Add more images (3-5 recommended)';
        } else {
            $score += 15;
        }

        // Price check (10 points)
        if ($advert->price > 0) {
            $score += 10;
        } else {
            $issues[] = '⚠️ No price specified';
            $recommendations[] = 'Add price for better search ranking';
        }

        // Location check (10 points)
        if ($advert->location) {
            $score += 10;
        } else {
            $issues[] = '⚠️ No location specified';
            $recommendations[] = 'Add specific location for local SEO';
        }

        // Category check (10 points)
        if ($advert->category_id) {
            $score += 10;
        }

        // Keyword density (15 points)
        $keywordDensity = $this->calculateKeywordDensity($advert);
        if ($keywordDensity > 0.5 && $keywordDensity < 3) {
            $score += 15;
        } elseif ($keywordDensity >= 3) {
            $score += 8;
            $issues[] = '⚠️ Keyword density too high (' . round($keywordDensity, 1) . '%)';
            $recommendations[] = 'Reduce keyword repetition for natural content';
        } else {
            $score += 5;
            $recommendations[] = 'Include relevant keywords naturally in description';
        }

        return [
            'score' => min($score, $maxScore),
            'grade' => $this->getGrade($score),
            'issues' => $issues,
            'recommendations' => $recommendations,
            'details' => [
                'title_length' => strlen($advert->title),
                'description_length' => strlen($advert->description),
                'image_count' => $imageCount,
                'has_price' => $advert->price > 0,
                'has_location' => !empty($advert->location),
                'keyword_density' => round($keywordDensity, 2),
            ]
        ];
    }

    /**
     * Calculate keyword density
     */
    private function calculateKeywordDensity(Advert $advert)
    {
        $text = strtolower($advert->title . ' ' . $advert->description);
        $words = str_word_count($text, 1);
        $totalWords = count($words);

        if ($totalWords == 0) return 0;

        // Get main keywords from title
        $titleWords = str_word_count(strtolower($advert->title), 1);
        $keywordCount = 0;

        foreach ($titleWords as $keyword) {
            if (strlen($keyword) > 3) { // Only count words longer than 3 chars
                $keywordCount += substr_count($text, $keyword);
            }
        }

        return ($keywordCount / $totalWords) * 100;
    }

    /**
     * Get letter grade from score
     */
    private function getGrade($score)
    {
        if ($score >= 90) return 'A+';
        if ($score >= 80) return 'A';
        if ($score >= 70) return 'B';
        if ($score >= 60) return 'C';
        if ($score >= 50) return 'D';
        return 'F';
    }
}
