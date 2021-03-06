<?php

$this->setTitle("Admin - Settings");
$this->setSidebar(true);
try {
    $acl = new Acl(USER_ID);
    if (!$acl->Access("z"))
        throw new Exception("Access denied");

    $tpl = new Template(PATH_APPLICATIONS . "admin/tpl/translations/");
    $action = isset($this->args["var_a"]) ? $this->args['var_a'] : "";

    switch ($action) {
        default:
            $tpl->loadFile("main.php");
            break;

        case 'edit':
            $tpl->lang_id = (isset($this->args['var_b'])) ? $this->args['var_b'] : 0;
            $tpl->loadFile("edit.php");
            break;

        case 'import':
            $tpl->loadFile("import.php");
            break;

        case 'export':
            $tpl->lang_id = (isset($this->args['var_b'])) ? $this->args['var_b'] : 0;
            $tpl->loadFile("export.php");
            break;

        case 'delete':
            $tpl->loadFile("delete.php");
            break;

        case 'create':
            $tpl->loadFile("create.php");
            break;
    }
    $tpl->build();
} Catch (Exception $e) {
    echo error(_t($e->getMessage()));
}
?>
