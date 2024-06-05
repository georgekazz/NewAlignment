<fieldset>
    <div class="row">
        <div class="col-md-4">
            <div id="radio" class='form-group'></div>
        </div>
        <div class="col-md-8">
            <div class="row">
                <div id="other" class="form-group">
                    <input id="other1" type="radio" name="link_type" value="other1" />
                    <i data-toggle="tooltip" data-placement="auto" data-container="body" data-animations="true" title="This option will create a link of your choice. Enter select other and enter a HTTP valid IRI in the text area." class="fa fa-fw fa-info-circle info-icon"></i>
                    Other<br />
                    <input type="text" name="other-text1" id="other-text1" class="form-control" value="">
                </div>
            </div>
            <div class="row" id="create-btn">
                <button type="button" class="btn btn-success btn-lg btn-block btn-flat" title="Click to create the link" onclick="getAction()">Create Link</button>
            </div>
        </div>
    </div>
</fieldset>
<div style="height: 350px" id="delete-dialog" title="Delete All" hidden="true">
    <p>
        <span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>
        These items will be permanently deleted and cannot be recovered. Are you sure?
    </p>
</div>

<script>
    var project_id = {{$project->id}};

    $('#other-text1').keypress(function(event) {
        if (event.which == 13) {
            event.preventDefault();
            getAction();
        }
    });

    function deleteAll() {
        $("#links-utility").load("utility/delete_all", {"project_id": project_id}, function() {
            reload();
        });
    }

    function deleteDialog() {
        $("#delete-dialog").dialog({
            resizable: false,
            height: 250,
            width: 550,
            modal: true,
            buttons: {
                "Confirm Delete All": function() {
                    $(this).dialog("close");
                    deleteAll();
                },
                Cancel: function() {
                    $(this).dialog("close");
                }
            }
        });
    }

    function getAction() {
        var target = "";
        var source = "";
        var link_type = "";
        var input = $("input:checked");

        if (input.length !== 0) {
            var checked = input[0];
            if (checked.value === "other1") {
                var otherText = $('#other-text1').val();
                if (otherText === "") {
                    $.toaster({ priority: 'error', title: 'Error', message: 'Could not create a link. Other is checked. Please provide a valid IRI in textarea' });
                    return;
                } else {
                    if (URLValidation(otherText)) {
                        link_type = otherText;
                    } else {
                        $.toaster({ priority: 'error', title: 'Error', message: 'Could not create a link. Invalid URL given. Please provide a valid IRI in textarea.' });
                        return;
                    }
                }
            } else {
                link_type = checked.value;
            }
        }

        source = $('#details_source').children().first().attr('id');
        target = $('#details_target').children().first().attr('id');

        if (source && target && link_type) {
            $("#links-utility").load("utility/create", { "source": source, "target": target, "link_type": link_type, "project_id": project_id },
                function(msg) {
                    if (msg === "1") {
                        $.toaster({ priority: 'success', title: 'Success', message: 'Link Created Successfully.' });
                        reload();
                    } else {
                        $.toaster({ priority: 'warning', title: 'Warning', message: 'Link already exists!' });
                    }
                });
        } else {
            handleError(source, target, link_type);
        }
    }

    function handleError(source, target, link_type) {
        if (!source) {
            $.toaster({ priority: 'error', title: 'Error', message: 'Could not create a link. No source entity selected.' });
        } else if (!target) {
            $.toaster({ priority: 'error', title: 'Error', message: 'Could not create a link. No target entity selected.' });
        } else if (!link_type) {
            $.toaster({ priority: 'error', title: 'Error', message: 'Could not create a link. No link type selected.' });
        }
    }

    function URLValidation(s) {
        var match_url_re = /^[a-z](?:[-a-z0-9\+\.])*:(?:\/\/(?:(?:%[0-9a-f][0-9a-f]|[-a-z0-9\._~!\$&'\(\)\*\+,;=:\xA0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[\uD800-\uD83E\uD840-\uD87E\uD880-\uD8BE\uD8C0-\uD8FE\uD900-\uD93E\uD940-\uD97E\uD980-\uD9BE\uD9C0-\uD9FE\uDA00-\uDA3E\uDA40-\uDA7E\uDA80-\uDABE\uDAC0-\uDAFE\uDB00-\uDB3E\uDB44-\uDB7E][\uDC00-\uDFFF]|[\uD83F\uD87F\uD8BF\uD8FF\uD93F\uD97F\uD9BF\uD9FF\uDA3F\uDA7F\uDABF\uDAFF\uDB3F\uDB7F][\uDC00-\uDFFD])*@)?(?:\[(?:(?:(?:[0-9a-f]{1,4}:){6}(?:[0-9a-f]{1,4}:[0-9a-f]{1,4}|(?:[0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])(?:\.(?:[0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])){3})|::(?:[0-9a-f]{1,4}:){5}(?:[0-9a-f]{1,4}:[0-9a-f]{1,4}|(?:[0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])(?:\.(?:[0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])){3})|(?:[0-9a-f]{1,4})?::(?:[0-9a-f]{1,4}:){4}(?:[0-9a-f]{1,4}:[0-9a-f]{1,4}|(?:[0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])(?:\.(?:[0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])){3})|(?:(?:[0-9a-f]{1,4}:){0,2}[0-9a-f]{1,4})?::(?:[0-9a-f]{1,4}:){3}(?:[0-9a-f]{1,4}:[0-9a-f]{1,4}|(?:[0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])(?:\.(?:[0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])){3})|(?:(?:[0-9a-f]{1,4}:){0,3}[0-9a-f]{1,4})?::[0-9a-f]{1,4}:(?:[0-9a-f]{1,4}:[0-9a-f]{1,4}|(?:[0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])(?:\.(?:[0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])){3})|(?:(?:[0-9a-f]{1,4}:){0,4}[0-9a-f]{1,4})?::(?:[0-9a-f]{1,4}:[0-9a-f]{1,4}|(?:[0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])(?:\.(?:[0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])){3})|(?:(?:[0-9a-f]{1,4}:){0,5}[0-9a-f]{1,4})?::(?:[0-9a-f]{1,4}:[0-9a-f]{1,4}|(?:[0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])(?:\.(?:[0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])){3})|(?:(?:[0-9a-f]{1,4}:){0,6}[0-9a-f]{1,4})?::(?:[0-9a-f]{1,4}:[0-9a-f]{1,4}|(?:[0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])(?:\.(?:[0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])){3}))\]|(?:(?:%[0-9a-f][0-9a-f]|[-a-z0-9\._~!\$&'\(\)\*\+,;=]|:|[\uE000-\uF8FF]|[\uF0000-\uFFFFD]|[\u100000-\u10FFFD])*)@)?(?:\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\]|(?:%[0-9a-f][0-9a-f]|[-a-z0-9\._~!\$&'\(\)\*\+,;=]|:|[\uE000-\uF8FF]|[\uF0000-\uFFFFD]|[\u100000-\u10FFFD])*)))(?::[0-9]*)?(?:\/(?:%[0-9a-f][0-9a-f]|[-a-z0-9\._~!\$&'\(\)\*\+,;=:@]|\/|[\uE000-\uF8FF]|[\uF0000-\uFFFFD]|[\u100000-\u10FFFD])*)*(?:\?(?:%[0-9a-f][0-9a-f]|[-a-z0-9\._~!\$&'\(\)\*\+,;=:@]|\/|\?|[\uE000-\uF8FF]|[\uF0000-\uFFFFD]|[\u100000-\u10FFFD])*)?(?:#(?:%[0-9a-f][0-9a-f]|[-a-z0-9\._~!\$&'\(\)\*\+,;=:@]|\/|\?|[\uE000-\uF8FF]|[\uF0000-\uFFFFD]|[\u100000-\u10FFFD])*)?$/i;

        return match_url_re.test(s);
    }
</script>
