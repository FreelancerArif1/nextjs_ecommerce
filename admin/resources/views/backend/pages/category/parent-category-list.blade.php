<p class="content_title">All Categories</p>
   <div id="nestable-wrapper" class="dd" >
      <ol class="dd-list list-group">
         @foreach($categories as $k => $category)
         <li class="dd-item list-group-item"  data-id="{{ $category['id'] }}" >
            <div class="dd-handle" >{{ $category['title'] }}</div>
            <div class="dd-option-handle">
               @if(Auth::user()->can('category.edit'))
               <a href="javascript:void(0)" data-toggle="modal" data-target="#myModal"  class="text-success edit_category_btn" data-category-id="{{$category['id']}}"><i class="mdi mdi-pencil-box-outline"></i></a>
               @endif
               @if(Auth::user()->can('category.delete'))
               <a class="text-danger delete_btn" data-url="{{ route('admin.category.delete',$category['id'])}}" data-toggle="modal" data-target="#deleteModal" href="#"><i class="mdi mdi-delete"></i></a>
               @endif
            </div>
            @if(!empty($category->categories_admin))
               @include('backend.pages.category.child-category-view', [ 'category' => $category])
            @endif
         </li>
         @endforeach
      </ol>
   </div>



<form id="save_nested_categories" action="{{ route('save.nested.categories') }}" method="post">
   @csrf
   <textarea style="display: none;" name="nested_category_array" id="nestable-output"></textarea>
   <p class="text-right"><button type="submit" id="save_nested_categories_button" class="btn btn-success" style="margin-top: 15px;" >Update Category Tree</button></p>
</form>

<!--Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Are you sure to delete this item? </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <p>Once you delete this item, you can restore this from trash list!</p>
            <textarea name="reason" id="reason" placeholder="Write reason, why you want to delete this item." class="form-control"></textarea>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <a type="button" href="#" class="btn btn-danger delete_trigger">Delete</a>
         </div>
      </div>
   </div>
</div>