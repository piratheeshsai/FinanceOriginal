<div>
    <div class="d-flex justify-content-between">
        <h5>Financial Summary</h5>
        <div>
            <input type="date" wire:model="startDate">
            <input type="date" wire:model="endDate">
            <button wire:click="render" class="btn btn-primary">Filter</button>
        </div>
    </div>

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Date</th>
                <th>Description</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $row)
                <tr>
                    <td>{{ $row->created_at }}</td>
                    <td>{{ $row->description }}</td>
                    <td>{{ number_format($row->amount, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
