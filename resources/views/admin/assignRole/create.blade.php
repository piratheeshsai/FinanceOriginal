

<form action="{{ route('assign.store') }}" method="POST">
    @csrf

    <select name="user_id" class="form-select form-select-lg mb-3 mt-3"
        aria-label=".form-select-lg example">
        <option value=""> Select User</option>
        @if ($users->count() > 0)
            @foreach ($users as $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
        @endif
    </select>

    <select name="role_name[]" class="form-select form-select-lg mb-3 "
        aria-label=".form-select-lg example">
        <option value=""> Select Role</option>
        @if ($roles->count() > 0)
            @foreach ($roles as $role)
                <option value="{{ $role->name }}">{{ $role->name }}</option>
            @endforeach
        @endif
    </select>

    <button type="submit" class="btn bg-gradient-dark"> Assign role </button>
</form>
