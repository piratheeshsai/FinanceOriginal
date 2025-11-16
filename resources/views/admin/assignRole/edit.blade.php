{{-- @extends('layouts.app')

@section('breadcrumb')
    Assign Role
@endsection



@section('page-title')
    users
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection


@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card-content">
                    <div class="card mb-4">
                        <form action="{{ route('assign.update',$selectedUser->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <select disabled name="user_id" class="form-select form-select-lg mb-3 mt-3"
                                aria-label="Default select example">
                                <option value=""> Select User</option>
                                @if ($users->count() > 0)
                                    @foreach ($users as $user)
                                        <option @if ($user->id == $selectedUser->id) selected @endif
                                            value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                @endif
                            </select>

                            <select name="role_id[]" class="form-select form-select-lg mb-3 "
                                aria-label=".form-select-lg example">
                                <option value=""> Select Role</option>
                                @if ($roles->count() > 0)
                                    @foreach ($roles as $role)
                                        <option @if  (in_array($role->id,$selectedUser->roles->pluck('id')->toArray())) selected @endif
                                         value="{{ $role->name }}">{{ $role->name }}</option>
                                    @endforeach
                                @endif
                            </select>




                            <button type="submit" class="btn bg-gradient-dark"> Assign role </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection --}}


<form action="{{ route('assign.update', $selectedUser->id) }}" method="POST">
    @csrf
    @method('PUT')
    <select disabled name="user_id" class="form-select form-select-lg mb-3 mt-3"
        aria-label="Default select example">
        <option value=""> Select User</option>
        <option selected value="{{ $selectedUser->id }}">{{ $selectedUser->name }}</option>
    </select>

    <select name="role_id[]" class="form-select form-select-lg mb-3 "
    aria-label=".form-select-lg example">
    <option value=""> Select Role</option>
    @if ($roles->count() > 0)
        @foreach ($roles as $role)
            <option @if  (in_array($role->id,$selectedUser->roles->pluck('id')->toArray())) selected @endif
             value="{{ $role->name }}">{{ $role->name }}</option>
        @endforeach
    @endif
</select>

    <button type="submit" id="updateRoleBtn" class="btn bg-gradient-dark">Assign Role</button>

</form>

@section('scripts')
    <script>
       $('#updateRoleBtn').on('click', function (e) {
    e.preventDefault(); // Prevent the default form submission

    const userId = '{{ $selectedUser->id }}'; // Get the user ID from the blade variable
    const roles = $('select[name="role_id[]"]').val(); // Get selected roles

    $.ajax({
        url: `/assign/${userId}`,  // URL for the update route
        type: 'PUT', // Use PUT for updating the user
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'), // CSRF token for security
            role_id: roles, // Selected role(s)
        },
        success: function (response) {
            // Trigger SweetAlert with a success message
            Swal.fire({
                title: 'Success!',
                text: response.message,
                icon: 'success',
                confirmButtonText: 'Okay'
            }).then(function() {
                // Optionally reload or update the UI after the message
                location.reload(); // Reload the page to reflect the changes
            });
        },
        error: function (xhr) {
            // Trigger SweetAlert for errors
            Swal.fire({
                title: 'Error!',
                text: 'An error occurred while updating the roles.',
                icon: 'error',
                confirmButtonText: 'Try Again'
            });
            console.log(xhr.responseText);
        }
    });
});

    </script>
@endsection
