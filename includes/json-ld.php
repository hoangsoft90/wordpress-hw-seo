<?php
/**
 * @param $homeUrl
 * @return array
 */
function hwseo_get_search_Action($homeUrl){
    $contentArr = array(
        "@type"      => "SearchAction",
        "target"     => "{$homeUrl}/?s={search_term}",
        "query-input"=> "required name=search_term"
    );
    return $contentArr;
}
/**
 * @param array $data
 * @hook filter_jsonld_data
 */
function hwseo_searchbox($data) {
    $homeUrl = get_home_url();

    if(is_front_page()) {
        $data['@type']            = "WebSite";
        $data['url']              = $homeUrl;
        $data['potentialAction']  = hwseo_get_search_Action($homeUrl);
    }
    return $data;
}
add_filter('extra_jsonld_data', 'hwseo_searchbox');
/**
 * seo local business
 * @return array
 */
function hwseo_ldjson_localBusiness() {
    $settings = HW_SEO_LocalBusiness::get_options();
    if(empty($settings['enable'])) return;

    $payload = array();
    $address = array(
        '@type' => 'PostalAddress',
        "streetAddress"=> isset($settings['streetAddress'])? $settings['streetAddress'] : '',
        "addressLocality"=> isset($settings['addressLocality'])? $settings['addressLocality']: '',
        "addressRegion" => isset($settings['addressRegion'])? $settings['addressRegion']:'',
        "postalCode" => isset($settings['postalCode'])? $settings['postalCode'] : '',
        "addressCountry" => isset($settings['addressCountry'])? $settings['addressCountry']: ''

    );
    $payload['address'] = $address;
    return $payload;
}
//seo person with json+ld format
function hwseo_jsonld_person () {
    $payload= array(
        '@type' => 'Person'
    );
    if(is_author()) {
        global $wp_query;
        $author_data = $wp_query->get_queried_object();

    }
    elseif(is_single()) {
        global $post;
        $author_data = get_userdata($post->post_author);
    }
    else $author_data = get_userdata(1);    //refer to admin
    // fetch twitter from author meta and concatenate with full twitter URL
    $twitter_url =  " https://twitter.com/";
    $twitterHandle = get_the_author_meta('twitter', $author_data->ID);
    $twitterHandleURL = $twitter_url . $twitterHandle;

    $websiteHandle = get_the_author_meta('url', $author_data->ID);

    $facebookHandle = get_the_author_meta('facebook',$author_data->ID);

    $gplusHandle = get_the_author_meta('googleplus', $author_data->ID);

    $linkedinHandle = get_the_author_meta('linkedin', $author_data->ID);

    $slideshareHandle = get_the_author_meta('slideshare', $author_data->ID);
    //user custom fields
    $phone = get_the_author_meta('phone', $author_data->ID);
    $jobtitle = get_the_author_meta('jobtitle', $author_data->ID);

    $payload['name'] = $author_data->display_name;
    $payload['email'] = $author_data->user_email;
    $payload['telephone'] = $phone;
    $payload['image'] = get_avatar_url(get_avatar($author_data->ID, 150));
    $payload['jobTitle'] = $jobtitle;
    $payload["sameAs"] =  array(
        $twitterHandleURL, $websiteHandle, $facebookHandle, $gplusHandle, $linkedinHandle, $slideshareHandle
    );

    return $payload;
}
/**
 * article in single page
 * @param array $data
 */
function hwseo_ldjson_article($data= array()) {
    // stuff for specific pages
    if (is_single() || is_page()) {
        global $post;
        if (have_posts()) : while (have_posts()) : the_post();
            $terms = wp_get_post_terms($post->ID, 'category');
            // this gets the data for the user who wrote that particular item
            $author_data = get_userdata($post->post_author);
            $post_url = get_permalink();
            $post_thumb = wp_get_attachment_url(get_post_thumbnail_id($post->ID));
            if(!$post_thumb) $post_thumb = hwseo_catch_that_image($post->post_content, HW_SEO_URL. '/assets/images/placeholder.png');

            $payload["@type"] = "Article";
            $payload["url"] = $post_url;
            $payload["author"] = array(
                "@type" => "Person",
                "name" => $author_data->display_name,
            );
            $payload["headline"] = $post->post_title;
            $payload['articleBody'] = the_excerpt();
            $time = strtotime( get_the_time('c') );
            #$contentArr['datePublished'] = date( 'c', $time );
            $payload["datePublished"] = $post->post_date;

            $payload["image"] = $post_thumb;
            $payload["ArticleSection"] = count($terms)? $terms[0]->name : '';

            $payload["Publisher"]['@type'] = 'Organization';
            $payload["Publisher"]['name'] = get_bloginfo('name');
        endwhile; endif;
        rewind_posts();

        $data = array_merge($data, $payload);
    }
    return $data;
}

/**
 * seo author
 * @param array $data
 */
function hwseo_ldjson_author($data = array()) {
    if (is_author()) {
        global $wp_query;
        $author_data = $wp_query->get_queried_object();

        // this gets the data for the user who wrote that particular item
        #$author_data = get_userdata($post_data->post_author);

        // some of you may not have all of these data points in your user profiles - delete as appropriate
        // fetch twitter from author meta and concatenate with full twitter URL
        $twitter_url =  " https://twitter.com/";
        $twitterHandle = get_the_author_meta('twitter');
        $twitterHandleURL = $twitter_url . $twitterHandle;

        $websiteHandle = get_the_author_meta('url');

        $facebookHandle = get_the_author_meta('facebook');

        $gplusHandle = get_the_author_meta('googleplus');

        $linkedinHandle = get_the_author_meta('linkedin');

        $slideshareHandle = get_the_author_meta('slideshare');

        $payload["@type"] = "Person";
        $payload["name"] = $author_data->display_name;
        $payload["email"] = $author_data->user_email;
        $payload["sameAs"] =  array(
            $twitterHandleURL, $websiteHandle, $facebookHandle, $gplusHandle, $linkedinHandle, $slideshareHandle
        );
        $data = array_merge($data, $payload);

    }
    return $data;
}