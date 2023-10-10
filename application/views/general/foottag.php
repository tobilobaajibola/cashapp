footer  -->
<!-- jquery slim -->
    
<!--===============================================================================================-->  
    <script src="<?php echo base_url();?>assets/vendor/jquery/jquery-3.6.0.min.js"></script>
<!--===============================================================================================-->
    <script src="<?php echo base_url();?>assets/vendor/bootstrap/js/popper.js"></script>
    <script src="<?php echo base_url();?>assets/vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
    <script src="<?php echo base_url();?>assets/vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
    <script src="<?php echo base_url();?>assets/vendor/tilt/tilt.jquery.min.js"></script>
    <script >
        $('.js-tilt').tilt({
            scale: 1.1
        })
    </script>
<!--===============================================================================================-->
  
    <script src="<?php echo base_url();?>assets/js/main.js"></script>
<?php
    $nonceValue = 'nonce_value';
?>

     <script>
            $(document).ready(function() {
                
                // Submit form
                $("#login_form").submit(function (event) {
                    event.preventDefault();

                    var login_type = $("#statement_login").val();
        var login_type = $("#login_type").val();
        var username = $("#username").val();
        var password = $("#password").val();

                    var nonceValue = '<?php echo $nonceValue; ?>';
                    
                    // Encrypt form data
                    let encryption = new Encryption();
                    var passwordEncrypted = encryption.encrypt(password, nonceValue);

                    // Submit form using Ajax
                    $.ajax({
                        url: 'result.php',
                        method: 'POST',
                        data: {
                                username: username,
                                password: passwordEncrypted,
                                login_type: login_type,
                                statement_login : statement_login
                        },
                        success:function(res) {
                            console.log(res);
                        }
                    });

                });
            });
        </script>
<!-- let encryption = new Encryption();
var nameEncrypted = encryption.encrypt(name, nonceValue);
 -->
<!-- custom js -->
<!-- <script src="<?php echo base_url();?>assets/js/custom.js"></script> -->

<!-- active_chart js -->
<!-- <?php if (basename($_SERVER['PHP_SELF']) == 'index.php') {?> -->
<!-- <script src="<?php echo base_url();?>assets/js/active_chart.js"></script> -->
<!-- <?php }?>

