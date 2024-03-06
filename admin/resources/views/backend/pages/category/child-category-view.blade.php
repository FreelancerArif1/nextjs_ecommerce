@if(!empty($category->categories_admin))
    <ol class="dd-list list-group">
        @foreach($category->categories_admin as $kk => $sub_category)
            <li class="dd-item list-group-item"  data-id="{{ $sub_category['id'] }}" >
                <div class="dd-handle" >{{ $sub_category['title'] }}</div>
                <div class="dd-option-handle">
                    @if(Auth::user()->can('category.edit'))
                    <a href="javascript:void(0)" data-toggle="modal" data-target="#myModal" class="text-success edit_category_btn" data-category-id="{{$sub_category['id']}}"><i class="mdi mdi-pencil-box-outline"></i></a>
                    @endif
                    @if(Auth::user()->can('category.delete'))
                    <a class="text-danger delete_btn" data-url="{{ route('admin.category.delete',$sub_category['id'])}}" data-toggle="modal" data-target="#deleteModal" href="#"><i class="mdi mdi-delete"></i></a>
                    @endif
                </div>

                @include('backend.pages.category.child-category-view', [ 'category' => $sub_category])
            </li>
        @endforeach
    </ol>
@endif
