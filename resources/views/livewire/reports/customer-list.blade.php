<div>
    <div class="d-flex justify-content-between mb-3">
        <h4>Customer Report</h4>
        <div>
            <input type="text" wire:model="search" placeholder="Search by Name..." class="form-control d-inline-block w-25">
            <button wire:click="exportPDF" class="btn btn-danger">Export PDF</button>
            <button wire:click="exportExcel" class="btn btn-success">Export Excel</button>
        </div>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th wire:click="sortBy('name')">Name</th>
                <th wire:click="sortBy('email')">Email</th>
                <th wire:click="sortBy('phone')">Phone</th>
                <th wire:click="sortBy('created_at')">Registered Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($customers as $customer)
                <tr>
                    <td>{{ $customer->name }}</td>
                    <td>{{ $customer->email }}</td>
                    <td>{{ $customer->customer_phone }}</td>
                    <td>{{ $customer->created_at->format('Y-m-d') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $customers->links() }}
</div>

