@extends('titan::layouts.website')

@section('content')
    <section class="content p-3">
        @include('titan::website.partials.page_header')

        <div class="row">
            <div class="body col-sm-7 col-lg-8">
                @include('titan::website.partials.breadcrumb')

                <div class="pagination-box">
                    @include('titan::website.blog.pagination')
                </div>
            </div>

            @include('titan::website.partials.page_side')
        </div>
    </section>
@endsection

@section('scripts')
    @parent
    <script type="text/javascript" charset="utf-8">
        $(function () {
            // paginator
            new PaginationClass();
        })
    </script>
@endsection