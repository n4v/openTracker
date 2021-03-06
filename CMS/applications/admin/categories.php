<?php

$this->setTitle("Admin - Categories");
$this->setSidebar(true);
try {
    $acl = new Acl(USER_ID);
    if (!$acl->Access("x"))
        throw new Exception("Access denied");

    $action = isset($this->args["var_a"]) ? $this->args['var_a'] : "";

    $tpl = new Template(PATH_APPLICATIONS . "admin/tpl/categories/");
    switch ($action) {
        default:
            $tpl->loadFile("main.php");
            break;

        case 'create':
            $tpl->loadFile("create.php");
            break;

        case 'edit':
            $tpl->id = isset($_GET['id']) ? $_GET['id'] : false;
            $tpl->loadFile("edit.php");
            break;

        case 'delete':
            $tpl->loadFile("delete.php");
            break;
    }
    $tpl->build();
} Catch (Exception $e) {
    echo error(_t($e->getMessage()));
}
?>

