@extends('layouts.dashboard')

@section('title')
Categories
@endsection

<!-- @section('breadcrumbs')
breadcrumbs
@endsection -->

@section('content')
<div class="row">
   <div class="col-md-12">
      <div class="card">
         <div class="card-header">
           <div class="row">
               <div class="col-md-6">
                  <!-- search -->
                  <form action="{{ route('categories.index') }}" method="GET">
                     <div class="input-group">
                        <input name="keyword" type="search" class="form-control" placeholder="Search for categories"
                        value="{{ request()->get('keyword') }}">
                        <div class="input-group-append">
                           <button class="btn btn-primary" type="submit">
                              <i class="fas fa-search"></i>
                           </button>
                        </div>
                     </div>
                  </form>
               </div>
               <div class="col-md-6">
                  <a href="{{ route('categories.create') }}" class="btn btn-primary float-right" role="button">
                     Add new
                     <i class="fas fa-plus-square"></i>
                  </a>
               </div>
            </div>
         </div>
         <div class="card-body">
            <ul class="list-group list-group-flush">
               <!-- list category -->
               @if (count($categories))
               @include('categories._category-list',[
                  'categories'=> $categories,
                  'count' => 0
                  ])
               @else
               <p>
                  <strong>
                     @if (request()->get('keyword'))
                     {{ trans('not found', ['keyword' => request()->get('keyword')]) }}
                     @else
                     {{ trans('')}}
                     @endif
                  </strong>
               </p>
               @endif
            </ul>
         </div>
         @if($categories->hasPages())
         <div class="card-footer">
            {{ $categories->links('vendor.pagination.bootstrap-4') }}
         </div>
         @endif
      </div>
   </div>
</div>

@endsection

@push('javascript-internal')
<script>
   $(document).ready(function(){
         //Event : delete
         $("form[role='alert']").submit(function(event) {
            event.preventDefault();
            Swal.fire({
   title: "Delete Category",
   text: "Apakah anda yakin ingin menghapus file tersebut?",
   icon: 'warning',
   allowOutsideClick: false,
   showCancelButton: true,
   cancelButtonText: "Cancel",
   reverseButtons: true,
   confirmButtonText: "Yes",
}).then((result) => {
   if (result.isConfirmed) {
      // todo: process of deleting categories
      event.target.submit();
   }
});
       
                     });
               });
</script>
@endpush