@extends ('layouts.dashboard')

@section ('title')
Buat pengaduan
@endsection

@section ('content')
<div class="row">
  <div class="col-md-12">
     <form action="{{ route('posts.store') }}" method="POST">
      @csrf
        <div class="card">
           <div class="card-body">
              <div class="row d-flex align-items-stretch">
                 <div class="col-md-8">
                    <!-- title -->
                    <div class="form-group">
                       <label for="input_post_title" class="font-weight-bold">
                          Title
                       </label>
                       <input id="input_post_title" value="{{ old('title') }}" name="title" type="text" class="form-control  @error('title') is-invalid @enderror"
                          placeholder="Input title" />
                          @error('title')
                          <span class="invalid-feedback">
                              <strong>
                                    {{ $message }}
                              </strong>
                           </span>
                           @enderror
                    </div>
                    <!-- slug -->
                    <div class="form-group">
                       <label for="input_post_slug" class="font-weight-bold">
                          Slug
                       </label>
                       <input id="input_post_slug" value="{{ old('slug') }}" name="slug" type="text" class="form-control  @error('slug') is-invalid @enderror" placeholder="Input slug"
                          readonly />
                          @error('slug')
                          <span class="invalid-feedback">
                              <strong>
                                    {{ $message }}
                              </strong>
                           </span>
                           @enderror
                    </div>
                    <!-- thumbnail -->
                    <div class="form-group">
                       <label for="input_post_thumbnail" class="font-weight-bold">
                          Thumbnail
                       </label>
                       <div class="input-group">
                          <div class="input-group-prepend">
                             <button id="button_post_thumbnail" data-input="input_post_thumbnail"
                                class="btn btn-primary" type="button">
                                Browse
                             </button>
                          </div>
                          <input id="input_post_thumbnail" name="thumbnail" value="{{ old('thumbnail') }}" type="text" class="form-control  @error('thumbnail') is-invalid @enderror"
                             placeholder="Input thumbnail" readonly />
                             @error('thumbnail')
                          <span class="invalid-feedback">
                              <strong>
                                    {{ $message }}
                              </strong>
                           </span>
                           @enderror
                       </div>
                    </div>
                    <!-- description -->
                    <div class="form-group">
                       <label for="input_post_description" class="font-weight-bold">
                          Description
                       </label>
                       <textarea id="input_post_description" name="description" placeholder="Input description" class="form-control  @error('description') is-invalid @enderror"
                          rows="3">{{ old('description') }}</textarea>
                          @error('description')
                          <span class="invalid-feedback">
                              <strong>
                                    {{ $message }}
                              </strong>
                           </span>
                           @enderror
                    </div>
                    <!-- content -->
                    <div class="form-group">
                       <label for="input_post_content" class="font-weight-bold">
                          Content
                       </label>
                       <textarea id="input_post_content" name="content" placeholder="Input content" class="form-control  @error('content') is-invalid @enderror"
                          rows="20">{{ old('content') }}</textarea>
                          @error('content')
                          <span class="invalid-feedback">
                              <strong>
                                    {{ $message }}
                              </strong>
                           </span>
                           @enderror
                    </div>
                 </div>
                 <div class="col-md-4">
                    <!-- catgeory -->
                    <div class="form-group">
                       <label for="input_post_description" class="font-weight-bold">
                          Category
                       </label>
                       <div class="form-control overflow-auto  @error('category') is-invalid @enderror" style="height: 886px">
                          <!-- List category -->
                          @include('posts._category-list',[
                           'categories' => $categories,
                           'categoryChecked' => old('category')

                           ])
                          <!-- List category -->
                       </div>
                       @error('category')
                          <span class="invalid-feedback">
                              <strong>
                                    {{ $message }}
                              </strong>
                           </span>
                           @enderror
                    </div>
                 </div>
              </div>
              <div class="row">
                 
              </div>
              <div class="row">
                 <div class="col-md-12">
                    <div class="float-right">
                       <a class="btn btn-warning px-4" href="{{ route('posts.index')}}">Back</a>
                       <button type="submit" class="btn btn-primary px-4">
                          Save
                       </button>
                    </div>
                 </div>
              </div>
           </div>
        </div>
     </form>
  </div>
</div>
@endsection

@push('css-external')
   <link rel="stylesheet" href="{{ asset('vendor/select2/css/select2.min.css') }}">
   <link rel="stylesheet" href="{{ asset('vendor/select2/css/select2-bootstrap4.min.css') }}">
@endpush

@push('javascript-external')
<!-- file manager -->
   <script src="{{ asset('vendor/laravel-filemanager/js/stand-alone-button.js') }}"></script>
<!-- plugin TinyMCE5 -->
<script src="{{ asset('vendor/tinymce5/jquery.tinymce.min.js') }}"></script>
<script src="{{ asset('vendor/tinymce5/tinymce.min.js') }}"></script>
<!-- select2 -->
<script src="{{ asset('vendor/select2/js/select2.min.js') }}"></script>
<script src="{{ asset('vendor/select2/js/i18n/' . app()->getLocale() . '.js') }}"></script>
@endpush

@push('javascript-internal')
<script>
   $(document).ready(function(event){
      //Input slug
      $("#input_post_title").change(function (event) {
       $("#input_post_slug").val(
       event.target.value
      .trim()
      .toLowerCase()
      .replace(/[^a-z\d-]/gi, "-")
      .replace(/-+/g, "-")
      .replace(/^-|-$/g, "")
  );
});

   //input thumbnail
   $('#button_post_thumbnail').filemanager('image');
   //Text editor content
   $("#input_post_content").tinymce({
  relative_urls: false,
  language: "en",
  plugins: [
    "advlist autolink lists link image charmap print preview hr anchor pagebreak",
    "searchreplace wordcount visualblocks visualchars code fullscreen",
    "insertdatetime media nonbreaking save table directionality",
    "emoticons template paste textpattern",
  ],
  toolbar1: "fullscreen preview",
  toolbar2:
    "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media",
    file_picker_callback: function(callback, value, meta) {
   let x = window.innerWidth || document.documentElement.clientWidth || document
      .getElementsByTagName('body')[0].clientWidth;
   let y = window.innerHeight || document.documentElement.clientHeight || document
      .getElementsByTagName('body')[0].clientHeight;

   let cmsURL = "{{ route('unisharp.lfm.show') }}" + '?editor=' + meta.fieldname;
   if (meta.filetype == 'image') {
      cmsURL = cmsURL + "&type=Images";
   } else {
      cmsURL = cmsURL + "&type=Files";
   }

   tinyMCE.activeEditor.windowManager.openUrl({
      url: cmsURL,
      title: 'Filemanager',
      width: x * 0.8,
      height: y * 0.8,
      resizable: "yes",
      close_previous: "no",
      onMessage: (api, message) => {
         callback(message.content);
      }
   });
}
});
   
   });
</script>
@endpush