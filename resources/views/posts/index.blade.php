@extends('layouts.dashboard')

@section('title')
Pengaduan 
@endsection

@section('content')
<div class="row">
  <div class="col-md-12">
     <div class="card">
        <div class="card-header">
           <div class="row">
              <div class="col-md-6">
                 <form action="" method="GET" class="form-inline form-row">
                    <div class="col">
                       <div class="input-group mx-1">
                          <!-- <label class="font-weight-bold mr-2">Status</label>
                          <select name="status" class="custom-select">
                             <option value="publish" selected>Publish</option>
                             <option value="draft">Draft</option> -->
                          </select>
                          <div class="input-group-append">
                             <!-- <button class="btn btn-primary" type="submit">Apply</button> -->
                          </div>
                       </div>
                    </div>
                    <div class="col">
                       <div class="input-group mx-1">
                          <input name="keyword" type="search" class="form-control" placeholder="Search for posts">
                          <div class="input-group-append">
                             <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i>
                             </button>
                          </div>
                       </div>
                    </div>
                 </form>
              </div>
              <div class="col-md-6">
                 <a href="{{ route('posts.create') }}" class="btn btn-primary float-right" role="button">
                    Add new
                    <i class="fas fa-plus-square"></i>
                 </a>
              </div>
           </div>
        </div>
        <div class="card-body">
           <ul class="list-group list-group-flush">
              <!-- list post -->
              @forelse ($posts as $post)
              <div class="card my-2">
   <div class="card-body">
      <h5>
        {{ $post->title }}
      </h5>
      <p>
      {{ $post->description }}
      </p>
      <div class="float-right">
         <!-- detail -->
         <a href="{{ route('posts.show', ['post' => $post]) }}" class="btn btn-sm btn-primary" role="button">
            <i class="fas fa-eye"></i>
         </a>
         <!-- edit -->
         <a href="{{ route('posts.edit',['post' => $post]) }}" class="btn btn-sm btn-info" role="button">
            <i class="fas fa-edit"></i>
         </a>
         <!-- delete -->
         <form class="d-inline" role="alert" action="{{ route('posts.destroy', ['post' => $post ]) }}" method="POST">
      @csrf 
      @method('DELETE')
      <button type="submit" class="btn btn-sm btn-danger">
        <i class="fas fa-trash"></i>
      </button>
    </form>
      </div>
   </div>
</div>

              @empty
              <p>
                <strong>
                    Data Post
                </strong>
              </p>
              @endforelse
           </ul>
        </div>
     </div>
  </div>
</div>
@endsection

@push('javascript-internal')
<script>
   $(document).ready(function(){
      //DELETE
      $("form[role='alert']").submit(function(event) {
         event.preventDefault();
         Swal.fire({
   title: "Delete post",
   text: "Apakah anda ingin menghapus post ini?",
   icon: 'warning',
   allowOutsideClick: false,
   showCancelButton: true,
   cancelButtonText: "Cancel",
   reverseButtons: true,
   confirmButtonText: "Yes",
}).then((result) => {
   if (result.isConfirmed) {
   event.target.submit();
   }
});


      });
   });
</script>
@endpush
