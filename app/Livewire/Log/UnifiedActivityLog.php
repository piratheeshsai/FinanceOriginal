<?php

namespace App\Livewire\Log;

use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithCursorPagination;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

class UnifiedActivityLog extends Component
{
    use WithPagination;


    // Core filtering properties
    public $subject = null;
    public $subjectId = null;
    public $subjectType = null;

    // UI and interaction states
    public $showDetails = false;
    public $selectedActivity = null;

    // Filter properties
    public $filterType = 'all';
    public $modelFilter = 'all';
    public $searchQuery = '';
    public $dateFrom = null;
    public $dateTo = null;
    public $userFilter = null;

    // Configuration
    protected $queryString = [
        'filterType',
        'modelFilter',
        'searchQuery',
        'dateFrom',
        'dateTo'
    ];

    // Maximum number of logs to display
    protected $maxLogEntries = 1000;

    public function mount($subjectId = null, $subjectType = null)
    {
        $this->subjectId = $subjectId;
        $this->subjectType = $subjectType;

        // Load specific subject if both ID and type are provided
        if ($subjectId && $subjectType) {
            try {
                $modelClass = $this->sanitizeModelClass($subjectType);
                $this->subject = $modelClass::findOrFail($subjectId);
            } catch (\Exception $e) {
                // Log error or handle gracefully
                session()->flash('error', 'Unable to load subject');
            }
        }
    }

    // Sanitize and validate model class
    protected function sanitizeModelClass($modelType)
    {
        // Ensure the model class exists and is in the correct namespace
        $allowedModels = [
            'Account' => 'App\\Models\\Account',
            'Collection' => 'App\\Models\\Collection',
            'Loan' => 'App\\Models\\Loan',
            'Transfer' => 'App\\Models\\Transfer',
            'Transaction' => 'App\\Models\\Transaction',
        ];

        return $allowedModels[$modelType] ?? $modelType;
    }

    // Toggle activity details view
    public function viewDetails($activityId)
    {
        $this->selectedActivity = Activity::findOrFail($activityId);
        $this->showDetails = true;
    }

    // Close activity details
    public function closeDetails()
    {
        $this->showDetails = false;
        $this->selectedActivity = null;
    }

    // Reset all filters
    public function clearFilters()
    {
        $this->reset([
            'filterType',
            'modelFilter',
            'searchQuery',
            'dateFrom',
            'dateTo',
            'userFilter'
        ]);
    }

    // Format values for display
    public function formatValue($value, $key)
    {
        // Numeric formatting for financial fields
        if (is_numeric($value) && preg_match('/amount|balance|principal|interest/i', $key)) {
            return number_format($value, 2);
        }

        // Date formatting
        if ($value instanceof \DateTime || (is_string($value) && preg_match('/^\d{4}-\d{2}-\d{2}/', $value))) {
            try {
                return Carbon::parse($value)->format('M d, Y');
            } catch (\Exception $e) {
                return $value;
            }
        }

        return $value;
    }

    // Get icon classes for different model types
    public function getModelIconClass($modelType)
    {
        $icons = [
            'App\\Models\\Account' => 'text-purple-600 bg-purple-100',
            'App\\Models\\Collection' => 'text-blue-600 bg-blue-100',
            'App\\Models\\Loan' => 'text-green-600 bg-green-100',
            'App\\Models\\Transfer' => 'text-yellow-600 bg-yellow-100',
            'App\\Models\\Transaction' => 'text-red-600 bg-red-100',
        ];

        return $icons[$modelType] ?? 'text-gray-600 bg-gray-100';
    }

    // Get display name for model types
    public function getModelDisplayName($modelType)
    {
        $names = [
            'App\\Models\\Account' => 'Account',
            'App\\Models\\Collection' => 'Collection',
            'App\\Models\\Loan' => 'Loan',
            'App\\Models\\Transfer' => 'Transfer',
            'App\\Models\\Transaction' => 'Transaction',
        ];

        return $names[$modelType] ?? class_basename($modelType);
    }

    // Get list of filterable models
    public function getModelsList()
    {
        return [
            'all' => 'All Models',
            'App\\Models\\Account' => 'Accounts',
            'App\\Models\\Collection' => 'Collections',
            'App\\Models\\Loan' => 'Loans',
            'App\\Models\\Transfer' => 'Transfers',
            'App\\Models\\Transaction' => 'Transactions',
        ];
    }

    public function render()
    {
        // Build the base query with optimized filtering
        $activitiesQuery = Activity::query()
            // Filter by subject if specific subject is selected
            ->when($this->subject, function($query) {
                return $query->where('subject_type', get_class($this->subject))
                             ->where('subject_id', $this->subject->id);
            })
            // Apply model type filter when not viewing a specific subject
            ->when($this->modelFilter !== 'all' && !$this->subject, function($query) {
                return $query->where('subject_type', $this->modelFilter);
            })
            // Event type filter
            ->when($this->filterType !== 'all', function($query) {
                return $query->where('event', $this->filterType);
            })
            // Search across description and properties
            ->when($this->searchQuery, function($query) {
                return $query->where(function($subQuery) {
                    $subQuery->where('description', 'like', "%{$this->searchQuery}%")
                             ->orWhere('properties', 'like', "%{$this->searchQuery}%");
                });
            })
            // Date range filters
            ->when($this->dateFrom, function($query) {
                return $query->where('created_at', '>=', Carbon::parse($this->dateFrom));
            })
            ->when($this->dateTo, function($query) {
                return $query->where('created_at', '<=', Carbon::parse($this->dateTo));
            })
            // User filter
            ->when($this->userFilter, function($query) {
                return $query->where('causer_id', $this->userFilter);
            })
            // Eager load causer to reduce N+1 queries
            ->with('causer')
            // Order by most recent first
            ->latest()
            // Limit to prevent excessive loading
            ->limit($this->maxLogEntries);

        // Use cursor pagination for better performance
        $activities = $activitiesQuery->Paginate(50);

        return view('livewire.log.unified-activity-log', [
            'activities' => $activities,
            'modelsList' => $this->getModelsList()
        ]);
    }
}
