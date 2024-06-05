<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">
            @if(isset($header))
                {!! $header !!}
            @else 
                No Selected Entity
            @endif
        </h3>
        <button type="button" class="btn btn-tool" title="Click for more details" data-bs-toggle="collapse" data-bs-target="#details_{{$dump}}">
            <i class="fas fa-{{ isset($collapsed) ? $collapsed : 'plus' }}"></i>
        </button>
    </div>
    <div id="details_{{$dump}}" class="collapse card-body">
        @if(isset($details))
            {!! $details !!}
        @else 
            <i>Click on an element to provide more info</i>
        @endif
    </div>
</div>

<!-- Custom CSS for styling -->
<style>
    .card {
        margin-bottom: 15px;
        border: 1px solid #ddd;
        border-radius: 8px;
    }

    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #ddd;
    }

    .btn-tool {
        color: #007bff;
        cursor: pointer;
    }

    .btn-tool:hover {
        color: #0056b3;
    }

    .collapse.show + .card-body {
        border-top: 1px solid #ddd;
    }
</style>
