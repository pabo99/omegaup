<?php

include_once('base/Groups_Identities.dao.base.php');
include_once('base/Groups_Identities.vo.base.php');
/** GroupsIdentities Data Access Object (DAO).
  *
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para
  * almacenar de forma permanente y recuperar instancias de objetos {@link GroupsIdentities }.
  * @access public
  *
  */
class GroupsIdentitiesDAO extends GroupsIdentitiesDAOBase {
    public static function GetMemberIdentities(Groups $group) {
        global  $conn;
        $sql = '
            SELECT
                i.username,
                i.name,
                c.name as country,
                c.country_id,
                s.name as state,
                s.state_id,
                sc.name as school,
                sc.school_id as school_id,
                u.username as user_username,
                (SELECT `urc`.classname FROM
                    `User_Rank_Cutoffs` urc
                WHERE
                    `urc`.score <= (
                            SELECT
                                `ur`.`score`
                            FROM
                                `User_Rank` `ur`
                            WHERE
                                `ur`.user_id = `i`.`user_id`
                        )
                ORDER BY
                    `urc`.percentile ASC
                LIMIT
                    1) `classname`
            FROM
                Groups_Identities gi
            INNER JOIN
                Identities i ON i.identity_id = gi.identity_id
            LEFT JOIN
                States s ON s.state_id = i.state_id AND s.country_id = i.country_id
            LEFT JOIN
                Countries c ON c.country_id = s.country_id
            LEFT JOIN
                Schools sc ON sc.school_id = i.school_id
            LEFT JOIN
                Users u ON u.user_id = i.user_id
            WHERE
                gi.group_id = ?;';

        $rs = $conn->Execute($sql, [$group->group_id]);
        $identities = [];
        foreach ($rs as $row) {
            if (strpos($row['username'], ':') === false) {
                array_push($identities, [
                    'username' => $row['username'],
                    'classname' => $row['classname'] ?? 'user-rank-unranked',
                ]);
                continue;
            }
            $row['classname'] = $row['classname'] ?? 'user-rank-unranked';
            array_push($identities, $row);
        }
        return $identities;
    }

    public static function GetMemberCountById($group_id) {
        global  $conn;
        $sql = '
            SELECT
                COUNT(*) AS count
            FROM
                Groups_Identities gi
            WHERE
                gi.group_id = ?;';
        $params = [$group_id];
        return $conn->GetOne($sql, $params);
    }
}
