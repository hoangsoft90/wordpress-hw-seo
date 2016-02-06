<?php
global $post;
$cats = wp_get_post_categories(get_the_ID(), array('fields'=> 'all'));
?>
<article class="<?php post_class()?>" id="<?php the_ID()?>" itemscope itemtype="http://schema.org/Article">
    <h1 itemprop="headline"><?php the_title()?></h1>
    <h2 itemprop="alternativeHeadline"></h2>
    <!-- post thumbnail -->
    <?php if(has_post_thumbnail()){
        $src = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
        //or
        the_post_thumbnail('thumbnail', array(
                'itemprop' => 'image', 'class'=> 'class1'
            ));
        ?>
    <img itemprop="image" src="<?php echo $src?>">
    <?php }?>
    <span itemprop="author"><?php the_author()?></span>
    <span itemprop="datePublished"><?php echo $post->post_date?></span>
    <span itemprop="description"><?php the_excerpt()?></span>
    <div itemprop="articleBody">
        <?php //the_content();  for single post page    ?>
    </div>
    <!-- category -->
    <?php if(!empty($cats)){
        $list = '';
        foreach($cats as $cat) {
            $list .= '<a href="'.get_term_link($cat->term_id, 'category').'">'.$cat->name.'</a>,';
        }
        printf('<span itemprop="ArticleSection" content="%s">Categories: %s</span>',
            $cats[0]->name,
            rtrim($list, ',')
        );
    }
        ?>

    <meta itemprop="url" content="<?php the_permalink()?>"/>
</article>