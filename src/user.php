<?php if (empty($user)) { ?>
    <div class="my-3 my-md-5">
        <div class="container">
            <div class="col-lg-6" style="margin: 0 auto">
                <div class="alert alert-primary">
                    Please insert an username
                </div>

                <form action="" method="get">
                    <div class="input-icon">
                        <input type="search"
                               class="form-control header-search"
                               placeholder="Search @steemit-username..."
                               tabindex="1"
                               name="user"
                        >
                        <div class="input-icon-addon">
                            <i class="fe fe-search"></i>
                        </div>
                    </div>
                    <input type="hidden" name="c" value="<?php echo $category; ?>"/>
                </form>
            </div>
        </div>
    </div>
<?php } else {
    require dirname(__FILE__).'/user/categories/'.$category.'.php';
} ?>