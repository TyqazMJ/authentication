@extends('layouts.app')

@section('content')
<div class="container">
    {{-- Avatar + Nickname at the top --}}
    @if (auth()->user()->avatar)
        <div class="text-center mb-4">
            <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Avatar" class="rounded-circle shadow" width="150" height="150">
            <h4 class="mt-2">{{ auth()->user()->nickname }}</h4>
        </div>
    @endif

    <h2 class="mb-4 text-center">Edit Profile</h2>

    {{-- Success Message --}}
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Profile Update Form --}}
    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="nickname" class="form-label">Nickname</label>
            <input type="text" id="nickname" name="nickname" class="form-control" value="{{ old('nickname', auth()->user()->nickname) }}" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" id="email" name="email" class="form-control" value="{{ old('email', auth()->user()->email) }}" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">New Password (optional)</label>
            <input type="password" id="password" name="password" class="form-control" placeholder="Leave blank to keep current password">
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">Phone Number</label>
            <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone', auth()->user()->phone) }}">
        </div>

        <div class="mb-3">
            <label for="city" class="form-label">City</label>
            <input type="text" id="city" name="city" class="form-control" value="{{ old('city', auth()->user()->city) }}">
        </div>

        <div class="mb-3">
            <label for="avatar" class="form-label">Profile Picture</label>
            <input type="file" id="avatar" name="avatar" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Update Profile</button>
    </form>

    {{-- Delete Account --}}
    <form method="POST" action="{{ route('profile.delete') }}" class="mt-4">
        @csrf
        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete your account?')">
            Delete Account
        </button>
    </form>
</div>
@endsection
