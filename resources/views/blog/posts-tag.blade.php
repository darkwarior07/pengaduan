@extends('layouts.blog')

@section('title')
    {{ trans('Tag', ['title' => $tag->title]) }}
@endsection

@section('content')
    <!-- Title -->
<h2 class="mt-4 mb-3">
        {{ trans('Tag', ['title' => $tag->title]) }}
</h2>

<!-- Breadcrumb:start -->
<!-- Breadcrumb:end -->
<div class="row">
   <div class="col-lg-8">
      <!-- Post list:start -->
      @forelse($posts as $post)
      <div class="card mb-4">
         <div class="card-body">
            <div class="row">
               <div class="col-lg-6">
                   <!-- thumbnail:start -->
                   @if (file_exists(public_path($post->thumbnail)))
                    <!-- true -->
                  <img class="card-img-top" src="{{ asset($post->thumbnail) }}" alt="{{ $post->title}}">
                  @else
                    <!-- else -->
                  <img class="img-fluid rounded" src="http://placehold.it/750x300" alt="{{ $post->title}}">
                  @endif
                  <!-- thumbnail:end -->                
               </div>
               <div class="col-lg-6">
                  <h2 class="card-title">
                    {{ $post->title }}
                  </h2>
                  <p class="card-text">
                  {{ $post->description }}
                  </p>
                  <a href="{{ route('blog.post.detail', ['slug' => $post->slug]) }}" class="btn btn-primary">
                     Read more
                  </a>
               </div>
            </div>
         </div>
      </div>
      @empty
         <h3 class="text-center">
         No data
        </h3>
      @endforelse
     

      <!-- empty -->
      
      <!-- Post list:end -->
   </div>
   <div class="col-md-4">
      <!-- Categories list:start -->
      <div class="card mb-1">
         <h5 class="card-header">
            Tag
         </h5>
         <div class="card-body">
            @foreach($tags as $tag)
            <a href="{{ route('blog.posts.tag', ['slug' => $tag->slug]) }}"
               class="badge badge-info py-3 px-5 my-1">{{ $tag->title }}</a>
            @endforeach
         </div>
      </div>
      <!-- Categories list:end -->
   </div>
</div>
@endsection