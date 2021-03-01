<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <title>Sign In</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1" name="viewport" />
        <!-- Bootstrap 3.3.7 -->
        <link type="text/css" rel="stylesheet" href="<?php echo $this->config->item('css'); ?>bootstrap.min.css" />
        <!-- Font Awesome -->
        <link rel="stylesheet" type="text/css" href="<?php echo $this->config->item('css'); ?>font-awesome.min.css" />
        <!-- Theme style -->
        <link type="text/css" rel="stylesheet" href="<?php echo $this->config->item('css'); ?>AdminLTE.css" />
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <link type="text/css" rel="stylesheet" href="<?php echo $this->config->item('css'); ?>style.css" />
        <style type="text/css">
            .box1{
                border-radius: 0px;
                border-top: none;
                margin-bottom: 0;
                width: none;
                box-shadow: none;
            }
        </style>
    </head>
    
    <body class="hold-transition login-page">
        <div class="box login-box box1">
            <div class="login-logo" style="margin-bottom: 0px;">
                <a href="javascript:void(0);"><?php echo SITE_NAME; ?></a>
            </div>
            <!-- /.login-logo -->
            <div class="box-body login-box-body" style="padding-top: 6px;">
                <p class="login-box-msg">Sign in to start your session</p>
                <form id="loginform" class="login-validate" action="#" method="post">
                    <div class="form-group has-feedback">
                        <input type="text" name="username" id="username" value="<?php echo $this->input->cookie('username',true); ?>" class="form-control" placeholder="User Name" required="true" autofocus="true" />
                        <span class="fa fa-user-circle form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input type="password" name="password" id="password" class="form-control" placeholder="Password" value="<?php echo $this->input->cookie('password',true); ?>" required="true" />
                        <span class="fa fa-lock form-control-feedback"></span>
                    </div>
                    <div class="row">
                        <!-- /.col -->
                        <div class="col-xs-12">
                            <button type="submit" name="submit" value="submit" class="btn btn-primary btn-block btn-flat"><i class="fa fa-sign-in"></i> Sign In</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
                <div id="lgnmessage"></div>
            </div>
            <!-- /.login-box-body -->
            <div class="overlay" style="display: block;"><i class="fa fa-spinner fa-spin1"></i></div>
        </div>
        <!-- /.login-box -->
    
        <!-- jQuery 3 -->
        <script type="text/javascript" src="<?php echo $this->config->item('js'); ?>jquery.min.js"></script>
        <!-- Bootstrap 3.3.7 -->
        <script type="text/javascript" src="<?php echo $this->config->item('js'); ?>bootstrap.min.js"></script>
        <script type="text/javascript" src="<?php echo $this->config->item('plugins'); ?>ace/js/chosen.jquery.min.js"></script>
        <!-- AdminLTE App -->
        <script type="text/javascript" src="<?php echo $this->config->item('js'); ?>adminlte.min.js"></script>
        <script type="text/javascript" src="<?php echo $this->config->item('js'); ?>script.js"></script>
        <script type="text/javascript" src="<?php echo $this->config->item('js'); ?>validator.js"></script>
		<script type="text/javascript">
			$(document).ready(function(){
			     $.validator.setDefaults({ ignore: ":hidden:not(select)" }) //for all select
                 $.validator.setDefaults({ ignore: ":hidden:not(.chkbox)" }) //for all select
                 
				$(".login-validate").validate({
					// validation rules for registration form
					errorClass: "text-red",
					validClass: "text-green",
					errorElement: 'div',
					errorPlacement: function(error, element) {
						if(element.parent('.input-group').length) {
							error.insertAfter(element.parent());
						}
						else if (element.hasClass('select2')) {     
							error.insertAfter(element.next('span'));  // select2
						}
                        else if (element.hasClass('chkbox')) {     
							error.insertAfter(element.next('label'));  // chkbox
						}
						else if (element.hasClass('chosen-select')) {     
							//error.insertAfter(element.next('span'));  // chosen-select
							//error.insertAfter("#shop_chosen");
							//element.next("div.chzn-container").append(error);
							error.insertAfter(element.siblings('[class*="chosen-container"]:eq(0)'));
						} else {
							error.insertAfter(element);
						}
					},
					onError : function(){
						$('.input-group.error-class').find('.help-block.form-error').each(function() {
							$(this).closest('.form-group').addClass('error-class').append($(this));
						});
					}
				});
                
                $(".login-validate").submit(function(e){
                    $("#lgnmessage").html('');
                    if($(".login-validate").validate().errorList.length==0){
                        $('.overlay').toggle();
                        var url = '<?php echo base_url(); ?>SignIn/process'
                        $.ajax({
                            type: "POST",
                            url: url,
                            data: new FormData(this), // serializes the form's elements.
                            contentType: false,
                            cache: false,
                            processData:false,
                            success: function(data){
                                var result = JSON.parse(data);
                                if(result['status']==1){
                                    //$('#loginform')[0].reset();
                                    $("#lgnmessage").attr("class","text-green");
                                    $("#lgnmessage").html(result['message']);
                                    setTimeout(function(){
                                        window.location.replace("<?php echo base_url(); ?>Dashboard");
                                    },500);
                                }
                                else{
                                    $("#lgnmessage").attr("class","text-red");
                                    $("#lgnmessage").html(result['message']);
                                }
                                $('.overlay').toggle();                       
                            },
                            error: function (request, status, error) {
                                $("#lgnmessage").attr("class","text-red");
                                $("#lgnmessage").html(request.responseText);
                                $('.overlay').toggle();
                            }
                        });
                    }  
                    e.preventDefault(); // avoid to execute the actual submit of the form.
                });
                
			});
		</script>
    </body>

</html>