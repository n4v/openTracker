<?php

$this->setTitle("Admin - Settings");
$this->setSidebar(true);
try {
    $acl = new Acl(USER_ID);
    if (!$acl->Access("x"))
        throw new Exception("Access denied");

    $action = isset($this->args["var_a"]) ? $this->args['var_a'] : "";

    $tpl = new Template(PATH_APPLICATIONS . "admin/tpl/documents/");
    switch ($action) {
        default:
            $tpl->loadFile("main.php");
            break;

        case 'edit':
            $tpl->type = isset($this->args["var_b"]) ? $this->args['var_b'] : false;
            $tpl->loadFile("edit.php");
            break;

        case 'create':
            $tpl->loadFile("create.php");
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
