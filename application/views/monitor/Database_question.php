<div style="background-color:white;margin-top:12px;padding:10px;padding-bottom:20px; margin-bottom:30px" id="oracle_app_monitor_database_question">
    <div class="page-title">
        <div class="x_title">
            <h2>Answer the question before access to database.</h2>
            <div class="clearfix"></div>
        </div>

    </div>
    <div class="clearfix"></div>
    <form id="answer_form">
        <p style="padding:7px"><?php echo $question; ?> = </p><input type="text" class="form-control" name="answer" style="width:50px"><button class="btn btn-success" style="margin-left:10px" type="submit">Submit</button>     
        <div class="clearfix"></div>
        <span class="message"></span>   
    </form>
    <br>
</div>
<script>
(function(){
    var thiscontent = $('#oracle_app_monitor_database_question');
    var thisform = thiscontent.find('#answer_form');
    var message = thisform.find('.message');

    thisform.submit(function(){
        var answer = $(this).find('input[name=answer]').val();

        if (answer != "") {
            // do ajax
            var url = oracle_app.baseurl + 'api/monitor/database_check_answer';

            var param = "?answer=" + answer;


            $.ajax({
                type: "GET",
                url: url + param,
                dataType: 'json',
                success: function(data) {
                    if (data.status == 'success') {
                        // data
                        oracle_app.load_module_content('monitor/database');
                    } else {
                        swal("You enter wrong answer!", "", "error");
                    }
                }
            }); 
        }

        return false;
    });
})();
</script>
