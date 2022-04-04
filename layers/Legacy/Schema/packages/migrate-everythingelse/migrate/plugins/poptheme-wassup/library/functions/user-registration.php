<?php
use PoP\ComponentModel\ComponentInfo as ComponentModelComponentInfo;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

\PoP\Root\App::addAction('show_user_profile', 'extraUserProfileFields', 1);
\PoP\Root\App::addAction('edit_user_profile', 'extraUserProfileFields', 1);

\PoP\Root\App::addAction('edit_user_created_user', 'saveExtraUserInfo', 10, 1);
\PoP\Root\App::addAction('personal_options_update', 'saveExtraUserProfileFields');
\PoP\Root\App::addAction('edit_user_profile_update', 'saveExtraUserProfileFields');

\PoP\Root\App::addFilter('insert_user_meta', adduserSetNickname(...), 10, 2);
function adduserSetNickname($meta, $user)
{

    // Do ALWAYS keep the nickname tied to the display_name
    // This way we can search by nickname
    // Need to do it here, because function edit_user, which saves the nickname, is called after the hook edit_user_profile_update
    // so in the backend the nickname will be overriden time and again with the value in the input
    $meta['nickname'] = $user->display_name;
    return $meta;
}
\PoP\Root\App::addAction('user_profile_update_errors', 'setNickname', 10, 3);
function setNickname(&$errors, $update, &$user)
{

    // Do ALWAYS keep the nickname tied to the display_name
    // This way we can search by nickname
    // Need to do it here, because function edit_user, which saves the nickname, is called after the hook edit_user_profile_update
    // so in the backend the nickname will be overriden time and again with the value in the input
    $user->nickname = $user->display_name;
}

function printUserPreferencesField($user_id, $input)
{
    global $user_preferences;
    if ($user_preferences == null) {
        $user_preferences = \PoPCMSSchema\UserMeta\Utils::getUserMeta($user_id, GD_METAKEY_PROFILE_USERPREFERENCES);
    }

    $checked = isset($_POST['user_preferences']) ? in_array($input, $_POST['user_preferences']) : in_array($input, $user_preferences);
    printf(
        '<input type="checkbox" name="user_preferences[]" value="%s" %s>',
        $input,
        $checked ? 'checked="checked"' : ''
    );
}


