window.onerror = function () {
    return true;
};

var F = {
    dialogId: null,
    showMessage: function (message) {
        alert(message);
    },
    initModelDialog: function () {
        var f_dialog = $('#f-dialog');
        if (f_dialog.length > 0) {
            this.dialogId = f_dialog;
            return;
        }

        var dialog_content = '<div class="modal fade" id="f-dialog">\
            <div class="modal-dialog">\
                <div class="modal-content">\
                    <div class="modal-header">\
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>\
                        <h4 class="modal-title" id="f-dialog-title">提示</h4>\
                    </div>\
                    <div class="modal-body" id="f-dialog-content">\
                    </div>\
                    <div id="f-dialog-footer" class="modal-footer">\
                        <button type="button" class="btn btn-primary" id="f-dialog-btn-close" data-dismiss="modal">关闭</button>\
                    </div>\
                </div>\
                <!-- /.modal-content -->\
            </div>\
            <!-- /.modal-dialog -->\
        </div><!-- /.modal -->';
        $(dialog_content).appendTo('body');

        this.dialogId = $('#f-dialog');
    },
    showDialog: function (content, title) {

        if (typeof content == 'object') {
            if (typeof content.url == 'string') {
                var url = content.url;
                $.get(url, { }, function (retData) {
                    F.showDialog(retData, title);
                });
            }

            return;
        }

        if (!this.dialogId)
            this.initModelDialog();

        // 如果是内容里面有表单，去掉 footer
        if (content.indexOf('</form>') != -1) {

            if (!title)
                title = "表单";

            $('#f-dialog-footer').addClass('hidden');

        }
        else {

            if (!title)
                title = "提示";

            $('#f-dialog-footer').removeClass('hidden');
        }

        $('#f-dialog-content').html(content);
        if (title) {
            $('#f-dialog-title').html(title);
        }


        this.dialogId.modal();
    },
    showProgress: function () {

        if (!this.dialogId)
            this.initModelDialog();

        $('#f-dialog-content').html('请稍等。。。');
        $('#f-dialog-title').html();
        this.dialogId.modal();
    },
    closeDialog: function () {
        $('#f-dialog-btn-close').click();
        //this.dialogId.remove();
        this.dialogId = null;
    }
};


$.ajaxSetup({ cache: false });

var pp;
function apply_ajax() {

    $(document).ready(function () {

        $('a[rel="ajax"], button[rel="ajax"]').each(function () {
            $(this).attr('rel', '');

            $(this).click(function () {

                F.showProgress();
                if ($(this).attr('confirm') != undefined) {
                    return ajax_confirm($(this).attr('confirm'), $(this).attr('href'));
                }
                else {
                    ajax_get($(this).attr('href'));

                    return false;
                }

            });
        });

        if ($("form")[0] != undefined) {
            if (typeof $.fn.ajaxForm == 'undefined') {
                $.getScript('/js/jquery.form.js', function () {
                    apply_ajax_form();
                });
            }
            else {
                apply_ajax_form();
            }
        }
    });
}

