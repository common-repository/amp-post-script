<?php

// Get template colors
$AMP_Post_Template = new AMP_Post_Template (get_the_id());
$colorText = $AMP_Post_Template->get_customizer_setting( 'text_color' );
$colorLink = $AMP_Post_Template->get_customizer_setting( 'link_color' );

/* POST TYPE SPECIFIC
================================================== */
?>
.amp-wp-header[data-post-type="page"] + .amp-wp-article .amp-wp-article-footer .amp-wp-meta{display:none}

<?php
/* BUTTON
================================================== */
?>
a.button,a.visited{display:inline-block;margin:0;padding:5px 10px;background:none;border:1px solid <?=$colorLink?>;box-shadow:none;border-radius:3px;font:16px/1.6em 'Open Sans',Arial,sans-serif;color:<?=$colorLink?>;text-shadow:none;text-decoration:none;cursor:pointer}
a.button:hover,a.button:active{background:none;border:1px solid <?=$colorText?>;color:<?=$colorText?>;text-decoration:none}
a.button.button-primary,a.button.button-primary:visited{display:inline-block;margin:0;padding:5px 10px;background:<?=$colorLink?>;border:1px solid <?=$colorLink?>;box-shadow:none;border-radius:3px;font:16px/1.6em 'Open Sans',Arial,sans-serif;color:#FFF;text-shadow:none;text-decoration:none;cursor:pointer}
a.button.button-primary:hover,a.button-primary:active{background:<?=$colorText?>;border:1px solid <?=$colorText?>;color:#FFF;text-decoration:none}

<?php
/* CUSTOM NAVIGATION
================================================== */
?>
a.ps-nav-icon{display:inline-block;position:relative;width:2em;height:2em;margin:-0.5em 10px -0.5em 0;padding:0;cursor:pointer;text-decoration:none}
a.ps-nav-icon span{display:block;height:20%;width:auto;margin:13% 0;padding:0;background:#FFF;border-radius:5px}

a.ps-nav-close{display:block;position:fixed;z-index:95;top:5px;right:5px;height:36px;width:36px;margin:-64px 0 0 0;background:#C00;border-radius:5px;box-shadow:0 0 20px rgba(0,0,0,0.25);font-size:30px;line-height:36px;font-weight:700;color:#FFF;text-shadow:0 1px 3px rgba(0,0,0,0.5);text-align:center;-webkit-transition:all .3s ease;-moz-transition:all .3s ease;transition:all .3s ease}
a.ps-nav-close:hover{background:#333}

nav.ps-nav{display:block;position:fixed;z-index:90;top:0;bottom:0;left:0;right:auto;height:auto;width:100%;margin:0 0 0 -100%;padding:0;background:rgba(255,255,255,0);pointer-events:none;-webkit-transition:all .3s ease;-moz-transition:all .3s ease;transition:all .3s ease}
nav.ps-nav div{display:block;position:absolute;top:0;bottom:0;left:0;right:0;width:100%;max-width:100%;margin:0;padding:0}
nav.ps-nav > div > ul{position:absolute;top:0;bottom:0;left:0;right:auto;height:auto;width:300px;max-width:90%;box-shadow:0 0 20px rgba(0,0,0,0.25);overflow:auto;pointer-events:auto}
nav.ps-nav ul{display:block;margin:0;padding:0;background:#FFF}
nav.ps-nav ul li{display:block;position:relative;margin:0;padding:0}
nav.ps-nav ul li a{display:block;position:relative;margin:0;padding:10px 15px;border-bottom:1px solid rgba(0,0,0,0.15);font-size:16px;line-height:1.6em;font-weight:400;color:#333;text-decoration:none}
nav.ps-nav ul li a:hover{color:#777}
nav.ps-nav ul ul{background:#EEE}
nav.ps-nav ul ul ul{background:#DDD}

a.ps-nav-icon:focus + nav.ps-nav,nav.ps-nav:hover{margin:0;background:rgba(255,255,255,0.75)}
a.ps-nav-icon:focus + nav.ps-nav + a.ps-nav-close,nav.ps-nav:hover + a.ps-nav-close{margin:0}

<?php
/* RELATED POSTS
================================================== */
?>
h3.related-posts-title{clear:both;margin-top:2em;text-align:center}
ul.related-posts{display:block;margin:0;padding:0;list-style:none;font-size:0;line-height:0;text-align:center}
ul.related-posts li{-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;display:inline-block;margin:0;padding:5px 5px;list-style:none;width:50%;height:200px;font-size:0;line-height:0;text-align:center}
ul.related-posts li img{display:block;width:100%;height:auto}
ul.related-posts li a{display:block;position:relative;height:100%;width:100%;background:#333;text-decoration:none;overflow:hidden}
ul.related-posts li a h4{display:block;position:absolute;top:auto;bottom:0;left:0;right:0;margin:0;padding:10px 10px;background:#000;background:rgba(0,0,0,0.5);font-size:16px;line-height:21px;font-weight:700;color:#FFF;text-shadow:0 3px 5px rgba(0,0,0,0.75);text-transform:uppercase;-webkit-transition:all .3s ease;-moz-transition:all .3s ease;transition:all .3s ease}
ul.related-posts li a:hover h4{background:rgba(0,0,0,0.8);color:#FFF}
@media only screen and (max-width:600px){ul.related-posts li{width:100%}}

<?php
/* OVERRIDE
================================================== */
?>
.alignright{max-width:50%;margin-left:10px}
.alignleft{max-width:50%;margin-right:10px}

<?php
/* PLUGIN FIX
================================================== */
?>
.ngg-galleryoverview,.ngg-slideshow,.ngg-slideshow-image-list.ngg-slideshow-nojs{display:none}