function extraUserProfileFields($user)
{
    if (!is_admin()) {
        return;
    } ?>
    <table class="form-table">
    <tr>
    <th><label for="title"><?php _e("Title", 'pop-coreprocessors'); ?></label></th>
    <td><input type="text" name="title" id="title" value="<?php echo \PoPCMSSchema\UserMeta\Utils::getUserMeta($user->ID, GD_METAKEY_PROFILE_TITLE, true) ?>" class="regular-text code" /></td>
    </tr>
    <tr>
    <th><label for="short_description"><?php _e("Short Description", 'pop-coreprocessors'); ?></label></th>
    <td><input type="text" name="short_description" id="short_description" value="<?php echo \PoPCMSSchema\UserMeta\Utils::getUserMeta($user->ID, GD_METAKEY_PROFILE_SHORTDESCRIPTION, true) ?>" class="regular-text code" /></td>
    </tr>
    </table>
    <h3><?php _e('Display email in the Profile page?', 'pop-coreprocessors') ?></h3>
    <table class="form-table">
    <tr>
    <th><label for="display_email"><?php _e('Display email in the Profile page?', 'pop-coreprocessors') ?></label></th>
    <td>
    <?php echo GD_AdminUtils::formDropdown('display_email', gdBuildSelectOptions(array('Yes', 'No')), isset($_POST['display_email']) ? $_POST['display_email'] : (\PoPCMSSchema\UserMeta\Utils::getUserMeta($user->ID, GD_METAKEY_PROFILE_DISPLAYEMAIL, true) ? "yes" : "no"), 'class="regular-text"'); ?>
    </td>
    </tr>
    </table>
    <h3><?php _e('User preferences', 'pop-coreprocessors') ?></h3>
    <h4><?php _e('Email notifications', 'pop-coreprocessors') ?></h4>
    <h5><?php _e('General:', 'pop-coreprocessors') ?></h5>
    <table class="form-table">
    <tr>
    <th><label><?php _e('New content is posted on the website', 'pop-coreprocessors') ?></label></th>
    <td>
    <?php printUserPreferencesField($user->ID, POP_USERPREFERENCES_EMAILNOTIFICATIONS_GENERAL_NEWPOST); ?>
    </td>
    </tr>
    </table>
    <h5><?php _e('A user on my network:', 'pop-coreprocessors') ?></h5>
    <table class="form-table">
    <tr>
    <th><label><?php _e('Created content', 'pop-coreprocessors') ?></label></th>
    <td>
    <?php printUserPreferencesField($user->ID, POP_USERPREFERENCES_EMAILNOTIFICATIONS_NETWORK_CREATEDCONTENT); ?>
    </td>
    </tr>
    <tr>
    <th><label><?php _e('Recommends content', 'pop-coreprocessors') ?></label></th>
    <td>
    <?php printUserPreferencesField($user->ID, POP_USERPREFERENCES_EMAILNOTIFICATIONS_NETWORK_RECOMMENDEDPOST); ?>
    </td>
    </tr>
    <tr>
    <th><label><?php _e('Follows another user', 'pop-coreprocessors') ?></label></th>
    <td>
    <?php printUserPreferencesField($user->ID, POP_USERPREFERENCES_EMAILNOTIFICATIONS_NETWORK_FOLLOWEDUSER); ?>
    </td>
    </tr>
    <tr>
    <th><label><?php _e('Subscribed to a topic', 'pop-coreprocessors') ?></label></th>
    <td>
    <?php printUserPreferencesField($user->ID, POP_USERPREFERENCES_EMAILNOTIFICATIONS_NETWORK_SUBSCRIBEDTOTOPIC); ?>
    </td>
    </tr>
    <tr>
    <th><label><?php _e('Added a comment', 'pop-coreprocessors') ?></label></th>
    <td>
    <?php printUserPreferencesField($user->ID, POP_USERPREFERENCES_EMAILNOTIFICATIONS_NETWORK_ADDEDCOMMENT); ?>
    </td>
    </tr>
    <tr>
    <th><label><?php _e('Up/down-voted a highlight', 'pop-coreprocessors') ?></label></th>
    <td>
    <?php printUserPreferencesField($user->ID, POP_USERPREFERENCES_EMAILNOTIFICATIONS_NETWORK_UPDOWNVOTEDPOST); ?>
    </td>
    </tr>
    <?php /* Comment Leo 20/03/2017: Horrible Fix: this should be externalized into user-role-editor-popprocessors */ ?>
    <?php if (defined('POP_USERCOMMUNITIES_INITIALIZED')) : ?>
        <tr>
        <th><label><?php _e('Joins a community', 'pop-coreprocessors') ?></label></th>
        <td>
        <?php printUserPreferencesField($user->ID, POP_USERPREFERENCES_EMAILNOTIFICATIONS_NETWORK_JOINSCOMMUNITY); ?>
        </td>
        </tr>
    <?php endif; ?>
    </table>
    <h5><?php _e('A topic I am subscribed to:', 'pop-coreprocessors') ?></h5>
    <table class="form-table">
    <tr>
    <th><label><?php _e('Has new content', 'pop-coreprocessors') ?></label></th>
    <td>
    <?php printUserPreferencesField($user->ID, POP_USERPREFERENCES_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_CREATEDCONTENT); ?>
    </td>
    </tr>
    <tr>
    <th><label><?php _e('Has a comment added', 'pop-coreprocessors') ?></label></th>
    <td>
    <?php printUserPreferencesField($user->ID, POP_USERPREFERENCES_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_ADDEDCOMMENT); ?>
    </td>
    </tr>
    </table>
    <h4><?php _e('Email digests', 'pop-coreprocessors') ?></h4>
    <table class="form-table">
    <tr>
    <th><label><?php _e('New content by the community (weekly)', 'pop-coreprocessors') ?></label></th>
    <td>
    <?php printUserPreferencesField($user->ID, POP_USERPREFERENCES_EMAILDIGESTS_WEEKLYLATESTPOSTS); ?>
    </td>
    </tr>
    <tr>
    <th><label><?php _e('Upcoming events (weekly)', 'pop-coreprocessors') ?></label></th>
    <td>
    <?php printUserPreferencesField($user->ID, POP_USERPREFERENCES_EMAILDIGESTS_WEEKLYUPCOMINGEVENTS); ?>
    </td>
    </tr>
    <tr>
    <th><label><?php _e('My notifications (daily)', 'pop-coreprocessors') ?></label></th>
    <td>
    <?php printUserPreferencesField($user->ID, POP_USERPREFERENCES_EMAILDIGESTS_DAILYNOTIFICATIONS); ?>
    </td>
    </tr>
    <tr>
    <th><label><?php _e('Special posts', 'pop-coreprocessors') ?></label></th>
    <td>
    <?php printUserPreferencesField($user->ID, POP_USERPREFERENCES_EMAILDIGESTS_SPECIALPOSTS); ?>
    </td>
    </tr>
    </table>
    <?php
}

function saveExtraUserInfo($user_id)
{
    if (!is_admin()) {
        return;
    }

    if (defined('POP_USERPLATFORM_INITIALIZED')) {
        // Last Edited: needed for the user thumbprint
        \PoPCMSSchema\UserMeta\Utils::updateUserMeta($user_id, GD_METAKEY_PROFILE_LASTEDITED, ComponentModelComponentInfo::get('time'));
    }
}

