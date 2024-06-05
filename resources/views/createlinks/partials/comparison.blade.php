<div class="container mt-4">
    @if(count($candidates) > 0)
        @foreach($candidates as $candidate)
        <div class="card mb-3 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="suggestion-label">
                        <strong>{{$candidate["label"]}}</strong>
                    </div>
                    <div class="suggestion-button">
                        <button title="@lang('alignment/createlinks.pick-button-title')" class="btn btn-sm btn-primary" onclick="click_button('{{$candidate["target"]}}')">
                            @lang('Pick') <i class="fas fa-hand-pointer"></i>
                        </button>
                    </div>
                </div>
                <div class="suggestion-progress">
                    <div class="progress" style="height: 25px;">
                        <div class="progress-bar" role="progressbar" style="width: {{round((float)$candidate["score"]*100,2)}}%; background-color: {{ $candidate['score'] < 0.6 ? '#ffc107' : '#28a745' }}" aria-valuenow="{{round((float)$candidate["score"]*100, 2)}}" aria-valuemin="0" aria-valuemax="100">
                            {{round((float)$candidate["score"]*100,2)}}%
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    @else
        <div class="alert alert-info">
            <h5>Sorry, I can't help you.</h5>
        </div>
    @endif
</div>



<!-- Custom CSS for styling -->
<style>
    .suggestion-label {
        font-size: 1.1rem;
    }

    .progress-bar {
        font-weight: bold;
    }

    .btn-primary {
        background-color: #007bff;
        border: none;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }
</style>
