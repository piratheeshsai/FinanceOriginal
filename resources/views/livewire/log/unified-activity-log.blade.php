<div class="container-fluid">
    <div class="card shadow-lg">
        <!-- Card Header with Filters -->
        <div class="card-header bg-light py-3">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="card-title mb-0">
                        @if($subject)
                            {{ class_basename(get_class($subject)) }} Activity Log: {{ $subject->id }}
                        @else
                            System Activity Log
                        @endif
                    </h5>
                </div>
                <div class="col-md-6 text-end">
                    <button wire:click="clearFilters" class="btn btn-outline-secondary btn-sm me-2">
                        <i class="bi bi-arrow-clockwise"></i> Reset Filters
                    </button>
                </div>
            </div>

            <!-- Filter Controls -->
            <div class="row mt-3 g-2">
                <!-- Search Input -->
                <div class="col-md-3">
                    <input
                        type="text"
                        wire:model.live.debounce.500ms="searchQuery"
                        placeholder="Search logs..."
                        class="form-control form-control-sm"
                    >
                </div>

                <!-- Event Type Filter -->
                <div class="col-md-2">
                    <select wire:model.live="filterType" class="form-select form-select-sm">
                        <option value="all">All Events</option>
                        <option value="created">Created</option>
                        <option value="updated">Updated</option>
                        <option value="deleted">Deleted</option>
                    </select>
                </div>

                <!-- Model Type Filter (if no specific subject) -->
                @if(!$subject)
                    <div class="col-md-2">
                        <select wire:model.live="modelFilter" class="form-select form-select-sm">
                            @foreach($modelsList as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <!-- Date Range Filters -->
                <div class="col-md-2">
                    <input
                        type="date"
                        wire:model.live="dateFrom"
                        class="form-control form-control-sm"
                        placeholder="From Date"
                    >
                </div>
                <div class="col-md-2">
                    <input
                        type="date"
                        wire:model.live="dateTo"
                        class="form-control form-control-sm"
                        placeholder="To Date"
                    >
                </div>
            </div>
        </div>

        <!-- Activity Log List -->
        <div class="card-body p-0">
            <div class="list-group list-group-flush">
                @forelse ($activities as $activity)
                    <div
                        wire:key="{{ $activity->id }}"
                        class="list-group-item list-group-item-action"
                        wire:click="viewDetails({{ $activity->id }})"
                    >
                        <div class="d-flex w-100 justify-content-between">
                            <div class="d-flex align-items-center">
                                <!-- Model Icon -->
                                <div class="me-3">
                                    <span class="badge {{ $this->getModelIconClass($activity->subject_type) }} p-2">
                                        <i class="bi bi-activity"></i>
                                    </span>
                                </div>

                                <!-- Activity Details -->
                                <div>
                                    <h6 class="mb-1">{{ $activity->description }}</h6>
                                    <small class="text-muted">
                                        {{ $activity->created_at->diffForHumans() }}
                                        by {{ $activity->causer->name ?? 'System' }}
                                    </small>

                                    @if($activity->subject_type)
                                        <span class="badge bg-secondary ms-2">
                                            {{ $this->getModelDisplayName($activity->subject_type) }} #{{ $activity->subject_id }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Event Type Badge -->
                            <button
        wire:click="viewDetails({{ $activity->id }})"
        class="btn btn-sm btn-outline-primary"
    >
        View Details
    </button>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4">
                        <i class="bi bi-inbox fs-1 text-muted"></i>
                        <p class="text-muted mt-2">No activity logs found</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Pagination -->
        <div class="card-footer">
            {{ $activities->links('livewire::bootstrap') }}
           
        </div>
    </div>


   <!-- Updated Modal Section -->
@if($showDetails && $selectedActivity)
<div
    class="modal fade show d-block"
    tabindex="-1"
    wire:click.self="closeDetails"
>
    <div
        class="modal-dialog modal-lg"
        wire:click.stop
    >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Activity Details</h5>
                <button
                    type="button"
                    class="btn-close"
                    wire:click="closeDetails"
                ></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <strong>Description:</strong>
                        <p>{{ $selectedActivity->description }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong>Performed By:</strong>
                        <p>{{ $selectedActivity->causer->name ?? 'System' }}</p>
                    </div>
                </div>

                @if($selectedActivity->event == 'updated' && isset($selectedActivity->properties['old']))
                    <h6 class="mt-3">Changes:</h6>
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Field</th>
                                <th>Old Value</th>
                                <th>New Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(collect($selectedActivity->properties['attributes'])
                                ->filter(fn($value, $key) =>
                                    isset($selectedActivity->properties['old'][$key]) &&
                                    $selectedActivity->properties['old'][$key] != $value)
                                as $key => $value)
                                <tr>
                                    <td>{{ ucfirst(str_replace('_', ' ', $key)) }}</td>
                                    <td class="text-danger">
                                        {{ $this->formatValue($selectedActivity->properties['old'][$key], $key) }}
                                    </td>
                                    <td class="text-success">
                                        {{ $this->formatValue($value, $key) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</div>
<div class="modal-backdrop fade show" wire:click="closeDetails"></div>
@endif
</div>
