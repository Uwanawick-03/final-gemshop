<?php

namespace App\Http\Controllers;

use App\Models\TourGuide;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class GuideListingController extends Controller
{
    /**
     * Display the main guide listing dashboard
     */
    public function index()
    {
        // Get summary statistics
        $summary = $this->getGuideSummary();
        
        // Get guide alerts
        $guideAlerts = $this->getGuideAlerts();
        
        // Get top performing guides
        $topPerformers = $this->getTopPerformers();
        
        // Get guide distribution
        $guideDistribution = $this->getGuideDistribution();
        
        // Get language distribution
        $languageDistribution = $this->getLanguageDistribution();
        
        // Get service area distribution
        $serviceAreaDistribution = $this->getServiceAreaDistribution();
        
        // Get employment status summary
        $employmentSummary = $this->getEmploymentSummary();

        return view('reports.guide-listing.index', compact(
            'summary',
            'guideAlerts',
            'topPerformers',
            'guideDistribution',
            'languageDistribution',
            'serviceAreaDistribution',
            'employmentSummary'
        ));
    }

    /**
     * Detailed guide listing with filters
     */
    public function detailed(Request $request)
    {
        $query = TourGuide::query();

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('guide_code', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('country', 'like', "%{$search}%");
            });
        }

        if ($request->filled('language')) {
            $query->whereJsonContains('languages', $request->language);
        }

        if ($request->filled('service_area')) {
            $query->whereJsonContains('service_areas', $request->service_area);
        }

        if ($request->filled('employment_status')) {
            $query->where('employment_status', $request->employment_status);
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active == '1');
        }

        if ($request->filled('license_status')) {
            $this->applyLicenseFilter($query, $request->license_status);
        }

        if ($request->filled('joined_from')) {
            $query->where('joined_date', '>=', $request->joined_from);
        }

        if ($request->filled('joined_to')) {
            $query->where('joined_date', '<=', $request->joined_to);
        }

        if ($request->filled('daily_rate_min')) {
            $query->where('daily_rate', '>=', $request->daily_rate_min);
        }

        if ($request->filled('daily_rate_max')) {
            $query->where('daily_rate', '<=', $request->daily_rate_max);
        }

        // Apply sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $guides = $query->paginate(50);

        // Get filter options
        $languages = $this->getUniqueLanguages();
        $serviceAreas = $this->getUniqueServiceAreas();

        return view('reports.guide-listing.detailed', compact(
            'guides',
            'languages',
            'serviceAreas'
        ));
    }

    /**
     * Guide performance report
     */
    public function performance(Request $request)
    {
        $query = TourGuide::query();

        // Apply filters
        if ($request->filled('employment_status')) {
            $query->where('employment_status', $request->employment_status);
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active == '1');
        }

        if ($request->filled('language')) {
            $query->whereJsonContains('languages', $request->language);
        }

        if ($request->filled('service_area')) {
            $query->whereJsonContains('service_areas', $request->service_area);
        }

        $guides = $query->orderBy('joined_date', 'desc')->get();

        // Calculate performance metrics for each guide
        $performanceData = $guides->map(function ($guide) {
            return [
                'guide' => $guide,
                'metrics' => $this->calculateGuideMetrics($guide)
            ];
        });

        // Sort by performance score
        $performanceData = $performanceData->sortByDesc(function ($item) {
            return $item['metrics']['performance_score'];
        });

        $languages = $this->getUniqueLanguages();
        $serviceAreas = $this->getUniqueServiceAreas();

        return view('reports.guide-listing.performance', compact(
            'performanceData',
            'languages',
            'serviceAreas'
        ));
    }

    /**
     * License and compliance report
     */
    public function compliance(Request $request)
    {
        $query = TourGuide::query();

        if ($request->filled('license_status')) {
            $this->applyLicenseFilter($query, $request->license_status);
        }

        if ($request->filled('employment_status')) {
            $query->where('employment_status', $request->employment_status);
        }

        $guides = $query->orderBy('license_expiry', 'asc')->get();

        // Group guides by license status
        $licenseGroups = [
            'valid' => $guides->filter(function ($guide) {
                return $guide->license_status === 'Valid';
            }),
            'expiring_soon' => $guides->filter(function ($guide) {
                return $guide->license_status === 'Expiring Soon';
            }),
            'expired' => $guides->filter(function ($guide) {
                return $guide->license_status === 'Expired';
            }),
            'no_license' => $guides->filter(function ($guide) {
                return $guide->license_status === 'No License';
            })
        ];

        return view('reports.guide-listing.compliance', compact(
            'guides',
            'licenseGroups'
        ));
    }

    /**
     * Export guide listing to PDF
     */
    public function exportPdf(Request $request)
    {
        $reportType = $request->get('type', 'summary');
        
        switch ($reportType) {
            case 'detailed':
                $guides = TourGuide::orderBy('created_at', 'desc')->get();
                $pdf = \PDF::loadView('reports.guide-listing.pdf.detailed', compact('guides'));
                break;
            case 'performance':
                $guides = TourGuide::orderBy('joined_date', 'desc')->get();
                $performanceData = $guides->map(function ($guide) {
                    return [
                        'guide' => $guide,
                        'metrics' => $this->calculateGuideMetrics($guide)
                    ];
                });
                $pdf = \PDF::loadView('reports.guide-listing.pdf.performance', compact('performanceData'));
                break;
            case 'compliance':
                $guides = TourGuide::orderBy('license_expiry', 'asc')->get();
                $pdf = \PDF::loadView('reports.guide-listing.pdf.compliance', compact('guides'));
                break;
            default:
                $summary = $this->getGuideSummary();
                $guideAlerts = $this->getGuideAlerts();
                $pdf = \PDF::loadView('reports.guide-listing.pdf.summary', compact('summary', 'guideAlerts'));
        }

        return $pdf->download("guide-listing-{$reportType}-" . now()->format('Y-m-d') . ".pdf");
    }

    /**
     * Export guide listing to Excel
     */
    public function exportExcel(Request $request)
    {
        $reportType = $request->get('type', 'summary');
        
        // This would typically use Laravel Excel package
        // For now, we'll return a CSV
        return $this->exportCsv($request);
    }

    /**
     * Export guide listing to CSV
     */
    public function exportCsv(Request $request)
    {
        $reportType = $request->get('type', 'summary');
        
        $filename = "guide-listing-{$reportType}-" . now()->format('Y-m-d') . ".csv";
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($reportType) {
            $handle = fopen('php://output', 'w');
            
            switch ($reportType) {
                case 'detailed':
                    fputcsv($handle, [
                        'Guide Code', 'Name', 'Email', 'Phone', 'City', 'Country',
                        'Languages', 'Service Areas', 'Employment Status', 'Daily Rate',
                        'License Status', 'Joined Date', 'Is Active'
                    ]);
                    TourGuide::chunk(100, function($guides) use ($handle) {
                        foreach ($guides as $guide) {
                            fputcsv($handle, [
                                $guide->guide_code,
                                $guide->full_name,
                                $guide->email ?? '',
                                $guide->phone,
                                $guide->city ?? '',
                                $guide->country ?? '',
                                $guide->languages_list,
                                $guide->service_areas_list,
                                $guide->employment_status,
                                $guide->daily_rate ?? '',
                                $guide->license_status,
                                $guide->joined_date ? $guide->joined_date->format('Y-m-d') : '',
                                $guide->is_active ? 'Yes' : 'No'
                            ]);
                        }
                    });
                    break;
                default:
                    fputcsv($handle, ['Guide Code', 'Name', 'Employment Status', 'Languages', 'Service Areas', 'Daily Rate']);
                    TourGuide::chunk(100, function($guides) use ($handle) {
                        foreach ($guides as $guide) {
                            fputcsv($handle, [
                                $guide->guide_code,
                                $guide->full_name,
                                $guide->employment_status,
                                $guide->languages_list,
                                $guide->service_areas_list,
                                $guide->daily_rate ?? ''
                            ]);
                        }
                    });
            }
            
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get guide summary statistics
     */
    private function getGuideSummary()
    {
        $totalGuides = TourGuide::count();
        $activeGuides = TourGuide::where('is_active', true)->count();
        $activeEmployed = TourGuide::where('employment_status', 'active')->count();
        $onLeave = TourGuide::where('employment_status', 'on_leave')->count();
        $terminated = TourGuide::where('employment_status', 'terminated')->count();
        
        // License statistics
        $validLicenses = TourGuide::whereNotNull('license_expiry')
            ->where('license_expiry', '>', now())
            ->count();
        $expiringLicenses = TourGuide::whereNotNull('license_expiry')
            ->where('license_expiry', '>', now())
            ->where('license_expiry', '<=', now()->addDays(30))
            ->count();
        $expiredLicenses = TourGuide::whereNotNull('license_expiry')
            ->where('license_expiry', '<=', now())
            ->count();
        $noLicenses = TourGuide::whereNull('license_expiry')->count();

        // Average daily rate
        $avgDailyRate = TourGuide::whereNotNull('daily_rate')->avg('daily_rate');

        // New guides this month
        $newGuidesThisMonth = TourGuide::whereMonth('joined_date', now()->month)
            ->whereYear('joined_date', now()->year)
            ->count();

        return [
            'total_guides' => $totalGuides,
            'active_guides' => $activeGuides,
            'active_employed' => $activeEmployed,
            'on_leave' => $onLeave,
            'terminated' => $terminated,
            'valid_licenses' => $validLicenses,
            'expiring_licenses' => $expiringLicenses,
            'expired_licenses' => $expiredLicenses,
            'no_licenses' => $noLicenses,
            'avg_daily_rate' => $avgDailyRate,
            'new_guides_this_month' => $newGuidesThisMonth
        ];
    }

    /**
     * Get guide alerts
     */
    private function getGuideAlerts()
    {
        return [
            'expiring_licenses' => TourGuide::whereNotNull('license_expiry')
                ->where('license_expiry', '>', now())
                ->where('license_expiry', '<=', now()->addDays(30))
                ->where('is_active', true)
                ->orderBy('license_expiry', 'asc')
                ->limit(10)
                ->get(),
            'expired_licenses' => TourGuide::whereNotNull('license_expiry')
                ->where('license_expiry', '<=', now())
                ->where('is_active', true)
                ->orderBy('license_expiry', 'desc')
                ->limit(10)
                ->get(),
            'no_licenses' => TourGuide::whereNull('license_expiry')
                ->where('is_active', true)
                ->limit(10)
                ->get(),
            'on_leave' => TourGuide::where('employment_status', 'on_leave')
                ->where('is_active', true)
                ->limit(10)
                ->get()
        ];
    }

    /**
     * Get top performing guides
     */
    private function getTopPerformers()
    {
        return TourGuide::where('is_active', true)
            ->where('employment_status', 'active')
            ->orderBy('joined_date', 'asc') // Longer service = better performance for now
            ->limit(10)
            ->get()
            ->map(function ($guide) {
                $guide->performance_metrics = $this->calculateGuideMetrics($guide);
                return $guide;
            });
    }

    /**
     * Get guide distribution by location
     */
    private function getGuideDistribution()
    {
        return TourGuide::select('city', 'country', DB::raw('count(*) as count'))
            ->whereNotNull('city')
            ->groupBy('city', 'country')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();
    }

    /**
     * Get language distribution
     */
    private function getLanguageDistribution()
    {
        $languages = collect();
        
        TourGuide::whereNotNull('languages')->get()->each(function ($guide) use ($languages) {
            if (is_array($guide->languages)) {
                foreach ($guide->languages as $language) {
                    $existing = $languages->firstWhere('language', $language);
                    if ($existing) {
                        $existing['count']++;
                    } else {
                        $languages->push(['language' => $language, 'count' => 1]);
                    }
                }
            }
        });

        return $languages->sortByDesc('count')->take(10);
    }

    /**
     * Get service area distribution
     */
    private function getServiceAreaDistribution()
    {
        $areas = collect();
        
        TourGuide::whereNotNull('service_areas')->get()->each(function ($guide) use ($areas) {
            if (is_array($guide->service_areas)) {
                foreach ($guide->service_areas as $area) {
                    $existing = $areas->firstWhere('area', $area);
                    if ($existing) {
                        $existing['count']++;
                    } else {
                        $areas->push(['area' => $area, 'count' => 1]);
                    }
                }
            }
        });

        return $areas->sortByDesc('count')->take(10);
    }

    /**
     * Get employment status summary
     */
    private function getEmploymentSummary()
    {
        return [
            'active' => TourGuide::where('employment_status', 'active')->count(),
            'inactive' => TourGuide::where('employment_status', 'inactive')->count(),
            'terminated' => TourGuide::where('employment_status', 'terminated')->count(),
            'on_leave' => TourGuide::where('employment_status', 'on_leave')->count()
        ];
    }

    /**
     * Apply license filter to query
     */
    private function applyLicenseFilter($query, $status)
    {
        switch ($status) {
            case 'valid':
                $query->whereNotNull('license_expiry')
                      ->where('license_expiry', '>', now());
                break;
            case 'expiring_soon':
                $query->whereNotNull('license_expiry')
                      ->where('license_expiry', '>', now())
                      ->where('license_expiry', '<=', now()->addDays(30));
                break;
            case 'expired':
                $query->whereNotNull('license_expiry')
                      ->where('license_expiry', '<=', now());
                break;
            case 'no_license':
                $query->whereNull('license_expiry');
                break;
        }
    }

    /**
     * Get unique languages
     */
    private function getUniqueLanguages()
    {
        return TourGuide::select('languages')
            ->whereNotNull('languages')
            ->pluck('languages')
            ->flatten()
            ->filter()
            ->unique()
            ->sort()
            ->values();
    }

    /**
     * Get unique service areas
     */
    private function getUniqueServiceAreas()
    {
        return TourGuide::select('service_areas')
            ->whereNotNull('service_areas')
            ->pluck('service_areas')
            ->flatten()
            ->filter()
            ->unique()
            ->sort()
            ->values();
    }

    /**
     * Calculate guide performance metrics
     */
    private function calculateGuideMetrics($guide)
    {
        $metrics = [
            'years_of_service' => $guide->years_of_service,
            'days_in_system' => $guide->days_in_system,
            'performance_rating' => $guide->performance_rating,
            'total_tours_conducted' => $guide->total_tours_conducted,
            'total_tours_in_progress' => $guide->total_tours_in_progress,
            'total_tours_pending' => $guide->total_tours_pending,
            'total_earnings' => $guide->total_earnings,
            'average_tour_rating' => $guide->average_tour_rating,
            'license_status' => $guide->license_status,
            'is_active' => $guide->is_active,
            'employment_status' => $guide->employment_status
        ];

        // Calculate performance score (0-100)
        $score = 0;
        
        // Years of service (max 25 points)
        $score += min($metrics['years_of_service'] * 5, 25);
        
        // License status (max 20 points)
        if ($metrics['license_status'] === 'Valid') {
            $score += 20;
        } elseif ($metrics['license_status'] === 'Expiring Soon') {
            $score += 10;
        }
        
        // Employment status (max 15 points)
        if ($metrics['employment_status'] === 'active') {
            $score += 15;
        } elseif ($metrics['employment_status'] === 'on_leave') {
            $score += 10;
        }
        
        // Activity status (max 10 points)
        if ($metrics['is_active']) {
            $score += 10;
        }
        
        // Tour performance (max 30 points) - mock data for now
        $tourScore = min($metrics['total_tours_conducted'] * 2, 30);
        $score += $tourScore;
        
        $metrics['performance_score'] = min($score, 100);
        
        return $metrics;
    }
}
