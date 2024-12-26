<div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="background-color: #fff">
            <div class="modal-header">
                <button type="button" class="close btn-sm" data-dismiss="modal" aria-hidden="true">x</button>
                <h4 class="modal-title custom_align text-danger" id="titles"><i class="fa fa-warning"></i> Attention</h4>
            </div>
            <form action="" method="post" id="remove-form">
                {!! csrf_field() !!}

                <input name="_method" type="hidden" id="method" value="DELETE">

                <div class="modal-body">
                    <div class="">
                        <i class="fa fa-info" style="padding-right: 10px"></i>
                        <span id="message"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <br>
                    <button type="submit" class="btn btn-flat ladda-button btn-success btn-sm send-request" data-style="zoom-in">
                        <span class="ladda-label"><span class="fa fa-check"></span> Yes</span>
                        <span class="ladda-spinner"><div class="ladda-progress" style="width: 0px;"></div></span></button>
                    <button type="button" class="btn btn-flat btn-default btn-sm" data-dismiss="modal"><span class="fa fa-times"></span> No</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>