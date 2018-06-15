<div class="header collapse d-lg-flex p-0" id="headerMenuCollapse">
    <div class="container">
        <div class="row align-items-center">
            <?php if ($page !== 'user' || $page === 'user' && $user !== null) { ?>
                <div class="col-lg-3 ml-auto">
                    <form class="my-3 my-lg-0">
                        <div class="input-icon">
                            <input type="search"
                                   class="form-control header-search"
                                   placeholder="Search @steemit-username..."
                                   tabindex="1"
                                   name="user"
                                   value="<?php echo $user; ?>"
                            >
                            <div class="input-icon-addon">
                                <i class="fe fe-search"></i>
                            </div>
                        </div>

                        <input type="hidden" name="c" value="<?php echo $category; ?>"/>
                    </form>
                </div>
            <?php } ?>

            <div class="col-lg order-lg-first">
                <ul class="nav nav-tabs border-0 flex-column flex-lg-row">
                    <li class="nav-item">
                        <a href="./" class="nav-link<?php if ($page === false) { ?> active<?php }; ?>">
                            <i class="fe fe-home"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="javascript:void(0)"
                           class="nav-link<?php if ($page === 'user') { ?> active<?php }; ?>"
                           data-toggle="dropdown"
                        >
                            <i class="fe fe-user"></i> User
                        </a>

                        <div class="dropdown-menu dropdown-menu-arrow">
                            <a href="?user=<?php echo $user; ?>&c=general" class="dropdown-item ">General</a>
                            <a href="?user=<?php echo $user; ?>&c=transfers" class="dropdown-item ">Transfers</a>
                            <a href="?user=<?php echo $user; ?>&c=bots" class="dropdown-item ">Bot usage</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a href="/?about" class="nav-link<?php if ($page === 'about') { ?> active<?php }; ?>">
                            <i class="fa fa-coffee"></i> About
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
