<div class="mb-3">
    <label class="form-label">Event Title <span class="text-danger">*</span></label>
    <input type="text" name="title" value="{{ old('title', $event->title ?? '') }}"
           class="form-control @error('title') is-invalid @enderror">
    @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label">Description <span class="text-danger">*</span></label>
    <textarea name="description" rows="4"
              class="form-control @error('description') is-invalid @enderror">{{ old('description', $event->description ?? '') }}</textarea>
    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Location <span class="text-danger">*</span></label>
        <input type="text" name="location" value="{{ old('location', $event->location ?? '') }}"
               class="form-control @error('location') is-invalid @enderror"
               placeholder="e.g. Conference Hall A, Dhaka">
        @error('location') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Event Date & Time <span class="text-danger">*</span></label>
        <input type="datetime-local" name="event_date"
               value="{{ old('event_date', isset($event) ? $event->event_date->format('Y-m-d\TH:i') : '') }}"
               class="form-control @error('event_date') is-invalid @enderror">
        @error('event_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Max Attendees <span class="text-muted">(optional)</span></label>
        <input type="number" name="max_attendees" min="1"
               value="{{ old('max_attendees', $event->max_attendees ?? '') }}"
               class="form-control">
    </div>
    @isset($event)
        <div class="col-md-6 mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                @foreach (['upcoming','ongoing','completed','cancelled'] as $s)
                    <option value="{{ $s }}" {{ old('status', $event->status) === $s ? 'selected' : '' }}>
                        {{ ucfirst($s) }}
                    </option>
                @endforeach
            </select>
        </div>
    @endisset
</div>

<div class="mb-3">
    <label class="form-label">Banner Image <span class="text-muted">(optional)</span></label>
    @if (isset($event) && $event->banner_url)
        <div class="mb-2">
            <img src="{{ $event->banner_url }}" class="img-thumbnail" style="max-height:120px">
        </div>
    @endif
    <input type="file" name="banner_image" class="form-control" accept="image/*">
    <div class="form-text">Max 2MB.</div>
</div>