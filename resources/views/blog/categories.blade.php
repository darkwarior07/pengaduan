@extends('layouts.blog')

@section('title')
Categories blog
@endsection

@section('content')
<h2 class="my-3">
  {{ trans('Categories') }}
</h2>
<!-- Breadcrumb:start -->
<!-- Breadcrumb:end -->

<!-- List category -->
<div class="row">
    @forelse ($categories as $category)
        <!-- true -->
   <div class="col-lg-3 col-sm-6 portfolio-item">
      <div class="card h-100">
        <!-- thumbnail:start -->
        @if (file_exists(public_path($category->thumbnail)))
                    <!-- true -->
                  <img class="card-img-top" src="{{ asset($category->thumbnail) }}" alt="{{ $category->title}}">
                  @else
                    <!-- else -->
                  <img class="img-fluid rounded" src="http://placehold.it/750x300" alt="{{ $category->title}}">
                  @endif
                  <!-- thumbnail:end -->    
         <div class="card-body">
            <h4 class="card-title">
               <a href="{{ route('blog.posts.category', ['slug' => $category->slug ]) }}">
                  {{ $category->title }}
               </a>
            </h4>
            <p class="card-text">
            {{ $category->description }}
            </p>
         </div>
      </div>
   </div>
    @empty
        <!-- false -->
   <h3 class="text-center">
      No data
   </h3>
    @endforelse
   
   
</div>
<!-- List category -->

<!-- pagination:start -->
<div class="row">
   <div class="col">

   </div>
</div>
<!-- pagination:end -->
@endsection