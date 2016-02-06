<?php
/**
 * Created by PhpStorm.
 * User: Hoang
 * Date: 1/3/16
 * Time: 11:41 AM
 */
add_action('show_user_profile', 'hwseo_show_extra_profile_fields');
add_action('edit_user_profile', 'hwseo_show_extra_profile_fields');
function hwseo_show_extra_profile_fields($user)
{
    $fb=get_the_author_meta('facebook',$user->ID);
    $googleplus=get_the_author_meta('googleplus',$user->ID);
    $linkedin =get_the_author_meta('linkedin',$user->ID);
    $slideshare = get_the_author_meta('slideshare',$user->ID);
    $jobtitle = get_the_author_meta('jobtitle',$user->ID);
    $phone = get_the_author_meta('phone',$user->ID);    //phone
    ?>
    <table class="form-table">
        <!--
        <tr>
            <td>facebook</td>
            <td><input type="text" name="facebook" value="<?php echo $fb?>"/></td>
        </tr>

        <tr>
            <td>Google Plus</td>
            <td><input type="text" name="googleplus" value="<?php echo $googleplus?>"/></td>
        </tr>
        -->
        <tr>
            <td><strong>Phone</strong></td>
            <td><input type="text" class="regular-text" name="phone" value="<?php echo $phone?>"/></td>
        </tr>
        <tr>
            <td><strong>Job Title</strong></td>
            <td><input type="text" class="regular-text" name="jobtitle" value="<?php echo $jobtitle?>"/></td>
        </tr>
        <tr>
            <td><strong>Linkedin</strong></td>
            <td><input type="text" class="regular-text" name="linkedin" value="<?php echo $linkedin?>"/></td>
        </tr>
        <tr>
            <td><strong>Slideshare</strong></td>
            <td><input type="text" class="regular-text" name="slideshare" value="<?php echo $slideshare?>"/></td>
        </tr>

    </table>
<?php
}
//update user profile
add_action('personal_options_update', 'hwseo_save_extra_profile_fields');
add_action('edit_user_profile_update', 'hwseo_save_extra_profile_fields');

function hwseo_save_extra_profile_fields($user_id)
{
    if (!current_user_can('edit_user', $user_id))
        return false;
    update_usermeta($user_id, 'phone', $_POST['phone']);    //update user profile
    update_usermeta($user_id, 'linkedin', $_POST['linkedin']);    //update user profile
    update_usermeta($user_id, 'slideshare', $_POST['slideshare']);    //update user profile
    update_usermeta($user_id, 'jobtitle', $_POST['jobtitle']);    //update user profile
}