<?php if(!defined('BASEPATH')){ require_once("index.html");exit; } ?>

<!-- Main content -->
<section class="content">
    <?php require_once(__DIR__.'/alerts.php'); ?>
    <?php
        if($action=='add'||$action=='edit'){
            ?>
            <form action="<?php echo base_url(); ?>Banner/save" class="form-validate1" method="post" enctype="multipart/form-data">
                <!-- Default box -->
                <div class="box box-default box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo ucfirst($action); ?> Banner</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                            <span class="dropdown">
                            	<button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>
                            	<ul class="dropdown-menu dropdown-menu-right">
                            		<li><a href="<?php echo base_url(); ?>Banner" class="text-olive"><i class="fa fa-eye"></i> View Banner</a></li>
                            	</ul>
                            </span>
                        </div>
                    </div>
                    <div class="box-body">
                        <?php
                            if($slug!=''){
                                ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <?php
                                                if(file_exists($this->config->item('banners_dir').get_thumb($image,'135x100'))){
                                                    ?>
                                                    <div class="image_container">
                                                        <a class="fancybox" rel="gallery0" href="<?php echo $this->config->item('banners_url').$image; ?>">
                                                            <img src="<?php echo $this->config->item('banners_url').get_thumb($image,'135x100'); ?>" class="img-thumbnail" />
                                                        </a>
                                                        <a style="display: none;" href="<?php echo base_url(); ?>Banner/DeleteImage/<?php echo $slug; ?>/<?php echo $image; ?>" onclick="return false;" class="image_delete_link btn-delete-image" title="Delete Image"><i class="fa fa-remove"></i></a>
                                                    </div>
                                                    <?php
                                                }
                                                else{
                                                    ?>
                                                    <a class="fancybox" rel="gallery0" href="<?php echo $this->config->item('banners_url').NOIMAGEFOUND; ?>">
                                                        <img style="width: 150px; height: 150px;" src="<?php echo $this->config->item('banners_url').NOIMAGEFOUND; ?>" class="img-thumbnail" />
                                                    </a>
                                                    <?php
                                                }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                        ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Type <span class="text-red star">*</span></label>
                                    <select name="type" id="type" class="form-control chosen-select">
                                        <option value="">Select Type</option>
                                        <?php
                                            foreach($types as $key=>$val){
                                                ?>
                                                <option <?php echo $val->id==$type?'selected="selected"':''; ?> value="<?php echo $val->id; ?>"><?php echo $val->name; ?></option>
                                                <?php
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Name <span class="text-red star">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control" value="<?php echo $name; ?>" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Image <span id="size"></span><span class="text-red star">*</span></label>
                                    <input type="file" name="image" id="image" accept="image/*" class="form-control" value="<?php echo $image; ?>" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="overlay"><i class="fa fa-spinner fa-spin"></i></div>
                    <div class="box-footer">
                        <input type="hidden" name="slug" id="slug" value="<?php echo $slug; ?>" />
                        <button type="submit" class="btn btn-default btn-sm text-olive"><i class="fa fa-save"></i> Save</button>
                    </div>
                </div>
                <!-- /.box -->
            </form>
            <script type="text/javascript">
                $(document).ready(function(){
                    $("#type").change(function(){
                        var id = $(this).val();
                        var size = '';
                        if(id=='1'){
                            size = '(Convered in 800x400)';
                        }
                        else if(id=='2'){
                            size = '(Convered in 800x210)';
                        }
                        $("#size").html(size);
                    });
                    <?php
                        if($type!=''){
                            ?>
                            $("#type").trigger("change");
                            <?php
                        }
                    ?>
                    $(document).delegate('.btn-delete-image', 'click', function() { 
                        var element = $(this);
                        bootbox.confirm({
                            backdrop: true,
                            title: "Are you sure?",
                            message: "Do you want to delete image now? This cannot be undone.",
                            buttons: {
                                cancel: {
                                    label: '<i class="fa fa-times"></i> Cancel',
                                    className: 'btn-default btn-sm'
                                },
                                confirm: {
                                    label: '<i class="fa fa-check"></i> Confirm',
                                    className: 'btn-warning btn-sm'
                                }
                            },
                            callback: function (result) {
                                if(result===true){
                                    window.location.href = $(element).attr('href');
                                }
                                else{
                                    
                                }
                            }
                        });
                    });
                });
            </script>
            <?php
        }
        else if($action=='view'){
            ?>
            <!-- Default box -->
            <div class="box box-widget box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">Banner</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                        <span class="dropdown">
                        	<button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>
                        	<ul class="dropdown-menu dropdown-menu-right">
                        		<li><a href="<?php echo current_url(); ?>/add"><i class="fa fa-plus"></i> Add Banner</a></li>
                        		<li><a href="javascript:void(0);" id="clear_state"><i class="fa fa-refresh"></i> Reset Filter</a></li>
                        	</ul>
                        </span>
                    </div>
                </div>
                <div class="table-responsive box-body">
                    <table id="data-table" class="table table-hover table-striped table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">#NO</th>
                                <th>NAME</th>
                                <th>TYPE</th>
                                <th class="text-center">STATUS</th>
                                <th class="text-center">ACTION</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
            
            <!-- DataTables -->
            <link type="text/css" rel="stylesheet" href="<?php echo $this->config->item('plugins'); ?>datatables/css/dataTables.bootstrap.min.css" />
            <script type="text/javascript" src="<?php echo $this->config->item('plugins'); ?>datatables/js/jquery.dataTables.min.js"></script>
            <script type="text/javascript" src="<?php echo $this->config->item('plugins'); ?>datatables/js/dataTables.bootstrap.min.js"></script>
            
            <script type="text/javascript">
                $(document).ready(function() {
                    
                    var table = $("#data-table").on( 'init.dt', function () {
                        var jsn = JSON.parse('<?php echo json_encode($filters); ?>');
                        $("#data-table").parents("div.row:first").before('<div class="row"><div id="filtercontent"></div></div>');
                        
                        $.each( jsn, function(i, item){
                            var filter = table.state().columns[i].search.search!=undefined?table.state().columns[i].search.search.replace(/^\^+|\$+$/gm,''):'';
                            var select = $('<select id="filer_'+i+'" class="form-control chosen-select"><option value="">All</option></select>')
            					.insertBefore('#filtercontent')
            					.on( 'change', function () {
            						var val = $(this).val();
            						
            						table.column( i )
            							.search( val ? '^'+$(this).val()+'$' : val, true, false )
            							.draw();
            					} );
                            
                            $.each( item.data, function(j, val){
                                var sel = filter!=''&&filter==j?'selected="selected"':'';
                                select.append( '<option '+sel+' value="'+ j +'">'+val+'</option>' );
                            });
                            col = '2';
                            if(item.col!=undefined){
                                col = item.col;
                            }
                            $('#filer_'+i).wrapAll('<div class="col-sm-'+col+' form-group"></div>');
            				$('<label>'+item.label+'&nbsp;</label>').insertBefore('#filer_'+i);
                            $(select).chosen();
            			});
                        //$('#data-table_wrapper').removeClass('form-inline');
                       
                    }).DataTable({
                        "processing": true,
                        "serverSide": true,
                        "stateSave": true,
                        "bStateSave": true,
                        "pageLength": <?php echo RECORD_PER_PAGE; ?>,
                        "oLanguage": {
                            "sProcessing": '<i class="fa fa-spinner fa-spin"></i> Please wait...',
                        },
                        "processing" : true,
                        "columnDefs": [
                           { "orderable": false, "targets": [-1] },
                           { "searchable" : false, "targets" : [0,-1] }
                        ],
                        "order": [[ 1, "asc" ]],
                        "ajax": {
                            url: '<?php echo current_url(); ?>/select',
                            type: 'POST',
                            "data": {
                                "action": "view"
                            }
                        },
                        "createdRow": function ( row, data, index ) {
                            $('td', row).eq(0).addClass('text-center');
                            $('td', row).eq(3).addClass('text-center');
                            $('td', row).eq(4).addClass('text-center');
                        }
            		});
                    
                    $(".dataTables_length select").chosen();
                    
                    $('#clear_state').click(function(){
                        table.state.clear();
                        window.location.reload();
                    });
                    
                    $(document).delegate('.btn-delete', 'click', function() { 
                        var element = $(this);
                        bootbox.confirm({
                            backdrop: true,
                            title: "Are you sure?",
                            message: "Do you want to delete record now? This cannot be undone.",
                            buttons: {
                                cancel: {
                                    label: '<i class="fa fa-times"></i> Cancel',
                                    className: 'btn-default btn-sm'
                                },
                                confirm: {
                                    label: '<i class="fa fa-check"></i> Confirm',
                                    className: 'btn-warning btn-sm'
                                }
                            },
                            callback: function (result) {
                                if(result===true){
                                    var slug = $(element).attr('slug');
                                    var data = 'slug='+slug;
                                    var parent = $(element).parent().parent();
                                    $.ajax({
                                        type: "POST",
                                        url: '<?php echo current_url(); ?>/delete',
                                        data: data,
                                        cache: false,
                                        success: function(data){
                                            data = JSON.parse(data);
                                            if(data['status']==1){
                                                parent.fadeOut('slow', function() {
                                                    table.row($(this)).remove().draw(false);
                                                    showMessage(data['message'],'success');
                                                });
                                            }
                                            else{
                                                showMessage(data['message'],'danger');
                                            }
                                        },
                                        error: function (request, status, error) {
                                            showMessage(request.responseText,'warning');
                                        }
                                    });
                                }
                            }
                        });
                    });
                    $(document).delegate('.btn-status', 'click', function() { 
                        var slug = $(this).attr('slug');
                        var status = $(this).val();
                        var data = 'slug='+ slug +"&status="+status;
                        var button = $(this);
                        $.ajax({
                            type: "POST",
                            url: '<?php echo current_url(); ?>/status',
                            data: data,
                            cache: false,
                            success: function(data){
                                data = JSON.parse(data);
                                if(data['status']==1){
                                    $(button).val(Math.abs(status-1));
                                    table.draw(false);
                                    showMessage(data['message'],'success');
                                }
                                else{
                                    button.prop("checked", !button.prop("checked"));
                                    showMessage(data['message'],'danger');
                                }
                            },
                            error: function (request, status, error) {
                                button.prop("checked", !button.prop("checked"));
                                showMessage(request.responseText,'warning');
                            }
                        });
                        
                    });
                });
                
            </script>
            <?php
        }
    ?>
    
</section>
<!-- /.content -->
<!-- Add mousewheel plugin (this is optional) -->
<script type="text/javascript" src="<?php echo $this->config->item('plugins'); ?>fancybox/jquery.mousewheel-3.0.6.pack.js"></script>

<!-- Add fancyBox -->
<link rel="stylesheet" href="<?php echo $this->config->item('plugins'); ?>fancybox/jquery.fancybox.css?v=2.1.7" type="text/css" media="screen" />
<script type="text/javascript" src="<?php echo $this->config->item('plugins'); ?>fancybox/jquery.fancybox.pack.js?v=2.1.7"></script>

<!-- Optionally add helpers - button, thumbnail and/or media -->
<link rel="stylesheet" href="<?php echo $this->config->item('plugins'); ?>fancybox/jquery.fancybox-buttons.css?v=1.0.5" type="text/css" media="screen" />
<script type="text/javascript" src="<?php echo $this->config->item('plugins'); ?>fancybox/jquery.fancybox-buttons.js?v=1.0.5"></script>
<script type="text/javascript" src="<?php echo $this->config->item('plugins'); ?>fancybox/jquery.fancybox-media.js?v=1.0.6"></script>

<link rel="stylesheet" href="<?php echo $this->config->item('plugins'); ?>fancybox/jquery.fancybox-thumbs.css?v=1.0.7" type="text/css" media="screen" />
<script type="text/javascript" src="<?php echo $this->config->item('plugins'); ?>fancybox/jquery.fancybox-thumbs.js?v=1.0.7"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $(".fancybox").fancybox({
    		openEffect	: 'none',
    		closeEffect	: 'none'
    	});
    })
</script>