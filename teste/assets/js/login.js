$(document).ready(function () {
    $('#btnLogin').click(function (e) {
        let user = $('#iptLogin').val();
        let pass = $('#iptPassword').val();
        let url  = JS_PATH_WEB + '/auth/login';
        
        icoSpinner('#message');
        
        $.post(
            url,
            {
                user: user,
                pass: pass
            },
            function (data) {
                let msg,
                    obj,
                    sts;
                
                if (isJson(data)) {
                    obj = JSON.parse(data);
                    
                    msg = obj.message;
                    sts = obj.status;
                } else {
                    msg = data;
                    sts = 'error';
                }
                
                if (sts === 'success') {
                    window.location.href = JS_PATH_WEB + '/index';
                } else {
                    alertMessage('#message', 'danger', 15000, msg);
                }
            }
        );
        
        e.preventDefault();
    });
    
    $('#btnSend').click(function (e) {
        let email = $('#iptEmail').val();
        let url   = JS_PATH_WEB + '/password/recover/send';
        
        icoSpinner('#message');
        
        $.post(
            url,
            {
                email: email
            },
            function (data) {
                alertMessage('#message', 'danger', 15000, data);
            }
        );
        
        e.preventDefault();
    });
    
    $('#btnRecord').click(function (e) {
        let token            = $('#ipt_token').val();
        let password         = $('#ipt_password').val();
        let password_confirm = $('#ipt_password_confirm').val();
        let url              = JS_PATH_WEB + '/password/recover/update';
        
        icoSpinner('#message');
        
        $.post(
            url,
            {
                password        : password,
                password_confirm: password_confirm,
                token           : token
            },
            function (data) {
                alertMessage('#message', 'danger', 15000, data.msg);
            },
            'JSon'
        );
        
        e.preventDefault();
    });
});