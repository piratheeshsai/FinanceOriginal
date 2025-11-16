<?php

namespace App\Livewire\Settings;

use App\Models\City;
use Livewire\Component;
use Livewire\WithPagination;

class Cities extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $name;
    public $city_id;
    public $isOpen = false;
    public $search = '';

    // Listen for the deleteCity event
    protected $listeners = ['deleteCity' => 'deleteCity'];

    protected $rules = [
        'name' => 'required|min:2|max:100|unique:cities,name',
    ];

    protected $messages = [
        'name.required' => 'The city name is required.',
        'name.min' => 'The city name must be at least 2 characters.',
        'name.max' => 'The city name cannot be more than 100 characters.',
        'name.unique' => 'This city name already exists.',
    ];

    public function render()
    {
        $cities = City::when($this->search, function($query) {
                    return $query->where('name', 'like', '%' . $this->search . '%');
                })
                ->orderBy('name')
                ->paginate(50);

        return view('livewire.settings.cities', compact('cities'));
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetValidation();
    }

    public function resetInputFields()
    {
        $this->name = '';
        $this->city_id = null;
        $this->resetValidation();
    }

    public function store()
    {
        if ($this->city_id) {
            $this->rules['name'] = 'required|min:2|max:100|unique:cities,name,' . $this->city_id;
        }

        $this->validate();

        City::updateOrCreate(
            ['id' => $this->city_id],
            ['name' => $this->name]
        );

        $message = $this->city_id ? 'City updated successfully.' : 'City created successfully.';

        $this->closeModal();
        $this->resetInputFields();

        // Use dispatch to send success message
        $this->dispatch('show-success-alert', message: 'City created successfully.');
    }

    public function edit($id)
    {
        $city = City::findOrFail($id);
        $this->city_id = $id;
        $this->name = $city->name;

        $this->openModal();
    }

    // New method to handle the deleteCity event
    public function deleteCity($cityId)
    {
        $city = City::findOrFail($cityId);
        $city->delete();

        $this->dispatch('show-success-alert', message: 'City deleted successfully.');
    }
    public function updatingSearch()
    {
        $this->resetPage();
    }
}
