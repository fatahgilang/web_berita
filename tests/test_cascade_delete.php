<?php

/**
 * Test Script for News-Banner Cascade Delete
 * 
 * This script tests the automatic deletion of banners when news is deleted.
 * Run with: php artisan tinker < tests/test_cascade_delete.php
 */

use App\Models\News;
use App\Models\Banner;

echo "\n========================================\n";
echo "Testing News-Banner Cascade Delete\n";
echo "========================================\n\n";

// Get current counts
$newsCount = News::count();
$bannerCount = Banner::count();

echo "Initial State:\n";
echo "- Total News: $newsCount\n";
echo "- Total Banners: $bannerCount\n\n";

// Find a news that has a banner
$newsWithBanner = News::whereHas('banner')->first();

if (!$newsWithBanner) {
    echo "❌ No news with banner found for testing.\n";
    echo "Creating test data...\n\n";
    
    // Create a test news and banner
    $testNews = News::first();
    if ($testNews) {
        $testBanner = Banner::create(['news_id' => $testNews->id]);
        echo "✓ Created test banner for news ID: {$testNews->id}\n\n";
        $newsWithBanner = $testNews;
    } else {
        echo "❌ No news available in database. Please add news first.\n";
        exit;
    }
}

echo "Test Subject:\n";
echo "- News ID: {$newsWithBanner->id}\n";
echo "- News Title: {$newsWithBanner->title}\n";
echo "- Has Banner: " . ($newsWithBanner->banner ? 'Yes' : 'No') . "\n";
if ($newsWithBanner->banner) {
    echo "- Banner ID: {$newsWithBanner->banner->id}\n";
}
echo "\n";

// Confirm before deletion
echo "⚠️  WARNING: This will delete the news and its banner!\n";
echo "Press Ctrl+C to cancel, or Enter to continue...\n";
// readline();

echo "Deleting news (ID: {$newsWithBanner->id})...\n";

// Store banner ID for verification
$bannerId = $newsWithBanner->banner ? $newsWithBanner->banner->id : null;

// Delete the news
$newsWithBanner->delete();

echo "✓ News deleted successfully!\n\n";

// Verify banner was also deleted
if ($bannerId) {
    $bannerExists = Banner::find($bannerId);
    
    echo "Verification:\n";
    if ($bannerExists) {
        echo "❌ FAILED: Banner still exists (ID: $bannerId)\n";
    } else {
        echo "✓ SUCCESS: Banner was automatically deleted (ID: $bannerId)\n";
    }
} else {
    echo "⚠️  No banner was associated with the deleted news.\n";
}

// Show final counts
$newNewsCount = News::count();
$newBannerCount = Banner::count();

echo "\nFinal State:\n";
echo "- Total News: $newNewsCount (was $newsCount)\n";
echo "- Total Banners: $newBannerCount (was $bannerCount)\n";

echo "\n========================================\n";
echo "Test Complete!\n";
echo "========================================\n\n";
