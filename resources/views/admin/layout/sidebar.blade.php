<aside class="main-sidebar">
    <div class="sidebar">
        <div class="user-panel">
            <div class="image text-center"><img src="{{asset('assets/admin/images/logo.png')}}" alt="logo"> </div>
        </div>
        <ul class="sidebar-menu" data-widget="tree">
            <li class="<?= Request::segment(2) == 'home' || Request::segment(2) == 'dashboard' ? 'active' : ''; ?>"> <a href="{{url('admin/home')}}">  <img src="{{asset('assets/admin/images/sideimg/dashboard.png')}}"  alt="dashboard"> <span>Dashboard</span></a></li>
            <li class="<?= Request::segment(2) == 'user-management' || Request::segment(2) == 'user-detail' ? 'active' : ''; ?>"> <a href="{{url('admin/user-management')}}">  <img src="{{asset('assets/admin/images/sideimg/users.png')}}"  alt="User list"> <span>User Management</span></a></li>
            <li class="<?= Request::segment(2) == 'subscription-management' || Request::segment(2) == 'subscription-plan' ? 'active' : ''; ?>"> <a href="{{url('admin/subscription-management')}}">  <img src="{{asset('assets/admin/images/sideimg/subscription.png')}}"  alt="subscription"> <span>Subscription Management</span></a></li>
            <li class="<?= Request::segment(2) == 'event-management' || Request::segment(2) == 'event-plan' ? 'active' : ''; ?>"> <a href="{{url('admin/event-management')}}">  <img src="{{asset('assets/admin/images/sideimg/event.png')}}"  alt="event"> <span>Event Management</span></a></li>
            <li class="<?= Request::segment(2) == 'poll-management' || Request::segment(2) == 'poll-plan' ? 'active' : ''; ?>"> <a href="{{url('admin/poll-management')}}">  <img src="{{asset('assets/admin/images/sideimg/poll.png')}}"  alt="poll"> <span>Poll Management</span></a></li>
            <li class="<?= Request::segment(2) == 'ringtone-management' || Request::segment(2) == 'ringtone-plan' ? 'active' : ''; ?>"> <a href="{{url('admin/ringtone-management')}}">  <img src="{{asset('assets/admin/images/sideimg/ringtone.png')}}"  alt="ringtone"> <span>Ringtone Management</span></a></li>
            <li class="<?= Request::segment(2) == 'content-management' || Request::segment(2) == 'content-plan' ? 'active' : ''; ?>"> <a href="{{url('admin/content-management')}}">  <img src="{{asset('assets/admin/images/sideimg/content.png')}}"  alt="content"> <span>Content Management</span></a></li>
            <li class="<?= Request::segment(2) == 'query-management' || Request::segment(2) == 'query-detail' ? 'active' : ''; ?>"> <a href="{{url('admin/query-management')}}">  <img src="{{asset('assets/admin/images/sideimg/help.png')}}"  alt="Help & Support"> <span>Help & Support</span></a></li>  
            <!-- <li class="<?= Request::segment(2) == 'video-management' || Request::segment(2) == 'video-detail' ? 'active' : ''; ?>"> <a href="{{url('admin/video-management')}}">  <img src="{{asset('assets/admin/images/sideimg/subscription.png')}}"  alt="Video list"> <span>Video Management</span></a></li> 
            <li class="<?= Request::segment(2) == 'category-management' || Request::segment(2) == 'edit-category' ? 'active' : ''; ?>"> <a href="{{url('admin/category-management')}}">  <img src="{{asset('assets/admin/images/sideimg/quantity.png')}}"  alt="Category list"> <span>Category Management</span></a></li>
            <li class="<?= Request::segment(2) == 'reason-management' || Request::segment(2) == 'edit-reason' ? 'active' : ''; ?>"> <a href="{{url('admin/reason-management')}}">  <img src="{{asset('
                images/sideimg/booking.png')}}"  alt="Reason list"> <span>Reason Management</span></a></li>
            <li class="<?= Request::segment(2) == 'report-management' || Request::segment(2) == 'report-detail' ? 'active' : ''; ?>"> <a href="{{url('admin/report-management')}}">  <img src="{{asset('assets/admin/images/sideimg/service.png')}}"  alt="Report list"> <span>Report Management</span></a></li>  
            <li class="treeview <?= Request::segment(2) == 'notification-list' || Request::segment(2) == 'compose-notification' ? 'menu-open' : ''; ?>"> <a href="#"> <img src="{{asset('assets/admin/images/sideimg/attributes.png')}}" alt="Notification Management"> <span>Notification Management</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
                <ul class="treeview-menu">
                    <li class="<?= Request::segment(2) == 'notification-list'? 'active' : ''; ?>"><a href="{{url('admin/notification-management')}}"> Notification List</a></li>
                    <li class="<?= Request::segment(2) == 'compose-notification'? 'active' : ''; ?>"><a href="{{url('admin/custom-notification')}}"> Compose New</a></li>
                </ul>
            </li>
           <li class="<?= Request::segment(2) == 'query-management' || Request::segment(2) == 'query-detail' ? 'active' : ''; ?>"> <a href="{{url('admin/query-management')}}">  <img src="{{asset('assets/admin/images/sideimg/help.png')}}"  alt="Help & Support"> <span>Help & Support</span></a></li>  -->
        </ul>
    </div>
</aside>