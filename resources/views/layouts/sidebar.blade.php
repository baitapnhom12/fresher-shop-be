<aside class="main-sidebar sidebar-dark-primary elevation-4 position-fixed">
    <!-- Brand Logo -->
    <a href="{{ route('home') }}" class="brand-link">
        <img src="/admin-layout/dist/img/AdminLTELogo.png" alt="HTV Admin Logo" class="brand-image img-circle elevation-3"
            style="opacity: .8">
        <span class="brand-text font-weight-light">HTV Admin</span>
    </a>
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
       with font-awesome or any other icon font library -->
                <li class="nav-item {{ Request::segment(2) == 'home' ? 'menu-open' : '' }}">
                    <a href="{{ route('home') }}" class="nav-link {{ Request::segment(2) == 'home' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>

                <li class="nav-item {{ Request::segment(2) == 'orders' ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ Request::segment(2) == 'orders' ? 'active' : '' }}">
                        <i class="nav-icon fa-solid fa-brands fa-google-wallet"></i>
                        <p>
                            Orders
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li
                            class="nav-item {{ Request::segment(3) == '' && Request::segment(2) == 'orders' ? 'bg-light' : '' }}">
                            <a href="{{ route('admin.order.list') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>List</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item {{ Request::segment(2) == 'categories' ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ Request::segment(2) == 'categories' ? 'active' : '' }}">
                        <i class="nav-icon fa-solid fa-layer-group"></i>
                        <p>
                            Categories
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li
                            class="nav-item {{ Request::segment(3) == 'list' && Request::segment(2) == 'categories' ? 'bg-light' : '' }}">
                            <a href="{{ route('category.list') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>List</p>
                            </a>
                        </li>

                        <li
                            class="nav-item {{ Request::segment(3) == 'create' && Request::segment(2) == 'categories' ? 'bg-light' : '' }}">
                            <a href="{{ route('category.create') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Create</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item {{ Request::segment(2) == 'products' ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ Request::segment(2) == 'products' ? 'active' : '' }}">
                        <i class="nav-icon fa-brands fa-product-hunt"></i>
                        <p>
                            Products
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li
                            class="nav-item {{ Request::segment(3) == '' && Request::segment(2) == 'products' ? 'bg-light' : '' }}">
                            <a href="{{ route('admin.product.list') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>List</p>
                            </a>
                        </li>

                        <li
                            class="nav-item {{ Request::segment(3) == 'create' && Request::segment(2) == 'products' ? 'bg-light' : '' }}">
                            <a href="{{ route('admin.product.create') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Create</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item {{ Request::segment(2) == 'brands' ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ Request::segment(2) == 'brands' ? 'active' : '' }}">
                        <i class="nav-icon fa-brands fa-slack"></i>
                        <p>
                            Brands
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li
                            class="nav-item {{ Request::segment(3) == '' && Request::segment(2) == 'brands' ? 'bg-light' : '' }}">
                            <a href="{{ Route('brand.list') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>List</p>
                            </a>
                        </li>
                        <li
                            class="nav-item {{ Request::segment(3) == 'create' && Request::segment(2) == 'brands' ? 'bg-light' : '' }}">
                            <a href="{{ Route('brand.create') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Create</p>
                            </a>
                        </li>
                    </ul>

                </li>
                <li class="nav-item {{ Request::segment(2) == 'sizes' ? 'menu-open' : '' }}">
                    <a href="{{ Route('size.index') }}"
                        class="nav-link {{ Request::segment(2) == 'sizes' ? 'active' : '' }}">
                        <i class="nav-icon fa-solid fa-sitemap"></i>
                        <p>
                            Sizes
                        </p>
                    </a>

                </li>
                <li class="nav-item {{ Request::segment(2) == 'concentrations' ? 'menu-open' : '' }}">
                    <a href="{{ Route('concentration.index') }}"
                        class="nav-link {{ Request::segment(2) == 'concentrations' ? 'active' : '' }}">
                        <i class="fa-solid fa-spray-can-sparkles nav-icon"></i>
                        <p>
                            Concentrations
                        </p>
                    </a>
                </li>
                <li class="nav-item {{ Request::segment(2) == 'discounts' ? 'menu-open' : '' }}">
                    <a href="{{ Route('discount.index') }}"
                        class="nav-link {{ Request::segment(2) == 'discounts' ? 'active' : '' }}">
                        <i class="nav-icon fa-solid fa-percent"></i>
                        <p>
                            Discounts
                        </p>
                    </a>

                </li>
                <li class="nav-item {{ Request::segment(2) == 'banners' ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ Request::segment(2) == 'banners' ? 'active' : '' }}">
                        <i class="nav-icon fa-solid fa-images"></i>
                        <p>
                            Banners
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li
                            class="nav-item {{ Request::segment(3) == '' && Request::segment(2) == 'banners' ? 'bg-light' : '' }}">
                            <a href="{{ Route('banner.list') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>List</p>
                            </a>
                        </li>
                        <li
                            class="nav-item  {{ Request::segment(3) == 'create' && Request::segment(2) == 'banners' ? 'bg-light' : '' }}">
                            <a href="{{ Route('banner.create') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Create</p>
                            </a>
                        </li>
                    </ul>

                </li>
                <li class="nav-item {{ Request::segment(2) == 'coupons' ? 'menu-open' : '' }}">
                    <a href="{{ Route('coupon.index') }}"
                        class="nav-link {{ Request::segment(2) == 'coupons' ? 'active' : '' }}">
                        <i class="nav-icon fa-solid fa-ticket"></i>
                        <p>
                            Coupons
                        </p>
                    </a>
                </li>
                <li class="nav-item {{ Request::segment(2) == 'articles' ? 'menu-open' : '' }}">
                    <a href="{{ Route('article.index') }}"
                        class="nav-link {{ Request::segment(2) == 'articles' ? 'active' : '' }}">
                        <i class="nav-icon fa-solid fa-tag"></i>
                        <p>
                            Categories Post
                        </p>
                    </a>
                </li>
                <li class="nav-item {{ Request::segment(2) == 'posts' ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ Request::segment(2) == 'posts' ? 'active' : '' }}">
                        <i class="fa-regular fa-file-lines nav-icon"></i>
                        <p>
                            Posts
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li
                            class="nav-item {{ Request::segment(3) == '' && Request::segment(2) == 'posts' ? 'bg-light' : '' }}">
                            <a href="{{ Route('post.list') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>List</p>
                            </a>
                        </li>
                        <li class="nav-item {{ Request::segment(3) == 'create' ? 'bg-light' : '' }}">
                            <a href="{{ Route('post.create') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Create</p>
                            </a>
                        </li>
                    </ul>

                </li>
                <li class="nav-item {{ Request::segment(2) == 'pages' ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ Request::segment(2) == 'pages' ? 'active' : '' }}">
                        <i class="fa-solid fa-folder nav-icon"></i>
                        <p>
                            Pages Manager
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li
                            class="nav-item {{ Request::segment(3) == '' && Request::segment(2) == 'pages' ? 'bg-light' : '' }}">
                            <a href="{{ Route('page.list') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>List</p>
                            </a>
                        </li>
                        <li class="nav-item {{ Request::segment(3) == 'create' ? 'bg-light' : '' }}">
                            <a href="{{ Route('page.create') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Create</p>
                            </a>
                        </li>
                    </ul>

                </li>
                <li class="nav-item {{ Request::segment(2) == 'features' ? 'menu-open' : '' }}">
                    <a href="{{ Route('feature.index') }}"
                        class="nav-link {{ Request::segment(2) == 'features' ? 'active' : '' }}">
                        <i class="fa-brands fa-rebel nav-icon"></i>
                        <p>
                            Features
                        </p>
                    </a>
                </li>
                <li class="nav-item {{ Request::segment(2) == 'contacts' ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ Request::segment(2) == 'contacts' ? 'active' : '' }}">
                        <i class="fa-solid fa-id-card-clip nav-icon"></i>
                        <p>
                            Contacts
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item {{ Request::segment(3) == 'feedbacks' ? 'bg-light' : '' }}">
                            <a href="{{ Route('feedback.index') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>FeedBacks</p>
                            </a>
                        </li>
                        <li class="nav-item {{ Request::segment(3) == 'questions' ? 'bg-light' : '' }}">
                            <a href="{{ Route('question.index') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Questions</p>
                            </a>
                        </li>
                        <li class="nav-item {{ Request::segment(3) == 'informations' ? 'bg-light' : '' }}">
                            <a href="{{ Route('information.index') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Information</p>
                            </a>
                        </li>
                    </ul>

                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
</aside>
