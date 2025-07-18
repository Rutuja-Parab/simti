@if(isset($result['errors']) && count($result['errors']) > 0)
    <div class="alert alert-danger">
        <strong class="d-block mb-2">Errors:</strong>
        <ul class="mb-0">
            @foreach($result['errors'] as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@elseif(isset($result['status']) && $result['status'] === 'Perfect')
    <div class="alert alert-success d-flex align-items-center" role="alert">
        <strong class="me-2">✅ Status:</strong> {{ $result['status'] }}
    </div>
@elseif(isset($result['error']))
    <div class="alert alert-warning">
        <strong>Error:</strong> {{ $result['error'] }}
    </div>
@endif
