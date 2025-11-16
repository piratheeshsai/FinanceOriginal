<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestTamilFonts extends Command
{
    protected $signature = 'fonts:test-tamil';
    protected $description = 'Test if Tamil fonts are properly installed';

    public function handle()
    {
        $fontDir = storage_path('fonts/');
        $this->info("Checking Tamil fonts in: " . $fontDir);

        $requiredFonts = [
            'NotoSansTamil-Regular.ttf',
            'NotoSansTamil-Bold.ttf'
        ];

        $allFound = true;

        foreach ($requiredFonts as $font) {
            $fontPath = $fontDir . $font;

            if (file_exists($fontPath)) {
                $size = filesize($fontPath);
                $this->info("✅ Found: {$font} (" . number_format($size) . " bytes)");
            } else {
                $this->error("❌ Missing: {$font}");
                $allFound = false;
            }
        }

        if ($allFound) {
            $this->info("\n🎉 All Tamil fonts are installed correctly!");
        } else {
            $this->error("\n❌ Some fonts are missing. Please download them from:");
            $this->line("https://fonts.google.com/noto/specimen/Noto+Sans+Tamil");
        }

        // Check permissions
        if (is_writable($fontDir)) {
            $this->info("✅ Font directory is writable");
        } else {
            $this->error("❌ Font directory is not writable");
            $this->line("Run: chmod 755 " . $fontDir);
        }
    }
}
