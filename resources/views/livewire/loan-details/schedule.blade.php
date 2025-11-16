<div class=" card card-body px-0 pt-0 pb-2">
    <div class="table-responsive p-0">
        <table class="table align-items-center mb-0">
            <thead>
                <tr>

                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">#
                    </th>

                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                        Date
                    </th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                    Description</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                        Principal</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                        Interest</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                        Due</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                            Paid</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                pending Due</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                    total Due</th>

                </tr>
            </thead>
            <tbody>
                @php $count = 1; @endphp
                @foreach($schedules as $schedule)
                <tr class="{{ $schedule->description === 'Payment Received +' ? 'font-weight-bolder bg-light' : '' }}">


                    <td class="text-center">
                        @if($schedule->description !== 'Payment Received +')
                            {{ $count++ }}
                        @endif
                    </td>
                        <td> <span
                            class="text-secondary text-xs font-weight-bold">{{ \Carbon\Carbon::parse($schedule->date)->format('m/d/Y') }}</span></td>

                        <td> <p class="text-xs font-weight-bold mb-0">{{ $schedule->description }}</p></td>
                        <td> <p class="text-xs font-weight-bold mb-0">{{ $schedule->principal }} </p></td>
                        <td><p class="text-xs font-weight-bold mb-0"> {{ $schedule->interest }}</p></td>
                        <td> <p class="text-xs font-weight-bold mb-0">{{ $schedule->due }} </p> </td>
                        <td> <p class="text-xs font-weight-bold mb-0">{{ $schedule->paid }} </p> </td>
                        <td><p class="text-xs font-weight-bold mb-0">{{ $schedule->pending_due }} </p></td>
                        <td><p class="text-xs font-weight-bold mb-0">{{ $schedule->total_due }}</p></td>
                    </tr>
                @endforeach
            </tbody>

            <tfoot>
                <tr class="font-weight-bold">
                    <td colspan="6">Total Due</td>
                    <td>{{ number_format($totalPaid, 2) }}</td>
                                                                         
                    <td>{{ number_format($totalDue, 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>


</div>
