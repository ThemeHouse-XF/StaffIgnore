<?php

/**
 *
 * @see XenForo_Model_UserIgnore
 */
class ThemeHouse_StaffIgnore_Extend_XenForo_Model_UserIgnore extends XFCP_ThemeHouse_StaffIgnore_Extend_XenForo_Model_UserIgnore
{

    public function getUserIgnoreCache($userId)
    {
        $ignoreCache = parent::getUserIgnoreCache($userId);

        if ($userId == XenForo_Visitor::getUserId()) {
            $viewingUser = XenForo_Visitor::getInstance();
        } else {
            $viewingUser = $this->_getUserModel()->getUserById($userId);
        }

        if ($viewingUser['is_staff']) {
            $ignoredStaff = $this->_getDb()->fetchPairs(
                '
    			SELECT user.user_id, user.username
    			FROM xf_user_ignored AS ignored
    			INNER JOIN xf_user AS user ON
    				(ignored.ignored_user_id = user.user_id
    				AND (user.is_admin = 1 OR user.is_moderator = 1)
    				AND user.user_id <> ignored.user_id)
    			WHERE ignored.user_id = ?
    			ORDER BY user.username
    		', $userId);
            $ignoreCache = array_merge($ignoreCache, $ignoredStaff);
        }

        return $ignoreCache;
    } /* END getUserIgnoreCache */

    public function canIgnoreUser($userId, array $user, &$error = '')
    {
        $canIgnoreUser = parent::canIgnoreUser($userId, $user, $error);

        $setError = (func_num_args() >= 3);

        if (!$canIgnoreUser && $user['is_staff']) {
            $user['is_staff'] = false;

            $canIgnoreUser = parent::canIgnoreUser($userId, $user, $error);

            if (!$canIgnoreUser) {
                return $canIgnoreUser;
            }

            if ($userId == XenForo_Visitor::getUserId()) {
                $viewingUser = XenForo_Visitor::getInstance();
            } else {
                $viewingUser = $this->_getUserModel()->getUserById($userId);
            }

            if ($viewingUser['is_staff']) {
                if ($setError) {
                    $error = '';
                }
                return true;
            }
        }

        return $canIgnoreUser;
    } /* END canIgnoreUser */
}