function apply_ajax_form() {
    $('form[rel="ajax"]').each(function () {
        $(this).attr('rel', '');

        $(this).ajaxForm({
            type: 'post',
            dataType: 'json',
            beforeSubmit: function () {
                try {
                    editor.sync();
                } catch (e) {
                }
            },
            success: function (data) {
                $.closeFloatDiv();

                if (data.result == 'redirect') {
                    location.href = data.url;
                }
                else if (data.result == 'failed') {
                    ajax_form_failed(data);
                }
                else {

                    ajax_form_success(data);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {

                if (textStatus == 'parsererror') {
                    F.showDialog(jqXHR['responseText']);
                }
                else {

                    if (jqXHR['status'] != '200') {
                        F.showDialog('<p style="padding:10px;">发生错误，描述如下：<br /><h1>'
                            + jqXHR['status'] + ' &nbsp; ' + jqXHR['statusText'] + '</h1>'
                            + jqXHR['responseText'] + '</p>');
                    }
                }
            }
        });
    });
}

function ajax_form_success(data) {

    var message_content = null;

    if (typeof(data) == 'string' && data.indexOf('{') == 0) {
        data = eval('(' + data + ')');

        if (data.result == 'redirect') {
            location.href = data.url;
            return;
        }
    }

    if (typeof data.content == 'undefined') {
        message_content = data;
    } else {
        message_content = data.content;
    }

    if (message_content == 'close') {
        //        $.colorbox.close();
        return;
    }

    $.closeFloatDiv();
    F.showDialog(message_content, data.title);

    if (data.url != undefined && data.url != '') {
        $('#f-dialog-btn-close').click(function () {
            location.href = data.url;
            return true;
        });
    }

    if (data.close_time != undefined && data.close_time > 0) {
        setTimeout(function () {
            $.colorbox.close();
        }, data.close_time * 1000);
    }

    return;
}

function ajax_form_failed(data) {
    var lost_items_text = '';

    if (data.msg)
        alert(data.msg);

    for (var key in data.items) {

        var element = undefined;

        if ($('input#' + key).length > 0) {
            element = $('input#' + key);
        } else if ($('input[name=' + key + ']')[0] != undefined) {
            element = $('input[name=' + key + ']');
        }
        else if ($('select[name=' + key + ']')[0] != undefined) {
            element = $('select[name=' + key + ']');
        }
        else if ($('textarea[name=' + key + ']')[0] != undefined) {
            element = $('textarea[name=' + key + ']');
        }
        else {
            lost_items_text += '[ ' + key + ' ] &nbsp;';
        }

        if (element != undefined) {

            var e_hash = 'e-' + key;

            if (!$('#lbl_' + e_hash)[0]) {

                //                if (!element.parent().hasClass('input-group')) {
                //                    var parentClass = element.parent().attr('class');
                //                    element.parent().removeClass(parentClass);
                //                    element.wrap('<div class="input-group" />').parent().addClass(parentClass);
                //                }

                element.attr('hash', e_hash).parent().addClass('has-error has-feedback');

                if (element.parent().hasClass('input-group')) {
                    element.after('<span class="glyphicon glyphicon-remove form-control-feedback"></span><span class="input-group-addon error-tips" id="lbl_' + e_hash + '">' + data.items[key] + '</span>');
                }
                else {
                    element.after('<span class="glyphicon glyphicon-remove form-control-feedback"></span><span class="error-tips" id="lbl_' + e_hash + '">' + data.items[key] + '</span>');
                }
            }

            element.click(function () {
                $(this).parent().removeClass('has-error has-feedback');
                $('.form-control-feedback').remove();
                $('#lbl_' + $(this).attr('hash')).remove();
            });
        } else {
            alert(retData.content);
        }
    }

    if (lost_items_text != '') {
        $.colorbox({html: '表单丢失了以下项目：' + lost_items_text});
    }

    if (data.message != undefined && data.message != '') {
        $.colorbox({html: '<span style="color:#f00;">' + data.message + '</span>'});
    }
}

function ajax_get(url_href) {

    var fancybox_ajax_url = '';
    if (typeof(url_href) == "object") {
        url_href = url_href.href;
    }
    if (url_href.indexOf('?') != -1) {
        fancybox_ajax_url = url_href + '&in_ajax=1';
    }
    else {
        fancybox_ajax_url = url_href + '?in_ajax=1';
    }

    $.ajax({
        url: fancybox_ajax_url,
        type: 'get',
        cache: false,
        // 'dataType':'json',
        success: function (ret_result) {

            ajax_form_success(ret_result);
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            if (XMLHttpRequest.status == 200 || XMLHttpRequest.status == 0) {
            }
            else if (XMLHttpRequest.status == 500) {
                $.showFloatDiv({
                    txt: '服务器内部错误(500)',
                    class_name: 'error'
                });
                return false;
            }
            else {
                alert('发生错误，error no (' + XMLHttpRequest.status + ')，请联系 fanshengshuai@gmail.com ');
            }

            return false;
        }
    });
}

function ajax_confirm(confirm_text, url_href) {
    $.colorbox({html: "<div class='flib_confirm_dlg'><h4 class='flib_confirm_content'>" + confirm_text + "</h4>"
        + "<ul class='flib_confirm_btn_area'>" +
        " <li href='" + url_href + "' id='flib_confirm_btn_yes' class='ui-button btn_yes'>是的<div class='btn-right'></div></li><li id='flib_confirm_btn_no' class='ui-button btn_no'>暂时不要<div class='btn-right'></div></li></ul></div>"
    });

    $('#flib_confirm_btn_yes').click(function () {
        ajax_get($(this).attr('href'));

        return false;
    });
    $('#flib_confirm_btn_no').click(function () {
        $.colorbox.close();

        return false;
    });
    return false;
}

jQuery.showFloatDiv = function (ox) {
    var oxdefaults = {
        content: '数据加载中,请稍后...',
        left: 410,
        top: 210,
        want_close: 1,
        suredo: function (e) {
            return false;
        },
        succdo: function (r) {
        },
        complete_text: '操作成功!',
        auto_close: 1,
        is_post: 0,
        class_name: 'alert',
        is_ajax: 0,
        intval_time: 1000,
        redirect_url: '/',
        dataType: 'json'
    };
    ox = ox || {};
    $.extend(oxdefaults, ox);
    $("#f-box_overlay").remove();
    $("#f-box").remove();

    if (oxdefaults.want_close == 1) {
        var floatdiv = $('<div class="f-box-overlayBG" id="f-box_overlay"></div><div id="f-box" class="f-box png-img"><iframe frameborder="0" class="ui-iframe"></iframe><table class="ui-dialog-box"><tr><td><div class="ui-dialog"><div class="ui-dialog-cnt" id="ui-dialog-cnt"><div class="ui-dialog-tip alert" id="ui-cnt"><span id="xtip">'
            + oxdefaults.content
            + '</span></div></div><div class="ui-dialog-close"><span class="close">关闭</span></div></div></td></tr></table></div>');
        $("body").append(floatdiv);
        $("#f-box_overlay").fadeIn(500);
        $("#f-box").fadeIn(500);
        $("#ui-cnt").removeClass('success error alert loading').addClass(
            oxdefaults.class_name);
        $(".ui-dialog-close").click(function () {
            $.closeFloatDiv();
        });
        if (oxdefaults.is_ajax == 1) {
            objEvent = getEvent();
            if (objEvent.srcElement) id = objEvent.srcElement;
            else id = objEvent.target;

            var url = (id.attributes["href"].nodeValue != null && id.attributes["href"].nodeValue != undefined) ? id.attributes["href"].nodeValue : id.data;
            $.ajax({
                url: url,
                async: true,
                type: 'get',
                cache: true,
                dataType: oxdefaults.dataType,
                success: function (data, textStatus) {

                    if (oxdefaults.dataType == 'html') {
                        $.closeFloatDiv();
                        $.colorbox({html: data });
                        return;
                    }

                    if (data.content != null && data.content != undefined) {
                        $("#xtip").html(data.content);
                    } else {
                        $("#xtip").html(oxdefaults.complete_text);
                    }
                    oxdefaults.succdo(data);
                    if (data.want_close != null && data.want_close != undefined) {
                        $.hidediv(data);
                    } else if (oxdefaults.auto_close == 1) {
                        $.hidediv(data);
                    }
                    if (data.wantredir != undefined || data.wantredir != null) {
                        if (data.redir != undefined || data.redir != null) {
                            setTimeout("$.refresh('" + data.redir + "')",
                                oxdefaults.intval_time);
                        } else {
                            setTimeout("$.refresh('" + oxdefaults.redirect_url
                                + "')", oxdefaults.intval_time);
                        }
                    }
                },
                error: function (e) {
                    $("#xtip").html('系统繁忙,请稍后再试...');
                }
            });
        }
    } else if (oxdefaults.want_close == 2) {
        objEvent = getEvent();
        if (objEvent.srcElement)
            id = objEvent.srcElement;
        else
            id = objEvent.target;
        var idval = (id.attributes["data"].nodeValue != null && id.attributes["data"].nodeValue != undefined) ? id.attributes["data"].nodeValue
            : id.data;
        var floatdiv = $('<div class="f-box-overlayBG" id="f-box_overlay"></div><div id="f-box" class="f-box png-img"><iframe frameborder="0" class="ui-iframe"></iframe><table class="ui-dialog-box"><tr><td><div class="ui-dialog"><div class="ui-dialog-cnt" id="ui-dialog-cnt"><div class="ui-dialog-tip alert" id="ui-cnt"><span id="xtip">'
            + oxdefaults.txt
            + '</span></div></div><div class="ui-dialog-todo"><a class="ui-link ui-link-small" href="javascript:void(0);" id="surebt">确定</a><a class="ui-link ui-link-small cancelbt"  id="cancelbt">取消</a><input type="hidden" id="hideval" value=""/></div><div class="ui-dialog-close"><span class="close">关闭</span></div></div></td></tr></table></div>');
        $("body").append(floatdiv);
        $("#f-box_overlay").fadeIn(500);
        $("#f-box").fadeIn(500);
        $(".ui-dialog-close").click(function () {
            $.closeFloatDiv();
        });
        $(".cancelbt").click(function () {
            $.closeFloatDiv();
        });
        $("#surebt")
            .click(
            function (e) {
                if (!oxdefaults.suredo(e)) {
                    $(".ui-dialog-todo").remove();
                    $("#ui-cnt").removeClass('succ error alert')
                        .addClass('loading');
                    if (oxdefaults.is_post == 0) {
                        $
                            .ajax({
                                url: idval,
                                async: true,
                                type: 'get',
                                cache: true,
                                dataType: 'json',
                                success: function (data, textStatus) {
                                    if (data.msg != null
                                        && data.msg != undefined) {
                                        $("#xtip").html(
                                            data.msg);
                                    } else {
                                        $("#xtip")
                                            .html(
                                                oxdefaults.complete_text);
                                    }
                                    oxdefaults.succdo(data);
                                    if (data.want_close != null
                                        && data.want_close != undefined) {
                                        $.hidediv(data);
                                    } else if (oxdefaults.auto_close == 1) {
                                        $.hidediv(data);
                                    }
                                },
                                error: function (e) {
                                    $("#xtip").html(
                                        '系统繁忙,请稍后再试...');
                                }
                            });
                    } else {
                        $("#" + oxdefaults.formid).qiresub({
                            curobj: $("#surebt"),
                            txt: '数据提交中,请稍后...',
                            onsucc: function (result) {
                                oxdefaults.succdo(result);
                                $.hidediv(result);
                            }
                        }).post({
                            url: oxdefaults.url
                        });
                    }
                } else {
                    oxdefaults.succdo(e);
                }
            });
    } else {
        var floatdiv = $('<div class="f-box_overlayBG" id="f-box_overlay"></div><div id="f-box" class="f-box"><iframe frameborder="0" class="ui-iframe"></iframe><div class="ui-dialog"><div class="ui-dialog-cnt" id="ui-dialog-cnt"><div class="ui-dialog-box"<div class="ui-cnt" id="ui-cnt">'
            + oxdefaults.txt + '</div></div></div></div></div>');
        $("body").append(floatdiv);
        $("#f-box_overlay").fadeIn(500);
        $("#f-box").fadeIn(500);
    }
    $('#f-box_overlay').bind('click', function (e) {
        $.closeFloatDiv(e);
        if (pp != null) {
            clearTimeout(pp);
        }
    });
};
jQuery.closeFloatDiv = function (e) {
    $("#f-box_overlay").remove();
    $("#f-box").remove();
};
jQuery.hidediv = function (e) {
    var oxdefaults = {
        intval_time: 10000
    };
    e = e || {};
    $.extend(oxdefaults, e);
    if (e.msg != null && e.msg != undefined) {
        $("#ui-cnt").html(e.msg);
    }
    if (parseInt(e.rcode) == 1) {
        $("#ui-cnt").removeClass('loading error alert').addClass('succ');
    } else if (parseInt(e.rcode) < 1) {
        $("#ui-cnt").removeClass('loading alert succ').addClass('error');
    }
    pp = setTimeout("$.closeFloatDiv()", oxdefaults.intval_time);
};
(function ($) {
    $.fn.kblsubmit = function (options) {
        var defaults = {
            txt: '数据提交中,请稍后...',
            redirect_url: window.location.href,
            dataType: 'json',
            onsucc: function (e) {
            },
            onerr: function () {
                $.hidediv({
                    msg: '系统繁忙'
                });
            },
            oncomplete: function () {
            },
            intval_time: 1000
        };
        options.curobj.attr('disabled', true);
        var ox = options.curobj.offset();
        var options = $.extend(defaults, options);
        $.showFloatDiv({
            offset: ox,
            txt: defaults.txt
        });
        var obj = $(this);
        var id = obj.attr('id');
        return {
            post: function (e) {
                $("#ui-cnt").removeClass('succ error alert')
                    .addClass('loading');
                $.post(
                    e.url,
                    obj.serializeArray(),
                    function (result) {
                        options.curobj.attr('disabled', false);
                        defaults.onsucc(result);
                        if (result.closediv != undefined
                            || result.closediv != null) {
                            $.closeFloatDiv();
                        }
                        if (result.wantredir != undefined
                            || result.wantredir != null) {
                            if (result.redir != undefined
                                || result.redir != null) {
                                setTimeout("$.refresh('" + result.redir
                                    + "')", options.intval_time);
                            } else {
                                setTimeout("$.refresh('" + options.redirect_url
                                    + "')", options.intval_time);
                            }
                        }
                    }, options.dataType).error(function () {
                        options.curobj.attr('disabled', false);
                        defaults.onerr();
                    }).complete(function () {
                        defaults.oncomplete();
                        options.curobj.attr('disabled', false);
                    });
            },
            implodeval: function (e) {
                val = $("#" + id + " :input").map(
                    function () {
                        if ($(this).attr('name') != ''
                            && $(this).attr('name') != undefined) {
                            return $(this).attr('name') + "-"
                                + $(this).val();
                        }
                    }).get().join("-");
                return val;
            },
            get: function (e) {
                $(".ui-dialog-todo").remove();
                $("#ui-cnt").removeClass('succ error alert')
                    .addClass('loading');
                var val = this.implodeval();
                $.get(
                    e.url + "-" + val,
                    '',
                    function (result) {
                        options.curobj.attr('disabled', false);
                        defaults.onsucc(result);
                        if (result.wantredir != undefined
                            || result.wantredir != null) {
                            if (result.redir != undefined
                                || result.redir != null) {
                                setTimeout("$.refresh(" + result.redir
                                    + ")", options.intval_time);
                            } else {
                                setTimeout("$.refresh(" + options.redirect_url
                                    + ")", options.intval_time);
                            }
                        }
                    }, options.dataType).error(function () {
                        options.curobj.attr('disabled', false);
                        defaults.onerr();
                    }).complete(function () {
                        defaults.oncomplete();
                        options.curobj.attr('disabled', false);
                    });
            }
        };
    };
    $.fn.ajaxdel = function (options) {
        var defaults = {
            txt: '数据提交中,请稍后...',
            redirect_url: window.location.href,
            dataType: 'json',
            onsucc: function (e) {
            },
            onerr: function () {
            },
            oncomplete: function () {
            },
            intval_time: 3000
        };
        $(".ui-dialog-todo").remove();
        $("#ui-cnt").removeClass('succ error alert').addClass('loading');
        var options = $.extend(defaults, options);
        var ajurl = $(this).attr('url');
        $.ajax({
            url: ajurl,
            success: function (data) {
                options.onsucc(data);
            },
            error: function () {
                options.onerr();
            },
            complete: function () {
                options.oncomplete();
            },
            dataType: 'json'
        });
    };
})(jQuery);

/*
 * 获取 URL 参数
 */
(function ($) {
    $.getUrlParam = function (name) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
        var r = window.location.search.substr(1).match(reg);
        if (r != null) return unescape(r[2]);
        return null;
    }
})(jQuery);

var spm = null;
$(function () {

    spm = $.getUrlParam('spm');
    if (spm != null) {
        $.cookie('spm', spm, {expires: 1, path: '/' });
    }

    apply_ajax();
});


function getEvent() {
    if (document.all)return window.event;
    func = getEvent.caller;
    while (func != null) {
        var arg0 = func.arguments[0];
        if (arg0) {
            if ((arg0.constructor == Event || arg0.constructor == MouseEvent) || (typeof(arg0) == "object" && arg0.preventDefault && arg0.stopPropagation)) {
                return arg0;
            }
        }
        func = func.caller;
    }
    return null;
}

function create_editor(id, form_id) {
    $.getScript('/js/editor/kindeditor-min.js', function () {

        editor = KindEditor.create('#' + id, {
            basePath: "/js/editor/",
            uploadJson: '/editor/upload',
            afterBlur: function () {
                this.sync();
            },
            afterCreate: function () {
                var self = this;

                if (typeof form_id != 'undefined') {

                    KindEditor.ctrl(self.edit.doc, 13, function () {
                        self.sync();
                        $('#' + form_id).submit();
                        return false;
                    });
                }
            },
            //			items : ['source', '|', 'fullscreen', 'undo', 'redo', 'print', 'cut', 'copy', 'paste',
            //			         'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
            //			         'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
            //			         'superscript', '|', 'selectall', 'clearhtml', 'quickformat', '-',
            //			         'title', 'fontname', 'fontsize', '|', 'textcolor', 'bgcolor', 'bold',
            //			         'italic', 'underline', 'strikethrough', 'removeformat', '|', 'image',
            //			         'flash', 'media', 'advtable', 'hr', 'emoticons', 'link', 'unlink' ],
            htmlTags: {
                font: [ 'color', 'size', 'face', '.background-color' ],
                span: [ '.color', '.background-color', '.font-size',
                    '.font-family', '.background', '.font-weight',
                    '.font-style', '.text-decoration', '.vertical-align',
                    '.line-height' ],
                table: [ 'border', 'cellspacing', 'cellpadding', 'width',
                    'height', 'align', 'bordercolor', '.padding',
                    '.margin', '.border', 'bgcolor', '.text-align',
                    '.color', '.background-color', '.font-size',
                    '.font-family', '.font-weight', '.font-style',
                    '.text-decoration', '.background', '.width', '.height',
                    '.border-collapse' ],
                'td,th': [ 'align', 'valign', 'width', 'height', 'colspan',
                    'rowspan', 'bgcolor', '.text-align', '.color',
                    '.background-color', '.font-size', '.font-family',
                    '.font-weight', '.font-style', '.text-decoration',
                    '.vertical-align', '.background', '.border' ],
                a: [ 'href', 'target', 'name' ],
                embed: [ 'src', 'width', 'height', 'type', 'loop',
                    'autostart', 'quality', '.width', '.height', 'align',
                    'allowscriptaccess' ],
                img: [ 'src', 'width', 'height', 'border', 'alt', 'title',
                    'align', '.width', '.height', '.border' ],
                'p,ol,ul,li,blockquote,h1,h2,h3,h4,h5,h6': [ 'align',
                    '.text-align', '.color', '.background-color',
                    '.font-size', '.font-family', '.background',
                    '.font-weight', '.font-style', '.text-decoration',
                    '.vertical-align', '.text-indent', '.margin-left' ],
                pre: [ 'class' ],
                hr: [ 'class', '.page-break-after' ],
                'br,tbody,tr,strong,b,sub,sup,em,i,u,strike,s,del': []
            }
        });
        KindEditor.plugin['remoteimg'] = {
            click: function (id) {
                $.post(KindEditor.scriptPath + 'php/remoteimg.php', {
                    str: KindEditor.html(id)
                }, function (data) {
                    alert('获取图片完成!');
                    KindEditor.html(id, '');
                    KindEditor.util.insertHtml(id, data);
                }, 'JSON');
            }
        };
    });
}

function create_simple_editor(id, form_id) {

    if ($('#' + id).html() == '') {
        $('#' + id).html("<p>\n</p>");
    }

    $.getScript('/js/editor/kindeditor-min.js', function () {

        editor = KindEditor.create('#' + id, {
            basePath: "/js/editor/",
            uploadJson: '/admin/documents/editorUpload',
            afterBlur: function () {
                this.sync();
            },
            afterCreate: function () {
                var self = this;

                if (typeof form_id != 'undefined') {

                    KindEditor.ctrl(self.edit.doc, 13, function () {
                        self.sync();
                        $('#' + form_id).submit();
                        return false;
                    });
                }

            },
            items: ['source', '|', 'fullscreen', 'cut', 'copy', 'paste',
                '|', 'justifyleft', 'justifycenter', 'justifyright', 'title', 'fontname', 'fontsize',
                '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline', 'strikethrough',
                '|', 'image', 'emoticons', 'link', 'unlink' ],
            htmlTags: {
                font: [ 'color', 'size', 'face', '.background-color' ],
                span: [ '.color', '.background-color', '.font-size',
                    '.font-family', '.background', '.font-weight',
                    '.font-style', '.text-decoration', '.vertical-align',
                    '.line-height' ],
                table: [ 'border', 'cellspacing', 'cellpadding', 'width',
                    'height', 'align', 'bordercolor', '.padding',
                    '.margin', '.border', 'bgcolor', '.text-align',
                    '.color', '.background-color', '.font-size',
                    '.font-family', '.font-weight', '.font-style',
                    '.text-decoration', '.background', '.width', '.height',
                    '.border-collapse' ],
                'td,th': [ 'align', 'valign', 'width', 'height', 'colspan',
                    'rowspan', 'bgcolor', '.text-align', '.color',
                    '.background-color', '.font-size', '.font-family',
                    '.font-weight', '.font-style', '.text-decoration',
                    '.vertical-align', '.background', '.border' ],
                a: [ 'href', 'target', 'name' ],
                embed: [ 'src', 'width', 'height', 'type', 'loop',
                    'autostart', 'quality', '.width', '.height', 'align',
                    'allowscriptaccess' ],
                img: [ 'src', 'width', 'height', 'border', 'alt', 'title',
                    'align', '.width', '.height', '.border' ],
                'p,ol,ul,li,blockquote,h1,h2,h3,h4,h5,h6': [ 'align',
                    '.text-align', '.color', '.background-color',
                    '.font-size', '.font-family', '.background',
                    '.font-weight', '.font-style', '.text-decoration',
                    '.vertical-align', '.text-indent', '.margin-left' ],
                pre: [ 'class' ],
                hr: [ 'class', '.page-break-after' ],
                'br,tbody,tr,strong,b,sub,sup,em,i,u,strike,s,del': []
            }
        });
        KindEditor.plugin['remoteimg'] = {
            click: function (id) {
                $.post(KindEditor.scriptPath + 'php/remoteimg.php', {
                    str: KindEditor.html(id)
                }, function (data) {
                    alert('获取图片完成!');
                    KindEditor.html(id, '');
                    KindEditor.util.insertHtml(id, data);
                }, 'JSON');
            }
        };
    });
}

function create_date_picker() {
    if (typeof WdatePicker != 'function') {
        $.getScript('/js/calendar/WdatePicker.js', function () {
            $('.date-picker').focus(function () {
                var format = "yyyy-MM-dd";
                if ($(this).hasClass('date-time')) {
                    format = 'yyyy-MM-dd HH:mm:ss';
                }
                WdatePicker({ dateFmt: format, onpicked: function () {
                } });
            });
        });
    } else {
        $('.date-picker').focus(function () {
            var format = "yyyy-MM-dd";
            if ($(this).hasClass('date-time')) {
                format = 'yyyy-MM-dd HH:mm:ss';
            }
            WdatePicker({ dateFmt: format, onpicked: function () {
            } });
        });
    }
}

function redirect(_redirect_url) {
    location = _redirect_url;
    return false;
}