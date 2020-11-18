<!-- sidebar menu -->
<?php
    $userinfo = Session::get('userinfo');
	$segment =  Request::segment(2);
    $sub_segment =  Request::segment(3);
?>
<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
    <?php
        // SUPER ADMIN //
        if ($userinfo['priv'] == "VSUPER"):
    ?>
    <div class="menu_section">
		<ul class="nav side-menu">
			<li class="{{ ($segment == 'dashboard' ? 'active' : '') }}">
				<a href="<?=url('backend/dashboard');?>"><i class="fa fa-dashboard"></i> Dashboard</a>
			</li>
        </ul>
    </div>
    <?php
        endif;
    ?>

    <?php
        //UBAH PASSWORD
        if (($userinfo['tipe'] == 'AGEN') || ($userinfo['tipe'] == 'LAIN')):
    ?>
    <div class="menu_section">
        <h3>GENERAL</h3>
		<ul class="nav side-menu">
            <li class="{{ ($segment == 'change-password' ? 'active' : '') }}">
                <a href="<?=url('backend/change-password');?>"><i class="fa fa-ticket"></i> Change Password</a>
            </li>
        </ul>
    </div>
    <?php
        endif;
    ?>

    <?php
        // IT TIRTA //
        if ($userinfo['priv'] == "VTTIRTA"):
    ?>
            <div class="menu_section">
                <h3>MASTER</h3>
                <ul class="nav side-menu">
                    <li class="{{ ($segment == 'usert' ? 'active' : '') }}">
                        <a href="<?=url('backend/usert');?>"><i class="fa fa-users"></i> Master User</a>
                    </li>
                </ul>
            </div>
            <div class="menu_section">
                <h3>INPUT</h3>
                <ul class="nav side-menu">
                    <li class="{{ ($segment == 'input-admin' ? 'active' : '') }}">
                        <a href="<?=url('backend/input-admin');?>"><i class="fa fa-table"></i> Input Data</a>
                    </li>
                </ul>
            </div>
    <?php
        endif;
    ?>

    <?php
        // SUPER ADMIN //
        if (($userinfo['priv'] == "VTTIRTA") || ($userinfo['priv'] == "VHTIRTA")):
    ?>
    <div class="menu_section">
        <h3>REPORT</h3>
		<ul class="nav side-menu">
            <li class="{{ ($segment == 'general-reportt' ? 'active' : '') }}">
                <a href="<?=url('backend/general-reportt');?>"><i class="fa fa-table"></i> General Report</a>
            </li>
        </ul>
    </div>
    <?php
        endif;
    ?>


    <?php
        // SUPER ADMIN //
        if (($userinfo['priv'] == "VSUPER") || ($userinfo['priv'] == "VADM")):
    ?>
    <div class="menu_section">
        <h3>MASTER</h3>
		<ul class="nav side-menu">
            <?php 
                if ($userinfo['priv'] == "VSUPER"):
            ?>
            <li class="{{ ($segment == 'user' ? 'active' : '') }}">
                <a href="<?=url('backend/user');?>"><i class="fa fa-users"></i> Master User</a>
            </li>
            <?php
                endif;
            ?>
            <li class="{{ ($segment == 'employee' ? 'active' : '') }}">
                <a href="<?=url('backend/employee');?>"><i class="fa fa-users"></i> Master Employee</a>
            </li>
        </ul>
    </div>
    <?php
        endif;
    ?>
    <?php
        // SUPER ADMIN //
        if (($userinfo['priv'] != "VTTIRTA") && ($userinfo['priv'] != "VHTIRTA")):
    ?>
    <div class="menu_section">
        <h3>INPUT</h3>
		<ul class="nav side-menu">
            <li class="{{ ($segment == 'input' ? 'active' : '') }}">
                <a href="<?=url('backend/input');?>"><i class="fa fa-table"></i> Input Data</a>
            </li>
        </ul>
    </div>
    <?php
        endif;
    ?>
    <?php
        // SUPER ADMIN //
        if (($userinfo['priv'] == "VSUPER") || ($userinfo['priv'] == "VADM") || (($userinfo['priv'] == "USER") && ($userinfo['pt'] == "TIRTA"))):
    ?>
    <div class="menu_section">
        <h3>REPORT</h3>
		<ul class="nav side-menu">
            <li class="{{ ($segment == 'general-report' ? 'active' : '') }}">
                <a href="<?=url('backend/general-report');?>"><i class="fa fa-table"></i> General Report</a>
            </li>
        </ul>
    </div>
    <?php
        endif;
    ?>
    <?php
        // SUPER ADMIN //
        if ($userinfo['priv'] == "VSUPER"):
    ?>
	<div class="menu_section">
        <h3>GENERAL</h3>
        <ul class="nav side-menu">
            <li class="{{ ($segment == 'setting' ? 'active' : '') }}">
                <a href="<?=url('backend/setting');?>"><i class="fa fa-cog"></i> Setting</a>
            </li>
        </ul>
    </div>
    <?php
        endif;
    ?>
</div>

