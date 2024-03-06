@extends('backend.layouts.master')
@section('title','Categories - '.config('concave.cnf_appname'))
@section('content')
<div class="grid-margin stretch-card">
   <div class="card">
      <div class="card-body">
         <span class="card-title">Dashboard > Catalog > Categories</span>
         <a class="btn btn-success float-right create_new_category" data-toggle="modal" data-target="#myModal" href="javascript:void(0)">Create New Category</a>
      </div>
   </div>
</div>
<div class="grid-margin">
   <div class="row">
      <div class="col-md-12">
         <div class="card">
            <div class="card-body">
                @include('backend.pages.category.parent-category-list')
            </div>
         </div>
      </div>

   </div>
</div>
  
<div class="modal fade" id="myModal">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="card">
                <div class="card-body category_form_element">
                    @include('backend.pages.category.create')
                </div>
             </div>
        </div>
        
      </div>
    </div>
  </div>



@push('footer')

<script>

$(document).ready(function()
{
    var updateOutput = function(e)
    {
        var list   = e.length ? e : $(e.target),
            output = list.data('output');
            //console.log(window.JSON.stringify(list.nestable('serialize')));
        if (window.JSON) {
            output.val(window.JSON.stringify(list.nestable('serialize')));

        } else {
            output.val('JSON browser support required for this demo.');
        }
    };

    // activate Nestable for list 1
    $('#nestable-wrapper').nestable({
        group: 1,
        maxDepth : 10,
    })
    .nestable('collapseAll')
    .on('change', updateOutput);

    // output initial serialised data
    updateOutput($('#nestable-wrapper').data('output', $('#nestable-output')));
    
    $('#nestable-menu').on('click', function(e)
    {
        var target = $(e.target),
            action = target.data('action');
        if (action === 'expand-all') {
            $('.dd').nestable('expandAll');
        }
        if (action === 'collapse-all') {
            $('.dd').nestable('collapseAll');
        }
    });

    
});

jQuery(document).on('submit','#save_nested_categories',function(event){
    event.preventDefault();
    jQuery('#msg_nested_categories').remove();
    jQuery('#save_nested_categories_button').html('Updating...');
    jQuery.ajax({
        url: jQuery(this).attr('action'),
        type: "post",
        data: jQuery(this).serialize(),
        success: function(response) {
            jQuery('#save_nested_categories').after('<p class="msg_nested_categories">Category tree has been updated successfully!</p>');
            jQuery('#save_nested_categories_button').html('Update Category Tree');
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(textStatus, errorThrown);
        }
    });

});


</script>

  
@endpush
@endsection