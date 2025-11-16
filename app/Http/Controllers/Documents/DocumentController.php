<?php


namespace App\Http\Controllers\Documents;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\Company; // Add this import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use NumberToWords\NumberToWords;
use Spatie\Browsershot\Browsershot;
use Str;
use Illuminate\Support\Facades\Log;

class DocumentController extends Controller
{
    /**
     * Helper method to get company logo for PDF generation
     * Matches the same logic as your sidebar
     */
    private function getCompanyLogo()
    {
        try {
            $company = Company::first();

            if ($company && $company->logo) {
                // Check if logo exists in storage (same path as sidebar)
                if (Storage::exists('logos/' . $company->logo)) {
                    $logoPath = Storage::path('logos/' . $company->logo);
                    $logoData = base64_encode(file_get_contents($logoPath));

                    // Detect image type
                    $imageType = pathinfo($company->logo, PATHINFO_EXTENSION);
                    $mimeType = $this->getImageMimeType($imageType);

                    Log::info("Using company logo from storage: logos/" . $company->logo);
                    return 'data:' . $mimeType . ';base64,' . $logoData;
                }

                // Alternative: Check if logo exists in public/storage (symlinked)
                $publicStoragePath = public_path('storage/logos/' . $company->logo);
                if (file_exists($publicStoragePath)) {
                    $logoData = base64_encode(file_get_contents($publicStoragePath));
                    $imageType = pathinfo($company->logo, PATHINFO_EXTENSION);
                    $mimeType = $this->getImageMimeType($imageType);

                    Log::info("Using company logo from public storage: " . $publicStoragePath);
                    return 'data:' . $mimeType . ';base64,' . $logoData;
                }
            }

            // Fallback to default logo (same as your sidebar else condition)
            $defaultLogoPath = public_path('assets/img/logo-ct.png');
            if (file_exists($defaultLogoPath)) {
                $logoData = base64_encode(file_get_contents($defaultLogoPath));
                Log::info("Using default logo from assets");
                return 'data:image/png;base64,' . $logoData;
            }

            // Final fallback
            $logoPath = public_path('logos/logo.png');
            if (file_exists($logoPath)) {
                $logoData = base64_encode(file_get_contents($logoPath));
                Log::info("Using fallback logo from public/logos");
                return 'data:image/png;base64,' . $logoData;
            }

            throw new \Exception('No logo file found');

        } catch (\Exception $e) {
            Log::error('Logo loading error: ' . $e->getMessage());

            // Debug information
            $company = Company::first();
            if ($company) {
                Log::error('Company logo filename: ' . ($company->logo ?? 'NULL'));
                Log::error('Storage path exists: ' . (Storage::exists('logos/' . ($company->logo ?? 'test.png')) ? 'YES' : 'NO'));
                Log::error('Public storage path exists: ' . (file_exists(public_path('storage/logos/' . ($company->logo ?? 'test.png'))) ? 'YES' : 'NO'));
            }

            throw new \Exception('Logo file not found');
        }
    }

    /**
     * Get proper MIME type for different image formats
     */
    private function getImageMimeType($extension)
    {
        $mimeTypes = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'svg' => 'image/svg+xml',
            'webp' => 'image/webp'
        ];

