@extends('layouts.app')
@section('content')
    <main>

        @include('front.slider')
        <div class="container mw-1620 bg-white border-radius-10">
            <div class="mb-3 mb-xl-5 pt-1 pb-4"></div>
            @include('front.category-carusel')

            <div class="mb-3 mb-xl-5 pt-1 pb-4"></div>

            @include('front.hot-deals')

            <div class="mb-3 mb-xl-5 pt-1 pb-4"></div>

            @include('front.category-banner')

            <div class="mb-3 mb-xl-5 pt-1 pb-4"></div>

            @include('front.products-grid')
        </div>

        <div class="mb-3 mb-xl-5 pt-1 pb-4"></div>

    </main>
@endsection
