@extends('layouts.app')

@section('content')
<div class="container">
  <br>
  <div class="row justify-content-center">
    <div class="col-md-6">
        <h2>Todos List</h2>
    </div>
    <div class="col-md-6">
        <div class="float-right">
            <a href="{{ route('todo.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add New Todo</a>
        </div>
    </div>
    <br>
    <div class="col-md-12">
        @include('partials.alerts') <!-- Include alerts partial -->

        <table class="table table-bordered">
            <thead class="thead-light">
                <tr>
                    <th width="5%">#</th>
                    <th>Task Name</th>
                    <th width="10%" class="text-center">Task Status</th>
                    <th width="14%" class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($todos as $todo)
                    <tr>
                        <th>{{ $todo->id }}</th>
                        <td>{{ $todo->title }}</td>
                        <td class="text-center">{{ $todo->status }}</td>
                        <td class="text-center">
                            <div class="action_btn">
                                <a href="{{ route('todo.edit', $todo->id)}}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('todo.destroy', $todo->id)}}" method="post" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" type="submit">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">No data found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
  </div>
</div>
@endsection