function saveExtraUserProfileFields($user_id)
{
    if (!is_admin()) {
        return;
    }

    saveExtraUserInfo($user_id);

    \PoPCMSSchema\UserMeta\Utils::updateUserMeta($user_id, GD_METAKEY_PROFILE_TITLE, $_POST['title'], true);
    \PoPCMSSchema\UserMeta\Utils::updateUserMeta($user_id, GD_METAKEY_PROFILE_SHORTDESCRIPTION, $_POST['short_description'], true);
    \PoPCMSSchema\UserMeta\Utils::updateUserMeta($user_id, GD_METAKEY_PROFILE_DISPLAYEMAIL, (isset($_POST['display_email']) && $_POST['display_email'] == "yes"), true, true);

    \PoPCMSSchema\UserMeta\Utils::updateUserMeta($user_id, GD_METAKEY_PROFILE_USERPREFERENCES, $_POST['user_preferences']);
}







/* Contact Methods for the Edit User Page for the WP backend*/
\PoP\Root\App::addFilter('user_contactmethods', gdUserContactmethods(...));
function gdUserContactmethods()
{
    $contact = array(
        \PoPCMSSchema\UserMeta\Utils::getMetaKey(GD_METAKEY_PROFILE_FACEBOOK) => TranslationAPIFacade::getInstance()->__('Facebook'),
        \PoPCMSSchema\UserMeta\Utils::getMetaKey(GD_METAKEY_PROFILE_TWITTER) => TranslationAPIFacade::getInstance()->__('Twitter'),
        \PoPCMSSchema\UserMeta\Utils::getMetaKey(GD_METAKEY_PROFILE_LINKEDIN) => TranslationAPIFacade::getInstance()->__('Linkedin'),
        \PoPCMSSchema\UserMeta\Utils::getMetaKey(GD_METAKEY_PROFILE_YOUTUBE) => TranslationAPIFacade::getInstance()->__('Youtube'),
        \PoPCMSSchema\UserMeta\Utils::getMetaKey(GD_METAKEY_PROFILE_INSTAGRAM) => TranslationAPIFacade::getInstance()->__('Instagram'),
    );

    return $contact;
}

\PoP\Root\App::addAction('show_user_profile', 'customExtraUserProfileFields', 2);
\PoP\Root\App::addAction('edit_user_profile', 'customExtraUserProfileFields', 2);

\PoP\Root\App::addAction('personal_options_update', 'customSaveExtraUserProfileFields', 20);
\PoP\Root\App::addAction('edit_user_profile_update', 'customSaveExtraUserProfileFields', 20);

function customExtraUserProfileFields($user)
{
    if (!is_admin()) {
        return;
    }
    $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
    ?>

    <h3><?php _e('Organization info', 'poptheme-wassup') ?></h3>
    <table class="form-table">
    <tbody>
        <tr>
            <th><label for="contact_person"><?php _e('Contact person', 'poptheme-wassup'); ?></label></th>
            <td><input class="text-input" name="contact_person" type="text" id="contact_person" value="<?php if (isset($_POST['contact_person'])) {
                echo $_POST["contact_person"];
                                                                                                        } else {
                                                                                                            echo \PoPCMSSchema\UserMeta\Utils::getUserMeta($user->ID, GD_URE_METAKEY_PROFILE_CONTACTPERSON, true);
                                                                                                        } ?>" /></td>
        </tr>
        <tr>
            <th><label for="contact_number"><?php _e('Contact number', 'poptheme-wassup'); ?></label></th>
            <td><input class="text-input" name="contact_number" type="text" id="contact_number" value="<?php if (isset($_POST['contact_number'])) {
                echo $_POST["contact_number"];
                                                                                                        } else {
                                                                                                            echo \PoPCMSSchema\UserMeta\Utils::getUserMeta($user->ID, GD_URE_METAKEY_PROFILE_CONTACTNUMBER, true);
                                                                                                        } ?>" /></td>
        </tr>
    </tbody>
    </table>
    <?php
}

function customSaveExtraUserProfileFields($user_id)
{
    if (!is_admin()) {
        return;
    }

    \PoPCMSSchema\UserMeta\Utils::updateUserMeta($user_id, GD_URE_METAKEY_PROFILE_CONTACTPERSON, esc_attr($_POST['contact_person']), true);
    \PoPCMSSchema\UserMeta\Utils::updateUserMeta($user_id, GD_URE_METAKEY_PROFILE_CONTACTNUMBER, esc_attr($_POST['contact_number']), true);
    \PoPCMSSchema\UserMeta\Utils::updateUserMeta($user_id, GD_METAKEY_PROFILE_FACEBOOK, esc_attr($_POST['facebook']), true);
    \PoPCMSSchema\UserMeta\Utils::updateUserMeta($user_id, GD_METAKEY_PROFILE_TWITTER, esc_attr($_POST['twitter']), true);
    \PoPCMSSchema\UserMeta\Utils::updateUserMeta($user_id, GD_METAKEY_PROFILE_LINKEDIN, esc_attr($_POST['linkedin']), true);
    \PoPCMSSchema\UserMeta\Utils::updateUserMeta($user_id, GD_METAKEY_PROFILE_YOUTUBE, esc_attr($_POST['youtube']), true);
    \PoPCMSSchema\UserMeta\Utils::updateUserMeta($user_id, GD_METAKEY_PROFILE_INSTAGRAM, esc_attr($_POST['instagram']), true);

    userNameUpdated($user_id);
}
