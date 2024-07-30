@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div class="flex flex-col flex-grow h-full">
    <div class="flex-grow p-6 overflow-auto bg-black rounded-2xl mx-4 mb-6">
        <div id="search_list"></div>
    </div>
</div>
@endsection