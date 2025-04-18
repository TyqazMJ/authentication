@extends('layouts.app')

@section('content')
<div class="container">
  <br>
  <div class="row justify-content-center">
    <div class="col-md-6">
        <h2>{{ isset($todo) ? 'Edit Todo' : 'Add Todo' }}</h2> <!-- Dynamically change the title -->
    </div>
    <div class="col-md-6">
        <div class="float-right">
            <a href="{{ route('todo.index') }}" class="btn btn-primary">Back</a>
        </div>
    </div>
    <br>
    <div class="col-md-12">
        @include('partials.alerts')

        <form action="{{ isset($todo) ? route('todo.update', $todo->id) : route('todo.store') }}" method="POST">
            @csrf
            @if(isset($todo)) @method('PUT') @endif

            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $todo->title ?? '') }}">
                @error('title') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea name="description" class="form-control" id="description" rows="5">{{ old('description', $todo->description ?? '') }}</textarea>
                @error('description') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label for="status">Select todo status</label>
                <select class="form-control" id="status" name="status">
                    <option value="pending" {{ (old('status', $todo->status ?? '') == 'pending') ? 'selected' : '' }}>Pending</option>
                    <option value="completed" {{ (old('status', $todo->status ?? '') == 'completed') ? 'selected' : '' }}>Completed</option>
                </select>
            </div>

            <button type="submit" class="btn btn-info">Submit</button>
        </form>
    </div>
  </div>
</div>
@endsection
