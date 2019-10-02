@extends('titan::layouts.admin')

@section('content')

    @include('titan::admin.photos.videos.videoable', ['videoable' => $photoable])

    @include('titan::admin.photos.photoable')

@endsection