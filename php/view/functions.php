<?php
function is_superadmin() {
    return isset($_SESSION['admin_role']) && $_SESSION['admin_role'] === 'super_admin';
}
function is_admin() {
    return isset($_SESSION['admin_role']) && $_SESSION['admin_role'] === 'admin';
}
