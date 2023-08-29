@php
    $user_id=Auth::user()->id;
    $permissionList=\App\Models\user_permissions::where('user_id',$user_id)->get();
    $arr=[];
    foreach ($permissionList as $plist)
        {
            array_push($arr,$plist->permissions->name);
        }
@endphp
<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>

<!-- -------------------------------------------Main/Dashboard----------------------------------------------------- -->
                @if (Auth::user()->type=='Admin')
                    <li> <a href="{{ route('home') }}" class="noti-dot active"><i class="la la-dashboard">
                            </i> <span> Dashboard</span></a>
                    </li>
                @else
                    @php $count= in_array('Dashboard Manage',$arr);@endphp
                    @if($count>0)
                        {{-- <li class="menu-title"><span>Main</span></li>--}}
                        <li> <a href="{{ route('home') }}" class="noti-dot active"><i class="la la-dashboard">
                                </i> <span> Dashboard</span></a>
                        </li>
                    @endif
                @endif

<!-----------------------------------------Authentication/User Controller------------------------------------------ -->
                @if (Auth::user()->type=='Admin')
                    {{-- <li class="menu-title"> <span>Authentication</span> </li>--}}
                    <li class="submenu">
                        <a href="#">
                            <i class="la la-user-secret"></i> <span> User Controller</span> <span class="menu-arrow"></span>
                        </a>
                        <ul style="display: none;">
                            <li><a href="{{ route('userManagement') }}">Users</a></li>
                            <li><a href="{{ route('roleUser') }}">Role</a></li>
                        </ul>
                    </li>
                    @else
                        <li class="submenu">
                            @if(!Auth::user()->type=='DeliveryAgent')
                            <a href="#">
                                <i class="la la-user-secret"></i> <span> User Controller</span> <span class="menu-arrow"></span>
                            </a>
                            @endif
                            <ul style="display: none;">
                            <!-- users -->
                            @php $users= in_array('Users',$arr);@endphp
                            @if($users>0)
                                <li><a href="{{ route('userManagement') }}">Users</a></li>
                            @endif
                            <!-- role -->
                            @php $users_role= in_array('Role',$arr);@endphp
                            @if($users_role>0)
                                    <li><a href="{{ route('roleUser') }}">Role</a></li>
                            @endif
                            </ul>
                        </li>

                @endif

<!-- --------------------------------------- product ------------------------------------------ -->
                @if (Auth::user()->type=='Admin' || Auth::user()->type=='DeliveryAgent' )
                    <li> <a href="{{route('product.index')}}"><i class="la la-building">
                            </i> <span>Products</span></a>
                    </li>
                @else
                    <!-- Products -->
                    @php $products= in_array('Products',$arr);@endphp
                    @if($products>0)
                        <li> <a href="{{route('product.index')}}"><i class="la la-building">
                                </i> <span>Products</span></a>
                        </li>
                    @endif
                @endif

<!------------------------------------------------Company---------------------------------------------- -->
{{--                @if (Auth::user()->type=='Admin')--}}
{{--                    --}}{{-- <li class="menu-title"> <span>Setting</span> </li>--}}
{{--                    <li> <a href="{{route('company/view')}}"><i class="la la-building">--}}
{{--                            </i> <span>Company</span></a>--}}
{{--                    </li>--}}
{{--                @else--}}
{{--                    <!-- Company -->--}}
{{--                    @php $company= in_array('Company Manage',$arr);@endphp--}}
{{--                    @if($company>0)--}}
{{--                    <li> <a href="{{route('company/view')}}"><i class="la la-building">--}}
{{--                            </i> <span>Company</span></a>--}}
{{--                    </li>--}}
{{--                    @endif--}}
{{--                @endif--}}



            </ul>
        </div>
    </div>
</div>
<!-- /Sidebar -->

