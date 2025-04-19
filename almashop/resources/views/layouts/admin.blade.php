<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">

@include('admin.head')

<body class="body">
<div id="wrapper">
    <div id="page" class="">
        <div class="layout-wrap">

            <!-- <div id="preload" class="preload-container">
<div class="preloading">
    <span></span>
</div>
</div> -->

            @include('admin.left-menu')
            <div class="section-content-right">

                @include('admin.header-dashboard')
                <div class="main-content">

                    <div class="main-content-inner">

                        <div class="main-content-wrap">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @yield('content')
                        </div>

                    </div>


                    <div class="bottom-page">
                        <div class="body-text">Copyright © 2024 SurfsideMedia</div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@include('admin.scripts')
</body>

</html>