        return $mimeTypes[strtolower($extension)] ?? 'image/png';
    }

    public function agreement()
    {
        return view('documents.agreement');
    }

    public function exportSingle(Loan $loan)
    {
        try {
            $loan->load('customer', 'approval', 'center.branch');

            // Use the helper method instead of hardcoded path
            $logoSrc = $this->getCompanyLogo();

            // Render Blade view to HTML
            $html = view('pdf.loan-agreement', [
                'loan' => $loan,
                'logoSrc' => $logoSrc
            ])->render();

            // Generate a safe file name
            $sanitizedLoanNumber = Str::slug($loan->loan_number, '-');
            $fileName = 'loan-agreement-' . $sanitizedLoanNumber . '-' . now()->format('Y-m-d') . '.pdf';

            $pdfPath = $this->generatePDFWithTamilFonts($html, $fileName);

            return response()->download($pdfPath)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            Log::error('PDF Export Error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function exportFiltered(Request $request)
    {
        try {
            $dateFilter = $request->input('date_filter');
            $search = $request->input('search', '');

            $loans = Loan::query()
                ->when($dateFilter, function ($query) use ($dateFilter) {
                    $query->whereDate('loan_date', $dateFilter);
                })
                ->when($search, function ($query) use ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('loan_number', 'like', '%' . $search . '%')
                            ->orWhereHas('customer', function ($subQuery) use ($search) {
                                $subQuery->where('full_name', 'like', '%' . $search . '%')
                                    ->orWhere('nic', 'like', '%' . $search . '%');
                            });
                    });
                })
                ->with('customer', 'approval', 'center.branch')
                ->get();

            if ($loans->isEmpty()) {
                return response()->json([
                    'error' => 'No loans found for the selected filters.'
                ], 404);
            }

            // Use the helper method
            $logoSrc = $this->getCompanyLogo();

            // Render HTML content
            $html = view('pdf.loan-agreements-bulk', [
                'loans' => $loans,
                'dateFilter' => $dateFilter,
                'logoSrc' => $logoSrc,
            ])->render();

            // Generate file name
            $filename = 'loan-agreements-bulk-' . ($dateFilter ?: 'all') . '-' . now()->format('Y-m-d') . '.pdf';

            $pdfPath = $this->generatePDFWithTamilFonts($html, $filename);

            return response()->download($pdfPath)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            Log::error('PDF Export Error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Mortgage document view
    public function mortgage()
    {
        return view('documents.mortgage');
    }

    public function exportSingleMortgage(Loan $loan)
    {
        try {
            $loan->load('customer', 'approval', 'center.branch');

            // Use the helper method
            $logoSrc = $this->getCompanyLogo();

            // Convert amount to words
            $numberToWords = new NumberToWords();
            $numberTransformer = $numberToWords->getNumberTransformer('en');
            $amountInWords = ucfirst($numberTransformer->toWords($loan->loan_amount));

            // Render the Blade view with all required data
            $html = view('pdf.loan-mortagage', [
                'loan' => $loan,
                'logoSrc' => $logoSrc,
                'amountInWords' => $amountInWords
            ])->render();

            // Generate a safe file name
            $sanitizedLoanNumber = Str::slug($loan->loan_number, '-');
            $fileName = 'loan-mortgage-' . $sanitizedLoanNumber . '-' . now()->format('Y-m-d') . '.pdf';

            $pdfPath = $this->generatePDFWithTamilFonts($html, $fileName);

            return response()->download($pdfPath)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            Log::error('PDF Export Error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function exportFilteredMortgage(Request $request)
    {
        try {
            $dateFilter = $request->input('date_filter');
            $search = $request->input('search', '');

            $loans = Loan::query()
                ->when($dateFilter, function ($query) use ($dateFilter) {
                    $query->whereDate('loan_date', $dateFilter);
                })
                ->when($search, function ($query) use ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('loan_number', 'like', '%' . $search . '%')
                            ->orWhereHas('customer', function ($subQuery) use ($search) {
                                $subQuery->where('full_name', 'like', '%' . $search . '%')
                                    ->orWhere('nic', 'like', '%' . $search . '%');
                            });
                    });
                })
                ->with('customer', 'approval', 'center.branch')
                ->get();

            if ($loans->isEmpty()) {
                return response()->json([
                    'error' => 'No loans found for the selected filters.'
                ], 404);
            }

            // Use the helper method
            $logoSrc = $this->getCompanyLogo();

            $numberToWords = new NumberToWords();
            $transformer = $numberToWords->getNumberTransformer('en');

            $html = view('pdf.loan-mortagage-bulk', [
                'loans' => $loans,
                'dateFilter' => $dateFilter,
                'logoSrc' => $logoSrc,
                'transformer' => $transformer,
            ])->render();

            $filename = 'loan-mortgage-bulk-' . ($dateFilter ?: 'all') . '-' . now()->format('Y-m-d') . '.pdf';

            $pdfPath = $this->generatePDFWithTamilFonts($html, $filename);

            return response()->download($pdfPath)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            Log::error('PDF Export Error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Promissory document view
    public function promissory()
    {
        return view('documents.promissory');
    }

    public function exportSingleAgreementLending(Loan $loan)
    {
        try {
            $loan->load('customer', 'approval', 'center.branch');
            $logoSrc = $this->getCompanyLogo(); // Use helper method

            $numberToWords = new NumberToWords();
            $numberTransformer = $numberToWords->getNumberTransformer('en');
            $amountInWords = ucfirst($numberTransformer->toWords($loan->loan_amount));

            $html = view('pdf.loan-AgreementLending', [
                'loan' => $loan,
                'logoSrc' => $logoSrc,
                'amountInWords' => $amountInWords
            ])->render();

            $sanitizedLoanNumber = Str::slug($loan->loan_number, '-');
            $fileName = 'loan-AgreementLending-' . $sanitizedLoanNumber . '-' . now()->format('Y-m-d') . '.pdf';

            $pdfPath = $this->generatePDFWithTamilFonts($html, $fileName);

            return response()->download($pdfPath)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            Log::error('PDF Export Error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function exportFilteredAgreementLending(Request $request)
    {
        try {
            $dateFilter = $request->input('date_filter');
            $search = $request->input('search', '');

            $loans = Loan::query()
                ->when($dateFilter, function ($query) use ($dateFilter) {
                    $query->whereDate('loan_date', $dateFilter);
                })
                ->when($search, function ($query) use ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('loan_number', 'like', '%' . $search . '%')
                            ->orWhereHas('customer', function ($subQuery) use ($search) {
                                $subQuery->where('full_name', 'like', '%' . $search . '%')
                                    ->orWhere('nic', 'like', '%' . $search . '%');
                            });
                    });
                })
                ->with('customer', 'approval', 'center.branch')
                ->get();

            if ($loans->isEmpty()) {
                return response()->json([
                    'error' => 'No loans found for the selected filters.'
                ], 404);
            }

            $logoPath = public_path('logos/logo.png');
            if (!file_exists($logoPath)) {
                throw new \Exception('Logo file not found');
            }

            $logoData = base64_encode(file_get_contents($logoPath));
            $logoSrc = 'data:image/png;base64,' . $logoData;

            $numberToWords = new NumberToWords();
            $transformer = $numberToWords->getNumberTransformer('en');

            $html = view('pdf.loan-AgreementLending-bulk', [
                'loans' => $loans,
                'dateFilter' => $dateFilter,
                'logoSrc' => $logoSrc,
                'transformer' => $transformer,
            ])->render();

            $filename = 'loan-AgreementLending-bulk-' . ($dateFilter ?: 'all') . '-' . now()->format('Y-m-d') . '.pdf';

            $pdfPath = $this->generatePDFWithTamilFonts($html, $filename);

            return response()->download($pdfPath)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            Log::error('PDF Export Error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function promissoryOrigin()
    {
        return view('documents.promissoryOrigin');
    }

    public function exportSinglePromissoryOrigin(Loan $loan)
    {
        try {
            $loan->load('customer', 'approval', 'center.branch');

            $logoPath = public_path('logos/logo.png');
            if (!file_exists($logoPath)) {
                throw new \Exception('Logo file not found');
            }

            $logoData = base64_encode(file_get_contents($logoPath));
            $logoSrc = 'data:image/png;base64,' . $logoData;

            $numberToWords = new NumberToWords();
            $numberTransformer = $numberToWords->getNumberTransformer('en');
            $amountInWords = ucfirst($numberTransformer->toWords($loan->loan_amount));

            $html = view('pdf.loan-promissoryOrigin', [
                'loan' => $loan,
                'logoSrc' => $logoSrc,
                'amountInWords' => $amountInWords
            ])->render();

            $sanitizedLoanNumber = Str::slug($loan->loan_number, '-');
            $fileName = 'loan-promissory-' . $sanitizedLoanNumber . '-' . now()->format('Y-m-d') . '.pdf';

            $pdfPath = $this->generatePDFWithTamilFonts($html, $fileName);

            return response()->download($pdfPath)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            Log::error('PDF Export Error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function exportFilteredPromissoryOrigin(Request $request)
    {
        try {
            $dateFilter = $request->input('date_filter');
            $search = $request->input('search', '');

            $loans = Loan::query()
                ->when($dateFilter, function ($query) use ($dateFilter) {
                    $query->whereDate('loan_date', $dateFilter);
                })
                ->when($search, function ($query) use ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('loan_number', 'like', '%' . $search . '%')
                            ->orWhereHas('customer', function ($subQuery) use ($search) {
                                $subQuery->where('full_name', 'like', '%' . $search . '%')
                                    ->orWhere('nic', 'like', '%' . $search . '%');
                            });
                    });
                })
                ->with('customer', 'approval', 'center.branch')
                ->get();

            if ($loans->isEmpty()) {
                return response()->json([
                    'error' => 'No loans found for the selected filters.'
                ], 404);
            }

            $logoPath = public_path('logos/logo.png');
            if (!file_exists($logoPath)) {
                throw new \Exception('Logo file not found');
            }

            $logoData = base64_encode(file_get_contents($logoPath));
            $logoSrc = 'data:image/png;base64,' . $logoData;

            $numberToWords = new NumberToWords();
            $transformer = $numberToWords->getNumberTransformer('en');

            $html = view('pdf.loan-promissoryOrigin-bulk', [
                'loans' => $loans,
                'dateFilter' => $dateFilter,
                'logoSrc' => $logoSrc,
                'transformer' => $transformer,
            ])->render();

            $filename = 'loan-promissory-bulk-' . ($dateFilter ?: 'all') . '-' . now()->format('Y-m-d') . '.pdf';

            $pdfPath = $this->generatePDFWithTamilFonts($html, $filename);

            return response()->download($pdfPath)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            Log::error('PDF Export Error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function VoucherTamil()
    {
        return view('documents.voucher');
    }

    public function exportSingleReceipt(Loan $loan)
    {
        try {
            $loan->load('customer', 'approval', 'center.branch');

            // Prepare logo as base64 to embed in the HTML
            $logoPath = public_path('logos/logo.png');
            if (!file_exists($logoPath)) {
                throw new \Exception('Logo file not found');
            }

            $logoData = base64_encode(file_get_contents($logoPath));
            $logoSrc = 'data:image/png;base64,' . $logoData;

            // Convert amount to words
            $numberToWords = new NumberToWords();
            $numberTransformer = $numberToWords->getNumberTransformer('en');
            $amountInWords = ucfirst($numberTransformer->toWords($loan->loan_amount));

            // Render the Blade view with all required data
            $html = view('pdf.loan-voucher', [
                'loan' => $loan,
                'logoSrc' => $logoSrc,
                'amountInWords' => $amountInWords
            ])->render();

            // Generate a safe file name
            $sanitizedLoanNumber = Str::slug($loan->loan_number, '-');
            $fileName = 'loan-voucher-' . $sanitizedLoanNumber . '-' . now()->format('Y-m-d') . '.pdf';

            $pdfPath = $this->generatePDFWithTamilFonts($html, $fileName);

            return response()->download($pdfPath)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            Log::error('PDF Export Error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function VoucherTamilBulk(Request $request)
    {
        try {
            $dateFilter = $request->input('date_filter');
            $search = $request->input('search', '');

            $loans = Loan::query()
                ->when($dateFilter, function ($query) use ($dateFilter) {
                    $query->whereDate('loan_date', $dateFilter);
                })
                ->when($search, function ($query) use ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('loan_number', 'like', '%' . $search . '%')
                            ->orWhereHas('customer', function ($subQuery) use ($search) {
                                $subQuery->where('full_name', 'like', '%' . $search . '%')
                                    ->orWhere('nic', 'like', '%' . $search . '%');
                            });
                    });
                })
                ->with('customer', 'approval', 'center.branch')
                ->get();

            if ($loans->isEmpty()) {
                return response()->json([
                    'error' => 'No loans found for the selected filters.'
                ], 404);
            }

            $logoPath = public_path('logos/logo.png');
            if (!file_exists($logoPath)) {
                throw new \Exception('Logo file not found');
            }

            $logoData = base64_encode(file_get_contents($logoPath));
            $logoSrc = 'data:image/png;base64,' . $logoData;

            $numberToWords = new NumberToWords();
            $transformer = $numberToWords->getNumberTransformer('en');

            $html = view('pdf.loan-voucher-bulk', [
                'loans' => $loans,
                'dateFilter' => $dateFilter,
                'logoSrc' => $logoSrc,
                'transformer' => $transformer,
            ])->render();

            $filename = 'loan-voucher-bulk-' . ($dateFilter ?: 'all') . '-' . now()->format('Y-m-d') . '.pdf';

            $pdfPath = $this->generatePDFWithTamilFonts($html, $filename);

            return response()->download($pdfPath)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            Log::error('PDF Export Error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // CROSS-PLATFORM PDF GENERATION METHOD
    private function generatePDFWithTamilFonts($html, $fileName)
    {
        $pdfPath = storage_path('app/public/' . $fileName);

        try {
            $browsershot = Browsershot::html($html)
                ->noSandbox()
                ->format('A4')
                ->margins(15, 15, 15, 15)
                ->emulateMedia('print')
                ->showBackground()
                ->waitUntilNetworkIdle(500)
                ->setDelay(2000)
                ->timeout(180);

            // Cross-platform configuration
            $this->configureBrowsershot($browsershot);

            $browsershot->save($pdfPath);
            return $pdfPath;

        } catch (\Exception $e) {
            Log::error('Browsershot PDF Error: ' . $e->getMessage());
            Log::error('System: ' . PHP_OS_FAMILY);

            // Fallback to DomPDF
            return $this->generatePDFAlternative($html, $fileName);
        }
    }


private function configureBrowsershot($browsershot)
{
    // Node.js & NPM binaries
    $nodeBinary = '/www/server/nvm/versions/node/v22.20.0/bin/node';
    $npmBinary  = '/www/server/nvm/versions/node/v22.20.0/bin/npm';

    if (is_executable($nodeBinary)) {
        $browsershot->setNodeBinary($nodeBinary);
        Log::info("✅ Using Node.js: {$nodeBinary}");
    } else {
        Log::error("❌ Node.js binary not found at {$nodeBinary}");
    }

    if (is_executable($npmBinary)) {
        $browsershot->setNpmBinary($npmBinary);
        Log::info("✅ Using NPM: {$npmBinary}");
    }

    // Manually set Puppeteer Chrome Headless Shell path
    $chromePath = '/var/www/.cache/puppeteer/chrome-headless-shell/linux-140.0.7339.207/chrome-headless-shell-linux64/chrome-headless-shell';
    if (file_exists($chromePath) && is_executable($chromePath)) {
        $browsershot->setChromePath($chromePath);
        Log::info("✅ Using manually installed Chrome Headless Shell: {$chromePath}");
    } else {
        Log::error("❌ Chrome binary not found at expected path: {$chromePath}");
    }

    // Safe user-data directory
    $userDataDir = sys_get_temp_dir() . '/chrome-user-data';
    if (!file_exists($userDataDir)) {
        mkdir($userDataDir, 0777, true);
    } elseif (substr(sprintf('%o', fileperms($userDataDir)), -3) !== '777') {
        chmod($userDataDir, 0777);
    }

    // Chrome options
    $browsershot->setOption('args', [
        '--no-sandbox',
        '--disable-setuid-sandbox',
        '--disable-dev-shm-usage',
        '--disable-gpu',
        '--headless=new',
        "--user-data-dir={$userDataDir}",
        '--single-process',
        '--disable-software-rasterizer',
        '--disable-extensions',
        '--disable-background-timer-throttling',
        '--disable-backgrounding-occluded-windows',
        '--disable-renderer-backgrounding',
        '--disable-crash-reporter',
        '--no-crash-upload',
        '--lang=ta-IN',
        '--font-render-hinting=medium',
        '--enable-font-antialiasing',
        '--disable-features=VizDisplayCompositor',
    ]);

    return $browsershot;
}







    // Fallback PDF generation using DomPDF
    private function generatePDFAlternative($html, $fileName)
    {
        try {
            // Requires: composer require barryvdh/laravel-dompdf
            $pdf = app('dompdf.wrapper');

            // Load Tamil fonts for DomPDF if available
            $this->loadTamilFonts($pdf->getDomPDF());

            $pdf->loadHTML($html);
            $pdf->setPaper('A4', 'portrait');
            $pdf->setWarnings(false);

            $pdfPath = storage_path('app/public/' . $fileName);
            $pdf->save($pdfPath);

            Log::info('PDF generated using DomPDF fallback');
            return $pdfPath;

        } catch (\Exception $e) {
            Log::error('DomPDF Error: ' . $e->getMessage());
            throw new \Exception('PDF generation failed: ' . $e->getMessage());
        }
    }

    private function loadTamilFonts($dompdf)
    {
        $fontDir = storage_path('fonts/');

        // Ensure fonts directory exists
        if (!is_dir($fontDir)) {
            mkdir($fontDir, 0755, true);
        }

        // Check if Tamil fonts exist
        $tamilFonts = [
            'NotoSansTamil-Regular.ttf' => 'normal',
            'NotoSansTamil-Bold.ttf' => 'bold'
        ];

        foreach ($tamilFonts as $fontFile => $weight) {
            $fontPath = $fontDir . $fontFile;

            // Download font if not exists
            if (!file_exists($fontPath)) {
                $this->downloadTamilFont($fontFile, $fontPath);
            }

            if (file_exists($fontPath)) {
                try {
                    // Register the font
                    $fontMetrics = $dompdf->getFontMetrics();

                    $fontMetrics->registerFont([
                        'family' => 'NotoSansTamil',
                        'style' => 'normal',
                        'weight' => $weight
                    ], $fontPath);

                    Log::info("Tamil font loaded successfully: " . $fontFile);
                } catch (\Exception $e) {
                    Log::error("Failed to load font {$fontFile}: " . $e->getMessage());
                }
            } else {
                Log::warning("Tamil font not found: " . $fontPath);
            }
        }
    }

    private function downloadTamilFont($fontFile, $fontPath)
    {
        try {
            $urls = [
                'NotoSansTamil-Regular.ttf' => 'https://github.com/googlefonts/noto-fonts/raw/main/hinted/ttf/NotoSansTamil/NotoSansTamil-Regular.ttf',
                'NotoSansTamil-Bold.ttf' => 'https://github.com/googlefonts/noto-fonts/raw/main/hinted/ttf/NotoSansTamil/NotoSansTamil-Bold.ttf'
            ];

            if (isset($urls[$fontFile])) {
                $fontData = file_get_contents($urls[$fontFile]);
                if ($fontData !== false) {
                    file_put_contents($fontPath, $fontData);
                    Log::info("Downloaded Tamil font: " . $fontFile);
                }
            }
        } catch (\Exception $e) {
            Log::error("Failed to download font {$fontFile}: " . $e->getMessage());
        }
    }
}
