@extends('layouts.blog')

@section('title')
    {{ trans('Tag Blog') }}
@endsection

@section('content')
<h2 class="my-3">
   Tags
</h2>
<!-- Breadcrumb:start -->
<!-- Breadcrumb:end -->

<!-- List tag -->
   <div class="row">
      <div class="col">
        @forelse($tags as $tag)
         <!-- true -->
         <a href="{{ route('blog.posts.tag', ['slug' => $tag->slug]) }}"
               class="badge badge-info py-3 px-5">{{ $tag->title }}</a>
        @empty
            <!-- false -->
            <h3 class="text-center">
               No data
            </h3>
        @endforelse
        

      </div>
   </div>
<!-- List tag -->

<!-- pagination:start -->
<div class="row">
   <div class="col">

   </div>
</div>
<!-- pagination:end -->
@endsection