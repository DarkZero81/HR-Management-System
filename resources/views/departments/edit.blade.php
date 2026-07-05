@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow max-w-2xl mx-auto">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0">تعديل بيانات القسم: {{ $department->name }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('departments.update', $department->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">اسم القسم <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $department->name) }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">الوصف</label>
                    <textarea name="description" id="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description', $department->description) }}</textarea>
                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('departments.index') }}" class="btn btn-secondary">إلغاء</a>
                    <button type="submit" class="btn btn-warning">تحديث البيانات</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
