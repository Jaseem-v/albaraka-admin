<!--- Sidemenu -->
<div id="sidebar-menu">
    <!-- Left Menu Start -->
    <ul class="metismenu list-unstyled" id="side-menu">
        <li class="menu-title" key="t-menu">Menu</li>

        <li>
            <a href="{{ route('home') }}" class="waves-effect">
                <i class="bx bx-home-circle"></i><span class="badge rounded-pill bg-info float-end"></span>
                <span key="t-dashboards">Dashboards</span>
            </a>
        </li>

        <li class="menu-title" key="t-menu">Masters</li>
        @if(Auth::user()->role == 'admin')
        <li>
            <a href="{{ route('project.index') }}" class="waves-effect">
                <i class="bx bx-briefcase-alt-2"></i>
                <span key="t-chat">Projects</span>
            </a>
        </li>
        @endif

        <li>
            <a href="{{ route('timesheet.index') }}" class="waves-effect">
                <i class="bx bx-task"></i>
                <span key="t-chat">Time Sheet</span>
            </a>
        </li>

        @if(Auth::user()->role == 'admin')
        <li>
            <a href="{{ route('supervisor.index') }}" class="waves-effect">
                <i class="bx bxs-user-detail"></i>
                <span key="t-chat">Supervisors</span>
            </a>
        </li>

        <li>
            <a href="{{ route('employee.index') }}" class="waves-effect">
                <i class="bx bx-user-circle"></i>
                <span key="t-chat">Employees</span>
            </a>
        </li>
        @endif

        <li class="menu-title" key="t-menu">Reports</li>
        <li>
            <a href="{{ route('report') }}" class="waves-effect">
                <i class="bx bx-receipt"></i>
                <span key="t-chat">Work Report</span>
            </a>
        </li>
        <li>
            <a href="{{ route('project') }}" class="waves-effect">
                <i class="bx bx-receipt"></i>
                <span key="t-chat">Project Report</span>
            </a>
        </li>
        <li>
            <a href="{{ route('leave.report') }}" class="waves-effect">
                <i class="bx bx-receipt"></i>
                <span key="t-chat">Leave Report</span>
            </a>
        </li>
        <li>
            <a href="{{ route('time.index') }}" class="waves-effect">
                <i class="bx bx-receipt"></i>
                <span key="t-chat">Time Report</span>
            </a>
        </li>

        <li class="menu-title" key="t-menu">General</li>
        @if(Auth::user()->role == 'admin')
        <li>
            <a href="{{ route('setting') }}" class="waves-effect">
                <i class="bx bx-cog"></i>
                <span key="t-chat">Setting</span>
            </a>
        </li>
        @endif

        <li>
            <a href="{{ route('password') }}" class="waves-effect">
                <i class="bx bx-wrench"></i>
                <span key="t-chat">Change Password</span>
            </a>
        </li>

        <li>
            <a href="{{ route('user.logout') }}" class="waves-effect">
                <i class="bx bx-power-off"></i>
                <span key="t-chat">Log Out</span>
            </a>
        </li>

    </ul>
</div>
<!-- Sidebar -->