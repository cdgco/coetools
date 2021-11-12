<?php

$ldapconn = ldap_connect("ldaps://ldap2", 389) or die("Could not connect to LDAP server.");

if($_GET['v'] == '2') {
    if($ldapconn) {
        $ldapbind = ldap_bind($ldapconn, 'uid='. $_SERVER['PHP_AUTH_USER']. ',ou=groups,dc=example,dc=com', $_SERVER['PHP_AUTH_PW']) or die ("Error trying to bind: ".ldap_error($ldapconn));

        if ($ldapbind) {
            $result = ldap_search($ldapconn, 'dc=example,dc=com', '(&(cn=*)(memberUid='.$_SERVER['PHP_AUTH_USER'].'))') or exit("Unable to search LDAP server");
            $groups = ldap_get_entries($ldapconn, $result);
            $userGroups = [];
            foreach ($groups as &$group) {
                if ($group['cn'][0]) {
                    array_push($userGroups, $group['cn'][0]);
                }
            }     
            print_r(in_array('support', $userGroups));
        }
    }

    ldap_close($ldapconn);
}
else {
    if($ldapconn) {
        $ldapbind = ldap_bind($ldapconn, 'uid='. $_SERVER['PHP_AUTH_USER']. ',ou=groups,dc=example,dc=com', $_SERVER['PHP_AUTH_PW']) or die ("Error trying to bind: ".ldap_error($ldapconn));
    
        if ($ldapbind) {
            $result = ldap_search($ldapconn, 'dc=example,dc=com', '(&(cn=*)(memberUid='.$_SERVER['PHP_AUTH_USER'].'))') or exit("Unable to search LDAP server");
            $groups = ldap_get_entries($ldapconn, $result);
            echo "<h2>Current user groups:</h2><ul>";
            foreach ($groups as &$group) {
                if ($group['cn'][0]) {
                    echo "<li>".$group['cn'][0] . "</li>";
                }
            }     
            echo '</ul><h2>Raw Data</h2><pre>';
            print_r($groups);
            echo "</pre>";
        }
    }
    ldap_close($ldapconn);
}