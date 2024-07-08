<button class="m-aside-left-close  m-aside-left-close--skin-dark " id="m_aside_left_close_btn">
    <i class="la la-close"></i>
</button>
<div id="m_aside_left" class="m-grid__item	m-aside-left  m-aside-left--skin-dark ">
    <!-- BEGIN: Aside Menu -->
    <div id="m_ver_menu" class="m-aside-menu  m-aside-menu--skin-dark m-aside-menu--submenu-skin-dark "
         data-menu-vertical="true" data-menu-scrollable="false" data-menu-dropdown-timeout="500">
        <ul class="m-menu__nav  m-menu__nav--dropdown-submenu-arrow ">
            <li class="m-menu__item " aria-haspopup="true">
                <a href="{!! route('admin.dashboard.index') !!}" class="m-menu__link ">
                    <i class="m-menu__link-icon flaticon-line-graph"></i>
                    <span class="m-menu__link-title">
                        <span class="m-menu__link-wrap">
                            <span class="m-menu__link-text">
                                Dashboard
                            </span>
                        </span>
                    </span>
                </a>
            </li>
            <li class="{!! str_contains(url()->current(), route('admin.dashboard.cities.index') )?'nav-active':'' !!} m-menu__item  m-menu__item--submenu"
                aria-haspopup="true" data-menu-submenu-toggle="hover">
                <a href="{!! route('admin.dashboard.cities.index') !!}" class="m-menu__link m-menu__toggle">
                    <i class="m-menu__link-icon "></i>
                    <span class="m-menu__link-text">Cities</span>
                </a>
            </li>
            <li class="{!! str_contains(url()->current(), route('admin.dashboard.categories.index')) ? 'nav-active' : '' !!} m-menu__item  m-menu__item--submenu"
                aria-haspopup="true"
                data-menu-submenu-toggle="hover">
                <a href="{!! route('admin.dashboard.categories.index') !!}" class="m-menu__link m-menu__toggle">
                    <i class="m-menu__link-icon "></i>
                    <span class="m-menu__link-text">
                         Category
                    </span>
                </a>
            </li>

            <li class="{!! str_contains(url()->current(), route('admin.dashboard.product.index')) ? 'nav-active' : '' !!} m-menu__item  m-menu__item--submenu"
                aria-haspopup="true"
                data-menu-submenu-toggle="hover">
                <a href="{!! route('admin.dashboard.product.index') !!}" class="m-menu__link m-menu__toggle">
                    <i class="m-menu__link-icon "></i>
                    <span class="m-menu__link-text">Products</span>
                </a>
            </li>
            <li class="{!! str_contains(url()->current(), route('admin.dashboard.users.index') )?'nav-active':'' !!} m-menu__item  m-menu__item--submenu"
                aria-haspopup="true" data-menu-submenu-toggle="hover">
                <a href="{!! route('admin.dashboard.users.index') !!}" class="m-menu__link m-menu__toggle">
                    <i class="m-menu__link-icon "></i>
                    <span class="m-menu__link-text">Users</span>
                </a>
            </li>

            <li class="{!! str_contains(url()->current(), route('admin.dashboard.riders.index')) ? 'nav-active' : '' !!} m-menu__item  m-menu__item--submenu"
                aria-haspopup="true"
                data-menu-submenu-toggle="hover">
                <a href="{!! route('admin.dashboard.riders.index') !!}" class="m-menu__link m-menu__toggle">
                    <i class="m-menu__link-icon "></i>
                    <span class="m-menu__link-text">Riders</span>
                </a>
            </li>


            <li class="{!! str_contains(url()->current(), route('admin.dashboard.suppliers.index') )?'nav-active':'' !!} m-menu__item  m-menu__item--submenu"
                aria-haspopup="true" data-menu-submenu-toggle="hover">
                <a href="{!! route('admin.dashboard.suppliers.index') !!}" class="m-menu__link m-menu__toggle">
                    <i class="m-menu__link-icon "></i>
                    <span class="m-menu__link-text">Suppliers</span>
                </a>
            </li>
            <li class="{!! str_contains(url()->current(), route('admin.dashboard.delivery-companies.index') )?'nav-active':'' !!} m-menu__item  m-menu__item--submenu"
                aria-haspopup="true" data-menu-submenu-toggle="hover">
                <a href="{!! route('admin.dashboard.delivery-companies.index') !!}" class="m-menu__link m-menu__toggle">
                    <i class="m-menu__link-icon "></i>
                    <span class="m-menu__link-text">Delivery Companies</span>
                </a>
            </li>
            <li class="m-menu__item  m-menu__item--submenu" aria-haspopup="true" data-menu-submenu-toggle="hover">
                <a href="#" class="m-menu__link m-menu__toggle">
                    <i class="m-menu__link-icon "></i>
                    <span class="m-menu__link-text">Riders</span>
                </a>
            </li>

            <li class="{!! str_contains(url()->current(), route('admin.dashboard.subscriptions.index') )?'nav-active':'' !!} m-menu__item  m-menu__item--submenu"
                aria-haspopup="true" data-menu-submenu-toggle="hover">
                <a href="{!! route('admin.dashboard.subscriptions.index') !!}" class="m-menu__link m-menu__toggle">
                    <i class="m-menu__link-icon "></i>
                    <span class="m-menu__link-text">Packages</span>
                </a>
            </li>
            <li class="{!! str_contains(url()->current(), route('admin.dashboard.featured-subscription.index') )?'nav-active':'' !!} m-menu__item  m-menu__item--submenu"
                aria-haspopup="true" data-menu-submenu-toggle="hover">
                <a href="{!! route('admin.dashboard.featured-subscription.index') !!}"
                   class="m-menu__link m-menu__toggle">
                    <i class="m-menu__link-icon "></i>
                    <span class="m-menu__link-text">Featured Packages</span>
                </a>
            </li>

            <li class="{!! str_contains(url()->current(), route('admin.dashboard.products.index') )?'nav-active':'' !!} m-menu__item  m-menu__item--submenu"
                aria-haspopup="true" data-menu-submenu-toggle="hover">
                <a href="{!! route('admin.dashboard.products.index') !!}" class="m-menu__link m-menu__toggle">
                    <i class="m-menu__link-icon "></i>
                    <span class="m-menu__link-text">Products</span>
                </a>
            </li>

            <li class="{!! str_contains(url()->current(), route('admin.dashboard.offers.index') )?'nav-active':'' !!} m-menu__item  m-menu__item--submenu"
                aria-haspopup="true" data-menu-submenu-toggle="hover">
                <a href="{!! route('admin.dashboard.offers.index') !!}" class="m-menu__link m-menu__toggle">
                    <i class="m-menu__link-icon "></i>
                    <span class="m-menu__link-text">Offers</span>
                </a>
            </li>

            <li class="{!! str_contains(url()->current(), route('admin.dashboard.articles.index') )?'nav-active':'' !!} m-menu__item  m-menu__item--submenu"
                aria-haspopup="true" data-menu-submenu-toggle="hover">
                <a href="{!! route('admin.dashboard.articles.index') !!}" class="m-menu__link m-menu__toggle">
                    <i class="m-menu__link-icon "></i>
                    <span class="m-menu__link-text">Articles</span>
                </a>
            </li>

            <li class="{!! str_contains(url()->current(), route('admin.dashboard.galleries.index') )?'nav-active':'' !!} m-menu__item  m-menu__item--submenu"
                aria-haspopup="true" data-menu-submenu-toggle="hover">
                <a href="{!! route('admin.dashboard.galleries.index') !!}" class="m-menu__link m-menu__toggle">
                    <i class="m-menu__link-icon "></i>
                    <span class="m-menu__link-text">Gallery</span>
                </a>
            </li>

            <li class="{!! str_contains(url()->current(), route('admin.dashboard.faqs.index') )?'nav-active':'' !!} m-menu__item  m-menu__item--submenu"
                aria-haspopup="true" data-menu-submenu-toggle="hover">
                <a href="{!! route('admin.dashboard.faqs.index') !!}" class="m-menu__link m-menu__toggle">
                    <i class="m-menu__link-icon "></i>
                    <span class="m-menu__link-text">FAQs</span>
                </a>
            </li>

            <li class="{!! str_contains(url()->current(), route('admin.dashboard.pages.index') )?'nav-active':'' !!} m-menu__item  m-menu__item--submenu"
                aria-haspopup="true" data-menu-submenu-toggle="hover">
                <a href="{!! route('admin.dashboard.pages.index') !!}" class="m-menu__link m-menu__toggle">
                    <i class="m-menu__link-icon "></i>
                    <span class="m-menu__link-text">Info Pages</span>
                </a>
            </li>

            <li class="{!! str_contains(url()->current(), route('admin.dashboard.withdraws.index') )?'nav-active':'' !!} m-menu__item  m-menu__item--submenu"
                aria-haspopup="true" data-menu-submenu-toggle="hover">
                <a href="{!! route('admin.dashboard.withdraws.index') !!}" class="m-menu__link m-menu__toggle">
                    <i class="m-menu__link-icon "></i>
                    <span class="m-menu__link-text">Withdraws</span>
                </a>
            </li>

            <li class="{!! str_contains(url()->current(), route('admin.dashboard.site-settings.index') )?'nav-active':'' !!} m-menu__item  m-menu__item--submenu"
                aria-haspopup="true" data-menu-submenu-toggle="hover">
                <a href="{!! route('admin.dashboard.site-settings.index') !!}" class="m-menu__link m-menu__toggle">
                    <i class="m-menu__link-icon "></i>
                    <span class="m-menu__link-text">Site Settings</span>
                </a>
            </li>

            <li class="{!! str_contains(url()->current(), route('admin.dashboard.administrators.index') )?'nav-active':'' !!} m-menu__item  m-menu__item--submenu"
                aria-haspopup="true" data-menu-submenu-toggle="hover">
                <a href="{!! route('admin.dashboard.administrators.index') !!}" class="m-menu__link m-menu__toggle">
                    <i class="m-menu__link-icon "></i>
                    <span class="m-menu__link-text">Administrators</span>
                </a>
            </li>

        </ul>
    </div>
    <!-- END: Aside Menu -->
</div>
