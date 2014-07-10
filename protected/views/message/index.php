<div class="row">
    <div class="col-sm-2 sidebar">
        <ul class="nav nav-sidebar message-users">
            <?php foreach ($users as $user) { ?>
                <li><a href="#<?= $user->id; ?>" data-id="<?= $user->id; ?>"
                       data-name="<?= $user->name; ?>"><?= $user->name; ?><span class="badge pull-right"></span></a>
                </li>
            <?php } ?>
        </ul>
    </div>
    <div class="col-sm-9 col-sm-offset-3 message-content"></div>
    <div class="col-sm-6 col-sm-offset-2 message-form">
        <form role="form">
            <div class="form-group">
                <textarea class="form-control" rows="2"
                          onkeypress="if(event.keyCode==10||(event.ctrlKey && event.keyCode==13))$('.message-form button').click();"
                          style="resize: none;"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Отправить сообщение</button>
            Ctrl+Enter — отправка сообщения, Enter — перенос строки
        </form>

    </div>
</div>