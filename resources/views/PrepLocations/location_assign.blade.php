@extends('app')

@section('content')
<div class="container container-table">
    <div class="row vertical-center-row">
        <div class="text-center col-md-4 col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-heading">Assign location to PO</div>

                {!! Form::open(['method'=>'POST', 'url'=>'location_assign_post']) !!}
                {!! Form::hidden('location_plant', $location_plant, ['class' => 'form-control']) !!}
                
                <div class="panel-body">
                    <p>Choose PO:</p>
                    {!! Form::select('po_id', $posArray, null, ['name'=>'po_id','class' => 'form-con trol', 'class'=>'chosen','required'=>'requ ired']) !!}
                </div>

                <div class="panel-body">
                    <p>Choose Location:</p>
                    {!! Form::select('location_id', $locationsArray, null, ['name'=>'location_id','class' => 'form-cont rol','class'=>'chosen','required'=>'requ ired']) !!}
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
