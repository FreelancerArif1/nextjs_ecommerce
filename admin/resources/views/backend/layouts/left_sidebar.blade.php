<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">

        <li class="nav-item">
            <a class="nav-link {{ Route::is('admin.index') ? 'menu_active' : '' }}" href="{{ route('admin.index') }}">
                <i class="menu-icon typcn typcn-th-large"></i>
                <span class="menu-title">Home</span>
            </a>
        </li>

        @if (Auth::user()->can('gallery.view'))
            <li class="nav-item">
                <a class="nav-link {{ Route::is('admin.media.gallery') ? 'menu_active' : '' }}"
                    href="{{ route('admin.media.gallery') }}">
                    <i title="Media Gallery" class="menu-icon mdi mdi-file-image"></i>
                    <span class="menu-title">Media Gallery</span>
                </a>
            </li>
        @endif


        @if (Auth::user()->can('product.view') ||
            Auth::user()->can('category.view') ||
            Auth::user()->can('brand.view') ||
            Auth::user()->can('customoption.view'))
            <li class="nav-item parent_group">
                <a class="nav-link" data-toggle="collapse" href="#products" aria-expanded="false"
                    aria-controls="products">
                    <i class="menu-icon mdi mdi-package-variant-closed"></i>
                    <span class="menu-title">Manage Product</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="products">
                    <ul class="nav flex-column sub-menu">
                        @if (Auth::user()->can('product.view'))
                            <li class="nav-item"><a
                                    class="nav-link {{ Route::is('admin.product') || Route::is('admin.product.create') || Route::is('admin.product.edit') ? 'menu_active' : '' }}"
                                    href="{{ route('admin.product') }}"> <i title="Products"
                                        class="mdi mdi-drawing-box menu_ico_small"></i> <span
                                        class="menu_title_small">Products</span></a></li>
                        @endif

                        @if (Auth::user()->can('category.view'))
                            <li class="nav-item"><a
                                    class="nav-link {{ Route::is('admin.category') || Route::is('admin.category.create') || Route::is('admin.category.edit') ? 'menu_active' : '' }}"
                                    href="{{ route('admin.category') }}"><i title="Categories"
                                        class="mdi mdi-format-list-bulleted menu_ico_small"></i> <span
                                        class="menu_title_small">Categories</span> </a></li>
                        @endif
                        @if (Auth::user()->can('brand.view'))
                            <li class="nav-item"><a
                                    class="nav-link {{ Route::is('admin.brand') || Route::is('admin.brand.create') || Route::is('admin.brand.edit') ? 'menu_active' : '' }}"
                                    href="{{ route('admin.brand') }}"><i title="Brands"
                                        class="mdi mdi-briefcase menu_ico_small"></i> <span
                                        class="menu_title_small">Brands</span></a></li>
                        @endif
                        @if (Auth::user()->can('review.view'))
                            <li class="nav-item"><a
                                    class="nav-link {{ Route::is('admin.review') || Route::is('admin.review.create') || Route::is('admin.review.edit') ? 'menu_active' : '' }}"
                                    href="{{ route('admin.review') }}"><i title="Product Reviews"
                                        class="mdi mdi-book menu_ico_small"></i> <span class="menu_title_small">Product
                                        Reviews</span> </a></li>
                        @endif

                        @if (Auth::user()->can('product.return.request'))
                            <li class="nav-item"><a
                                    class="nav-link {{ Route::is('admin.product.return.request') ? 'menu_active' : '' }}"
                                    href="{{ route('admin.product.return.request') }}"><i
                                        title="Product Return Request" class="mdi mdi-replay menu_ico_small"></i> <span
                                        class="menu_title_small">Product Return Request</span> </a></li>
                        @endif

                        @if (Auth::user()->can('product.restock.request'))
                            <li class="nav-item"><a
                                    class="nav-link {{ Route::is('admin.product.restock.request') ? 'menu_active' : '' }}"
                                    href="{{ route('admin.product.restock.request') }}"><i
                                        title="Product Return Request" class="mdi mdi-replay menu_ico_small"></i> <span
                                        class="menu_title_small">Product Restock Request</span> </a></li>
                        @endif

                        @if (Auth::user()->can('import.view') && Auth::user()->can('import.create'))
                            <li class="nav-item"><a
                                    class="nav-link {{ Route::is('admin.product.shipping.cost') ? 'menu_active' : '' }}"
                                    href="{{ route('admin.product.shipping.cost') }}"> <i title="Shipping Cost Import"
                                        class="mdi mdi-drawing-box menu_ico_small"></i> <span
                                        class="menu_title_small">Shipping Cost Import</span></a></li>
                        @endif



                    </ul>
                </div>
            </li>
        @endif


        @if (Auth::user()->can('attributeset.view') || Auth::user()->can('attributelist.view'))
            <li class="nav-item parent_group">
                <a class="nav-link" data-toggle="collapse" href="#Attribute" aria-expanded="false"
                    aria-controls="Attribute">
                    <i title="Attribute" class="menu-icon typcn typcn-tag"></i>
                    <span class="menu-title">Attribute</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="Attribute">
                    <ul class="nav flex-column sub-menu">

                        @if (Auth::user()->can('attributeset.view'))
                            <li class="nav-item"><a
                                    class="nav-link {{ Route::is('admin.attribute-set') || Route::is('admin.attribute-set.create') || Route::is('admin.attribute-set.edit') ? 'menu_active' : '' }}"
                                    href="{{ route('admin.attribute-set') }}"><i title="Attribute Set"
                                        class="mdi mdi-select-all menu_ico_small"></i> <span
                                        class="menu_title_small">Attribute Set</span></a></li>
                        @endif

                        @if (Auth::user()->can('attributelist.view'))
                            <li class="nav-item"><a
                                    class="nav-link {{ Route::is('admin.attribute-list') || Route::is('admin.attribute.create') || Route::is('admin.attribute.edit') ? 'menu_active' : '' }}"
                                    href="{{ route('admin.attribute-list') }}"><i title="Attribute List"
                                        class="mdi mdi-format-list-bulleted menu_ico_small"></i> <span
                                        class="menu_title_small">Attribute List</span></a></li>
                        @endif

                    </ul>
                </div>
            </li>
        @endif

        @if (Auth::user()->can('order.view'))
            <li class="nav-item parent_group">
                <a class="nav-link" data-toggle="collapse" href="#Sales" aria-expanded="false" aria-controls="Sales">
                    <i title="Sales" class="menu-icon mdi mdi-sale"></i>
                    <span class="menu-title">Sales</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="Sales">
                    <ul class="nav flex-column sub-menu">

                        @if (Auth::user()->can('pos.view'))
                            <li class="nav-item"><a
                                    class="nav-link {{ Route::is('admin.pos') ? 'menu_active' : '' }}"
                                    href="{{ route('admin.pos') }}"><i title="POS"
                                        class="mdi mdi-cart menu_ico_small"></i> <span
                                        class="menu_title_small">POS</span></a></li>
                        @endif

                        @if (Auth::user()->can('order.view'))
                            <li class="nav-item"><a
                                    class="nav-link {{ Route::is('admin.order') || Route::is('admin.order.show') || Route::is('admin.order.create') || Route::is('admin.order.edit') ? 'menu_active' : '' }}"
                                    href="{{ route('admin.order') }}"><i title="Orders"
                                        class="mdi mdi-cart menu_ico_small"></i> <span
                                        class="menu_title_small">Orders</span></a></li>
                            {{-- <li class="nav-item"><a
                                    class="nav-link {{ Route::is('admin.order.promotional') || Route::is('admin.order.show.promotional') || Route::is('admin.order.create.promotional') || Route::is('admin.order.edit.promotional') ? 'menu_active' : '' }}"
                                    href="{{ route('admin.order.promotional') }}"><i title="Promotional Orders"
                                        class="mdi mdi-cart-outline menu_ico_small"></i> <span
                                        class="menu_title_small">Promotional Orders</span></a></li> --}}
                        @endif

                    </ul>
                </div>
            </li>
        @endif


        @if (Auth::user()->can('report.view'))
            <li class="nav-item parent_group">
                <a class="nav-link" data-toggle="collapse" href="#Reports" aria-expanded="false"
                    aria-controls="Reports">
                    <i class="menu-icon mdi mdi-chart-bar"></i>
                    <span class="menu-title">Reports</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="Reports">
                    <ul class="nav flex-column sub-menu">
                        @if (Auth::user()->can('report.sales'))
                            <li class="nav-item"><a
                                    class="nav-link {{ Route::is('admin.report') ? 'menu_active' : '' }}"
                                    href="{{ route('admin.report') }}"><i title="Reports"
                                        class="mdi mdi-chart-pie menu_ico_small"></i> <span
                                        class="menu_title_small">Sales</span></a></li>
                        @endif
                        @if (Auth::user()->can('report.corporate.sale'))
                            <li class="nav-item"><a
                                    class="nav-link {{ Route::is('admin.report.corporate.sales') ? 'menu_active' : '' }}"
                                    href="{{ route('admin.report.corporate.sales') }}"><i title="Corporate Sales Report"
                                        class="mdi mdi-chart-pie menu_ico_small"></i> <span
                                        class="menu_title_small">Corporate Sales</span></a></li>
                        @endif
                        @if (Auth::user()->can('report.products.sales'))
                            <li class="nav-item"><a
                                    class="nav-link {{ Route::is('admin.product.sale.report') ? 'menu_active' : '' }}"
                                    href="{{ route('admin.product.sale.report') }}"><i title="Reports"
                                        class="mdi mdi-chart-pie menu_ico_small"></i> <span
                                        class="menu_title_small">Product Sales</span></a></li>
                        @endif
                        @if (Auth::user()->can('report.balance.history'))
                            <li class="nav-item"><a
                                    class="nav-link {{ Route::is('admin.seller.account.history') ? 'menu_active' : '' }}"
                                    href="{{ route('admin.seller.account.history') }}"><i title="Balance History"
                                        class="mdi mdi-chart-pie menu_ico_small"></i> <span
                                        class="menu_title_small">Balance History</span></a></li>
                        @endif
                        @if (Auth::user()->can('report.category.sales'))
                            <li class="nav-item"><a
                                    class="nav-link {{ Route::is('admin.catagory.wise.product.sale') ? 'menu_active' : '' }}"
                                    href="{{ route('admin.catagory.wise.product.sale') }}"><i title="Category Wise"
                                        class="mdi mdi-chart-pie menu_ico_small"></i> <span
                                        class="menu_title_small">Category Sales</span></a></li>
                        @endif
                        @if (Auth::user()->can('report.seller.products'))
                            <li class="nav-item"><a
                                    class="nav-link {{ Route::is('admin.seller.product.wise') ? 'menu_active' : '' }}"
                                    href="{{ route('admin.seller.product.wise') }}"><i title="Seller Product Wise"
                                        class="mdi mdi-chart-pie menu_ico_small"></i> <span
                                        class="menu_title_small">Seller Products</span></a></li>
                        @endif
                        @if (Auth::user()->can('report.single.product'))
                            <li class="nav-item"><a
                                    class="nav-link {{ Route::is('admin.single.product.wise') || Route::is('admin.get.single.product.wise') ? 'menu_active' : '' }}"
                                    href="{{ route('admin.single.product.wise') }}"><i title="Single Product Wise"
                                        class="mdi mdi-chart-pie menu_ico_small"></i> <span
                                        class="menu_title_small">Single Product</span></a></li>
                        @endif
                        @if (Auth::user()->can('report.sales.confirm.status'))
                            <li class="nav-item"><a
                                    class="nav-link {{ Route::is('admin.sale.confirm.status.wise') ? 'menu_active' : '' }}"
                                    href="{{ route('admin.sale.confirm.status.wise') }}"><i
                                        title="Single Product Wise" class="mdi mdi-chart-pie menu_ico_small"></i>
                                    <span class="menu_title_small">Sales Confirm Status</span></a></li>
                        @endif
                        @if (Auth::user()->can('report.products.wishlist'))
                            <li class="nav-item"><a
                                    class="nav-link {{ Route::is('admin.product.wishlist') ? 'menu_active' : '' }}"
                                    href="{{ route('admin.product.wishlist') }}"><i title="Single Product Wise"
                                        class="mdi mdi-chart-pie menu_ico_small"></i>
                                    <span class="menu_title_small">Products Wishlist</span></a></li>
                        @endif
                        @if (Auth::user()->can('report.top.sold.products'))
                            <li class="nav-item"><a
                                    class="nav-link {{ Route::is('admin.top.sold.product.report') ? 'menu_active' : '' }}"
                                    href="{{ route('admin.top.sold.product.report') }}"><i
                                        title="Single Product Wise" class="mdi mdi-chart-pie menu_ico_small"></i>
                                    <span class="menu_title_small">Top Sold Products</span></a></li>
                        @endif
                        @if (Auth::user()->can('report.low.stock.item'))
                            <li class="nav-item"><a
                                    class="nav-link {{ Route::is('admin.low.stock.item.report') ? 'menu_active' : '' }}"
                                    href="{{ route('admin.low.stock.item.report') }}"><i
                                        title="Single Product Wise" class="mdi mdi-chart-pie menu_ico_small"></i>
                                    <span class="menu_title_small">Low Stock Item</span></a></li>
                        @endif
                        @if (Auth::user()->can('report.vat'))
                            <li class="nav-item"><a
                                    class="nav-link {{ Route::is('admin.vat.report') ? 'menu_active' : '' }}"
                                    href="{{ route('admin.vat.report') }}"><i
                                        title="Single Product Wise" class="mdi mdi-chart-pie menu_ico_small"></i>
                                    <span class="menu_title_small">Vat</span></a></li>
                        @endif

                        @if (Auth::user()->can('report.coupon.uses'))
                            <li class="nav-item"><a
                                    class="nav-link {{ Route::is('admin.report.coupon.uses.report') ? 'menu_active' : '' }}"
                                    href="{{ route('admin.report.coupon.uses.report') }}"><i
                                        title="Coupon Uses Report" class="mdi mdi-chart-pie menu_ico_small"></i>
                                    <span class="menu_title_small">Coupon Uses</span></a></li>
                        @endif

                    </ul>
                </div>
            </li>
        @endif

        @if (Auth::user()->can('voucher.view') || Auth::user()->can('coupon.view'))
            <li class="nav-item parent_group">
                <a class="nav-link" data-toggle="collapse" href="#Voucher" aria-expanded="false"
                    aria-controls="Voucher">
                    <i class="menu-icon mdi mdi-cash-multiple"></i>
                    <span class="menu-title">Voucher & Coupon</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="Voucher">
                    <ul class="nav flex-column sub-menu">

                        @if (Auth::user()->can('coupon.view'))
                            <li class="nav-item">
                                <a class="nav-link {{ Route::is('admin.coupons') || Route::is('admin.coupons') || Route::is('admin.coupons.edit') ? 'menu_active' : '' }}"
                                    href="{{ route('admin.coupons') }}">
                                    <i title="Coupons" class="menu-icon mdi mdi-cash"></i>
                                    <span class="menu-title">Coupons</span>
                                </a>
                            </li>
                        @endif

                        @if (Auth::user()->can('voucher.view'))
                            <li class="nav-item">
                                <a class="nav-link {{ Route::is('admin.voucher') || Route::is('admin.voucher.create') || Route::is('admin.voucher.edit') ? 'menu_active' : '' }}"
                                    href="{{ route('admin.voucher') }}">
                                    <i class="menu-icon mdi mdi-sale"></i>
                                    <span class="menu-title">Vouchers</span>
                                </a>
                            </li>
                        @endif
                        @if (Auth::user()->can('voucher.view'))
                            <li class="nav-item">
                                <a class="nav-link {{ Route::is('admin.voucher.category') || Route::is('admin.voucher.category.create') || Route::is('admin.voucher.category.edit') ? 'menu_active' : '' }}"
                                    href="{{ route('admin.voucher.category') }}">
                                    <i class="menu-icon mdi mdi-checkbox-multiple-marked"></i>
                                    <span class="menu-title">Voucher Category</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </li>
        @endif


        @if (Auth::user()->can('marketing.view'))
            <li class="nav-item parent_group">
                <a class="nav-link" data-toggle="collapse" href="#Marketing" aria-expanded="false"
                    aria-controls="Marketing">
                    <i class="menu-icon mdi mdi-chart-line"></i>
                    <span class="menu-title">Marketing</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="Marketing">
                    <ul class="nav flex-column sub-menu">
                        @if (Auth::user()->can('marketing.push.notification'))
                            <li class="nav-item"><a
                                    class="nav-link {{ Route::is('admin.marketing.push.notification') ? 'menu_active' : '' }}"
                                    href="{{ route('admin.marketing.push.notification') }}"><i title="Marketing"
                                        class="mdi mdi-bell-ring menu_ico_small"></i> <span
                                        class="menu_title_small">Send Push Notification</span></a></li>
                        @endif
                        @if (Auth::user()->can('marketing.bulk.message'))
                            <li class="nav-item"><a
                                    class="nav-link {{ Route::is('admin.marketing.bulk.message') ? 'menu_active' : '' }}"
                                    href="{{ route('admin.marketing.bulk.message') }}"><i title="Send Bulk Message"
                                        class="mdi mdi-contact-mail menu_ico_small"></i> <span
                                        class="menu_title_small">Send Bulk Message</span></a></li>
                        @endif

                        @if (Auth::user()->can('flash_deals.view'))
                            <li class="nav-item"><a
                                    class="nav-link {{ Route::is('admin.flash_deal') || Route::is('admin.flash_deal.create') || Route::is('admin.flash_deal.edit') ? 'menu_active' : '' }}"
                                    href="{{ route('admin.flash_deal') }}"><i title="Sliders"
                                        class="mdi mdi-monitor-multiple menu_ico_small"></i> <span
                                        class="menu_title_small">Flash Deals</span></a></li>
                        @endif

                        @if (Auth::user()->can('marketing.user.search'))
                            <li class="nav-item"><a
                                    class="nav-link {{ Route::is('admin.marketing.user.search.keyword') ? 'menu_active' : '' }}"
                                    href="{{ route('admin.marketing.user.search.keyword') }}"><i
                                        title="Send Bulk Message" class="mdi mdi-contact-mail menu_ico_small"></i>
                                    <span class="menu_title_small">Search Keywords</span></a></li>
                        @endif

                        @if (Auth::user()->can('marketing.subscriber'))
                            <li class="nav-item"><a
                                    class="nav-link {{ Route::is('admin.marketing.subscribers') ? 'menu_active' : '' }}"
                                    href="{{ route('admin.marketing.subscribers') }}"><i title="Subscriber list"
                                        class="mdi mdi-contact-mail menu_ico_small"></i>
                                    <span class="menu_title_small">Subscriber List</span></a></li>
                        @endif
                    </ul>
                </div>
            </li>
        @endif



        @if (Auth::user()->can('report.view'))
            <li class="nav-item parent_group">
                <a class="nav-link" data-toggle="collapse" href="#Accounting" aria-expanded="false"
                    aria-controls="Accounting">
                    <i title="Accounting" class="menu-icon mdi mdi-scale-balance"></i>
                    <span class="menu-title">Accounting</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="Accounting">
                    <ul class="nav flex-column sub-menu">
                        @if (Auth::user()->can('report.view'))
                            <li class="nav-item"><a
                                    class="nav-link {{ Route::is('admin.product.refund.report') ? 'menu_active' : '' }}"
                                    href="{{ route('admin.product.refund.report') }}"><i title="Refund Request"
                                        class="mdi mdi-update menu_ico_small"></i> <span
                                        class="menu_title_small">Refund Request</span></a></li>
                        @endif

                        @if (Auth::user()->can('report.view'))
                            <li class="nav-item"><a
                                    class="nav-link {{ Route::is('admin.vendor.withdrawal.request') ? 'menu_active' : '' }}"
                                    href="{{ route('admin.vendor.withdrawal.request') }}"><i
                                        title="Withdrawal Request" class="mdi mdi-cash-usd menu_ico_small"></i> <span
                                        class="menu_title_small">Withdrawal Request</span></a></li>
                        @endif
                    </ul>
                </div>
            </li>
        @endif


        @if (Auth::user()->can('affiliate.view'))
            <li class="nav-item parent_group">
                <a class="nav-link" data-toggle="collapse" href="#Affiliate" aria-expanded="false"
                    aria-controls="Affiliate">
                    <i title="Affiliate" class="menu-icon mdi mdi-bullhorn"></i>
                    <span class="menu-title">Affiliate</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="Affiliate">
                    <ul class="nav flex-column sub-menu">
                        @if (Auth::user()->can('affiliate.view'))
                            <li class="nav-item">
                                <a class="nav-link {{ Route::is('admin.affiliate') || Route::is('admin.affiliate') ? 'menu_active' : '' }}"
                                    href="{{ route('admin.affiliate') }}">
                                    <i title="Affiliate" class="mdi mdi-update menu_ico_small"></i> <span
                                        class="menu_title_small">Affiliate</span>
                                </a>
                            </li>
                        @endif
                        @if (Auth::user()->can('affiliate.view'))
                            <li class="nav-item">
                                <a class="nav-link {{ Route::is('admin.affiliate.withdrawal') || Route::is('admin.affiliate.withdrawal') ? 'menu_active' : '' }}"
                                    href="{{ route('admin.affiliate.withdrawal') }}">
                                    <i title="Affiliate" class="mdi mdi-update menu_ico_small"></i> <span
                                        class="menu_title_small">Withdrawal Request</span>
                                </a>
                            </li>
                        @endif

                    </ul>
                </div>
            </li>
        @endif


        @if (Auth::user()->can('ticket.view'))
            <li class="nav-item parent_group">
                <a class="nav-link" data-toggle="collapse" href="#Tickets" aria-expanded="false"
                    aria-controls="Tickets">
                    <i title="Tickets" class="menu-icon mdi mdi-message-processing"></i>
                    <span class="menu-title">Tickets</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="Tickets">
                    <ul class="nav flex-column sub-menu">
                        @if (Auth::user()->can('ticket.view'))
                            <li class="nav-item">
                                <a class="nav-link {{ Route::is('admin.ticket.create') || Route::is('admin.ticket.edit') || Route::is('admin.ticket.replay') || Route::is('admin.ticket') ? 'menu_active' : '' }}"
                                    href="{{ route('admin.ticket') }}">
                                    <i title="Tickets" class="mdi mdi-update menu_ico_small"></i> <span
                                        class="menu_title_small">Tickets</span>
                                </a>
                            </li>
                        @endif

                    </ul>
                </div>
            </li>
        @endif



        @if (Auth::user()->can('import.view'))
            <li class="nav-item parent_group">
                <a class="nav-link" data-toggle="collapse" href="#import" aria-expanded="false"
                    aria-controls="Imports">
                    <i class="menu-icon mdi mdi-upload"></i>
                    <span class="menu-title">Bulk Imports</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="import">
                    <ul class="nav flex-column sub-menu">
                        @if (Auth::user()->can('import.view') && Auth::user()->can('import.create'))
                            <li class="nav-item"><a
                                    class="nav-link {{ Route::is('admin.import.product.csv') ? 'menu_active' : '' }}"
                                    href="{{ route('admin.import.product.csv') }}"> <i title="Products Import"
                                        class="mdi mdi-drawing-box menu_ico_small"></i> <span
                                        class="menu_title_small">Products Import</span></a></li>
                        @endif
                        @if (Auth::user()->can('import.view') && Auth::user()->can('import.create'))
                            <li class="nav-item"><a
                                    class="nav-link {{ Route::is('admin.import.category.csv') ? 'menu_active' : '' }}"
                                    href="{{ route('admin.import.category.csv') }}"> <i title="Category Import"
                                        class="mdi mdi-drawing-box menu_ico_small"></i> <span
                                        class="menu_title_small">Category Import</span></a></li>
                        @endif
                        @if (Auth::user()->can('import.view') && Auth::user()->can('import.create'))
                            <li class="nav-item"><a
                                    class="nav-link {{ Route::is('admin.import.brand.csv') ? 'menu_active' : '' }}"
                                    href="{{ route('admin.import.brand.csv') }}"> <i title="Brand Import"
                                        class="mdi mdi-drawing-box menu_ico_small"></i> <span
                                        class="menu_title_small">Brand Import</span></a></li>
                        @endif
                        @if (Auth::user()->can('import.view') && Auth::user()->can('import.create'))
                            <li class="nav-item"><a
                                    class="nav-link {{ Route::is('admin.import.customer.csv') ? 'menu_active' : '' }}"
                                    href="{{ route('admin.import.customer.csv') }}"> <i title="Customer Import"
                                        class="mdi mdi-drawing-box menu_ico_small"></i> <span
                                        class="menu_title_small">Customer Import</span></a></li>
                        @endif
                        @if (Auth::user()->can('import.view') && Auth::user()->can('import.create'))
                            <li class="nav-item"><a
                                    class="nav-link {{ Route::is('admin.import.seller.csv') ? 'menu_active' : '' }}"
                                    href="{{ route('admin.import.seller.csv') }}"> <i title="Seller Import"
                                        class="mdi mdi-drawing-box menu_ico_small"></i> <span
                                        class="menu_title_small">Seller Import</span></a></li>
                        @endif
                        @if (Auth::user()->can('import.view') && Auth::user()->can('import.create'))
                            <li class="nav-item"><a
                                    class="nav-link {{ Route::is('admin.import.product.image.csv') ? 'menu_active' : '' }}"
                                    href="{{ route('admin.import.product.image.csv') }}"> <i title="Products Update"
                                        class="mdi mdi-drawing-box menu_ico_small"></i> <span
                                        class="menu_title_small">Product Image Import</span></a></li>
                        @endif
                        @if (Auth::user()->can('import.view') && Auth::user()->can('import.create'))
                            <li class="nav-item"><a
                                    class="nav-link {{ Route::is('admin.import.product.update.csv') ? 'menu_active' : '' }}"
                                    href="{{ route('admin.import.product.update.csv') }}"> <i title="Products Update"
                                        class="mdi mdi-drawing-box menu_ico_small"></i> <span
                                        class="menu_title_small">Products Update</span></a></li>
                        @endif
                    </ul>
                </div>
            </li>
        @endif



        @if (Auth::user()->can('career.view'))
            <li class="nav-item parent_group">
                <a class="nav-link" data-toggle="collapse" href="#Career" aria-expanded="false"
                    aria-controls="Career">
                    <i class="menu-icon mdi mdi-account-multiple"></i>
                    <span class="menu-title">Career</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="Career">
                    <ul class="nav flex-column sub-menu">
                        @if (Auth::user()->can('career.view'))
                            <li class="nav-item">
                                <a class="nav-link {{ Route::is('admin.career.list') || Route::is('admin.career.create') || Route::is('admin.career.edit') ? 'menu_active' : '' }}"
                                    href="{{ route('admin.career.list') }}">
                                    <i class="menu-icon mdi mdi-format-list-bulleted"></i>
                                    <span class="menu-title">List</span>
                                </a>
                            </li>
                        @endif
                        @if (Auth::user()->can('career.request'))
                            <li class="nav-item">
                                <a class="nav-link {{ Route::is('admin.career.request') ? 'menu_active' : '' }}"
                                    href="{{ route('admin.career.request') }}">
                                    <i class="menu-icon mdi mdi-file-account"></i>
                                    <span class="menu-title">Career Request</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </li>
        @endif




        @if (Auth::user()->can('blog.view'))
            <li class="nav-item parent_group">
                <a class="nav-link" data-toggle="collapse" href="#Blog" aria-expanded="false"
                    aria-controls="Blog">
                    <i class="menu-icon mdi mdi-home-map-marker"></i>
                    <span class="menu-title">Blogs</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="Blog">
                    <ul class="nav flex-column sub-menu">
                        @if (Auth::user()->can('blog.view'))
                            <li class="nav-item">
                                <a class="nav-link {{ Route::is('admin.blog') || Route::is('admin.blog.create') || Route::is('admin.blog.edit') ? 'menu_active' : '' }}"
                                    href="{{ route('admin.blog') }}">
                                    <i class="menu-icon mdi mdi-home-variant"></i>
                                    <span class="menu-title">Blog</span>
                                </a>
                            </li>
                        @endif
                        @if (Auth::user()->can('blog.category.view'))
                            <li class="nav-item">
                                <a class="nav-link {{ Route::is('admin.blog.category') || Route::is('admin.blog.category.create') || Route::is('admin.blog.category.edit') ? 'menu_active' : '' }}"
                                    href="{{ route('admin.blog.category') }}">
                                    <i class="menu-icon mdi mdi-checkbox-multiple-marked"></i>
                                    <span class="menu-title">Blog Category</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </li>
        @endif

        @if (Auth::user()->can('admin.view') || Auth::user()->can('vendor.view') || Auth::user()->can('customer.view'))
            <li class="nav-item parent_group">
                <a class="nav-link" data-toggle="collapse" href="#Users" aria-expanded="false"
                    aria-controls="Users">
                    <i class="menu-icon mdi mdi-account-multiple"></i>
                    <span class="menu-title">User Management</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="Users">
                    <ul class="nav flex-column sub-menu">

                        @if (Auth::user()->can('admin.view'))
                            <li class="nav-item"><a
                                    class="nav-link {{ Route::is('admin.administrator') || Route::is('admin.administrator.create') || Route::is('admin.administrator.edit') ? 'menu_active' : '' }}"
                                    href="{{ route('admin.administrator') }}"><i title="Administrators"
                                        class="mdi mdi-account-key menu_ico_small"></i> <span
                                        class="menu_title_small">Administrators</span></a></li>
                        @endif

                        @if (Auth::user()->can('vendor.view'))
                            <li class="nav-item"><a
                                    class="nav-link {{ Route::is('admin.vendor') || Route::is('admin.vendor.create') || Route::is('admin.vendor.accounts') || Route::is('admin.vendor.edit') ? 'menu_active' : '' }}"
                                    href="{{ route('admin.vendor') }}"><i title="Sellers"
                                        class="mdi  mdi-run menu_ico_small"></i> <span
                                        class="menu_title_small">Sellers</span></a></li>
                        @endif

                        @if (Auth::user()->can('customer.view'))
                            <li class="nav-item"><a
                                    class="nav-link {{ Route::is('admin.user') || Route::is('admin.user.create') || Route::is('admin.user.edit') ? 'menu_active' : '' }}"
                                    href="{{ route('admin.user') }}"><i title="Customers"
                                        class="mdi mdi-account-multiple menu_ico_small"></i> <span
                                        class="menu_title_small">Customers</span></a></li>
                        @endif

                    </ul>
                </div>
            </li>
        @endif

        @if (Auth::user()->can('role.view') || Auth::user()->can('role.create'))
            <li class="nav-item parent_group">
                <a class="nav-link" data-toggle="collapse" href="#UserRules" aria-expanded="false"
                    aria-controls="Users">
                    <i class="menu-icon mdi mdi-clipboard-account"></i>
                    <span class="menu-title">Roles & Permissions</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="UserRules">
                    <ul class="nav flex-column sub-menu">
                        @if (Auth::user()->can('role.view'))
                            <li class="nav-item"><a
                                    class="nav-link {{ Route::is('roles.index') || Route::is('roles.create') || Route::is('roles.edit') ? 'menu_active' : '' }}"
                                    href="{{ route('roles.index') }}"><i title="Roles"
                                        class="mdi mdi-nature-people menu_ico_small"></i> <span
                                        class="menu_title_small">Roles</span></a></li>
                        @endif

                    </ul>
                </div>
            </li>
        @endif



        @if (Auth::user()->can('slider.view') ||
            Auth::user()->can('testimonials.view') ||
            Auth::user()->can('design.view') ||
            Auth::user()->can('language.view'))
            <li class="nav-item parent_group">
                <a class="nav-link" data-toggle="collapse" href="#Appearance" aria-expanded="false"
                    aria-controls="Appearance">
                    <i class="menu-icon mdi mdi-settings"></i>
                    <span class="menu-title">Settings</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="Appearance">
                    <ul class="nav flex-column sub-menu">

                        @if(Auth::user()->can('navbar.view'))
                            <li class="nav-item"><a
                                    class="nav-link {{ Route::is('admin.navbar') || Route::is('admin.navbar.create') || Route::is('admin.navbar.edit') ? 'menu_active' : '' }}"
                                    href="{{ route('admin.navbar') }}"><i title="Navbars"
                                        class="mdi mdi-monitor-multiple menu_ico_small"></i> <span
                                        class="menu_title_small">Navbar</span></a></li>
                        @endif 

                        @if (Auth::user()->can('language.view'))
                            <li class="nav-item">
                                <a class="nav-link {{ Route::is('admin.language') || Route::is('admin.language.create') || Route::is('admin.language.edit') ? 'menu_active' : '' }}"
                                    href="{{ route('admin.language') }}">
                                    {{-- <i class="menu-icon mdi mdi-flag"></i> --}}
                                    <span class="menu-title">Languages</span>
                                </a>
                            </li>
                        @endif

                        @if (Auth::user()->can('pick_point.view'))
                            <li class="nav-item"><a
                                    class="nav-link {{ Route::is('admin.pick_points') || Route::is('admin.pick_points.create') || Route::is('admin.pick_points.edit') ? 'menu_active' : '' }}"
                                    href="{{ route('admin.pick_points') }}"><i title="Sliders"
                                        class="mdi mdi-home-map-marker menu_ico_small"></i> <span
                                        class="menu_title_small">Pick Points</span></a></li>
                        @endif

                        @if(Auth::user()->can('slider.view'))
                            <li class="nav-item"><a
                                    class="nav-link {{ Route::is('admin.slider') || Route::is('admin.slider.create') || Route::is('admin.slider.edit') ? 'menu_active' : '' }}"
                                    href="{{ route('admin.slider') }}"><i title="Sliders"
                                        class="mdi mdi-monitor-multiple menu_ico_small"></i> <span
                                        class="menu_title_small">Sliders</span></a></li>
                        @endif
                        {{-- @if (Auth::user()->can('testimonials.view'))
			        <li class="nav-item"><a class="nav-link {{ (Route::is('admin.testimonial') || Route::is('admin.testimonial.create') || Route::is('admin.testimonial.edit')) ? 'menu_active' : '' }}" href="{{ route('admin.testimonial')}}"><i title="Testimonials" class="mdi mdi-nature-people menu_ico_small"></i> <span class="menu_title_small">Testimonials</span></a></li>
            @endif --}}

                        @if (Auth::user()->can('design.view'))
                            <li class="nav-item"><a
                                    class="nav-link {{ Route::is('admin.designs') || Route::is('admin.designs.create') || Route::is('admin.designs.edit') ? 'menu_active' : '' }}"
                                    href="{{ route('admin.designs') }}"><i title="Design & Settings"
                                        class="mdi mdi-auto-fix menu_ico_small"></i> <span
                                        class="menu_title_small">Design & Settings</span></a></li>
                        @endif

                        @if (Auth::user()->can('environment.view'))
                            <li class="nav-item"><a
                                    class="nav-link {{ Route::is('admin.designs.environment') ? 'menu_active' : '' }}"
                                    href="{{ route('admin.designs.environment') }}"><i title="Environments"
                                        class="mdi mdi-auto-fix menu_ico_small"></i> <span
                                        class="menu_title_small">Environments</span></a></li>
                        @endif

                        @if (Auth::user()->can('search.dashboard'))
                            <li class="nav-item"><a
                                    class="nav-link {{ Route::is('admin.search.dashboard.index') || Route::is('admin.create.search.dashboard') || Route::is('admin.edit.search.dashboard')  ? 'menu_active' : '' }}"
                                    href="{{ route('admin.search.dashboard.index') }}"><i title="Search"
                                        class="mdi mdi-auto-fix menu_ico_small"></i> <span
                                        class="menu_title_small">Search Dashboard</span></a></li>
                        @endif
                    </ul>
                </div>
            </li>
        @endif

        @if (Auth::user()->can('location.view'))
            <li class="nav-item parent_group">
                <a class="nav-link" data-toggle="collapse" href="#Locations" aria-expanded="false"
                    aria-controls="Locations">
                    <i class="menu-icon mdi mdi-map-marker"></i>
                    <span class="menu-title">Locations</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="Locations">
                    <ul class="nav flex-column sub-menu">



                        <li class="nav-item">
                            <a class="nav-link {{ Route::is('admin.location.division') ? 'menu_active' : '' }}"
                                href="{{ route('admin.location.division') }}">
                                <i title="Sliders" class="mdi mdi-home-map-marker menu_ico_small"></i>
                                <span class="menu_title_small">Division</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ Route::is('admin.location.district') ? 'menu_active' : '' }}"
                                href="{{ route('admin.location.district') }}">
                                <i title="Sliders" class="mdi mdi-home-map-marker menu_ico_small"></i>
                                <span class="menu_title_small">District</span>
                            </a>
                        </li>


                        <li class="nav-item">
                            <a class="nav-link {{ Route::is('admin.location.upazila') ? 'menu_active' : '' }}"
                                href="{{ route('admin.location.upazila') }}">
                                <i title="Sliders" class="mdi mdi-home-map-marker menu_ico_small"></i>
                                <span class="menu_title_small">Upazila/Thana</span>
                            </a>
                        </li>


                        <li class="nav-item">
                            <a class="nav-link {{ Route::is('admin.location.union') ? 'menu_active' : '' }}"
                                href="{{ route('admin.location.union') }}">
                                <i title="Sliders" class="mdi mdi-home-map-marker menu_ico_small"></i>
                                <span class="menu_title_small">Union/Area</span>
                            </a>
                        </li>



                    </ul>
                </div>
            </li>
        @endif


        @if (Auth::user()->can('pages.view'))
            <li class="nav-item">
                <a class="nav-link {{ Route::is('admin.page') || Route::is('admin.page.create') || Route::is('admin.page.edit') ? 'menu_active' : '' }}"
                    href="{{ route('admin.page') }}">
                    <i title="Pages" class="menu-icon mdi mdi-file-document"></i>
                    <span class="menu-title">Pages</span>
                </a>
            </li>
        @endif

        @if (Auth::user()->can('settings.view'))
            <li class="nav-item">
                <a class="nav-link {{ Route::is('admin.settings') || Route::is('admin.settings.create') || Route::is('admin.settings.edit') ? 'menu_active' : '' }}"
                    href="{{ route('admin.settings') }}">
                    <i class="menu-icon mdi mdi-information-outline"></i>
                    <span class="menu-title">Configuration</span>
                </a>
            </li>
        @endif

    </ul>
</nav>
