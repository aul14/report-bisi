@if ($makeEdit)
    <a href="javascript:void(0)" data-id="{{ $row_id }}" class="btn btn-xs btn-primary {{ $class_edit }}">
        <i class="fas fa-edit"></i> Edit</a>
@endif
@if ($makeDelete)
    <a href="javascript:void(0)" data-id="{{ $row_id }}" class="btn btn-xs btn-danger {{ $class_delete }}">
        <i class="fas fa-trash"></i> Delete</a>
@endif
