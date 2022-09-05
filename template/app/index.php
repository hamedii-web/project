<?php require_once(BASE_PATH . '/template/app/layout/header.php'); ?>

    <div class="site-main-container">
        <!-- Start top-post Area -->
        <section class="top-post-area pt-10">
            <div class="container no-padding">
                <div class="row small-gutters">
                <?php if(isset($topPostView[0])) { ?>
                    <div class="col-lg-8 top-post-left">
                        <div class="feature-image-thumb relative">
                            <div class="overlay overlay-bg"></div>
                            <img class="img-fluid" src="<?= asset($topPostView[0]['image']) ?>" alt="">
                        </div>
                        
                        <div class="top-post-details">
                            <ul class="tags">
                                <li><a href="<?= url('show-category/' . $topPostView[0]['cat_id']) ?>"><?= $topPostView[0]['category'] ?></a></li>
                            </ul>
                            <a href="<?= url('show-post/' . $topPostView[0]['id']) ?>">
                                <h3><?= $topPostView[0]['title'] ?></h3>
                            </a>
                            <ul class="meta">
                                <li><a href="#"><span class="lnr lnr-user"></span><?= $topPostView[0]['username'] ?></a></li>
                                <li><a href="#"><?= jalaliDate($topPostView[0]['created_at']) ?><span class="lnr lnr-calendar-full"></span></a></li>
                                <li><a href="#"><?= $topPostView[0]['comment_count'] ?><span class="lnr lnr-bubble"></span></a></li>
                            </ul>
                        </div>
                        
                    </div>
                    <?php } ?>
                    <div class="col-lg-4 top-post-right">
                    <?php if(isset($topPostView[1])) { ?>
                        <div class="single-top-post">
                            <div class="feature-image-thumb relative">
                                <div class="overlay overlay-bg"></div>
                                <img class="img-fluid" src="<?= asset($topPostView[1]['image']) ?>" alt="">
                            </div>
                            <div class="top-post-details">
                                <ul class="tags">
                                    <li><a href="<?= url('show-category/' . $topPostView[1]['cat_id']) ?>"><?= $topPostView[1]['category'] ?></a></li>
                                </ul>
                                <a href="<?= url('show-post/' . $topPostView[1]['id']) ?>">
                                    <h4><?= $topPostView[1]['title'] ?></h4>
                                </a>
                                <ul class="meta">
                                    <li><a href="#"><span class="lnr lnr-user"></span><?= $topPostView[1]['username'] ?></a></li>
                                    <li><a href="#"><?= jalaliDate($topPostView[1]['created_at']) ?><span class="lnr lnr-calendar-full"></span></a></li>
                                    <li><a href="#"> <?= $topPostView[1]['comment_count'] ?><span class="lnr lnr-bubble"></span></a></li>
                                </ul>
                            </div>
                        </div>
                        <?php 
                    }
                    if(isset($topPostView[2])) {
                        ?>
                        <div class="single-top-post mt-10">
                            <div class="feature-image-thumb relative">
                                <div class="overlay overlay-bg"></div>
                                <img class="img-fluid" src="<?= asset($topPostView[2]['image']) ?>" alt="">
                            </div>
                            <div class="top-post-details">
                                <ul class="tags">
                                    <li><a href="<?= url('show-category/' . $topPostView[2]['cat_id']) ?>"><?= $topPostView[2]['category'] ?></a></li>
                                </ul>
                                <a href="<?= url('show-post/' . $topPostView[2]['id']) ?>">
                                    <h4><?= $topPostView[2]['title'] ?></h4>
                                </a>
                                <ul class="meta">
                                    <li><a href="#"><span class="lnr lnr-user"></span><?= $topPostView[2]['username'] ?></a></li>
                                    <li><a href="#"><?= $jalaliDate($topPostView[2]['created_at']) ?><span class="lnr lnr-calendar-full"></span></a></li>
                                    <li><a href="#"><?= $topPostView[2]['comment_count'] ?><span class="lnr lnr-bubble"></span></a></li>
                                </ul>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                    <div class="col-lg-12">
                        <?php if(!empty($brakingNews)) { ?>
                        <div class="news-tracker-wrap">
                            <h6><span>خبر فوری:</span> <a href="<?= url('show-post/' . $brakingNews['id']) ?>"><?= $brakingNews['title'] ?></a></h6>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </section>
        <!-- End top-post Area -->
        <!-- Start latest-post Area -->
        <section class="latest-post-area pb-120">
            <div class="container no-padding">
                <div class="row">
                    <div class="col-lg-8 post-list">
                        <!-- Start latest-post Area -->
                        <div class="latest-post-wrap">
                            <h4 class="cat-title">آخرین اخبار</h4>
                            <?php foreach($lastPosts as $lastPost) { ?>
                            <div class="single-latest-post row align-items-center">
                                <div class="col-lg-5 post-left">
                                    <div class="feature-img relative">
                                        <div class="overlay overlay-bg"></div>
                                        <img class="img-fluid" src="<?= asset($lastPost['image']) ?>" alt="">
                                    </div>
                                    <ul class="tags">
                                        <li><a href="<?= url('show-category/' . $lastPost['cat_id']) ?>"><?= $lastPost['category'] ?></a></li>
                                    </ul>
                                </div>
                                <div class="col-lg-7 post-right">
                                    <a href="<?= url('show-post/' . $lastPost['id']) ?>">
                                        <h4><?= $lastPost['title'] ?></h4>
                                    </a>
                                    <ul class="meta">
                                        <li><a href="#"><span class="lnr lnr-user"></span><?= $lastPost['username'] ?></a></li>
                                        <li><a href="#"><?= jalaliDate($lastPost['created_at']) ?><span class="lnr lnr-calendar-full"></span></a></li>
                                        <li><a href="#"> <?= $lastPost['comment_count'] ?><span class="lnr lnr-bubble"></span></a></li>
                                    </ul>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                        <!-- End latest-post Area -->

                        <!-- Start banner-ads Area -->
                        <?php if(!empty($bodyBanner)) { ?>
                        <div class="col-lg-12 ad-widget-wrap mt-30 mb-30">
                            <img class="img-fluid" src="<?= asset($bodyBanner['image']) ?>" alt="">
                        </div>
                        <?php } ?>
                        <!-- End banner-ads Area -->
                        <!-- Start popular-post Area -->
                        <div class="popular-post-wrap">
                            <h4 class="title">اخبار پربازدید</h4>
                            <?php if(isset($popularPosts[0])) { ?>
                            <div class="feature-post relative">
                                <div class="feature-img relative">
                                    <div class="overlay overlay-bg"></div>
                                    <img class="img-fluid" src="<?= asset($popularPosts[0]['image']); ?>" alt="">
                                </div>
                                <div class="details">
                                    <ul class="tags">
                                        <li><a href="<?= url('show-category/' . $popularPosts[0]['cat_id']) ?>"><?= $popularPosts[0]['category'] ?></a></li>
                                    </ul>
                                    <a href="<?= url('show-post/' . $popularPosts[0]['id']) ?>">
                                        <h3><?= $popularPosts[0]['title'] ?></h3>
                                    </a>
                                    <ul class="meta">
                                        <li><a href="#"><span class="lnr lnr-user"></span><?= $popularPosts[0]['username'] ?></a></li>
                                        <li><a href="#"><?= jalaliDate($popularPosts[0]['created_at']) ?><span class="lnr lnr-calendar-full"></span></a></li>
                                        <li><a href="#"><?= $popularPosts[0]['comment_count'] ?><span class="lnr lnr-bubble"></span></a></li>
                                    </ul>
                                </div>
                            </div>
                            <?php } ?>
                            <div class="row mt-20 medium-gutters">
                            <?php if(isset($popularPosts[1])) { ?>
                                <div class="col-lg-6 single-popular-post">
                                    <div class="feature-img-wrap relative">
                                        <div class="feature-img relative">
                                            <div class="overlay overlay-bg"></div>
                                            <img class="img-fluid" src="<?= asset($popularPosts[1]['image']); ?>" alt="">
                                        </div>
                                        <ul class="tags">
                                            <li><a href="<?= url('show-category/' . $popularPosts[1]['cat_id']) ?>"><?= $popularPosts[1]['category'] ?></a></li>
                                        </ul>
                                    </div>
                                    <div class="details">
                                        <a href="<?= url('show-post/' . $popularPosts[1]['id']) ?>">
                                            <h4><?= $popularPosts[1]['title'] ?></h4>
                                        </a>
                                        <ul class="meta">
                                            <li><a href="#"><span class="lnr lnr-user"></span><?= $popularPosts[1]['username'] ?></a></li>
                                            <li><a href="#"><?= jalaliDate($popularPosts[1]['created_at']) ?><span class="lnr lnr-calendar-full"></span></a></li>
                                            <li><a href="#"> <?= $popularPosts[1]['comment_count'] ?><span class="lnr lnr-bubble"></span></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <?php 
                            }
                            if(isset($popularPosts[2])) {
                                
                                ?>
                                <div class="col-lg-6 single-popular-post">
                                    <div class="feature-img-wrap relative">
                                        <div class="feature-img relative">
                                            <div class="overlay overlay-bg"></div>
                                            <img class="img-fluid" src="<?= asset($popularPosts[2]['image']); ?>" alt="">
                                        </div>
                                        <ul class="tags">
                                            <li><a href="<?= url('show-category/' . $popularPosts[2]['cat_id']) ?>"><?= $popularPosts[2]['category'] ?></a></li>
                                        </ul>
                                    </div>
                                    <div class="details">
                                        <a href="<?= url('show-post/' . $popularPosts[2]['id']) ?>">
                                            <h4><?= $popularPosts[2]['title'] ?></h4>
                                        </a>
                                        <ul class="meta">
                                            <li><a href="#"><span class="lnr lnr-user"></span><?= $popularPosts[2]['username'] ?></a></li>
                                            <li><a href="#"><?= jalaliDate($popularPosts[2]['created_at']) ?><span class="lnr lnr-calendar-full"></span></a></li>
                                            <li><a href="#"><?= $popularPosts[2]['comment_count'] ?><span class="lnr lnr-bubble"></span></a></li>
                                        </ul>
                                        <p class="excert">
                                            خلاصه متن خبر
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                        <!-- End popular-post Area -->
                    </div>
                    <?php require_once(BASE_PATH . '/template/app/layout/sidebar.php'); ?>
                </div>
            </div>
        </section>
        <!-- End latest-post Area -->
    </div>

    <?php require_once(BASE_PATH . '/template/app/layout/footer.php'); ?>