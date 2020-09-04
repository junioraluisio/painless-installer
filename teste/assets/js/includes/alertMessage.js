/**
 *
 * @param obj
 * @param classAlert
 * @param time
 * @param msg
 * @param msgEnd
 */
function alertMessage(obj, classAlert, time, msg, msgEnd='')
{
    $(obj).html(msg)
          .addClass("text-" + classAlert)
          .animateCss("bounceIn");
    
    setTimeout(
        function () {
            $(obj)
                .animateCss("bounceOut", function () {
                    $(obj).html(msgEnd);
                });
        },
        time
    );
}