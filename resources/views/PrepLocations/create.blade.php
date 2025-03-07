@extends('app')

@section('content')
<div class="container container-table">
    <div class="row vertical-center-row">
        <div class="text-center col-md-4 col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-heading">Create location</div>

                {!! Form::open(['method'=>'POST', 'url'=>'location_create_post']) !!}

                <div class="panel-body">
                    <p>Location:</p>
                    {!! Form::text('location', null, ['class' => 'form-control']) !!}
                </div>

                <div class="panel-body">
                    <p>Location Description:</p>
                    {!! Form::text('location_desc', null, ['class' => 'form-control']) !!}
                </div>

                <div class="panel-body">
                    <p>Location Plant:</p>
                    {!! Form::select('location_plant', ['' => '', 'Subotica' => 'Subotica', 'Kikinda' => 'Kikinda', 'Senta' => 'Senta'], '', ['class' => 'form-control']) !!} 
                </div>

                <div class="panel-body">
                    {!! Form::submit('Confirm', ['class' => 'btn btn-success center-block']) !!}
                </div>

                @include('errors.list')
                {!! Form::close() !!}

                <br>
            </div>
        </div>
    </div>
</div>
@endsection
