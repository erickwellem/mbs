
    <h2>Forgot your password?</h2>

    <form id="frm_password-forgot" method="post" action="">
        <p>Please submit your email or username:</p>
        <input type="text" name="frm_email" id="frm_email" placeholder="Email"> or <input type="text" name="frm_username" id="frm_username" placeholder="Username">
        <div id="status_message"></div>
        <p><button type="button" class="btn btn-inverse">Submit</button>
    </form>

    <script>
        $(document).ready(function() {

            $('#status_message').hide();

            $('#frm_email').click(function() {            
                $('#status_message').hide();                
            });

            $('#frm_username').click(function() {            
                $('#status_message').hide();                
            });            

            $('.btn').click(function() {
                
                var str_loader = '<img src="<?php echo $STR_URL; ?>img/loading.gif" /> Checking. Please wait...';
                $('#status_message').show(300);
                $('#status_message').html(str_loader);

                if (checkPasswordForgotForm())
                {                       

                    var str_username = $('#frm_username').val();
                    var str_email = $('#frm_email').val();                    
                    var dataString = "frm_username=" + str_username + "&frm_email=" + str_email;

                    var request = $.ajax({                              
                        url: "ajax/password_forgot_proc.php",
                        type: "post", 
                        data: dataString,
                        success: function(msg) {
                            $('#status_message').show(300);
                            $('#status_message').addClass('alert-success well');
                            $('#status_message').html(msg);

                            $('#frm_username').val('');
                            $('#frm_email').val('');
                            
                        }

                    });
                }
            });

            function checkPasswordForgotForm()
            {
                
                var str_email = $('#frm_email').val();
                var str_username = $('#frm_username').val();
                var regex = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

                if (str_email == '' && str_username == '')
                {
                    
                    $('#status_message').addClass('alert-error well');
                    $('#status_message').html('Please fill with registered email, or if you prefer to use your username, please fill its input!');                    
                    $('#frm_email').focus();
                    return false;
                }

                if (str_email !== '' && regex.test(str_email) == false)
                {
                 
                    $('#status_message').addClass('alert-error well');
                    $('#status_message').html('Pleae fill with a valid email!');                    
                    $('#frm_email').focus();
                    return false;
                }                

                if (str_email == '' && str_username !== '' && str_username.length < 5)
                {
                    $('#status_message').addClass('alert-error well');
                    $('#status_message').html('Username must contain minimum 5 characters!');                    
                    $('#frm_username').focus();
                    return false;
                }                
                
                return true;

            }
        });
    </script>
            
        	