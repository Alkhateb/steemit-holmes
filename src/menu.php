<div class="header collapse d-lg-flex p-0" id="headerMenuCollapse">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-3 ml-auto">
                <form class="input-icon my-3 my-lg-0">
                    <input type="search"
                           class="form-control header-search"
                           placeholder="Search @steemit-username..."
                           tabindex="1"
                           name="user"
                    >
                    <div class="input-icon-addon">
                        <i class="fe fe-search"></i>
                    </div>
                </form>
            </div>
            <div class="col-lg order-lg-first">
                <ul class="nav nav-tabs border-0 flex-column flex-lg-row">
                    <li class="nav-item">
                        <a href="./" class="nav-link<?php if ($page === false) { ?> active<?php }; ?>">
                            <i class="fe fe-home"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/?user" class="nav-link<?php if ($page === 'user') { ?> active<?php }; ?>">
                            <i class="fe fe-user"></i> User
                        </a>
                    </li>
                    <!--                    <li class="nav-item">-->
                    <!--                        <a href="" class="nav-link" data-toggle="dropdown">-->
                    <!--                            <i class="fa fa-tags"></i> Tags-->
                    <!--                        </a>-->
                    <!---->
                    <!--                    </li>-->
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