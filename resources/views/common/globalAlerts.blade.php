@if (session('message'))
    <div class="row">

        <div class="col-md-12 mb-1">
            <div class="alert {{session('class')}}">
                {{ session('message') }}
            </div>
        </div>
    </div>
@endif
