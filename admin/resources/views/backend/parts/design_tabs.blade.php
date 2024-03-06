<div id="accordion">
    <div class="product_card">
      <div  id="headingAdvancedInformation">
          <a class="btn btn-link" data-toggle="collapse" data-target="#advancedInformation" aria-expanded="true" aria-controls="collapseOne">
            Website Settings
          </a>
      </div>
  
      <div id="advancedInformation" class="collapse show" aria-labelledby="headingAdvancedInformation" data-parent="#accordion">
        <div class="product_card_body">
          <div class="nav flex-column nav-pills settings_tabs" id="v-pills-tab" role="tablist" aria-orientation="vertical">
            <a class="nav-link" data-toggle="pill" href="#Product" role="tab" aria-selected="true">Home Layout</a>
            <a class="nav-link" data-toggle="pill" href="#GroceryLayout" role="tab" aria-selected="false">Grocery Layout</a>
            <a class="nav-link" data-toggle="pill" href="#All_promotional_banner" role="tab" aria-selected="false">Promotional Banner</a>

            <a class="nav-link" data-toggle="pill" href="#Offers" role="tab" aria-selected="false">Offers</a>
            <a class="nav-link" data-toggle="pill" href="#General" role="tab" aria-selected="false">General</a>
            <a class="nav-link" data-toggle="pill" href="#Logo" role="tab"  aria-selected="false">Logo</a>
            <a class="nav-link" data-toggle="pill" href="#SocialLinks" role="tab" aria-selected="false">Social Links</a>
            <a class="nav-link" data-toggle="pill" href="#sms_gateway" role="tab" aria-selected="false">SMS Gateway</a>
            <a class="nav-link" data-toggle="pill" href="#sms_template" role="tab" aria-selected="false">SMS Template</a>
            <a class="nav-link" data-toggle="pill" href="#scripts" role="tab" aria-selected="false">Header & Footer Scripts</a> 
          </div>
        </div>
      </div>
    </div>
  </div>

  @push('footer')
      <script>
          jQuery(document).on('click','.settings_tabs a',function(){
              localStorage.setItem('selected_settings_tab',jQuery(this).attr('href'));
          });
          jQuery(document).ready(function(){
            var selectedItem = localStorage.getItem('selected_settings_tab');
            jQuery('a[href="'+selectedItem+'"]').trigger('click');
          });
      </script>
  @endpush