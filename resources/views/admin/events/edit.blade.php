@extends('layouts.app')
@section('title', 'Edit Event')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body p-4">
                <h4 class="mb-4">Edit Event</h4>

                <form method="POST" action="{{ route('admin.events.update', $event) }}"
                      enctype="multipart/form-data">
                    @csrf @method('PUT')
                    @include('admin.events._form')
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                        <a href="{{ route('admin.events.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection