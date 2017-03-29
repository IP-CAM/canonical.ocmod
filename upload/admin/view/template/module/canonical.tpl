<?php echo $header; ?>
<?php echo $column_left; ?>


<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" form="form-canonical" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
            <h1><?= $heading_title ?></h1>
            <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="container-fluid">

        <?php if ($error_warning) { ?>
            <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        <?php } ?>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
            </div>
            <div class="panel-body">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-canonical" class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
                        <div class="col-sm-10">
                            <select name="canonical_status" id="input-status" class="form-control">
                                <?php if ($canonical_status) { ?>
                                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                <option value="0"><?php echo $text_disabled; ?></option>
                                <?php } else { ?>
                                <option value="1"><?php echo $text_enabled; ?></option>
                                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </form>
            </div>

        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?= $heading_rule_edit ?></h3>
            </div>
            <section class="ruleCreating">

                <form name="addRule" id="addRuleForm" method="Post" action="<?= $add_rule_href ?>">
                    <table class="table ruleParameters">
                        <tr>
                            <td colspan="2">
                                <label for="basic-url"><?= $select_canonical_path ?>
                                <div class="input-group">
                                    <span class="input-group-addon" id="basic-addon1"><?= $host ?></span>
                                    <input type="text" name="canonical_url" class="form-control" id="basic-url" aria-describedby="basic-addon1" required="">
                                </div>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td >
                                <label for="basic-url col-lg-8"><?= $select_page_path ?>
                                <div class="input-group">
                                    <span class="input-group-addon" id="basic-addon2"><?= $host ?></span>
                                    <input type="text" name="url" id="page_path" class="form-control" id="basic-url" aria-describedby="basic-addon2" required>
                                </div>
                                </label><br>
                                <label>GET-параметры</label><br>
                                <button class="btn btn-secondary" id="addGet"><?= $add_param_button ?></button>
                            </td>
                            <td>
                                <div class="get_params">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <input type="reset" class="btn btn-secondary" value="<?= $add_rule_button ?>">
                                <input type="submit" class="btn btn-primary" value="<?= $submit_rule_button ?>">
                            </td>
                        </tr>
                    </table>
                </form>
            </section>
        </div>
        <div class="panel panel-default">
            <section class="rules">
                <div class="panel-heading">
                    <h3 class="panel-title"><?= $rules_header ?></h3>
                </div>
                <table class="table">
                    <thead>
                    <tr>
                        <th>№</th>
                        <th><?= $rules_url ?></th>
                        <th><?= $rules_canon_url ?></th>
                        <th></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php foreach ($rules as $id => $rule) { ?>

                    <?php $paramsString = '';
                    if (!empty($rule['params'])) {
                        foreach ($rule['params'] as $key => $value) {
                            $paramsString .= $key . '=' . $value . ', ';
                        }
                    } ?>

                        <tr>
                            <th scope="row"><?= $id ?></th>
                            <td>
                                <?= isset($rule['url']) ?
                                        $rule['url'] . "<br>" . $paramsString
                                        : $paramsString
                                ?>
                            </td>
                            <td><?= $rule['canonical_url'] ?></td>
                            <td>
                                <form name="delete_rule" method="post" action="<?= $delete_rule_href ?>">
                                    <input type="hidden" name="id" value="<?= $id ?>">
                                    <input type="submit" class="btn btn-primary" value="<?= $rules_remove_button ?>">
                                </form>
                            </td>
                        </tr>

                    <?php } ?>

                    </tbody>
                </table>
            </section>

        </div>
    </div>
</div>
<script>
    var i = 0;
    $('#addGet').on('click',function(event) {
        event.preventDefault();
        $('.get_params').append(
            '<div class="row col-lg-8">' +
            '<input type="text" class="form-control" placeholder="key" name="params['+i+'][key]" required>' +
            '<input type="text" class="form-control" placeholder="value" name="params['+i+'][value]">' +
            '</div>'
        );
        i++;
        $('#page_path').removeAttr('required');
    });
    $('input[type=reset]').on('click',function() {
        console.log('r');
        i = 0;
        $('.get_params').text("");
    });
</script>

<?php echo $footer; ?>