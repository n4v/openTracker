<?php
try {

    $this->setSidebar(true);

    $db = new DB("support");
    $db->setColPrefix("ticket_");
    $db->select("ticket_id = '" . $db->escape($_GET['ticket']) . "'");
    if (!$db->numRows())
        throw new Exception("ticket not found");

    $ticket_id = $_GET['ticket'];

    $db->nextRecord();

    if ($db->user != USER_ID)
        throw new Exception("Access denied");

    switch ($db->status) {
        default:
            $msg = "<font color='red'>" . _t("unsolved") . "</font>";
            break;
        case 1:
            $msg = "<font color='green'>" . _t("solved") . "</font>";
            break;
    }
    ?>
    <h4><?php echo $db->subject; ?>: <?php echo $msg; ?></h4>
    <?php
    if ($db->status == 0) {
        ?>
        <form method="post">
            <input type="hidden" name="secure_input" value="<?php echo $_SESSION['secure_token_last'] ?>">
            <?php echo bbeditor("message", 7, 70) ?><br />
            <input type="submit" name="reply" value="<?php echo _t("Reply") ?>">
        </form>
        <?php
    }
    ?>
    <div id="conv">
        <?php
        if (isset($_POST['reply'])) {
            try {
                if ($_POST['secure_input'] != $_SESSION['secure_token'])
                    throw new Exception("Wrong secured token");

                if (empty($_POST['message']))
                    throw new Exception("missing form");


                if ($db->status == 1)
                    throw new Exception("Ticked is marked as solved and can not take anymore messages.");

                $db = new DB("support_messages");
                $db->setColPrefix("message_");
                $db->user = USER_ID;
                $db->added = time();
                $db->content = $_POST['message'];
                $db->ticket = $ticket_id;
                $db->unread = 1;
                $db->insert();
            } Catch (Exception $e) {
                echo error(_t($e->getMessage()));
            }
        }

        $db = new DB("support_messages");
        $db->setColPrefix("message_");
        $db->setSort("message_added DESC");
        $db->select("message_ticket = '" . $db->escape($ticket_id) . "'");
        while ($db->nextRecord()) {
            $user = new Acl($db->user);
            ?>
            <div class="item">
                <div class="avatar">
                    <?php echo "<img src='" . $user->avatar() . "' style='max-width:70px'>"; ?>
                </div>
                <div class="sender">
                    <a href="<?php echo page("profile", "view", $user->name) ?>"><b><?php echo $user->name ?></b></a><br />
                    <?php echo htmlformat($db->content, true); ?>
                </div>
                <div class="date">
                    <?php echo get_date($db->added); ?>
                </div>
            </div>
            <?
        }
        ?>
    </div>
    <?php
    $db = new DB("support_messages");
    $db->message_unread = 1;
    $db->update("message_ticket = '" . $db->escape($ticket_id) . "'");
} Catch (Exception $e) {
    echo error(_t($e->getMessage()));
}
?>
