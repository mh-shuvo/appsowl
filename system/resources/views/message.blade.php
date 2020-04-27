@if(count($errors))
<div class="row">
    <div class="col-lg-12">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li><strong>{{ $error }}</strong></li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</div>        
@endif

@if (session('status'))
<div class="row">
    <div class="col-lg-12">
        <div class="alert alert-success">
            <strong>{{ session('status') }}</strong>
        </div>
    </div>
</div>        
@endif

@if (session('error_message'))
<div class="row">
    <div class="col-lg-12">
        <div class="alert alert-danger">
            <strong>{{ session('error_message') }}</strong>
        </div>
    </div>
</div>
@endif