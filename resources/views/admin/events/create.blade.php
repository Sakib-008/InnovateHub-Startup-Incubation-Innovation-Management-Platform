@extends('layouts.app')
@section('title', 'Create Event')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body p-4">
                <h4 class="mb-4">Create Event</h4>

                <form method="POST" action="{{ route('admin.events.store') }}" enctype="multipart/form-data">
                    @csrf
                    @include('admin.events._form')
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">Create Event</button>
                        <a href="{{ route('admin.events.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection